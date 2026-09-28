<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'staff'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/solicitudes', [ServiceRequestController::class, 'index'])->name('requests.index');
    Route::get('/solicitudes/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('requests.show');
    Route::put('/solicitudes/{serviceRequest}/asignacion', [ServiceRequestController::class, 'updateAssignment'])->name('requests.assignment');
    Route::put('/solicitudes/{serviceRequest}/estado', [ServiceRequestController::class, 'updateStatus'])->name('requests.status');
    Route::post('/solicitudes/{serviceRequest}/comentarios', [ServiceRequestController::class, 'storeComment'])->name('requests.comments.store');

    Route::get('/recursos', [ResourceController::class, 'index'])->name('resources.index');
    Route::post('/recursos', [ResourceController::class, 'store'])->name('resources.store');
    Route::put('/recursos/{resource}', [ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/recursos/{resource}', [ResourceController::class, 'deactivate'])->name('resources.deactivate');
    Route::get('/reportes', [ReportController::class, 'index'])->name('reports.index');
});
