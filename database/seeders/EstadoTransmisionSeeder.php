<?php

namespace Database\Seeders;

use App\Models\EstadoTransmision;
use Illuminate\Database\Seeder;

/**
 * Estados internos de transmisión de un DTE/evento hacia el MH (no es un catálogo del MH).
 */
class EstadoTransmisionSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'Pendiente',
            'Enviado',
            'Aceptado',
            'Rechazado',
            'Error de conexión',
        ];

        foreach ($estados as $descripcion) {
            EstadoTransmision::updateOrCreate(['descripcion_estado_trans' => $descripcion]);
        }
    }
}
