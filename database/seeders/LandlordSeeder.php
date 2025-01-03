<?php

namespace Database\Seeders;

use App\Models\{User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LandlordSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name'              => 'master',
            'email'             => 'master@admin.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('admin'),
        ]);

        $role = $user->roles()->create([
            'name'      => 'master',
            'hierarchy' => 0,
        ]);

        $role->abilities()->createMany([
            ['name' => 'tenant_create'],
            ['name' => 'tenant_read'],
            ['name' => 'tenant_update'],
            ['name' => 'tenant_delete'],
            ['name' => 'user_create'],
            ['name' => 'user_read'],
            ['name' => 'user_update'],
            ['name' => 'user_delete'],
            ['name' => 'admin'],
        ]);
    }
}
