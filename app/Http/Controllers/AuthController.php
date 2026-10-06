<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {

            $user = auth()->user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'employee') {
                return redirect()->route('employee.leads.index');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, true)) {
            return back()
                ->withErrors([
                    'username' => 'Login yoki parol noto‘g‘ri.',
                ])
                ->withInput($request->only('username'));
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user->status) {
            Auth::logout();

            return back()->withErrors([
                'username' => 'Hisobingiz faol emas.',
            ]);
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'employee') {

            $departments = $user->departments;

            if ($departments->count() === 1) {

                session([
                    'department_id' => $departments->first()->id
                ]);

                return redirect()->route('employee.leads.index');
            }

            if ($departments->count() > 1) {
                return redirect()->route('employee.department.select');
            }

            Auth::logout();

            return back()->withErrors([
                'username' => 'Sizga hech qanday bo‘lim biriktirilmagan.',
            ]);
        }

        Auth::logout();

        return back()->withErrors([
            'username' => 'Foydalanuvchi roli aniqlanmadi.',
        ]);
    }

    public function selectDepartment()
    {
        $departments = auth()->user()->departments;

        return view('select-department', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'department_id' => [
                'required',
                'exists:departments,id',
            ],
        ]);

        $departmentId = $request->department_id;

        $hasDepartment = auth()->user()
            ->departments()
            ->where('departments.id', $departmentId)
            ->exists();

        if (!$hasDepartment) {
            abort(403);
        }

        session([
            'department_id' => $departmentId
        ]);

        return redirect()->route('employee.dashboard');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}