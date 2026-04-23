<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('writer_wallets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('writer_id')
                ->unique()
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('saldo_disponible', 12, 2)->default(0);
            $table->decimal('saldo_retenido', 12, 2)->default(0);

            $table->decimal('total_generado', 12, 2)->default(0);
            $table->decimal('total_pagado', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writer_wallets');
    }
};