<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoInvalidacion extends Model{ 
    protected $table = 'eventos_invalidacion';
    protected $primaryKey = 'invalidacion_id';
    protected $fillable = [
        'documento_id',
        'motivo_invalidacion_codigo',
        'fecha_generacion',
        'sello_recepcion_invalidacion'
    ];
    // Relación con el Documento (DTE)
    public function documento()
    {
        return $this->belongsTo(DteDocumento::class, 'documento_id', 'documento_id');
    }

    // Relación con el Motivo
    public function motivo()
    {
        return $this->belongsTo(CatMotivoInvalidacion::class, 'motivo_invalidacion_codigo', 'm_motivo_codigo');
    }
}
