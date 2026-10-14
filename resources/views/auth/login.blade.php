
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kirish</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
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
                <p>Hisobingizga kirish uchun ma'lumotlarni kiriting.</p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="login">Login</label>

                    <div class="input-wrapper">
                        <i class="fa-regular fa-user"></i>

                        <input
                            type="text"
                            id="login"
                            name="username"
                            placeholder="Loginni kiriting"
                            autocomplete="username"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Parol</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Parolni kiriting"
                            autocomplete="current-password"
                            required>

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword()"
                            aria-label="Parolni ko‘rsatish">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="auth-buttons">
                    <button
                        type="submit"
                        name="action"
                        value="login"
                        class="auth-button">
                        Kirish
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.querySelector('.password-toggle i');

    const showPassword = password.type === 'password';

    password.type = showPassword ? 'text' : 'password';

    icon.classList.toggle('fa-eye', !showPassword);
    icon.classList.toggle('fa-eye-slash', showPassword);
}


</script>

</body>
</html>
