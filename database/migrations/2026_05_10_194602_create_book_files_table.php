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
        Schema::create('book_files', function (Blueprint $table) {
        $table->id();

        $table->foreignId('book_id')
            ->constrained('books')
            ->onDelete('cascade');

        // TIPO DE ARCHIVO
        $table->enum('tipo', [
            'preview',
            'completo',
            'extra'
        ])->default('completo');

        // ARCHIVO
        $table->string('archivo');

        // INFORMACIÓN
        $table->string('nombre_original')->nullable();

        $table->unsignedBigInteger('peso')->nullable();

        $table->string('mime_type')->nullable();

        $table->string('extension')->nullable();

        // VERSIONADO
        $table->string('version')->nullable();

        $table->timestamps();

        $table->index('tipo');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_files');
    }
};
