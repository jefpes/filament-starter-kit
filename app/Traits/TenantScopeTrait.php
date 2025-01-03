<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait TenantScopeTrait
{
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TenantScope());

        self::getCreating();
    }

    private static function getCreating(): void
    {
        static::creating(function ($model) {
            self::defineTenant($model);
        });
    }

    private static function defineTenant($model): void //@phpstan-ignore-line
    {
        if (Auth::check()) {
            $model->tenant_id = Auth::user()->tenant_id; //@phpstan-ignore-line
        } else {
            if (session()->get('tenant') !== null) {
                $model->tenant_id = session()->get('tenant')->id;
            }
        }
    }
}
