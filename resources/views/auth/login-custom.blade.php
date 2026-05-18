<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PKK Kabupaten Toba</title>
    <link href="https://cdn.jsdelivr.net/npm/@fontsource/plus-jakarta-sans@5.0.19/index.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0f6b63;
            --primary-light: #197a6e;
            --gold: #d69e2e;
            --bg-light: #f7fafc;
            --text-dark: #1a202c;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-logo {
            width: 70px; height: 70px; border-radius: 50%;
            margin: 0 auto 1rem; object-fit: cover;
            border: 3px solid var(--gold);
        }
        .login-header h1 {
            font-size: 1.4rem; font-weight: 700; color: var(--primary);
            margin-bottom: 0.3rem;
        }
        .login-header p { color: var(--text-muted); font-size: 0.9rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block; margin-bottom: 0.5rem;
            font-weight: 600; font-size: 0.9rem; color: var(--text-dark);
        }
        .form-control {
            width: 100%; padding: 0.85rem 1rem;
            border: 2px solid #e2e8f0; border-radius: 10px;
            font-family: inherit; font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            outline: none; border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15,107,99,0.1);
        }
        .btn-login {
            width: 100%; padding: 0.9rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff; border: none; border-radius: 10px;
            font-family: inherit; font-weight: 600; font-size: 1rem;
            cursor: pointer; transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15,107,99,0.4);
        }
        .error-message {
            background: #fff5f5; color: #c53030;
            padding: 0.75rem 1rem; border-radius: 8px;
            margin-bottom: 1rem; font-size: 0.9rem;
        }
        .forgot-link {
            display: block; text-align: center;
            color: var(--primary); text-decoration: none;
            font-size: 0.85rem; margin-top: 1rem;
        }
        .forgot-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('assets/landing/images/PKK-Logo.png') }}" alt="PKK Logo" class="login-logo">
            <h1>Admin Panel</h1>
            <p>PKK Kabupaten Toba</p>
        </div>

        @if($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" 
                       value="{{ old('email') }}" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn-login">Masuk</button>
        </form>
        
        <a href="#" class="forgot-link">Lupa password?</a>
    </div>
</body>
</html>