<?php

namespace Database\Seeders;

use App\Models\EstadoDte;
use Illuminate\Database\Seeder;

/**
 * Estados internos del ciclo de vida de un DTE (no es un catálogo del MH).
 */
class EstadoDteSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'Borrador',
            'Firmado',
            'Transmitido',
            'Rechazado',
            'Invalidado',
            'Contingencia',
        ];

        foreach ($estados as $nombre) {
            EstadoDte::updateOrCreate(['nombre_estado' => $nombre]);
        }
    }
}
