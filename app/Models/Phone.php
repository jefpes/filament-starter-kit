<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Phone
 *
 * @property string $id
 * @property string $type
 * @property string $ddi
 * @property string $ddd
 * @property string $number
 * @property string $phonable_id
 * @property string $phonable_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Phone extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'type',
        'ddi',
        'ddd',
        'number',
    ];

    public function phonable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
