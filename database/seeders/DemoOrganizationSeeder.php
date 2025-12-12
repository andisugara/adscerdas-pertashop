<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\Setting;
use App\Models\Shift;
use Illuminate\Support\Facades\Hash;

class DemoOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo organization
        $org = Organization::create([
            'name' => 'Pertashop Demo',
            'slug' => 'pertashop-demo',
            'phone' => '081234567890',
            'email' => 'demo@pertashop.com',
            'address' => 'Jl. Demo No. 123, Jakarta',
            'harga_jual' => 12000,
            'rumus' => 2.09,
            'hpp_per_liter' => 11500,
            'stok_awal' => 5000.000,
            'totalisator_awal' => 10000.000,
            'is_active' => true,
        ]);

        // Create trial subscription for demo org
        Subscription::create([
            'organization_id' => $org->id,
            'plan_name' => 'trial',
            'price' => 0,
            'status' => 'active',
            'payment_method' => null,
            'payment_status' => 'paid',
            'starts_at' => now(),
            'ends_at' => now()->addDays(14),
            'approved_at' => now(),
            'approved_by' => null,
        ]);

        // Create owner user
        $owner = User::create([
            'name' => 'Demo Owner',
            'email' => 'owner@pertashop.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'aktif' => true,
            'active_organization_id' => $org->id,
        ]);

        // Create operator user
        $operator = User::create([
            'name' => 'Demo Operator',
            'email' => 'operator@pertashop.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'aktif' => true,
            'active_organization_id' => $org->id,
        ]);

        // Attach users to organization
        $org->users()->attach($owner->id, ['role' => 'owner', 'is_active' => true]);
        $org->users()->attach($operator->id, ['role' => 'operator', 'is_active' => true]);

        // Create trial subscription
        Subscription::create([
            'organization_id' => $org->id,
            'plan_name' => 'trial',
            'price' => 0,
            'status' => 'active',
            'payment_method' => 'manual',
            'payment_status' => 'paid',
            'starts_at' => now(),
            'ends_at' => now()->addDays(14),
            'approved_at' => now(),
            'approved_by' => 1, // superadmin
        ]);

        // Create settings for organization
        Setting::create([
            'organization_id' => $org->id,
            'nama_pertashop' => 'Pertashop Demo',
            'kode_pertashop' => 'DEMO001',
            'alamat' => 'Jl. Demo No. 123, Jakarta',
            'harga_jual' => 12000,
            'rumus' => 2.09,
            'hpp_per_liter' => 11500,
        ]);

        // Create shifts for organization
        $shifts = [
            ['nama_shift' => 'Pagi', 'jam_mulai' => '07:00', 'jam_selesai' => '15:00', 'urutan' => 1],
            ['nama_shift' => 'Siang', 'jam_mulai' => '15:00', 'jam_selesai' => '23:00', 'urutan' => 2],
            ['nama_shift' => 'Malam', 'jam_mulai' => '23:00', 'jam_selesai' => '07:00', 'urutan' => 3],
        ];

        foreach ($shifts as $shift) {
            Shift::create(array_merge($shift, [
                'organization_id' => $org->id,
                'aktif' => true,
            ]));
        }

        $this->command->info('Demo organization created successfully!');
        $this->command->info('Owner Email: owner@pertashop.com / Password: password');
        $this->command->info('Operator Email: operator@pertashop.com / Password: password');
    }
}
