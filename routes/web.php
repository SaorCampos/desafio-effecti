<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ContractController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Rota inicial (Dashboard)
Route::get('/', function () {
    return redirect()->route('contracts.index');
});

// Rotas de Clientes
Route::resource('clients', ClientController::class);

// Rotas de Serviços
Route::resource('services', ServiceController::class);

// Rotas de Contratos
Route::resource('contracts', ContractController::class);

require __DIR__.'/settings.php';
