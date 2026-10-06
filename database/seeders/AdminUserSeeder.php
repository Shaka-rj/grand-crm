<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
            'name' => 'Asosiy Admin',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
            'status' => true,
        ]);

        $departments = Department::pluck('id');

        $admin->departments()->attach($departments);
    }
}
