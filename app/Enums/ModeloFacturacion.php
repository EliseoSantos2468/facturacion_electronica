<?php

namespace App\Enums;

/**
 * Modelo de facturación (CAT-008).
 * Previo: se transmite antes de entregar el documento al receptor.
 * Diferido: se transmite después (contingencia u operación fuera de línea).
 */
enum ModeloFacturacion: int
{
    case Previo = 1;
    case Diferido = 2;

    public function label(): string
    {
        return match ($this) {
            self::Previo => 'Previo',
            self::Diferido => 'Diferido',
        };
    }
}
