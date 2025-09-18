<?php

use App\Http\Controllers\Api\SampleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::get('/sample-hello', [SampleController::class, 'hello']);
