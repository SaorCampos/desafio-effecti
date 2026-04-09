<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\ContractApplicationHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContractRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct(
        private ContractApplicationHandler $applicationHandler
    ) {}

    public function index(Request $request): JsonResponse
    {
        $contracts = $this->applicationHandler->handleList($request->all());
        return response()->json($contracts);
    }
    public function store(CreateContractRequest $request): JsonResponse
    {
        $result = $this->applicationHandler->handleStore($request->validated());
        return response()->json([
            'message' => 'Contrato criado com sucesso',
            'data' => $result
        ], 201);
    }
    public function update(Request $request, int $id)
    {
        $result = $this->applicationHandler->handleUpdate($id, $request->all());
        return response()->json($result);
    }
    public function destroy(int $id)
    {
        $this->applicationHandler->handleDelete($id);
        return response()->json(null, 204);
    }
}
