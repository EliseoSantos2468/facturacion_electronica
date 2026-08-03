<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo MH CAT-016 - Condición de la operación (contado, crédito, otro).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_condicion_operacion', function (Blueprint $table) {
            $table->string('condicion_codigo', 2)->primary();
            $table->string('descripcion', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_condicion_operacion');
    }
};
