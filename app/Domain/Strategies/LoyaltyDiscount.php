<?php

namespace App\Domain\Strategies;

use App\Domain\Entities\Contract;
use DateTime;

class LoyaltyDiscount implements DiscountStrategy
{
    public function getName(): string { return 'loyalty_discount'; }

    public function calculate(Contract $contract): float
    {
        $totalValue = $contract->getTotalBaseValue();
        $yearsActive = $contract->startDate->diff(new DateTime())->y;
        // 5% de desconto por cada ano de fidelidade, limitado a 20%
        $discountPercent = min($yearsActive * 0.05, 0.20);
        return $totalValue * (1 - $discountPercent);
    }
}
