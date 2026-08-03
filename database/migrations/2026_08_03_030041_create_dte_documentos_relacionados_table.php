<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Nodo `documentoRelacionado` del JSON MH: usado principalmente en Notas de
 * Crédito/Débito y en documentos derivados para referenciar los documentos que
 * modifican o soportan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dte_documentos_relacionados', function (Blueprint $table) {
            $table->id('documento_relacionado_id');
            $table->unsignedBigInteger('documento_id');
            $table->string('tipo_documento', 2);
            $table->unsignedTinyInteger('tipo_generacion');
            $table->string('numero_documento', 40);
            $table->date('fecha_emision');
            $table->timestamps();

            $table->foreign('documento_id')
                ->references('documento_id')->on('dte_documentos')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dte_documentos_relacionados');
    }
};
