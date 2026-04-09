<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Service;

interface ServiceRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Service;
    public function save(Service $service): Service;
    public function delete(int $id): bool;
}
