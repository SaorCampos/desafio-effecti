<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\ClientModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = ClientModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'document' => $this->faker->unique()->numerify('###########'), // CPF simulado
            'email' => $this->faker->unique()->safeEmail(),
            'status' => 'active',
        ];
    }
}
