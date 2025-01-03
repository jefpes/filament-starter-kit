<?php

namespace App\Models;

use App\Traits\{HasPhone, HasPhoto};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany};

/**
 * Class Store
 *
 * @property \App\Models\User $users
 *
 * @method BelongsToMany users()
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $zip_code
 * @property string $state
 * @property string $city
 * @property string $neighborhood
 * @property string $street
 * @property string $number
 * @property string $complement
 * @property bool $active
 * @property \Illuminate\Support\Carbon $created_at
 */
class Store extends Model
{
    use HasFactory;
    use HasUlids;
    use HasPhone;
    use HasPhoto;

    protected $fillable = [
        'name',
        'slug',
        'zip_code',
        'state',
        'city',
        'neighborhood',
        'street',
        'number',
        'complement',
        'active',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
