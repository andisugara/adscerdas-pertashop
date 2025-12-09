<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TankAddition extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jumlah_liter',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_liter' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
