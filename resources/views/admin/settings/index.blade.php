@extends('layouts.admin')

@push('styles')

<link
rel="stylesheet"
href="{{ asset('css/admin/settings.css') }}"
>

@endpush
@section('content')

<div class="settings-page">

    <div class="settings-header">
        <div>
            <h1>Sozlamalar</h1>
            <p>Admin tizim sozlamalari</p>
        </div>
    </div>

    @if(session('success'))
        <div class="success-message">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="settings-list">

        <a
            href="{{ route('admin.settings.password') }}"
            class="settings-item"
        >
            <div class="settings-item-left">

                <div class="settings-icon">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div class="settings-info">
                    <h3>Login va parol</h3>
                    <p>Admin login va parolini o‘zgartirish</p>
                </div>

            </div>

            <div class="settings-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
        </a>

    </div>

</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/settings.css') }}">
@endpush