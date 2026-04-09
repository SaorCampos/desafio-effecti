<?php

namespace App\Domain\Entities;

class Service
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly float $baseValue
    ) {}
}
