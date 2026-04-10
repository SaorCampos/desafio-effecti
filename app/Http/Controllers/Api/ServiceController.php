<?php

namespace App\Http\Controllers\Api;

use App\Application\Handlers\ServiceApplicationHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\ServiceListingRequest;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceApplicationHandler $handler
    ) {}

    public function index(ServiceListingRequest $request)
    {
        $services = $this->handler->handleList($request->all());
        if ($request->wantsJson()) {
            return response()->json($services);
        }
        return Inertia::render('services/ServiceList', [
            'services' => $services,
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(CreateServiceRequest $request)
    {
        $result = $this->handler->handleStore($request->validated());
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Serviço criado com sucesso',
                'data' => $result
            ], 201);
        }
        return redirect()->route('services.index')
            ->with('success', 'Serviço criado com sucesso!');
    }

    public function update(CreateServiceRequest $request, int $id)
    {
        $result = $this->handler->handleUpdate($id, $request->validated());
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Serviço atualizado com sucesso',
                'data' => $result
            ]);
        }
        return redirect()->route('services.index')
            ->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(int $id)
    {
        $this->handler->handleDelete($id);
        return response()->json(null, 204);
    }
}
