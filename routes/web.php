<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;

Route::middleware('guest')->group(function() {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register-form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login-form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:requester'])->group(function() {
    //requester can see his/her tickets
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    //requester can create ticket
    Route::get('/create-ticket', [TicketController::class, 'create'])->name('create-ticket');
    Route::post('/store-ticket', [TicketController::class, 'store'])->name('store-ticket');
    Route::put('/update-request',[TicketController::class, 'update'])->name('update-request');
});

Route::middleware(['auth', 'role:mis'])->group(function() {
    Route::get('/admin', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::put('/update-ticket-admin', [TicketController::class, 'update'])->name('ticket-update-admin');
});
