<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatTipoDocumentoIdentificacion extends Model{
    protected $table = 'cat_tipo_documento_identificacion';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = [
        'codigo',
        'nombre_documento_identificacion',
    ];
}
