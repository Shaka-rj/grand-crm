
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\LeadsController;
use App\Http\Controllers\Employee\AttendanceController;

Route::middleware(['auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Bo‘lim tanlash
        |--------------------------------------------------------------------------
        | Xodim tizimga kirganda bo‘lim tanlashi uchun.
        | Bu route'lar employee.department middleware'dan tashqarida.
        */

        Route::get('/department/select', [AuthController::class, 'selectDepartment'])
            ->name('department.select');

        Route::post('/department/store', [AuthController::class, 'storeDepartment'])
            ->name('department.store');

        /*
        |--------------------------------------------------------------------------
        | Davomat
        |--------------------------------------------------------------------------
        */

        Route::get('/attendance', [AttendanceController::class, 'show'])
            ->name('attendance.show');

        Route::get('/attendance/list', [AttendanceController::class, 'list'])
            ->name('attendance.list');

        Route::post('/attendance', [AttendanceController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('attendance.store');

        /*
        |--------------------------------------------------------------------------
        | Bo‘lim tanlangandan keyingi sahifalar
        |--------------------------------------------------------------------------
        */

        Route::middleware('employee.department')->group(function () {

            // Dashboard
            Route::get('/dashboard', function () {
                return redirect()->route('employee.leads.index');
            })->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | Leadlar
            |--------------------------------------------------------------------------
            */

            // Leadlar ro‘yxati
            Route::get('/leads', [LeadsController::class, 'index'])
                ->name('leads.index');

            // Yangi lead qo‘shish
            Route::post('/leads', [LeadsController::class, 'store'])
                ->name('leads.store');

            // Lead statusini va izohini yangilash
            Route::patch('/leads/{lead}', [LeadsController::class, 'update'])
                ->name('leads.update');

            // Lead tarixini ko‘rish
            Route::get('/leads/{lead}/history', [LeadsController::class, 'history'])
                ->name('leads.history');

        });

    });
