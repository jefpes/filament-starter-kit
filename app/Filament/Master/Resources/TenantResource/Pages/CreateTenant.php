<?php

namespace App\Filament\Master\Resources\TenantResource\Pages;

use App\Filament\Master\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function afterCreate(): void
    {
        $tenant = $this->getRecord();
        $tenant->domains()->create(['domain' => $this->data['domain'] . '.' . env('CENTRAL_DOMAIN')]); // @phpstan-ignore-line
    }
}
