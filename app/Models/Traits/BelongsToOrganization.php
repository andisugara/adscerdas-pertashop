<?php

namespace App\Models\Traits;

use App\Models\Scopes\OrganizationScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToOrganization
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToOrganization(): void
    {
        // Apply global scope
        static::addGlobalScope(new OrganizationScope());

        // Auto-assign organization_id saat create
        static::creating(function ($model) {
            if (Auth::check() && !$model->organization_id && Auth::user()->active_organization_id) {
                $model->organization_id = Auth::user()->active_organization_id;
            }
        });
    }

    /**
     * Get the organization that owns the model.
     */
    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }
}
