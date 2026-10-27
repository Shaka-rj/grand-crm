@extends('layouts.employee')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/employee/daily-reports.css') }}">
@endpush
@section('content')
<div class="daily-report-page">
    <h1>Hisobotni tahrirlash</h1>
    <p>Sana: {{ $dailyReport->report_date->format('d.m.Y') }}</p>

    <form method="POST"
        action="{{ route('employee.daily-reports.update', $dailyReport) }}">
        @csrf
        @method('PUT')

        @foreach($fields as $field)
            <div class="form-group">
                <label>
                    {{ $field->label }}
                    @if($field->is_required) * @endif
                </label>

                @php
                    $name = "values[{$field->id}]";
                    $value = old("values.{$field->id}", $values[$field->id] ?? '');
                @endphp

                @if($field->type === 'textarea')
                    <textarea name="{{ $name }}" class="form-control"
                        @required($field->is_required)>{{ $value }}</textarea>

                @elseif($field->type === 'select')
                    <select name="{{ $name }}" class="form-control"
                        @required($field->is_required)>
                        <option value="">Tanlang</option>
                        @foreach($field->options ?? [] as $option)
                            <option value="{{ $option }}" @selected($value == $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>

                @elseif($field->type === 'boolean')
                    <select name="{{ $name }}" class="form-control"
                        @required($field->is_required)>
                        <option value="">Tanlang</option>
                        <option value="1" @selected((string) $value === '1')>Ha</option>
                        <option value="0" @selected((string) $value === '0')>Yo‘q</option>
                    </select>

                @else
                    <input
                        type="{{ in_array($field->type, ['number', 'date', 'time']) ? $field->type : 'text' }}"
                        name="{{ $name }}"
                        value="{{ $value }}"
                        class="form-control"
                        @required($field->is_required)
                    >
                @endif

                @error("values.{$field->id}")
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <div class="form-group">
            <label for="comment">Izoh</label>
            <textarea name="comment" id="comment" rows="4"
                class="form-control">{{ old('comment', $dailyReport->comment) }}</textarea>
        </div>

        <button type="submit">O‘zgarishlarni saqlash</button>
    </form>
</div>
@endsection