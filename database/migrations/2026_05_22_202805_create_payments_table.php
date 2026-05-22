<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->enum('proveedor', [
                'paypal'
            ])->default('paypal');

            $table->string('transaction_id')->nullable();

            $table->string('paypal_order_id')->nullable();

            $table->string('paypal_capture_id')->nullable();

            $table->decimal('monto', 10, 2);

            $table->string('moneda', 10)
                ->default('USD');

            $table->enum('estado', [
                'pending',
                'completed',
                'failed',
                'refunded'
            ])->default('pending');

            $table->longText('response_json')->nullable();

            $table->timestamp('pagado_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};