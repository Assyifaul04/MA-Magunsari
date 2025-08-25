{{-- resources/views/layouts/components/sidebar.blade.php --}}
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
                <li><a href="{{ route('absensi.masuk') }}" class="{{ isActive('absensi.masuk') }}"><i
                            class="bi bi-dot"></i><span>Masuk</span></a></li>
                <li><a href="{{ route('absensi.keluar') }}" class="{{ isActive('absensi.keluar') }}"><i
                            class="bi bi-dot"></i><span>Keluar</span></a></li>
                <li><a href="{{ route('absensi.izin') }}" class="{{ isActive('absensi.izin') }}"><i
                            class="bi bi-dot"></i><span>Izin</span></a></li>
            </ul>
        </li>

        <!-- Master Data -->
        <li class="nav-item">
            <a class="nav-link {{ isActive('siswa.*') || isActive('kelas.*') || isActive('pengaturan.*') ? '' : 'collapsed' }}"
                data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i>
                <span>Master Data</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="master-nav"
                class="nav-content {{ isActive('siswa.*') || isActive('kelas.*') || isActive('pengaturan.*') ? 'show' : 'collapse' }}"
                data-bs-parent="#sidebar-nav">
                <li><a href="{{ route('siswa.index') }}" class="{{ isActive('siswa.*') }}"><i
                            class="bi bi-dot"></i><span>Siswa</span></a></li>
                <li><a href="{{ route('kelas.index') }}" class="{{ isActive('kelas.*') }}"><i
                            class="bi bi-dot"></i><span>Kelas</span></a></li>
                <li><a href="{{ route('pengaturan.edit') }}" class="{{ isActive('pengaturan.*') }}"><i
                            class="bi bi-dot"></i><span>Pengaturan</span></a></li>
            </ul>
        </li>

        <!-- Data Absensi -->
        <li class="nav-item">
            <a class="nav-link {{ isActive('absensi.hariIni') || isActive('absensi.byRange') ? '' : 'collapsed' }}"
                data-bs-target="#data-absensi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-calendar-check"></i>
                <span>Data Absensi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="data-absensi-nav"
                class="nav-content {{ isActive('absensi.hariIni') || isActive('absensi.byRange') ? 'show' : 'collapse' }}"
                data-bs-parent="#sidebar-nav">
                <li><a href="{{ route('absensi.hariIni') }}" class="{{ isActive('absensi.hariIni') }}"><i
                            class="bi bi-dot"></i><span>Hari Ini</span></a></li>
                <li><a href="{{ route('absensi.byRange') }}" class="{{ isActive('absensi.byRange') }}"><i
                            class="bi bi-dot"></i><span>By Range</span></a></li>
            </ul>
        </li>

        <!-- Data  -->
        <li class="nav-item">
            <a class="nav-link {{ isActive('absensi.performa') || isActive('absensi.rekap') ? '' : 'collapsed' }}"
                data-bs-target="#data-laporan-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-text"></i>
                <span>Laporan Performa</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="data-laporan-nav"
                class="nav-content {{ isActive('absensi.performa') || isActive('absensi.rekap') ? 'show' : 'collapse' }}"
                data-bs-parent="#sidebar-nav">
                <li><a href="{{ route('absensi.performa') }}" class="{{ isActive('absensi.performa') }}"><i
                            class="bi bi-dot"></i><span>Performa Kehadiran</span></a></li>
            </ul>
        </li>

    </ul>
</aside>
