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

        <button class="add-employee-btn" onclick="openEmployeeModal()">
            <i class="fa-solid fa-plus"></i>
            Yangi hodim qo‘shish
        </button>
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
<div class="employee-modal" id="employeeModal">
    <div class="employee-modal-overlay" onclick="closeEmployeeModal()"></div>

    <div class="employee-modal-card">
        <!-- Header -->
        <div class="employee-modal-header">
            <div>
                <h2>Yangi hodim</h2>
                <p>Hodim ma'lumotlarini kiriting</p>
            </div>

            <button type="button" class="modal-close" onclick="closeEmployeeModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.employees.store') }}">
            @csrf
            <div class="employee-form-group">
                <label> Ism </label>

                <input type="text" name="name" placeholder="Hodim ismini kiriting" required />
            </div>

            <div class="employee-form-group">
                <label> Username </label>

                <input type="text" name="username" placeholder="Username kiriting" required />
            </div>

            <div class="employee-form-group">
                <label> Parol </label>

                <input type="password" name="password" placeholder="Parol kiriting" required />
            </div>

            <!-- Departments -->
            <div class="employee-form-group">
                <label> Qaysi bo‘limlarda ishlaydi? </label>

                <div class="department-checkboxes">
                    @foreach($departments as $department)

                    <label class="department-checkbox">

                        <input
                        type="checkbox"
                        name="departments[]"
                        value="{{ $department->id }}"
                        {{ in_array(
                        $department->id,
                        old('departments', [])
                        ) ? 'checked' : '' }}
                        >

                        <span class="checkbox-custom">
                            <i class="fa-solid fa-check"></i>
                        </span>

                        <span class="department-checkbox-name">
                            {{ $department->name }}
                        </span>

                    </label>

                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="employee-modal-actions">
                <button type="button" class="modal-cancel" onclick="closeEmployeeModal()">Bekor qilish</button>

                <button type="submit" class="modal-submit">
                    <i class="fa-solid fa-user-plus"></i>
                    Hodim qo‘shish
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="employee-modal" id="editEmployeeModal">
    <div class="employee-modal-overlay" onclick="closeEditEmployeeModal()"></div>

    <div class="employee-modal-card">
        <!-- Header -->
        <div class="employee-modal-header">
            <div>
                <h2>Hodimni tahrirlash</h2>
                <p>Hodim ma'lumotlarini o‘zgartirish</p>
            </div>

            <button type="button" class="modal-close" onclick="closeEditEmployeeModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Form -->
        <form method="POST" id="editEmployeeForm" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="employee-form-group">
                <label>Ism</label>

                <input
                type="text"
                name="name"
                id="editName"
                required />
            </div>

            <div class="employee-form-group">
                <label>Username</label>

                <input
                type="text"
                name="username"
                id="editUsername"
                required
                >
            </div>

            <div class="employee-form-group">
                <label>Yangi parol</label>

                <input
                type="password"
                name="password"
                placeholder="O‘zgartirmasangiz bo‘sh qoldiring"
                >

                <small class="password-hint"> Parolni o‘zgartirish shart emas. </small>
            </div>

            <!-- Departments -->
            <div class="employee-form-group">

                <label>Qaysi bo‘limlarda ishlaydi?</label>

                <div class="department-checkboxes">

                    @foreach($departments as $department)

                    <label class="department-checkbox">

                        <input
                        type="checkbox"
                        name="departments[]"
                        value="{{ $department->id }}"
                        class="edit-department"
                        >

                        <span class="checkbox-custom">
                            <i class="fa-solid fa-check"></i>
                        </span>

                        <span class="department-checkbox-name">
                            {{ $department->name }}
                        </span>

                    </label>

                    @endforeach

                </div>

            </div>

            <!-- Actions -->
            <div class="edit-employee-actions">
        <div class="edit-actions-right">
            <button type="button" class="modal-cancel" onclick="closeEditEmployeeModal()">
                Bekor qilish
            </button>

            <button type="submit" class="modal-submit">
                <i class="fa-solid fa-check"></i>
                Saqlash
            </button>
        </div>
    </div>
</form>
<form method="POST"
    id="deleteEmployeeForm">
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="delete-employee-btn"
        onclick="return confirm('Haqiqatan ham bu hodimni o‘chirmoqchimisiz?')">
        <i class="fa-regular fa-trash-can"></i>
        Hodimni o‘chirish2
    </button>

</form>
</div>
</div>
<script src="{{ asset('js/admin/employee.js') }}"></script>

@endsection