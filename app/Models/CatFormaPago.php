<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatFormaPago extends Model
{
    protected $table = 'cat_forma_pago';
    protected $primaryKey = 'forma_pago_codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'forma_pago_codigo',
        'descripcion',
    ];
}
