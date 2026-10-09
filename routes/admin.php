<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SettingsController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        // Employees
        Route::get('/employees', [EmployeeController::class, 'index'])
            ->name('employees.index');

        Route::post('/employees', [EmployeeController::class, 'store'])
            ->name('employees.store');

        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])
            ->name('employees.update');

        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
            ->name('employees.destroy');


        // Leads
        Route::get('/leads', [LeadController::class, 'index'])
            ->name('leads.index');

        // Departments
        Route::get('/department/select/{department}', [DepartmentController::class, 'select'])
    ->name('department.select');



        //Settings
        Route::get('/settings', [SettingsController::class, 'index'])
            ->name('settings.index');

        Route::get('/settings/password', [SettingsController::class, 'password'])
            ->name('settings.password');

        Route::put('/settings/password', [SettingsController::class, 'updatePassword'])
            ->name('settings.password.update');

        Route::get('/leads/{lead}/history', [LeadController::class,'history',])->name('leads.history');

});