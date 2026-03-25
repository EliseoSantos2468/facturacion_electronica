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
        Schema::create('usuario_perfiles', function (Blueprint $table) {
            $table->id('usuario_perfil_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('emisor_id')->nullable(); // Null si es Super Admin
            $table->foreign('emisor_id')->references('emisor_id')->on('emisores');
            $table->foreignId('rol_id')->constrained('roles');
            $table->unsignedBigInteger('creado_por_user_id')->nullable();
            $table->foreign('creado_por_user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_perfiles');
    }
};
