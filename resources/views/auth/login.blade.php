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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4154f1;
            --primary-dark: #2c3cdd;
            --secondary-color: #6c757d;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --white-color: #ffffff;
            --shadow: 0px 0px 30px rgba(1, 41, 112, 0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease-in-out;
            --orange-primary: #169af9;
            --orange-dark: #0c7fea;
            --success-color: #22c55e;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Nunito", sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--dark-color);
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
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
                radial-gradient(circle at 40% 80%, rgba(249, 115, 22, 0.02) 0%, transparent 50%);
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

        /* Login wrapper - container utama */
        .login-wrapper {
            background: var(--white-color);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            width: 100%;
            max-width: 850px;
            min-height: 480px;
            display: flex;
            position: relative;
        }

        .login-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--orange-primary));
        }

        /* Login section - bagian kiri */
        .login-section {
            flex: 1;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 350px;
        }

        /* Animation section - bagian kanan */
        .animation-section {
            flex: 1;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 480px;
            overflow: hidden;
        }

        .animation-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(249, 115, 22, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(168, 85, 247, 0.03) 0%, transparent 50%);
        }

        /* Logo section */
        .logo-section {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 2.5rem;
            gap: 15px;
        }

        .logo-image {
            height: 50px;
            width: 50px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(65, 84, 241, 0.1));
            transition: transform 0.6s ease-in-out;
            cursor: pointer;
        }

        .logo-image:hover,
        .logo-image.flipped {
            transform: rotateY(180deg);
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .logo-text:hover {
            color: var(--primary-dark);
            text-decoration: none;
        }

        /* Welcome text */
        .welcome-text {
            margin-bottom: 1.5rem;
        }

        .welcome-text h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.3rem;
        }

        .welcome-text p {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin: 0;
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
            transition: var(--transition);
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

        .form-control:focus ~ .input-group-text,
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
            font-size: 0.9rem;
            padding: 0.75rem 1.3rem;
            width: 100%;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            text-transform: none;
            letter-spacing: 0.3px;
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

        /* === RFID ANIMATION STYLES === */
        .rfid-system {
            position: relative;
            width: 280px;
            height: 280px;
            z-index: 2;
        }

        .rfid-reader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 65px;
            background: linear-gradient(145deg, #ffffff, #f1f5f9);
            border-radius: 10px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            cursor: pointer;
            transition: var(--transition);
        }

        .rfid-reader:hover {
            transform: translate(-50%, -50%) scale(1.05);
            box-shadow: 0 12px 30px rgba(1, 41, 112, 0.15);
        }

        .rfid-reader::before {
            content: '';
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .rfid-card {
            position: absolute;
            width: 50px;
            height: 32px;
            background: #fff;
            border-radius: 5px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }

        .rfid-card::before {
            content: '';
            width: 20px;
            height: 12px;
            background: linear-gradient(135deg, #89a9d3, #0babf5);
            border-radius: 2px;
        }

        .rfid-card:nth-child(2) {
            top: 20%;
            left: 20%;
            animation: cardFloat1 4s infinite;
        }

        .rfid-card:nth-child(3) {
            top: 25%;
            right: 15%;
            animation: cardFloat2 4s infinite 1s;
        }

        .rfid-card:nth-child(4) {
            bottom: 30%;
            left: 15%;
            animation: cardFloat3 4s infinite 2s;
        }

        .rfid-card:nth-child(5) {
            bottom: 25%;
            right: 20%;
            animation: cardFloat4 4s infinite 3s;
        }

        /* Gelombang sinyal */
        .signal-wave {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 2px solid var(--orange-primary);
            border-radius: 50%;
            opacity: 0;
            animation: signalWave 3s infinite;
        }

        .signal-wave:nth-child(6) {
            width: 120px;
            height: 120px;
            animation-delay: 0s;
        }

        .signal-wave:nth-child(7) {
            width: 160px;
            height: 160px;
            animation-delay: 0.5s;
        }

        .signal-wave:nth-child(8) {
            width: 200px;
            height: 200px;
            animation-delay: 1s;
        }

        /* Titik data */
        .data-point {
            position: absolute;
            width: 6px;
            height: 6px;
            background: var(--orange-primary);
            border-radius: 50%;
            opacity: 0;
            animation: dataFlow 2s infinite;
        }

        .data-point:nth-child(9) {
            top: 10%;
            left: 45%;
            animation-delay: 0s;
        }

        .data-point:nth-child(10) {
            top: 15%;
            right: 40%;
            animation-delay: 0.2s;
        }

        .data-point:nth-child(11) {
            bottom: 20%;
            left: 35%;
            animation-delay: 0.4s;
        }

        .data-point:nth-child(12) {
            bottom: 15%;
            right: 45%;
            animation-delay: 0.6s;
        }

        /* Status */
        .status-indicator {
            position: absolute;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.8rem;
            z-index: 10;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 500;
            color: #334155;
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: statusPulse 2s infinite;
        }

        .status-dot.online {
            background: var(--success-color);
        }

        .status-dot.scanning {
            background: var(--orange-primary);
        }

        /* RFID Animasi */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        @keyframes cardFloat1 {
            0%,100%{opacity:0;transform:translate(0,0) rotate(0deg);}
            25%{opacity:1;}
            50%{opacity:1;transform:translate(100px,50px) rotate(5deg);}
            75%{opacity:0.5;transform:translate(150px,120px) rotate(10deg);}
        }

        @keyframes cardFloat2 {
            0%,100%{opacity:0;transform:translate(0,0) rotate(0deg);}
            25%{opacity:1;}
            50%{opacity:1;transform:translate(-80px,60px) rotate(-5deg);}
            75%{opacity:0.5;transform:translate(-120px,140px) rotate(-10deg);}
        }

        @keyframes cardFloat3 {
            0%,100%{opacity:0;transform:translate(0,0) rotate(0deg);}
            25%{opacity:1;}
            50%{opacity:1;transform:translate(90px,-70px) rotate(7deg);}
            75%{opacity:0.5;transform:translate(130px,-130px) rotate(15deg);}
        }

        @keyframes cardFloat4 {
            0%,100%{opacity:0;transform:translate(0,0) rotate(0deg);}
            25%{opacity:1;}
            50%{opacity:1;transform:translate(-70px,-80px) rotate(-7deg);}
            75%{opacity:0.5;transform:translate(-110px,-140px) rotate(-15deg);}
        }

        @keyframes signalWave {
            0%{opacity:0;transform:translate(-50%,-50%) scale(0.8);}
            50%{opacity:0.6;}
            100%{opacity:0;transform:translate(-50%,-50%) scale(1.2);}
        }

        @keyframes dataFlow {
            0%,100%{opacity:0;transform:scale(0) rotate(0deg);}
            50%{opacity:1;transform:scale(1) rotate(180deg);}
        }

        @keyframes statusPulse {
            0%,100%{opacity:1;}
            50%{opacity:0.5;}
        }

        /* Login animation */
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-section {
            animation: fadeInLeft 0.8s ease-out;
        }

        .animation-section {
            animation: fadeInRight 1s ease-out 0.3s both;
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

        /* Responsive design */
        @media (max-width: 1024px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 600px;
            }

            .login-section {
                min-width: auto;
                padding: 2.5rem;
            }

            .animation-section {
                min-height: 400px;
            }

            .rfid-system {
                width: 300px;
                height: 300px;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
            }

            .login-wrapper {
                min-height: auto;
            }

            .login-section {
                padding: 2rem;
            }

            .animation-section {
                min-height: 350px;
            }

            .rfid-system {
                width: 280px;
                height: 280px;
            }

            .logo-text {
                font-size: 1.5rem;
            }

            .welcome-text h3 {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .login-section {
                padding: 1.5rem;
            }

            .logo-image {
                height: 40px;
                width: 40px;
            }

            .logo-text {
                font-size: 1.3rem;
            }

            .animation-section {
                min-height: 300px;
            }

            .rfid-system {
                width: 250px;
                height: 250px;
            }

            .rfid-reader {
                width: 100px;
                height: 70px;
            }

            .rfid-reader::before {
                width: 35px;
                height: 35px;
            }
        }

        /* Hover effects untuk interactivity */
        .rfid-card:hover {
            transform: scale(1.1) !important;
            opacity: 1 !important;
            z-index: 15;
        }

        /* Focus states for accessibility */
        .rfid-reader:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
    </style>
</head>

<body>
    <main class="main-container">
        <div class="login-wrapper">
            <!-- Login Section - Kiri -->
            <div class="login-section">
                <!-- Logo Section -->
                <div class="logo-section">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo" class="logo-image" id="logoImage">
                    <h2 class="logo-text">Sistem Absensi</h2>
                </div>

                <!-- Welcome Text -->
                <div class="welcome-text">
                    <h3>Selamat Datang!</h3>
                    <p>Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <!-- Alert Error -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

            <!-- Animation Section - Kanan -->
            <div class="animation-section">
                <div class="rfid-system">
                    <!-- RFID Reader -->
                    <div class="rfid-reader" tabindex="0" role="button" aria-label="RFID Reader"></div>

                    <!-- RFID Cards -->
                    <div class="rfid-card"></div>
                    <div class="rfid-card"></div>
                    <div class="rfid-card"></div>
                    <div class="rfid-card"></div>

                    <!-- Signal Waves -->
                    <div class="signal-wave"></div>
                    <div class="signal-wave"></div>
                    <div class="signal-wave"></div>

                    <!-- Data Points -->
                    <div class="data-point"></div>
                    <div class="data-point"></div>
                    <div class="data-point"></div>
                    <div class="data-point"></div>

                    <!-- Status Indicator -->
                    <div class="status-indicator">
                        <div class="status-item">
                            <div class="status-dot online"></div>
                            <span>Online</span>
                        </div>
                        <div class="status-item">
                            <div class="status-dot scanning"></div>
                            <span>Scanning</span>
                        </div>
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
            const logoImage = document.getElementById('logoImage');
            const rfidReader = document.querySelector('.rfid-reader');
            const rfidCards = document.querySelectorAll('.rfid-card');
            const signalWaves = document.querySelectorAll('.signal-wave');
            const dataPoints = document.querySelectorAll('.data-point');

            // Logo flip functionality
            logoImage.addEventListener('click', function() {
                this.classList.toggle('flipped');
            });

            // Auto flip logo every 4 seconds
            setInterval(() => {
                logoImage.classList.toggle('flipped');
            }, 4000);

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

            // RFID Animation interactions
            function triggerRFIDScan() {
                // Enhanced animations
                rfidCards.forEach((card, index) => {
                    card.style.animationDelay = `${index * 0.3}s`;
                    card.style.animationDuration = '2s';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1.1)';
                    }, index * 300);
                });

                signalWaves.forEach((wave, index) => {
                    wave.style.animationDelay = `${index * 0.2}s`;
                    wave.style.animationDuration = '1.5s';
                });

                dataPoints.forEach((point, index) => {
                    point.style.animationDelay = `${index * 0.1}s`;
                    point.style.animationDuration = '1s';
                });

                // Reset after animation
                setTimeout(() => {
                    rfidCards.forEach(card => {
                        card.style.opacity = '';
                        card.style.transform = '';
                    });
                }, 2000);
            }

            // RFID reader interactions
            rfidReader.addEventListener('click', triggerRFIDScan);
            rfidReader.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    triggerRFIDScan();
                }
            });

            // Auto trigger RFID scan every 8 seconds
            setInterval(triggerRFIDScan, 8000);

            // Interactive card hover effects
            rfidCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.15) rotate(5deg)';
                    this.style.opacity = '1';
                    this.style.zIndex = '15';
                    this.style.boxShadow = '0 15px 35px rgba(249, 115, 22, 0.3)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.zIndex = '';
                    this.style.boxShadow = '';
                });
            });

            // Status indicator interactions
            const statusItems = document.querySelectorAll('.status-item');
            statusItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Animate status click
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Parallax effect on mouse move
            const animationSection = document.querySelector('.animation-section');
            animationSection.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / centerY * 5;
                const rotateY = (centerX - x) / centerX * 5;
                
                this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            animationSection.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });

            // Accessibility enhancements
            rfidReader.setAttribute('aria-describedby', 'rfid-description');
            const description = document.createElement('div');
            description.id = 'rfid-description';
            description.className = 'sr-only';
            description.textContent = 'Click to simulate RFID card scanning';
            document.body.appendChild(description);

            // Performance optimization - pause animations when not visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    } else {
                        entry.target.style.animationPlayState = 'paused';
                    }
                });
            });

            // Observe all animated elements
            [...rfidCards, ...signalWaves, ...dataPoints].forEach(el => {
                observer.observe(el);
            });
        });

        // Add CSS class for screen readers
        const style = document.createElement('style');
        style.textContent = `
            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>