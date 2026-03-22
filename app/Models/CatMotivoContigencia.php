<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatMotivoContigencia extends Model{
    protected $table = 'cat_motivo_contingencia';
    protected $primaryKey = 'm_contingencia_codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'm_contingencia_codigo',
        'descripcion_motivo'
    ];
}
