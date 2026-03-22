<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificadoFirma extends Model{
    protected $table = 'certificado_firma';
    protected $primaryKey = 'certificado_id';
    protected $fillable = [
        'archivo_p12',
        'clave_encriptada',
        'fecha_vencimiento',
        'emisor_id' //fk
    ];
    public function emisor()
    {
        return $this->belongsTo(Emisor::class, 'emisor_id', 'emisor_id');
    }
}
