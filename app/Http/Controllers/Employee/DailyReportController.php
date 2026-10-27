<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\DailyReportField;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DailyReportController extends Controller
{
    public function index()
    {
        $reports = DailyReport::where('user_id', auth()->id())
            ->orderByDesc('report_date')
            ->paginate(20);

        return view('employee.daily-reports.index', compact('reports'));
    }

    public function create(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $reportDate = \Carbon\Carbon::parse($date)->startOfDay();

        abort_if($reportDate->gt(now()->startOfDay()), 403);

        abort_if(
            now()->gt($reportDate->copy()->addDay()->setTime(22, 0)),
            403,
            'Ushbu hisobotni yaratish muddati tugagan.'
        );

        $exists = DailyReport::where('user_id', auth()->id())
            ->whereDate('report_date', $date)
            ->exists();

        if ($exists) {
            return redirect()->route('employee.daily-reports.index')
                ->with('error', 'Bu sana uchun hisobot allaqachon yaratilgan.');
        }

        $fields = DailyReportField::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('employee.daily-reports.create', compact('date', 'fields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_date' => ['required', 'date_format:Y-m-d'],
            'comment' => ['nullable', 'string', 'max:10000'],
            'values' => ['nullable', 'array'],
        ]);

        $date = \Carbon\Carbon::createFromFormat(
            'Y-m-d',
            $request->report_date
        )->startOfDay();

        if ($date->gt(now()->startOfDay())) {
            throw ValidationException::withMessages([
                'report_date' => 'Kelajak sanasi uchun hisobot yaratib bo‘lmaydi.',
            ]);
        }

        if (now()->gt($date->copy()->addDay()->setTime(22, 0))) {
            throw ValidationException::withMessages([
                'report_date' => 'Hisobot yaratish muddati tugagan.',
            ]);
        }

        $fields = DailyReportField::where('is_active', true)->get();
        $inputValues = $request->input('values', []);

        foreach ($fields as $field) {
            $value = $inputValues[$field->id] ?? null;

            if ($field->is_required && ($value === null || $value === '')) {
                throw ValidationException::withMessages([
                    "values.{$field->id}" => "{$field->label} maydoni to‘ldirilishi shart.",
                ]);
            }

            if ($value !== null && $value !== '') {
                $this->validateFieldValue($field, $value);
            }
        }

        // Davomat jadvalidan shu kundagi birinchi va oxirgi qayd vaqti olinadi.
        $attendance = AttendanceRecord::where('user_id', auth()->id())
            ->whereBetween('created_at', [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
            ]);

        $arrival = (clone $attendance)->min('created_at');
        $departure = (clone $attendance)->max('created_at');

        DB::transaction(function () use (
            $request,
            $date,
            $fields,
            $inputValues,
            $arrival,
            $departure
        ) {
            $report = DailyReport::create([
                'user_id' => auth()->id(),
                'report_date' => $date->toDateString(),
                'arrival_time' => $arrival
                    ? \Carbon\Carbon::parse($arrival)->format('H:i:s')
                    : null,
                'departure_time' => $departure && $departure !== $arrival
                    ? \Carbon\Carbon::parse($departure)->format('H:i:s')
                    : null,
                'comment' => $request->comment,
                'submitted_at' => now(),
            ]);

            foreach ($fields as $field) {
                $value = $inputValues[$field->id] ?? null;

                $report->values()->create([
                    'daily_report_field_id' => $field->id,
                    'value' => is_array($value)
                        ? json_encode($value, JSON_UNESCAPED_UNICODE)
                        : $value,
                ]);
            }
        });

        return redirect()->route('employee.daily-reports.index')
            ->with('success', 'Kunlik hisobot saqlandi.');
    }

    public function edit(DailyReport $dailyReport)
    {
        abort_unless($dailyReport->user_id === auth()->id(), 403);

        $deadline = $dailyReport->report_date->copy()->setTime(22, 0);

        abort_if(
            now()->gt($deadline),
            403,
            'Hisobotni tahrirlash muddati tugagan.'
        );

        $fields = DailyReportField::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $dailyReport->load('values');

        $values = $dailyReport->values->pluck('value', 'daily_report_field_id');

        return view(
            'employee.daily-reports.edit',
            compact('dailyReport', 'fields', 'values')
        );
    }


    public function update(Request $request, DailyReport $dailyReport)
    {
        abort_unless($dailyReport->user_id === auth()->id(), 403);

        $deadline = $dailyReport->report_date->copy()->setTime(22, 0);

        abort_if(
            now()->gt($deadline),
            403,
            'Hisobotni tahrirlash muddati tugagan.'
        );

        $request->validate([
            'comment' => ['nullable', 'string', 'max:10000'],
            'values' => ['nullable', 'array'],
        ]);

        $fields = DailyReportField::where('is_active', true)->get();
        $inputValues = $request->input('values', []);

        foreach ($fields as $field) {
            $value = $inputValues[$field->id] ?? null;

            if ($field->is_required && ($value === null || $value === '')) {
                throw ValidationException::withMessages([
                    "values.{$field->id}" => "{$field->label} maydoni to‘ldirilishi shart.",
                ]);
            }

            if ($value !== null && $value !== '') {
                $this->validateFieldValue($field, $value);
            }
        }

        // Hisobot sanasi bo‘yicha davomat yozuvlarini qayta olish
        $start = $dailyReport->report_date->copy()->startOfDay();
        $end = $dailyReport->report_date->copy()->endOfDay();

        $attendance = AttendanceRecord::where('user_id', $dailyReport->user_id)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        $arrivalTime = $attendance->first()?->created_at?->format('H:i:s');
        $departureTime = $attendance->last()?->created_at?->format('H:i:s');

        DB::transaction(function () use (
            $dailyReport,
            $request,
            $fields,
            $inputValues,
            $arrivalTime,
            $departureTime
        ) {
            $dailyReport->update([
                'arrival_time' => $arrivalTime,
                'departure_time' => $departureTime,
                'comment' => $request->comment,
            ]);

            foreach ($fields as $field) {
                $value = $inputValues[$field->id] ?? null;

                $dailyReport->values()->updateOrCreate(
                    ['daily_report_field_id' => $field->id],
                    [
                        'value' => is_array($value)
                            ? json_encode($value, JSON_UNESCAPED_UNICODE)
                            : $value,
                    ]
                );
            }
        });

        return redirect()->route('employee.daily-reports.index')
            ->with('success', 'Hisobot va ish vaqtlari yangilandi.');
    }


    private function validateFieldValue(
        DailyReportField $field,
        mixed $value
    ): void {
        $rules = match ($field->type) {
            'number' => ['numeric'],
            'date' => ['date'],
            'time' => ['date_format:H:i'],
            'select' => ['string', 'in:' . implode(',', $field->options ?? [])],
            'textarea', 'text' => ['string', 'max:10000'],
            'boolean' => ['boolean'],
            default => ['string', 'max:10000'],
        };

        validator(
            ['value' => $value],
            ['value' => $rules]
        )->validate();
    }
}