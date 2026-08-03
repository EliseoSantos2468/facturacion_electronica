<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DteDocumentoRelacionado extends Model
{
    protected $table = 'dte_documentos_relacionados';
    protected $primaryKey = 'documento_relacionado_id';

    protected $fillable = [
        'documento_id',
        'tipo_documento',
        'tipo_generacion',
        'numero_documento',
        'fecha_emision',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
    ];

    public function documento()
    {
        return $this->belongsTo(DteDocumento::class, 'documento_id', 'documento_id');
    }
}
