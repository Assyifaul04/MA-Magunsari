@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    <div class="pagetitle">
        <h1 class="fw-bold text-primary">Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row g-4">

            <!-- Stats Cards -->
            <div class="col-12">
                <div class="row g-3 g-md-4">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="card info-card sales-card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-muted mb-3">Total Siswa</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary">
                                        <i class="bi bi-people text-white fs-4"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h3 class="fw-bold text-dark mb-1">{{ $totalSiswa }}</h3>
                                        <span class="text-success small fw-semibold">{{ $siswaAktif }} aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="card info-card revenue-card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-muted mb-3">Total Kelas</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success">
                                        <i class="bi bi-house-door text-white fs-4"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h3 class="fw-bold text-dark mb-1">{{ $totalKelas }}</h3>
                                        <span class="text-muted small">kelas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="card info-card customers-card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-muted mb-3">Absensi Hari Ini</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning">
                                        <i class="bi bi-calendar-check text-white fs-4"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h3 class="fw-bold text-dark mb-1">{{ $absensiHariIni }}</h3>
                                        <span class="text-muted small">entri</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="card info-card status-card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-muted mb-3">Status Waktu</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info">
                                        <i class="bi bi-clock text-white fs-4"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="text-primary fw-bold mb-0">{{ $jenisAbsensi }}</h6>
                                        <small class="text-muted">{{ $statusWaktu }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="col-lg-8">
                <div class="row g-4">

                    <!-- Chart: 7 Hari Terakhir -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0 fw-bold">Statistik Absensi</h5>
                                </div>
                                <div id="reportsChart" class="chart-container"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart: Masuk vs Pulang -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3 fw-bold">Absensi Masuk vs Pulang</h5>
                                <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <div class="row g-4">

                    <!-- Status Absensi Hari Ini -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4 fw-bold">Status Absensi Hari Ini</h5>
                                <div id="budgetChart" style="min-height: 300px;" class="echart"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Absensi Per Kelas -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4 fw-bold">Absensi Per Kelas <span class="text-muted fw-normal">|
                                        Hari Ini</span></h5>
                                <div class="absensi-kelas-list" style="max-height: 250px; overflow-y: auto;">
                                    @foreach ($absensiPerKelas as $kelas)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $kelas['nama'] }}</h6>
                                                <small
                                                    class="text-muted">{{ $kelas['hadir'] }}/{{ $kelas['total_siswa'] }}</small>
                                            </div>
                                            <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $kelas['persentase'] >= 80 ? 'success' : ($kelas['persentase'] >= 60 ? 'warning' : 'danger') }}"
                                                    style="width: {{ $kelas['persentase'] }}%"></div>
                                            </div>
                                            <span
                                                class="badge bg-{{ $kelas['persentase'] >= 80 ? 'success' : ($kelas['persentase'] >= 60 ? 'warning' : 'danger') }} px-2 py-1">
                                                {{ $kelas['persentase'] }}%
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Siswa Sering Terlambat -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4 fw-bold">Siswa Terlambat</h5>
                                <div class="activity">
                                    @forelse($siswaSeringTerlambat as $siswa)
                                        <div class="activity-item d-flex align-items-center mb-3">
                                            <i class="bi bi-clock-history text-warning me-3"></i>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $siswa->nama }}</div>
                                                <small
                                                    class="text-muted">{{ $siswa->kelas->nama ?? 'Kelas tidak tersedia' }}</small>
                                            </div>
                                            <span
                                                class="badge bg-warning text-dark px-2 py-1">{{ $siswa->terlambat_count }}x</span>
                                        </div>
                                    @empty
                                        <div class="text-center text-muted py-3">
                                            <i class="bi bi-emoji-smile fs-2 text-success"></i>
                                            <p class="mb-0 mt-2">Tidak ada keterlambatan</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Styles -->
    <style>
        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.3rem;
        }

        .info-card .card-icon {
            background: #f8f9fa;
            color: #495057;
        }

        .info-card.sales-card .card-icon {
            background: #d1ecf1;
            color: #0c5460;
        }

        .info-card.revenue-card .card-icon {
            background: #d4edda;
            color: #155724;
        }

        .info-card.customers-card .card-icon {
            background: #fff3cd;
            color: #856404;
        }

        .info-card.status-card .card-icon {
            background: #d1ecf1;
            color: #0c5460;
        }

        .chart-container {
            min-height: 350px;
        }

        .progress {
            border-radius: 10px;
            height: 8px !important;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        .activity-item i {
            font-size: 1.2rem;
        }

        .list-group-item {
            padding: 0.7rem 0;
            border: none;
            border-bottom: 1px dashed #eee;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .absensi-kelas-list::-webkit-scrollbar {
            width: 6px;
        }

        .absensi-kelas-list::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 10px;
        }

        .absensi-kelas-list::-webkit-scrollbar-thumb:hover {
            background: #6c757d;
        }

        @media (max-width: 576px) {
            .card-title {
                font-size: 1.1rem;
            }

            .info-card h3 {
                font-size: 1.6rem;
            }
        }
    </style>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // === Data dari Blade ===
            const absensi7Hari = @json($absensi7Hari);
            const absensiStatus = @json($absensiHariIniStatus);
            const absensiMingguIni = @json($absensiMingguIni);

            // === Chart 1: 7 Hari Terakhir (ApexCharts) ===
            let chart1 = new ApexCharts(document.querySelector("#reportsChart"), {
                series: [{
                    name: 'Hadir',
                    data: absensi7Hari.map(item => item.hadir),
                    color: '#28a745'
                }, {
                    name: 'Terlambat',
                    data: absensi7Hari.map(item => item.terlambat),
                    color: '#ffc107'
                }, {
                    name: 'Izin',
                    data: absensi7Hari.map(item => item.izin),
                    color: '#17a2b8'
                }, {
                    name: 'Pulang',
                    data: absensi7Hari.map(item => item.pulang),
                    color: '#6c757d'
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.4,
                        opacityTo: 0.1
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: absensi7Hari.map(item => item.tanggal),
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                tooltip: {
                    theme: 'dark'
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    strokeDashArray: 3
                }
            });
            chart1.render();

            // === Chart 2: Status Absensi (Pie) ===
            echarts.init(document.querySelector("#budgetChart")).setOption({
                tooltip: {
                    trigger: 'item',
                    formatter: '{b}: {c} ({d}%)'
                },
                legend: {
                    top: '5%',
                    left: 'center',
                    textStyle: {
                        fontSize: 12
                    }
                },
                series: [{
                    name: 'Status',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: 16,
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [{
                            value: absensiStatus.hadir || 0,
                            name: 'Hadir',
                            itemStyle: {
                                color: '#28a745'
                            }
                        },
                        {
                            value: absensiStatus.terlambat || 0,
                            name: 'Terlambat',
                            itemStyle: {
                                color: '#ffc107'
                            }
                        },
                        {
                            value: absensiStatus.izin || 0,
                            name: 'Izin',
                            itemStyle: {
                                color: '#17a2b8'
                            }
                        },
                        {
                            value: absensiStatus.pulang || 0,
                            name: 'Pulang',
                            itemStyle: {
                                color: '#6c757d'
                            }
                        }
                    ]
                }]
            });

            // === Chart 3: Masuk vs Pulang (Line) ===
            echarts.init(document.querySelector("#trafficChart")).setOption({
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Masuk', 'Pulang'],
                    top: '5%'
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '10%',
                    containLabel: true
                },
                xAxis: [{
                    type: 'category',
                    data: absensiMingguIni.map(item => item.hari),
                    axisLabel: {
                        rotate: 20
                    }
                }],
                yAxis: [{
                    type: 'value'
                }],
                series: [{
                        name: 'Masuk',
                        type: 'line',
                        smooth: true,
                        data: absensiMingguIni.map(item => item.masuk),
                        itemStyle: {
                            color: '#007bff'
                        },
                        areaStyle: {
                            color: 'rgba(0, 123, 255, 0.2)'
                        }
                    },
                    {
                        name: 'Pulang',
                        type: 'line',
                        smooth: true,
                        data: absensiMingguIni.map(item => item.pulang),
                        itemStyle: {
                            color: '#28a745'
                        },
                        areaStyle: {
                            color: 'rgba(40, 167, 69, 0.2)'
                        }
                    }
                ]
            });

            // === Real-time Clock ===
            function updateTime() {
                const now = new Date();
                document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID');
            }
            setInterval(updateTime, 1000);

            // === Filter Dummy (bisa dikembangkan untuk AJAX) ===
            document.querySelectorAll('.filter-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filter = this.getAttribute('data-filter');
                    console.log('Filter dipilih:', filter);
                    // Di sini bisa tambahkan logika AJAX untuk reload chart
                    alert(`Filter "${this.textContent}" dipilih. Implementasi AJAX diperlukan.`);
                });
            });
        });
    </script>
@endpush
