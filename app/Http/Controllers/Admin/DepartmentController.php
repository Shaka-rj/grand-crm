<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function select(Department $department)
    {
        session([
            'admin_department_id' => $department->id
        ]);

        return redirect()->back();
    }
}