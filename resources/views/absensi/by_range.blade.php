@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Data Absensi By Range</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item">Absensi</li>
                <li class="breadcrumb-item active">By Range</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Filter Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-funnel text-primary"></i> Filter Data Absensi
                            </h5>
                            <div class="badge bg-primary rounded-pill">
                                {{ $absensi->count() }} Data Ditemukan
                            </div>
                        </div>

                        <form method="GET" action="{{ route('absensi.byRange') }}" id="filterForm">
                            <div class="row g-3">
                                <!-- Tanggal Filter -->
                                <div class="col-md-6 col-lg-3">
                                    <label for="tanggal_mulai" class="form-label">
                                        <i class="bi bi-calendar-event text-success"></i> Tanggal Mulai
                                    </label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                                        value="{{ request('tanggal_mulai') }}" max="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label for="tanggal_selesai" class="form-label">
                                        <i class="bi bi-calendar-check text-success"></i> Tanggal Selesai
                                    </label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                                        value="{{ request('tanggal_selesai') }}" max="{{ date('Y-m-d') }}">
                                </div>

                                <!-- Kelas Filter -->
                                <div class="col-md-6 col-lg-3">
                                    <label for="kelas" class="form-label">
                                        <i class="bi bi-building text-info"></i> Kelas
                                    </label>
                                    <select name="kelas" id="kelas" class="form-select">
                                        <option value="">Semua Kelas</option>
                                        @foreach (\App\Models\Kelas::all() as $kelas)
                                            <option value="{{ $kelas->id }}"
                                                {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Jenis Filter -->
                                <div class="col-md-6 col-lg-3">
                                    <label for="jenis" class="form-label">
                                        <i class="bi bi-tags text-warning"></i> Jenis Absensi
                                    </label>
                                    <select name="jenis" id="jenis" class="form-select">
                                        <option value="">Semua Jenis</option>
                                        <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>
                                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                                        </option>
                                        <option value="pulang" {{ request('jenis') == 'pulang' ? 'selected' : '' }}>
                                            <i class="bi bi-box-arrow-right"></i> Pulang
                                        </option>
                                        <option value="izin" {{ request('jenis') == 'izin' ? 'selected' : '' }}>
                                            <i class="bi bi-exclamation-circle"></i> Izin
                                        </option>
                                    </select>
                                </div>

                                <!-- Nama Filter -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="nama" class="form-label">
                                        <i class="bi bi-person-search text-secondary"></i> Nama Siswa
                                    </label>
                                    <input type="text" name="nama" id="nama" class="form-control"
                                        placeholder="Cari nama siswa..." value="{{ request('nama') }}">
                                </div>

                                <!-- Status Filter -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="status" class="form-label">
                                        <i class="bi bi-flag text-danger"></i> Status
                                    </label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>
                                            Hadir
                                        </option>
                                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>
                                            Terlambat
                                        </option>
                                        <option value="pulang" {{ request('status') == 'pulang' ? 'selected' : '' }}>
                                            Pulang
                                        </option>
                                        <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>
                                            Izin
                                        </option>
                                        <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>
                                            Sakit
                                        </option>
                                    </select>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-md-12 col-lg-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary btn-sm px-3">
                                            <i class="bi bi-search"></i> Filter
                                        </button>

                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                                            onclick="resetForm()">
                                            <i class="bi bi-arrow-clockwise"></i> Reset
                                        </button>

                                        <button type="button" id="btnExport" class="btn btn-success btn-sm px-3">
                                            <i class="bi bi-file-earmark-excel"></i> Excel
                                        </button>

                                        <button type="button" id="btnPrint" class="btn btn-info btn-sm px-3">
                                            <i class="bi bi-printer"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Data Table Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Absensi</h5>
                
                        @if (request()->hasAny(['tanggal_mulai', 'tanggal_selesai', 'kelas', 'jenis', 'nama', 'status']))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle me-1"></i>
                                Filter aktif: {{ $absensi->count() }} data ditemukan
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                
                        <!-- Responsive table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">RFID</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">Jenis</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($absensi as $i => $a)
                                        <tr>
                                            <th scope="row">{{ $i + 1 }}</th>
                                            <td>{{ $a->rfid }}</td>
                                            <td>{{ $a->siswa->nama }}</td>
                                            <td>
                                                @if ($a->siswa->kelas)
                                                    <span class="badge bg-secondary">{{ $a->siswa->kelas->nama }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($a->jenis == 'masuk')
                                                    <span class="badge bg-success">{{ ucfirst($a->jenis) }}</span>
                                                @elseif($a->jenis == 'pulang')
                                                    <span class="badge bg-warning text-dark">{{ ucfirst($a->jenis) }}</span>
                                                @else
                                                    <span class="badge bg-info">{{ ucfirst($a->jenis) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($a->status == 'hadir')
                                                    <span class="badge bg-success">{{ ucfirst($a->status) }}</span>
                                                @elseif($a->status == 'terlambat')
                                                    <span class="badge bg-warning text-dark">{{ ucfirst($a->status) }}</span>
                                                @elseif($a->status == 'izin')
                                                    <span class="badge bg-info">{{ ucfirst($a->status) }}</span>
                                                @elseif($a->status == 'sakit')
                                                    <span class="badge bg-danger">{{ ucfirst($a->status) }}</span>
                                                @else
                                                    <span class="badge bg-primary">{{ ucfirst($a->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $a->jam }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                    <br>
                                                    <strong>Tidak ada data absensi</strong>
                                                    <br>
                                                    <small>Silakan ubah filter atau periode tanggal untuk menampilkan data</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- End Responsive table -->
                
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Toast Container for Alerts -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer">
        <!-- Toasts will be inserted here -->
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .avatar-sm {
            font-size: 14px;
            font-weight: bold;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .badge {
            font-size: 0.75em;
        }

        code {
            font-size: 0.875em;
        }

        .toast-container .toast {
            min-width: 300px;
        }
    </style>
@endpush

@push('scripts')
<script>
    const exportRoute = "{{ route('absensi.export') }}";
    const printRoute = "{{ route('absensi.print') }}";
    const byRangeRoute = "{{ route('absensi.byRange') }}";

    @if (request()->hasAny(['tanggal_mulai', 'tanggal_selesai', 'kelas', 'jenis', 'nama', 'status']) && $absensi->count() > 0)
        var showSuccessToast = true;
        var successMessage = "Filter berhasil diterapkan. Ditemukan {{ $absensi->count() }} data.";
    @endif

    @if (request()->hasAny(['tanggal_mulai', 'tanggal_selesai', 'kelas', 'jenis', 'nama', 'status']) && $absensi->count() === 0)
        var showWarningToast = true;
        var warningMessage = "Tidak ada data yang sesuai dengan filter yang dipilih.";
    @endif
</script>
<script src="{{ asset('js/by-range.js') }}"></script>
@endpush

