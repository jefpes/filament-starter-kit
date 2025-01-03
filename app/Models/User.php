<?php

namespace App\Models;

use Filament\Models\Contracts\{FilamentUser, HasAvatar, HasTenants};
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property Role $roles
 * @property Collection $abilities
 * @property Collection $stores
 *
 *
 * @method BelongsToMany roles()
 * @method Builder abilities()
 * @method BelongsToMany stores()
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string|null $tertiary_color
 * @property string|null $quaternary_color
 * @property string|null $quinary_color
 * @property string|null $senary_color
 * @property string|null $font
 * @property bool $navigation_mode
 * @property string|null $avatar_url
 */
class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasTenants, HasAvatar
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasUlids;

    protected $fillable = [
        'name',
        'email',
        'remember_token',
        'email_verified_at',
        'password',
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'quaternary_color',
        'quinary_color',
        'senary_color',
        'font',
        'navigation_mode',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password'        => 'hashed',
            'navigation_mode' => 'bool',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }

    public function hierarchy(string $id): bool
    {
        $h_user_loged = collect($this->roles)->pluck('hierarchy')->max();
        $h_user_param = (collect(User::withTrashed()->find($id)->roles)->pluck('hierarchy')->max() ?? $h_user_loged + 1);

        return $h_user_loged <= $h_user_param;
    }

    public function scopeSearch(Builder $q, string $val): Builder
    {
        return $q->where('name', 'like', "%{$val}%");
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function abilities(): Builder
    {
        return Ability::query()->whereHas('roles', fn ($query) => $query->whereIn('id', $this->roles->pluck('id'))); //@phpstan-ignore-line
    }

    public function hasAbility(string $ability): bool
    {
        return $this->abilities()->where('name', $ability)->exists();
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->stores;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->stores()->whereKey($tenant)->exists();
    }
}
