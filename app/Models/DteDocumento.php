<?php

namespace App\Models;

use App\Enums\Ambiente;
use App\Enums\CondicionOperacion;
use App\Enums\ModeloFacturacion;
use App\Enums\TipoDte;
use App\Enums\TipoTransmision;
use Illuminate\Database\Eloquent\Model;

class DteDocumento extends Model
{
    protected $table = 'dte_documentos';
    protected $primaryKey = 'documento_id';

    protected $fillable = [
        'codigo_generacion',
        'numero_control',
        'sello_recepcion',
        'tipo_dte_codigo',
        'estado_dte',
        'ambiente',
        'version',
        'modelo_facturacion',
        'tipo_transmision',
        'fecha_emision',
        'hora_emision',
        'moneda',
        'condicion_operacion_codigo',
        'total_gravada',
        'total_exenta',
        'total_no_sujeta',
        'sub_total',
        'iva',
        'iva_retenido',
        'ret_renta',
        'monto_total_operacion',
        'total_pagar',
        'total_letras',
        'observaciones_mh',
        'dte_relacionado_id',
        'json_firmado',
        'forma_jws',
        'url_representacion_grafica',
        'emisor_id',
        'receptor_id',
    ];

    protected $casts = [
        'json_firmado' => 'array',
        'fecha_emision' => 'date',
        'ambiente' => Ambiente::class,
        'tipo_dte_codigo' => TipoDte::class,
        'modelo_facturacion' => ModeloFacturacion::class,
        'tipo_transmision' => TipoTransmision::class,
        'condicion_operacion_codigo' => CondicionOperacion::class,
        'total_gravada' => 'decimal:2',
        'total_exenta' => 'decimal:2',
        'total_no_sujeta' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'iva' => 'decimal:2',
        'iva_retenido' => 'decimal:2',
        'ret_renta' => 'decimal:2',
        'monto_total_operacion' => 'decimal:2',
        'total_pagar' => 'decimal:2',
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
        return $this->belongsTo(Emisor::class, 'emisor_id', 'emisor_id');
    }

    public function receptor()
    {
        return $this->belongsTo(Receptor::class, 'receptor_id', 'receptor_id');
    }

    public function condicionOperacion()
    {
        return $this->belongsTo(CatCondicionOperacion::class, 'condicion_operacion_codigo', 'condicion_codigo');
    }

    public function dteRelacionado()
    {
        return $this->belongsTo(self::class, 'dte_relacionado_id', 'documento_id');
    }

    public function detalles()
    {
        return $this->hasMany(DteDetalle::class, 'documento_id', 'documento_id');
    }

    public function pagos()
    {
        return $this->hasMany(DtePago::class, 'documento_id', 'documento_id');
    }

    public function documentosRelacionados()
    {
        return $this->hasMany(DteDocumentoRelacionado::class, 'documento_id', 'documento_id');
    }

    public function transmisiones()
    {
        return $this->hasMany(DteTransmision::class, 'documento_id', 'documento_id');
    }
}
