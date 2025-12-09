<?php

use App\Models\Setting;

if (!function_exists('getSetting')) {
    function getSetting()
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = new Setting([
                'nama_pertashop' => 'Pertashop',
                'kode_pertashop' => 'PTS001',
                'alamat' => '',
                'harga_jual' => 12000,
                'rumus' => 2.09,
                'hpp_per_liter' => 11500,
            ]);
        }

        return $setting;
    }
}

if (!function_exists('getMenu')) {
    function getMenu()
    {
        $user = auth()->user();

        $menu = [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'ki-home',
                'roles' => ['owner', 'operator'],
            ],
            [
                'title' => 'Laporan Harian',
                'route' => 'daily-reports.index',
                'icon' => 'ki-document',
                'roles' => ['owner', 'operator'],
            ],
            [
                'title' => 'Penambahan Tangki',
                'route' => 'tank-additions.index',
                'icon' => 'ki-abstract-26',
                'roles' => ['owner', 'operator'],
            ],
            [
                'title' => 'Pengeluaran',
                'route' => 'expenses.index',
                'icon' => 'ki-wallet',
                'roles' => ['owner', 'operator'],
            ],
            [
                'title' => 'Setoran',
                'route' => 'deposits.index',
                'icon' => 'ki-dollar',
                'roles' => ['owner', 'operator'],
            ],
        ];

        if ($user && $user->isOwner()) {
            $menu[] = [
                'title' => 'Pengaturan',
                'icon' => 'ki-gear',
                'roles' => ['owner'],
                'children' => [
                    [
                        'title' => 'Setting Pertashop',
                        'route' => 'settings.index',
                        'roles' => ['owner'],
                    ],
                    [
                        'title' => 'Manajemen Shift',
                        'route' => 'shifts.index',
                        'roles' => ['owner'],
                    ],
                ],
            ];
        }

        // Filter menu based on user role
        if ($user) {
            return collect($menu)->filter(function ($item) use ($user) {
                return in_array($user->role, $item['roles']);
            })->toArray();
        }

        return [];
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($angka, $decimal = 2)
    {
        return number_format($angka, $decimal, ',', '.');
    }
}
