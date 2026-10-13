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

        $hasDepartment = $departmentId
            && $user->departments()
                ->where('departments.id', $departmentId)
                ->exists();

        if (!$hasDepartment) {
            session()->forget('department_id');

            $departments = $user->departments;

            if ($departments->count() === 1) {
                session([
                    'department_id' => $departments->first()->id,
                ]);
            } elseif ($departments->count() > 1) {
                return redirect()->route('employee.department.select');
            } else {
                abort(403, 'Sizga hech qanday bo‘lim biriktirilmagan.');
            }
        }

        return $next($request);
    }
}