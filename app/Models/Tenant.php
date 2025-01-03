<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Tenant extends Model
{
    use SoftDeletes;
    use HasUlids;

    protected $fillable = [
        'code',
        'name',
        'domain',
        'monthly_fee',
        'due_day',
        'include_in_marketplace',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'include_in_marketplace' => 'boolean',
        ];
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
