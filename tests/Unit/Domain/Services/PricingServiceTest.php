<?php

namespace Tests\Unit\Domain\Services;

use App\Domain\Entities\Contract;
use App\Domain\Entities\ContractItem;
use App\Domain\Services\PricingService;
use App\Domain\Strategies\LoyaltyDiscount;
use App\Domain\Strategies\QuantityDiscount;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    public function test_it_should_apply_the_best_discount_for_the_client()
    {
        // 1. Setup do Cenário
        // Simulando 6 itens (ativa desconto de quantidade de 10%)
        // Simulando contrato iniciado há 3 anos (ativa fidelidade de 15% - 5% ao ano)
        $items = [
            new ContractItem(serviceId: 1, quantity: 6, unitValue: 100.00)
        ];
        $contract = new Contract(
            id: 0,
            clientId: 1,
            items: $items,
            startDate: (new \DateTime())->modify('-3 years'),
            status: 'active'
        );
        $service = new PricingService(
            new QuantityDiscount(),
            new LoyaltyDiscount()
        );
        // 2. Execução
        $result = $service->calculateBestPrice($contract);
        // 3. Asserções
        $this->assertEquals(510.00, $result['final_value']);
        $this->assertEquals('loyalty_discount', $result['applied_rule']);
    }
}
