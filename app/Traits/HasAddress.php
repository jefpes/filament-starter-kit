<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

trait HasAddress
{
    public function addresses(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function getMainAddressAttribute(): ?Model
    {
        return $this->addresses()->first();
    }
}
