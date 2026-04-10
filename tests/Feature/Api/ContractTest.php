<?php

namespace Tests\Feature\Api;

use App\Infrastructure\Eloquent\Models\ClientModel;
use App\Infrastructure\Eloquent\Models\ContractItemModel;
use App\Infrastructure\Eloquent\Models\ContractModel;
use App\Infrastructure\Eloquent\Models\ServiceModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_contract_with_items_and_calculated_price()
    {
        // Arrange: Criar dependências no banco
        $client = ClientModel::factory()->create();
        $service = ServiceModel::factory()->create(['base_value' => 100.00]);
        $payload = [
            'client_id' => $client->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'active',
            'items' => [
                [
                    'service_id' => $service->id,
                    'quantity' => 10, // Deve ativar desconto de quantidade
                    'unitValue' => 100.00
                ]
            ]
        ];
        // Act: Chama a API
        $response = $this->postJson('/api/contracts', $payload);
        // Assert: Verifica resposta e persistência
        $response->assertStatus(201);
        $this->assertDatabaseHas('contracts', [
            'client_id' => $client->id,
            'total_monthly_value' => 900.00 // 1000 - 10%
        ]);
        $contract = ContractModel::first();
        $this->assertNotNull($contract->calculation_history);
        $this->assertEquals('quantity_discount', $contract->calculation_history['applied_rule']);
    }

    public function test_it_lists_contracts_with_items_and_client()
    {
        // Criar um contrato via factory ou manualmente para testar o index
        ContractModel::factory()
            ->has(ContractItemModel::factory()->count(2), 'items')
            ->create();
        $response = $this->getJson('/api/contracts');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'clientId', 'items', 'startDate', 'status']
            ]);
    }
    public function test_it_recalculates_price_when_items_are_removed()
    {
        // Arrange: Criar um contrato inicial com 10 itens (10% de desc em 1000.00 = 900.00)
        $client = ClientModel::factory()->create();
        $service = ServiceModel::factory()->create(['base_value' => 100.00]);
        $today = now()->format('Y-m-d');
        $contract = ContractModel::factory()->create([
            'client_id' => $client->id,
            'start_date' => $today,
            'total_monthly_value' => 900.00,
        ]);
        // Criamos os 10 itens no banco vinculados a este contrato
        ContractItemModel::factory()->count(10)->create([
            'contract_id' => $contract->id,
            'service_id' => $service->id,
            'quantity' => 1,
            'unit_value' => 100.00
        ]);
        // Act: Atualizamos o contrato para ter apenas 1 item (perde o desconto de quantidade)
        $updatePayload = [
            'client_id' => $client->id,
            'start_date' => $today,
            'status' => 'active',
            'items' => [
                [
                    'service_id' => $service->id,
                    'quantity' => 1,
                    'unitValue' => 100.00
                ]
            ]
        ];
        $response = $this->putJson("/api/contracts/{$contract->id}", $updatePayload);
        // Assert: Verifica resposta e nova persistência
        $response->assertStatus(200);
        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'total_monthly_value' => 100.00,
            'calculation_history->applied_rule' => 'none'
        ]);
        // Garantir que os 10 itens antigos foram removidos e agora só existe 1
        $this->assertEquals(1, ContractItemModel::where('contract_id', $contract->id)->count());
    }
    public function test_it_deletes_contract_and_its_items()
    {
        // Arrange: Criar contrato com itens
        $contract = ContractModel::factory()
            ->has(ContractItemModel::factory()->count(3), 'items')
            ->create();
        $this->assertDatabaseCount('contracts', 1);
        $this->assertDatabaseCount('contract_items', 3);
        // Act: Chama a API de exclusão
        $response = $this->deleteJson("/api/contracts/{$contract->id}");
        // Assert: Verifica se tudo sumiu
        $response->assertStatus(204);
        $this->assertDatabaseCount('contracts', 0);
        $this->assertDatabaseCount('contract_items', 0);
    }
    public function test_it_fails_to_create_contract_without_items()
    {
        $client = ClientModel::factory()->create();
        $payload = [
            'client_id' => $client->id,
            'start_date' => now()->format('Y-m-d'),
            'items' => []
        ];
        $response = $this->postJson('/api/contracts', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }
    public function test_it_fails_to_create_contract_with_invalid_client()
    {
        $payload = [
            'client_id' => 9999,
            'start_date' => now()->format('Y-m-d'),
            'items' => [
                ['service_id' => 1, 'quantity' => 1, 'unitValue' => 100]
            ]
        ];
        $response = $this->postJson('/api/contracts', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['client_id']);
    }
}
