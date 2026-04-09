<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Contract;
use App\Domain\Repositories\ContractRepositoryInterface;
use App\Infrastructure\Eloquent\Models\ContractModel;
use App\Infrastructure\Mappers\ContractMapper;
use Illuminate\Support\Facades\DB;

class EloquentContractRepository implements ContractRepositoryInterface
{
    public function save(Contract $contract, array $calculationHistory): void
    {
        // Usamos transação para garantir que contrato e itens sejam salvos juntos
        DB::transaction(function () use ($contract, $calculationHistory) {
            $model = ContractModel::updateOrCreate(
                ['id' => $contract->id],
                [
                    'client_id' => $contract->clientId,
                    'start_date' => $contract->startDate,
                    'end_date' => $contract->endDate,
                    'total_monthly_value' => $calculationHistory['final_value'],
                    'calculation_history' => $calculationHistory,
                    'status' => $contract->status
                ]
            );
            $model->items()->delete();
            foreach ($contract->items as $item) {
                $model->items()->create([
                    'service_id' => $item['service_id'],
                    'quantity' => $item['quantity'],
                    'unit_value' => $item['unitValue'],
                ]);
            }
        });
    }
    public function findById(int $id): ?Contract
    {
        $model = ContractModel::with('items')->find($id);
        return $model ? ContractMapper::toEntity($model) : null;
    }
    public function getAllWithClients(): array
    {
        return ContractModel::with(['client', 'items'])
            ->get()
            ->map(fn($model) => ContractMapper::toEntity($model))
            ->toArray();
    }
    public function delete(int $id): bool
    {
        return (bool)ContractModel::destroy($id);
    }
}
