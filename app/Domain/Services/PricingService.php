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
        $baseTotal = $contract->getTotalBaseValue();
        $bestDiscount = 0.0;
        $appliedRule = 'none';
        foreach ($this->strategies as $strategy) {
            $discount = $strategy->calculate($contract);
            if ($discount > $bestDiscount) {
                $bestDiscount = $discount;
                $appliedRule = $strategy->getName();
            }
        }
        return [
            'base_value' => $baseTotal,
            'discount_value' => $bestDiscount,
            'final_value' => $baseTotal - $bestDiscount,
            'applied_rule' => $appliedRule,
        ];
    }
}
