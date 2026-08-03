<?php

namespace App\Services\Dte\Support;

use Illuminate\Support\Str;

/**
 * Genera el `codigoGeneracion` del DTE: UUID v4 en mayúsculas.
 * El MH lo exige en formato UUID canónico (8-4-4-4-12) y en mayúsculas.
 */
final class CodigoGeneracion
{
    public static function generar(): string
    {
        return strtoupper((string) Str::uuid());
    }
}
