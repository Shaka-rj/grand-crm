<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployeeDepartment
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'employee') {
            return $next($request);
        }

        $departmentId = session('department_id');

        // Tanlangan bo‘lim xodimga tegishlimi?
        if ($departmentId) {
            $valid = $user->departments()
                ->where('departments.id', $departmentId)
                ->exists();

            if ($valid) {
                return $next($request);
            }

            session()->forget('department_id');
        }

        // Sessionda bo‘lim yo‘q — tanlash sahifasiga
        return redirect()->route('employee.department.select');
    }
}