<?php

namespace App\Providers;

use App\Models\Department;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Admin uchun
        View::composer('admin.*', function ($view) {
            $departments = Department::orderBy('id')->get();

            $view->with('departments', $departments);
        });

        // Employee uchun
        View::composer([
            'employee.*',
            'components.employee.sidebar',
        ], function ($view) {
            $user = auth()->user();

            $department = null;

            if ($user && session()->has('department_id')) {
                $department = $user->departments()
                    ->where('departments.id', session('department_id'))
                    ->first();
            }

            $view->with([
                'user' => $user,
                'department' => $department,
            ]);
        });
    }
}