<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Modificar items del carrito
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('vendedor_id')->nullable()->after('book_id')->constrained('vendedores')->nullOnDelete();
            $table->decimal('descuento_aplicado', 10, 2)->default(0)->after('precio_unitario');
        });

        // 2. Modificar la orden general (para saber el descuento total)
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('descuento_total', 10, 2)->default(0)->after('subtotal');
        });

        // 3. Modificar los items de la orden (aquí es donde viaja la comisión al Wallet)
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('vendedor_id')->nullable()->after('writer_id')->constrained('vendedores')->nullOnDelete();
            $table->decimal('descuento_aplicado', 10, 2)->default(0)->after('precio');
            $table->decimal('comision_vendedor', 10, 2)->default(0)->after('ganancia_writer');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['vendedor_id']);
            $table->dropColumn(['vendedor_id', 'descuento_aplicado']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('descuento_total');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['vendedor_id']);
            $table->dropColumn(['vendedor_id', 'descuento_aplicado', 'comision_vendedor']);
        });
    }
};