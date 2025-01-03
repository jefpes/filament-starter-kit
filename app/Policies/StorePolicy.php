<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{Store, User};

class StorePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::MASTER->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Store $store): bool
    {
        return $user->hasAbility(Permission::MASTER->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility(Permission::MASTER->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Store $store): bool
    {
        return $user->hasAbility(Permission::MASTER->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Store $store): bool
    {
        if (Store::query()->count() === 1) {
            return false;
        }

        return $user->hasAbility(Permission::MASTER->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function transfer(User $user, Store $store): bool
    {
        if (Store::query()->count() === 1) {
            return false;
        }

        return $user->hasAbility(Permission::MASTER->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Store $store): bool
    {
        return $user->hasAbility(Permission::MASTER->value);
    }
}
