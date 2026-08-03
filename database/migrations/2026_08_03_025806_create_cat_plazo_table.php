<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo MH CAT-018 - Plazo (para operaciones a crédito: días, meses, años).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_plazo', function (Blueprint $table) {
            $table->string('plazo_codigo', 2)->primary();
            $table->string('descripcion', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_plazo');
    }
};
