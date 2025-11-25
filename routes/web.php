<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'We tested this successfully!';
});

Route::get('/health', function () {
    return response()->json(['status' => 'OK'], 200);
});

Route::post('/reset', function () {
    return 'CSRF is disabled for this route.';
});
