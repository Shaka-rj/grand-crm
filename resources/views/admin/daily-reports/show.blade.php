
@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/daily-reports.css') }}">
@endpush

@section('content')
<div class="admin-daily-reports">
    <div class="telegram-report">

        <h2>📋 KUNLIK HISOBOT</h2>

        <p>👤 <strong>Xodim:</strong> {{ $dailyReport->user->name }}</p>

        <div class="telegram-divider"></div>

        <h3>⏰ Ish vaqti</h3>

        <p>- Kelgan vaqti: <strong>{{ $dailyReport->arrival_time ? \Illuminate\Support\Carbon::parse($dailyReport->arrival_time)->format('H:i') : '—' }}</strong></p>

        <p>- Ketgan vaqti: <strong>{{ $dailyReport->departure_time ? \Illuminate\Support\Carbon::parse($dailyReport->departure_time)->format('H:i') : '—' }}</strong></p>

        <div class="telegram-divider"></div>

        <h3>📞 Leadlar hisoboti</h3>

        @foreach($dailyReport->values as $item)
            <p>
                - {{ $item->field?->label ?? 'Maydon o‘chirilgan' }}:
                <strong>{{ $item->value ?? '—' }}</strong>
            </p>
        @endforeach

        <div class="telegram-divider"></div>

        <h3>📝 Izoh</h3>

        <p class="telegram-comment">{{ $dailyReport->comment ?: 'Izoh kiritilmagan.' }}</p>

        <div class="telegram-divider"></div>

        <p class="telegram-date">
            📅 Hisobot sanasi: {{ $dailyReport->report_date->format('d.m.Y') }}
        </p>

        <a href="{{ route('admin.daily-report.index') }}" class="telegram-back">
            ← Hisobotlarga qaytish
        </a>

    </div>
</div>
@endsection
