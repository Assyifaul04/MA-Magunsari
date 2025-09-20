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
    </div>

    <section class="section">
        {{-- Filter Section --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-funnel-fill me-2"></i>Filter Data
                        </h5>

                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label for="tahun" class="form-label">
                                    <i class="bi bi-calendar-range me-1"></i>Tahun
                                </label>
                                <select name="tahun" id="tahun" class="form-select" onchange="this.form.submit()">
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
                                <select name="bulan" id="bulan" class="form-select" onchange="this.form.submit()">
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
                                <select name="kelas" id="kelas" class="form-select" onchange="this.form.submit()">
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
                                    <select name="siswa" id="siswa" class="form-select" onchange="this.form.submit()">
                                        <option value="">-- Pilih Siswa --</option>
                                        @foreach ($siswaKelas as $s)
                                            <option value="{{ $s->id }}"
                                                {{ (string) $siswaId === (string) $s->id ? 'selected' : '' }}>
                                                {{ $s->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="siswa" id="siswa" class="form-select" disabled>
                                        <option value="">-- Pilih Kelas Dahulu --</option>
                                    </select>
                                @endif
                            </div>

                            <div class="col-12">
                                <a href="{{ request()->url() }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                                </a>

                                @if ($siswaId && $jumlahHari)
                                    <a href="{{ route('rekap.export', [
                                        'tahun' => $tahun,
                                        'bulan' => $bulan,
                                        'kelas' => $kelas,
                                        'siswa' => $siswaId,
                                    ]) }}"
                                        class="btn btn-success">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                    </a>
                                @endif
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
                    $firstDayOfWeek = $start->dayOfWeek;
                    $weeks = (int) ceil(($jumlahHari + $firstDayOfWeek) / 7);

                    $totalHadir = collect($row['data'])->filter(fn($status) => $status === '✔')->count();
                    $totalIzin = collect($row['data'])->filter(fn($status) => $status === 'I')->count();
                    $totalSakit = collect($row['data'])->filter(fn($status) => $status === 'S')->count();
                    $totaltidakmasuk = collect($row['data'])->filter(fn($status) => $status === '-')->count();
                    $totalKehadiran = $totalHadir + $totalIzin + $totalSakit + $totaltidakmasuk;
                    $persentaseHadir = $totalKehadiran > 0 ? round(($totalHadir / $totalKehadiran) * 100, 1) : 0;
                @endphp

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- Header siswa --}}
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h5 class="card-title mb-1">
                                            <i class="bi bi-person-circle me-2"></i>{{ $row['siswa']->nama }}
                                        </h5>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-people-fill me-1"></i>{{ $row['siswa']->kelas->nama ?? '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $start->translatedFormat('F Y') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Statistik --}}
                                <div class="row mb-4">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card bg-success text-white text-center">
                                            <div class="card-body py-3">
                                                <i class="bi bi-check-circle-fill fs-3 mb-2"></i>
                                                <h4 class="mb-0">{{ $totalHadir }}</h4>
                                                <small>Hadir ({{ $persentaseHadir }}%)</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card bg-warning text-white text-center">
                                            <div class="card-body py-3">
                                                <i class="bi bi-info-circle-fill fs-3 mb-2"></i>
                                                <h4 class="mb-0">{{ $totalIzin }}</h4>
                                                <small>Izin
                                                    ({{ $totalKehadiran > 0 ? round(($totalIzin / $totalKehadiran) * 100, 1) : 0 }}%)</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card bg-info text-white text-center">
                                            <div class="card-body py-3">
                                                <i class="bi bi-heart-pulse-fill fs-3 mb-2"></i>
                                                <h4 class="mb-0">{{ $totalSakit }}</h4>
                                                <small>Sakit
                                                    ({{ $totalKehadiran > 0 ? round(($totalSakit / $totalKehadiran) * 100, 1) : 0 }}%)</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card bg-danger text-white text-center">
                                            <div class="card-body py-3">
                                                <i class="bi bi-x-circle-fill fs-3 mb-2"></i>
                                                <h4 class="mb-0">{{ $totaltidakmasuk }}</h4>
                                                <small>Tidak Masuk
                                                    ({{ $totalKehadiran > 0 ? round(($totaltidakmasuk / $totalKehadiran) * 100, 1) : 0 }}%)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Calendar Table --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered calendar-table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th class="text-center">Minggu</th>
                                                <th class="text-center">Senin</th>
                                                <th class="text-center">Selasa</th>
                                                <th class="text-center">Rabu</th>
                                                <th class="text-center">Kamis</th>
                                                <th class="text-center">Jumat</th>
                                                <th class="text-center">Sabtu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($w = 0; $w < $weeks; $w++)
                                                <tr>
                                                    @for ($dow = 0; $dow < 7; $dow++)
                                                        @php
                                                            $cellDay = $w * 7 + $dow - $firstDayOfWeek + 1;
                                                        @endphp

                                                        @if ($cellDay >= 1 && $cellDay <= $jumlahHari)
                                                            @php
                                                                $status = $row['data'][$cellDay] ?? null;
                                                                $class = 'text-center calendar-cell';
                                                                $icon = '';
                                                                $statusText = '';
                                                                $bgColor = '';

                                                                if ($status === '✔') {
                                                                    $bgColor = 'table-success';
                                                                    $icon =
                                                                        '<i class="bi bi-check-circle-fill text-success"></i>';
                                                                    $statusText = 'Hadir';
                                                                } elseif ($status === 'I') {
                                                                    $bgColor = 'table-warning';
                                                                    $icon =
                                                                        '<i class="bi bi-info-circle-fill text-warning"></i>';
                                                                    $statusText = 'Izin';
                                                                } elseif ($status === 'S') {
                                                                    $bgColor = 'table-info';
                                                                    $icon =
                                                                        '<i class="bi bi-heart-pulse-fill text-info"></i>';
                                                                    $statusText = 'Sakit';
                                                                } elseif ($status === '-') {
                                                                    $bgColor = 'table-danger';
                                                                    $icon =
                                                                        '<i class="bi bi-x-circle-fill text-danger"></i>';
                                                                    $statusText = 'Tidak Masuk';
                                                                } else {
                                                                    $bgColor = '';
                                                                    $statusText = 'Belum ada data';
                                                                }
                                                            @endphp
                                                            <td class="{{ $class }} {{ $bgColor }}"
                                                                title="{{ $statusText }} - {{ $cellDay }} {{ $start->translatedFormat('F Y') }}">
                                                                <div class="py-2">
                                                                    <div class="fw-bold">{{ $cellDay }}</div>
                                                                    @if ($status)
                                                                        <div class="mt-1">{!! $icon !!}</div>
                                                                        <small
                                                                            class="d-block mt-1">{{ $statusText }}</small>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td class="text-center calendar-cell table-light"></td>
                                                        @endif
                                                    @endfor
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Legend --}}
                                <div class="mt-4 p-3 bg-light rounded">
                                    <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Keterangan Status:</h6>
                                    <div class="row">
                                        <div class="col-md-3 col-6 mb-2">
                                            <span class="badge bg-success me-2">✔</span>
                                            <small><strong>Hadir</strong></small>
                                        </div>
                                        <div class="col-md-3 col-6 mb-2">
                                            <span class="badge bg-warning me-2">I</span>
                                            <small><strong>Izin</strong></small>
                                        </div>
                                        <div class="col-md-3 col-6 mb-2">
                                            <span class="badge bg-info me-2">S</span>
                                            <small><strong>Sakit</strong></small>
                                        </div>
                                        <div class="col-md-3 col-6 mb-2">
                                            <span class="badge bg-danger me-2">-</span>
                                            <small><strong>Tidak Masuk</strong></small>
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
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x display-1 text-muted mb-4"></i>
                                <h5 class="text-muted mb-3">Belum Ada Data Absensi</h5>
                                <p class="text-muted">
                                    Silakan pilih <strong>Tahun</strong>, <strong>Bulan</strong>, <strong>Kelas</strong>,
                                    dan <strong>Siswa</strong><br>
                                    untuk menampilkan rekap kalender absensi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <style>
        .calendar-table {
            table-layout: fixed;
        }

        .calendar-table th,
        .calendar-table td {
            width: 14.28%;
            height: 80px;
            vertical-align: middle;
        }

        .calendar-cell {
            position: relative;
            cursor: pointer;
        }

        .calendar-cell:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {

            .calendar-table th,
            .calendar-table td {
                height: 60px;
                font-size: 0.9rem;
            }
        }
    </style>
@endsection
