<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('writers', function (Blueprint $table) {
            $table->dropColumn([
                'telefono',
                'pais',
                'ciudad',
                'biografia'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('writers', function (Blueprint $table) {
            //
        });
    }
};
