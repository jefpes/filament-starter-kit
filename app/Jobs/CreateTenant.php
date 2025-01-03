<?php

namespace App\Jobs;

use App\Models\{Ability, Role, Settings, Store, Tenant, User};
use Database\Seeders\{AbilitySeeder};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class CreateTenant implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Tenant $tenant)
    {
    }

    public function handle(): void
    {
        $this->tenant->run(function () {
            collect([
                AbilitySeeder::class,
            ])->each(fn ($seeder) => (new $seeder())->run());

            $user        = User::query()->create(['name' => $this->tenant->name, 'email' => $this->tenant->email, 'password' => $this->tenant->password]);
            $maintenance = User::query()->create(['name' => 'maintenance', 'email' => 'maintenance@admin.com', 'password' => 'maintenance']);

            $store = Store::query()->create(['name' => $this->tenant->name, 'slug' => Str::slug($this->tenant->name)]);
            $store->users()->attach([$user->id, $maintenance->id]);

            Settings::query()->create(['name' => $this->tenant->name]);

            $this->createMasterRole($maintenance);
            $this->createAdminRole($user);
        });
    }

    private function createMasterRole(User $user): void
    {
        $role = Role::query()->create(['name' => 'master', 'hierarchy' => 0]);
        $role->users()->attach($user->id);
        $role->abilities()->sync(Ability::query()->pluck('id')->toArray());
    }

    private function createAdminRole(User $user): void
    {
        $role = Role::query()->create(['name' => 'admin', 'hierarchy' => 1]);
        $role->users()->attach($user->id);
        $role->abilities()->sync(Ability::query()->pluck('id')->toArray());
    }
}
