<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatMotivoInvalidacion extends Model{
    protected $table = 'cat_motivo_invalidacion';
    protected $primaryKey = 'm_motivo_codigo';
    
    protected $fillable = [
        'descripcion_motivo'
    ];
}
