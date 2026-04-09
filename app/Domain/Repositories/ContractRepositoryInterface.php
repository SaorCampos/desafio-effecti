<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Contract;

interface ContractRepositoryInterface
{
    public function findById(int $id): ?Contract;
    public function save(Contract $contract, array $calculationHistory): void;
    public function getAllWithClients(): array;
}
