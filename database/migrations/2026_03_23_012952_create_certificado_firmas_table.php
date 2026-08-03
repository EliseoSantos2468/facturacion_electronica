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
        Schema::create('certificado_firma', function (Blueprint $table) {
            $table->id('certificado_id'); 
            $table->unsignedBigInteger('emisor_id');
            $table->string('archivo_p12'); 
            $table->text('clave_encriptada'); 
            $table->date('fecha_vencimiento'); 
            $table->timestamps();
            
            $table->foreign('emisor_id')
                ->references('emisor_id')
                ->on('emisores')
                ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificado_firma');
    }
};
