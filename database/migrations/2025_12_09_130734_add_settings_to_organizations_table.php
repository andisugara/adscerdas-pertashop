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
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('kode_pertashop')->nullable()->after('name');
            $table->decimal('harga_jual', 15, 2)->default(12000)->after('address'); // Harga BBM per liter
            $table->decimal('rumus', 8, 2)->default(2.09)->after('harga_jual'); // Rumus konversi MM ke Liter
            $table->decimal('hpp_per_liter', 15, 2)->default(11500)->after('rumus'); // HPP per liter
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['kode_pertashop', 'harga_jual', 'rumus', 'hpp_per_liter']);
        });
    }
};
