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
        $settings = \App\Models\Settings::create([
            'name'      => 'Starter',
            'opened_in' => '2021-07-02',
            'cnpj'      => '99.999.999/9999-99',
            'about'     => 'Somos uma empresa de venda de veículos, na qual prezamos pela qualidade e satisfação do cliente.',
            'email'     => 'google@gmail.com',
            'x'         => 'x.com',
            'instagram' => 'instagram.com',
            'facebook'  => 'facebook.com',
            'linkedin'  => 'linkedin.com',
            'youtube'   => 'youtube.com',
            'whatsapp'  => 'whatsapp.com',
        ]);

        $settings->addresses()->create([
            'zip_code'     => '99999-999',
            'street'       => 'Rua dos Bobos',
            'number'       => '0',
            'neighborhood' => 'Bairro dos Bobos',
            'city'         => 'Fortaleza',
            'state'        => 'CE',
        ]);

        $settings->phones()->create([
            'type'   => 'Comercial',
            'ddi'    => '55',
            'ddd'    => '85',
            'number' => '99999-9999',
        ]);
    }
}
