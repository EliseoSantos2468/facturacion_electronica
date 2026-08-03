<?php

namespace Database\Seeders;

use App\Models\CatCondicionOperacion;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-016 - Condición de la operación.
 */
class CatCondicionOperacionSeeder extends Seeder
{
    public function run(): void
    {
        $condiciones = [
            ['1', 'Contado'],
            ['2', 'A crédito'],
            ['3', 'Otro'],
        ];

        foreach ($condiciones as [$codigo, $descripcion]) {
            CatCondicionOperacion::updateOrCreate(
                ['condicion_codigo' => $codigo],
                ['descripcion' => $descripcion]
            );
        }
    }
}
