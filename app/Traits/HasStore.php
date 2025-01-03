<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\{Store};
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

/**
 * Trait HasStore
 *
 * @property \App\Models\Store $store
 *
 * @method BelongsTo store()
 */
trait HasStore
{
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

}
