<?php

namespace App\Application\Handlers;

use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Domain\Entities\Service;

class ServiceApplicationHandler
{
    public function __construct(private ServiceRepositoryInterface $repository) {}

    public function handleList() { return $this->repository->findAll(); }

    public function handleStore(array $data)
    {
        $service = new Service(null, $data['name'], (float) $data['base_value']);
        return $this->repository->save($service);
    }

    public function handleUpdate(int $id, array $data)
    {
        $service = new Service($id, $data['name'], (float) $data['base_value']);
        return $this->repository->save($service);
    }

    public function handleDelete(int $id) { return $this->repository->delete($id); }
}
