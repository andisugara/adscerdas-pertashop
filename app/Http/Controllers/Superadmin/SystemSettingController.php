<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->keyBy('key');
        return view('superadmin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'payment_gateway' => 'required|in:manual,duitku',
            'trial_days' => 'required|integer|min:0',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'duitku_merchant_code' => 'nullable|string',
            'duitku_api_key' => 'nullable|string',
            'duitku_callback_url' => 'nullable|url',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_name' => 'required|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value);
        }

        return redirect()->route('superadmin.settings.index')
            ->with('success', 'Settings updated successfully');
    }
}
