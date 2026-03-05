<?php

namespace App\Services;

use App\Models\Perfil;
use App\Models\Rol;

class UserService
{
    public static function crearPerfilYRol($usuario, $data)
    {
        // PERFIL
        Perfil::firstOrCreate(
            ['user_id' => $usuario->id], // condición de búsqueda
            [
                'nombres' => $data['nombres'] ?? null,
                'apellidos' => $data['apellidos'] ?? null,
                'genero' => $data['genero'] ?? null,
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                'pais' => $data['pais'] ?? null,
                'ciudad' => $data['ciudad'] ?? null,
                'fecha_actualizacion' => now()
            ]
        );

        // ROL
        Rol::firstOrCreate(
            ['user_id' => $usuario->id], // busca si ya existe
            [
                'rol' => 'comprador',
                'estado' => true,
                'fecha_asignacion' => now()
            ]
        );
    }
}