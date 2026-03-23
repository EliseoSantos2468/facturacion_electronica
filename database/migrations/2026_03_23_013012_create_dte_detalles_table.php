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
        Schema::create('dte_detalles', function (Blueprint $table) {
            $table->id('detalle_id');
            $table->unsignedBigInteger('documento_id');
            $table->unsignedBigInteger('producto_id');
            $table->decimal('cantidad', 16, 2);
            $table->decimal('precio_unitario', 16, 2);
            $table->decimal('monto_descuento', 16, 2)->default(0);
            $table->decimal('ventas_nosujetas', 16, 2)->default(0); 
            $table->decimal('ventas_exentas', 16, 2)->default(0);
            $table->decimal('ventas_gravadas', 16, 2)->default(0);
            $table->timestamps();

            $table->foreign('documento_id')->references('documento_id')->on('dte_documentos')->onDelete('cascade');
            $table->foreign('producto_id')->references('producto_id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dte_detalles');
    }
};
