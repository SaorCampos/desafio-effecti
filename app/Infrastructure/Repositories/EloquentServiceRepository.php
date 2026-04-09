<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Service;
use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Infrastructure\Eloquent\Models\ServiceModel;
use App\Infrastructure\Mappers\ServiceMapper;

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
}
