<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'permiso_id';
    protected $fillable = ['nombre', 'descripcion'];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'permiso_rol');
    }
}
