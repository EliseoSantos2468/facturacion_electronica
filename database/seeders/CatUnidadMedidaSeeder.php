<?php

namespace Database\Seeders;

use App\Models\CatUnidadMedida;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-014 - Unidad de medida.
 *
 * Catálogo grande (~99 filas) cargado desde database/seeders/data/unidades_medida.json.
 * Ver database/seeders/data/README.md para completarlo con el Anexo oficial del MH.
 */
class CatUnidadMedidaSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/unidades_medida.json');
        $unidades = json_decode(file_get_contents($path), true) ?? [];

        foreach ($unidades as $unidad) {
            CatUnidadMedida::updateOrCreate(
                ['unidad_codigo' => $unidad['unidad_codigo']],
                ['nombre_unidad' => $unidad['nombre_unidad']]
            );
        }
    }
}
