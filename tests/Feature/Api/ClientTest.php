<?php

namespace Tests\Feature\Api;

use App\Infrastructure\Eloquent\Models\ClientModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_clients()
    {
        ClientModel::factory()->count(10)->create();
        $response = $this->getJson('/api/clients');
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }
    public function test_it_can_filter_clients_by_name()
    {
        ClientModel::factory()->create(['name' => 'João Silva']);
        ClientModel::factory()->create(['name' => 'Maria Oliveira']);
        $response = $this->getJson('/api/clients?name=João');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'João Silva');
    }
    public function test_it_can_create_a_client_with_valid_data()
    {
        $document = '52998224725';
        $payload = [
            'name' => 'Cliente Teste',
            'document' => $document,
            'email' => 'teste@exemplo.com',
            'status' => 'active'
        ];
        $expectedHash = hash_hmac('sha256', $document, config('app.key'));
        $response = $this->postJson('/api/clients', $payload);
        $response->assertStatus(201);
        $this->assertDatabaseHas('clients', [
            'document_index' => $expectedHash,
            'email' => 'teste@exemplo.com'
        ]);
    }
    public function test_it_fails_to_create_client_with_invalid_document()
    {
        $payload = [
            'name' => 'Cliente Invalido',
            'document' => '11111111111',
            'email' => 'invalido@exemplo.com',
            'status' => 'active'
        ];
        $response = $this->postJson('/api/clients', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['document']);
    }
    public function test_it_can_update_a_client()
    {
        $validDocument = '52998224725';
        $client = ClientModel::factory()->create([
            'document' => $validDocument,
            'email' => 'original@email.com',
            'status' => 'active'
        ]);
        $updatePayload = [
            'name' => 'Nome Atualizado',
            'document' => $validDocument,
            'email' => 'original@email.com',
            'status' => 'inactive'
        ];
        $response = $this->putJson("/api/clients/{$client->id}", $updatePayload);
        $response->assertStatus(200);
    }
    public function test_it_can_delete_a_client()
    {
        $client = ClientModel::factory()->create();
        $response = $this->deleteJson("/api/clients/{$client->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
    public function test_it_fails_on_duplicate_email()
    {
        ClientModel::factory()->create(['email' => 'duplicado@exemplo.com']);
        $payload = [
            'name' => 'Outro Cliente',
            'document' => '05432167000',
            'email' => 'duplicado@exemplo.com',
            'status' => 'active'
        ];
        $response = $this->postJson('/api/clients', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
