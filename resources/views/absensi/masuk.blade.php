@extends('layouts.app')
@section('content')
    <!-- Page Title dengan Icon dan Breadcrumb -->
    <div class="pagetitle">
        <div class="d-flex align-items-center">
            <i class="bi bi-calendar-check me-2 text-primary fs-4"></i>
            <h1 class="mb-0">Absensi Masuk</h1>
        </div>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Absensi</li>
                <li class="breadcrumb-item active">Absensi Masuk</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Card dengan Header yang Menarik -->
                <div class="card shadow border-0">
                    <div class="card-header bg-gradient-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="bi bi-people-fill me-2"></i>Data Absensi masuk siswa
                                </h5>
                                <small class="opacity-75">Rekap absensi masuk siswa hari ini</small>
                            </div>
                            <div class="text-end">
                                <div class="badge bg-dark text-white fs-6 px-3 py-2">
                                    Total: {{ count($absensi) }} data
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Responsive Table Wrapper -->
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">
                                            <i class="bi bi-hash text-muted"></i>
                                        </th>
                                        <th class="fw-semibold">
                                            <i class="bi bi-person-fill text-primary me-1"></i>Nama Siswa
                                        </th>
                                        <th class="fw-semibold">
                                            <i class="bi bi-door-open text-info me-1"></i>Kelas
                                        </th>
                                        <th class="fw-semibold text-center">
                                            <i class="bi bi-calendar3 text-warning me-1"></i>Tanggal
                                        </th>
                                        <th class="fw-semibold text-center">
                                            <i class="bi bi-clock text-success me-1"></i>Jam
                                        </th>
                                        <th class="fw-semibold text-center">
                                            <i class="bi bi-bookmark text-secondary me-1"></i>Jenis
                                        </th>
                                        <th class="fw-semibold text-center">
                                            <i class="bi bi-check-circle text-success me-1"></i>Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($absensi as $i => $a)
                                        <tr class="border-bottom">
                                            <td class="text-center text-muted fw-medium">
                                                {{ $i + 1 }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-initial bg-light-primary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px; font-weight: 600;">
                                                        {{ strtoupper(substr($a->siswa->nama ?? 'N', 0, 1)) }}{{ strtoupper(substr($a->siswa->nama ?? 'A', 1, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark">{{ $a->siswa->nama ?? '-' }}</div>
                                                        <small class="text-muted">Siswa Aktif</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light-info text-info px-2 py-1">
                                                    {{ $a->siswa->kelas->nama ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-medium text-dark">{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($a->tanggal)->format('l') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-bold text-success">{{ $a->jam }}</div>
                                                <small class="text-muted">WIB</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $a->jenis == 'masuk' ? 'bg-primary' : 'bg-secondary' }} px-2 py-1">
                                                    <i class="bi bi-{{ $a->jenis == 'masuk' ? 'box-arrow-in-right' : 'box-arrow-right' }} me-1"></i>
                                                    {{ ucfirst($a->jenis) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $statusClass = match($a->status) {
                                                        'hadir' => 'bg-success',
                                                        'terlambat' => 'bg-warning',
                                                        'alpha' => 'bg-danger',
                                                        'izin' => 'bg-info',
                                                        'sakit' => 'bg-secondary',
                                                        default => 'bg-dark'
                                                    };
                                                    $statusIcon = match($a->status) {
                                                        'hadir' => 'check-circle-fill',
                                                        'terlambat' => 'clock-fill',
                                                        'alpha' => 'x-circle-fill',
                                                        'izin' => 'info-circle-fill',
                                                        'sakit' => 'heart-pulse-fill',
                                                        default => 'question-circle-fill'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }} px-2 py-1">
                                                    <i class="bi bi-{{ $statusIcon }} me-1"></i>
                                                    {{ ucfirst($a->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                                    <h5 class="text-muted">Tidak Ada Data</h5>
                                                    <p class="mb-0">Belum ada data absensi untuk ditampilkan</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #6f42c1 0%, #007bff 100%);
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .bg-light-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-header {
        border: none;
        padding: 1.5rem;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .avatar-initial {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.1), rgba(13, 110, 253, 0.2));
        border: 2px solid rgba(13, 110, 253, 0.1);
    }

    .table th {
        border-top: none;
        border-bottom: 2px solid #e9ecef;
        padding: 1rem 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    @media (max-width: 768px) {
        .card-header {
            padding: 1rem;
        }
        .card-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .card-header .text-end {
            margin-top: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
    <script src="{{ asset('js/absensi.js') }}"></script>
@endpush