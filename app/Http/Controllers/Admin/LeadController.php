<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Department;
use App\Models\LeadStatus;



class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with([
            'department',
            'user',
            'status',
        ]);

        // Bo‘limlar
        if ($request->filled('department_ids')) {

            // Qo‘lda filtr tanlangan
            $query->whereIn(
                'department_id',
                $request->department_ids
            );

        } else {

            // Default: sessiondagi tanlangan bo‘lim
            $departmentId = session('admin_department_id');

            if ($departmentId) {
                $query->where(
                    'department_id',
                    $departmentId
                );
            }
        }

        // Status
        if ($request->filled('status_ids')) {
            $query->whereIn(
                'status_id',
                $request->status_ids
            );
        }

        // Sana
        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        $leads = $query
            ->latest('created_at')
            ->paginate(50);

        $statuses = LeadStatus::orderBy('id')->get();

        return view('admin.leads.index', compact(
            'leads',
            'statuses'
        ));
    }
}
