<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Orquesta la carga de todos los catálogos (MH e internos) usados por el módulo DTE.
 */
class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CatDepartamentoSeeder::class,
            CatMunicipioSeeder::class,
            CatTipoDocumentoIdentificacionSeeder::class,
            CatTipoDteSeeder::class,
            CatTipoItemSeeder::class,
            CatTipoReceptorSeeder::class,
            CatMotivoInvalidacionSeeder::class,
            CatMotivoContigenciaSeeder::class,
            CatEstablecimientoSeeder::class,
            CatCondicionOperacionSeeder::class,
            CatFormaPagoSeeder::class,
            CatPlazoSeeder::class,
            CatUnidadMedidaSeeder::class,
            CatActividadEconomicaSeeder::class,
            EstadoDteSeeder::class,
            EstadoTransmisionSeeder::class,
            ClasificacionTributariaSeeder::class,
        ]);
    }
}
