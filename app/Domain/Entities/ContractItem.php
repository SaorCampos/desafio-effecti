<?php

namespace App\Domain\Entities;

class ContractItem
{
    public function __construct(
        public readonly int $serviceId,
        public readonly int $quantity,
        public readonly float $unitValue
    ) {}
}
