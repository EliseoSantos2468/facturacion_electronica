<?php

namespace Database\Seeders;

use App\Models\CatEstablecimiento;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-009 - Tipo de establecimiento.
 *
 * NOTA: solo se incluyen los códigos de mayor certeza (Casa Matriz / Sucursal).
 * Antes de emitir en producción, completar/validar el resto de códigos contra
 * el Anexo vigente publicado por el Ministerio de Hacienda.
 */
class CatEstablecimientoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['01', 'Casa Matriz'],
            ['02', 'Sucursal / Agencia'],
        ];

        foreach ($tipos as [$codigo, $nombre]) {
            CatEstablecimiento::updateOrCreate(
                ['establecimiento_codigo' => $codigo],
                ['establecimiento_nombre' => $nombre]
            );
        }
    }
}
