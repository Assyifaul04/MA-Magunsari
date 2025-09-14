@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Rekap Absensi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Absensi</li>
                <li class="breadcrumb-item active">Rekap Bulanan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        {{-- Filter Section --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card filter-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-funnel-fill me-2"></i>Filter Data
                        </h5>

                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label for="tahun" class="form-label">
                                    <i class="bi bi-calendar-range me-1"></i>Tahun
                                </label>
                                <select name="tahun" id="tahun" class="form-select custom-select" onchange="this.form.submit()">
                                    <option value="">-- Pilih Tahun --</option>
                                    @for ($t = now()->year; $t >= 2020; $t--)
                                        <option value="{{ $t }}"
                                            {{ (string) $tahun === (string) $t ? 'selected' : '' }}>{{ $t }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="bulan" class="form-label">
                                    <i class="bi bi-calendar-month me-1"></i>Bulan
                                </label>
                                <select name="bulan" id="bulan" class="form-select custom-select" onchange="this.form.submit()">
                                    <option value="">-- Pilih Bulan --</option>
                                    @for ($b = 1; $b <= 12; $b++)
                                        <option value="{{ $b }}"
                                            {{ (string) $bulan === (string) $b ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::createFromDate(null, $b, 1)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="kelas" class="form-label">
                                    <i class="bi bi-people me-1"></i>Kelas
                                </label>
                                <select name="kelas" id="kelas" class="form-select custom-select" onchange="this.form.submit()">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelasList as $k)
                                        <option value="{{ $k->id }}"
                                            {{ (string) $kelas === (string) $k->id ? 'selected' : '' }}>
                                            {{ $k->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="siswa" class="form-label">
                                    <i class="bi bi-person me-1"></i>Siswa
                                </label>
                                @if ($kelas)
                                    @php
                                        $siswaKelas = \App\Models\Siswa::where('kelas_id', $kelas)->get();
                                    @endphp
                                    <select name="siswa" id="siswa" class="form-select custom-select" onchange="this.form.submit()">
                                        <option value="">-- Pilih Siswa --</option>
                                        @foreach ($siswaKelas as $s)
                                            <option value="{{ $s->id }}"
                                                {{ (string) $siswaId === (string) $s->id ? 'selected' : '' }}>
                                                {{ $s->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="siswa" id="siswa" class="form-select custom-select" disabled>
                                        <option value="">-- Pilih Kelas Dahulu --</option>
                                    </select>
                                @endif
                            </div>

                            <div class="col-12">
                                <a href="{{ request()->url() }}" class="btn btn-outline-primary btn-reset">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Calendar Section --}}
        @if ($siswaId && $jumlahHari)
            @foreach ($rekap as $row)
                @php
                    $start = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
                    $firstDayOfWeek = $start->dayOfWeek; // 0 = Minggu
                    $weeks = (int) ceil(($jumlahHari + $firstDayOfWeek) / 7);
                    
                    // Hitung statistik
                    $totalHadir = collect($row['data'])->filter(fn($status) => $status === '✔')->count();
                    $totalIzin = collect($row['data'])->filter(fn($status) => $status === 'I')->count();
                    $totalSakit = collect($row['data'])->filter(fn($status) => $status === 'S')->count();
                    $totaltidakmasuk = collect($row['data'])->filter(fn($status) => $status === '-')->count();
                    $totalKehadiran = $totalHadir + $totalIzin + $totalSakit + $totaltidakmasuk;
                    $persentaseHadir = $totalKehadiran > 0 ? round(($totalHadir / $totalKehadiran) * 100, 1) : 0;
                @endphp

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card calendar-card">
                            <div class="card-body">
                                {{-- Header dengan info siswa --}}
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="student-info">
                                        <h5 class="card-title mb-1">
                                            <i class="bi bi-person-circle me-2"></i>{{ $row['siswa']->nama }}
                                        </h5>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-people-fill me-1"></i>{{ $row['siswa']->kelas->nama ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="date-badge">
                                        <span class="badge bg-gradient-primary fs-6 px-3 py-2">
                                            <i class="bi bi-calendar3 me-2"></i>{{ $start->translatedFormat('F Y') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Statistik Ringkasan --}}
                                <div class="row mb-4">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="summary-card bg-light-success">
                                            <div class="summary-icon">
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            </div>
                                            <div class="summary-content">
                                                <h4 class="summary-number text-success">{{ $totalHadir }}</h4>
                                                <p class="summary-label">Hadir</p>
                                                <small class="text-success fw-bold">{{ $persentaseHadir }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="summary-card bg-light-warning">
                                            <div class="summary-icon">
                                                <i class="bi bi-info-circle-fill text-warning"></i>
                                            </div>
                                            <div class="summary-content">
                                                <h4 class="summary-number text-warning">{{ $totalIzin }}</h4>
                                                <p class="summary-label">Izin</p>
                                                <small class="text-muted">{{ $totalKehadiran > 0 ? round(($totalIzin / $totalKehadiran) * 100, 1) : 0 }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="summary-card bg-light-info">
                                            <div class="summary-icon">
                                                <i class="bi bi-heart-pulse-fill text-info"></i>
                                            </div>
                                            <div class="summary-content">
                                                <h4 class="summary-number text-info">{{ $totalSakit }}</h4>
                                                <p class="summary-label">Sakit</p>
                                                <small class="text-muted">{{ $totalKehadiran > 0 ? round(($totalSakit / $totalKehadiran) * 100, 1) : 0 }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="summary-card bg-light-danger">
                                            <div class="summary-icon">
                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                            </div>
                                            <div class="summary-content">
                                                <h4 class="summary-number text-danger">{{ $totaltidakmasuk }}</h4>
                                                <p class="summary-label">tidak masuk</p>
                                                <small class="text-muted">{{ $totalKehadiran > 0 ? round(($totaltidakmasuk / $totalKehadiran) * 100, 1) : 0 }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Calendar Table --}}
                                <div class="calendar-wrapper">
                                    <table class="calendar-table">
                                        <thead>
                                            <tr class="calendar-header">
                                                <th class="day-header minggu"><i class="bi bi-calendar-day me-1"></i>Minggu</th>
                                                <th class="day-header senin"><i class="bi bi-calendar-day me-1"></i>Senin</th>
                                                <th class="day-header selasa"><i class="bi bi-calendar-day me-1"></i>Selasa</th>
                                                <th class="day-header rabu"><i class="bi bi-calendar-day me-1"></i>Rabu</th>
                                                <th class="day-header kamis"><i class="bi bi-calendar-day me-1"></i>Kamis</th>
                                                <th class="day-header jumat"><i class="bi bi-calendar-day me-1"></i>Jumat</th>
                                                <th class="day-header sabtu"><i class="bi bi-calendar-day me-1"></i>Sabtu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($w = 0; $w < $weeks; $w++)
                                                <tr class="calendar-week">
                                                    @for ($dow = 0; $dow < 7; $dow++)
                                                        @php
                                                            $cellDay = $w * 7 + $dow - $firstDayOfWeek + 1;
                                                            $dayClasses = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
                                                            $dayClass = $dayClasses[$dow];
                                                        @endphp

                                                        @if ($cellDay >= 1 && $cellDay <= $jumlahHari)
                                                            @php
                                                                $status = $row['data'][$cellDay] ?? null;
                                                                $class = $dayClass;
                                                                $icon = '';
                                                                $statusText = '';
                                                                
                                                                if ($status === '✔') {
                                                                    $class .= ' status-hadir';
                                                                    $icon = '<i class="bi bi-check-circle-fill"></i>';
                                                                    $statusText = 'Hadir';
                                                                } elseif ($status === 'I') {
                                                                    $class .= ' status-izin';
                                                                    $icon = '<i class="bi bi-info-circle-fill"></i>';
                                                                    $statusText = 'Izin';
                                                                } elseif ($status === 'S') {
                                                                    $class .= ' status-sakit';
                                                                    $icon = '<i class="bi bi-heart-pulse-fill"></i>';
                                                                    $statusText = 'Sakit';
                                                                } elseif ($status === '-') {
                                                                    $class .= ' status-tidak masuk';
                                                                    $icon = '<i class="bi bi-x-circle-fill"></i>';
                                                                    $statusText = 'tidak masuk';
                                                                } else {
                                                                    $class .= ' status-kosong';
                                                                    $statusText = 'Belum ada data';
                                                                }
                                                            @endphp
                                                            <td class="calendar-cell {{ $class }}" title="{{ $statusText }} - {{ $cellDay }} {{ $start->translatedFormat('F Y') }}">
                                                                <div class="date-content">
                                                                    <div class="date-number">{{ $cellDay }}</div>
                                                                    @if($status)
                                                                        <div class="status-icon">{!! $icon !!}</div>
                                                                        <div class="status-text">{{ $statusText }}</div>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td class="calendar-cell other-month {{ $dayClass }}"></td>
                                                        @endif
                                                    @endfor
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Legend --}}
                                <div class="mt-4 p-4 legend-container">
                                    <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Keterangan Status:</h6>
                                    <div class="row">
                                        <div class="col-md-3 col-6 mb-2">
                                            <div class="legend-item">
                                                <span class="legend-box status-hadir me-2"></span>
                                                <span class="legend-text">
                                                    <i class="bi bi-check-circle text-success me-1"></i>
                                                    <strong>Hadir</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-2">
                                            <div class="legend-item">
                                                <span class="legend-box status-izin me-2"></span>
                                                <span class="legend-text">
                                                    <i class="bi bi-info-circle text-warning me-1"></i>
                                                    <strong>Izin</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-2">
                                            <div class="legend-item">
                                                <span class="legend-box status-sakit me-2"></span>
                                                <span class="legend-text">
                                                    <i class="bi bi-heart-pulse text-info me-1"></i>
                                                    <strong>Sakit</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-2">
                                            <div class="legend-item">
                                                <span class="legend-box status-tidak masuk me-2"></span>
                                                <span class="legend-text">
                                                    <i class="bi bi-x-circle text-danger me-1"></i>
                                                    <strong>tidak masuk</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col-lg-12">
                    <div class="card empty-state-card">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <div class="empty-illustration mb-4">
                                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-3">Belum Ada Data Absensi</h5>
                                <p class="text-muted mb-4">
                                    Silakan pilih <strong>Tahun</strong>, <strong>Bulan</strong>, <strong>Kelas</strong>, dan <strong>Siswa</strong><br>
                                    untuk menampilkan rekap kalender absensi.
                                </p>
                                <div class="empty-action">
                                    <i class="bi bi-arrow-up text-primary me-2"></i>
                                    <span class="text-primary">Gunakan filter di atas untuk memulai</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- Enhanced Custom Styles for Nice Admin --}}
    <style>
        :root {
            --primary-color: #012970;
            --primary-light: rgba(1, 41, 112, 0.1);
            --secondary-color: #899bbd;
            --success-color: #198754;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --danger-color: #dc3545;
            --light-gray: #f8f9fa;
            --border-color: #e9ecef;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        /* Enhanced Card Styles */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            background: white;
        }

        .filter-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9ff 100%);
            border: 1px solid rgba(1, 41, 112, 0.1);
        }

        .calendar-card {
            background: linear-gradient(135deg, #fff 0%, #fafbff 100%);
            border: 1px solid rgba(1, 41, 112, 0.05);
        }

        .empty-state-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            min-height: 400px;
        }

        /* Enhanced Form Controls */
        .custom-select {
            border: 2px solid transparent;
            background: rgba(1, 41, 112, 0.05);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .custom-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(1, 41, 112, 0.25);
            background: white;
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--primary-color) 0%, #013d8a 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        /* Summary Cards */
        .summary-card {
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            border: 1px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .summary-icon {
            margin-right: 1rem;
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .summary-content {
            flex: 1;
        }

        .summary-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            line-height: 1;
        }

        .summary-label {
            margin-bottom: 0.25rem;
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .bg-light-success {
            background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(25, 135, 84, 0.05) 100%) !important;
            border-color: rgba(25, 135, 84, 0.2) !important;
        }

        .bg-light-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%) !important;
            border-color: rgba(255, 193, 7, 0.2) !important;
        }

        .bg-light-info {
            background: linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.05) 100%) !important;
            border-color: rgba(13, 202, 240, 0.2) !important;
        }

        .bg-light-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%) !important;
            border-color: rgba(220, 53, 69, 0.2) !important;
        }

        /* Enhanced Calendar Styles */
        .calendar-wrapper {
            overflow-x: auto;
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            background: white;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            min-width: 800px;
        }

        /* Header styling dengan warna samar untuk setiap hari */
        .calendar-header th {
            padding: 1.25rem 0.75rem;
            font-weight: 700;
            text-align: center;
            font-size: 0.9rem;
            color: white;
            border: none;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .day-header.minggu { background: linear-gradient(135deg, #535354 0%, #4e6383 100%); }
        .day-header.senin { background: linear-gradient(135deg, #535354 0%, #4e6383 100%); }
        .day-header.selasa { background: linear-gradient(135deg, #535354 0%, #4e6383 100%); }
        .day-header.rabu { background: linear-gradient(135deg, #535354 0%, #4e6383 100%); }
        .day-header.kamis { background: linear-gradient(135deg, #535354 0%, #4e6383 100%); }
        .day-header.jumat { background: linear-gradient(135deg, #535354 0%, #4e6383 100%); }
        .day-header.sabtu { background: linear-gradient(135deg, #535354 0%, #4e6383 100%); }

        .calendar-header th:first-child { border-top-left-radius: 1rem; }
        .calendar-header th:last-child { border-top-right-radius: 1rem; }

        /* Cell styling dengan warna samar sesuai hari */
        .calendar-cell {
            height: 100px;
            vertical-align: top;
            padding: 0;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }

        /* Warna latar belakang samar untuk setiap hari */
        .calendar-cell.minggu:not(.status-hadir):not(.status-izin):not(.status-sakit):not(.status-tidak masuk) {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.03) 0%, rgba(220, 53, 69, 0.01) 100%);
        }

        .calendar-cell.senin:not(.status-hadir):not(.status-izin):not(.status-sakit):not(.status-tidak masuk) {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.03) 0%, rgba(13, 110, 253, 0.01) 100%);
        }

        .calendar-cell.selasa:not(.status-hadir):not(.status-izin):not(.status-sakit):not(.status-tidak masuk) {
            background: linear-gradient(135deg, rgba(25, 135, 84, 0.03) 0%, rgba(25, 135, 84, 0.01) 100%);
        }

        .calendar-cell.rabu:not(.status-hadir):not(.status-izin):not(.status-sakit):not(.status-tidak masuk) {
            background: linear-gradient(135deg, rgba(253, 126, 20, 0.03) 0%, rgba(253, 126, 20, 0.01) 100%);
        }

        .calendar-cell.kamis:not(.status-hadir):not(.status-izin):not(.status-sakit):not(.status-tidak masuk) {
            background: linear-gradient(135deg, rgba(111, 66, 193, 0.03) 0%, rgba(111, 66, 193, 0.01) 100%);
        }

        .calendar-cell.jumat:not(.status-hadir):not(.status-izin):not(.status-sakit):not(.status-tidak masuk) {
            background: linear-gradient(135deg, rgba(13, 202, 240, 0.03) 0%, rgba(13, 202, 240, 0.01) 100%);
        }

        .calendar-cell.sabtu:not(.status-hadir):not(.status-izin):not(.status-sakit):not(.status-tidak masuk) {
            background: linear-gradient(135deg, rgba(108, 117, 125, 0.03) 0%, rgba(108, 117, 125, 0.01) 100%);
        }

        .calendar-cell:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            z-index: 10;
            border-radius: 0.5rem;
        }

        .date-content {
            padding: 0.75rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .date-number {
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .status-icon {
            font-size: 1.5rem;
            margin: 0.25rem 0;
        }

        .status-text {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Enhanced Status Colors */
        .status-hadir {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%) !important;
            color: white !important;
        }

        .status-hadir .date-number,
        .status-hadir .status-text {
            color: white !important;
        }

        .status-izin {
            background: linear-gradient(135deg, var(--warning-color) 0%, #ffca2c 100%) !important;
            color: white !important;
        }

        .status-izin .date-number,
        .status-izin .status-text {
            color: white !important;
        }

        .status-sakit {
            background: linear-gradient(135deg, var(--info-color) 0%, #31d2f2 100%) !important;
            color: white !important;
        }

        .status-sakit .date-number,
        .status-sakit .status-text {
            color: white !important;
        }

        .status-tidak masuk {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%) !important;
            border: 2px dashed var(--danger-color) !important;
            color: var(--danger-color) !important;
        }

        .status-tidak masuk .date-number,
        .status-tidak masuk .status-text {
            color: var(--danger-color) !important;
        }

        .status-kosong {
            background: linear-gradient(135deg, rgba(108, 117, 125, 0.05) 0%, rgba(108, 117, 125, 0.02) 100%) !important;
            border: 1px dashed rgba(108, 117, 125, 0.3) !important;
        }

        .other-month {
            background: rgba(248, 249, 250, 0.5) !important;
            opacity: 0.3;
            pointer-events: none;
        }

        /* Enhanced Legend Styles */
        .legend-container {
            background: linear-gradient(135deg, rgba(1, 41, 112, 0.02) 0%, rgba(1, 41, 112, 0.01) 100%);
            border: 1px solid rgba(1, 41, 112, 0.1);
            border-radius: 1rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .legend-item:hover {
            background: rgba(1, 41, 112, 0.05);
            transform: translateX(5px);
        }

        .legend-box {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 0.375rem;
            border: 2px solid transparent;
            vertical-align: middle;
        }

        .legend-box.status-hadir {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            border-color: rgba(25, 135, 84, 0.3);
        }

        .legend-box.status-izin {
            background: linear-gradient(135deg, var(--warning-color) 0%, #ffca2c 100%);
            border-color: rgba(255, 193, 7, 0.3);
        }

        .legend-box.status-sakit {
            background: linear-gradient(135deg, var(--info-color) 0%, #31d2f2 100%);
            border-color: rgba(13, 202, 240, 0.3);
        }

        .legend-box.status-tidak masuk {
            background: rgba(220, 53, 69, 0.1);
            border: 2px dashed rgba(220, 53, 69, 0.5);
        }

        .legend-text {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Student Info Enhancement */
        .student-info {
            position: relative;
        }

        .student-info::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #013d8a 100%);
            border-radius: 2px;
        }

        /* Date Badge Enhancement */
        .date-badge .badge {
            background: linear-gradient(135deg, var(--primary-color) 0%, #013d8a 100%) !important;
            border: none;
            box-shadow: 0 4px 15px rgba(1, 41, 112, 0.3);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #013d8a 100%) !important;
        }

        /* Empty State Enhancement */
        .empty-illustration {
            position: relative;
        }

        .empty-illustration::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(1, 41, 112, 0.1) 0%, rgba(1, 41, 112, 0.05) 100%);
            border-radius: 50%;
            z-index: -1;
        }

        .empty-action {
            padding: 1rem;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.05) 100%);
            border: 1px solid rgba(13, 110, 253, 0.2);
            border-radius: 0.5rem;
            display: inline-block;
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .calendar-table {
                min-width: 600px;
            }

            .calendar-cell {
                height: 80px;
            }

            .date-content {
                padding: 0.5rem;
            }

            .date-number {
                font-size: 1rem;
            }

            .status-icon {
                font-size: 1.2rem;
            }

            .status-text {
                font-size: 0.65rem;
            }

            .summary-card {
                padding: 1rem;
            }

            .summary-number {
                font-size: 1.5rem;
            }

            .summary-icon {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .calendar-table {
                min-width: 500px;
            }

            .calendar-header th {
                padding: 1rem 0.5rem;
                font-size: 0.8rem;
            }

            .calendar-cell {
                height: 70px;
            }

            .date-number {
                font-size: 0.9rem;
            }

            .status-icon {
                font-size: 1rem;
            }

            .summary-card {
                flex-direction: column;
                text-align: center;
            }

            .summary-icon {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
        }

        /* Animation Enhancements */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .calendar-card {
            animation: slideInUp 0.6s ease-out;
        }

        .summary-card {
            animation: fadeIn 0.8s ease-out;
        }

        .calendar-cell:hover .status-icon {
            animation: pulse 0.6s ease-in-out;
        }

        /* Tooltip Enhancement */
        .calendar-cell {
            position: relative;
        }

        .calendar-cell::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .calendar-cell::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(5px);
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.9);
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .calendar-cell:hover::after,
        .calendar-cell:hover::before {
            opacity: 1;
        }

        /* Print Styles */
        @media print {
            .filter-card,
            .legend-container {
                display: none !important;
            }

            .calendar-card {
                box-shadow: none !important;
                border: 1px solid #000 !important;
            }

            .calendar-table {
                border: 1px solid #000 !important;
            }

            .calendar-cell {
                border: 1px solid #000 !important;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            :root {
                --primary-color: #4dabf7;
                --primary-light: rgba(77, 171, 247, 0.1);
                --secondary-color: #adb5bd;
                --border-color: #495057;
                --light-gray: #212529;
            }

            .card {
                background: #343a40 !important;
                color: #fff;
            }

            .custom-select {
                background: rgba(77, 171, 247, 0.1) !important;
                color: #fff;
                border-color: rgba(77, 171, 247, 0.2);
            }

            .calendar-cell {
                border-color: rgba(255, 255, 255, 0.1) !important;
            }

            .date-number {
                color: #4dabf7 !important;
            }
        }

        /* Additional Utilities */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, #013d8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .border-gradient {
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box, 
                        linear-gradient(135deg, var(--primary-color), #013d8a) border-box;
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(1, 41, 112, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
@endsection