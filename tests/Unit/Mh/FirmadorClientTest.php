<?php

use App\Services\Mh\Exceptions\FirmadorException;
use App\Services\Mh\FirmadorClient;
use Illuminate\Http\Client\Factory as HttpFactory;

function makeFirmador(HttpFactory $http): FirmadorClient
{
    return new FirmadorClient($http, [
        'base_url' => 'http://localhost:8113',
        'firmar_path' => '/firmardocumento/',
        'timeout' => 5,
    ]);
}

it('devuelve el JWS cuando el firmador responde status=OK', function () {
    $http = new HttpFactory();
    $http->fake([
        'localhost:8113/firmardocumento/' => $http::response([
            'status' => 'OK',
            'body' => 'eyJhbGciOiJSUzUxMiJ9.PAYLOAD.SIG',
        ], 200),
    ]);

    $jws = makeFirmador($http)->firmar('0614-010101-101-1', 'clavesecreta', ['x' => 1]);

    expect($jws)->toBe('eyJhbGciOiJSUzUxMiJ9.PAYLOAD.SIG');
});

it('lanza FirmadorException si el firmador responde ERROR', function () {
    $http = new HttpFactory();
    $http->fake([
        'localhost:8113/firmardocumento/' => $http::response([
            'status' => 'ERROR',
            'body' => ['codigo' => '999', 'mensaje' => 'Clave privada incorrecta'],
        ], 200),
    ]);

    makeFirmador($http)->firmar('0614-010101-101-1', 'malaclave', ['x' => 1]);
})->throws(FirmadorException::class, 'Firmador reportó ERROR');

it('lanza FirmadorException si status=OK pero body vacío', function () {
    $http = new HttpFactory();
    $http->fake([
        'localhost:8113/firmardocumento/' => $http::response([
            'status' => 'OK',
            'body' => '',
        ], 200),
    ]);

    makeFirmador($http)->firmar('0614-010101-101-1', 'clave', ['x' => 1]);
})->throws(FirmadorException::class, 'sin JWS');

it('lanza FirmadorException con HTTP != 2xx', function () {
    $http = new HttpFactory();
    $http->fake([
        'localhost:8113/firmardocumento/' => $http::response(['err' => 'boom'], 500),
    ]);

    makeFirmador($http)->firmar('0614-010101-101-1', 'clave', ['x' => 1]);
})->throws(FirmadorException::class, 'HTTP 500');
