<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Perfil extends Model
{
    protected $table = 'perfiles';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 
        'nombres',
        'apellidos',
        'genero',
        'fecha_nacimiento',
        'telefono',
        'pais',
        'ciudad',
        'foto_url',
        'bio',
        'web',
        'facebook',
        'instagram',
        'x',
        'fecha_actualizacion'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}