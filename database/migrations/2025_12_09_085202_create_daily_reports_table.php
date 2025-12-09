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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('totalisator_awal', 15, 2);
            $table->decimal('totalisator_akhir', 15, 2);
            $table->decimal('stok_awal_mm', 15, 2); // Stok dalam MM
            $table->decimal('stok_akhir_mm', 15, 2); // Stok dalam MM
            $table->timestamps();

            $table->unique(['user_id', 'shift_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
