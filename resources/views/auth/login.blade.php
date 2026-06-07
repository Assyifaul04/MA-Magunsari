<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Sistem Absensi</title>

    <link href="{{ asset('image/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand:        #3b5bdb;
            --brand-dark:   #2f4ac2;
            --brand-light:  #eef2ff;
            --danger:       #e03131;
            --danger-bg:    #fff5f5;
            --surface:      #ffffff;
            --surface-soft: #f8f9fc;
            --border:       #e4e8f0;
            --text-primary: #1a1d23;
            --text-muted:   #6c757d;
            --radius:       10px;
            --transition:   all .22s ease;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #eef1f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Subtle grid bg */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(59,91,219,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59,91,219,.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* decorative blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 { width: 400px; height: 400px; background: rgba(59,91,219,.10); top: -120px; right: -100px; }
        .blob-2 { width: 320px; height: 320px; background: rgba(100,200,255,.08); bottom: -80px; left: -80px; }

        /* ── Card ── */
        .login-card {
            position: relative; z-index: 1;
            width: 100%; max-width: 420px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(59,91,219,.12), 0 2px 8px rgba(0,0,0,.06);
            overflow: hidden;
            animation: slideUp .5s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* top accent bar */
        .login-card::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, var(--brand) 0%, #7ca4f4 100%);
        }

        .card-body { padding: 36px 36px 32px; }

        /* ── Brand header ── */
        .brand-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
        }
        .brand-icon-wrap {
            width: 48px; height: 48px;
            background: var(--brand-light);
            border-radius: 14px;
            display: grid; place-items: center;
            flex-shrink: 0;
            transition: var(--transition);
            cursor: pointer;
        }
        .brand-icon-wrap:hover { background: #dde6ff; transform: rotate(-6deg) scale(1.05); }
        .brand-icon-wrap img { width: 30px; height: 30px; object-fit: contain; }

        .brand-info { line-height: 1.25; }
        .brand-name {
            font-size: 1.2rem; font-weight: 800;
            color: var(--brand); letter-spacing: -.3px;
        }
        .brand-sub {
            font-size: .72rem; font-weight: 500;
            color: var(--text-muted); letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* ── Divider ── */
        .card-divider {
            height: 1px; background: var(--border);
            margin: 0 0 24px;
        }

        /* ── Heading ── */
        .login-heading { margin-bottom: 24px; }
        .login-heading h2 {
            font-size: 1.15rem; font-weight: 700;
            color: var(--text-primary); margin-bottom: 3px;
        }
        .login-heading p {
            font-size: .82rem; color: var(--text-muted); font-weight: 400;
        }

        /* ── Alert ── */
        .alert-pro {
            display: flex; align-items: flex-start; gap: 10px;
            background: var(--danger-bg);
            border: 1px solid rgba(224,49,49,.2);
            border-left: 3px solid var(--danger);
            border-radius: var(--radius);
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: .84rem; color: var(--danger);
            font-weight: 500;
        }
        .alert-pro i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
        .alert-pro .btn-close {
            margin-left: auto; flex-shrink: 0;
            font-size: .7rem; opacity: .6;
        }
        .alert-pro .btn-close:hover { opacity: 1; }

        /* ── Form ── */
        .field-group { margin-bottom: 18px; }
        .field-label {
            display: block;
            font-size: .75rem; font-weight: 700;
            color: var(--text-primary);
            letter-spacing: .03em;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .field-input-wrap {
            display: flex; align-items: center;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            transition: var(--transition);
            overflow: hidden;
        }
        .field-input-wrap:focus-within {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(59,91,219,.1);
        }
        .field-input-wrap.is-error {
            border-color: var(--danger);
        }
        .field-input-wrap.is-error:focus-within {
            box-shadow: 0 0 0 3px rgba(224,49,49,.1);
        }

        .field-icon {
            width: 44px; height: 44px;
            display: grid; place-items: center;
            color: var(--text-muted);
            font-size: .95rem;
            flex-shrink: 0;
            transition: color .2s;
        }
        .field-input-wrap:focus-within .field-icon { color: var(--brand); }

        .field-input-wrap input {
            flex: 1;
            height: 44px;
            border: none; outline: none;
            background: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .875rem;
            color: var(--text-primary);
            padding: 0 14px 0 0;
        }
        .field-input-wrap input::placeholder { color: #bec5d0; }

        .field-error {
            font-size: .77rem; color: var(--danger);
            font-weight: 500; margin-top: 5px;
            display: flex; align-items: center; gap: 4px;
        }

        /* ── Submit Button ── */
        .btn-submit {
            width: 100%;
            height: 46px;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .9rem; font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            display: flex; align-items: center; justify-content: center; gap: 8px;
            letter-spacing: .01em;
            margin-top: 6px;
            position: relative; overflow: hidden;
        }
        .btn-submit::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.15) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform .5s ease;
        }
        .btn-submit:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59,91,219,.3);
        }
        .btn-submit:hover::after { transform: translateX(100%); }
        .btn-submit:active { transform: translateY(-1px); }
        .btn-submit.loading { pointer-events: none; opacity: .8; }

        /* ── Footer note ── */
        .card-footer-note {
            text-align: center;
            margin-top: 20px;
            font-size: .75rem;
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .card-footer-note i { color: var(--brand); }

        /* responsive */
        @media (max-width: 480px) {
            body { padding: 16px; }
            .card-body { padding: 24px 20px; }
            .brand-name { font-size: 1.05rem; }
        }
    </style>
</head>

<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="login-card">
        <div class="card-body">

            <div class="brand-header">
                <div class="brand-icon-wrap" id="logoWrap">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo" id="logoImage">
                </div>
                <div class="brand-info">
                    <div class="brand-name">Sistem Absensi</div>
                    <div class="brand-sub">Portal Manajemen</div>
                </div>
            </div>

            <div class="card-divider"></div>

            <div class="login-heading">
                <h2>Masuk ke Akun Anda</h2>
                <p>Masukkan kredensial untuk melanjutkan ke dashboard</p>
            </div>

            @if (session('error'))
                <div class="alert-pro alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf

                <div class="field-group">
                    <label for="email" class="field-label">Email / NIP</label>
                    <div class="field-input-wrap @error('email') is-error @enderror">
                        <div class="field-icon"><i class="bi bi-person-badge"></i></div>
                        <input type="text" name="email" id="email"
                               value="{{ old('email') }}" required autofocus
                               placeholder="Masukkan Email atau NIP">
                    </div>
                    @error('email')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle" style="font-size:.8rem;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password" class="field-label">Password</label>
                    <div class="field-input-wrap @error('password') is-error @enderror">
                        <div class="field-icon"><i class="bi bi-lock-fill"></i></div>
                        <input type="password" name="password" id="password"
                               required placeholder="••••••••">
                    </div>
                    @error('password')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle" style="font-size:.8rem;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button class="btn-submit" type="submit" id="loginBtn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk
                </button>
            </form>

            <div class="card-footer-note">
                <i class="bi bi-shield-check"></i>
                Koneksi aman &amp; terenkripsi
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form     = document.querySelector('.needs-validation');
            const loginBtn = document.getElementById('loginBtn');
            const logoWrap = document.getElementById('logoWrap');

            // Logo interaction
            let flipped = false;
            logoWrap.addEventListener('click', function() {
                flipped = !flipped;
                logoWrap.style.transform = flipped ? 'rotate(-12deg) scale(1.08)' : '';
            });
            setInterval(() => {
                flipped = !flipped;
                logoWrap.style.transform = flipped ? 'rotate(-6deg) scale(1.05)' : '';
                setTimeout(() => {
                    logoWrap.style.transform = '';
                }, 500);
            }, 4000);

            // Form submit
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    loginBtn.classList.add('loading');
                    loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
                }
                form.classList.add('was-validated');
            });

            // Input focus - highlight wrap
            document.querySelectorAll('.field-input-wrap input').forEach(input => {
                input.addEventListener('focus', () => input.closest('.field-input-wrap').classList.add('focused'));
                input.addEventListener('blur',  () => input.closest('.field-input-wrap').classList.remove('focused'));
            });

            // Auto-dismiss alerts
            document.querySelectorAll('.alert-pro').forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity .3s, transform .3s';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-6px)';
                    setTimeout(() => alert.remove(), 350);
                }, 5000);
            });
        });
    </script>
</body>
</html>