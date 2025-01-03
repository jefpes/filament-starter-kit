<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class Photo
 *
 * @method MorphTo photoable()
 *
 * @property Model $photoable
 *
 * @property string $id
 * @property string $path
 * @property bool $main
 * @property bool $public
 */
class Photo extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'path',
        'main',
        'public',
        'photoable_id',
        'photoable_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'main'   => 'boolean',
            'public' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($photo) {
            if (Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }
        });

        static::saving(function ($photo) {
            if ($photo->isDirty('path')) {
                $photo->handlePhotoSaving();
            }
        });

        static::updating(function ($photo) {
            if ($photo->isDirty('path')) {
                $photo->deleteOldPhoto();
            }
        });
    }

    public function photoable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function handlePhotoSaving(): void
    {
        if (!$this->path) {
            return;
        }

        $directory = $this->photoable->getPhotoDirectory(); //@phpstan-ignore-line

        $newFileName = sprintf(
            '%s_%s.%s',
            Str::slug($this->photoable->getPhotoNamePrefix()), //@phpstan-ignore-line
            (string) Str::uuid(),
            pathinfo($this->path, PATHINFO_EXTENSION)
        );

        $newFilePath = $directory . '/' . $newFileName;

        if (Storage::disk('public')->exists($this->path)) {
            Storage::disk('public')->move($this->path, $newFilePath);
            $this->path = $newFilePath;
        }
    }

    protected function deleteOldPhoto(): void
    {
        $originalPath = $this->getOriginal('path');

        if ($originalPath && Storage::disk('public')->exists($originalPath)) {
            Storage::disk('public')->delete($originalPath);
        }
    }
}
