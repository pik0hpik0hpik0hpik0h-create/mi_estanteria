<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('writers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->onDelete('cascade');

            $table->string('nombre_pluma', 150);
            $table->string('documento_identidad', 30);
            $table->string('tipo_documento', 20)->nullable();

            $table->string('telefono', 30)->nullable();
            $table->string('pais', 100)->default('Ecuador');
            $table->string('ciudad', 100)->nullable();

            $table->text('biografia')->nullable();

            $table->enum('estado', ['pendiente','aprobado','rechazado','suspendido'])
                  ->default('pendiente');

            $table->timestamp('aprobado_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writers');
    }
};