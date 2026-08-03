<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DteDocumento extends Model{
    protected $table = 'dte_documentos';
    protected $primaryKey = 'documento_id';

    protected $fillable = [
        'codigo_generacion',
        'numero_control',
        'sello_recepcion',
        'tipo_dte_codigo',
        'estado_dte',
        'json_firmado',
        'forma_jws',
        'url_representacion_grafica',
        'emisor_id',
        'receptor_id'
    ];

    public function tipoDte()
    {
        return $this->belongsTo(CatTipoDte::class, 'tipo_dte_codigo', 'codigo_tipo_documento');
    }
    public function estadoDte()
    {
        return $this->belongsTo(EstadoDte::class, 'estado_dte', 'estado_dte_id');
    }

    public function emisor()
    {
        return $this->belongsTo(Emisor::class, 'emisor_id','emisor_id');
    }

    public function receptor()
    {
        return $this->belongsTo(Receptor::class, 'receptor_id','receptor_id');
    }
}
