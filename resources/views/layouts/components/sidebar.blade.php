<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Dropdown: Presensi -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.*') ? '' : 'collapsed' }}" data-bs-target="#absensi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-credit-card"></i>
                <span>Presensi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="absensi-nav" class="nav-content collapse {{ request()->routeIs('absensi.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('absensi.masuk') }}" class="{{ request()->routeIs('absensi.masuk') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('absensi.keluar') }}" class="{{ request()->routeIs('absensi.keluar') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Keluar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('absensi.izin') }}" class="{{ request()->routeIs('absensi.izin') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Izin</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Dropdown: Master Data -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('siswa.*') || request()->routeIs('kelas.*') || request()->routeIs('pengaturan.*') ? '' : 'collapsed' }}" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i>
                <span>Master Data</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="master-nav" class="nav-content collapse {{ request()->routeIs('siswa.*') || request()->routeIs('kelas.*') || request()->routeIs('pengaturan.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('siswa.index') }}" class="{{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kelas.index') }}" class="{{ request()->routeIs('kelas.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Kelas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pengaturan.edit') }}" class="{{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Dropdown: Data Absensi -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.hariIni') || request()->routeIs('absensi.byRange') ? '' : 'collapsed' }}" data-bs-target="#data-absensi-menu" data-bs-toggle="collapse" href="#">
                <i class="bi bi-calendar-check"></i>
                <span>Data Absensi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="data-absensi-menu" class="nav-content collapse {{ request()->routeIs('absensi.hariIni') || request()->routeIs('absensi.byRange') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('absensi.hariIni') }}" class="{{ request()->routeIs('absensi.hariIni') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Hari Ini</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('absensi.byRange') }}" class="{{ request()->routeIs('absensi.byRange') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>By Range</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Dropdown: Data Laporan -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.performa') || request()->routeIs('absensi.rekap') ? '' : 'collapsed' }}" data-bs-target="#data-laporan-menu" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-text"></i>
                <span>Data Laporan</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="data-laporan-menu" class="nav-content collapse {{ request()->routeIs('absensi.performa') || request()->routeIs('absensi.rekap') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('absensi.performa') }}" class="{{ request()->routeIs('absensi.performa') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Performa</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('absensi.rekap') }}" class="{{ request()->routeIs('absensi.rekap') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Rekap</span>
                    </a>
                </li> --}}
            </ul>
        </li>

    </ul>
</aside>
