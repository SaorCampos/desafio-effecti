<?php

namespace App\Domain\Strategies;

use App\Domain\Entities\Contract;

class ProgressiveDiscount implements DiscountStrategy
{
    public function getName(): string { return 'progressive_discount'; }

    public function calculate(Contract $contract): float
    {
        $total = $contract->getTotalBaseValue();
        $rate = 0;
        if ($total >= 5000) {
            $rate = 0.15;
        } elseif ($total >= 2000) {
            $rate = 0.10;
        }
        return $total * $rate;
    }
}
