@extends('layouts.admin')

@push('styles')

<link
rel="stylesheet"
href="{{ asset('css/admin/settings.css') }}"
>

@endpush
@section('content')
<div class="settings-card">

    <div class="settings-card-header">
        <div>
            <h2>Login va parol</h2>
            <p>Admin login va parolini o‘zgartirish</p>
        </div>
    </div>

    <form
        method="POST"
        action="{{ route('admin.settings.password.update') }}"
        class="settings-form"
    >
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Eski parol</label>

            <input
                type="password"
                name="current_password"
                placeholder="Eski parolni kiriting"
            >

            @error('current_password')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Yangi login</label>

            <input
                type="text"
                name="username"
                value="{{ old('username', auth()->user()->username) }}"
                placeholder="Yangi login"
            >

            @error('username')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Yangi parol</label>

            <input
                type="password"
                name="password"
                placeholder="Yangi parol"
            >

            @error('password')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Yangi parolni tasdiqlash</label>

            <input
                type="password"
                name="password_confirmation"
                placeholder="Yangi parolni qayta kiriting"
            >
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.settings.index') }}">
                Bekor qilish
            </a>

            <button type="submit">
                <i class="fa-solid fa-check"></i>
                Saqlash
            </button>
        </div>

    </form>

</div>
@endsection