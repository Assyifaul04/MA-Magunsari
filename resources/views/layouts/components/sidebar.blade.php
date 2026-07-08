@php
    use Illuminate\Support\Facades\Auth;

    /**
     * Helper: cek apakah route aktif, kembalikan class 'active' atau ''.
     */
    function isActive($routes)   { return request()->routeIs($routes) ? 'active' : ''; }

    /**
     * Helper: cek apakah collapse harus terbuka, kembalikan 'show' atau 'collapse'.
     */
    function isExpanded($routes) { return request()->routeIs($routes) ? 'show' : 'collapse'; }

    $role = Auth::user()->role ?? '';
@endphp

{{-- ══════════════════════════════════════════════════════
     STYLES – Sidebar
     Semua token visual (warna, tipografi, layout) didefinisikan di sini.
═══════════════════════════════════════════════════════════ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

    #sidebar.sidebar {
        background    : #ffffff;
        border-right  : 1px solid #eaecf3;
        box-shadow    : 2px 0 20px rgba(15,23,80,.05);
        font-family   : 'DM Sans', sans-serif;
        display       : flex;
        flex-direction: column;
        padding-bottom: 0;
    }
    .sb-user {
        margin        : 14px 14px 6px;
        padding       : 13px 14px;
        background    : linear-gradient(130deg,#f0fff7 0%,#e6faf1 100%);
        border        : 1px solid #b2f0d0;
        border-radius : 13px;
        display       : flex;
        align-items   : center;
        gap           : 11px;
        flex-shrink   : 0;
    }
    .sb-avatar {
        width        : 38px;
        height       : 38px;
        border-radius: 11px;
        background   : linear-gradient(135deg,#1e8a4a,#27ae60);
        color        : #fff;
        font-size    : 16px;
        font-weight  : 700;
        display      : grid;
        place-items  : center;
        flex-shrink  : 0;
        box-shadow   : 0 3px 10px rgba(30,138,74,.3);
        font-family  : 'DM Sans', sans-serif;
        letter-spacing: -.02em;
    }
    .sb-user-info  { min-width: 0; flex: 1; }
    .sb-user-name {
        font-size      : 13px;
        font-weight    : 700;
        color          : #1a1d2e;
        white-space    : nowrap;
        overflow       : hidden;
        text-overflow  : ellipsis;
        line-height    : 1.2;
        letter-spacing : -.01em;
    }
    .sb-user-role {
        font-size  : 11px;
        color      : #7c85a2;
        display    : flex;
        align-items: center;
        gap        : 4px;
        margin-top : 3px;
        font-weight: 500;
    }
    .sb-user-role i { color: #22c55e; font-size: 10px; }
    .sb-role-chip {
        display       : inline-flex;
        align-items   : center;
        gap           : 5px;
        font-size     : 10px;
        font-weight   : 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding       : 3px 9px;
        border-radius : 50px;
        margin-left   : auto;
        flex-shrink   : 0;
    }
    .sb-role-chip.chip-admin { background: #fff0f0; color: #e03131; }
    .sb-role-chip.chip-guru  { background: #f0fff8; color: #0ca678; }
    .sb-role-chip.chip-super { background: #f3f0ff; color: #7048e8; }
    .sb-kelas-info {
        margin        : 6px 10px 4px;
        padding       : 9px 13px;
        background    : linear-gradient(130deg,#f0fff7 0%,#e6faf1 100%);
        border        : 1px solid #b2f0d0;
        border-radius : 10px;
        display       : flex;
        align-items   : center;
        gap           : 9px;
    }
    .sb-kelas-info i { font-size: 15px; color: #1e8a4a; flex-shrink: 0; }
    .sb-kelas-info-text { min-width: 0; }
    .sb-kelas-label {
        font-size      : 9.5px;
        font-weight    : 700;
        text-transform : uppercase;
        letter-spacing : .08em;
        color          : #7c85a2;
        line-height    : 1;
    }
    .sb-kelas-name {
        font-size      : 13px;
        font-weight    : 700;
        color          : #1a1d2e;
        letter-spacing : -.01em;
        line-height    : 1.3;
        white-space    : nowrap;
        overflow       : hidden;
        text-overflow  : ellipsis;
    }
    .sidebar-nav {
        padding   : 6px 0 24px;
        overflow-y: auto;
        flex      : 1;
    }
    .sidebar-nav::-webkit-scrollbar       { width: 3px; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e6f0; border-radius: 4px; }
    .sidebar-nav .nav-heading {
        font-size     : 9.5px;
        font-weight   : 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color         : #c2cbdf;
        padding       : 20px 22px 7px;
        margin        : 0;
    }
    .sidebar-nav .nav-heading-divider {
        border    : none;
        border-top: 1px solid #f0f2f8;
        margin    : 2px 18px 4px;
    }
    .sidebar-nav .nav-item { list-style: none; }
    .sidebar-nav .nav-link {
        display        : flex;
        align-items    : center;
        gap            : 10px;
        font-size      : 13.5px;
        font-weight    : 500;
        color          : #5a6382;
        border-radius  : 10px;
        margin         : 1px 10px;
        padding        : 10px 13px;
        transition     : background .18s, color .18s;
        text-decoration: none;
        position       : relative;
        letter-spacing : -.01em;
    }
    .sidebar-nav .nav-link i:first-child {
        font-size  : 16px;
        width      : 20px;
        text-align : center;
        flex-shrink: 0;
    }
    .sidebar-nav .nav-link span { flex: 1; }
    .sidebar-nav .nav-link .bi-chevron-down {
        font-size  : 10.5px;
        color      : #c2cbdf;
        transition : transform .25s, color .18s;
        flex-shrink: 0;
    }
    .sidebar-nav .nav-link:not(.collapsed) .bi-chevron-down {
        transform: rotate(180deg);
        color    : #1e8a4a;
    }
    .sidebar-nav .nav-link:hover {
        background: #f0fff7;
        color     : #1e8a4a;
    }
    .sidebar-nav .nav-link:not(.collapsed),
    .sidebar-nav .nav-link.active {
        background : #e6faf1;
        color      : #1e8a4a;
        font-weight: 600;
    }
    .sidebar-nav .nav-link.active::before,
    .sidebar-nav .nav-link:not(.collapsed)::before {
        content      : '';
        position     : absolute;
        left         : 0;
        top          : 50%;
        transform    : translateY(-50%);
        width        : 3px;
        height       : 56%;
        background   : #1e8a4a;
        border-radius: 0 3px 3px 0;
    }
    .sidebar-nav .nav-content {
        list-style: none;
        padding   : 2px 0 4px;
        margin    : 0;
    }
    .sidebar-nav .nav-content li { list-style: none; }
    .sidebar-nav .nav-content a {
        display        : flex;
        align-items    : center;
        gap            : 8px;
        font-size      : 13px;
        font-weight    : 500;
        color          : #7c85a2;
        border-radius  : 9px;
        margin         : 1px 10px 1px 34px;
        padding        : 8px 13px;
        text-decoration: none;
        transition     : background .15s, color .15s, padding-left .15s;
        position       : relative;
        letter-spacing : -.01em;
    }
    .sidebar-nav .nav-content a i.bi-circle {
        font-size  : 6px;
        color      : #d0d5e8;
        flex-shrink: 0;
        transition : color .15s, transform .15s;
    }
    .sidebar-nav .nav-content a:hover {
        background  : #f0fff7;
        color       : #1e8a4a;
        padding-left: 18px;
    }
    .sidebar-nav .nav-content a:hover i.bi-circle {
        color    : #1e8a4a;
        transform: scale(1.3);
    }
    .sidebar-nav .nav-content a.active {
        background  : #e6faf1;
        color       : #1e8a4a;
        font-weight : 700;
        padding-left: 18px;
    }
    .sidebar-nav .nav-content a.active i.bi-circle { color: #1e8a4a; }
    .icon-dashboard { color: #1e8a4a; } 
    .icon-user      { color: #ef4444; } 
    .icon-presensi  { color: #22c55e; } 
    .icon-data      { color: #1e8a4a; } 
    .icon-master    { color: #f59e0b; } 
    .icon-wa        { color: #10b981; } 
    .icon-laporan   { color: #8b5cf6; } 
    .icon-scan      { color: #1e8a4a; } 
    .icon-absensi   { color: #22c55e; } 
    .icon-rfid      { color: #f59e0b; } 

    .sb-scan-btn {
        display        : flex;
        align-items    : center;
        gap            : 10px;
        font-size      : 13.5px;
        font-weight    : 500;
        color          : #1e8a4a;
        border-radius  : 10px;
        margin         : 1px 10px;
        padding        : 10px 13px;
        transition     : background .18s, color .18s;
        text-decoration: none;
        position       : relative;
        letter-spacing : -.01em;
        border         : 1.5px dashed #b2f0d0;
        background     : #f0fff7;
    }
    .sb-scan-btn i:first-child {
        font-size  : 16px;
        width      : 20px;
        text-align : center;
        flex-shrink: 0;
    }
    .sb-scan-btn .sb-scan-badge {
        font-size     : 9px;
        font-weight   : 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding       : 2px 7px;
        border-radius : 50px;
        background    : #d1fae5;
        color         : #065f46;
        flex-shrink   : 0;
    }
    .sb-scan-btn:hover {
        background  : #e6faf1;
        color       : #1e8a4a;
        border-color: #6ee7b7;
    }
    .sb-footer {
        padding    : 12px 18px;
        border-top : 1px solid #f0f2f8;
        display    : flex;
        align-items: center;
        gap        : 8px;
        flex-shrink: 0;
        background : #fafbfd;
    }
    .sb-footer-dot {
        width        : 7px;
        height       : 7px;
        border-radius: 50%;
        background   : #22c55e;
        box-shadow   : 0 0 0 3px rgba(34,197,94,.18);
        flex-shrink  : 0;
    }
    .sb-footer-text {
        font-size  : 11px;
        color      : #bcc3d8;
        font-weight: 500;
    }
</style>

{{-- ══════════════════════════════════════════════════════
     MARKUP – Sidebar
═══════════════════════════════════════════════════════════ --}}
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-heading">Utama</li>
        <hr class="nav-heading-divider">

        <li class="nav-item">
            @php
                $dashRoute = match($role) {
                    'superAdmin' => 'superAdmin.dashboard',
                    'admin'      => 'admin.dashboard',
                    'guru'       => 'guru.dashboard',
                    default      => 'admin.dashboard',
                };
            @endphp

            <a class="nav-link {{ request()->routeIs($dashRoute) ? 'active' : 'collapsed' }}"
               href="{{ route($dashRoute) }}">
                <i class="bi bi-grid icon-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if ($role === 'superAdmin')
            <li class="nav-heading">Administrator</li>
            <hr class="nav-heading-divider">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? '' : 'collapsed' }}"
                   data-bs-target="#users-nav"
                   data-bs-toggle="collapse"
                   href="#">
                    <i class="bi bi-person-gear icon-user"></i>
                    <span>Manajemen User</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="users-nav"
                    class="nav-content {{ isExpanded('users.*') }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('users.index') }}" class="{{ isActive('users.index') }}">
                            <i class="bi bi-circle"></i>
                            <span>Users</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if ($role === 'admin')
            <li class="nav-item">
                <a class="sb-scan-btn"
                   href="{{ route('absensi.scan') }}"
                   target="_blank"
                   rel="noopener noreferrer">
                    <i class="bi bi-qr-code-scan icon-scan"></i>
                    <span>Scan RFID</span>
                    <span class="sb-scan-badge">Tab Baru</span>
                </a>
            </li>

            <li class="nav-heading">Manajemen Absensi</li>
            <hr class="nav-heading-divider">

            @php
                $presensiActive = request()->routeIs('absensi.masuk')
                               || request()->routeIs('absensi.keluar');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $presensiActive ? '' : 'collapsed' }}"
                   data-bs-target="#absensi-nav"
                   data-bs-toggle="collapse"
                   href="#">
                    <i class="bi bi-upc-scan icon-presensi"></i>
                    <span>Presensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="absensi-nav"
                    class="nav-content {{ $presensiActive ? 'show' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('absensi.masuk') }}" class="{{ isActive('absensi.masuk') }}">
                            <i class="bi bi-circle"></i>
                            <span>Masuk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absensi.keluar') }}" class="{{ isActive('absensi.keluar') }}">
                            <i class="bi bi-circle"></i>
                            <span>Pulang</span>
                        </a>
                    </li>
                </ul>
            </li>

            @php
                $dataAbsensiActive = request()->routeIs('absensi.hariIni')
                                  || request()->routeIs('absensi.byRange')
                                  || request()->routeIs('absensi.rekap_bulanan');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $dataAbsensiActive ? '' : 'collapsed' }}"
                   data-bs-target="#data-absensi-nav"
                   data-bs-toggle="collapse"
                   href="#">
                    <i class="bi bi-calendar-check icon-data"></i>
                    <span>Rekap Absensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="data-absensi-nav"
                    class="nav-content {{ $dataAbsensiActive ? 'show' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('absensi.hariIni') }}" class="{{ isActive('absensi.hariIni') }}">
                            <i class="bi bi-circle"></i>
                            <span>Hari Ini</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absensi.byRange') }}" class="{{ isActive('absensi.byRange') }}">
                            <i class="bi bi-circle"></i>
                            <span>By Range</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absensi.rekap_bulanan') }}" class="{{ isActive('absensi.rekap_bulanan') }}">
                            <i class="bi bi-circle"></i>
                            <span>Rekap Bulanan</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-heading">Konfigurasi Sistem</li>
            <hr class="nav-heading-divider">

            @php
                $masterActive = request()->routeIs('siswa.*')
                             || request()->routeIs('kelas.*')
                             || request()->routeIs('pengaturan.*')
                             || request()->routeIs('orangtua.*')
                             || request()->routeIs('guru.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $masterActive ? '' : 'collapsed' }}"
                   data-bs-target="#master-nav"
                   data-bs-toggle="collapse"
                   href="#">
                    <i class="bi bi-database icon-master"></i>
                    <span>Master Data</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="master-nav"
                    class="nav-content {{ $masterActive ? 'show' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('siswa.index') }}" class="{{ isActive('siswa.*') }}">
                            <i class="bi bi-circle"></i>
                            <span>Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.index') }}" class="{{ isActive('guru.*') }}">
                            <i class="bi bi-circle"></i>
                            <span>Guru</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kelas.index') }}" class="{{ isActive('kelas.*') }}">
                            <i class="bi bi-circle"></i>
                            <span>Kelas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orangtua.index') }}" class="{{ isActive('orangtua.*') }}">
                            <i class="bi bi-circle"></i>
                            <span>Orang Tua</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengaturan.edit') }}" class="{{ isActive('pengaturan.*') }}">
                            <i class="bi bi-circle"></i>
                            <span>Waktu Scan</span>
                        </a>
                    </li>
                </ul>
            </li>

            @php
                $rfidAdminActive = request()->routeIs('rfid.*');
            @endphp

            <li class="nav-item">
                <a class="nav-link {{ $rfidAdminActive ? '' : 'collapsed' }}"
                data-bs-target="#admin-rfid-nav"
                data-bs-toggle="collapse"
                href="#">
                    <i class="bi bi-credit-card-2-front icon-master"></i>
                    <span>Laporan RFID</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="admin-rfid-nav"
                    class="nav-content {{ $rfidAdminActive ? 'show' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('rfid.laporan-hilang') }}"
                        class="{{ request()->routeIs('rfid.laporan-hilang') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i>
                            <span>RFID Hilang</span>
                        </a>
                    </li>
                </ul>
            </li>
            @php
                $waActive = request()->routeIs('templatewa.*')
                         || request()->routeIs('notifikasiwa.*')
                         || request()->routeIs('pengaturan-wa.*'); // <-- Tambahan di sini
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $waActive ? '' : 'collapsed' }}"
                   data-bs-target="#wa-nav"
                   data-bs-toggle="collapse"
                   href="#">
                    <i class="bi bi-whatsapp icon-wa"></i>
                    <span>WhatsApp</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="wa-nav"
                    class="nav-content {{ $waActive ? 'show' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('templatewa.index') }}" class="{{ isActive('templatewa.*') }}">
                            <i class="bi bi-circle"></i>
                            <span>Template Pesan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notifikasiwa.index') }}" class="{{ isActive('notifikasiwa.*') }}">
                            <i class="bi bi-circle"></i>
                            <span>Log WhatsApp</span>
                        </a>
                    </li>
                    <!-- Menu Baru: Pengaturan WA -->
                    <li>
                        <a href="{{ route('pengaturan-wa.index') }}" class="{{ isActive('pengaturan-wa.*') }}">
                            <i class="bi bi-circle"></i>
                            <span>Token Api WA</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- ┌──────────────────────────────────────────────
             │ GURU MENU
             └────────────────────────────────────────────── --}}
        @if ($role === 'guru')
            @php
                $guruModel     = \App\Models\Guru::with('kelas')->where('user_id', Auth::id())->first();
                $semuaKelas    = $guruModel?->kelas ?? collect();
                $isKelasActive = request()->routeIs('guru.siswa.*');
            @endphp

            <li class="nav-heading">Wali Kelas</li>
            <hr class="nav-heading-divider">

            {{-- 1. Dropdown: KELAS --}}
            <li class="nav-item">
                <a class="nav-link {{ $isKelasActive ? '' : 'collapsed' }}"
                data-bs-target="#guru-kelas-nav"
                data-bs-toggle="collapse"
                href="#">
                    <i class="bi bi-door-open icon-dashboard"></i>
                    <span>Kelas</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="guru-kelas-nav"
                    class="nav-content {{ $isKelasActive ? 'show' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">

                    @forelse ($semuaKelas as $kelas)
                        <li>
                            <a href="{{ route('guru.siswa.index', ['kelas_id' => $kelas->id]) }}"
                            class="{{ request()->routeIs('guru.siswa.*') && request()->query('kelas_id') == $kelas->id ? 'active' : '' }}">
                                <i class="bi bi-circle"></i>
                                <span>{{ $kelas->nama }}</span>
                            </a>
                        </li>
                    @empty
                        <li>
                            <a href="#" class="disabled" style="opacity:.55;pointer-events:none;">
                                <i class="bi bi-circle"></i>
                                <span>Belum ada kelas</span>
                            </a>
                        </li>
                    @endforelse

                </ul>
            </li>

            {{-- 2. Dropdown: REKAP ABSENSI (Gabungan Hari Ini & Rekap Bulanan) --}}
            @php
                $rekapAbsensiGuruActive = request()->routeIs('guru.absensi.hari-ini') 
                                       || request()->routeIs('guru.absensi.rekap') 
                                       || request()->routeIs('guru.livefeed') 
                                       || request()->routeIs('guru.grafik');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $rekapAbsensiGuruActive ? '' : 'collapsed' }}"
                data-bs-target="#guru-rekap-nav"
                data-bs-toggle="collapse"
                href="#">
                    <i class="bi bi-journal-text icon-presensi"></i>
                    <span>Rekap Absensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="guru-rekap-nav"
                    class="nav-content {{ $rekapAbsensiGuruActive ? 'show' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        {{-- Pastikan route ini sudah terdaftar di web.php --}}
                        <a href="{{ Route::has('guru.absensi.hari-ini') ? route('guru.absensi.hari-ini') : url('guru/absensi/hari-ini') }}"
                           class="{{ isActive('guru.absensi.hari-ini') }}">
                            <i class="bi bi-circle"></i>
                            <span>Absensi Hari Ini</span>
                        </a>
                    </li>
                    <li>
                        {{-- Pastikan route ini sudah terdaftar di web.php --}}
                        <a href="{{ Route::has('guru.absensi.rekap') ? route('guru.absensi.rekap') : url('guru/absensi/rekap') }}"
                           class="{{ isActive('guru.absensi.rekap') }}">
                            <i class="bi bi-circle"></i>
                            <span>Rekap Bulanan</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- 3. Dropdown: KARTU RFID SISWA --}}
            @php
                $rfidGuruActive = request()->routeIs('guru.rfid.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $rfidGuruActive ? '' : 'collapsed' }}"
                data-bs-target="#guru-rfid-nav"
                data-bs-toggle="collapse"
                href="#">
                    <i class="bi bi-credit-card-2-front icon-rfid"></i>
                    <span>Kartu RFID Siswa</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="guru-rfid-nav"
                    class="nav-content {{ $rfidGuruActive ? 'show' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        {{-- Ganti URL/Route dengan route yang tepat nanti --}}
                        <a href="{{ url('guru/rfid/belum-terdaftar') }}"
                           class="{{ isActive('guru.rfid.belum-terdaftar') }}">
                            <i class="bi bi-circle"></i>
                            <span>Belum Terdaftar</span>
                        </a>
                    </li>
                    <li>
                        {{-- Ganti URL/Route dengan route yang tepat nanti --}}
                        <a href="{{ url('guru/rfid/laporan-hilang') }}"
                           class="{{ isActive('guru.rfid.laporan-hilang') }}">
                            <i class="bi bi-circle"></i>
                            <span>Laporan RFID Hilang</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

    </ul>

    <div class="sb-footer">
        <div class="sb-footer-dot"></div>
        <span class="sb-footer-text">Sistem Absensi &bull; Online</span>
    </div>

</aside>