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
        $contracts = $this->applicationHandler->listAllContracts($request->all());
        return response()->json($contracts);
    }
    public function store(CreateContractRequest $request): JsonResponse
    {
        $result = $this->applicationHandler->createContract($request->validated());
        return response()->json([
            'message' => 'Contrato criado com sucesso',
            'data' => $result
        ], 201);
    }
}
