<?php

namespace App\Application\Handlers;

use App\Domain\Repositories\ClientRepositoryInterface;
use App\Domain\Entities\Client;

class ClientApplicationHandler
{
    public function __construct(private ClientRepositoryInterface $repository) {}

    public function handleList() { return $this->repository->findAll(); }

    public function handleStore(array $data)
    {
        $client = new Client(null, $data['name'], $data['document'], $data['email'], $data['status'] ?? 'active');
        return $this->repository->save($client);
    }

    public function handleUpdate(int $id, array $data)
    {
        $client = new Client($id, $data['name'], $data['document'], $data['email'], $data['status']);
        return $this->repository->save($client);
    }

    public function handleDelete(int $id) { return $this->repository->delete($id); }
}
