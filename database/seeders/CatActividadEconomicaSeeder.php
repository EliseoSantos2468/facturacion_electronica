<?php

namespace Database\Seeders;

use App\Models\CatActividadEconomica;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-019 - Actividad económica (CIIU).
 *
 * Catálogo muy grande cargado desde database/seeders/data/actividades_economicas.json.
 * Viene vacío por defecto: ver database/seeders/data/README.md para completarlo
 * con el Anexo oficial del MH.
 */
class CatActividadEconomicaSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/actividades_economicas.json');
        $actividades = json_decode(file_get_contents($path), true) ?? [];

        foreach ($actividades as $actividad) {
            CatActividadEconomica::updateOrCreate(
                ['codigo_actividad' => $actividad['codigo_actividad']],
                ['descripcion_actividad' => $actividad['descripcion_actividad']]
            );
        }
    }
}
