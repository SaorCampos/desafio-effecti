<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\ServiceModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = ServiceModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Serviço ' . $this->faker->unique()->word(),
            'base_value' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
