<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\{Builder, Model};

/**
 * Class Role
 *
 * @property \App\Models\User $users
 * @property \App\Models\Ability $abilities
 *
 * @method BelongsToMany users()
 * @method BelongsToMany abilities()
 *
 * @property string $id
 * @property string $name
 * @property int $hierarchy
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Role extends Model
{
    use HasUlids;
    use HasFactory;

    protected $fillable = [
        'name',
        'hierarchy',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class);
    }

    public function scopeHierarchy(Builder $q, User $user): Builder
    {
        return $q->where('hierarchy', '>=', $user->roles()->pluck('hierarchy')->max());
    }
}
