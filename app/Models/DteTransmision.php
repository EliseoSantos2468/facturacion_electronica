<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DteTransmision extends Model
{
    protected $table = 'dte_transmisiones';
    protected $primaryKey = 'transmision_id';

    protected $fillable = [
        'documento_id',
        'intento',
        'endpoint',
        'http_status',
        'mensaje',
        'request_payload',
        'response_payload',
        'procesado_en',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'procesado_en' => 'datetime',
    ];

    public function documento()
    {
        return $this->belongsTo(DteDocumento::class, 'documento_id', 'documento_id');
    }
}
