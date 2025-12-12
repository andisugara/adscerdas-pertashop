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
                'title' => 'Laporan',
                'icon' => 'ki-chart-line',
                'roles' => ['owner'], // Only owner can view reports
                'children' => [
                    [
                        'title' => 'Laporan',
                        'route' => 'reports.index',
                        'roles' => ['owner'],
                    ],
                    [
                        'title' => 'Laporan Harian',
                        'route' => 'reports.daily',
                        'roles' => ['owner'],
                    ],
                ],
            ],
            [
                'title' => 'Laporan Harian',
                'route' => 'daily-reports.index',
                'icon' => 'ki-document',
                'roles' => ['owner', 'operator'], // Both can input daily reports
            ],
            [
                'title' => 'Penambahan Tangki',
                'route' => 'tank-additions.index',
                'icon' => 'ki-abstract-26',
                'roles' => ['owner', 'operator'], // Both can input tank additions
            ],
            [
                'title' => 'Pengeluaran',
                'route' => 'expenses.index',
                'icon' => 'ki-wallet',
                'roles' => ['owner'], // Owner only
            ],
            [
                'title' => 'Cetak Struk',
                'route' => 'receipts.index',
                'icon' => 'ki-printer',
                'roles' => ['owner', 'operator'], // Both owner and operator can print receipts
            ],
            [
                'title' => 'Setoran',
                'route' => 'deposits.index',
                'icon' => 'ki-dollar',
                'roles' => ['owner'], // Owner only
            ],
            [
                'title' => 'Gaji',
                'route' => 'salaries.index',
                'icon' => 'ki-wallet',
                'roles' => ['owner'], // Owner only
            ],
        ];

        if ($user && $user->isOwner()) {
            $menu[] = [
                'title' => 'Kelola Pertashop',
                'route' => 'organizations.index',
                'icon' => 'ki-home-2',
                'roles' => ['owner'],
            ];
            $menu[] = [
                'title' => 'Kelola Operator',
                'route' => 'operators.index',
                'icon' => 'ki-people',
                'roles' => ['owner'],
            ];
            $menu[] = [
                'title' => 'My Subscription',
                'route' => 'subscriptions.index',
                'icon' => 'ki-price-tag',
                'roles' => ['owner'],
            ];
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
