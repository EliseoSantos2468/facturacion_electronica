<?php

namespace App\Services\Mh;

use App\Enums\Ambiente;
use App\Enums\TipoDte; // usado en PHPDoc del payload
use App\Models\DteDocumento;
use App\Models\DteTransmision;
use App\Services\Mh\Exceptions\MhAuthException;
use App\Services\Mh\Exceptions\MhTransmissionException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Cliente principal contra el sistema DTE del Ministerio de Hacienda.
 *
 * Cada llamada:
 *   1. Obtiene token vía TokenManager (con caché).
 *   2. Ejecuta el POST HTTP contra el endpoint del ambiente activo.
 *   3. Persiste el intento en `dte_transmisiones` (independientemente del resultado).
 *   4. Devuelve un DTO tipado o lanza MhTransmissionException.
 *
 * No firma; espera recibir el JWS ya producido por `FirmadorClient`.
 */
class MhClient
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly TokenManager $tokens,
        private readonly array $config,
    ) {
    }

    /**
     * Transmite un DTE previamente firmado (JWS) al MH.
     *
     * @param  DteDocumento  $documento    Registro en BD; se usa para logging y para relacionar el intento.
     * @param  string        $jws          JWS obtenido del firmador.
     * @param  string        $usuarioMh    Usuario del emisor en el ambiente MH.
     * @param  string        $passwordMh   Password del emisor en el ambiente MH.
     */
    public function transmitirDte(
        DteDocumento $documento,
        string $jws,
        string $usuarioMh,
        string $passwordMh,
    ): RecepcionResult {
        $ambiente = $this->ambienteDe($documento);
        $endpoint = $this->endpoint($ambiente, 'recepcion');

        /** @var TipoDte $tipoDte */
        $tipoDte = $documento->tipo_dte_codigo;

        $payload = [
            'ambiente' => $ambiente,
            'idEnvio' => (int) $documento->documento_id,
            'version' => $tipoDte->version(),
            'tipoDte' => $tipoDte->value,
            'documento' => $jws,
            'codigoGeneracion' => $documento->codigo_generacion,
        ];

        return $this->ejecutar(
            documento: $documento,
            endpoint: $endpoint,
            payload: $payload,
            ambiente: $ambiente,
            usuarioMh: $usuarioMh,
            passwordMh: $passwordMh,
        );
    }

    /**
     * Envía un evento de invalidación (anulación) al MH.
     */
    public function invalidarDte(
        DteDocumento $documento,
        string $jwsEventoInvalidacion,
        string $usuarioMh,
        string $passwordMh,
    ): RecepcionResult {
        $ambiente = $this->ambienteDe($documento);
        $endpoint = $this->endpoint($ambiente, 'anulacion');

        $payload = [
            'ambiente' => $ambiente,
            'idEnvio' => (int) $documento->documento_id,
            'version' => 2,
            'documento' => $jwsEventoInvalidacion,
        ];

        return $this->ejecutar(
            documento: $documento,
            endpoint: $endpoint,
            payload: $payload,
            ambiente: $ambiente,
            usuarioMh: $usuarioMh,
            passwordMh: $passwordMh,
        );
    }

    private function ejecutar(
        DteDocumento $documento,
        string $endpoint,
        array $payload,
        string $ambiente,
        string $usuarioMh,
        string $passwordMh,
    ): RecepcionResult {
        $token = $this->tokens->token($ambiente, $usuarioMh, $passwordMh);
        $intento = (int) DteTransmision::where('documento_id', $documento->documento_id)->max('intento') + 1;

        $response = null;
        $exception = null;

        try {
            $response = $this->http
                ->timeout((int) ($this->config['http']['timeout'] ?? 30))
                ->connectTimeout((int) ($this->config['http']['connect_timeout'] ?? 10))
                ->retry(
                    (int) ($this->config['http']['retries'] ?? 2),
                    (int) ($this->config['http']['retry_delay_ms'] ?? 1500),
                    throw: false,
                )
                ->withToken($this->soloToken($token))
                ->withHeaders(['User-Agent' => (string) ($this->config['http']['user_agent'] ?? 'facturacion-sv/1.0')])
                ->asJson()
                ->post($endpoint, $payload);
        } catch (Throwable $e) {
            $exception = $e;
        }

        $this->registrarIntento($documento, $intento, $endpoint, $payload, $response, $exception);

        // 401 → probablemente el token expiró antes del TTL configurado. Se olvida y
        // se reintenta una vez más de forma explícita (sin recursión ilimitada).
        if ($response?->status() === 401) {
            $this->tokens->olvidar($ambiente, $usuarioMh);
            throw new MhTransmissionException(
                'MH respondió 401 (token inválido). Se limpió el caché; reintente la operación.',
                payload: (array) $response->json(),
                httpStatus: 401,
            );
        }

        if ($exception !== null) {
            throw new MhTransmissionException(
                "Error de red al transmitir DTE: {$exception->getMessage()}",
                previous: $exception,
            );
        }

        return $this->interpretarRespuesta($response);
    }

    private function interpretarRespuesta(Response $response): RecepcionResult
    {
        $raw = (array) $response->json();

        $result = new RecepcionResult(
            estado: (string) ($raw['estado'] ?? 'DESCONOCIDO'),
            selloRecepcion: $raw['selloRecibido'] ?? $raw['sello'] ?? null,
            descripcionMsg: $raw['descripcionMsg'] ?? null,
            codigoMsg: $raw['codigoMsg'] ?? null,
            codigoGeneracion: $raw['codigoGeneracion'] ?? null,
            httpStatus: $response->status(),
            raw: $raw,
        );

        if (! $response->successful() || ! $result->fueAceptado()) {
            throw new MhTransmissionException(
                sprintf(
                    'MH rechazó el DTE (estado=%s, cod=%s): %s',
                    $result->estado,
                    $result->codigoMsg ?? '?',
                    $result->descripcionMsg ?? 'sin descripción',
                ),
                payload: $raw,
                httpStatus: $response->status(),
            );
        }

        return $result;
    }

    private function registrarIntento(
        DteDocumento $documento,
        int $intento,
        string $endpoint,
        array $payload,
        ?Response $response,
        ?Throwable $exception,
    ): void {
        DB::transaction(fn () => DteTransmision::create([
            'documento_id' => $documento->documento_id,
            'intento' => $intento,
            'endpoint' => $endpoint,
            'http_status' => $response?->status(),
            'mensaje' => $exception?->getMessage()
                ?? ($response?->json('descripcionMsg') ?? null),
            'request_payload' => $payload,
            'response_payload' => $response?->json(),
            'procesado_en' => now(),
        ]));
    }

    private function ambienteDe(DteDocumento $documento): string
    {
        $ambiente = $documento->ambiente;

        return $ambiente instanceof Ambiente
            ? $ambiente->value
            : (string) ($ambiente ?? $this->config['ambiente']);
    }

    private function endpoint(string $ambiente, string $tipo): string
    {
        $endpoint = $this->config['endpoints'][$ambiente]
            ?? throw new MhAuthException("Ambiente MH desconocido: {$ambiente}.");

        return rtrim($endpoint['base'], '/') . $endpoint[$tipo];
    }

    /**
     * El MH devuelve el token con prefijo "Bearer ". Se lo quitamos porque
     * withToken() ya lo antepone.
     */
    private function soloToken(string $token): string
    {
        return preg_replace('/^Bearer\s+/i', '', trim($token)) ?? $token;
    }
}
