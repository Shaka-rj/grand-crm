<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyReportField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DailyReportFieldController extends Controller
{
    public function index()
    {
        $fields = DailyReportField::orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.daily-reports.fields', compact('fields'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['key'] = $this->uniqueKey($data['label']);
        $data['options'] = $data['options'] ?? null;

        DailyReportField::create($data);

        return back()->with('success', 'Maydon qo‘shildi.');
    }

    public function update(Request $request, DailyReportField $field)
    {
        $data = $this->validatedData($request, $field->id);

        // Key eski hisobotlardagi javoblar bilan bog‘lanishni saqlash uchun o‘zgarmaydi.
        unset($data['key']);

        $field->update($data);

        return back()->with('success', 'Maydon yangilandi.');
    }

    public function destroy(DailyReportField $field)
    {
        $field->delete();

        return back()->with(
            'success',
            'Maydon yashirildi. Eski hisobotlardagi javoblar saqlanadi.'
        );
    }

    private function validatedData(
        Request $request,
        ?int $fieldId = null
    ): array {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'type' => [
                'required',
                Rule::in([
                    'number', 'text', 'textarea',
                    'date', 'time', 'select', 'boolean',
                ]),
            ],
            'options_text' => ['nullable', 'string', 'max:5000'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $options = collect(preg_split('/\r\n|\r|\n/', $data['options_text'] ?? ''))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($data['type'] === 'select' && count($options) === 0) {
            abort(
                422,
                'Tanlov maydoni uchun har bir variantni alohida qatorda kiriting.'
            );
        }

        return [
            'label' => $data['label'],
            'type' => $data['type'],
            'options' => $data['type'] === 'select' ? $options : null,
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }

    private function uniqueKey(string $label): string
    {
        $base = Str::slug($label, '_');

        if ($base === '') {
            $base = 'field';
        }

        $key = $base;
        $number = 2;

        while (DailyReportField::withTrashed()->where('key', $key)->exists()) {
            $key = $base . '_' . $number++;
        }

        return $key;
    }
}