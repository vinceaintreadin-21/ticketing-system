<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SSOController;
use App\Http\Controllers\Api\SampleController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TicketNoteController;
use App\Http\Controllers\Api\TicketHistoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::get('/sample-hello', [SampleController::class, 'hello']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('guest')->group(function() {
    Route::get('/auth/redirect/{provider}', [SSOController::class, 'redirect']);
    Route::get('/auth/callback/{provider}', [SSOController::class, 'callback']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/dashboard', [SSOController::class, 'dashboard']);
    Route::get('/index',[TicketController::class, 'index']);
    Route::post('/logout', [SSOController::class, 'handleLogout']);

    Route::post('/tickets', [TicketController::class, 'store']); // create ticket
    Route::get('/tickets', [TicketController::class, 'index']);  // list tickets
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']); // view ticket

    // Ticket note routes (file uploads, comments, etc.)
    Route::post('/ticket-notes/{ticketId}', [TicketNoteController::class, 'store']);
    Route::get('/ticket-notes/{ticket}', [TicketNoteController::class, 'index']);


});

Route::middleware(['auth:sanctum', 'role:mis'])->group(function() {
    Route::get('/ticket-history/{ticketId}', [TicketHistoryController::class, 'index']);
});
