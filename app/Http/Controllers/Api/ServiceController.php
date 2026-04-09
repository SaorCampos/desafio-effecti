<?php

namespace App\Http\Controllers\Api;

use App\Application\Handlers\ServiceApplicationHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateServiceRequest;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceApplicationHandler $handler
    ) {}

    public function index()
    {
        $services = $this->handler->handleList();
        return response()->json($services);
    }

    public function store(CreateServiceRequest $request)
    {
        $service = $this->handler->handleStore($request->validated());
        return response()->json($service, 201);
    }

    public function update(CreateServiceRequest $request, int $id)
    {
        $service = $this->handler->handleUpdate($id, $request->validated());
        return response()->json($service);
    }

    public function destroy(int $id)
    {
        $this->handler->handleDelete($id);
        return response()->json(null, 204);
    }
}
