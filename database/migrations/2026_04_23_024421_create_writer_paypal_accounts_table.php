<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('writer_paypal_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('writer_id')
                ->unique()
                ->constrained()
                ->onDelete('cascade');

            $table->string('paypal_email', 150);
            $table->string('paypal_nombre_cuenta', 150)->nullable();
            $table->string('paypal_merchant_id', 100)->nullable();

            $table->boolean('paypal_verificado')->default(false);
            $table->timestamp('verificado_en')->nullable();

            $table->enum('estado', ['pendiente','activo','bloqueado'])
                  ->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writer_paypal_accounts');
    }
};