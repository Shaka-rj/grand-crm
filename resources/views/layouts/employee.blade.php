<!DOCTYPE html>
<html lang="uz">

@include('components.employee.head')

<body>


    <div class="crm-layout">
        @include('components.employee.sidebar')

        <main class="content">

            @yield('content')

        </main>
    </div>

    @stack('scripts')