<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoTransmision extends Model{
    protected $table = 'estado_transmision';
    protected $primaryKey = 'estado_trans_id';
    
    protected $fillable = [
        'descripcion_estado_trans'
    ];
}
