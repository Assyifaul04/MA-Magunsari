<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Data Siswa -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('siswa.*') ? '' : 'collapsed' }}" href="{{ route('siswa.index') }}">
                <i class="bi bi-person-lines-fill"></i>
                <span>Data Siswa</span>
            </a>
        </li>

        <!-- Absensi Harian -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.index') ? '' : 'collapsed' }}" href="{{ route('absensi.index') }}">
                <i class="bi bi-fingerprint"></i>
                <span>Absensi</span>
            </a>
        </li>

        <!-- Rekap/Riwayat Absensi -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.rekap') ? '' : 'collapsed' }}" href="{{ route('absensi.rekap') }}">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat Absensi</span>
            </a>
        </li>

        <!-- Logout -->
        {{-- 
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
        --}}
    </ul>

</aside>
