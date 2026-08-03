<?php

use App\Enums\Ambiente;
use App\Enums\CondicionOperacion;
use App\Enums\TipoDte;
use App\Models\Emisor;
use App\Models\Receptor;
use App\Services\Dte\Builders\FacturaBuilder;
use App\Services\Dte\Support\CodigoGeneracion;
use App\Services\Dte\Support\NumeroControl;

function makeEmisor(): Emisor
{
    return new Emisor([
        'nit' => '0614-010101-101-1',
        'nrc' => '123456',
        'nombre_razon_social' => 'Comercial Prueba, S.A. de C.V.',
        'nombre_comercial' => 'Comercial Prueba',
        'actividad_economica_codigo' => '46900',
        'departamento_codigo' => '06',
        'municipio_codigo' => '0614',
        'establecimiento_codigo' => '0001',
        'direccion_complemento' => 'Colonia Centro, San Salvador',
        'telefono' => '+503 2222-2222',
        'correo_electronico' => 'facturas@comercialprueba.sv',
        'punto_venta_codigo' => '0001',
    ]);
}

function makeReceptor(): Receptor
{
    return new Receptor([
        'tipo_documento_codigo' => '36',
        'numero_documento' => '0210-050619-102-9',
        'nombre_razon_social' => 'Ferretería La Cima, S.A.',
        'nrc' => '654321',
        'actividad_economica_codigo' => '47522',
        'departamento_codigo' => '05',
        'municipio_codigo' => '0511',
        'correo_electronico' => 'compras@lacima.sv',
    ]);
}

it('arma una Factura con nodos identificacion/emisor/receptor y totales coherentes', function () {
    $emitidoEn = new DateTimeImmutable('2026-08-02 10:24:00');
    $codigoGeneracion = CodigoGeneracion::generar();
    $numeroControl = NumeroControl::construir(TipoDte::Factura, '0001', '0001', 42);

    $builder = new FacturaBuilder(
        emisor: makeEmisor(),
        receptor: makeReceptor(),
        codigoGeneracion: $codigoGeneracion,
        numeroControl: $numeroControl,
        ambiente: Ambiente::Pruebas,
        emitidoEn: $emitidoEn,
    );

    $payload = $builder
        ->condicionOperacion(CondicionOperacion::Contado)
        ->agregarItem(
            codigo: 'SKU-001',
            descripcion: 'Servicio de consultoría',
            cantidad: 2,
            unidadMedida: '99',
            precioUnitario: 50.00,
            ventasGravadas: 100.00,
        )
        ->agregarPago(formaPagoCodigo: '01', monto: 100.00)
        ->build();

    expect($payload['identificacion']['tipoDte'])->toBe('01')
        ->and($payload['identificacion']['ambiente'])->toBe('00')
        ->and($payload['identificacion']['numeroControl'])->toBe($numeroControl)
        ->and($payload['identificacion']['codigoGeneracion'])->toBe($codigoGeneracion)
        ->and($payload['identificacion']['fecEmi'])->toBe('2026-08-02')
        ->and($payload['identificacion']['horEmi'])->toBe('10:24:00');

    expect($payload['emisor']['nit'])->toBe('06140101011011')
        ->and($payload['emisor']['nombre'])->toBe('Comercial Prueba, S.A. de C.V.');

    expect($payload['receptor']['numDocumento'])->toBe('0210-050619-102-9')
        ->and($payload['receptor']['nombre'])->toBe('Ferretería La Cima, S.A.');

    expect($payload['cuerpoDocumento'])->toHaveCount(1)
        ->and($payload['cuerpoDocumento'][0]['ventaGravada'])->toBe(100.00);

    expect($payload['resumen']['totalGravada'])->toBe(100.00)
        ->and($payload['resumen']['montoTotalOperacion'])->toBe(100.00)
        ->and($payload['resumen']['totalPagar'])->toBe(100.00)
        ->and($payload['resumen']['totalLetras'])->toBe('CIEN 00/100 DÓLARES')
        ->and($payload['resumen']['condicionOperacion'])->toBe(1)
        ->and($payload['resumen']['pagos'])->toHaveCount(1);
});

it('genera numeroControl con formato correcto (31 chars)', function () {
    $nc = NumeroControl::construir(TipoDte::Factura, '0001', '0001', 1);

    expect($nc)->toBe('DTE-01-00010001-000000000000001')
        ->and(strlen($nc))->toBe(31);
});

it('rechaza correlativo fuera de rango', function () {
    NumeroControl::construir(TipoDte::Factura, '0001', '0001', 0);
})->throws(InvalidArgumentException::class);
