<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Contract;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ContractRepositoryInterface
{
    public function findById(int $id): ?Contract;
    public function save(Contract $contract, array $calculationHistory): void;
    public function getAllWithClients(): array;
    public function delete(int $id): bool;
    public function findAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
}
