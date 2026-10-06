<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bo‘limni tanlash</title>

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
</head>

<body>

    <div class="auth-page">
        <div class="auth-right">

            <div class="auth-card department-card">
                <div class="auth-heading">
                    <h1>Bo‘limni tanlang</h1>

                    <p>
                        Qaysi bo‘lim nomidan ishlaysiz?
                    </p>

                </div>

                <form method="POST" action="{{ route('employee.department.store') }}">

                    @csrf

                    <div class="department-list">
                        @foreach($departments as $department)

                        <label class="department-option">
                            <input
                            type="radio"
                            name="department_id"
                            value="{{ $department->id }}"
                            required
                            >
                            <span class="department-radio">
                                <span></span>
                            </span>

                            <span class="department-option-icon">
                                <img src="{{ asset('img/'.$department->icon) }}">
                            </span>

                            <span class="department-option-info">
                                <strong>{{ $department->name }}</strong>
                                <small>{{ $department->description }}</small>
                            </span>

                        </label>

                        @endforeach      
                    </div>


                    <button type="submit" class="auth-button">
                        Davom etish
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>
</html>