<?php

namespace App\Http\Controllers\Api;

use App\Application\Handlers\ClientApplicationHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientListingRequest;
use App\Http\Requests\CreateClientRequest;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function __construct(
        private ClientApplicationHandler $handler
    ) {}

    public function index(ClientListingRequest $request)
    {
        $clients = $this->handler->handleList($request->all());
        if ($request->wantsJson()) {
            return response()->json($clients);
        }
        return Inertia::render('clients/ClientList', [
            'clients' => $clients,
            'filters' => $request->only(['name', 'status'])
        ]);
    }
    public function store(CreateClientRequest $request)
    {

        $client = $this->handler->handleStore($request->validated());
        if ($request->wantsJson()) {
            return response()->json($client, 201);
        }
        return redirect()->route('clients.index');
    }
    public function update(CreateClientRequest $request, int $id)
    {
        $client = $this->handler->handleUpdate($id, $request->validated());
        if ($request->wantsJson()) {
            return response()->json($client);
        }
        return redirect()->route('clients.index');
    }
    public function destroy(int $id)
    {
        $this->handler->handleDelete($id);
        return response()->json(null, 204);
    }
}
