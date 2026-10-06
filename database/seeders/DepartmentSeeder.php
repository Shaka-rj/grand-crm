<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Grand Travel Tours',
                'description' => 'Dunyo bo\'ylab sayohat',
                'icon' => 'grandtravel.png',
            ],
            [
                'name' => 'Grand Agency',
                'description' => '.',
                'icon' => 'grandagenc.png',
            ],
            [
                'name' => 'Centrum Umra',
                'description' => 'Umra ziyorati',
                'icon' => 'centrum.png',
            ],
            [
                'name' => 'Hikmat Umra',
                'description' => 'Umra ziyorati',
                'icon' => 'hikmat.png',
            ],

        ];
       

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name']],
                $department
            );
        }
    }
}