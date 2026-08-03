<?php

namespace Database\Seeders;

use App\Models\CatMunicipio;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-013 - Municipio.
 *
 * Catálogo grande (~262 filas) cargado desde database/seeders/data/municipios.json.
 * Ver database/seeders/data/README.md para completarlo con el Anexo oficial del MH.
 */
class CatMunicipioSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/municipios.json');
        $municipios = json_decode(file_get_contents($path), true) ?? [];

        foreach ($municipios as $municipio) {
            CatMunicipio::updateOrCreate(
                ['municipio_codigo' => $municipio['municipio_codigo']],
                [
                    'municipio_nombre' => $municipio['municipio_nombre'],
                    'departamento_codigo' => $municipio['departamento_codigo'] ?? null,
                ]
            );
        }
    }
}
