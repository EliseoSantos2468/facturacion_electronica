<?php

namespace App\Services\Dte\Builders;

use App\Enums\Ambiente;
use App\Enums\CondicionOperacion;
use App\Enums\ModeloFacturacion;
use App\Enums\TipoDte;
use App\Enums\TipoTransmision;
use App\Models\Emisor;
use App\Models\Receptor;
use App\Services\Dte\Support\MontoEnLetras;
use DateTimeImmutable;

/**
 * Builder de la Factura Electrónica (CAT-002 código 01), esquema v1.
 *
 * Uso:
 *   $factura = new FacturaBuilder($emisor, $receptor, $codigoGen, $numeroControl, ...);
 *   $factura->agregarItem(...);
 *   $factura->agregarPago(codigo: '01', monto: 100.00);
 *   $payload = $factura->build();
 */
class FacturaBuilder extends BaseDteBuilder
{
    /** @var array<int,array<string,mixed>> */
    private array $items = [];

    /** @var array<int,array<string,mixed>> */
    private array $pagos = [];

    /** @var array<int,array<string,mixed>> */
    private array $documentosRelacionados = [];

    private CondicionOperacion $condicionOperacion = CondicionOperacion::Contado;

    private ?string $observaciones = null;

    public function __construct(
        Emisor $emisor,
        ?Receptor $receptor,
        string $codigoGeneracion,
        string $numeroControl,
        Ambiente $ambiente,
        DateTimeImmutable $emitidoEn,
        ModeloFacturacion $modeloFacturacion = ModeloFacturacion::Previo,
        TipoTransmision $tipoTransmision = TipoTransmision::Normal,
    ) {
        parent::__construct(
            $emisor,
            $receptor,
            $codigoGeneracion,
            $numeroControl,
            $ambiente,
            $modeloFacturacion,
            $tipoTransmision,
            $emitidoEn,
        );
    }

    public function tipoDte(): TipoDte
    {
        return TipoDte::Factura;
    }

    public function condicionOperacion(CondicionOperacion $condicion): self
    {
        $this->condicionOperacion = $condicion;

        return $this;
    }

    public function observaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Agrega un ítem al cuerpo del documento.
     *
     * $tipoItem: CAT-011 (1=Bienes, 2=Servicios, 3=Ambos, 4=Otros/tributos).
     * $ventas* : monto del ítem en cada clasificación tributaria (usar solo la aplicable).
     */
    public function agregarItem(
        string $codigo,
        string $descripcion,
        float $cantidad,
        string $unidadMedida,
        float $precioUnitario,
        float $ventasGravadas = 0.0,
        float $ventasExentas = 0.0,
        float $ventasNoSujetas = 0.0,
        float $montoDescuento = 0.0,
        int $tipoItem = 1,
        string $numeroDocumento = '',
    ): self {
        $this->items[] = [
            'numItem' => count($this->items) + 1,
            'tipoItem' => $tipoItem,
            'numeroDocumento' => $numeroDocumento !== '' ? $numeroDocumento : null,
            'cantidad' => round($cantidad, 5),
            'codigo' => $codigo,
            'codTributo' => null,
            'uniMedida' => $unidadMedida,
            'descripcion' => $descripcion,
            'precioUni' => round($precioUnitario, 2),
            'montoDescu' => round($montoDescuento, 2),
            'ventaNoSuj' => round($ventasNoSujetas, 2),
            'ventaExenta' => round($ventasExentas, 2),
            'ventaGravada' => round($ventasGravadas, 2),
            'tributos' => null,
            'psv' => 0,
            'noGravado' => 0,
        ];

        return $this;
    }

    public function agregarPago(
        string $formaPagoCodigo,
        float $monto,
        ?string $referencia = null,
        ?string $plazoCodigo = null,
        ?int $periodo = null,
    ): self {
        $this->pagos[] = [
            'codigo' => $formaPagoCodigo,
            'montoPago' => round($monto, 2),
            'referencia' => $referencia,
            'plazo' => $plazoCodigo,
            'periodo' => $periodo,
        ];

        return $this;
    }

    public function agregarDocumentoRelacionado(
        string $tipoDocumento,
        int $tipoGeneracion,
        string $numeroDocumento,
        DateTimeImmutable $fechaEmision,
    ): self {
        $this->documentosRelacionados[] = [
            'tipoDocumento' => $tipoDocumento,
            'tipoGeneracion' => $tipoGeneracion,
            'numeroDocumento' => $numeroDocumento,
            'fechaEmision' => $fechaEmision->format('Y-m-d'),
        ];

        return $this;
    }

    public function build(): array
    {
        $totalGravada = array_sum(array_column($this->items, 'ventaGravada'));
        $totalExenta = array_sum(array_column($this->items, 'ventaExenta'));
        $totalNoSujeta = array_sum(array_column($this->items, 'ventaNoSuj'));
        $totalDescuento = array_sum(array_column($this->items, 'montoDescu'));

        $subTotalVentas = round($totalGravada + $totalExenta + $totalNoSujeta, 2);
        $subTotal = round($subTotalVentas - $totalDescuento, 2);
        $montoTotalOperacion = $subTotal;
        $totalPagar = $montoTotalOperacion;

        return [
            'identificacion' => $this->nodoIdentificacion(),
            'documentoRelacionado' => $this->documentosRelacionados !== [] ? $this->documentosRelacionados : null,
            'emisor' => $this->nodoEmisor(),
            'receptor' => $this->nodoReceptor(),
            'otrosDocumentos' => null,
            'ventaTercero' => null,
            'cuerpoDocumento' => $this->items,
            'resumen' => [
                'totalNoSuj' => round($totalNoSujeta, 2),
                'totalExenta' => round($totalExenta, 2),
                'totalGravada' => round($totalGravada, 2),
                'subTotalVentas' => $subTotalVentas,
                'descuNoSuj' => 0,
                'descuExenta' => 0,
                'descuGravada' => round($totalDescuento, 2),
                'porcentajeDescuento' => 0,
                'totalDescu' => round($totalDescuento, 2),
                'tributos' => null,
                'subTotal' => $subTotal,
                'ivaRete1' => 0,
                'reteRenta' => 0,
                'montoTotalOperacion' => round($montoTotalOperacion, 2),
                'totalNoGravado' => 0,
                'totalPagar' => round($totalPagar, 2),
                'totalLetras' => MontoEnLetras::convertir($totalPagar),
                'totalIva' => 0,
                'saldoFavor' => 0,
                'condicionOperacion' => $this->condicionOperacion->value,
                'pagos' => $this->pagos !== [] ? $this->pagos : null,
                'numPagoElectronico' => null,
            ],
            'extension' => null,
            'apendice' => null,
        ];
    }
}
