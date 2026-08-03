<?php

namespace Database\Seeders;

use App\Models\ClasificacionTributaria;
use Illuminate\Database\Seeder;

/**
 * Clasificación tributaria interna de productos (Gravado / Exento / No sujeto).
 */
class ClasificacionTributariaSeeder extends Seeder
{
    public function run(): void
    {
        $clasificaciones = [
            'Gravado',
            'Exento',
            'No sujeto',
        ];

        foreach ($clasificaciones as $nombre) {
            ClasificacionTributaria::updateOrCreate(['nombre_clasificacion' => $nombre]);
        }
    }
}
