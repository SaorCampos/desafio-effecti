<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Service as ServiceEntity;
use App\Infrastructure\Eloquent\Models\ServiceModel;

class ServiceMapper
{
    public static function toEntity(ServiceModel $model): ServiceEntity
    {
        return new ServiceEntity(
            id: $model->id,
            name: $model->name,
            baseValue: (float) $model->base_value
        );
    }
}
