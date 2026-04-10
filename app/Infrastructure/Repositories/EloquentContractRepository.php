<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Contract;
use App\Domain\Repositories\ContractRepositoryInterface;
use App\Infrastructure\Eloquent\Models\ContractModel;
use App\Infrastructure\Mappers\ContractMapper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
                    'service_id' => $item->serviceId,
                    'quantity'   => $item->quantity,
                    'unit_value' => $item->unitValue,
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
        return DB::transaction(function () use ($id) {
            $model = ContractModel::find($id);
            if (!$model) {
                return false;
            }
            $model->items()->delete();
            return (bool) $model->delete();
        });
    }
    public function findAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = ContractModel::query()
            ->with(['client', 'items.service']);
        if (!empty($filters['client_name'])) {
            $query->whereHas('client', fn($q) => $q->where('name', 'like', '%' . $filters['client_name'] . '%'));
        }
        if (!empty($filters['service_name'])) {
            $query->whereHas('items.service', fn($q) => $q->where('name', 'like', '%' . $filters['service_name'] . '%'));
        }
        if (!empty($filters['start_date'])) {
            $query->whereDate('start_date', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('end_date', '<=', $filters['end_date']);
        }
        if (!empty($filters['contract_id'])) {
            $query->where('id', $filters['contract_id']);
        }
        $paginator = $query->paginate($perPage);
        return $paginator->through(function ($model) {
            return ContractMapper::toEntity($model);
        });
    }
}
