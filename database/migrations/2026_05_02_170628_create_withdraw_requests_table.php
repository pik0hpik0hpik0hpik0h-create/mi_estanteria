<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('writer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained('writer_wallets')->cascadeOnDelete();

            $table->decimal('monto', 10, 2);

            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'pagado'])
                  ->default('pendiente');

            $table->string('paypal_email');
            $table->string('paypal_merchant_id')->nullable();

            $table->text('nota_admin')->nullable();

            $table->foreignId('procesado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('procesado_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdraw_requests');
    }
};