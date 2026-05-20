@php
    use Illuminate\Support\Facades\Auth;

    function isActive($routes)
    {
        return request()->routeIs($routes) ? 'active' : '';
    }

    function isExpanded($routes)
    {
        return request()->routeIs($routes) ? 'show' : 'collapse';
    }
@endphp

<style>
    /* Custom Styling to make NiceAdmin Sidebar more Premium */
    .sidebar {
        box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.08);
        border-right: 1px solid #f3f4f7;
    }
    
    .sidebar-user-card {
        background: linear-gradient(135deg, #f6f9ff 0%, #eef2ff 100%);
        border-radius: 12px;
        padding: 15px;
        margin: 10px 15px 20px 15px;
        border: 1px solid #e0e7ff;
    }

    .sidebar-nav .nav-heading {
        font-size: 11px;
        font-weight: 700;
        color: #899bbd;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        padding-left: 20px;
        margin-top: 10px;
    }

    .sidebar-nav .nav-link {
        border-radius: 8px;
        margin: 2px 15px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .sidebar-nav .nav-link:hover {
        background-color: #f6f9ff;
        color: #4154f1;
        transform: translateX(4px); /* Animasi geser kanan saat hover */
    }

    .sidebar-nav .nav-content a {
        border-radius: 6px;
        margin: 2px 15px 2px 35px;
        padding: 8px 15px;
        transition: all 0.2s ease;
    }

    .sidebar-nav .nav-content a:hover {
        background-color: rgba(65, 84, 241, 0.05);
        color: #4154f1;
        padding-left: 25px; /* Animasi geser teks child saat hover */
    }

    /* Icon Colors */
    .icon-dashboard { color: #4154f1; }
    .icon-user { color: #dc3545; }
    .icon-presensi { color: #198754; }
    .icon-data { color: #0dcaf0; }
    .icon-master { color: #ffc107; }
    .icon-wa { color: #20c997; }
</style>

<aside id="sidebar" class="sidebar d-flex flex-column">
    
    <!-- User Profile Widget (Visual Enhancement) -->
    <div class="sidebar-user-card d-flex align-items-center">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 42px; height: 42px; font-size: 18px; font-weight: bold;">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="d-flex flex-column overflow-hidden">
            <span class="fw-bold text-dark text-truncate" style="font-size: 14px;">{{ Auth::user()->name ?? 'Pengguna' }}</span>
            <span class="text-muted text-capitalize" style="font-size: 12px;">
                <i class="bi bi-shield-check text-success me-1"></i> {{ Auth::user()->role }}
            </span>
        </div>
    </div>

    <ul class="sidebar-nav flex-grow-1 pb-4" id="sidebar-nav">

        <li class="nav-heading">Utama</li>
        
        <li class="nav-item">
            @if (Auth::user()->role === 'superAdmin')
                <a class="nav-link {{ isActive('superAdmin.dashboard') ? '' : 'collapsed' }}"
                    href="{{ route('superAdmin.dashboard') }}">
                    <i class="bi bi-grid icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            @elseif (Auth::user()->role === 'admin')
                <a class="nav-link {{ isActive('admin.dashboard') ? '' : 'collapsed' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            @elseif (Auth::user()->role === 'guru')
                <a class="nav-link {{ isActive('guru.dashboard') ? '' : 'collapsed' }}"
                    href="{{ route('guru.dashboard') }}">
                    <i class="bi bi-grid icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            @endif
        </li>

        <!-- Menu khusus SuperAdmin -->
        @if (Auth::user()->role === 'superAdmin')
            <li class="nav-heading">Administrator</li>
            <li class="nav-item">
                <a class="nav-link {{ isActive('users.*') ? '' : 'collapsed' }}" data-bs-target="#users-nav"
                    data-bs-toggle="collapse" href="#">
                    <i class="bi bi-person-gear icon-user"></i>
                    <span>Manajemen User</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="users-nav" class="nav-content {{ isExpanded('users.*') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('users.index') }}" class="{{ isActive('users.index') }}">
                            <i class="bi bi-circle"></i><span>Users</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Menu Admin -->
        @if (Auth::user()->role === 'admin')
            
            <li class="nav-heading">Manajemen Absensi</li>
            
            <!-- Presensi -->
            <li class="nav-item">
                @php
                    $isPresensiActive = isActive('absensi.masuk') || isActive('absensi.keluar') || isActive('absensi.izin');
                    $isPresensiExpanded = $isPresensiActive ? 'show' : 'collapse';
                @endphp
                <a class="nav-link {{ $isPresensiActive ? '' : 'collapsed' }}" data-bs-target="#absensi-nav"
                    data-bs-toggle="collapse" href="#">
                    <i class="bi bi-upc-scan icon-presensi"></i>
                    <span>Presensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="absensi-nav" class="nav-content {{ $isPresensiExpanded }}" data-bs-parent="#sidebar-nav">
                    <li><a href="{{ route('absensi.masuk') }}" class="{{ isActive('absensi.masuk') }}"><i class="bi bi-circle"></i><span>Masuk</span></a></li>
                    <li><a href="{{ route('absensi.keluar') }}" class="{{ isActive('absensi.keluar') }}"><i class="bi bi-circle"></i><span>Keluar</span></a></li>
                    <li><a href="{{ route('absensi.izin') }}" class="{{ isActive('absensi.izin') }}"><i class="bi bi-circle"></i><span>Izin</span></a></li>
                </ul>
            </li>

            <!-- Data Absensi -->
            <li class="nav-item">
                @php
                    $isDataAbsensiActive = isActive('absensi.hariIni') || isActive('absensi.byRange') || isActive('absensi.rekap_bulanan');
                    $isDataAbsensiExpanded = $isDataAbsensiActive ? 'show' : 'collapse';
                @endphp
                <a class="nav-link {{ $isDataAbsensiActive ? '' : 'collapsed' }}"
                    data-bs-target="#data-absensi-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-calendar-check icon-data"></i>
                    <span>Data Absensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="data-absensi-nav"
                    class="nav-content {{ $isDataAbsensiExpanded }}"
                    data-bs-parent="#sidebar-nav">

                    <li><a href="{{ route('absensi.hariIni') }}" class="{{ isActive('absensi.hariIni') }}"><i class="bi bi-circle"></i><span>Hari Ini</span></a></li>
                    <li><a href="{{ route('absensi.byRange') }}" class="{{ isActive('absensi.byRange') }}"><i class="bi bi-circle"></i><span>By Range</span></a></li>
                    <li><a href="{{ route('absensi.rekap_bulanan') }}" class="{{ isActive('absensi.rekap_bulanan') }}"><i class="bi bi-circle"></i><span>Rekap Bulanan</span></a></li>
                </ul>
            </li>

            <li class="nav-heading">Konfigurasi Sistem</li>

            <!-- Master Data -->
            <li class="nav-item">
                @php
                    $isMasterActive = isActive('siswa.*') || isActive('kelas.*') || isActive('pengaturan.*') || isActive('orangtua.*');
                    $isMasterExpanded = $isMasterActive ? 'show' : 'collapse';
                @endphp
                <a class="nav-link {{ $isMasterActive ? '' : 'collapsed' }}"
                    data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-database icon-master"></i>
                    <span>Master Data</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="master-nav"
                    class="nav-content {{ $isMasterExpanded }}"
                    data-bs-parent="#sidebar-nav">

                    <li><a href="{{ route('siswa.index') }}" class="{{ isActive('siswa.*') }}"><i class="bi bi-circle"></i><span>Siswa</span></a></li>
                    <li><a href="{{ route('kelas.index') }}" class="{{ isActive('kelas.*') }}"><i class="bi bi-circle"></i><span>Kelas</span></a></li>
                    <li><a href="{{ route('orangtua.index') }}" class="{{ isActive('orangtua.*') }}"><i class="bi bi-circle"></i><span>Orang Tua</span></a></li>
                    <li><a href="{{ route('pengaturan.edit') }}" class="{{ isActive('pengaturan.*') }}"><i class="bi bi-circle"></i><span>Pengaturan</span></a></li>
                </ul>
            </li>

            <!-- WhatsApp System -->
            <li class="nav-item">
                @php
                    $isWaActive = isActive('templatewa.*') || isActive('notifikasiwa.*');
                    $isWaExpanded = $isWaActive ? 'show' : 'collapse';
                @endphp
                <a class="nav-link {{ $isWaActive ? '' : 'collapsed' }}"
                    data-bs-target="#wa-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-whatsapp icon-wa"></i>
                    <span>WhatsApp</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="wa-nav"
                    class="nav-content {{ $isWaExpanded }}"
                    data-bs-parent="#sidebar-nav">

                    <li><a href="{{ route('templatewa.index') }}" class="{{ isActive('templatewa.*') }}"><i class="bi bi-circle"></i><span>Template Pesan</span></a></li>
                    <li><a href="{{ route('notifikasiwa.index') }}" class="{{ isActive('notifikasiwa.*') }}"><i class="bi bi-circle"></i><span>Log WhatsApp</span></a></li>
                </ul>
            </li>

        @endif

        <!-- Guru -->
        @if (Auth::user()->role === 'guru')
            <li class="nav-heading">Menu Guru</li>
            {{-- Tambahkan menu khusus guru di sini kalau ada --}}
        @endif
        
    </ul>
</aside>