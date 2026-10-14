@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/employee.css')}}?v=3">
@endpush

@section('content')
<div class="att-page">

    <div class="att-header">
        <div>
            <h1>Davomatlar</h1>
            <p>Xodimlar davomatini tekshirish</p>
        </div>
        <a href="{{ route('admin.employees.index') }}" class="att-back">
            <i class="fa-solid fa-arrow-left"></i> Xodimlar
        </a>
    </div>

    <div class="att-stats">
        <div><span>Jami</span><strong>{{ $attendances->total() }}</strong></div>
        <div><span>Kutilmoqda</span><strong>{{ $pendingCount }}</strong></div>
        <div><span>Tasdiqlangan</span><strong>{{ $approvedCount }}</strong></div>
        <div><span>Bekor qilingan</span><strong>{{ $rejectedCount }}</strong></div>
    </div>

    <div class="att-list">
        <div class="att-list-head">
            <span>Xodim</span>
            <span>Sana</span>
            <span>Masofa</span>
            <span>Holat</span>
            <span></span>
        </div>

        @forelse ($attendances as $attendance)
            @php
                $status = $attendance->status ?? 'pending';

                $labels = [
                    'pending' => 'Kutilmoqda',
                    'approved' => 'Tasdiqlangan',
                    'rejected' => 'Bekor qilingan',
                ];
            @endphp

            <article class="att-item">
                <div class="att-row">
                    <div class="att-employee">
                        <div class="att-avatar">
                            {{ strtoupper(substr($attendance->user->name ?? '?', 0, 2)) }}
                        </div>
                        <div class="att-employee-text">
                            <strong>{{ $attendance->user->name ?? 'Xodim o‘chirilgan' }}</strong>
                            <small>{{ $attendance->user->username ?? '—' }}</small>
                        </div>
                    </div>

                    <div class="att-date">
                        {{ $attendance->created_at->format('d.m.Y') }}
                        <small>{{ $attendance->created_at->format('H:i') }}</small>
                    </div>

                    <div class="att-distance">
                        {{ $attendance->distance_meters !== null
                            ? number_format($attendance->distance_meters, 0, ',', ' ') . ' m'
                            : '—' }}
                    </div>

                    <div>
                        <span class="att-status att-{{ $status }}">
                            {{ $labels[$status] ?? $status }}
                        </span>
                    </div>

                    <button type="button"
                            class="att-toggle"
                            onclick="attToggle(this)">
                        Batafsil <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>

                <div class="att-details" hidden>
                    <div class="att-detail-grid">
                        <div><small>Davomat ID</small><strong>#{{ $attendance->id }}</strong></div>
                        <div><small>Latitude</small><strong>{{ $attendance->latitude ?? '—' }}</strong></div>
                        <div><small>Longitude</small><strong>{{ $attendance->longitude ?? '—' }}</strong></div>
                        <div>
                            <small>Lokatsiya aniqligi</small>
                            <strong>{{ $attendance->accuracy_meters !== null ? $attendance->accuracy_meters . ' m' : '—' }}</strong>
                        </div>
                        <div>
                            <small>Ofis hududidami?</small>
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

                    @if ($attendance->photo_path)
                        <div class="att-photo">
                            <a href="{{ asset('storage/' . $attendance->photo_path) }}"
                               target="_blank" rel="noopener noreferrer">
                                <img src="{{ asset('storage/' . $attendance->photo_path) }}"
                                     alt="Davomat rasmi" loading="lazy"
                                     onerror="this.closest('.att-photo').style.display='none'">
                            </a>
                        </div>
                    @endif

                    @if ($attendance->reason)
                        <p class="att-note"><b>Xodim izohi:</b> {{ $attendance->reason }}</p>
                    @endif

                    @if ($attendance->admin_comment)
                        <p class="att-note"><b>Admin izohi:</b> {{ $attendance->admin_comment }}</p>
                    @endif

                    @if ($attendance->reviewed_at)
                        <p class="att-note">
                            <b>Ko‘rib chiqilgan:</b>
                            {{ $attendance->reviewed_at->format('d.m.Y H:i') }}
                        </p>
                    @endif

                    @if ($status === 'pending')
                        <div class="att-actions">
                            <form action="{{ route('admin.attendance.approve', $attendance->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Davomat tasdiqlansinmi?')">
                                @csrf
                                <button class="att-approve" type="submit">
                                    <i class="fa-solid fa-check"></i> Tasdiqlash
                                </button>
                            </form>


                        <form action="{{ route('admin.attendance.reject', $attendance->id) }}"
                              method="POST"
                              class="att-reject"
                              onsubmit="return confirm('Davomat bekor qilinsinmi?')">
                            @csrf

                            <input type="text"
                                   name="admin_comment"
                                   maxlength="1000"
                                   placeholder="Bekor qilish sababi..."
                                   required>

                            <button class="att-decline" type="submit">
                                <i class="fa-solid fa-ban"></i> Bekor qilish
                            </button>
                        </form>


                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="att-empty">Davomatlar mavjud emas.</div>
        @endforelse
    </div>

    @if ($attendances->hasPages())
        <div class="att-pagination">{{ $attendances->links() }}</div>
    @endif
</div>

<script>
function attToggle(button) {
    const details = button.closest('.att-item').querySelector('.att-details');
    details.hidden = !details.hidden;

    button.innerHTML = details.hidden
        ? 'Batafsil <i class="fa-solid fa-chevron-down"></i>'
        : 'Yopish <i class="fa-solid fa-chevron-up"></i>';
}
</script>
@endsection
