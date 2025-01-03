<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'zip_code'     => $this->faker->numerify('#####-###'),
            'street'       => $this->faker->streetName,
            'number'       => $this->faker->buildingNumber,
            'neighborhood' => 'Neighborhood',
            'city'         => $this->faker->randomElement([
                'Apuiares',
                'Aquiraz',
                'Caucaia',
                'Chorozinho',
                'Curu',
                'Eusébio',
                'General Sampaio',
                'Guaiúba',
                'Horizonte',
                'Irauçuba',
                'Itaitinga',
                'Itapajé',
                'Maracanaú',
                'Maranguape',
                'Pacatuba',
                'Pacoti',
                'Palmácia',
                'Paraipaba',
                'Paracuru',
                'Pentecoste',
                'Pindoretama',
                'Redenção',
                'São Gonçalo do Amarante',
                'Tejuçuoca',
                'Trairi',
                'Tururu',
                'Umirim',
                'Uruburetama',
            ]),
            'state' => $this->faker->randomElement([
                'Acre', 'Alagoas', 'Amapá', 'Amazonas', 'Bahia', 'Ceará', 'Distrito Federal',
                'Espírito Santo', 'Goiás', 'Maranhão', 'Mato Grosso', 'Mato Grosso do Sul',
                'Minas Gerais', 'Pará', 'Paraíba', 'Paraná', 'Pernambuco', 'Piauí', 'Rio de Janeiro',
                'Rio Grande do Norte', 'Rio Grande do Sul', 'Rondônia', 'Roraima', 'Santa Catarina',
                'São Paulo', 'Sergipe', 'Tocantins',
            ]),
            'complement' => 'Complement test',
        ];
    }

    /**
     * Gera um endereço para um modelo específico.
     *
     * @param string $modelClass Nome da classe do modelo que será relacionado ao endereço.
     * @param int $modelId ID do modelo que será relacionado ao endereço.
     * @return array<string, mixed>
     */
    public function forModel(string $modelClass, int $modelId): array
    {
        // Ajusta os dados para o modelo especificado.
        $addressData = $this->definition();
        $foreignKey  = strtolower(class_basename($modelClass)) . '_id';

        return array_merge($addressData, [$foreignKey => $modelId]);
    }
}
