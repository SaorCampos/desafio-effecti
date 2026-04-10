<?php

namespace Tests\Feature\Api;

use App\Infrastructure\Eloquent\Models\ServiceModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_services()
    {
        ServiceModel::factory()->count(10)->create();
        $response = $this->getJson('/api/services');
        $response->assertStatus(200)
                 ->assertJsonCount(10, 'data');
    }
    public function test_it_can_create_a_service()
    {
        $payload = [
            'name' => 'Suporte Técnico Premium',
            'base_value' => 150.50
        ];
        $response = $this->postJson('/api/services', $payload);
        $response->assertStatus(201);
        $this->assertDatabaseHas('services', [
            'name' => 'Suporte Técnico Premium',
            'base_value' => 150.50
        ]);
    }
    public function test_it_fails_to_create_service_with_negative_value()
    {
        $payload = [
            'name' => 'Serviço Inválido',
            'base_value' => -10.00
        ];
        $response = $this->postJson('/api/services', $payload);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['base_value']);
    }
    public function test_it_can_update_a_service()
    {
        $service = ServiceModel::factory()->create([
            'name' => 'Nome Original',
            'base_value' => 100.00
        ]);
        $updatePayload = [
            'name' => 'Nome Atualizado',
            'base_value' => 120.00
        ];
        $response = $this->putJson("/api/services/{$service->id}", $updatePayload);
        $response->assertStatus(200);
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Nome Atualizado',
            'base_value' => 120.00
        ]);
    }
    public function test_it_can_delete_a_service()
    {
        $service = ServiceModel::factory()->create();
        $response = $this->deleteJson("/api/services/{$service->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }
}
