<?php

/*
|--------------------------------------------------------------------------
| Ministerio de Hacienda — Facturación Electrónica (SV)
|--------------------------------------------------------------------------
|
| Endpoints, timeouts y credenciales del sistema DTE del MH.
| Los defaults reflejan las URLs publicadas en el Manual del Sistema de
| Transmisión v3. No modificar en producción sin verificar contra el manual
| vigente publicado por el MH.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Ambiente activo
    |--------------------------------------------------------------------------
    |
    | Cadena de dos caracteres según CAT-010: "00" = pruebas, "01" = producción.
    | Se usa como default cuando un DTE se emite sin especificar ambiente.
    |
    */
    'ambiente' => env('MH_AMBIENTE', '00'),

    /*
    |--------------------------------------------------------------------------
    | Endpoints por ambiente
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        '00' => [
            'base' => env('MH_URL_PRUEBAS', 'https://apitest.dtes.mh.gob.sv'),
            'auth' => '/seguridad/auth',
            'recepcion' => '/fesv/recepciondte/',
            'recepcion_lote' => '/fesv/recepcionlote/',
            'anulacion' => '/fesv/anulardte/',
            'contingencia' => '/fesv/contingencia/',
            'consulta' => '/fesv/dtes/consultardte/',
        ],
        '01' => [
            'base' => env('MH_URL_PRODUCCION', 'https://api.dtes.mh.gob.sv'),
            'auth' => '/seguridad/auth',
            'recepcion' => '/fesv/recepciondte/',
            'recepcion_lote' => '/fesv/recepcionlote/',
            'anulacion' => '/fesv/anulardte/',
            'contingencia' => '/fesv/contingencia/',
            'consulta' => '/fesv/dtes/consultardte/',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP client
    |--------------------------------------------------------------------------
    */
    'http' => [
        'timeout' => env('MH_HTTP_TIMEOUT', 30),
        'connect_timeout' => env('MH_HTTP_CONNECT_TIMEOUT', 10),
        'retries' => env('MH_HTTP_RETRIES', 2),
        'retry_delay_ms' => env('MH_HTTP_RETRY_DELAY_MS', 1500),
        'user_agent' => env('MH_HTTP_USER_AGENT', 'facturacion-electronica-sv/1.0'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache del token de autenticación
    |--------------------------------------------------------------------------
    |
    | El MH devuelve un token con expiración; para no reautenticar en cada
    | transmisión se cachea. TTL en minutos, seguro por debajo del real.
    |
    */
    'token_cache' => [
        'store' => env('MH_TOKEN_CACHE_STORE'),   // null = default cache
        'ttl_minutes' => env('MH_TOKEN_TTL_MINUTES', 55),
        'prefix' => 'mh:token:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Firmador Java del MH
    |--------------------------------------------------------------------------
    |
    | El MH provee un firmador (svfe-api-firmador-*.jar) que debe correr como
    | servicio HTTP local. Los defaults usan localhost:8113 (puerto documentado).
    |
    */
    'firmador' => [
        'base_url' => env('MH_FIRMADOR_URL', 'http://localhost:8113'),
        'firmar_path' => env('MH_FIRMADOR_PATH', '/firmardocumento/'),
        'timeout' => env('MH_FIRMADOR_TIMEOUT', 20),
    ],

];
