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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('producto_id'); 
            $table->string('codigo_interno', 50)->unique();
            $table->string('descripcion', 255);
            $table->string('tipo_item_codigo', 5); 
            $table->string('unidad_medida_codigo', 10);
            $table->decimal('precio_unitario_sin_iva', 16, 2);
            $table->unsignedBigInteger('clasificacion_tributaria_id');
            $table->decimal('stock_actual', 16, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('tipo_item_codigo')->references('item_codigo')->on('cat_tipo_item');
            $table->foreign('unidad_medida_codigo')->references('unidad_codigo')->on('cat_unidad_medida');
            $table->foreign('clasificacion_tributaria_id')->references('clasificacion_id')->on('clasificacion_tributaria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
