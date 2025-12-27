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
        Schema::table('tank_additions', function (Blueprint $table) {
            $table->string('no_polisi')->nullable()->after('tanggal');
            $table->string('shipment_no')->nullable()->after('no_polisi');
            $table->string('nama_pengemudi')->nullable()->after('shipment_no');
            $table->string('no_so_sa')->nullable()->after('nama_pengemudi');
            $table->decimal('stok_awal', 10, 2)->nullable()->after('jumlah_liter');
            $table->decimal('stok_akhir', 10, 2)->nullable()->after('stok_awal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tank_additions', function (Blueprint $table) {
            $table->dropColumn([
                'no_polisi',
                'shipment_no',
                'nama_pengemudi',
                'no_so_sa',
                'stok_awal',
                'stok_akhir'
            ]);
        });
    }
};
