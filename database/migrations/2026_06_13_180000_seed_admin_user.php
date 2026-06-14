<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Crea un usuario administrador inicial con su perfil y rol 'admin'.
     * Idempotente: si el email ya existe, no inserta nada.
     */
    public function up(): void
    {
        $email = 'admin1@admin.com';

        // Si el admin ya existe, no hacemos nada (evita duplicados al re-correr migraciones).
        $existente = DB::table('users')->where('email', $email)->first();
        if ($existente) {
            return;
        }

        DB::transaction(function () use ($email) {

            // USER
            $userId = DB::table('users')->insertGetId([
                'name'              => 'Administrador',
                'email'             => $email,
                'is_admin'          => true,
                'email_verified_at' => now(),
                'password'          => Hash::make('admin1234'),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // PERFIL
            DB::table('perfiles')->insert([
                'user_id'             => $userId,
                'nombres'             => 'Administrador',
                'apellidos'           => 'Mi Estantería',
                'fecha_actualizacion' => now(),
            ]);

            // ROL admin (requiere migración previa que agrega 'admin' al ENUM)
            DB::table('roles')->insert([
                'user_id'          => $userId,
                'rol'              => 'admin',
                'estado'           => true,
                'fecha_asignacion' => now(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * Elimina el usuario admin sembrado. Perfil y roles se borran en cascada
     * por la FK con onDelete('cascade').
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'admin1@admin.com')->delete();
    }
};
