{{-- resources/views/layouts/components/sidebar.blade.php --}}
@php
    use Illuminate\Support\Facades\Auth;

    function isActive($routes) {
        return request()->routeIs($routes) ? 'active' : '';
    }

    function isExpanded($routes) {
        return request()->routeIs($routes) ? 'show' : 'collapse';
    }
@endphp

<aside id="sidebar" class="sidebar d-flex flex-column">

    <ul class="sidebar-nav flex-grow-1" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ isActive('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Presensi -->
        <li class="nav-item">
            <a class="nav-link {{ isActive('absensi.*') ? '' : 'collapsed' }}" data-bs-target="#absensi-nav"
               data-bs-toggle="collapse" href="#">
                <i class="bi bi-credit-card"></i>
                <span>Presensi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="absensi-nav" class="nav-content {{ isExpanded('absensi.*') }}" data-bs-parent="#sidebar-nav">
                <li><a href="{{ route('absensi.masuk') }}" class="{{ isActive('absensi.masuk') }}"><i class="bi bi-dot"></i><span>Masuk</span></a></li>
                <li><a href="{{ route('absensi.keluar') }}" class="{{ isActive('absensi.keluar') }}"><i class="bi bi-dot"></i><span>Keluar</span></a></li>
                <li><a href="{{ route('absensi.izin') }}" class="{{ isActive('absensi.izin') }}"><i class="bi bi-dot"></i><span>Izin</span></a></li>
            </ul>
        </li>

        <!-- Master Data -->
        <li class="nav-item">
            <a class="nav-link {{ (isActive('siswa.*') || isActive('kelas.*') || isActive('pengaturan.*')) ? '' : 'collapsed' }}"
               data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i>
                <span>Master Data</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="master-nav" class="nav-content {{ (isActive('siswa.*') || isActive('kelas.*') || isActive('pengaturan.*')) ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
                <li><a href="{{ route('siswa.index') }}" class="{{ isActive('siswa.*') }}"><i class="bi bi-dot"></i><span>Siswa</span></a></li>
                <li><a href="{{ route('kelas.index') }}" class="{{ isActive('kelas.*') }}"><i class="bi bi-dot"></i><span>Kelas</span></a></li>
                <li><a href="{{ route('pengaturan.edit') }}" class="{{ isActive('pengaturan.*') }}"><i class="bi bi-dot"></i><span>Pengaturan</span></a></li>
            </ul>
        </li>

        <!-- Data Absensi -->
        <li class="nav-item">
            <a class="nav-link {{ (isActive('absensi.hariIni') || isActive('absensi.byRange')) ? '' : 'collapsed' }}"
               data-bs-target="#data-absensi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-calendar-check"></i>
                <span>Data Absensi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="data-absensi-nav" class="nav-content {{ (isActive('absensi.hariIni') || isActive('absensi.byRange')) ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
                <li><a href="{{ route('absensi.hariIni') }}" class="{{ isActive('absensi.hariIni') }}"><i class="bi bi-dot"></i><span>Hari Ini</span></a></li>
                <li><a href="{{ route('absensi.byRange') }}" class="{{ isActive('absensi.byRange') }}"><i class="bi bi-dot"></i><span>By Range</span></a></li>
            </ul>
        </li>

        <!-- Data Laporan -->
        <li class="nav-item">
            <a class="nav-link {{ (isActive('absensi.performa') || isActive('absensi.rekap')) ? '' : 'collapsed' }}"
               data-bs-target="#data-laporan-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-text"></i>
                <span>Data Laporan</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="data-laporan-nav" class="nav-content {{ (isActive('absensi.performa') || isActive('absensi.rekap')) ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
                <li><a href="{{ route('absensi.performa') }}" class="{{ isActive('absensi.performa') }}"><i class="bi bi-dot"></i><span>Performa</span></a></li>
            </ul>
        </li>

    </ul>

    <!-- Profile Sidebar -->
    <div class="sidebar-profile mt-auto p-3 border-top">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Avatar & Info -->
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle me-2" width="36" height="36">
                <div>
                    <strong>{{ Auth::user()->name }}</strong><br>
                    <small class="text-muted">{{ Auth::user()->role ?? 'User' }}</small>
                </div>
            </div>

            <!-- Dropdown -->
            <div class="dropdown">
                <a href="#" class="text-decoration-none text-dark" id="profileDropdown" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical fs-5"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li class="dropdown-header text-center">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->role ?? 'User' }}</small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-gear me-2"></i>Account Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</aside>
