<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi RFID</title>

    <!-- NiceAdmin CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --bs-font-sans-serif: "Nunito", sans-serif;
        }

        body {
            font-family: "Nunito", sans-serif;
            background: linear-gradient(135deg, #fafafb 0%, #f8f6f9 100%);
            min-height: 100vh;
        }

        .scan-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .rfid-image {
            max-width: 200px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .rfid-image:hover {
            transform: scale(1.05);
        }

        .progress-custom {
            height: 8px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 50px;
            overflow: hidden;
        }

        .progress-bar-custom {
            height: 100%;
            background: linear-gradient(90deg, #4154f1, #2c3cdd);
            border-radius: 50px;
            transition: width 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-bar-custom::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .rfid-input {
            border: 2px solid #e6e9ff;
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .rfid-input:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.25);
            background: white;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .jenis-badge {
            font-size: 1.5rem;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #4154f1, #2c3cdd);
            color: white;
            box-shadow: 0 5px 15px rgba(65, 84, 241, 0.4);
        }

        .instruction-text {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 500;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #4154f1;
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
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 p-4">
        <div class="scan-container p-5 text-center" style="max-width: 500px; width: 100%;">

            <!-- Header -->
            <div class="mb-4">
                <i class="bi bi-credit-card-2-front text-primary" style="font-size: 3rem;"></i>
                <h1 class="h3 mb-3 fw-bold text-dark">Sistem Absensi RFID</h1>
                <p class="mb-2">Status Absen:</p>
                <span class="jenis-badge" id="jenisAbsen">...</span>
            </div>

            <!-- Instruction -->
            <p class="instruction-text mb-4">
                <i class="bi bi-hand-index me-2"></i>
                Silahkan Tempelkan Kartu RFID Anda
            </p>

            <!-- RFID Image -->
            <div class="mb-4">
                <img src="{{ asset('image/RFID.jpeg') }}" alt="RFID Card" class="rfid-image img-fluid">
            </div>

            <!-- Progress Bar -->
            <div class="progress-custom mb-4" style="width: 200px; margin: 0 auto;">
                <div id="progressBar" class="progress-bar-custom" style="width: 0%;"></div>
            </div>

            <!-- RFID Form -->
            <form id="rfidForm" class="mb-4" autocomplete="off">
                <div class="position-relative">
                    <input type="text" name="rfid" id="rfidInput" placeholder="Tempelkan RFID Anda..."
                        class="form-control rfid-input">
                    <input type="hidden" name="jenis" id="jenisInput">
                    <div class="loading-spinner position-absolute"
                        style="right: 15px; top: 50%; transform: translateY(-50%);" id="loadingSpinner"></div>
                </div>
            </form>

            <!-- Status Message -->
            <div id="statusMessage" class="mt-3"></div>

            <!-- System Info -->
            <div class="mt-4 pt-3 border-top">
                <small class="text-muted">
                    <i class="bi bi-shield-check me-1"></i>
                    Sistem Keamanan Aktif •
                    <span id="currentTime"></span>
                </small>
            </div>
        </div>
    </div>

    <!-- Alert Container untuk Notifikasi -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055; min-width: 300px;">
        <div id="alertContainer"></div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Script -->
    <!-- Include external JS file if you prefer -->
    <script src="{{ asset('js/absensi-form.js') }}"></script>
</body>

</html>
