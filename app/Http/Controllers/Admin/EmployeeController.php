<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')
            ->with('departments')
            ->latest()
            ->get();

        $activeEmployees = User::where('role', 'employee')
            ->where('status', true)
            ->count();

        $departments = Department::all();

        return view('admin.employees', compact('employees', 'departments', 'activeEmployees'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'departments' => ['required', 'array'],
            'departments.*' => ['exists:departments,id'],
        ]);

        $employee = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => 'employee',
            'status' => true,
        ]);

        $employee->departments()->sync($data['departments']);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Hodim muvaffaqiyatli qo‘shildi.');
    }

    public function update(Request $request, User $employee)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $employee->id,
            ],

            'password' => ['nullable', 'string', 'min:6'],

            'departments' => ['required', 'array'],

            'departments.*' => [
                'exists:departments,id',
            ],
        ]);


        $employee->name = $data['name'];
        $employee->username = $data['username'];


        if (!empty($data['password'])) {
            $employee->password = Hash::make($data['password']);
        }


        $employee->save();


        $employee->departments()->sync(
            $data['departments']
        );


        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Hodim maʼlumotlari yangilandi.');
    }

    public function destroy(User $employee)
    {
        $employee->update([
            'status' => false,
        ]);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Hodim faoliyati to‘xtatildi.');
    }
}
