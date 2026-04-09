<?php

namespace App\Domain\Services;

use App\Domain\Entities\Contract;
use App\Domain\Strategies\DiscountStrategy;

class PricingService
{
    private array $strategies;

    public function __construct(DiscountStrategy ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function calculateBestPrice(Contract $contract): array
    {
        $results = [];
        $baseValue = $contract->getTotalBaseValue();
        foreach ($this->strategies as $strategy) {
            $results[] = [
                'rule' => $strategy->getName(),
                'value' => (float) $strategy->calculate($contract)
            ];
        }
        // Encontra o menor valor (maior desconto)
        $bestOption = collect($results)->sortBy('value')->first();
        return [
            'base_total' => $baseValue,
            'applied_rule' => $bestOption['rule'],
            'final_value' => $bestOption['value'],
            'rules_evaluated' => $results
        ];
    }
}
