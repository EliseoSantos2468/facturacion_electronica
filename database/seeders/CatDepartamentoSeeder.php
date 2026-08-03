<?php

namespace Database\Seeders;

use App\Models\CatDepartamento;
use Illuminate\Database\Seeder;

class CatDepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            ['00', 'Otro (fuera de El Salvador)'],
            ['01', 'Ahuachapán'],
            ['02', 'Santa Ana'],
            ['03', 'Sonsonate'],
            ['04', 'Chalatenango'],
            ['05', 'La Libertad'],
            ['06', 'San Salvador'],
            ['07', 'Cuscatlán'],
            ['08', 'La Paz'],
            ['09', 'Cabañas'],
            ['10', 'San Vicente'],
            ['11', 'Usulután'],
            ['12', 'San Miguel'],
            ['13', 'Morazán'],
            ['14', 'La Unión'],
        ];

        foreach ($departamentos as [$codigo, $nombre]) {
            CatDepartamento::updateOrCreate(
                ['departamento_codigo' => $codigo],
                ['departamento_nombre' => $nombre]
            );
        }
    }
}
