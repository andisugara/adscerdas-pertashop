<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'organization_id',
        'plan_name',
        'price',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'merchant_order_id',
        'duitku_reference',
        'starts_at',
        'ends_at',
        'approved_at',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the organization that owns the subscription.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user who approved the subscription.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->payment_status === 'paid'
            && $this->ends_at > now();
    }

    /**
     * Check if subscription is expired.
     */
    public function isExpired(): bool
    {
        return $this->ends_at <= now();
    }
}
