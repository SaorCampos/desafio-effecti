<?php

use App\Http\Controllers\Api\ContractController;
use Illuminate\Support\Facades\Route;

Route::controller(ContractController::class)->group(function () {
    Route::get('/contracts', 'index');
    Route::post('/contracts', 'store');
});
