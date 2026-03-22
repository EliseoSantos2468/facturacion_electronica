<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClasificacionTributaria extends Model{
    protected $table = 'clasificacion_tributaria';
    protected $primaryKey = 'clasificacion_id';
    
    protected $fillable = [
        'nombre_clasificacion'
    ];
}
