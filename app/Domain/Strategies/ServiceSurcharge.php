<?php

namespace App\Domain\Strategies;

use App\Domain\Entities\Contract;

class ServiceSurcharge implements DiscountStrategy
{
    private const SURCHARGE_IDS = [5, 10]; // IDs que geram acréscimo

    public function getName(): string { return 'service_surcharge'; }

    public function calculate(Contract $contract): float
    {
        $surcharge = 0;
        foreach ($contract->items as $item) {
            if (in_array($item->serviceId, self::SURCHARGE_IDS)) {
                // Aplica 15% de ACRÉSCIMO sobre esses itens específicos
                $surcharge += ($item->unitValue * $item->quantity) * 0.15;
            }
        }
        return -$surcharge;
    }
}
