<?php

namespace App\Services\Mh;

/**
 * DTO con la respuesta relevante del MH al recibir/procesar un DTE.
 */
final class RecepcionResult
{
    /**
     * @param  array<string,mixed>  $raw  Respuesta completa del MH.
     */
    public function __construct(
        public readonly string $estado,           // PROCESADO | RECHAZADO | ...
        public readonly ?string $selloRecepcion,
        public readonly ?string $descripcionMsg,
        public readonly ?string $codigoMsg,
        public readonly ?string $codigoGeneracion,
        public readonly int $httpStatus,
        public readonly array $raw,
    ) {
    }

    public function fueAceptado(): bool
    {
        return strtoupper($this->estado) === 'PROCESADO' && $this->selloRecepcion !== null;
    }
}
