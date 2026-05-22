<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('codigo')->unique();

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('impuestos', 10, 2)->default(0);
            $table->decimal('comision', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->string('moneda', 10)->default('USD');

            $table->enum('estado', [
                'pendiente',
                'pagado',
                'cancelado',
                'reembolsado'
            ])->default('pendiente');

            $table->string('paypal_order_id')->nullable();
            $table->string('paypal_capture_id')->nullable();

            $table->timestamp('pagado_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};