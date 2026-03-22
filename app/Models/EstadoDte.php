<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoDte extends Model{
    protected $table = 'estado_dte';
    protected $primaryKey = 'estado_dte_id';
    
    protected $fillable = [
        'nombre_estado'
    ];
}
