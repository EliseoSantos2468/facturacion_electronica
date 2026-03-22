<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emisor extends Model{
    protected $table = 'emisores';
    protected $primaryKey = 'emisor_id';

    protected $fillable = [
        'nit',
        'nrc',
        'nombre_razon_social',
        'nombre_comercial',
        'actividad_economica_codigo', //fk
        'departamento_codigo', //fk
        'municipio_codigo', //fk
        'direccion_complemento',
        'telefono',
        'correo_electronico',
        'establecimiento_codigo',
        'punto_venta_codigo'
    ];

    public function actividadEconomica()
    {
        return $this->belongsTo(CatActividadEconomica::class, 'actividad_economica_codigo', 'codigo_actividad');
    }

    public function departamento()
    {
        return $this->belongsTo(CatDepartamento::class, 'departamento_codigo', 'departamento_codigo');
    }

    public function municipio()
    {
        return $this->belongsTo(CatMunicipio::class, 'municipio_codigo', 'municipio_codigo');       
    }
}
