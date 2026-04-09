<?php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\ClientModel;
use App\Infrastructure\Eloquent\ServiceModel;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        UserModel::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        ClientModel::factory(10)->create();
        ServiceModel::factory(10)->create();
    }
}
