<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Relations\{MorphMany};

/**
 * Trait HasPhoto
 *
 * @property \App\Models\Photo $photos
 * @property \App\Models\Photo $mainPhoto
 * @property \App\Models\Photo $publicPhotos
 *
 * @method \App\Models\Photo photos()
 * @method \App\Models\Photo mainPhoto()
 * @method \App\Models\Photo publicPhotos()
 *
 * @method \Illuminate\Database\Eloquent\Builder withPublicPhotos()
 * @method \Illuminate\Database\Eloquent\Builder withMainPhoto()
 *
 * @method string getPhotoDirectory()
 * @method string getPhotoNamePrefix()
 *
 * @property-read string $name
 * @property-read string $plate
 */
trait HasPhoto
{
    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function mainPhoto(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->morphOne(Photo::class, 'photoable')->where('main', true);
    }

    public function publicPhotos(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->morphMany(Photo::class, 'photoable')->where('public', true);
    }

    public function scopeWithPublicPhotos(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->with(['photos' => function ($query) {
            $query->where('public', true);
        }]);
    }

    public function scopeWithMainPhoto(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->with(['photos' => function ($query) {
            $query->where('public', true)->where('main', true);
        }]);
    }

    public function getPhotoDirectory(): string
    {
        return 'photos/' . strtolower(class_basename($this));
    }

    public function getPhotoNamePrefix(): string
    {
        $attributes = ['name', 'plate'];

        // Verifique cada atributo na ordem definida e use o primeiro que existir
        foreach ($attributes as $attribute) {
            if (isset($this->$attribute) && !empty($this->$attribute)) {
                return $this->$attribute;
            }
        }

        // Caso nenhum atributo seja encontrado, use o nome da classe como fallback
        return class_basename($this);
    }
}
