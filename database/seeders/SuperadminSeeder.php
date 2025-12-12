<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@pertashop.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'aktif' => true,
            'active_organization_id' => null,
        ]);

        $this->command->info('Superadmin created successfully!');
        $this->command->info('Email: superadmin@pertashop.com');
        $this->command->info('Password: password');
    }
}
