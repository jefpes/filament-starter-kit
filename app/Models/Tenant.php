<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\{HasDatabase, HasDomains};
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

/**
 * Class Tenant
 *
 * @method HasMany domains()
 *
 * @property \App\Models\Domain $domains
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $data
 * @property bool $active
 * @property bool $marketplace
 */

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;
    use HasDomains;

    protected $fillable = ['id', 'name', 'email', 'password', 'data', 'active', 'marketplace', 'monthly_fee'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active'      => 'boolean',
            'marketplace' => 'boolean',
        ];
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string>
     */
    public static function getCustomColumns(): array
    {
        return ['id', 'name', 'email', 'password', 'data', 'active', 'marketplace', 'monthly_fee'];
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getPasswordAttribute(string $value): string
    {
        return $this->attributes['password'] = bcrypt($value);
    }
}
