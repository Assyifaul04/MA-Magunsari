@extends('layouts.super')

@section('title', 'Super Admin Dashboard')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('superAdmin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card welcome-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="card-title mb-2">
                                    <i class="bi bi-sun text-warning me-2"></i>
                                    Selamat Datang, {{ Auth::user()->name }}!
                                </h4>
                                <p class="text-muted mb-0">
                                    Anda login sebagai Super Administrator. Kelola sistem dengan bijak dan hati-hati.
                                </p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ now()->format('l, d F Y - H:i') }}
                                </small>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="welcome-icon">
                                    <i class="bi bi-shield-check text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Total Users -->
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card users-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users <span>| Semua</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="totalUsers">{{ $stats['total_users'] }}</h6>
                                <span class="text-success small pt-1 fw-bold">
                                    {{ $stats['users_today'] }}
                                </span>
                                <span class="text-muted small pt-2 ps-1">hari ini</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Super Admins -->
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card superadmin-card">
                    <div class="card-body">
                        <h5 class="card-title">Super Admin <span>| Total</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-shield-fill-check"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['super_admins'] }}</h6>
                                <span class="text-danger small pt-1 fw-bold">Akses Penuh</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admins -->
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card admin-card">
                    <div class="card-body">
                        <h5 class="card-title">Admin <span>| Total</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-gear"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['admins'] }}</h6>
                                <span class="text-primary small pt-1 fw-bold">Kelola Sistem</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teachers -->
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card guru-card">
                    <div class="card-body">
                        <h5 class="card-title">Guru <span>| Total</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-mortarboard"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['gurus'] }}</h6>
                                <span class="text-success small pt-1 fw-bold">Pengajar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Activities -->
            <div class="col-12">
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
                            <li><a class="dropdown-item" href="#" onclick="filterActivity('today')">Hari Ini</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterActivity('week')">Minggu Ini</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterActivity('month')">Bulan Ini</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Aktivitas Terbaru <span id="activityPeriod">| Hari ini</span></h5>

                        <div class="activity">
                            @forelse($recentUsers as $user)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $user['created_diff'] }}</div>
                                    <i class="bi bi-circle-fill activity-badge 
                                        @if ($user['role'] == 'superAdmin') text-danger 
                                        @elseif($user['role'] == 'admin') text-primary 
                                        @else text-success @endif align-self-start"></i>
                                    <div class="activity-content">
                                        User baru <strong>{{ $user['name'] }}</strong> ditambahkan dengan role
                                        <span class="badge 
                                            @if ($user['role'] == 'superAdmin') bg-danger 
                                            @elseif($user['role'] == 'admin') bg-primary 
                                            @else bg-success @endif">
                                            {{ $user['role'] == 'superAdmin' ? 'Super Admin' : ($user['role'] == 'admin' ? 'Admin' : 'Guru') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle fs-3"></i>
                                    <p class="mt-2">Belum ada aktivitas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Siswa Rajin Kehadiran</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jumlah Hadir Tepat Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaRajin as $siswa)
                                <tr>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                                    <td>{{ $siswa->jumlah_hadir ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Siswa Terlambat</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jumlah Terlambat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaBermasalah as $siswa)
                                <tr>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                                    <td>{{ $siswa->jumlah_terlambat ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Distribution Chart -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Distribusi User <span>| Berdasarkan Role</span></h5>
                        
                        <!-- Debug Info (Remove in production) -->
                        <div class="alert alert-info small mb-3">
                            <strong>Debug:</strong> 
                            Super Admin: {{ $stats['super_admins'] }}, 
                            Admin: {{ $stats['admins'] }}, 
                            Guru: {{ $stats['gurus'] }}
                        </div>

                        <div id="userDistributionChart" style="min-height: 400px;">
                            <!-- Chart will be rendered here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Menu Cepat</h5>

                        <div class="list-group list-group-flush">
                            <a href="{{ route('users.create') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-person-plus text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-1">Tambah User</h6>
                                    <small class="text-muted">Buat akun user baru</small>
                                </div>
                            </a>

                            <a href="{{ route('users.index') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-people text-success me-3"></i>
                                <div>
                                    <h6 class="mb-1">Kelola User</h6>
                                    <small class="text-muted">Edit, hapus user</small>
                                </div>
                            </a>

                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-gear text-warning me-3"></i>
                                <div>
                                    <h6 class="mb-1">Pengaturan Sistem</h6>
                                    <small class="text-muted">Konfigurasi aplikasi</small>
                                </div>
                            </a>

                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-graph-up text-info me-3"></i>
                                <div>
                                    <h6 class="mb-1">Laporan</h6>
                                    <small class="text-muted">Lihat statistik detail</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Status Sistem</h5>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="text-success">
                                        <i class="bi bi-check-circle fs-2"></i>
                                    </div>
                                    <h6 class="text-success mb-0">Online</h6>
                                    <small class="text-muted">Server</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-success">
                                    <i class="bi bi-database fs-2"></i>
                                </div>
                                <h6 class="text-success mb-0">Normal</h6>
                                <small class="text-muted">Database</small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                Uptime: {{ now()->diffForHumans(\Carbon\Carbon::now()->startOfDay()) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            box-shadow: 0 0.5rem 1.2rem rgba(189, 197, 209, 0.2);
        }

        .welcome-card .card-title {
            color: white;
            font-weight: 600;
        }

        .welcome-card .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .welcome-icon i {
            font-size: 3rem;
            opacity: 0.3;
        }

        .info-card {
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
            border: none;
        }

        .info-card h6 {
            font-size: 28px;
            color: #012970;
            font-weight: 700;
            margin: 0;
            padding: 0;
        }

        .card-icon {
            font-size: 32px;
            line-height: 0;
            width: 64px;
            height: 64px;
            flex-shrink: 0;
            flex-grow: 0;
        }

        .users-card .card-icon {
            color: #4154f1;
            background: #f6f6fe;
        }

        .superadmin-card .card-icon {
            color: #ff771d;
            background: #ffecdf;
        }

        .admin-card .card-icon {
            color: #2eca6a;
            background: #e0f8e9;
        }

        .guru-card .card-icon {
            color: #ff5828;
            background: #ffe7e1;
        }

        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activite-label {
            color: #888;
            position: relative;
            flex-shrink: 0;
            flex-grow: 0;
            min-width: 64px;
            font-size: 12px;
        }

        .activity-badge {
            margin-top: 3px;
            z-index: 1;
            font-size: 11px;
            line-height: 0;
            border-radius: 50%;
            flex-shrink: 0;
            border: 3px solid #fff;
            flex-grow: 0;
            margin-left: 20px;
            margin-right: 20px;
        }

        .activity-content {
            padding-left: 10px;
            font-size: 14px;
            color: #777;
        }

        .list-group-item {
            border: none;
            border-radius: 0;
            padding: 12px 0;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .list-group-item h6 {
            color: #012970;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .card {
            border-radius: 10px;
        }

        .card-title {
            color: #012970;
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // User Distribution Chart dengan debugging
            const superAdminCount = {{ $stats['super_admins'] }};
            const adminCount = {{ $stats['admins'] }};
            const guruCount = {{ $stats['gurus'] }};
            
            console.log('Chart Data Debug:');
            console.log('Super Admin:', superAdminCount);
            console.log('Admin:', adminCount);
            console.log('Guru:', guruCount);
            console.log('Total:', superAdminCount + adminCount + guruCount);

            // Ubah kondisi untuk menampilkan chart meski ada data 0
            const totalUsers = superAdminCount + adminCount + guruCount;
            
            if (totalUsers > 0) {
                // Filter out roles with 0 count untuk chart yang lebih clean
                let series = [];
                let labels = [];
                let colors = [];
                
                if (superAdminCount > 0) {
                    series.push(superAdminCount);
                    labels.push('Super Admin');
                    colors.push('#ff771d');
                }
                
                if (adminCount > 0) {
                    series.push(adminCount);
                    labels.push('Admin');
                    colors.push('#4154f1');
                }
                
                if (guruCount > 0) {
                    series.push(guruCount);
                    labels.push('Guru');
                    colors.push('#2eca6a');
                }
                
                // Jika masih tidak ada data sama sekali, buat dummy data
                if (series.length === 0) {
                    series = [1];
                    labels = ['Belum ada user'];
                    colors = ['#e0e0e0'];
                }

                const options = {
                    series: series,
                    chart: {
                        height: 350,
                        type: 'donut', // Ubah ke donut untuk tampilan lebih modern
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800,
                        }
                    },
                    labels: labels,
                    colors: colors,
                    legend: {
                        position: 'bottom',
                        fontSize: '14px',
                        fontFamily: 'inherit'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '60%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Users',
                                        fontSize: '16px',
                                        fontWeight: 600,
                                        color: '#012970'
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val, opts) {
                            return opts.w.config.series[opts.seriesIndex]
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }],
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " user"
                            }
                        }
                    }
                };

                console.log('Rendering chart with options:', options);
                
                try {
                    const chart = new ApexCharts(document.querySelector("#userDistributionChart"), options);
                    chart.render();
                    console.log('Chart rendered successfully');
                } catch (error) {
                    console.error('Error rendering chart:', error);
                    document.querySelector("#userDistributionChart").innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-exclamation-triangle fs-3 text-warning"></i>
                            <p class="mt-2">Error loading chart</p>
                            <small>${error.message}</small>
                        </div>
                    `;
                }
            } else {
                document.querySelector("#userDistributionChart").innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-pie-chart fs-3"></i>
                        <p class="mt-2">Belum ada data user untuk ditampilkan</p>
                        <p class="small">Silakan tambahkan user terlebih dahulu</p>
                    </div>
                `;
            }
        });

        // Filter activity function
        function filterActivity(period) {
            const periodLabel = document.getElementById('activityPeriod');
            switch (period) {
                case 'today':
                    periodLabel.textContent = '| Hari ini';
                    break;
                case 'week':
                    periodLabel.textContent = '| Minggu ini';
                    break;
                case 'month':
                    periodLabel.textContent = '| Bulan ini';
                    break;
            }

            // AJAX call to get filtered activities
            fetch(`/api/recent-activities?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    // Update activity list
                    console.log('Filtered activities:', data);
                })
                .catch(error => console.log('Error fetching activities:', error));
        }

        // Auto refresh user count every 30 seconds
        setInterval(function() {
            fetch('/api/user-count')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalUsers').textContent = data.total;
                    console.log('User count updated:', data);
                })
                .catch(error => console.log('Error fetching user count:', error));
        }, 30000);
    </script>
@endpush