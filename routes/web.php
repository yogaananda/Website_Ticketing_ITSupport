<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserAssetController;
use App\Http\Controllers\UserConsumableController;
use App\Http\Controllers\ItApprovalController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\AdminApprovalController;


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
        Route::resource('consumables', ConsumableController::class)->except(['create', 'show', 'edit']);
        Route::resource('assets', AssetController::class)->except(['create', 'show', 'edit']);
        Route::get('/approvals', [AdminApprovalController::class, 'index'])->name('approvals.index');
        Route::put('/approvals/procurement/{id}/approve', [AdminApprovalController::class, 'approveProcurement'])->name('approvals.procurement.approve');
        Route::put('/approvals/procurement/{id}/reject', [AdminApprovalController::class, 'rejectProcurement'])->name('approvals.procurement.reject');

    });

    Route::prefix('it')->name('it.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'it'])->name('dashboard');
        Route::get('/approvals', [ItApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/asset/{id}/approve', [ItApprovalController::class, 'approveAsset'])->name('approvals.asset.approve');
        Route::post('/approvals/asset/{id}/reject', [ItApprovalController::class, 'rejectAsset'])->name('approvals.asset.reject');
        Route::post('/approvals/asset/{id}/return', [ItApprovalController::class, 'returnAsset'])->name('approvals.asset.return');
        Route::post('/approvals/consumable/{id}/approve', [ItApprovalController::class, 'approveConsumable'])->name('approvals.consumable.approve');
        Route::post('/approvals/consumable/{id}/reject', [ItApprovalController::class, 'rejectConsumable'])->name('approvals.consumable.reject');
        Route::get('/procurements', [ProcurementController::class, 'index'])->name('procurements.index');
        Route::post('/procurements', [ProcurementController::class, 'store'])->name('procurements.store');
        Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::delete('/maintenance/{id}', [MaintenanceController::class, 'destroy'])->name('maintenance.destroy');

    });

    Route::prefix('user')->name('user.')->group(function () {   
        Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
        Route::get('/my-assets', [UserAssetController::class, 'index'])->name('assets.index');
        Route::post('/my-assets', [UserAssetController::class, 'store'])->name('assets.store');
        Route::get('/consumables', [UserConsumableController::class, 'index'])->name('consumables.index');
        Route::post('/consumables', [UserConsumableController::class, 'store'])->name('consumables.store');
    });

    Route::get('/tickets/{id}/detail', [TicketController::class, 'showDetail'])->name('tickets.showDetail');
    Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');
    Route::patch('/tickets/{id}/update', [TicketController::class, 'update'])->name('tickets.update');
    Route::resource('tickets', TicketController::class)->except(['store', 'update']);

});