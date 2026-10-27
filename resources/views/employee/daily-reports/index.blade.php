@extends('layouts.employee')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/employee/daily-reports.css') }}">
@endpush
@section('content')
<div class="daily-report-page">
    <h1>Kunlik hisobotlar</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
    @endphp

    <div class="report-actions">
        @foreach([$today, $yesterday] as $date)
            @php
                $exists = $reports->getCollection()->contains(
                    fn ($report) => $report->report_date->toDateString() === $date
                );
                $deadline = \Carbon\Carbon::parse($date)->addDay()->setTime(15, 0);
            @endphp

            @if(!$exists && now()->lte($deadline))
                <a href="{{ route('employee.daily-reports.create', ['date' => $date]) }}">
                    {{ $date }} uchun hisobot yaratish
                </a>
            @endif
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th>Sana</th>
                <th>Kelgan vaqti</th>
                <th>Ketgan vaqti</th>
                <th>Holati</th>
                <th>Amal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
                <tr>
                    <td>{{ $report->report_date->format('d.m.Y') }}</td>
                    <td>{{ $report->arrival_time ? substr($report->arrival_time, 0, 5) : '—' }}</td>
                    <td>{{ $report->departure_time ? substr($report->departure_time, 0, 5) : '—' }}</td>
                    <td>
                        @if(now()->lte($report->report_date->copy()->setTime(22, 0)))
                            Tahrirlash mumkin
                        @else
                            Yopilgan
                        @endif
                    </td>
                    <td>
                        @if(now()->lte($report->report_date->copy()->setTime(22, 0)))
                            <a href="{{ route('employee.daily-reports.edit', $report) }}">
                                Tahrirlash
                            </a>
                        @else
                            Tahrirlash muddati tugagan
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Hozircha hisobotlar yo‘q.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $reports->links() }}
</div>
@endsection