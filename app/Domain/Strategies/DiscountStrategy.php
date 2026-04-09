<?php

namespace App\Domain\Strategies;

use App\Domain\Entities\Contract;

interface DiscountStrategy
{
    public function getName(): string;
    public function calculate(Contract $contract): float;
}
