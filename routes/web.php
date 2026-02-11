<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.action');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/report', [DashboardController::class, 'report'])->name('report');
        Route::get('/manage-users', [UserController::class, 'index'])->name('log_user');
        Route::post('/manage-users', [UserController::class, 'store'])->name('storeUser');
        Route::put('/manage-users/{id}', [DashboardController::class, 'updateUser'])->name('updateUser');
    });

    Route::prefix('it')->name('it.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'it'])->name('dashboard');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
    });

    Route::get('/tickets/{id}/detail', [TicketController::class, 'showDetail'])->name('tickets.showDetail');
    Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');
    Route::patch('/tickets/{id}/update', [TicketController::class, 'update'])->name('tickets.update');
    Route::resource('tickets', TicketController::class)->except(['store', 'update']);

});