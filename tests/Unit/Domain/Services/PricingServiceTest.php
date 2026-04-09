<?php

namespace Tests\Unit\Domain\Services;

use Tests\TestCase;
use App\Domain\Entities\Contract;
use App\Domain\Entities\ContractItem;
use App\Domain\Services\PricingService;
use App\Domain\Strategies\QuantityDiscount;
use App\Domain\Strategies\LoyaltyDiscount;

class PricingServiceTest extends TestCase
{
    private PricingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PricingService(new QuantityDiscount(), new LoyaltyDiscount());
    }

    public function test_should_apply_loyalty_discount_when_it_is_better()
    {
        // 6 itens (10% desc) mas 5 anos de casa (25% desc -> limitado a 20%)
        $items = [new ContractItem(1, 6, 100.00)]; // Total 600.00
        $contract = new Contract(0, 1, $items, (new \DateTime())->modify('-5 years'));
        $result = $this->service->calculateBestPrice($contract);
        $this->assertEquals('loyalty_discount', $result['applied_rule']);
        $this->assertEquals(480.00, $result['final_value']); // 600 - 20%
    }

    public function test_should_apply_quantity_discount_when_it_is_better()
    {
        // 10 itens (10% desc) e cliente novo (0% desc)
        $items = [new ContractItem(1, 10, 100.00)]; // Total 1000.00
        $contract = new Contract(0, 1, $items, new \DateTime());
        $result = $this->service->calculateBestPrice($contract);
        $this->assertEquals('quantity_discount', $result['applied_rule']);
        $this->assertEquals(900.00, $result['final_value']);
    }
}
