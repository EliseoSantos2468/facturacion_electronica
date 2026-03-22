<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatEstablecimiento extends Model{
    protected $table = 'cat_establecimientos';
    protected $primaryKey = 'establecimiento_codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'establecimiento_codigo',
        'establecimiento_nombre'
    ];
}
