<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dermatales - Register</title>
    <meta name="description" content="Create an account for Dermatales Clinic Management System.">

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

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .form-header .form-label {
            margin-bottom: 0;
        }

        .forgot-link {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--primary-hover);
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
            margin-top: 1.5rem;
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

        .register-prompt {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .register-link {
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .register-link:hover {
            color: var(--primary-hover);
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
                    <img src="{{ asset('assets/image/logo1.png') }}" alt="Dermatales Logo"
                        style="width: 150px; height: auto;">
                </div>
            </div>

            <h2 class="title">Create an Account</h2>
            <p class="subtitle">Sign up to get started</p>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        autocomplete="name" class="form-input">
                    @if ($errors->get('name'))
                        <ul class="error-message">
                            @foreach ((array) $errors->get('name') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="username" class="form-input">
                    @if ($errors->get('email'))
                        <ul class="error-message">
                            @foreach ((array) $errors->get('email') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="form-input">
                    @if ($errors->get('password'))
                        <ul class="error-message">
                            @foreach ((array) $errors->get('password') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="form-input">
                    @if ($errors->get('password_confirmation'))
                        <ul class="error-message">
                            @foreach ((array) $errors->get('password_confirmation') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="register-prompt" style="margin-top: 1rem; text-align: right;">
                    <a href="{{ route('login') }}" class="forgot-link">Already registered?</a>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    Register
                </button>
            </form>
        </div>
    </div>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            // Add a simple loading state when the form is submitted
            if (form && submitBtn) {
                form.addEventListener('submit', function () {
                    submitBtn.innerHTML = 'Registering...';
                    submitBtn.style.opacity = '0.7';
                    submitBtn.style.pointerEvents = 'none';
                });
            }
        });
    </script>
</body>

</html>