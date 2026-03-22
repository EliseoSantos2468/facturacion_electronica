<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoContingencia extends Model
{
    protected $table = 'eventos_contigencia';
    protected $primaryKey = 'contingencia_codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'contingencia_codigo', 
        'fecha_hora_inicio', 
        'fecha_hora_fin', 
        'motivo_contingencia_codigo', //FK
        'estado_transmision_id', //FK
        'sello_recepcion_evento'
    ];

    public function motivoContingencia()
    {
        return $this->belongsTo(CatMotivoContigencia::class, 'motivo_contingencia_codigo', 'm_contingencia_codigo');
    }

    public function estadoTransmision()
    {
        return $this->belongsTo(EstadoTransmision::class, 'estado_transmision_id', 'estado_trans_id');
    }
}
