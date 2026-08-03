<?php

namespace Database\Seeders;

use App\Models\CatMotivoInvalidacion;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-028 - Tipo de invalidación de un DTE.
 * La PK es autoincremental; se inserta en el mismo orden que el catálogo oficial
 * para que el id coincida con el código MH (1, 2, 3).
 */
class CatMotivoInvalidacionSeeder extends Seeder
{
    public function run(): void
    {
        $motivos = [
            'Error en la información del documento tributario electrónico (genera reemplazo)',
            'Rescindir de la operación realizada',
            'Otro',
        ];

        foreach ($motivos as $descripcion) {
            CatMotivoInvalidacion::updateOrCreate(
                ['descripcion_motivo' => $descripcion]
            );
        }
    }
}
