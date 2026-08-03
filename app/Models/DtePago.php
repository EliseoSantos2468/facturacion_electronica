<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DtePago extends Model
{
    protected $table = 'dte_pagos';
    protected $primaryKey = 'pago_id';

    protected $fillable = [
        'documento_id',
        'forma_pago_codigo',
        'monto',
        'referencia',
        'plazo_codigo',
        'periodo',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function documento()
    {
        return $this->belongsTo(DteDocumento::class, 'documento_id', 'documento_id');
    }

    public function formaPago()
    {
        return $this->belongsTo(CatFormaPago::class, 'forma_pago_codigo', 'forma_pago_codigo');
    }

    public function plazo()
    {
        return $this->belongsTo(CatPlazo::class, 'plazo_codigo', 'plazo_codigo');
    }
}
