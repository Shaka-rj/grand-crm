
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Employee\AttendanceController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Xodimlar
        |--------------------------------------------------------------------------
        */

        Route::get('/employees', [EmployeeController::class, 'index'])
            ->name('employees.index');

        Route::post('/employees', [EmployeeController::class, 'store'])
            ->name('employees.store');

        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])
            ->name('employees.update');

        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
            ->name('employees.destroy');


        /*
        |--------------------------------------------------------------------------
        | Davomat
        |--------------------------------------------------------------------------
        */
        Route::get('/attendance', [EmployeeController::class, 'attendance'])
            ->name('attendance.index');
        Route::post('/attendance/{attendance}/approve', [
            EmployeeController::class, 'attendanceapprove'
        ])->name('attendance.approve');

        Route::post('/attendance/{attendance}/reject', [
            EmployeeController::class, 'attendancereject'
        ])->name('attendance.reject');

        Route::get('/attendance/{attendance}/photo', [
            AttendanceController::class,
            'photo',
        ])->name('attendance.photo');


        /*
        |--------------------------------------------------------------------------
        | Leadlar
        |--------------------------------------------------------------------------
        */

        // Leadlar ro‘yxati
        Route::get('/leads', [LeadController::class, 'index'])
            ->name('leads.index');

        // Yangi lead qo‘shish
        Route::get('/leads/create', [LeadController::class, 'create'])
            ->name('leads.create');

        Route::post('/leads', [LeadController::class, 'store'])
            ->name('leads.store');

        // Leadni tahrirlash
        Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])
            ->name('leads.edit');

        Route::put('/leads/{lead}', [LeadController::class, 'update'])
            ->name('leads.update');

        // Leadni o‘chirish
        Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])
            ->name('leads.destroy');

        // Lead statuslari va izohlari tarixi
        Route::get('/leads/{lead}/history', [LeadController::class, 'history'])
            ->name('leads.history');


        /*
        |--------------------------------------------------------------------------
        | Bo‘limlar
        |--------------------------------------------------------------------------
        */

        Route::get('/department/select/{department}', [DepartmentController::class, 'select'])
            ->name('department.select');


        /*
        |--------------------------------------------------------------------------
        | Sozlamalar
        |--------------------------------------------------------------------------
        */

        Route::get('/settings', [SettingsController::class, 'index'])
            ->name('settings.index');

        Route::get('/settings/password', [SettingsController::class, 'password'])
            ->name('settings.password');

        Route::put('/settings/password', [SettingsController::class, 'updatePassword'])
            ->name('settings.password.update');

    });
