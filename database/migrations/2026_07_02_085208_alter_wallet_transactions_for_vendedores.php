<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            // Quitamos la llave foránea estricta para permitir IDs de vendedor_wallets
            $table->dropForeign(['wallet_id']);
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            // Revertimos el cambio en caso de hacer rollback
            $table->foreign('wallet_id')->references('id')->on('writer_wallets')->cascadeOnDelete();
        });
    }
};