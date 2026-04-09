<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Client;

interface ClientRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Client;
    public function save(Client $client): Client;
    public function delete(int $id): bool;
}
