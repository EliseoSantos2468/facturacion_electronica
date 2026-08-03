<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatCondicionOperacion extends Model
{
    protected $table = 'cat_condicion_operacion';
    protected $primaryKey = 'condicion_codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'condicion_codigo',
        'descripcion',
    ];
}
