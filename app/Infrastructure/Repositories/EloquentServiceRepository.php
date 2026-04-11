<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Service;
use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Infrastructure\Eloquent\Models\ServiceModel;
use App\Infrastructure\Mappers\ServiceMapper;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentServiceRepository implements ServiceRepositoryInterface
{
    public function findAll(): array
    {
        return ServiceModel::all()->map(fn($m) => ServiceMapper::toEntity($m))->toArray();
    }
    public function findById(int $id): ?Service
    {
        $model = ServiceModel::find($id);
        return $model ? ServiceMapper::toEntity($model) : null;
    }
    public function save(Service $service): Service
    {
        $model = ServiceModel::updateOrCreate(
            ['id' => $service->id],
            [
                'name' => $service->name,
                'base_value' => $service->baseValue,
            ]
        );
        return ServiceMapper::toEntity($model);
    }
    public function delete(int $id): bool
    {
        return (bool) ServiceModel::destroy($id);
    }
    public function findAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = ServiceModel::query();
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (isset($filters['min_base_value'])) {
            $query->where('base_value', '>=', $filters['min_base_value']);
        }
        if (isset($filters['max_base_value'])) {
            $query->where('base_value', '<=', $filters['max_base_value']);
        }
        if (isset($filters['service_id'])) {
            $query->where('id', $filters['service_id']);
        }
        $paginator = $query->paginate($perPage);
        return $paginator->through(function ($model) {
            return ServiceMapper::toEntity($model);
        });
    }
}
