<?php

namespace App\Services\Mh;

use App\Services\Mh\Exceptions\MhAuthException;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Client\Factory as HttpFactory;
use Throwable;

/**
 * Obtiene y mantiene en caché el token JWT del MH necesario para transmitir DTE.
 *
 * El endpoint `/seguridad/auth` responde 200 con:
 *   { "status": "OK", "body": { "token": "Bearer eyJ..." }, ... }
 * o 401/400 en caso de credenciales inválidas.
 */
class TokenManager
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly CacheRepository $cache,
        private readonly array $config,
    ) {
    }

    /**
     * Retorna el token vigente (o lo solicita al MH si no hay en caché).
     * $usuario y $password son las credenciales del emisor ante el MH (no las de la app).
     */
    public function token(string $ambiente, string $usuario, string $password): string
    {
        $key = $this->cacheKey($ambiente, $usuario);

        $cached = $this->cache->get($key);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $token = $this->autenticar($ambiente, $usuario, $password);

        $this->cache->put(
            $key,
            $token,
            now()->addMinutes((int) ($this->config['token_cache']['ttl_minutes'] ?? 55)),
        );

        return $token;
    }

    /**
     * Fuerza la invalidación del token cacheado (útil ante 401 durante una transmisión).
     */
    public function olvidar(string $ambiente, string $usuario): void
    {
        $this->cache->forget($this->cacheKey($ambiente, $usuario));
    }

    private function autenticar(string $ambiente, string $usuario, string $password): string
    {
        $endpoint = $this->config['endpoints'][$ambiente] ?? null;
        if ($endpoint === null) {
            throw new MhAuthException("Ambiente MH desconocido: {$ambiente}.");
        }

        try {
            $response = $this->http
                ->timeout((int) ($this->config['http']['timeout'] ?? 30))
                ->connectTimeout((int) ($this->config['http']['connect_timeout'] ?? 10))
                ->withHeaders(['User-Agent' => (string) ($this->config['http']['user_agent'] ?? 'facturacion-sv/1.0')])
                ->asForm()
                ->post($endpoint['base'] . $endpoint['auth'], [
                    'user' => $usuario,
                    'pwd' => $password,
                ]);
        } catch (Throwable $e) {
            throw new MhAuthException(
                "Error de red autenticando contra el MH: {$e->getMessage()}",
                previous: $e,
            );
        }

        if (! $response->successful()) {
            throw new MhAuthException(
                "MH respondió {$response->status()} al autenticar (usuario={$usuario})."
            );
        }

        $payload = $response->json();
        $token = $payload['body']['token'] ?? null;

        if (! is_string($token) || $token === '') {
            throw new MhAuthException(
                'Respuesta del MH sin token en body.token: ' . $response->body()
            );
        }

        return $token;
    }

    private function cacheKey(string $ambiente, string $usuario): string
    {
        $prefix = (string) ($this->config['token_cache']['prefix'] ?? 'mh:token:');

        return $prefix . $ambiente . ':' . sha1($usuario);
    }
}
