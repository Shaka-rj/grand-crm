<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyReport::with('user')
            ->orderByDesc('report_date');

        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $reports = $query->paginate(25)->withQueryString();

        $employees = \App\Models\User::where('role', 'employee')
            ->orderBy('name')
            ->get();

        return view(
            'admin.daily-reports.index',
            compact('reports', 'employees')
        );
    }

    public function show(DailyReport $dailyReport)
    {
        $dailyReport->load(['user', 'values.field']);

        return view('admin.daily-reports.show', compact('dailyReport'));
    }
}