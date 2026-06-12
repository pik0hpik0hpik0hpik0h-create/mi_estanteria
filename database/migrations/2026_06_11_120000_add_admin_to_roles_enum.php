<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Agrega el valor 'admin' al ENUM de la columna 'rol' en la tabla 'roles'.
     * Se usa SQL crudo porque Laravel Schema Builder no permite modificar ENUMs en MySQL.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE roles MODIFY COLUMN rol ENUM('comprador','vendedor','escritor','admin') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * Antes de revertir, eliminamos las filas con rol='admin' para evitar errores
     * al reducir el enum.
     */
    public function down(): void
    {
        DB::statement("DELETE FROM roles WHERE rol = 'admin'");
        DB::statement("ALTER TABLE roles MODIFY COLUMN rol ENUM('comprador','vendedor','escritor') NOT NULL");
    }
};
