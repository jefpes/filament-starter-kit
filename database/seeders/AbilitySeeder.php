<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\{Ability};
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        foreach (Permission::cases() as $permission) {
            Ability::create(['name' => $permission->value]);
        }
    }
}
