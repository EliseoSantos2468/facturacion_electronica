<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receptor extends Model{
    protected $table = 'receptores';
    protected $primaryKey = 'receptor_id';
    protected $fillable = [
        'tipo_documento_codigo', //fk
        'tipo_receptor_codigo', //fk
        'numero_documento',
        'nombre_razon_social',
        'nrc',
        'actividad_economica_codigo', //fk
        'departamento_codigo', //fk
        'municipio_codigo', //fk
        'correo_electronico' 
    ];  
    public function tipoDocumento(){
        return $this->belongsTo(CatTipoDocumentoIdentificacion::class, 'tipo_documento_codigo', 'codigo');
    }
    public function tipoReceptor(){
        return $this->belongsTo(CatTipoReceptor::class, 'tipo_receptor_codigo', 'tipo_receptor_codigo');
    }
    public function actividadEconomica(){
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
