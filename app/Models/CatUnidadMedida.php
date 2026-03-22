<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatUnidadMedida extends Model{
    protected $table = 'cat_unidad_medida';
    protected $primaryKey = 'unidad_codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'unidad_codigo',
        'nombre_unidad'
    ];
}
