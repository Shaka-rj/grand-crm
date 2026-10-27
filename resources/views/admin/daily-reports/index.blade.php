@extends('layouts.admin')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/daily-reports.css') }}">
@endpush

@section('content')
<div class="admin-daily-reports">
    <h1>Xodimlarning kunlik hisobotlari</h1>
    <a href="{{ route('admin.daily-report.fields.index') }}">
        Hisobot sozlamalari
    </a>
    <form method="GET">
        <input type="date" name="date_from" value="{{ request('date_from') }}">
        <input type="date" name="date_to" value="{{ request('date_to') }}">

        <select name="user_id">
            <option value="">Barcha xodimlar</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}"
                    @selected(request('user_id') == $employee->id)>
                    {{ $employee->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filtrlash</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Sana</th>
                <th>Xodim</th>
                <th>Kelgan vaqti</th>
                <th>Ketgan vaqti</th>
                <th>Hisobot vaqti</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
                <tr>
                    <td>{{ $report->report_date->format('d.m.Y') }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>{{ $report->arrival_time ? substr($report->arrival_time, 0, 5) : '—' }}</td>
                    <td>{{ $report->departure_time ? substr($report->departure_time, 0, 5) : '—' }}</td>
                    <td>{{ $report->submitted_at?->format('d.m.Y H:i') ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.daily-report.show', $report) }}">
                            Ko‘rish
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Hisobotlar topilmadi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $reports->links() }}
</div>
@endsection