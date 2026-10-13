@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/leads.css') }}">
@endpush

@section('content')

<div class="admin-leads-page">

    <div class="page-header">
        <div>
            <h1>Yangi lead qo‘shish</h1>
            <p>Yangi mijoz ma’lumotlarini kiriting</p>
        </div>

        <a href="{{ route('admin.leads.index') }}" class="reset-btn">
            Ortga
        </a>
    </div>

    <div class="leads-table-card lead-edit-card">

        <form
            method="POST"
            action="{{ route('admin.leads.store') }}"
        >
            @csrf

            <div class="lead-form-body">

                <div class="lead-form-group">
                    <label for="client_name">Mijoz ismi</label>
                    <input
                        type="text"
                        name="client_name"
                        id="client_name"
                        value="{{ old('client_name') }}"
                        maxlength="255"
                        required
                    >
                    @error('client_name')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="lead-form-group">
                    <label for="phone">Telefon raqami</label>
                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        value="{{ old('phone') }}"
                        maxlength="30"
                        required
                    >
                    @error('phone')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="lead-form-group">
                    <label for="travel_date">Qachon bormoqchi?</label>
                    <input
                        type="text"
                        name="travel_date"
                        id="travel_date"
                        value="{{ old('travel_date') }}"
                        maxlength="255"
                    >
                    @error('travel_date')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="lead-form-group">
                    <label for="department_id">Bo‘lim</label>
                    <select name="department_id" id="department_id" required>
                        <option value="">Bo‘limni tanlang</option>

                        @foreach($departments as $department)
                            <option
                                value="{{ $department->id }}"
                                @selected(old('department_id') == $department->id)
                            >
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="lead-form-group">
                    <label for="status_id">Status</label>
                    <select name="status_id" id="status_id" required>
                        <option value="">Statusni tanlang</option>

                        @foreach($statuses as $status)
                            <option
                                value="{{ $status->id }}"
                                @selected(old('status_id') == $status->id)
                            >
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_id')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="lead-form-group">
                    <label for="user_id">Biriktirilgan xodim</label>
                    <select name="user_id" id="user_id">
                        <option value="">Biriktirilmagan</option>

                        @foreach($employees as $employee)
                            <option
                                value="{{ $employee->id }}"
                                @selected(old('user_id') == $employee->id)
                            >
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="lead-form-group lead-comment-group">
                    <label for="comment">Izoh</label>
                    <textarea
                        name="comment"
                        id="comment"
                        rows="5"
                        maxlength="2000"
                        placeholder="Mijoz bilan suhbat natijasi yoki qo‘shimcha ma’lumot..."
                    >{{ old('comment') }}</textarea>

                    @error('comment')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="lead-modal-footer">
                <button type="submit" class="lead-modal-submit">
                    <i class="fa-solid fa-check"></i>
                    Leadni saqlash
                </button>
            </div>

        </form>
    </div>

</div>

@endsection