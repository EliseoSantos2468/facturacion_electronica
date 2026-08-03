<?php

use App\Services\Dte\Support\MontoEnLetras;

it('convierte montos comunes a letras', function (float $monto, string $esperado) {
    expect(MontoEnLetras::convertir($monto))->toBe($esperado);
})->with([
    [0,          'CERO 00/100 DÓLARES'],
    [1,          'UNO 00/100 DÓLARES'],
    [15,         'QUINCE 00/100 DÓLARES'],
    [21,         'VEINTIUNO 00/100 DÓLARES'],
    [45.50,      'CUARENTA Y CINCO 50/100 DÓLARES'],
    [100,        'CIEN 00/100 DÓLARES'],
    [101,        'CIENTO UNO 00/100 DÓLARES'],
    [999.99,     'NOVECIENTOS NOVENTA Y NUEVE 99/100 DÓLARES'],
    [1000,       'MIL 00/100 DÓLARES'],
    [1234.56,    'MIL DOSCIENTOS TREINTA Y CUATRO 56/100 DÓLARES'],
    [1000000,    'UN MILLÓN 00/100 DÓLARES'],
    [2500000.01, 'DOS MILLONES QUINIENTOS MIL 01/100 DÓLARES'],
]);

it('rechaza montos negativos', function () {
    MontoEnLetras::convertir(-1);
})->throws(InvalidArgumentException::class);

it('rechaza montos por encima del máximo soportado', function () {
    MontoEnLetras::convertir(1_000_000_000);
})->throws(InvalidArgumentException::class);
