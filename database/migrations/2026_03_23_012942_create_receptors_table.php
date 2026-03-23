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
        Schema::create('receptores', function (Blueprint $table) {
            $table->id('receptor_id'); 
            $table->string('tipo_documento_codigo', 2)->nullable(); 
            $table->string('tipo_receptor_codigo', 1)->nullable();
            $table->string('actividad_economica_codigo', 10)->nullable();
            $table->string('departamento_codigo', 10)->nullable();
            $table->string('municipio_codigo', 10)->nullable();
            $table->string('numero_documento', 25)->nullable();
            $table->string('nombre_razon_social', 200)->nullable();
            $table->string('nrc', 20)->nullable();
            $table->string('correo_electronico', 200)->nullable();
            $table->timestamps();
            
            $table->foreign('tipo_documento_codigo')->references('codigo')->on('cat_tipo_documento_identificacion');
            $table->foreign('tipo_receptor_codigo')->references('tipo_receptor_codigo')->on('cat_tipo_receptor');
            $table->foreign('actividad_economica_codigo')->references('codigo_actividad')->on('cat_actividad_economica');
            $table->foreign('departamento_codigo')->references('departamento_codigo')->on('cat_departamentos');
            $table->foreign('municipio_codigo')->references('municipio_codigo')->on('cat_municipios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receptores');
    }
};
