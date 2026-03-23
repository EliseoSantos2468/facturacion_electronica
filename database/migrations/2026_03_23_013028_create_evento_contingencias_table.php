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
        Schema::create('eventos_contigencia', function (Blueprint $table) {
            $table->string('contingencia_codigo')->primary();
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin')->nullable();
            $table->string('motivo_contingencia_codigo'); 
            $table->unsignedBigInteger('estado_transmision_id');
            $table->string('sello_recepcion_evento', 40)->nullable();
            $table->timestamps();

            $table->foreign('motivo_contingencia_codigo')
                ->references('m_contingencia_codigo')
                ->on('cat_motivo_contingencia');

            $table->foreign('estado_transmision_id')
                ->references('estado_trans_id')
                ->on('estado_transmision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_contigencia');
    }
};
