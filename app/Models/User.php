<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'aktif',
        'active_organization_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aktif' => 'boolean',
        ];
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function tankAdditions()
    {
        return $this->hasMany(TankAddition::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get the organizations that the user belongs to.
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get the active organization.
     */
    public function activeOrganization()
    {
        return $this->belongsTo(Organization::class, 'active_organization_id');
    }

    /**
     * Alias for activeOrganization (for compatibility).
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'active_organization_id');
    }

    /**
     * Switch to different organization.
     */
    public function switchOrganization($organizationId)
    {
        // Check if user has access to this organization
        if ($this->organizations()->where('organizations.id', $organizationId)->exists()) {
            $this->update(['active_organization_id' => $organizationId]);
            return true;
        }
        return false;
    }

    public function isSuperadmin()
    {
        return $this->role === 'superadmin';
    }

    public function isOwner()
    {
        // Check if user has 'owner' role in any organization
        return $this->organizations()->wherePivot('role', 'owner')->exists();
    }

    public function isOperator()
    {
        return $this->organizations()->wherePivot('role', 'operator')->exists();
    }
}
