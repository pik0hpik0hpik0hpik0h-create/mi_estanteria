<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'correo',
        'contrasena_hash',
        'estado',
        'fecha_registro',
        'ultimo_acceso'
    ];

    public function perfil()
    {
        return $this->hasOne(Perfil::class, 'usuario_id');
    }

    public function rol()
    {
        return $this->hasOne(Rol::class, 'usuario_id');
    }
}