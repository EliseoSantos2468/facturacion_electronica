<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'rol_id';
    protected $fillable = [
        'nombre',
        'slug',
    ];
    
    public function perfiles()
    {
        return $this->hasMany(UsuarioPerfil::class, 'rol_id');
    }
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol');
    }
}
