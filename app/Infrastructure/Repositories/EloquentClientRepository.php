<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Client;
use App\Domain\Repositories\ClientRepositoryInterface;
use App\Infrastructure\Eloquent\Models\ClientModel;
use App\Infrastructure\Mappers\ClientMapper;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
    public function findAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = ClientModel::query();
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }
        if (!empty($filters['document'])) {
            $numbersOnly = preg_replace('/\D/', '', $filters['document']);
            $searchHash = hash_hmac('sha256', $numbersOnly, config('app.key'));
            $query->where('document_index', $searchHash);
        }
        if (!empty($filters['client_id'])) {
            $query->where('id', $filters['client_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        $paginator = $query->paginate($perPage);
        return $paginator->through(function ($model) {
            return ClientMapper::toEntity($model);
        });
    }
}
