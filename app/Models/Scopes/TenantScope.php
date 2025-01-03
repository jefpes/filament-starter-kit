<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\{Builder, Model, Scope};

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (filled(tenant())) {
            $builder->where('tenant_id', tenant()->id); //@phpstan-ignore-line
        }
    }
}
