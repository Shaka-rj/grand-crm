<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Department;
use App\Models\LeadStatus;
use App\Models\LeadHistory;
use Illuminate\Support\Facades\DB;

class LeadsController extends Controller
{
    public function index(Request $request)
    {
        $departmentId = session('department_id');
        $userId = auth()->id();

        // Default: oxirgi 30 kun
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo   = $request->date_to ?? now()->format('Y-m-d');

        $leadFilter = function ($query) use (
            $departmentId,
            $userId,
            $dateFrom,
            $dateTo
        ) {
            $query->where('department_id', $departmentId)
                  ->where('user_id', $userId);

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
            'comment' => 'nullable|string|max:2000',
        ]);

        // Faqat o‘zi yaratgan va o‘z bo‘limiga tegishli lead
        if (
            $lead->user_id != auth()->id() ||
            $lead->department_id != session('department_id')
        ) {
            abort(403, 'Bu leadni o‘zgartirishga ruxsatingiz yo‘q.');
        }

        // Status o‘zgarmagan bo‘lsa, tarixga yozmaymiz
        if ((int) $lead->status_id === (int) $data['status_id']) {
            return back()->with('info', 'Status o‘zgarmadi.');
        }

        DB::transaction(function () use ($lead, $data) {

            $lead->update([
                'status_id' => $data['status_id'],
                'status_updated_at' => now(),
            ]);

            // Status o‘zgarishi tarixini saqlash
            LeadHistory::create([
                'lead_id' => $lead->id,
                'status_id' => $data['status_id'],
                'user_id' => auth()->id(),
                'comment' => $data['comment'] ?? null,
            ]);
        });

        return redirect()
            ->route('employee.leads.index')
            ->with('success', 'Lead statusi muvaffaqiyatli o‘zgartirildi.');
    }

    public function history(Lead $lead)
    {
        // Faqat o‘zi yaratgan va o‘z bo‘limidagi lead
        if (
            $lead->user_id != auth()->id() ||
            $lead->department_id != session('department_id')
        ) {
            abort(403);
        }

        $histories = LeadHistory::with([
            'status:id,name,color',
            'user:id,name',
        ])
            ->where('lead_id', $lead->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($history) {
                return [
                    'status' => $history->status?->name ?? 'Noma’lum',
                    'color' => $history->status?->color ?? '#64748b',
                    'comment' => $history->comment,
                    'user' => $history->user?->name ?? 'Noma’lum xodim',
                    'date' => $history->created_at->format('d.m.Y, H:i'),
                ];
            });

        return response()->json([
            'lead' => $lead->client_name,
            'histories' => $histories,
        ]);
    }
}
