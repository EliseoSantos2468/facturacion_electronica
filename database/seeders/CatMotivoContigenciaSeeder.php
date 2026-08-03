<?php

namespace Database\Seeders;

use App\Models\CatMotivoContigencia;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-005 - Motivo o tipo de contingencia.
 */
class CatMotivoContigenciaSeeder extends Seeder
{
    public function run(): void
    {
        $motivos = [
            ['1', 'No disponibilidad de sistema del MH'],
            ['2', 'No disponibilidad de internet del emisor'],
            ['3', 'Falla en el suministro de energía eléctrica del emisor'],
            ['4', 'Falla en el sistema del emisor'],
            ['5', 'Otro'],
        ];

        foreach ($motivos as [$codigo, $descripcion]) {
            CatMotivoContigencia::updateOrCreate(
                ['m_contingencia_codigo' => $codigo],
                ['descripcion_motivo' => $descripcion]
            );
        }
    }
}
