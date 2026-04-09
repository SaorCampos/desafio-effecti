<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\ContractItemModel;
use App\Infrastructure\Eloquent\Models\ServiceModel;
use Illuminate\Database\Eloquent\Factories\Factory;


class ContractItemFactory extends Factory
{
    protected $model = ContractItemModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contract_id' => ContractFactory::new(),
            'service_id' => ServiceModel::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit_value' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
