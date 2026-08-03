<?php

namespace App\Enums;

/**
 * Condición de la operación (CAT-016).
 */
enum CondicionOperacion: int
{
    case Contado = 1;
    case Credito = 2;
    case Otro = 3;

    public function label(): string
    {
        return match ($this) {
            self::Contado => 'Contado',
            self::Credito => 'A crédito',
            self::Otro => 'Otro',
        };
    }
}
