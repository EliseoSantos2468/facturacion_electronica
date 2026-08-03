<?php

namespace App\Services\Dte\Builders;

use App\Enums\Ambiente;
use App\Enums\ModeloFacturacion;
use App\Enums\TipoDte;
use App\Enums\TipoTransmision;
use App\Models\Emisor;
use App\Models\Receptor;
use App\Services\Dte\Contracts\DteBuilder;
use DateTimeImmutable;

/**
 * Base compartida entre los builders concretos.
 * Encapsula la construcción de los nodos `identificacion`, `emisor` y `receptor`
 * que son comunes (con pequeñas variaciones) a todos los tipos de DTE.
 */
abstract class BaseDteBuilder implements DteBuilder
{
    public function __construct(
        protected readonly Emisor $emisor,
        protected readonly ?Receptor $receptor,
        protected readonly string $codigoGeneracion,
        protected readonly string $numeroControl,
        protected readonly Ambiente $ambiente,
        protected readonly ModeloFacturacion $modeloFacturacion,
        protected readonly TipoTransmision $tipoTransmision,
        protected readonly DateTimeImmutable $emitidoEn,
    ) {
    }

    abstract public function tipoDte(): TipoDte;

    /**
     * @return array<string,mixed>
     */
    protected function nodoIdentificacion(): array
    {
        return [
            'version' => $this->tipoDte()->version(),
            'ambiente' => $this->ambiente->value,
            'tipoDte' => $this->tipoDte()->value,
            'numeroControl' => $this->numeroControl,
            'codigoGeneracion' => $this->codigoGeneracion,
            'tipoModelo' => $this->modeloFacturacion->value,
            'tipoOperacion' => $this->tipoTransmision->value,
            'tipoContingencia' => null,
            'motivoContin' => null,
            'fecEmi' => $this->emitidoEn->format('Y-m-d'),
            'horEmi' => $this->emitidoEn->format('H:i:s'),
            'tipoMoneda' => 'USD',
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function nodoEmisor(): array
    {
        return [
            'nit' => $this->digitos($this->emisor->nit),
            'nrc' => $this->digitos($this->emisor->nrc),
            'nombre' => $this->emisor->nombre_razon_social,
            'codActividad' => $this->emisor->actividad_economica_codigo,
            'descActividad' => $this->emisor->relationLoaded('actividadEconomica')
                ? optional($this->emisor->actividadEconomica)->descripcion_actividad
                : null,
            'nombreComercial' => $this->emisor->nombre_comercial,
            'tipoEstablecimiento' => $this->emisor->establecimiento_codigo,
            'direccion' => [
                'departamento' => $this->emisor->departamento_codigo,
                'municipio' => $this->emisor->municipio_codigo,
                'complemento' => $this->emisor->direccion_complemento,
            ],
            'telefono' => $this->emisor->telefono,
            'correo' => $this->emisor->correo_electronico,
            'codEstableMH' => $this->emisor->establecimiento_codigo,
            'codEstable' => null,
            'codPuntoVentaMH' => $this->emisor->punto_venta_codigo,
            'codPuntoVenta' => null,
        ];
    }

    /**
     * @return array<string,mixed>|null
     */
    protected function nodoReceptor(): ?array
    {
        if ($this->receptor === null) {
            return null;
        }

        return [
            'tipoDocumento' => $this->receptor->tipo_documento_codigo,
            'numDocumento' => $this->receptor->numero_documento,
            'nrc' => $this->digitos($this->receptor->nrc),
            'nombre' => $this->receptor->nombre_razon_social,
            'codActividad' => $this->receptor->actividad_economica_codigo,
            'descActividad' => $this->receptor->relationLoaded('actividadEconomica')
                ? optional($this->receptor->actividadEconomica)->descripcion_actividad
                : null,
            'direccion' => $this->receptor->departamento_codigo === null
                ? null
                : [
                    'departamento' => $this->receptor->departamento_codigo,
                    'municipio' => $this->receptor->municipio_codigo,
                    'complemento' => null,
                ],
            'telefono' => null,
            'correo' => $this->receptor->correo_electronico,
        ];
    }

    /**
     * Elimina cualquier caracter no numérico (para NIT y NRC almacenados con guiones).
     */
    protected function digitos(?string $valor): ?string
    {
        if ($valor === null) {
            return null;
        }

        $limpio = preg_replace('/\D+/', '', $valor);

        return $limpio === '' ? null : $limpio;
    }
}
