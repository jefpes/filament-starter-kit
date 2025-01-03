<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Phone>
 */
class PhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'   => $this->faker->randomElement(['Casa', 'Trabalho', 'Pai', 'Mãe', 'Whatsapp', 'Outro']),
            'ddi'    => $this->faker->numerify('##'),
            'ddd'    => $this->faker->numerify('##'),
            'number' => $this->faker->numerify('#####-####'),
        ];
    }
}
