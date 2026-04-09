<?php

namespace App\Domain\Strategies;

use App\Domain\Entities\Contract;

class QuantityDiscount implements DiscountStrategy
{
    public function getName(): string { return 'quantity_discount'; }

    public function calculate(Contract $contract): float
    {
        $totalItems = array_reduce($contract->items, fn($carry, $item) => $carry + $item->quantity, 0);
        $totalValue = $contract->getTotalBaseValue();
        // Se tiver mais de 5 itens, ganha 10% de desconto
        return $totalItems >= 5 ? $totalValue * 0.90 : $totalValue;
    }
}
