<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kirish</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>

<div class="auth-page">
    <div class="auth-right">

        <div class="auth-card">

            <div class="mobile-logo">
                <div class="brand-icon">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

                <span>CRM</span>
            </div>

            <div class="auth-heading">

                <h1>Tizimga kirish</h1>

                <p>
                    Hisobingizga kirish uchun ma'lumotlarni kiriting.
                </p>

            </div>


            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="form-group">

                    <label for="login">
                        Login
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-regular fa-user"></i>

                        <input
                            type="text"
                            id="login"
                            name="username"
                            placeholder="Loginni kiriting"
                            autocomplete="username"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="password">
                        Parol
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Parolni kiriting"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword()"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>

                    </div>

                </div>


                <button type="submit" class="auth-button">

                    Kirish

                    <i class="fa-solid fa-arrow-right"></i>

                </button>

            </form>

        </div>

    </div>

</div>


<script>

function togglePassword() {

    const password = document.getElementById('password');
    const button = document.querySelector('.password-toggle i');

    if (password.type === 'password') {

        password.type = 'text';

        button.classList.remove('fa-eye');
        button.classList.add('fa-eye-slash');

    } else {

        password.type = 'password';

        button.classList.remove('fa-eye-slash');
        button.classList.add('fa-eye');

    }

}

</script>

</body>
</html>