<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Forgot Password</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-start: #e0e7ff;
            --bg-mid: #faf5ff;
            --bg-end: #f0fdfa;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border: #d1d5db;
            --error: #ef4444;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-start), var(--bg-mid), var(--bg-end));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: var(--text-main);
        }

        * {
            box-sizing: border-box;
        }

        .login-container {
            width: 100%;
            max-width: 28rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            padding: 2.5rem 3rem;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .logo {
            width: 4rem;
            height: 4rem;
            background: linear-gradient(to top right, #4f46e5, #9333ea);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: rotate(3deg);
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: rotate(0deg);
        }

        .logo svg {
            width: 2rem;
            height: 2rem;
            color: white;
            stroke: currentColor;
            fill: none;
        }

        .title {
            text-align: center;
            font-size: 1.875rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.025em;
        }

        .subtitle {
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
            margin: 0 0 2rem 0;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.5);
            color: var(--text-main);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            background: #fff;
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem 1rem;
            background-color: var(--primary);
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .submit-btn:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        .back-link {
            display: block;
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .error-message {
            color: var(--error);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            list-style: none;
            padding: 0;
        }

        .status-message {
            color: #059669;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            text-align: center;
            background: #d1fae5;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #10b981;
        }

        @media (max-width: 640px) {
            .login-card {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">

            <div class="logo-container">
                <div>
                    <img src="{{ public_path('assets/image/logo1.png') }}" alt="Dermatales Logo"
                        style="width: 150px; height: auto;">
                </div>
            </div>

            <h2 class="title">Reset Password</h2>
            <p class="subtitle">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
            </p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="form-input">
                    @if ($errors->get('email'))
                        <ul class="error-message">
                            @foreach ((array) $errors->get('email') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    {{ __('Email Password Reset Link') }}
                </button>
            </form>

            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="back-link">&larr; Back to login</a>
            @endif
        </div>
    </div>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('resetForm');
            const submitBtn = document.getElementById('submitBtn');

            if (form && submitBtn) {
                form.addEventListener('submit', function () {
                    submitBtn.innerHTML = 'Sending link...';
                    submitBtn.style.opacity = '0.7';
                    submitBtn.style.pointerEvents = 'none';
                });
            }
        });
    </script>
</body>

</html>