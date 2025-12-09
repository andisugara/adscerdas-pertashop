<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_pertashop',
        'kode_pertashop',
        'alamat',
        'harga_jual',
        'rumus',
        'hpp_per_liter',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'rumus' => 'decimal:2',
        'hpp_per_liter' => 'decimal:2',
    ];
}
