<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class OrganizationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check() && Auth::user()->active_organization_id) {
            // Superadmin bisa lihat semua data
            if (!Auth::user()->isSuperadmin()) {
                $builder->where($model->getTable() . '.organization_id', Auth::user()->active_organization_id);
            }
        }
    }
}
