@extends('layouts.admin')

@push('styles')

<link
rel="stylesheet"
href="{{ asset('css/admin/employee.css') }}"
>

@endpush


@section('content')

<div class="employees-page">
    <!-- Header -->

    <div class="employees-header">
        <div>
            <h1>Hodimlar</h1>
            <p>Tizimdagi barcha hodimlarni boshqarish</p>
        </div>

        <div class="employees-header-actions">
            <a href="{{ route('admin.attendance.index') }}"
               class="attendance-admin-open-btn">
                <i class="fa-solid fa-calendar-check"></i>
                Davomat
            </a>
            
            <button type="button"
                    class="add-employee-btn"
                    onclick="openEmployeeModal()">
                <i class="fa-solid fa-plus"></i>
                Yangi hodim qo‘shish
            </button>
        </div>
    </div>


    <!-- Table Card -->
    <div class="employees-card">
        <div class="employees-card-header">
            <div>
                <h2>Hodimlar ro‘yxati</h2>
                <span>Jami {{$activeEmployees}} ta hodim</span>
            </div>
        </div>

        <div class="employees-table-wrapper">
            <table class="employees-table">
                <thead>
                    <tr>
                        <th>ISM</th>
                        <th>USERNAME</th>
                        <th>BO‘LIMLAR</th>
                        <th>HOLATI</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($employees as $employee)

                    <tr>
                        <td>
                            <div class="employee-info">

                                <div class="employee-avatar">
                                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                                </div>

                                <div>
                                    <strong>{{ $employee->name }}</strong>
                                    <span>Hodim</span>
                                </div>

                            </div>
                        </td>
                        <td>
                            <span class="username">
                                {{ $employee->username }}
                            </span>
                        </td>

                        <td>
                            <div class="department-tags">

                                @foreach($employee->departments as $department)

                                <span class="department-tag">
                                    {{ $department->name }}
                                </span>

                                @endforeach

                            </div>
                        </td>
                        <td>

                            @if($employee->status)

                            <span class="employee-status active">
                                Faol
                            </span>

                            @else

                            <span class="employee-status inactive">
                                Faol emas
                            </span>

                            @endif

                        </td>
                        <td class="employee-actions">
                            <button
                            type="button"
                            class="edit-btn"
                            onclick="openEditEmployeeModal(
                                {{ $employee->id }},
                                @js($employee->name),
                                @js($employee->username),
                                @js($employee->departments->pluck('id'))
                                )"
                                >
                                <i class="fa-solid fa-pen"></i>
                                Tahrirlash
                            </button>
                        </td>

                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Employee Modal -->

@include('admin.employee.create')

<!-- Edit Employee Modal -->

@include('admin.employee.edit')

<script src="{{ asset('js/admin/employee.js') }}"></script>

@endsection