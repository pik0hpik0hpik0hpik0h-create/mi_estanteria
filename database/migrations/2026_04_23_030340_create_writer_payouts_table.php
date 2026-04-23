<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('writer_payouts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('writer_id')
                ->constrained('writers')
                ->onDelete('cascade');

            $table->decimal('monto', 12, 2);
            $table->string('moneda', 10)->default('USD');
            $table->string('paypal_email', 150);

            $table->string('paypal_batch_id')->nullable();
            $table->string('paypal_item_id')->nullable();
            $table->string('transaction_id')->nullable();

            $table->enum('status', [
                'pending',
                'processing',
                'paid',
                'failed',
                'cancelled'
            ])->default('pending');

            $table->text('error_message')->nullable();
            $table->longText('response_json')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writer_payouts');
    }
};