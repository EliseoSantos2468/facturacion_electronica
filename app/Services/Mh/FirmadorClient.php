<?php

namespace App\Services\Mh;

use App\Services\Mh\Exceptions\FirmadorException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Throwable;

/**
 * Cliente HTTP del firmador Java publicado por el MH (`svfe-api-firmador-*.jar`).
 *
 * El firmador expone `POST {firmador_url}/firmardocumento/` con body:
 *   {
 *     "nit": "06140101011011",
 *     "activo": true,
 *     "passwordPri": "clave del certificado",
 *     "dteJson": { ...payload DTE... }
 *   }
 *
 * Y responde:
 *   { "status": "OK",    "body": "eyJhbGciOiJSUzUxMiJ9..."   }   ← JWS listo para transmitir
 *   { "status": "ERROR", "body": [...detalle...] }
 */
class FirmadorClient
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly array $config,
    ) {
    }

    /**
     * Firma un payload DTE y retorna el JWS resultante.
     *
     * @param  array<string,mixed>  $dtePayload  Payload construido por un DteBuilder.
     */
    public function firmar(string $nit, string $passwordPrivada, array $dtePayload): string
    {
        $url = rtrim((string) $this->config['base_url'], '/')
            . '/' . ltrim((string) $this->config['firmar_path'], '/');

        try {
            $response = $this->http
                ->timeout((int) ($this->config['timeout'] ?? 20))
                ->asJson()
                ->post($url, [
                    'nit' => $this->soloDigitos($nit),
                    'activo' => true,
                    'passwordPri' => $passwordPrivada,
                    'dteJson' => $dtePayload,
                ]);
        } catch (Throwable $e) {
            throw new FirmadorException(
                "No se pudo contactar al firmador ({$url}): {$e->getMessage()}",
                previous: $e,
            );
        }

        if (! $response->successful()) {
            throw new FirmadorException(
                "Firmador respondió HTTP {$response->status()}.",
                payload: (array) $response->json(),
            );
        }

        $body = $response->json();
        $status = $body['status'] ?? null;

        if ($status !== 'OK') {
            throw new FirmadorException(
                'Firmador reportó ERROR al firmar el DTE.',
                payload: (array) $body,
            );
        }

        $jws = $body['body'] ?? null;
        if (! is_string($jws) || $jws === '') {
            throw new FirmadorException(
                'Firmador devolvió status=OK pero sin JWS en `body`.',
                payload: (array) $body,
            );
        }

        return $jws;
    }

    private function soloDigitos(string $valor): string
    {
        return preg_replace('/\D+/', '', $valor) ?? '';
    }

}
