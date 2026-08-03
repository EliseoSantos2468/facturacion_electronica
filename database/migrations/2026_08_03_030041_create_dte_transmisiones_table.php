<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bitácora de intentos de transmisión de un DTE hacia el MH.
 * Un registro por cada llamada HTTP (envío original, reintentos, invalidación, etc.).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dte_transmisiones', function (Blueprint $table) {
            $table->id('transmision_id');
            $table->unsignedBigInteger('documento_id');
            $table->unsignedSmallInteger('intento');
            $table->string('endpoint');
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->string('mensaje')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('procesado_en')->nullable();
            $table->timestamps();

            $table->foreign('documento_id')
                ->references('documento_id')->on('dte_documentos')
                ->cascadeOnDelete();

            $table->index(['documento_id', 'intento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dte_transmisiones');
    }
};
