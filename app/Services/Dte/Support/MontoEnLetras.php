<?php

namespace App\Services\Dte\Support;

use InvalidArgumentException;

/**
 * Convierte un monto en USD a su expresión en letras usada en el campo
 * `totalLetras` del DTE. Formato:
 *
 *     "MIL DOSCIENTOS TREINTA Y CUATRO 56/100 DÓLARES"
 *
 * Soporta hasta 999,999,999.99.
 */
final class MontoEnLetras
{
    private const UNIDADES = [
        '', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
        'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS',
        'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE', 'VEINTE',
    ];

    private const DECENAS = [
        20 => 'VEINTI', 30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA',
        60 => 'SESENTA', 70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA',
    ];

    private const CENTENAS = [
        100 => 'CIENTO', 200 => 'DOSCIENTOS', 300 => 'TRESCIENTOS', 400 => 'CUATROCIENTOS',
        500 => 'QUINIENTOS', 600 => 'SEISCIENTOS', 700 => 'SETECIENTOS',
        800 => 'OCHOCIENTOS', 900 => 'NOVECIENTOS',
    ];

    public static function convertir(float|string $monto): string
    {
        $monto = round((float) $monto, 2);

        if ($monto < 0) {
            throw new InvalidArgumentException('El monto no puede ser negativo.');
        }

        if ($monto > 999_999_999.99) {
            throw new InvalidArgumentException('El monto excede el máximo soportado (999,999,999.99).');
        }

        $enteros = (int) floor($monto);
        $centavos = (int) round(($monto - $enteros) * 100);

        $letras = self::numeroAletras($enteros);
        $centavosStr = str_pad((string) $centavos, 2, '0', STR_PAD_LEFT);

        return sprintf('%s %s/100 DÓLARES', $letras, $centavosStr);
    }

    private static function numeroAletras(int $n): string
    {
        if ($n === 0) {
            return 'CERO';
        }

        $millones = intdiv($n, 1_000_000);
        $resto = $n % 1_000_000;

        $partes = [];

        if ($millones > 0) {
            $partes[] = $millones === 1
                ? 'UN MILLÓN'
                : self::centenaEnLetras($millones) . ' MILLONES';
        }

        $miles = intdiv($resto, 1000);
        $unidades = $resto % 1000;

        if ($miles > 0) {
            $partes[] = $miles === 1
                ? 'MIL'
                : self::centenaEnLetras($miles) . ' MIL';
        }

        if ($unidades > 0) {
            $partes[] = self::centenaEnLetras($unidades);
        }

        return trim(implode(' ', $partes));
    }

    /**
     * Convierte un número de 1 a 999 a letras.
     */
    private static function centenaEnLetras(int $n): string
    {
        if ($n <= 20) {
            return self::UNIDADES[$n];
        }

        if ($n === 100) {
            return 'CIEN';
        }

        $centenas = intdiv($n, 100) * 100;
        $resto = $n % 100;

        $texto = '';
        if ($centenas > 0) {
            $texto .= self::CENTENAS[$centenas];
        }

        if ($resto > 0) {
            $texto .= ($centenas > 0 ? ' ' : '') . self::decenaEnLetras($resto);
        }

        return trim($texto);
    }

    private static function decenaEnLetras(int $n): string
    {
        if ($n <= 20) {
            return self::UNIDADES[$n];
        }

        $decena = intdiv($n, 10) * 10;
        $unidad = $n % 10;

        if ($decena === 20) {
            return $unidad === 0 ? 'VEINTE' : 'VEINTI' . self::UNIDADES[$unidad];
        }

        if ($unidad === 0) {
            return self::DECENAS[$decena];
        }

        return self::DECENAS[$decena] . ' Y ' . self::UNIDADES[$unidad];
    }
}
