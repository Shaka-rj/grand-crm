<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Department;
use App\Models\LeadStatus;
use App\Models\LeadHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


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

        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->get();

        if (request()->has('user_ids')) {
            $userIds = request()->input('user_ids', []);

            $query->where(function ($q) use ($userIds) {
                if (in_array('0', $userIds)) {
                    $q->orWhereNull('user_id');
                }

                $employeeIds = array_filter(
                    $userIds,
                    fn ($id) => $id !== '0'
                );

                if (!empty($employeeIds)) {
                    $q->orWhereIn('user_id', $employeeIds);
                }
            });
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
            'statuses',
            'employees'
        ));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $statuses = LeadStatus::orderBy('id')->get();

        $employees = User::where('role', 'employee')
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.leads.create', compact(
            'departments',
            'statuses',
            'employees'
        ));
    }

    public function history(Lead $lead)
    {
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

    public function edit(Lead $lead)
    {
        $departments = Department::orderBy('name')->get();
        $statuses = LeadStatus::orderBy('id')->get();

        $employees = User::where('role', 'employee')
            ->where('status', true)
            ->whereHas('departments', function ($query) use ($lead) {
                $query->where('departments.id', $lead->department_id);
            })
            ->orderBy('name')
            ->get();

        // Avvalgi xodim boshqa bo‘limga tegishli bo‘lsa ham ko‘rsatiladi.
        if ($lead->user_id && !$employees->contains('id', $lead->user_id)) {
            $previousEmployee = User::find($lead->user_id);

            if ($previousEmployee) {
                $employees->push($previousEmployee);
            }
        }

        return view('admin.leads.edit', compact(
            'lead',
            'departments',
            'statuses',
            'employees'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'travel_date' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'department_id' => ['required', 'exists:departments,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'status_id' => ['required', 'exists:lead_statuses,id'],
        ]);

        // Xodim tanlangan bo‘limga biriktirilganini tekshirish
        if (!empty($data['user_id'])) {
            $validEmployee = User::where('id', $data['user_id'])
                ->where('role', 'employee')
                ->where('status', true)
                ->whereHas('departments', function ($query) use ($data) {
                    $query->where(
                        'departments.id',
                        $data['department_id']
                    );
                })
                ->exists();

            if (!$validEmployee) {
                return back()
                    ->withErrors([
                        'user_id' => 'Xodim tanlangan bo‘limga biriktirilmagan yoki faol emas.',
                    ])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($data) {
            // Lead ma’lumotlarini yaratish
            $lead = Lead::create([
                'client_name' => $data['client_name'],
                'phone' => $data['phone'],
                'travel_date' => $data['travel_date'] ?? null,
                'department_id' => $data['department_id'],
                'user_id' => $data['user_id'] ?? null,
                'status_id' => $data['status_id'],
                'status_updated_at' => now(),
            ]);

            // Dastlabki status va izoh tarixga yoziladi
            LeadHistory::create([
                'lead_id' => $lead->id,
                'status_id' => $data['status_id'],
                'user_id' => auth()->id(),
                'comment' => trim($data['comment'] ?? '') ?: null,
            ]);
        });

        return redirect()
            ->route('admin.leads.index')
            ->with('success', 'Yangi lead muvaffaqiyatli qo‘shildi.');
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'travel_date' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'department_id' => ['required', 'exists:departments,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'status_id' => ['required', 'exists:lead_statuses,id'],
        ]);

        // Xodim tanlangan bo‘limga tegishli ekanini tekshirish
        if (!empty($data['user_id'])) {
            $validEmployee = User::where('id', $data['user_id'])
                ->where('role', 'employee')
                ->whereHas('departments', function ($query) use ($data) {
                    $query->where('departments.id', $data['department_id']);
                })
                ->exists();

            if (!$validEmployee) {
                return back()
                    ->withErrors([
                        'user_id' => 'Xodim tanlangan bo‘limga biriktirilmagan.',
                    ])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($lead, $data) {
            $oldStatusId = (int) $lead->status_id;
            $newStatusId = (int) $data['status_id'];

            $newComment = trim($data['comment'] ?? '');

            // Izoh oxirgi tarixiy yozuvdan olinadi
            $lastHistory = $lead->histories()->latest('id')->first();
            $oldComment = trim((string) ($lastHistory?->comment ?? ''));

            $statusChanged = $oldStatusId !== $newStatusId;
            $commentChanged = $oldComment !== $newComment;

            // Faqat leadning o‘ziga tegishli maydonlarni saqlash
            $lead->update([
                'client_name' => $data['client_name'],
                'phone' => $data['phone'],
                'travel_date' => $data['travel_date'] ?? null,
                'department_id' => $data['department_id'],
                'user_id' => $data['user_id'] ?? null,
                'status_id' => $newStatusId,
                'status_updated_at' => $statusChanged
                    ? now()
                    : $lead->status_updated_at,
            ]);

            // Status yoki izoh o‘zgarmasa, tarixga yozilmaydi
            if (!$statusChanged && !$commentChanged) {
                return;
            }

            LeadHistory::create([
                'lead_id' => $lead->id,
                'status_id' => $newStatusId,
                'user_id' => auth()->id(),
                'comment' => $newComment !== '' ? $newComment : null,
            ]);
        });

        return redirect()
            ->route('admin.leads.index');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()
            ->route('admin.leads.index')
            ->with('success', 'Lead o‘chirildi.');
    }
}
