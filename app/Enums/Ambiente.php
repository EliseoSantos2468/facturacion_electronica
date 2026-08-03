<?php

namespace App\Enums;

/**
 * Ambiente de destino MH (CAT-010).
 * El código se envía tal cual en el campo `ambiente` del JSON DTE.
 */
enum Ambiente: string
{
    case Pruebas = '00';
    case Produccion = '01';

    public function label(): string
    {
        return match ($this) {
            self::Pruebas => 'Pruebas',
            self::Produccion => 'Producción',
        };
    }
}
