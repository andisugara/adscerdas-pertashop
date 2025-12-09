<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $fillable = [
        'user_id',
        'shift_id',
        'tanggal',
        'totalisator_awal',
        'totalisator_akhir',
        'stok_awal_mm',
        'stok_akhir_mm',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'totalisator_awal' => 'decimal:3',
        'totalisator_akhir' => 'decimal:3',
        'stok_awal_mm' => 'decimal:2',
        'stok_akhir_mm' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    // Calculated properties
    public function getSalesLiterAttribute()
    {
        return $this->totalisator_awal - $this->totalisator_akhir;
    }

    public function getStokAwalLiterAttribute()
    {
        $setting = Setting::first();
        return $this->stok_awal_mm * ($setting->rumus ?? 2.09);
    }

    public function getStokAkhirLiterAttribute()
    {
        $setting = Setting::first();
        return $this->stok_akhir_mm * ($setting->rumus ?? 2.09);
    }
}
