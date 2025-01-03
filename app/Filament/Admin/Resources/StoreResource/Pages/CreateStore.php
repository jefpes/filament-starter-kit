<?php

namespace App\Filament\Admin\Resources\StoreResource\Pages;

use App\Filament\Admin\Resources\StoreResource;
use App\Models\{Store, User};
use Filament\Resources\Pages\CreateRecord;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    protected function afterCreate(): void
    {
        // Obtem todos os usuários que têm a habilidade 'master'
        $masterUsers = User::all()->filter(fn ($user) => $user->hasAbility('master'));

        // Itere por cada loja e sincronize os usuários 'master'
        Store::all()->each(function ($store) use ($masterUsers) {
            $store->users()->syncWithoutDetaching($masterUsers->pluck('id')->toArray());
        });
    }

    public function getSubNavigation(): array
    {
        if (filled($cluster = static::getCluster())) {
            return $this->generateNavigationItems($cluster::getClusteredComponents());
        }

        return [];
    }
}
