<?php

namespace Database\Seeders;

use App\Models\{Ability, Store, User};
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

        $this->call(StoreSeeder::class);

        $user->stores()->sync(Store::pluck('id')->toArray());

        // Criar a role 'master'
        $role = $user->roles()->create([
            'name'      => 'master',
            'hierarchy' => 0,
        ]);

        $this->call(AbilitySeeder::class);

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        $user = User::create([
            'name'              => 'admin',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => '$2y$12$./r6VDrmKTNOxFte58tY..03PmNJgc856574gU8toIftu.KZ6Scwi',
            'remember_token'    => 'ulju8vGmyW7Ju2YXZLhYradlbIBVK1kUWG7Moow0ENieWYwbSKpiXJSfNMXc',
        ]);

        $this->call(StoreSeeder::class);

        $user->stores()->sync(Store::pluck('id')->toArray());

        $role = $user->roles()->create([
            'name'      => 'admin',
            'hierarchy' => 1,
        ]);

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        $this->call(SettingsSeeder::class);
    }
}
