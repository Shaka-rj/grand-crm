
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Davomatlarim</title>

    <link rel="stylesheet"
          href="{{ asset('css/employee/attendance.css') }}">
</head>
<body>

<div class="attendance-page">

    <header class="attendance-header">
        <div>
            <h1>Davomatlarim</h1>
            <p>Davomat ma’lumotlari tarixi</p>
        </div>

        <div class="attendance-actions">
            <a href="{{ route('employee.attendance.show') }}"
               class="btn btn-primary">
                + Qayta davomatdan o‘tish
            </a>

            <form action="{{ route('logout') }}" method="get">
                @csrf
                <button type="submit" class="btn btn-logout">
                    Chiqish
                </button>
            </form>
        </div>
    </header>

    <div class="attendance-summary">
        <div class="summary-card">
            <span>Jami davomatlar</span>
            <strong>{{ $attendances->total() }}</strong>
        </div>

        <div class="summary-card">
            <span>Joriy sahifa</span>
            <strong>{{ $attendances->currentPage() }}</strong>
        </div>

        <div class="summary-card">
            <span>Sahifadagi yozuvlar</span>
            <strong>{{ $attendances->count() }}</strong>
        </div>
    </div>

    <div class="attendance-list">

        @forelse ($attendances as $attendance)

            <article class="attendance-card">

                <div class="attendance-card-header">
                    <div>
                        <span class="attendance-number">
                            Davomat #{{ $attendance->id }}
                        </span>

                        <h3>
                            {{ $attendance->created_at->format('d.m.Y') }}
                        </h3>

                        <p class="attendance-time">
                            {{ $attendance->created_at->format('H:i:s') }}
                        </p>
                    </div>

                    @php
                        $status = $attendance->status ?? 'pending';

                        $statusLabels = [
                            'pending' => 'Tekshirilmoqda',
                            'approved' => 'Tasdiqlangan',
                            'rejected' => 'Bekor qilingan',
                        ];
                    @endphp

                    <span class="status-badge status-{{ $status }}">
                        {{ $statusLabels[$status] ?? $status }}
                    </span>
                </div>

                <div class="attendance-details">

                    <div class="detail-item">
                        <span>Ofis hududidami?</span>
                        <strong>
                            @if ($attendance->is_within_office === null)
                                Aniqlanmagan
                            @elseif ($attendance->is_within_office)
                                Ha
                            @else
                                Yo‘q
                            @endif
                        </strong>
                    </div>

                </div>

                @if ($attendance->reason)
                    <div class="attendance-note">
                        <strong>Izoh:</strong>
                        {{ $attendance->reason }}
                    </div>
                @endif

                @if ($attendance->admin_comment)
                    <div class="admin-note">
                        <strong>Admin izohi:</strong>
                        {{ $attendance->admin_comment }}
                    </div>
                @endif

                @if ($attendance->reviewed_at)
                    <div class="review-date">
                        Ko‘rib chiqilgan:
                        {{ $attendance->reviewed_at->format('d.m.Y H:i') }}
                    </div>
                @endif

            </article>

        @empty

            <div class="attendance-empty">
                <h3>Davomat mavjud emas</h3>
                <p>Davomatdan o‘tganingizdan keyin ma’lumotlar shu yerda ko‘rinadi.</p>

                <a href="{{ route('employee.attendance.show') }}"
                   class="btn btn-primary">
                    Davomatdan o‘tish
                </a>
            </div>

        @endforelse

    </div>

    @if ($attendances->hasPages())
        <div class="attendance-pagination">
            {{ $attendances->links() }}
        </div>
    @endif

</div>

</body>
</html>
