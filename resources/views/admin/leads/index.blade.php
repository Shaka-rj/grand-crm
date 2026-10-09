@extends('layouts.admin')

@push('styles')

<link
rel="stylesheet"
href="{{ asset('css/admin/leads.css') }}"
>

@endpush


@section('content')

<div class="admin-leads-page">

    <div class="page-header">
        <div>
            <h1>Leadlar</h1>
            <p>Barcha bo‘limlardagi leadlar</p>
        </div>

        <div class="total-leads">
            Jami: <strong>{{ $leads->total() }}</strong>
        </div>
    </div>


    {{-- FILTER --}}
    <form method="GET"
          action="{{ route('admin.leads.index') }}"
          class="lead-filters">

        <div class="filter-item department-filter">

            <label>Bo‘limlar</label>

            <button type="button" class="filter-select-btn">
                <span id="departmentFilterText">Bo‘limni tanlang</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="department-dropdown">

            @foreach($departments as $department)

                <label class="check-option">

                    <input
                        type="checkbox"
                        name="department_ids[]"
                        value="{{ $department->id }}"
                        data-name="{{ $department->name }}"
                        {{
                            in_array(
                                $department->id,
                                request('department_ids', [session('admin_department_id')])
                            )
                            ? 'checked'
                            : ''
                        }}
                    >

                    <span>{{ $department->name }}</span>

                </label>

            @endforeach

            </div>

        </div>


        <div class="filter-item status-filter">

            <label>Status</label>

            <button type="button" class="filter-select-btn status-select-btn">
                <span>Statuslarni tanlang</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="status-dropdown">

                @foreach($statuses as $status)

                    <label class="check-option">

                        <input
                            type="checkbox"
                            name="status_ids[]"
                            value="{{ $status->id }}"
                            {{ in_array($status->id, request('status_ids', [])) ? 'checked' : '' }}
                        >

                        <span
                            class="status-dot"
                            style="background: {{ $status->color }}"
                        ></span>

                        <span>{{ $status->name }}</span>

                    </label>

                @endforeach

            </div>

        </div>


        <div class="filter-item date-filter">

            <label>Sana</label>

            <div class="date-range">

                <input
                    type="date"
                    name="date_from"
                    value="{{ request('date_from') }}"
                >

                <span>—</span>

                <input
                    type="date"
                    name="date_to"
                    value="{{ request('date_to') }}"
                >

            </div>

        </div>


        <div class="filter-actions">

            <button type="submit" class="filter-btn">
                <i class="fa-solid fa-filter"></i>
                Filtrlash
            </button>

            <a
                href="{{ route('admin.leads.index') }}"
                class="reset-btn"
            >
                Tozalash
            </a>

        </div>

    </form>


    {{-- TABLE --}}
    <div class="leads-table-card">

        <div class="table-wrapper">

            <table class="leads-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ism</th>
                        <th>Telefon</th>
                        <th>Bo‘lim</th>
                        <th>Xodim</th>
                        <th>Status</th>
                        <th>Amallar</th>
                        <th>Sana</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($leads as $lead)

                        <tr>

                            <td class="lead-id">
                                {{ str_pad($lead->id, 6, '0', STR_PAD_LEFT) }}
                            </td>

                            <td>
                                <div class="client-name">
                                    {{ $lead->client_name }}
                                </div>
                            </td>

                            <td>
                                {{ $lead->phone }}
                            </td>

                            <td>
                                <span class="department-name">
                                    {{ $lead->department->name }}
                                </span>
                            </td>

                            <td>
                                @if($lead->user)
                                    <div class="employee-name">
                                        {{ $lead->user->name }}
                                    </div>
                                @else
                                    <span class="not-assigned">
                                        Biriktirilmagan
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span
                                    class="status-badge"
                                    style="
                                        color: {{ $lead->status->color }};
                                        background: {{ $lead->status->color }}15;
                                    "
                                >
                                    {{ $lead->status->name }}
                                </span>
                            </td>

                            <td>
                                <button
                                    type="button"
                                    class="lead-history-btn"
                                    data-history-url="{{ route('admin.leads.history', $lead) }}"
                                    data-lead="{{ $lead->client_name }}"
                                    title="Status tarixini ko‘rish"
                                >
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </button>
                            </td>

                            <td class="lead-date">
                                {{ $lead->created_at->format('d.m H:i') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="empty-row">
                                Leadlar topilmadi
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="pagination">
            {{ $leads->withQueryString()->links() }}
        </div>

    </div>

</div>



<div class="history-modal" id="historyModal" hidden>
    <div class="history-overlay"></div>

    <div class="history-box">
        <div class="history-header">
            <div>
                <h3>Status tarixi</h3>
                <span id="historyLeadName"></span>
            </div>

            <button type="button" id="closeHistory">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="history-content" id="historyContent">
            Tarix yuklanmoqda...
        </div>
    </div>
</div>

<script src="{{ asset('js/admin/leads.js') }}"></script>
@endsection