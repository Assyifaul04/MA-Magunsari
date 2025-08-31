<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>MAS Nurul Huda - Sistem Absensi RFID</title>
    <meta content="Sistem Absensi RFID MAS Nurul Huda Mangunsari" name="description">
    <meta content="absensi, rfid, madrasah, sistem" name="keywords">
    
    <!-- Favicons -->
    <link href="image/logo.png" rel="icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #f1f5f9 100%);
            color: #1e293b;
            overflow-x: hidden;
            line-height: 1.6;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.1);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            text-decoration: none;
        }

        .logo i {
            font-size: 1.5rem;
            color: #1625f9;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-auth {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .btn-login {
            color: #475569;
            border-color: rgba(15, 23, 42, 0.2);
            background: transparent;
        }

        .btn-login:hover {
            background: rgba(15, 23, 42, 0.05);
            color: #1e293b;
            border-color: rgba(15, 23, 42, 0.3);
        }

        .btn-signup {
            background: #168bf9;
            color: #ffffff;
            border-color: #1687f9;
        }

        .btn-signup:hover {
            background: #0c6cea;
            color: #ffffff;
            border-color: #0c74ea;
        }

        /* Main Content */
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 6rem 0 4rem;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 30%, #e2e8f0 100%);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(249, 115, 22, 0.08) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.06) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(168, 85, 247, 0.05) 0%, transparent 50%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.2);
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            color: #1687f9;
            animation: fadeInUp 0.6s ease-out;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #0f172a;
            line-height: 1.1;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-title .highlight {
            color: #1662f9;
            background: linear-gradient(135deg, #169af9 0%, #0c82ea 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
            animation: fadeInUp 1.2s ease-out 0.6s both;
        }

        .btn-cta {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1687f9 0%, #0c9cea 100%);
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(249, 115, 22, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(249, 115, 22, 0.35);
            color: #ffffff;
        }

        .btn-secondary {
            background: #ffffff;
            color: #475569;
            border: 2px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.1);
        }

        .btn-secondary:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.15);
        }

        /* Features Section */
        .features-section {
            padding: 4rem 0;
            background: #ffffff;
            position: relative;
            z-index: 2;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(249, 115, 22, 0.3);
            box-shadow: 0 8px 40px rgba(15, 23, 42, 0.1);
            background: #fefefe;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #168bf9 0%, #0c7fea 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.5rem;
            color: #ffffff;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        .feature-description {
            color: #64748b;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #1684f9 0%, #0c82ea 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-top: 3rem;
            animation: fadeInUp 1.4s ease-out 0.8s both;
            box-shadow: 0 8px 40px rgba(249, 115, 22, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Animations */
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

        /* Responsive */
        @media (max-width: 768px) {
            .auth-buttons {
                gap: 0.5rem;
            }
            
            .btn-auth {
                padding: 0.4rem 1rem;
                font-size: 0.9rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-cta {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }

        .loading-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(249, 115, 22, 0.3);
            border-top: 3px solid #16c0f9;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Particle effects */
        @keyframes particleFloat {
            0% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            100% {
                opacity: 0;
                transform: translateY(-100px) scale(0);
            }
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="main-container">
        <!-- Header -->
        <header class="header">
            <div class="container">
                <a href="#" class="logo">
                    <i class="bi bi-mortarboard"></i>
                    <span>MAS Nurul Huda</span>
                </a>
                <div class="auth-buttons">
                    <a href="/login" class="btn-auth btn-login">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Login
                    </a>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="bi bi-lightning-fill"></i>
                        <span>Sistem Absensi RFID Terdepan</span>
                    </div>
                    
                    <h1 class="hero-title">
                        Kelola Absensi dengan<br>
                        <span class="highlight">Teknologi RFID</span>
                    </h1>
                    
                    <p class="hero-subtitle">
                        Sistem absensi modern untuk MAS Nurul Huda Mangunsari. 
                        Monitoring kehadiran siswa secara real-time dengan teknologi RFID yang akurat dan efisien.
                    </p>
                    
                    <div class="cta-buttons">
                        <a href="/login" class="btn-cta btn-primary">
                            <i class="bi bi-rocket-takeoff"></i>
                            <span>Mulai Sekarang</span>
                        </a>
                        <a href="#features" class="btn-cta btn-secondary">
                            <i class="bi bi-play-circle"></i>
                            <span>Lihat Demo</span>
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="stats-section">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <h3 id="studentsCount">0</h3>
                                <p>Total Siswa</p>
                            </div>
                            <div class="stat-item">
                                <h3 id="teachersCount">0</h3>
                                <p>Total Guru</p>
                            </div>
                            <div class="stat-item">
                                <h3>99.9%</h3>
                                <p>Akurasi</p>
                            </div>
                            <div class="stat-item">
                                <h3>24/7</h3>
                                <p>Monitoring</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section" id="features">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Fitur Unggulan</h2>
                    <p class="section-subtitle">
                        Teknologi terdepan untuk mengelola absensi dengan mudah dan efisien
                    </p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-wifi"></i>
                        </div>
                        <h3 class="feature-title">Teknologi RFID</h3>
                        <p class="feature-description">
                            Sistem absensi menggunakan teknologi RFID untuk akurasi tinggi dan kecepatan proses yang optimal
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h3 class="feature-title">Laporan Real-time</h3>
                        <p class="feature-description">
                            Monitor kehadiran siswa dan guru secara real-time dengan dashboard yang interaktif dan informatif
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Keamanan Tinggi</h3>
                        <p class="feature-description">
                            Data absensi tersimpan aman dengan enkripsi tingkat enterprise dan backup otomatis
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h3 class="feature-title">Notifikasi Otomatis</h3>
                        <p class="feature-description">
                            Orang tua mendapat notifikasi real-time tentang kehadiran anak melalui WhatsApp atau SMS
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <h3 class="feature-title">Analisis Mendalam</h3>
                        <p class="feature-description">
                            Analisis pola kehadiran dengan grafik dan statistik untuk evaluasi performa akademik
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cloud"></i>
                        </div>
                        <h3 class="feature-title">Cloud Based</h3>
                        <p class="feature-description">
                            Akses data dari mana saja dengan sistem berbasis cloud yang reliable dan scalable
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loadingOverlay').classList.add('hidden');
            }, 1000);
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Counter animation
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            
            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start);
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target;
                }
            }
            updateCounter();
        }

        // Intersection Observer for counters
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Animate counters
                    const studentsCount = document.getElementById('studentsCount');
                    const teachersCount = document.getElementById('teachersCount');
                    
                    if (studentsCount && !studentsCount.classList.contains('animated')) {
                        studentsCount.classList.add('animated');
                        animateCounter(studentsCount, 850);
                    }
                    
                    if (teachersCount && !teachersCount.classList.contains('animated')) {
                        teachersCount.classList.add('animated');
                        animateCounter(teachersCount, 45);
                    }
                }
            });
        }, observerOptions);

        // Observe stats section
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Particle effect on mouse move
        document.addEventListener('mousemove', function(e) {
            if (Math.random() > 0.98) {
                createParticle(e.clientX, e.clientY);
            }
        });

        function createParticle(x, y) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                width: 4px;
                height: 4px;
                background: #f97316;
                border-radius: 50%;
                pointer-events: none;
                left: ${x}px;
                top: ${y}px;
                z-index: 1;
                animation: particleFloat 1s ease-out forwards;
            `;
            
            document.body.appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 1000);
        }

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
                header.style.boxShadow = '0 4px 30px rgba(15, 23, 42, 0.1)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.boxShadow = '0 4px 20px rgba(15, 23, 42, 0.05)';
            }
        });

        // Feature cards animation on scroll
        const featureCards = document.querySelectorAll('.feature-card');
        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        featureCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            cardObserver.observe(card);
        });
    </script>
</body>
</html>