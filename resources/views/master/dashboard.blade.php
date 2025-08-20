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
    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Statistik Hari Ini -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                                <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                                <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Kehadiran <span>| Hari Ini</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $hariIni['hadir'] }}/{{ $hariIni['total_siswa'] }}</h6>
                                    <span class="text-success small pt-1 fw-bold">{{ $hariIni['persentase_kehadiran'] }}%</span> 
                                    <span class="text-muted small pt-2 ps-1">kehadiran</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Terlambat <span>| Hari Ini</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $hariIni['terlambat'] }}</h6>
                                    <span class="text-danger small pt-1 fw-bold">
                                        {{ $hariIni['total_siswa'] > 0 ? round(($hariIni['terlambat'] / $hariIni['total_siswa']) * 100, 1) : 0 }}%
                                    </span> 
                                    <span class="text-muted small pt-2 ps-1">dari total siswa</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-xl-12">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Alpha <span>| Hari Ini</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-x"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $hariIni['alpha'] }}</h6>
                                    <span class="text-warning small pt-1 fw-bold">
                                        {{ $hariIni['total_siswa'] > 0 ? round(($hariIni['alpha'] / $hariIni['total_siswa']) * 100, 1) : 0 }}%
                                    </span> 
                                    <span class="text-muted small pt-2 ps-1">tidak hadir</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Kehadiran 7 Hari -->
                <div class="col-12">
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="#">7 Hari</a></li>
                                <li><a class="dropdown-item" href="#">30 Hari</a></li>
                                <li><a class="dropdown-item" href="#">90 Hari</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Grafik Kehadiran <span>/7 Hari Terakhir</span></h5>
                            <div id="reportsChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart Status Absensi -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Status Absensi <span>| Bulan Ini</span></h5>
                            <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
                        </div>
                    </div>
                </div>

                <!-- Siswa Terlambat Hari Ini -->
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                                <li><a class="dropdown-item" href="#">Kemarin</a></li>
                                <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Siswa Terlambat <span>| Hari Ini</span></h5>
                            <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">Jam</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswaTerlambat as $index => $absen)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $absen->siswa->nama }}</td>
                                        <td>{{ $absen->siswa->kelas->nama ?? '-' }}</td>
                                        <td>{{ Carbon\Carbon::parse($absen->jam)->format('H:i') }}</td>
                                        <td><span class="badge bg-warning">Terlambat</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada siswa terlambat hari ini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side columns -->
        <div class="col-lg-4">
            <!-- Statistik Per Kelas -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                        <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                        <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Kehadiran Per Kelas <span>| Hari Ini</span></h5>
                    @foreach($statistikKelas as $kelas)
                    <div class="activity">
                        <div class="activity-item d-flex">
                            <div class="activite-label">{{ $kelas->persentase }}%</div>
                            <i class='bi bi-circle-fill activity-badge 
                                @if($kelas->persentase >= 90) text-success
                                @elseif($kelas->persentase >= 70) text-warning
                                @else text-danger
                                @endif align-self-start'></i>
                            <div class="activity-content">
                                <strong>{{ $kelas->nama }}</strong><br>
                                <small class="text-muted">
                                    {{ $kelas->hadir }}/{{ $kelas->total_siswa }} siswa hadir
                                    @if($kelas->terlambat > 0)
                                        <br><span class="text-warning">{{ $kelas->terlambat }} terlambat</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Absensi Terbaru -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                        <li><a class="dropdown-item" href="#">Kemarin</a></li>
                        <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                    </ul>
                </div>
                <div class="card-body pb-0">
                    <h5 class="card-title">Absensi Terbaru <span>| Real Time</span></h5>
                    <div class="news">
                        @foreach($absensiTerbaru as $absen)
                        <div class="post-item clearfix">
                            <div class="post-content">
                                <h6>
                                    <span class="badge 
                                        @if($absen->status == 'hadir') bg-success
                                        @elseif($absen->status == 'terlambat') bg-warning
                                        @elseif($absen->status == 'pulang') bg-primary
                                        @elseif($absen->status == 'izin') bg-info
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($absen->status) }}
                                    </span>
                                    {{ $absen->siswa->nama }}
                                </h6>
                                <p class="mb-1">{{ $absen->siswa->kelas->nama ?? '-' }}</p>
                                <small class="text-muted">
                                    {{ Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }} - 
                                    {{ Carbon\Carbon::parse($absen->jam)->format('H:i') }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Website Traffic -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                        <li><a class="dropdown-item" href="#">3 Bulan</a></li>
                        <li><a class="dropdown-item" href="#">6 Bulan</a></li>
                    </ul>
                </div>
                <div class="card-body pb-0">
                    <h5 class="card-title">Performa Kelas <span>| Bulan Ini</span></h5>
                    <div id="budgetChart" style="min-height: 400px;" class="echart"></div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Chart Kehadiran 7 Hari
    new ApexCharts(document.querySelector("#reportsChart"), {
        series: [{
            name: 'Kehadiran',
            data: @json(array_column($chartKehadiran, 'hadir')),
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            },
        },
        markers: {
            size: 4
        },
        colors: ['#4154f1'],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.4,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: @json(array_column($chartKehadiran, 'tanggal')),
        },
        tooltip: {
            x: {
                format: 'dd/MM'
            },
        }
    }).render();

    // Chart Status Absensi (Donut)
    echarts.init(document.querySelector("#trafficChart")).setOption({
        tooltip: {
            trigger: 'item'
        },
        legend: {
            top: '5%',
            left: 'center'
        },
        series: [{
            name: 'Status Absensi',
            type: 'pie',
            radius: ['40%', '70%'],
            avoidLabelOverlap: false,
            label: {
                show: false,
                position: 'center'
            },
            emphasis: {
                label: {
                    show: true,
                    fontSize: '18',
                    fontWeight: 'bold'
                }
            },
            labelLine: {
                show: false
            },
            data: [
                { value: {{ $chartStatus['hadir'] ?? 0 }}, name: 'Hadir', itemStyle: { color: '#5cb85c' }},
                { value: {{ $chartStatus['terlambat'] ?? 0 }}, name: 'Terlambat', itemStyle: { color: '#f0ad4e' }},
                { value: {{ $chartStatus['izin'] ?? 0 }}, name: 'Izin', itemStyle: { color: '#5bc0de' }},
            ]
        }]
    });

    // Chart Performa Per Kelas (Bar)
    const performaData = @json($chartPerformaKelas);
    echarts.init(document.querySelector("#budgetChart")).setOption({
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        legend: {
            data: ['Hadir', 'Terlambat']
        },
        toolbox: {
            show: true,
            feature: {
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar'] },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: [{
            type: 'category',
            boundaryGap: false,
            data: performaData.map(item => item.nama)
        }],
        yAxis: [{
            type: 'value'
        }],
        series: [{
            name: 'Hadir',
            type: 'line',
            stack: 'Total',
            areaStyle: {},
            emphasis: {
                focus: 'series'
            },
            data: performaData.map(item => item.hadir)
        }, {
            name: 'Terlambat',
            type: 'line',
            stack: 'Total',
            areaStyle: {},
            emphasis: {
                focus: 'series'
            },
            data: performaData.map(item => item.terlambat)
        }]
    });
});

// Auto refresh setiap 30 detik
setInterval(function() {
    location.reload();
}, 30000);
</script>
@endpush