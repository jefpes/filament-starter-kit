<?php

namespace App\Filament\Master\Resources\TenantResource\Pages;

use App\Filament\Master\Resources\TenantResource;
use App\Models\{Ability, Company, Settings, User};
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    public array $user = []; // @phpstan-ignore-line

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->user = [
            'name'              => $data['user_name'],
            'email'             => $data['user_email'],
            'email_verified_at' => now(),
            'password'          => bcrypt($data['user_password']),
            'remember_token'    => Str::random(10),
        ];

        return $data;
    }

    protected function afterCreate(): void
    {
        $company = Company::create(['name' => $this->record->name]); // @phpstan-ignore-line

        $company->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line

        $user = User::create($this->user); // @phpstan-ignore-line

        $user->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line

        Settings::create(['user_id' => $user->id]); // @phpstan-ignore-line

        $role = $user->roles()->create([
            'name'      => 'admin',
            'hierarchy' => 1,
        ]);

        $role->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line

        $role->abilities()->sync(Ability::pluck('id')->toArray()); // @phpstan-ignore-line
    }
}
