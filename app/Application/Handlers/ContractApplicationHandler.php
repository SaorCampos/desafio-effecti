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

    public function handleList()
    {
        return $this->repository->getAllWithClients();
    }

    public function handleStore(array $data): array
    {
        return $this->saveContract(0, $data);
    }

    public function handleUpdate(int $id, array $data): array
    {
        return $this->saveContract($id, $data);
    }
    public function handleDelete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    private function saveContract(int $id, array $data): array
    {
        // Converte os dados brutos da Request para Entidades de Domínio (ContractItem)
        $items = array_map(fn($item) => new ContractItem(
            serviceId: $item['service_id'],
            quantity: $item['quantity'],
            unitValue: (float) $item['unit_value']
        ), $data['items']);
        $contractEntity = new ContractEntity(
            id: $id,
            clientId: $data['client_id'],
            items: $items,
            startDate: new \DateTime($data['start_date']),
            endDate: isset($data['end_date']) ? new \DateTime($data['end_date']) : null,
            status: $data['status'] ?? 'active'
        );
        // O Core da Regra de Negócio: Calcula qual estratégia dá o maior desconto
        $calculation = $this->pricingService->calculateBestPrice($contractEntity);
        // Persistência Atómica via Repository
        $this->repository->save($contractEntity, $calculation);

        return $calculation;
    }
}
