<?php

namespace Database\Seeders;

use App\Models\CatTipoDocumentoIdentificacion;
use Illuminate\Database\Seeder;

/**
 * Catálogo MH CAT-022 - Tipo de documento de identificación del receptor.
 */
class CatTipoDocumentoIdentificacionSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['13', 'Documento Único de Identidad (DUI)'],
            ['36', 'Número de Identificación Tributaria (NIT)'],
            ['02', 'Carnet de Residente'],
            ['03', 'Pasaporte'],
            ['37', 'Otro'],
        ];

        foreach ($tipos as [$codigo, $nombre]) {
            CatTipoDocumentoIdentificacion::updateOrCreate(
                ['codigo' => $codigo],
                ['nombre_documento_identificacion' => $nombre]
            );
        }
    }
}
