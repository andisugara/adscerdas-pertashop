<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'payment_gateway',
                'value' => 'manual',
                'type' => 'string',
                'description' => 'Payment gateway: manual or duitku',
            ],
            [
                'key' => 'trial_days',
                'value' => '14',
                'type' => 'integer',
                'description' => 'Number of trial days for new organizations',
            ],
            [
                'key' => 'monthly_price',
                'value' => '100000',
                'type' => 'integer',
                'description' => 'Monthly subscription price in IDR',
            ],
            [
                'key' => 'yearly_price',
                'value' => '1000000',
                'type' => 'integer',
                'description' => 'Yearly subscription price in IDR',
            ],
            [
                'key' => 'duitku_merchant_code',
                'value' => '',
                'type' => 'string',
                'description' => 'Duitku merchant code',
            ],
            [
                'key' => 'duitku_api_key',
                'value' => '',
                'type' => 'string',
                'description' => 'Duitku API key',
            ],
            [
                'key' => 'duitku_mode',
                'value' => 'sandbox',
                'type' => 'string',
                'description' => 'Duitku mode: sandbox or production',
            ],
            [
                'key' => 'bank_name',
                'value' => 'Bank BCA',
                'type' => 'string',
                'description' => 'Bank name for manual transfer',
            ],
            [
                'key' => 'bank_account_number',
                'value' => '1234567890',
                'type' => 'string',
                'description' => 'Bank account number for manual transfer',
            ],
            [
                'key' => 'bank_account_name',
                'value' => 'PT Pertashop Indonesia',
                'type' => 'string',
                'description' => 'Bank account holder name for manual transfer',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('System settings created successfully!');
    }
}
