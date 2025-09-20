<?php

use App\Http\Controllers\Api\SampleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SSOController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::get('/sample-hello', [SampleController::class, 'hello']);

Route::middleware('guest')->group(function() {
    Route::get('/auth/redirect/{provider}', [SSOController::class, 'redirect']);
    Route::get('/auth/callback/{provider}', [SSOController::class, 'callback']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/dashboard', [SSOController::class, 'dashboard']);
    Route::post('/logout', [SSOController::class, 'handleLogout']);
});
