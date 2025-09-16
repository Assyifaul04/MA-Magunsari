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
            @if (Auth::user()->role === 'superAdmin')
                <a class="nav-link {{ isActive('superAdmin.dashboard') ? '' : 'collapsed' }}"
                    href="{{ route('superAdmin.dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            @elseif (Auth::user()->role === 'admin')
                <a class="nav-link {{ isActive('admin.dashboard') ? '' : 'collapsed' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            @elseif (Auth::user()->role === 'guru')
                <a class="nav-link {{ isActive('guru.dashboard') ? '' : 'collapsed' }}"
                    href="{{ route('guru.dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            @endif
        </li>

        <!-- Menu khusus SuperAdmin -->
        @if (Auth::user()->role === 'superAdmin')
            <li class="nav-item">
                <a class="nav-link {{ isActive('users.*') ? '' : 'collapsed' }}" data-bs-target="#users-nav"
                    data-bs-toggle="collapse" href="#">
                    <i class="bi bi-person-gear"></i>
                    <span>Manajemen User</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="users-nav" class="nav-content {{ isExpanded('users.*') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('users.index') }}" class="{{ isActive('users.index') }}">
                            <i class="bi bi-dot"></i><span>Users</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Menu Admin -->
        @if (Auth::user()->role === 'admin')
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
                    <li>
                        <a href="{{ route('absensi.rekap_bulanan') }}"
                            class="{{ isActive('absensi.rekap_bulanan') }}">
                            <i class="bi bi-dot"></i>
                            <span>Rekap</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Guru -->
        @if (Auth::user()->role === 'guru')
            {{-- Tambahkan menu khusus guru di sini kalau ada --}}
        @endif
    </ul>
</aside>
