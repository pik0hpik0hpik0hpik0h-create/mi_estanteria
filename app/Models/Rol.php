<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles_usuario';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'rol',
        'estado',
        'fecha_asignacion'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
