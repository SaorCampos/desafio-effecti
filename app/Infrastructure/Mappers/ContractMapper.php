<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Contract as ContractEntity;
use App\Domain\Entities\ContractItem;
use App\Infrastructure\Eloquent\Models\ContractModel;

class ContractMapper
{
    public static function toEntity(ContractModel $model): ContractEntity
    {
        return new ContractEntity(
            id: $model->id,
            clientId: $model->client_id,
            items: $model->items->map(fn($item) => new ContractItem(
                serviceId: $item->service_id,
                quantity: $item->quantity,
                unitValue: (float) $item->unit_value
            ))->toArray(),
            startDate: $model->start_date,
            endDate: $model->end_date,
            status: $model->status
        );
    }
}
