<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatTipoItem extends Model{
    protected $table = 'cat_tipo_item';
    protected $primaryKey = 'item_codigo'; 
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'item_codigo', 
        'descripcion'
    ]; 
}
