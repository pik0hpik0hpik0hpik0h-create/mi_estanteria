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
        Schema::create('books', function (Blueprint $table) {
        $table->id();

        // RELACIONES
        $table->foreignId('writer_id')
            ->constrained('writers')
            ->onDelete('cascade');

        $table->foreignId('book_category_id')
            ->nullable()
            ->constrained('book_categories')
            ->nullOnDelete();

        // INFORMACIÓN GENERAL
        $table->string('titulo');
        $table->string('slug')->unique();

        $table->text('descripcion_corta')->nullable();
        $table->longText('descripcion')->nullable();

        // PORTADA PRINCIPAL
        $table->string('portada')->nullable();

        // TIPO
        $table->enum('tipo', [
            'ebook',
            'fisico',
            'ambos'
        ])->default('ebook');

        // FORMATOS
        $table->enum('formato', [
            'pdf',
            'epub',
            'mobi'
        ])->nullable();

        // INFORMACIÓN EXTRA
        $table->string('idioma')->default('Español');

        $table->string('isbn')->nullable();

        $table->unsignedInteger('paginas')->nullable();

        $table->date('fecha_publicacion')->nullable();

        // PRECIOS
        $table->decimal('precio', 10, 2)->default(0);

        // SOLO PARA FÍSICOS
        $table->unsignedInteger('stock')->nullable();

        // ESTADO
        $table->enum('estado', [
            'borrador',
            'revision',
            'publicado',
            'rechazado'
        ])->default('borrador');

        // VISIBILIDAD
        $table->boolean('visibilidad')->default(true);

        // DESTACADOS
        $table->boolean('destacado')->default(false);

        // MÉTRICAS
        $table->unsignedBigInteger('total_ventas')->default(0);

        $table->decimal('promedio_rating', 3, 2)
            ->default(0);

        // SEO
        $table->string('meta_title')->nullable();
        $table->text('meta_description')->nullable();

        $table->timestamps();

        // ÍNDICES
        $table->index('titulo');
        $table->index('estado');
        $table->index('tipo');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book');
    }
};
