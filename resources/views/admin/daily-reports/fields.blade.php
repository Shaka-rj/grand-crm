@extends('layouts.admin')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/daily-reports.css') }}">
@endpush
@section('content')
<div class="admin-daily-reports">
    <h1>Hisobot maydonlari</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2>Yangi maydon qo‘shish</h2>

    <form method="POST" action="{{ route('admin.daily-report.fields.store') }}">
        @csrf

        <input name="label" placeholder="Maydon nomi" required>

        <select name="type" required>
            <option value="number">Son</option>
            <option value="text">Matn</option>
        </select>
        <button type="submit">Maydon qo‘shish</button>
    </form>

    <hr>

    <h2>Mavjud maydonlar</h2>

    @foreach($fields as $field)
        <div class="field-item">
            <form method="POST"
                action="{{ route('admin.daily-report.fields.update', $field) }}">
                @csrf
                @method('PUT')

                <input name="label" value="{{ $field->label }}" required>

                <select name="type" required>
                    @foreach([
                        'number' => 'Son',
                        'text' => 'Matn'
                    ] as $type => $label)
                        <option value="{{ $type }}"
                            @selected($field->type === $type)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>     

                <button type="submit">Saqlash</button>
            </form>

            <form method="POST"
                action="{{ route('admin.daily-report.fields.destroy', $field) }}"
                onsubmit="return confirm('Maydonni yashirishni tasdiqlaysizmi?')">
                @csrf
                @method('DELETE')
                <button type="submit">O‘chirish</button>
            </form>
        </div>
        <hr>
    @endforeach
</div>
@endsection