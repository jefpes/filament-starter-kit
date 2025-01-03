<?php

namespace App\Observers;

use App\Models\Settings;
use Illuminate\Support\Facades\Storage;

class SettingsObserver
{
    /**
     * Handle the Settings "updated" event.
     */
    public function updated(Settings $settings): void
    {
        $logoToDelete = $settings->getOriginal('logo');

        if ($settings->isDirty('logo')) {
            Storage::delete("public/$logoToDelete");
        }

        $faviconToDelete = $settings->getOriginal('favicon');

        if ($settings->isDirty('favicon')) {
            Storage::delete("public/$faviconToDelete");
        }

        $bgImgToDelete = $settings->getOriginal('bg_img');

        if ($settings->isDirty('bg_img')) {
            Storage::delete("public/$bgImgToDelete");
        }
    }
}
