<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function password()
    {
        return view('admin.settings.password');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'current_password' => ['required'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()
                ->withErrors([
                    'current_password' => 'Eski parol noto‘g‘ri.',
                ])
                ->withInput();
        }

        $user->update([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Login va parol muvaffaqiyatli o‘zgartirildi.');
    }
}