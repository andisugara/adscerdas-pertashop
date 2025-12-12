<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'kode_pertashop',
        'phone',
        'email',
        'address',
        'logo',
        'harga_jual',
        'rumus',
        'hpp_per_liter',
        'stok_awal',
        'totalisator_awal',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'harga_jual' => 'decimal:2',
        'rumus' => 'decimal:2',
        'hpp_per_liter' => 'decimal:2',
        'stok_awal' => 'decimal:3',
        'totalisator_awal' => 'decimal:3',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($organization) {
            if (!$organization->slug) {
                $organization->slug = \Illuminate\Support\Str::slug($organization->name);
            }
        });
    }

    /**
     * Get the users for the organization.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get the subscriptions for the organization.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription.
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    /**
     * Get the settings for the organization.
     */
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    /**
     * Check if organization has active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /**
     * Get the trial subscription.
     */
    public function trialSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('plan_name', 'trial')
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    /**
     * Check if organization is in trial period.
     */
    public function isInTrial(): bool
    {
        return $this->trialSubscription()->exists();
    }
}
