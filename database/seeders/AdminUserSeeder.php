<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use App\Models\Perfil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Crea (o actualiza) un usuario administrador por defecto.
     *
     * Credenciales:
     *   Email:    admin1@admin.com
     *   Password: admin123
     *
     * Después del seed conviene cambiar la contraseña desde el perfil.
     */
    public function run(): void
    {
        // Limpieza: si ya existía un admin con el email viejo (de un seed anterior),
        // lo eliminamos para no dejar usuarios huérfanos.
        // Las tablas roles y perfiles tienen ON DELETE CASCADE, así que basta con
        // borrar el User.
        User::where('email', 'admin@miestanteria.com')->delete();

        // Crear o actualizar el usuario admin.
        $admin = User::updateOrCreate(
            ['email' => 'admin1@admin.com'],
            [
                'name'              => 'admin1',
                'password'          => Hash::make('admin123'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );

        // Perfil asociado (necesario porque la vista /perfil lo asume existente).
        Perfil::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'nombres'   => 'Admin',
                'apellidos' => 'Principal',
            ]
        );

        // Rol enum 'admin' activo.
        Rol::updateOrCreate(
            [
                'user_id' => $admin->id,
                'rol'     => 'admin',
            ],
            [
                'estado'           => 1,
                'fecha_asignacion' => now(),
            ]
        );
    }
}
