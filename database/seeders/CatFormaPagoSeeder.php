<?php

namespace Database\Seeders;

use App\Models\CatFormaPago;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-017 - Forma de pago.
 * Códigos verificados contra el Manual de Programador DTE v3.
 */
class CatFormaPagoSeeder extends Seeder
{
    public function run(): void
    {
        $formas = [
            ['01', 'Billetes y monedas'],
            ['02', 'Tarjeta Débito'],
            ['03', 'Tarjeta Crédito'],
            ['04', 'Cheque'],
            ['05', 'Transferencia_Depósito Bancario'],
            ['08', 'Dinero electrónico'],
            ['09', 'Monedero electrónico'],
            ['11', 'Bitcoin'],
            ['12', 'Otras Criptomonedas'],
            ['13', 'Cuentas por pagar del receptor'],
            ['14', 'Giro bancario'],
            ['99', 'Otros (se debe indicar el medio de pago)'],
        ];

        foreach ($formas as [$codigo, $descripcion]) {
            CatFormaPago::updateOrCreate(
                ['forma_pago_codigo' => $codigo],
                ['descripcion' => $descripcion]
            );
        }
    }
}
