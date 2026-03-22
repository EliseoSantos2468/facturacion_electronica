<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatTipoReceptor extends Model{
    protected $table = 'cat_tipo_receptor';
    protected $primaryKey = 'tipo_receptor_codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'tipo_receptor_codigo',
        'nombre'
    ];
}
