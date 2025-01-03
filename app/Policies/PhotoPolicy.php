<?php

namespace App\Policies;

use App\Models\{Photo, User};
use Illuminate\Auth\Access\{HandlesAuthorization};

class PhotoPolicy
{
    use HandlesAuthorization;

    private function getPhotoableType($photoable): string // @phpstan-ignore-line
    {
        return strtolower(class_basename($photoable));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Photo $photo): bool
    {
        return $user->hasAbility($this->getPhotoableType($photo->photoable) . '_photo_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, $photoable): bool // @phpstan-ignore-line
    {
        return $user->hasAbility($this->getPhotoableType($photoable) . '_photo_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Photo $photo): bool
    {
        return $user->hasAbility($this->getPhotoableType($photo->photoable) . '_photo_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Photo $photo): bool
    {
        return $user->hasAbility($this->getPhotoableType($photo->photoable) . '_photo_delete');
    }

    public function setMainPublic(User $user, Photo $photo): bool
    {
        return $this->delete($user, $photo) && $this->create($user, $photo->photoable);
    }
}
