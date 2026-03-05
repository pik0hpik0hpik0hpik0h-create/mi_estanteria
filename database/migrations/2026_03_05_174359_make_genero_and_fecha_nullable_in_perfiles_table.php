<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('perfiles', function (Blueprint $table) {
            $table->string('apellidos')->nullable()->change();
            $table->enum('genero', ['M','F'])->nullable()->change();
            $table->date('fecha_nacimiento')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perfiles', function (Blueprint $table) {
            $table->string('apellidos')->nullable(false)->change();
            $table->enum('genero', ['M','F'])->nullable(false)->change();
            $table->date('fecha_nacimiento')->nullable(false)->change();
        });
    }
};
