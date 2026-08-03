<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo MH CAT-017 - Forma de pago (efectivo, tarjeta, transferencia, etc.).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_forma_pago', function (Blueprint $table) {
            $table->string('forma_pago_codigo', 2)->primary();
            $table->string('descripcion', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_forma_pago');
    }
};
