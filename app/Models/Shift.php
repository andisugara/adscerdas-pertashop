<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToOrganization;

class Shift extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'nama_shift',
        'jam_mulai',
        'jam_selesai',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'aktif' => 'boolean',
    ];

    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }
}
