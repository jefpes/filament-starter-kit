<?php

namespace App\Models;

use App\Traits\TenantScopeTrait;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BaseModel
 * @package App\Models
 * @property string $id
 * @property string|null $tenant_id
 * @property Tenant $tenant
 */
class BaseModel extends Model
{
    use TenantScopeTrait;
    use HasUlids;

    /**
     * @var array<string>
     */
    protected $with = ['tenant'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
