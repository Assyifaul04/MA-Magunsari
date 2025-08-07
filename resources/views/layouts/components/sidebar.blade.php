<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Dropdown: Manajemen Siswa -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('siswa.*') ? '' : 'collapsed' }}" data-bs-target="#siswa-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-lines-fill"></i><span>Manajemen Siswa</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="siswa-nav" class="nav-content collapse {{ request()->routeIs('siswa.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('siswa.index') }}" class="{{ request()->routeIs('siswa.index') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Data Siswa</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Absensi Harian -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.index') ? '' : 'collapsed' }}" href="{{ route('absensi.index') }}">
                <i class="bi bi-credit-card"></i>
                <span>Absensi</span>
            </a>
        </li>

        <!-- Rekap Absensi -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.rekap') ? '' : 'collapsed' }}" href="{{ route('absensi.rekap') }}">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat Absensi</span>
            </a>
        </li>
    </ul>
</aside>
