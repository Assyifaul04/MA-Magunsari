@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Pengaturan Jam</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Pengaturan</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-clock-history me-2"></i>
                        Konfigurasi Waktu Kehadiran Siswa
                    </h5>
                    
                    <!-- Informasi Status -->
                    @if ($sudahAdaMasuk)
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>
                            <strong>Informasi:</strong> Ada siswa yang sudah melakukan absen masuk hari ini. 
                            Pengaturan jam masuk tidak dapat diubah.
                        </div>
                    </div>
                    @endif

                    <form id="formPengaturan" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Jam Masuk Awal -->
                        <div class="row mb-3">
                            <label for="jam_masuk_awal" class="col-sm-3 col-form-label">
                                <i class="bi bi-sunrise me-1"></i>
                                Jam Masuk Awal
                            </label>
                            <div class="col-sm-9">
                                <input type="time" 
                                       name="jam_masuk_awal" 
                                       id="jam_masuk_awal" 
                                       step="60" 
                                       class="form-control"
                                       value="{{ $pengaturan->jam_masuk_awal ?? '05:00' }}"
                                       @if ($sudahAdaMasuk) disabled @endif
                                       required>
                                <div class="form-text">
                                    <i class="bi bi-lightbulb me-1"></i>
                                    Waktu paling awal siswa dapat melakukan absen masuk
                                </div>
                            </div>
                        </div>

                        <!-- Jam Masuk Akhir -->
                        <div class="row mb-3">
                            <label for="jam_masuk_akhir" class="col-sm-3 col-form-label">
                                <i class="bi bi-sun me-1"></i>
                                Jam Masuk Akhir
                            </label>
                            <div class="col-sm-9">
                                <input type="time" 
                                       name="jam_masuk_akhir" 
                                       id="jam_masuk_akhir" 
                                       step="60" 
                                       class="form-control"
                                       value="{{ $pengaturan->jam_masuk_akhir ?? '07:00' }}"
                                       @if ($sudahAdaMasuk) disabled @endif
                                       required>
                                <div class="form-text">
                                    <i class="bi bi-lightbulb me-1"></i>
                                    Batas waktu terakhir siswa dapat absen masuk (setelah ini dianggap terlambat)
                                </div>
                                
                                <!-- Alert Jam Masuk -->
                                <div id="jamMasukAlert" class="alert alert-warning mt-2 d-none">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    <span id="jamMasukInfo"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Jam Pulang -->
                        <div class="row mb-4">
                            <label for="jam_pulang" class="col-sm-3 col-form-label">
                                <i class="bi bi-sunset me-1"></i>
                                Jam Pulang
                            </label>
                            <div class="col-sm-9">
                                <input type="time" 
                                       name="jam_pulang" 
                                       id="jam_pulang" 
                                       step="60" 
                                       class="form-control"
                                       value="{{ $pengaturan->jam_pulang ?? '15:00' }}"
                                       required>
                                <div class="form-text">
                                    <i class="bi bi-lightbulb me-1"></i>
                                    Waktu siswa dapat melakukan absen pulang
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" id="btnSimpan" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Simpan Pengaturan
                                </button>
                                <button type="reset" class="btn btn-outline-secondary ms-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Alert Messages -->
                    <div id="alertSuccess" class="alert alert-success alert-dismissible fade show mt-3 d-none" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        <span class="alert-message"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <div id="alertError" class="alert alert-danger alert-dismissible fade show mt-3 d-none" role="alert">
                        <i class="bi bi-exclamation-octagon me-1"></i>
                        <span class="alert-message"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Info Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi Pengaturan
                    </h5>
                    
                    <div class="d-flex align-items-center mb-3 cursor-pointer" data-bs-toggle="collapse" 
                         data-bs-target="#jamMasukInfo" aria-expanded="false" aria-controls="jamMasukInfo">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px; background-color: #e3f2fd;">
                            <i class="bi bi-clock text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Jam Masuk <i class="bi bi-chevron-down ms-1"></i></h6>
                            <small class="text-muted">Klik untuk melihat keterangan</small>
                        </div>
                    </div>
                    
                    <div class="collapse" id="jamMasukInfo">
                        <div class="card card-body mb-3" style="background-color: #f8f9fa; border-left: 4px solid #007bff;">
                            <small>
                                <strong>Cara Menggunakan:</strong><br>
                                • <strong>Jam Masuk Awal:</strong> Waktu paling awal siswa bisa absen (contoh: 05:00)<br>
                                • <strong>Jam Masuk Akhir:</strong> Batas waktu absen masuk, setelah jam ini dianggap terlambat<br>
                                • Siswa hanya bisa absen masuk dalam rentang waktu ini<br>
                                • Pengaturan otomatis terkunci jika ada siswa yang sudah absen hari ini
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3 cursor-pointer" data-bs-toggle="collapse" 
                         data-bs-target="#jamPulangInfo" aria-expanded="false" aria-controls="jamPulangInfo">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px; background-color: #fff3e0;">
                            <i class="bi bi-calendar-check text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Jam Pulang <i class="bi bi-chevron-down ms-1"></i></h6>
                            <small class="text-muted">Klik untuk melihat keterangan</small>
                        </div>
                    </div>
                    
                    <div class="collapse" id="jamPulangInfo">
                        <div class="card card-body mb-3" style="background-color: #f8f9fa; border-left: 4px solid #ffc107;">
                            <small>
                                <strong>Cara Menggunakan:</strong><br>
                                • Tentukan waktu siswa dapat melakukan absen pulang<br>
                                • Siswa dapat absen pulang mulai dari jam yang ditentukan<br>
                                • Tidak ada batas akhir untuk absen pulang<br>
                                • Jam pulang bisa diubah kapan saja sesuai kebutuhan sekolah
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3 cursor-pointer" data-bs-toggle="collapse" 
                         data-bs-target="#tipsInfo" aria-expanded="false" aria-controls="tipsInfo">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px; background-color: #e8f5e8;">
                            <i class="bi bi-lightbulb-fill text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Tips & Trik <i class="bi bi-chevron-down ms-1"></i></h6>
                            <small class="text-muted">Klik untuk melihat tips</small>
                        </div>
                    </div>
                    
                    <div class="collapse" id="tipsInfo">
                        <div class="card card-body mb-3" style="background-color: #f8f9fa; border-left: 4px solid #28a745;">
                            <small>
                                <strong>Tips Penggunaan:</strong><br>
                                • Berikan toleransi waktu 15-30 menit untuk jam masuk akhir<br>
                                • Sesuaikan dengan jadwal transportasi siswa<br>
                                • Koordinasi dengan guru piket untuk konsistensi<br>
                                • Backup pengaturan sebelum melakukan perubahan besar<br>
                                • Monitor laporan keterlambatan untuk evaluasi pengaturan
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Time Card -->
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-clock me-2"></i>
                        Waktu Saat Ini
                    </h5>
                    <div id="currentTime" class="display-6 text-primary">
                        --:--:--
                    </div>
                    <div id="currentDate" class="text-muted">
                        Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="{{ asset('js/pengaturan.js') }}"></script>
<script>
// Real-time clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    const dateString = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    document.getElementById('currentTime').textContent = timeString;
    document.getElementById('currentDate').textContent = dateString;
}

// Update clock every second
setInterval(updateClock, 1000);
updateClock(); // Initial call

// Add cursor pointer style for collapsible items
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effect for collapsible items
    const collapsibleItems = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapsibleItems.forEach(item => {
        item.style.cursor = 'pointer';
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
            this.style.borderRadius = '8px';
            this.style.transition = 'background-color 0.2s ease';
        });
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
        });
    });

    // Handle chevron rotation
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(element) {
        const chevron = element.querySelector('.bi-chevron-down');
        const target = document.querySelector(element.getAttribute('data-bs-target'));
        
        target.addEventListener('show.bs.collapse', function() {
            chevron.style.transform = 'rotate(180deg)';
            chevron.style.transition = 'transform 0.2s ease';
        });
        
        target.addEventListener('hide.bs.collapse', function() {
            chevron.style.transform = 'rotate(0deg)';
            chevron.style.transition = 'transform 0.2s ease';
        });
    });
});
</script>
@endpush