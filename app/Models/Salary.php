<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToOrganization;

class Salary extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'bulan',
        'jumlah',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];
}
