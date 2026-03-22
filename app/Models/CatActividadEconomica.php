<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatActividadEconomica extends Model{
    protected $table = 'cat_actividad_economica';
    protected $primaryKey = 'codigo_actividad';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = [
        'codigo_actividad',
        'descripcion_actividad',
    ];
}
