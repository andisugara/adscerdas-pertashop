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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pertashop');
            $table->string('kode_pertashop')->unique();
            $table->text('alamat')->nullable();
            $table->decimal('harga_jual', 15, 2)->default(12000); // Harga BBM per liter
            $table->decimal('rumus', 8, 2)->default(2.09); // Rumus konversi MM ke Liter
            $table->decimal('hpp_per_liter', 15, 2)->default(11500); // HPP per liter
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
