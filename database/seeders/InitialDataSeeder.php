<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Owner
        \App\Models\User::create([
            'name' => 'Owner',
            'email' => 'owner@pertashop.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'aktif' => true,
        ]);

        // Create Operators
        \App\Models\User::create([
            'name' => 'Operator Pagi',
            'email' => 'operator1@pertashop.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
            'aktif' => true,
        ]);

        \App\Models\User::create([
            'name' => 'Operator Siang',
            'email' => 'operator2@pertashop.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
            'aktif' => true,
        ]);

        // Create Settings
        \App\Models\Setting::create([
            'nama_pertashop' => 'Pertashop Maju Jaya',
            'kode_pertashop' => 'PMJ001',
            'alamat' => 'Jl. Raya No. 123',
            'harga_jual' => 12000,
            'rumus' => 2.09,
            'hpp_per_liter' => 11500,
        ]);

        // Create Shifts
        \App\Models\Shift::create([
            'nama_shift' => 'Pagi',
            'jam_mulai' => '06:00:00',
            'jam_selesai' => '14:00:00',
            'urutan' => 1,
            'aktif' => true,
        ]);

        \App\Models\Shift::create([
            'nama_shift' => 'Siang',
            'jam_mulai' => '14:00:00',
            'jam_selesai' => '22:00:00',
            'urutan' => 2,
            'aktif' => true,
        ]);

        \App\Models\Shift::create([
            'nama_shift' => 'Malam',
            'jam_mulai' => '22:00:00',
            'jam_selesai' => '06:00:00',
            'urutan' => 3,
            'aktif' => false, // Inactive by default
        ]);
    }
}
