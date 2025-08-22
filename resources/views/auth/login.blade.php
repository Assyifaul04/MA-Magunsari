<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Sistem Absensi</title>

    <!-- Favicon -->
    <link href="{{ asset('image/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4154f1;
            --primary-dark: #2c3cdd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --white-color: #ffffff;
            --shadow: 0px 0px 30px rgba(1, 41, 112, 0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease-in-out;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Nunito", sans-serif;
            background: var(--white-color);
            color: var(--dark-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 25% 25%, rgba(65, 84, 241, 0.02) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(65, 84, 241, 0.03) 0%, transparent 50%),
                linear-gradient(135deg, rgba(65, 84, 241, 0.01) 0%, transparent 50%);
            z-index: -1;
            pointer-events: none;
        }

        /* Main container */
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Card styling */
        .login-card {
            background: var(--white-color);
            border: none;
            border-radius: 15px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        }

        .card-body {
            padding: 2.5rem;
        }

        /* Logo section */
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-image {
            height: 70px;
            width: 70px;
            object-fit: contain;
            margin-bottom: 1rem;
            filter: drop-shadow(0 4px 8px rgba(65, 84, 241, 0.1));
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .logo-text:hover {
            color: var(--primary-dark);
            text-decoration: none;
        }

        /* Header section */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            color: var(--secondary-color);
            font-size: 0.95rem;
            margin-bottom: 0;
            font-weight: 400;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: var(--light-color);
            border: 1px solid #e0e6ed;
            border-right: none;
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            color: var(--secondary-color);
            width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control {
            border: 1px solid #e0e6ed;
            border-left: none;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
            background: var(--white-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(65, 84, 241, 0.1);
            outline: none;
        }

        .form-control:focus+.input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            background: rgba(65, 84, 241, 0.05);
            color: var(--primary-color);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
        }

        /* Button styling */
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: var(--border-radius);
            color: var(--white-color);
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.875rem 1.5rem;
            width: 100%;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            text-transform: none;
            letter-spacing: 0.5px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--transition);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark), #1e2bbd);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(65, 84, 241, 0.3);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .btn-login:focus {
            box-shadow: 0 0 0 0.2rem rgba(65, 84, 241, 0.25);
        }

        /* Alert styling */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-left: 3rem;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-danger::before {
            content: '⚠';
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
        }

        .alert .btn-close {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* Validation feedback */
        .invalid-feedback {
            display: block !important;
            font-size: 0.875rem;
            color: var(--danger-color);
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* Credits */
        .credits {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--secondary-color);
            font-weight: 400;
        }

        .credits strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Responsive design */
        @media (max-width: 576px) {
            .main-container {
                padding: 15px;
            }

            .card-body {
                padding: 2rem 1.5rem;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .logo-image {
                height: 60px;
                width: 60px;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            position: relative;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top-color: var(--white-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <main class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-sm-9">
                    <div class="card login-card">
                        <div class="card-body">
                            <!-- Logo Section -->
                            <div class="logo-section">
                                <a href="#" class="text-decoration-none">
                                    <img src="{{ asset('image/logo.png') }}" alt="Logo" class="logo-image">
                                    <div class="logo-text">Sistem Absensi</div>
                                </a>
                            </div>

                            <!-- Header Section -->
                            <div class="login-header">
                                <h1 class="login-title">Selamat Datang!</h1>
                                <p class="login-subtitle">Silakan masuk ke akun admin Anda</p>
                            </div>

                            <!-- Alert Error -->
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Form Login -->
                            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                                @csrf

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required autofocus
                                            placeholder="Masukkan email Anda">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" required
                                            placeholder="Masukkan password Anda">
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <button class="btn btn-login" type="submit" id="loginBtn">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        Masuk
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Credits -->
                    <div class="credits">
                        &copy; {{ now()->year }} <strong>Sistem Absensi</strong>. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.needs-validation');
            const loginBtn = document.getElementById('loginBtn');

            // Enhanced form validation
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    // Add loading state to button
                    loginBtn.classList.add('loading');
                    loginBtn.innerHTML = '<span style="opacity: 0;">Memproses...</span>';
                }

                form.classList.add('was-validated');
            });

            // Input focus enhancement
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });

            // Auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>

</html>
