<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalanceController;

Route::get('/', function () {
    return 'We tested this successfully!';
});

Route::get('/health', function () {
    return response()->json(['status' => 'OK'], 200);
});

Route::post('/reset', [BalanceController::class, 'reset']);

Route::get('/balance', [BalanceController::class, 'getBalance']);
