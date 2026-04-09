<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\ClientModel;
use App\Infrastructure\Eloquent\Models\ContractModel;
use Illuminate\Database\Eloquent\Factories\Factory;


class ContractFactory extends Factory
{
    protected $model = ContractModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => ClientModel::factory(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => 'active',
            'total_monthly_value' => $this->faker->randomFloat(2, 100, 1000),
            'calculation_history' => null,
        ];
    }
}
