<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

Route::controller(ContractController::class)->group(function () {
    Route::get('/contracts', 'index');
    Route::post('/contracts', 'store');
    Route::put('/contracts/{id}', 'update');
    Route::delete('/contracts/{id}', 'destroy');
});

Route::controller(ClientController::class)->group(function () {
    Route::get('/clients', 'index');
    Route::post('/clients', 'store');
    Route::put('/clients/{id}', 'update');
    Route::delete('/clients/{id}', 'destroy');
});

Route::controller(ServiceController::class)->group(function () {
    Route::get('/services', 'index');
    Route::post('/services', 'store');
    Route::put('/services/{id}', 'update');
    Route::delete('/services/{id}', 'destroy');
});
