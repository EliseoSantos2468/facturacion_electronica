<?php

namespace App\Enums;

/**
 * Tipo de transmisión (CAT-007).
 * Normal: transmisión individual en línea.
 * Contingencia: transmisión por lote tras un evento de contingencia.
 */
enum TipoTransmision: int
{
    case Normal = 1;
    case Contingencia = 2;

    public function label(): string
    {
        return match ($this) {
            self::Normal => 'Normal',
            self::Contingencia => 'Contingencia',
        };
    }
}
