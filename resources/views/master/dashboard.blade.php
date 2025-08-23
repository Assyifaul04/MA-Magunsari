@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row g-4">

        <!-- Left side columns -->
        <div class="col-lg-8">
            <div class="row g-4">

                <!-- Statistik Cards -->
                <div class="col-xxl-3 col-md-6 col-sm-6">
                    <div class="card info-card sales-card shadow-sm border-0">
                        <div class="card-body pt-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-container rounded-circle me-3 d-flex align-items-center justify-content-center"
                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #4e73df, #224abe); color: white;">
                                    <i class="bi bi-people fs-3"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted small">Total Siswa</p>
                                    <h5 class="mb-0 fw-bold" id="total-siswa">{{ $totalSiswa }}</h5>
                                    <small class="text-success fw-semibold"><i class="bi bi-arrow-up"></i> {{ $siswaAktif }} aktif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6">
                    <div class="card info-card revenue-card shadow-sm border-0">
                        <div class="card-body pt-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-container rounded-circle me-3 d-flex align-items-center justify-content-center"
                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #1cc88a, #17a673); color: white;">
                                    <i class="bi bi-check-circle fs-3"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted small">Hadir Hari Ini</p>
                                    <h5 class="mb-0 fw-bold" id="masuk-hari-ini">{{ $masukHariIni }}</h5>
                                    <small class="text-success fw-semibold"><i class="bi bi-percent"></i> {{ $persentaseHadir }}% hadir</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6">
                    <div class="card info-card customers-card shadow-sm border-0">
                        <div class="card-body pt-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-container rounded-circle me-3 d-flex align-items-center justify-content-center"
                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #f6c23e, #f4a100); color: white;">
                                    <i class="bi bi-clock fs-3"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted small">Terlambat</p>
                                    <h5 class="mb-0 fw-bold">{{ $terlambatHariIni }}</h5>
                                    <small class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle"></i> {{ $izinHariIni }} izin</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6">
                    <div class="card info-card shadow-sm border-0">
                        <div class="card-body pt-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-container rounded-circle me-3 d-flex align-items-center justify-content-center"
                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #4e73df, #224abe); color: white;">
                                    <i class="bi bi-box-arrow-right fs-3"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted small">Pulang</p>
                                    <h5 class="mb-0 fw-bold" id="pulang-hari-ini">{{ $pulangHariIni }}</h5>
                                    <small class="text-muted">siswa pulang</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Absensi -->
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li><h6 class="dropdown-header">Filter</h6></li>
                                <li><a class="dropdown-item" href="#" onclick="updateChart('week')">Minggu Ini</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateChart('month')">Bulan Ini</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Statistik Absensi <span class="text-muted">| 7 Hari Terakhir</span></h5>
                            <div id="reportsChart" class="chart-container" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Status Distribution -->
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Distribusi Status Absensi <span class="text-muted">| Minggu Ini</span></h5>
                            <div id="trafficChart" class="chart-container" style="min-height: 400px;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right side columns -->
        <div class="col-lg-4">

            <!-- Jam dan Pengaturan -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <h5 class="card-title fw-semibold mb-3">Waktu Saat Ini</h5>
                    <h2 id="current-time" class="text-primary fw-bold display-6">{{ now()->format('H:i:s') }}</h2>
                    <p class="text-muted fs-5">{{ now()->format('l, d F Y') }}</p>

                    @if($pengaturan)
                    <div class="row mt-4 g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Jam Masuk</small>
                            <div class="fw-bold text-dark">{{ $pengaturan->jam_masuk_awal }} - {{ $pengaturan->jam_masuk_akhir }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Pulang</small>
                            <div class="fw-bold text-dark">{{ $pengaturan->jam_pulang }}</div>
                        </div>
                        <div class="col-12 mt-2">
                            <small class="text-muted">Status</small>
                            <div class="badge bg-success bg-gradient px-3 py-2 w-100">Absensi Aktif</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Top Kelas -->
            <div class="card shadow-sm border-0">
                <div class="card-body pb-0">
                    <h5 class="card-title fw-semibold">Top Kelas <span class="text-muted">| Bulan Ini</span></h5>
                    <div class="mt-3">
                        @foreach($topKelas as $index => $kelas)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded-3"
                             style="background: #f8f9fa;">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary rounded-circle me-2" style="width: 28px; height: 28px;">{{ $index + 1 }}</span>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $kelas->nama }}</h6>
                                    <small class="text-muted">{{ $kelas->total_siswa }} siswa</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success">{{ $kelas->total_hadir }}</span>
                                <small class="d-block text-muted">hadir</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow-sm border-0">
                <div class="card-body pb-0">
                    <h5 class="card-title fw-semibold">Absensi Terbaru <span class="text-muted">| Hari Ini</span></h5>
                    <div class="activity mt-3" id="recent-activity">
                        @foreach($absensiTerbaru->take(8) as $absen)
                        <div class="activity-item d-flex mb-3">
                            <div class="activite-label fw-bold text-primary" style="min-width: 50px;">{{ Carbon\Carbon::parse($absen->jam)->format('H:i') }}</div>
                            <i class='bi bi-circle-fill activity-badge 
                                @if($absen->status == 'hadir') text-success
                                @elseif($absen->status == 'terlambat') text-warning  
                                @elseif($absen->status == 'pulang') text-info
                                @else text-secondary
                                @endif mx-2 align-self-center' style="font-size: 10px;"></i>
                            <div class="activity-content flex-grow-1">
                                <strong>{{ $absen->siswa->nama }}</strong>
                                <span class="text-muted ms-1">({{ $absen->siswa->kelas->nama ?? '-' }})</span>
                                <br>
                                <small class="text-muted">
                                    {{ ucfirst($absen->jenis) }} &bull;
                                    <span class="badge bg-soft 
                                        @if($absen->status == 'hadir') text-success bg-success-subtle
                                        @elseif($absen->status == 'terlambat') text-warning bg-warning-subtle  
                                        @elseif($absen->status == 'pulang') text-info bg-info-subtle
                                        @else text-secondary bg-secondary-subtle
                                        @endif
                                        rounded-pill px-2 py-1" style="font-size: 0.75em;">
                                        {{ ucfirst($absen->status) }}
                                    </span>
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // Real-time Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID');
        document.getElementById('current-time').textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock(); // Initial call

    // Chart Data from PHP
    const chartData = @json($chartData);
    const statusData = @json($statusMingguIni);

    // Line Chart - Absensi
    const chartOptions = {
        series: [{
            name: 'Masuk',
            data: chartData.map(item => item.masuk),
            color: '#4e73df'
        }, {
            name: 'Pulang',
            data: chartData.map(item => item.pulang),
            color: '#1cc88a'
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false },
            animations: { enabled: true }
        },
        fill: {
            type: 'gradient',
            gradient: { opacityFrom: 0.4, opacityTo: 0.1, shadeIntensity: 1 }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: chartData.map(item => item.day),
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: { tickAmount: 4 },
        tooltip: { x: { format: 'dd MMM' } },
        grid: { borderColor: '#e7eaf6', strokeDashArray: 5 }
    };

    const chart = new ApexCharts(document.querySelector("#reportsChart"), chartOptions);
    chart.render();

    // Pie Chart - Distribusi Status
    const trafficChart = echarts.init(document.querySelector("#trafficChart"));
    const trafficOption = {
        tooltip: { trigger: 'item', formatter: '{a} <br/>{b}: {c} ({d}%)' },
        legend: { top: '7%', left: 'center', textStyle: { fontSize: 12 } },
        series: [{
            name: 'Status Absensi',
            type: 'pie',
            radius: ['50%', '70%'],
            avoidLabelOverlap: false,
            label: { show: false },
            emphasis: {
                label: { show: true, fontSize: 16, fontWeight: 'bold' }
            },
            labelLine: { show: false },
            data: [
                { value: statusData.hadir, name: 'Hadir', itemStyle: { color: '#1cc88a' } },
                { value: statusData.terlambat, name: 'Terlambat', itemStyle: { color: '#f6c23e' } },
                { value: statusData.izin, name: 'Izin', itemStyle: { color: '#4e73df' } },
                { value: statusData.sakit, name: 'Sakit', itemStyle: { color: '#e74a3b' } }
            ]
        }]
    };
    trafficChart.setOption(trafficOption);

    // Real-time update
    function updateRealtimeData() {
        fetch('{{ route("dashboard.realtime") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('masuk-hari-ini').textContent = data.masuk_hari_ini;
                document.getElementById('pulang-hari-ini').textContent = data.pulang_hari_ini;
            })
            .catch(err => console.error('Realtime fetch error:', err));
    }
    setInterval(updateRealtimeData, 30000);

    // Responsive resize
    window.addEventListener('resize', () => {
        chart.update(); trafficChart.resize();
    });

    // Filter chart
    window.updateChart = function(period) {
        alert('Fitur filter: ' + period); // Ganti dengan logika fetch ke backend
    };
});
</script>

<style>
/* Modern Professional Styling */
.card {
    border-radius: 16px !important;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.card-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

/* Responsive Adjustments */
@media (max-width: 576px) {
    .card-body {
        padding: 1rem;
    }
    .icon-container {
        width: 48px !important;
        height: 48px !important;
        font-size: 1.2rem !important;
    }
    .display-6 {
        font-size: 2rem;
    }
    .activity-item {
        font-size: 0.85rem;
    }
    .col-sm-6 {
        flex: 0 0 50%;
            max-width: 50%;
    }
}

/* Badge Style */
.bg-soft {
    background-color: rgba(0,0,0,0.05);
}

/* Chart Responsive */
.chart-container {
    width: 100%;
    min-height: 300px;
}

/* Activity Timeline */
.activity-badge {
    font-size: 10px;
    min-width: 10px;
}
.activite-label {
    font-size: 0.8rem;
    color: #4e73df;
    font-weight: 600;
}
</style>
@endpush