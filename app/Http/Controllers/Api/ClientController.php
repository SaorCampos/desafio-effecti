<?php

namespace App\Http\Controllers\Api;

use App\Application\Handlers\ClientApplicationHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientListingRequest;
use App\Http\Requests\CreateClientRequest;

class ClientController extends Controller
{
    public function __construct(
        private ClientApplicationHandler $handler
    ) {}

    public function index(ClientListingRequest $request)
    {
        $clients = $this->handler->handleList($request->all());
        return response()->json($clients);
    }
    public function store(CreateClientRequest $request)
    {

        $client = $this->handler->handleStore($request->validated());
        return response()->json($client, 201);
    }
    public function update(CreateClientRequest $request, int $id)
    {
        $client = $this->handler->handleUpdate($id, $request->validated());
        return response()->json($client);
    }
    public function destroy(int $id)
    {
        $this->handler->handleDelete($id);
        return response()->json(null, 204);
    }
}
