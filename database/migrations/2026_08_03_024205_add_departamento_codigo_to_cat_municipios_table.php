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
        Schema::table('cat_municipios', function (Blueprint $table) {
            $table->string('departamento_codigo', 10)->nullable()->after('municipio_codigo');
            $table->foreign('departamento_codigo')->references('departamento_codigo')->on('cat_departamentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cat_municipios', function (Blueprint $table) {
            $table->dropForeign(['departamento_codigo']);
            $table->dropColumn('departamento_codigo');
        });
    }
};
