<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{Role, User};

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::ADMIN->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasAbility(Permission::ADMIN->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility(Permission::ADMIN->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        if ($role->hierarchy < collect($user->roles)->min('hierarchy')) {
            return false;
        }

        return $user->hasAbility(Permission::ADMIN->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        if ($role->hierarchy < collect($user->roles)->min('hierarchy')) {
            return false;
        }

        return $user->hasAbility(Permission::ADMIN->value);
    }

    public function addAbilities(User $user, Role $role): bool
    {
        if ($role->hierarchy < collect($user->roles)->min('hierarchy')) {
            return false;
        }

        return $user->hasAbility(Permission::ADMIN->value);
    }
}
