<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\{Ability, Settings, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Criar o usuário 'master'
        $user = User::create([
            'name'              => 'master',
            'email'             => 'master@admin.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('admin'),
        ]);

        Settings::create(['user_id' => $user->id]);

        // Criar a role 'master'
        $role = $user->roles()->create([
            'name'      => 'master',
            'hierarchy' => 0,
        ]);

        foreach (Permission::cases() as $permission) {
            $role->abilities()->create(['name' => $permission->value]);
        }

        $user = User::create([
            'name'              => 'admin',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => '$2y$12$./r6VDrmKTNOxFte58tY..03PmNJgc856574gU8toIftu.KZ6Scwi',
            'remember_token'    => 'ulju8vGmyW7Ju2YXZLhYradlbIBVK1kUWG7Moow0ENieWYwbSKpiXJSfNMXc',
        ]);

        Settings::create(['user_id' => $user->id]);

        $role = $user->roles()->create([
            'name'      => 'admin',
            'hierarchy' => 1,
        ]);

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        $this->call(CompanySeeder::class);
    }
}
