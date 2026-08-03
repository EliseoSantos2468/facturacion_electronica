<?php

namespace Database\Seeders;

use App\Models\CatTipoReceptor;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-025 - Tipo de persona (natural / jurídica) del receptor.
 */
class CatTipoReceptorSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['1', 'Persona Natural'],
            ['2', 'Persona Jurídica'],
        ];

        foreach ($tipos as [$codigo, $nombre]) {
            CatTipoReceptor::updateOrCreate(
                ['tipo_receptor_codigo' => $codigo],
                ['nombre' => $nombre]
            );
        }
    }
}
