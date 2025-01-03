<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Phone;

trait HasPhone
{
    public function phones(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }
}
