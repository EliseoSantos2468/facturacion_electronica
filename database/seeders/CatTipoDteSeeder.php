<?php

namespace Database\Seeders;

use App\Models\CatTipoDte;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-002 - Tipo de Documento Tributario Electrónico.
 */
class CatTipoDteSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['01', 'Factura'],
            ['03', 'Comprobante de Crédito Fiscal'],
            ['04', 'Nota de Remisión'],
            ['05', 'Nota de Crédito'],
            ['06', 'Nota de Débito'],
            ['07', 'Comprobante de Retención'],
            ['08', 'Comprobante de Liquidación'],
            ['09', 'Documento Contable de Liquidación'],
            ['11', 'Factura de Exportación'],
            ['14', 'Factura de Sujeto Excluido'],
            ['15', 'Comprobante de Donación'],
        ];

        foreach ($tipos as [$codigo, $nombre]) {
            CatTipoDte::updateOrCreate(
                ['codigo_tipo_documento' => $codigo],
                ['nombre_tipo_documento' => $nombre]
            );
        }
    }
}
