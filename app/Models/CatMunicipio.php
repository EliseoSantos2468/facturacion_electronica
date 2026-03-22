<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatMunicipio extends Model{
    protected $table = 'cat_municipios';
    protected $primaryKey = 'municipio_codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'municipio_codigo',
        'municipio_nombre',
    ];
}
