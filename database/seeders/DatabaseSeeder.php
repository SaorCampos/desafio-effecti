<?php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\Models\ClientModel;
use App\Infrastructure\Eloquent\Models\ServiceModel;
use App\Infrastructure\Eloquent\Models\UserModel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        UserModel::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        ClientModel::factory(10)->create();
        ServiceModel::factory(10)->create();
    }
}
