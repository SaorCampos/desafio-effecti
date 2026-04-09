<?php

namespace App\Domain\Strategies;

use App\Domain\Entities\Contract;

class QuantityDiscount implements DiscountStrategy
{
    public function getName(): string
    {
        return 'quantity_discount';
    }

    public function calculate(Contract $contract): float
    {
        $totalItems = array_reduce($contract->items, fn($carry, $item) => $carry + $item->quantity, 0);
        $totalValue = $contract->getTotalBaseValue();
        if ($totalItems >= 10) {
            return $totalValue * 0.10; // Retorna 10% do total
        }
        return 0.0;
    }
}
