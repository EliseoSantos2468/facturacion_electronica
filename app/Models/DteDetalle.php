<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DteDetalle extends Model{
    protected $table = 'dte_detalles';
    protected $primaryKey = 'detalle_id';
    
    protected $fillable = [
        'documento_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'monto_descuento',
        'ventas_gravadas',
        'ventas_exentas',
        'ventas_no_sujetas'
    ];
    public function documento(){
        return $this->belongsTo(DteDocumento::class, 'documento_id', 'documento_id');
    }   
    public function producto(){
        return $this->belongsTo(Producto::class, 'producto_id', 'producto_id');
    }
}
