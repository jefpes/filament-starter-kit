<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Settings::create([
            'name'      => 'Motor Market',
            'opened_in' => '2021-07-02',
            'cnpj'      => '99.999.999/9999-99',
            'about'     => 'Somos uma empresa de software, na qual prezamos pela qualidade e satisfação do cliente.',
            'email'     => 'google@gmail.com',
            'x'         => 'x.com',
            'instagram' => 'instagram.com',
            'facebook'  => 'facebook.com',
            'linkedin'  => 'linkedin.com',
            'youtube'   => 'youtube.com',
            'whatsapp'  => 'whatsapp.com',
        ]);
    }
}
