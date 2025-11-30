<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TerminalController as AdminTerminalController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Terminal\DashboardController as TerminalDashboardController;
use App\Http\Controllers\Terminal\DepartureController as TerminalDepartureController;
use App\Http\Controllers\Terminal\FinancialController as TerminalFinancialController;
use App\Http\Controllers\Terminal\ReportController as TerminalReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Admin BPTD routes
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Terminals management
        Route::resource('terminals', AdminTerminalController::class);
        
        // Users management
        Route::resource('users', AdminUserController::class);
        
        // Reports
        Route::get('/reports', function () {
            return \Inertia\Inertia::render('Admin/Reports/Index');
        })->name('reports.index');
    });

    // Terminal User routes
    Route::middleware(['role:terminal_admin'])->prefix('terminal')->name('terminal.')->group(function () {
        Route::get('/dashboard', [TerminalDashboardController::class, 'index'])->name('dashboard');
        
        // Departures management
        Route::resource('departures', TerminalDepartureController::class);
        Route::get('/vehicles/operator/{operator}', [TerminalDepartureController::class, 'getVehiclesByOperator'])
            ->name('vehicles.by-operator');
        
        // Financial records
        Route::resource('financial', TerminalFinancialController::class);
        
        // Reports
        Route::get('/reports', [TerminalReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/export', [TerminalReportController::class, 'export'])->name('reports.export');
        
        // Statistics
        Route::get('/statistics', function () {
            return \Inertia\Inertia::render('Terminal/Statistics/Index');
        })->name('statistics.index');
    });
});