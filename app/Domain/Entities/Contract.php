<?php

namespace App\Domain\Entities;

class Contract
{
    public function __construct(
        public readonly int $id,
        public readonly int $clientId,
        public readonly ?string $clientName,
        public readonly ?float $totalValue,
        /** @var ContractItem[] */
        public readonly array $items,
        public readonly \DateTimeImmutable $startDate,
        public readonly ?\DateTimeImmutable $endDate = null,
        public readonly string $status = 'active'
    ) {}

    public function getTotalBaseValue(): float
    {
        return array_reduce($this->items, function($carry, ContractItem $item) {
            return $carry + ($item->unitValue * $item->quantity);
        }, 0.0);
    }
}
