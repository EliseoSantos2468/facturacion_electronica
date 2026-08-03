<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatPlazo extends Model
{
    protected $table = 'cat_plazo';
    protected $primaryKey = 'plazo_codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'plazo_codigo',
        'descripcion',
    ];
}
