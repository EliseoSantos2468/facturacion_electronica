<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Detalle de pagos asociados a un DTE (nodo pagos del JSON MH).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dte_pagos', function (Blueprint $table) {
            $table->id('pago_id');
            $table->unsignedBigInteger('documento_id');
            $table->string('forma_pago_codigo', 2);
            $table->decimal('monto', 16, 2);
            $table->string('referencia', 50)->nullable();
            $table->string('plazo_codigo', 2)->nullable();
            $table->unsignedSmallInteger('periodo')->nullable();
            $table->timestamps();

            $table->foreign('documento_id')
                ->references('documento_id')->on('dte_documentos')
                ->cascadeOnDelete();

            $table->foreign('forma_pago_codigo')
                ->references('forma_pago_codigo')->on('cat_forma_pago');

            $table->foreign('plazo_codigo')
                ->references('plazo_codigo')->on('cat_plazo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dte_pagos');
    }
};
