<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emisores', function (Blueprint $table) {
            $table->id('emisor_id'); 
            $table->string('nit')->nullable();
            $table->string('nrc')->nullable();
            $table->string('nombre_razon_social')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->string('actividad_economica_codigo')->nullable();
            $table->string('departamento_codigo', 10)->nullable();
            $table->string('municipio_codigo', 10)->nullable();
            $table->string('establecimiento_codigo', 10)->nullable();
            $table->string('direccion_complemento')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('punto_venta_codigo')->nullable();
            $table->timestamps();
            
            $table->foreign('actividad_economica_codigo')->references('codigo_actividad')->on('cat_actividad_economica');
            $table->foreign('departamento_codigo')->references('departamento_codigo')->on('cat_departamentos');
            $table->foreign('municipio_codigo')->references('municipio_codigo')->on('cat_municipios');
            $table->foreign('establecimiento_codigo')->references('establecimiento_codigo')->on('cat_establecimientos');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emisores');
    }
};
