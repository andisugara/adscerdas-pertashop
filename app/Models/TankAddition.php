<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToOrganization;

class TankAddition extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'user_id',
        'tanggal',
        'no_polisi',
        'shipment_no',
        'nama_pengemudi',
        'no_so_sa',
        'jumlah_liter',
        'stok_awal',
        'stok_akhir',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_liter' => 'decimal:2',
        'stok_awal' => 'decimal:2',
        'stok_akhir' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get organization setting for calculation
     */
    public function getSetting()
    {
        return Setting::where('organization_id', $this->organization_id)->first();
    }

    /**
     * Calculate Stok Awal Liter (SAL)
     */
    public function getStokAwalLiterAttribute()
    {
        if (!$this->stok_awal) return 0;
        $setting = $this->getSetting();
        $rumus = $setting ? $setting->rumus : 1;
        return $this->stok_awal * $rumus;
    }

    /**
     * Calculate Stok Akhir Liter (SAKL)
     */
    public function getStokAkhirLiterAttribute()
    {
        if (!$this->stok_akhir) return 0;
        $setting = $this->getSetting();
        $rumus = $setting ? $setting->rumus : 1;
        return $this->stok_akhir * $rumus;
    }

    /**
     * Calculate Loses
     * Loses = (SAL + Jumlah Liter) - SAKL
     */
    public function getLosesAttribute()
    {
        if (!$this->stok_awal || !$this->stok_akhir) return 0;
        return ($this->stok_awal_liter + $this->jumlah_liter) - $this->stok_akhir_liter;
    }
}
