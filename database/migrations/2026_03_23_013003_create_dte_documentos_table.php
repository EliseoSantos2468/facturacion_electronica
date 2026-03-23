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
        Schema::create('dte_documentos', function (Blueprint $table) {
            $table->id('documento_id');
            $table->uuid('codigo_generacion')->unique();
            $table->string('numero_control', 31)->unique();
            $table->string('sello_recepcion', 40)->nullable();
            $table->string('tipo_dte_codigo', 2);
            $table->unsignedBigInteger('estado_dte');
            $table->unsignedBigInteger('emisor_id'); 
            $table->unsignedBigInteger('receptor_id');
            $table->json('json_firmado')->nullable(); 
            $table->text('forma_jws')->nullable(); 
            $table->string('url_representacion_grafica')->nullable();
            $table->timestamps();
            
            $table->foreign('tipo_dte_codigo')->references('codigo_tipo_documento')->on('cat_tipo_dte');
            $table->foreign('estado_dte')->references('estado_dte_id')->on('estado_dte');
            $table->foreign('emisor_id')->references('emisor_id')->on('emisores');
            $table->foreign('receptor_id')->references('receptor_id')->on('receptores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dte_documentos');
    }
};
