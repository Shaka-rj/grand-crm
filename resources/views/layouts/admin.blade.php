<!DOCTYPE html>
<html lang="uz">

@include('components.admin.head')

<body>


    <div class="crm-layout">
        @include('components.admin.sidebar')

        <main class="content">

            @yield('content')

        </main>
    </div>

    @stack('scripts')