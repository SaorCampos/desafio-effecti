<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ContractController;
use Illuminate\Support\Facades\Route;

Route::controller(ContractController::class)->group(function () {
    Route::get('/contracts', 'index');
    Route::post('/contracts', 'store');
});

Route::controller(ClientController::class)->group(function () {
    Route::get('/clients', 'index');
    Route::post('/clients', 'store');
    Route::put('/clients/{id}', 'update');
    Route::delete('/clients/{id}', 'destroy');
});
