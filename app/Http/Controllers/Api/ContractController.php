<?php

namespace App\Http\Controllers\Api;

use App\Application\Handlers\ClientApplicationHandler;
use App\Application\Handlers\ContractApplicationHandler;
use App\Application\Handlers\ServiceApplicationHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractListingRequest;
use App\Http\Requests\CreateContractRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContractController extends Controller
{
    public function __construct(
        private ContractApplicationHandler $applicationHandler,
        private ClientApplicationHandler $clientHandler,
        private ServiceApplicationHandler $serviceHandler

    ) {}

    public function index(ContractListingRequest $request)
    {
        $contracts = $this->applicationHandler->handleList($request->all());
        if ($request->wantsJson()) {
            return response()->json($contracts);
        }
        return Inertia::render('contracts/ContractList', [
            'contracts' => $contracts,
            'filters' => $request->only(['client_id', 'service_id', 'status'])
        ]);
    }
    public function store(CreateContractRequest $request)
    {
        $result = $this->applicationHandler->handleStore($request->validated());
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Contrato criado com sucesso',
                'data' => $result
            ], 201);
        }
        return redirect()->route('contracts.index')
            ->with('success', 'Contrato criado com sucesso!');
    }
    public function update(Request $request, int $id)
    {
        $result = $this->applicationHandler->handleUpdate($id, $request->all());
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Contrato atualizado com sucesso',
                'data' => $result
            ]);
        }
        return redirect()->route('contracts.index')
            ->with('success', 'Contrato atualizado com sucesso!');
    }
    public function destroy(int $id)
    {
        $deleted = $this->applicationHandler->handleDelete($id);
        if (!$deleted) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Contrato não encontrado'], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Contrato não encontrado.']);
        }
        if (request()->wantsJson()) {
            return response()->noContent();
        }
        return redirect()->route('contracts.index');
    }

    public function create(Request $request)
    {
        return Inertia::render('contracts/ContractForm', [
            'clients' => $this->clientHandler->handleList($request->all()),
            'services' => $this->serviceHandler->handleList($request->all())
        ]);
    }
    public function edit(int $id, Request $request)
    {
        $contract = $this->applicationHandler->handleFindById($id);
        return Inertia::render('contracts/ContractForm', [
            'contract' => $contract,
            'clients' => $this->clientHandler->handleList($request->all()),
            'services' => $this->serviceHandler->handleList($request->all())
        ]);
    }
}
