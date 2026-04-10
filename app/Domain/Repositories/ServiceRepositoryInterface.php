<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Service;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ServiceRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Service;
    public function save(Service $service): Service;
    public function delete(int $id): bool;
    public function findAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
}
