<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Resetea la contraseña del usuario administrador usando el modelo Eloquent,
     * para que el cast 'hashed' del modelo User se encargue del hashing y quede
     * 100% compatible con Auth::attempt().
     */
    public function up(): void
    {
        $admin = User::where('email', 'admin1@admin.com')->first();

        if (!$admin) {
            return;
        }

        $admin->password = 'admin1234';
        $admin->save();
    }

    /**
     * Reverse the migrations.
     *
     * No revertimos un reseteo de contraseña.
     */
    public function down(): void
    {
        //
    }
};
