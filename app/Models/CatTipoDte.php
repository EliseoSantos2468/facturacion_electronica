<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatTipoDte extends Model{
    protected $table = 'cat_tipo_dte';
    protected $primaryKey = 'codigo_tipo_documento';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'codigo_tipo_documento', 
        'nombre_tipo_documento'
    ];
}
