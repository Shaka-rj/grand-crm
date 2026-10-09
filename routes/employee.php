<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\LeadsController;

Route::middleware(['auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('employee.leads.index');
        })->name('dashboard');

        Route::get('/department/select', [AuthController::class, 'selectDepartment'])
            ->name('department.select');

        Route::post('/department/store', [AuthController::class, 'storeDepartment'])
            ->name('department.store');

        // leads
        Route::get('/leads', [LeadsController::class, 'index'])->name('leads.index');

        Route::post('/employee/leads', [LeadsController::class, 'store'])->name('leads.store');

        Route::patch('/leads/{lead}', [LeadsController::class, 'update'])->name('leads.update');

        Route::get('/leads/{lead}/history', [LeadsController::class, 'history',])->name('leads.history');
});