<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Client as ClientEntity;
use App\Infrastructure\Eloquent\Models\ClientModel;

class ClientMapper
{
    public static function toEntity(ClientModel $model): ClientEntity
    {
        return new ClientEntity(
            id: $model->id,
            name: $model->name,
            document: $model->document,
            email: $model->email,
            status: $model->status
        );
    }
}
