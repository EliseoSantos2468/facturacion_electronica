<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model{
protected $table = 'productos';
    protected $primaryKey = 'producto_id'; 

    protected $fillable = [
        'codigo_interno',
        'descripcion',
        'tipo_item_codigo', //fk
        'unidad_medida_codigo', //fk
        'precio_unitario_sin_iva',
        'clasificacion_tributaria_id', //fk
        'stock_actual',
    ];

    public function unidadMedida()
    {
        return $this->belongsTo(CatUnidadMedida::class, 'unidad_medida_codigo', 'unidad_codigo');
    }

    public function tipoItem()
    {
        return $this->belongsTo(CatTipoItem::class, 'tipo_item_codigo', 'item_codigo');
    }
    

    public function clasificacionTributaria()
    {
        return $this->belongsTo(ClasificacionTributaria::class, 'clasificacion_tributaria_id', 'clasificacion_id');
    }
}
