<?php

namespace App\Services\Dte\Support;

use App\Enums\TipoDte;
use InvalidArgumentException;

/**
 * Genera el `numeroControl` del DTE exigido por el MH: 31 caracteres con formato
 *
 *     DTE-TT-EEEEPPPP-CCCCCCCCCCCCCCC
 *
 * donde:
 *   TT               = código del tipo de DTE (CAT-002), 2 chars.
 *   EEEEPPPP         = 8 chars alfanuméricos: 4 de código de establecimiento MH + 4 de punto de venta MH.
 *   CCCCCCCCCCCCCCC  = correlativo interno del emisor por tipo de DTE, 15 chars numéricos.
 */
final class NumeroControl
{
    public static function construir(
        TipoDte $tipoDte,
        string $codigoEstableMh,
        string $codigoPuntoVentaMh,
        int $correlativo,
    ): string {
        $establecimiento = self::normalizarCodigo($codigoEstableMh, 4, 'codigoEstableMh');
        $puntoVenta = self::normalizarCodigo($codigoPuntoVentaMh, 4, 'codigoPuntoVentaMh');

        if ($correlativo < 1 || $correlativo > 999_999_999_999_999) {
            throw new InvalidArgumentException(
                'El correlativo debe estar entre 1 y 999999999999999.'
            );
        }

        $correlativoStr = str_pad((string) $correlativo, 15, '0', STR_PAD_LEFT);

        return sprintf(
            'DTE-%s-%s%s-%s',
            $tipoDte->value,
            $establecimiento,
            $puntoVenta,
            $correlativoStr,
        );
    }

    private static function normalizarCodigo(string $codigo, int $longitud, string $campo): string
    {
        $codigo = strtoupper(trim($codigo));

        if ($codigo === '') {
            throw new InvalidArgumentException("El campo {$campo} no puede estar vacío.");
        }

        if (strlen($codigo) > $longitud) {
            throw new InvalidArgumentException(
                "El campo {$campo} excede {$longitud} caracteres."
            );
        }

        if (! preg_match('/^[A-Z0-9]+$/', $codigo)) {
            throw new InvalidArgumentException(
                "El campo {$campo} solo admite caracteres alfanuméricos."
            );
        }

        return str_pad($codigo, $longitud, '0', STR_PAD_LEFT);
    }
}
