@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/leads.css') }}">
@endpush

@section('content')

<div class="admin-leads-page">

    <div class="page-header">
        <div>
            <h1>Leadni tahrirlash</h1>
            <p>Lead #{{ str_pad($lead->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <a href="{{ route('admin.leads.index') }}" class="reset-btn">
            Ortga
        </a>
    </div>

    <div class="leads-table-card lead-edit-card">

        <form
            method="POST"
            action="{{ route('admin.leads.update', $lead) }}"
        >
            @csrf
            @method('PUT')

            <div class="lead-form-body">

                <div class="lead-form-group">
                    <label>Mijoz ismi</label>
                    <input
                        type="text"
                        name="client_name"
                        value="{{ old('client_name', $lead->client_name) }}"
                        required
                    >
                </div>

                <div class="lead-form-group">
                    <label>Telefon raqami</label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $lead->phone) }}"
                        maxlength="30"
                        required
                    >
                </div>

                <div class="lead-form-group">
                    <label>Qachon bormoqchi?</label>
                    <input
                        type="text"
                        name="travel_date"
                        value="{{ old('travel_date', $lead->travel_date) }}"
                    >
                </div>

                <div class="lead-form-group">
                    <label>Bo‘lim</label>

                    <select name="department_id" required>
                        @foreach($departments as $department)
                            <option
                                value="{{ $department->id }}"
                                @selected(
                                    old('department_id', $lead->department_id)
                                    == $department->id
                                )
                            >
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="lead-form-group">
                    <label>Status</label>

                    <select name="status_id" required>
                        @foreach($statuses as $status)
                            <option
                                value="{{ $status->id }}"
                                @selected(
                                    old('status_id', $lead->status_id)
                                    == $status->id
                                )
                            >
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="lead-form-group">
                    <label>Biriktirilgan xodim</label>

                    <select name="user_id">
                        <option value="">Biriktirilmagan</option>

                        @foreach($employees as $employee)
                            <option
                                value="{{ $employee->id }}"
                                @selected(
                                    (string) old('user_id', $lead->user_id)
                                    === (string) $employee->id
                                )
                            >
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="lead-form-group lead-comment-group">
                    <label for="comment">Izoh</label>

                    <textarea
                        name="comment"
                        id="comment"
                        rows="5"
                        maxlength="2000"
                        placeholder="Mijoz bilan suhbat natijasi yoki qo‘shimcha ma’lumot..."
                    >{{ old('comment', $lead->histories->first()?->comment ?? '') }}</textarea>

                    @error('comment')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="lead-form-group">
                    <label>Status yangilangan sana</label>

                    <input
                        type="text"
                        value="{{ $lead->status_updated_at
                            ? $lead->status_updated_at->format('d.m.Y H:i')
                            : 'Hali yangilanmagan' }}"
                        disabled
                    >
                </div>

            </div>

            <div class="lead-modal-footer">
                <button type="submit" class="lead-modal-submit">
                    <i class="fa-solid fa-check"></i>
                    Saqlash
                </button>
            </div>

        </form>

        <form
            method="POST"
            action="{{ route('admin.leads.destroy', $lead) }}"
            onsubmit="return confirm('Leadni o‘chirishni tasdiqlaysizmi?')"
            class="lead-delete-form"
        >
            @csrf
            @method('DELETE')

            <button type="submit" class="lead-delete-btn">
                <i class="fa-regular fa-trash-can"></i>
                Leadni o‘chirish
            </button>
        </form>

    </div>

</div>

@endsection