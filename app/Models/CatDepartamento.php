<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatDepartamento extends Model{
    protected $table = 'cat_departamentos';
    protected $primaryKey = 'departamento_codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'departamento_codigo',
        'departamento_nombre'
    ];
}
