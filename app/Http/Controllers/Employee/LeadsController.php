<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Department;
use App\Models\LeadStatus;

class LeadsController extends Controller
{
    public function index(Request $request)
    {
        $departmentId = session('department_id');

        $department = Department::findOrFail($departmentId);

        // Default: oxirgi 30 kun
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo   = $request->date_to ?? now()->format('Y-m-d');

        $leadFilter = function ($query) use ($departmentId, $dateFrom, $dateTo) {

            $query->where('department_id', $departmentId);

            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }

            $query->orderByDesc('status_updated_at');
        };

        $statuses = LeadStatus::orderBy('id')
            ->withCount([
                'leads' => $leadFilter
            ])
            ->with([
                'leads' => $leadFilter
            ])
            ->get();

        $totalLeads = $statuses->sum('leads_count');

        return view('employee.leads.index', compact(
            'department',
            'statuses',
            'totalLeads',
            'dateFrom',
            'dateTo'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'travel_date' => 'nullable|string|max:255',
            'status_id' => 'required|exists:lead_statuses,id',
        ]);

        $user = auth()->user();

        $departmentId = session('department_id');

        if (!$departmentId) {
            return redirect()
                ->route('employee.department.select')
                ->with('error', 'Avval bo‘limni tanlang.');
        }

        $lead = Lead::create([
            'client_name' => $data['client_name'],
            'phone' => $data['phone'],
            'travel_date' => $data['travel_date'] ?? null,
            'user_id' => $user->id,
            'department_id' => $departmentId,
            'status_id' => $data['status_id'],
            'status_updated_at' => now(),
        ]);

        return redirect()
            ->route('employee.leads.index')
            ->with('success', 'Lead muvaffaqiyatli qo‘shildi.');
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'status_id' => 'required|exists:lead_statuses,id',
        ]);

        // Faqat tanlangan bo‘limdagi leadni o‘zgartirishga ruxsat
        if ($lead->department_id != session('department_id')) {
            abort(403);
        }

        $lead->update([
            'status_id' => $data['status_id'],
            'status_updated_at' => now(),
        ]);

        return redirect()
            ->route('employee.leads.index')
            ->with('success', 'Lead statusi muvaffaqiyatli o‘zgartirildi.');
    }
}
