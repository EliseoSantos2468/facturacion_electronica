<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioPerfil extends Model
{
    protected $table = 'usuario_perfiles'; 
    protected $primaryKey = 'usuario_perfil_id';
    protected $fillable = [
        'user_id',
        'emisor_id',
        'rol_id',
        'creado_por_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function emisor()
    {
        return $this->belongsTo(Emisor::class, 'emisor_id', 'emisor_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por_user_id');
    }
}
