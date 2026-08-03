<?php

use App\Services\Mh\Exceptions\MhAuthException;
use App\Services\Mh\TokenManager;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Http\Client\Factory as HttpFactory;

function makeTokenManager(HttpFactory $http, CacheRepository $cache): TokenManager
{
    return new TokenManager($http, $cache, [
        'endpoints' => [
            '00' => [
                'base' => 'https://apitest.dtes.mh.gob.sv',
                'auth' => '/seguridad/auth',
            ],
        ],
        'http' => ['timeout' => 5, 'connect_timeout' => 3, 'user_agent' => 'test/1.0'],
        'token_cache' => ['ttl_minutes' => 30, 'prefix' => 'mh:token:'],
    ]);
}

it('autentica y devuelve el token cuando MH responde OK', function () {
    $http = new HttpFactory();
    $http->fake([
        'apitest.dtes.mh.gob.sv/seguridad/auth' => $http::response([
            'status' => 'OK',
            'body' => ['token' => 'Bearer eyJhbGciOiJIUzI1NiJ9.tok'],
        ], 200),
    ]);
    $cache = new CacheRepository(new ArrayStore());

    $token = makeTokenManager($http, $cache)->token('00', 'usuario', 'pass');

    expect($token)->toBe('Bearer eyJhbGciOiJIUzI1NiJ9.tok');
});

it('usa el token cacheado en la segunda llamada', function () {
    $http = new HttpFactory();
    $http->fake([
        'apitest.dtes.mh.gob.sv/seguridad/auth' => $http::response([
            'status' => 'OK', 'body' => ['token' => 'tok-1'],
        ], 200),
    ]);
    $cache = new CacheRepository(new ArrayStore());
    $mgr = makeTokenManager($http, $cache);

    $mgr->token('00', 'usuario', 'pass');
    $mgr->token('00', 'usuario', 'pass');
    $mgr->token('00', 'usuario', 'pass');

    // Solo una llamada HTTP a pesar de las 3 invocaciones.
    $http->assertSentCount(1);
});

it('lanza MhAuthException con HTTP != 2xx', function () {
    $http = new HttpFactory();
    $http->fake([
        'apitest.dtes.mh.gob.sv/seguridad/auth' => $http::response(['err' => 'invalid'], 401),
    ]);
    $cache = new CacheRepository(new ArrayStore());

    makeTokenManager($http, $cache)->token('00', 'usuario', 'mala');
})->throws(MhAuthException::class);

it('lanza MhAuthException si la respuesta no trae token', function () {
    $http = new HttpFactory();
    $http->fake([
        'apitest.dtes.mh.gob.sv/seguridad/auth' => $http::response([
            'status' => 'OK', 'body' => ['algo' => 'raro'],
        ], 200),
    ]);
    $cache = new CacheRepository(new ArrayStore());

    makeTokenManager($http, $cache)->token('00', 'usuario', 'pass');
})->throws(MhAuthException::class, 'sin token');
