<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\EventController;

Route::post('/reset', [BalanceController::class, 'reset']);

Route::get('/balance', [BalanceController::class, 'getBalance']);

Route::post('/event', [EventController::class, 'handleEvent']);
