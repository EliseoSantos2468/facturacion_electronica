<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega a dte_documentos los campos exigidos por el JSON DTE del MH que
 * no estaban en la migración inicial: ambiente, versión, modelo de facturación,
 * tipo de transmisión, fecha/hora de emisión, moneda, condición de operación,
 * totales, observaciones del MH y referencia a DTE relacionado (para NC/ND).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dte_documentos', function (Blueprint $table) {
            $table->string('ambiente', 2)->default('00')->after('numero_control');
            $table->unsignedTinyInteger('version')->default(1)->after('ambiente');
            $table->unsignedTinyInteger('modelo_facturacion')->default(1)->after('version');
            $table->unsignedTinyInteger('tipo_transmision')->default(1)->after('modelo_facturacion');
            $table->date('fecha_emision')->nullable()->after('tipo_transmision');
            $table->time('hora_emision')->nullable()->after('fecha_emision');
            $table->string('moneda', 3)->default('USD')->after('hora_emision');
            $table->string('condicion_operacion_codigo', 2)->nullable()->after('moneda');

            $table->decimal('total_gravada', 16, 2)->default(0)->after('condicion_operacion_codigo');
            $table->decimal('total_exenta', 16, 2)->default(0)->after('total_gravada');
            $table->decimal('total_no_sujeta', 16, 2)->default(0)->after('total_exenta');
            $table->decimal('sub_total', 16, 2)->default(0)->after('total_no_sujeta');
            $table->decimal('iva', 16, 2)->default(0)->after('sub_total');
            $table->decimal('iva_retenido', 16, 2)->default(0)->after('iva');
            $table->decimal('ret_renta', 16, 2)->default(0)->after('iva_retenido');
            $table->decimal('monto_total_operacion', 16, 2)->default(0)->after('ret_renta');
            $table->decimal('total_pagar', 16, 2)->default(0)->after('monto_total_operacion');
            $table->string('total_letras', 255)->nullable()->after('total_pagar');

            $table->text('observaciones_mh')->nullable()->after('total_letras');
            $table->unsignedBigInteger('dte_relacionado_id')->nullable()->after('observaciones_mh');

            $table->foreign('condicion_operacion_codigo')
                ->references('condicion_codigo')->on('cat_condicion_operacion');

            $table->foreign('dte_relacionado_id')
                ->references('documento_id')->on('dte_documentos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dte_documentos', function (Blueprint $table) {
            $table->dropForeign(['dte_relacionado_id']);
            $table->dropForeign(['condicion_operacion_codigo']);

            $table->dropColumn([
                'ambiente',
                'version',
                'modelo_facturacion',
                'tipo_transmision',
                'fecha_emision',
                'hora_emision',
                'moneda',
                'condicion_operacion_codigo',
                'total_gravada',
                'total_exenta',
                'total_no_sujeta',
                'sub_total',
                'iva',
                'iva_retenido',
                'ret_renta',
                'monto_total_operacion',
                'total_pagar',
                'total_letras',
                'observaciones_mh',
                'dte_relacionado_id',
            ]);
        });
    }
};
