<?php

namespace App\Application\Services;

use App\Domain\Entities\Contract as ContractEntity;
use App\Domain\Entities\ContractItem;
use App\Domain\Repositories\ContractRepositoryInterface;
use App\Domain\Services\PricingService;

class ContractApplicationHandler
{
    public function __construct(
        private ContractRepositoryInterface $repository,
        private PricingService $pricingService
    ) {}

    public function listAllContracts(array $filters = [])
    {
        return $this->repository->getAllWithClients($filters);
    }

    public function createContract(array $data): array
    {
        $items = array_map(fn($item) => new ContractItem(
            serviceId: $item['service_id'],
            quantity: $item['quantity'],
            unitValue: (float) $item['unitValue']
        ), $data['items']);
        $contractEntity = new ContractEntity(
            id: 0,
            clientId: $data['client_id'],
            items: $items,
            startDate: new \DateTime($data['start_date']),
            status: 'active'
        );
        $calculation = $this->pricingService->calculateBestPrice($contractEntity);
        $this->repository->save($contractEntity, $calculation);
        return $calculation;
    }
}
