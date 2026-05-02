<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wallet_id')->constrained('writer_wallets')->cascadeOnDelete();

            $table->enum('tipo', ['ingreso', 'retiro', 'ajuste']);
            $table->decimal('monto', 10, 2);

            $table->string('descripcion')->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};