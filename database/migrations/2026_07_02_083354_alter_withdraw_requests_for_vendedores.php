<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            // 1. Quitamos la llave foránea de wallet_id para que acepte tanto billeteras de escritores como de vendedores
            $table->dropForeign(['wallet_id']);

            // 2. Modificamos writer_id para que pueda ser nulo (nullable)
            $table->unsignedBigInteger('writer_id')->nullable()->change();

            // 3. Añadimos la nueva columna para el vendedor
            $table->foreignId('vendedor_id')
                  ->nullable()
                  ->after('writer_id')
                  ->constrained('vendedores')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            // Revertimos los cambios si hacemos un rollback
            $table->dropForeign(['vendedor_id']);
            $table->dropColumn('vendedor_id');

            $table->unsignedBigInteger('writer_id')->nullable(false)->change();

            $table->foreign('wallet_id')->references('id')->on('writer_wallets')->cascadeOnDelete();
        });
    }
};