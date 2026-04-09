<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Client;
use App\Domain\Repositories\ClientRepositoryInterface;
use App\Infrastructure\Eloquent\Models\ClientModel;
use App\Infrastructure\Mappers\ClientMapper;

class EloquentClientRepository implements ClientRepositoryInterface
{
    public function findAll(): array
    {
        return ClientModel::all()->map(fn($m) => ClientMapper::toEntity($m))->toArray();
    }
    public function findById(int $id): ?Client
    {
        $model = ClientModel::find($id);
        return $model ? ClientMapper::toEntity($model) : null;
    }
    public function save(Client $client): Client
    {
        $model = ClientModel::updateOrCreate(
            ['id' => $client->id],
            [
                'name' => $client->name,
                'document' => $client->document,
                'email' => $client->email,
                'status' => $client->status,
            ]
        );
        return ClientMapper::toEntity($model);
    }
    public function delete(int $id): bool
    {
        return (bool) ClientModel::destroy($id);
    }
}
