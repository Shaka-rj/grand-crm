<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if (auth()->user()->role === 'employee') {
        return redirect()->route('employee.leads.index');
    }

    abort(403);
});




require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/employee.php';