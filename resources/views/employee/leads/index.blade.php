@extends('layouts.employee')

@push('styles')

<link
rel="stylesheet"
href="{{ asset('css/employee/leads.css') }}"
>

@endpush


@section('content')

<div class="page-header">

    <div class="page-heading">
        <h1>Leadlar</h1>

        <div class="breadcrumb">
            <span>{{ $department->name }}</span>
            <i class="fa-solid fa-chevron-right"></i>
            <strong>Leadlar</strong>
        </div>
    </div>

    <div class="page-actions">

        <form method="GET" action="{{ route('employee.leads.index') }}">

            <div class="date-range-filter">

                <div class="date-input">
                    <i class="fa-regular fa-calendar"></i>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                    >
                </div>

                <span class="date-separator">—</span>

                <div class="date-input">
                    <i class="fa-regular fa-calendar"></i>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                    >
                </div>

                <button type="submit" class="date-filter-btn">
                    <i class="fa-solid fa-filter"></i>
                    Filtrlash
                </button>

            </div>

        </form>

        <button class="new-lead-btn" onclick="openCreateLeadModal()">
            <i class="fa-solid fa-plus"></i>
            Yangi lead
        </button>


    </div>

</div>


<!-- Statistikalar -->
<div class="lead-stats">

    <div class="stat-box">
        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>

        <div>
            <span>Jami leadlar</span>
            <strong>{{ $totalLeads }}</strong>
        </div>
    </div>


    @foreach($statuses as $status)

        <div class="stat-box">

            <div
                class="stat-icon"
                style="
                    background: {{ $status->color }}15;
                    color: {{ $status->color }};
                "
            >
                <i class="{{ $status->icon }}"></i>
            </div>

            <div>
                <span>{{ $status->name }}</span>
                <strong>{{ $status->leads_count }}</strong>
            </div>

        </div>

    @endforeach
</div>


<!-- Lead statuslari -->

    
<div class="leads-board">

@foreach($statuses as $status)

    <div class="lead-column">

        <div
            class="column-header"
            style="
                background: {{ $status->color }}15;
                color: {{ $status->color }};
            "
        >
            <div>
                <span>{{ $status->name }}</span>
                <strong>{{ $status->leads_count }}</strong>
            </div>

            <button>
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>

        <div class="lead-list">

            @foreach($status->leads as $lead)

                <div class="lead-card">

                    <div class="lead-card-main">

                        <strong class="lead-name">
                            {{ $lead->client_name }}
                        </strong>

                        <div class="lead-phone">
                            <i class="fa-solid fa-phone"></i>
                            {{ $lead->phone }}
                        </div>

                        @if($lead->travel_date)
                            <div class="lead-time">
                                <i class="fa-regular fa-calendar"></i>
                                {{ $lead->travel_date }}
                            </div>
                        @endif

                        <div class="lead-time">
                            <i class="fa-regular fa-clock"></i>
                            {{ $lead->status_updated_at->format('d.m.Y, H:i') }}
                        </div>

                    </div>

                    <div class="lead-card-actions">

                        {{-- Statusni o‘zgartirish --}}
                        <button
                            type="button"
                            class="lead-status-btn"
                            data-lead="{{ $lead->client_name }}"
                            data-lead-id="{{ $lead->id }}"
                            data-status-id="{{ $lead->status_id }}"
                            title="Statusni o‘zgartirish"
                        >
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        {{-- Tarixni ko‘rish --}}
                        <button
                            type="button"
                            class="lead-history-btn"
                            data-history-url="{{ route('employee.leads.history', $lead) }}"
                            data-lead="{{ $lead->client_name }}"
                            title="Status tarixini ko‘rish"
                        >
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </button>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endforeach

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

@include('employee.leads.edit')
@include('employee.leads.create')

<script src="{{ asset('js/employee/leads.js') }}"></script>

@endsection