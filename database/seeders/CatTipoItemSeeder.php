<?php

namespace Database\Seeders;

use App\Models\CatTipoItem;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-011 - Tipo de ítem.
 */
class CatTipoItemSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['1', 'Bienes'],
            ['2', 'Servicios'],
            ['3', 'Bienes y Servicios'],
            ['4', 'Otros (Tributos / impuestos)'],
        ];

        foreach ($tipos as [$codigo, $descripcion]) {
            CatTipoItem::updateOrCreate(
                ['item_codigo' => $codigo],
                ['descripcion' => $descripcion]
            );
        }
    }
}
