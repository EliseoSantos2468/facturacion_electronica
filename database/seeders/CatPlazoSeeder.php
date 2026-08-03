<?php

namespace Database\Seeders;

use App\Models\CatPlazo;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-018 - Plazo (unidad temporal para operaciones a crédito).
 */
class CatPlazoSeeder extends Seeder
{
    public function run(): void
    {
        $plazos = [
            ['01', 'Días'],
            ['02', 'Meses'],
            ['03', 'Años'],
        ];

        foreach ($plazos as [$codigo, $descripcion]) {
            CatPlazo::updateOrCreate(
                ['plazo_codigo' => $codigo],
                ['descripcion' => $descripcion]
            );
        }
    }
}
