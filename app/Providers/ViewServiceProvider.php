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
        View::composer('employee.*', function ($view) {
            $user = auth()->user();

            $department = null;

            if (session()->has('department_id')) {
                $department = Department::find(
                    session('department_id')
                );
            }

            $view->with([
                'user' => $user,
                'department' => $department,
            ]);
        });
    }
}