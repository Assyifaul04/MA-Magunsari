@extends('layouts.app')

@section('content')
    <div class="pagetitle mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="mb-1">Absensi Harian</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Absensi</li>
                        <li class="breadcrumb-item active">Hari Ini</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card mb-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">

                {{-- Scan RFID --}}
                <div class="d-flex align-items-center gap-3 flex-grow-1 min-w-0">
                    <div class="me-2">
                        <i class="bi bi-credit-card fs-2 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold mb-1">Scan RFID untuk mencatat absen</div>
                        <div class="text-muted small">
                            Tempelkan kartu RFID, sistem otomatis mencatat masuk/pulang dan memperbarui tampilan.
                        </div>
                    </div>
                    <div class="position-relative" style="min-width: 260px;">
                        <div class="input-group shadow-sm border border-primary-subtle rounded-pill overflow-hidden">
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-upc-scan text-primary"></i>
                            </span>
                            <input type="text" id="rfid_input_global" class="form-control border-0 form-control-lg ps-0"
                                placeholder="Tempelkan kartu RFID…" autofocus autocomplete="off" aria-label="Input RFID"
                                style="background-color: #fdfdfd;">
                            <span class="input-group-text bg-white border-0 px-2" id="rfid-spinner" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"
                                    aria-hidden="true"></div>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Dropdown Filter Data Absensi --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary d-flex align-items-center" type="button" id="filterDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel-fill me-1"></i> Pengaturan Filter Data <i
                            class="bi bi-caret-down-fill ms-1"></i>
                    </button>
                    <div class="dropdown-menu p-3 shadow" style="min-width: 380px; max-height: 400px; overflow-y: auto;"
                        aria-labelledby="filterDropdown">

                        <form id="filter-data-form" method="GET" action="{{ route('absensi.index') }}" class="row g-2">
                            {{-- Filter Tanggal --}}
                            <div class="col-12">
                                <label for="tanggal" class="form-label small mb-1">Tanggal</label>
                                <select name="tanggal" id="tanggal" class="form-select form-select-sm">
                                    <option value="hari_ini" {{ $tanggalFilter == 'hari_ini' ? 'selected' : '' }}>Hari Ini
                                    </option>
                                    <option value="kemarin" {{ $tanggalFilter == 'kemarin' ? 'selected' : '' }}>Kemarin
                                    </option>
                                    <option value="7_hari" {{ $tanggalFilter == '7_hari' ? 'selected' : '' }}>7 Hari
                                        Terakhir</option>
                                    <option value="1_bulan" {{ $tanggalFilter == '1_bulan' ? 'selected' : '' }}>1 Bulan
                                        Terakhir</option>
                                </select>
                            </div>

                            {{-- Filter Kelas --}}
                            <div class="col-12">
                                <label for="kelas" class="form-label small mb-1">Kelas</label>
                                <select name="kelas" id="kelas" class="form-select form-select-sm">
                                    <option value="">Semua Kelas</option>
                                    <option value="XA" {{ $kelas == 'XA' ? 'selected' : '' }}>XA</option>
                                    <option value="XB" {{ $kelas == 'XB' ? 'selected' : '' }}>XB</option>
                                    <option value="XIA" {{ $kelas == 'XIA' ? 'selected' : '' }}>XIA</option>
                                    <option value="XIB" {{ $kelas == 'XIB' ? 'selected' : '' }}>XIB</option>
                                    <option value="XIIA" {{ $kelas == 'XIIA' ? 'selected' : '' }}>XIIA</option>
                                    <option value="XIIB" {{ $kelas == 'XIIB' ? 'selected' : '' }}>XIIB</option>
                                </select>
                            </div>

                            {{-- Filter Nama --}}
                            <div class="col-12">
                                <label for="nama" class="form-label small mb-1">Nama</label>
                                <input type="text" name="nama" id="nama" placeholder="Cari nama..."
                                    class="form-control form-control-sm" value="{{ $nama ?? '' }}">
                            </div>

                            {{-- Filter Keterangan --}}
                            <div class="col-12">
                                <label for="keterangan" class="form-label small mb-1">Keterangan</label>
                                <select name="keterangan" id="keterangan" class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option value="hadir" {{ $keterangan === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="terlambat" {{ $keterangan === 'terlambat' ? 'selected' : '' }}>Terlambat
                                    </option>
                                    <option value="izin" {{ $keterangan === 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="alfa" {{ $keterangan === 'alfa' ? 'selected' : '' }}>Alfa</option>
                                </select>
                            </div>

                            {{-- Tombol submit filter data --}}
                            <div class="col-12 d-flex gap-2 mt-2">
                                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                                    <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter Data
                                </button>
                                <a href="{{ route('absensi.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Reset Filter
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Dropdown Pengaturan Jam Masuk/Pulang --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary d-flex align-items-center" type="button" id="jamDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-clock-fill me-1"></i> Pengaturan Jam Masuk/Pulang <i
                            class="bi bi-caret-down-fill ms-1"></i>
                    </button>
                    <div class="dropdown-menu p-3 shadow" style="min-width: 360px;" aria-labelledby="jamDropdown">
                        <form id="filter-jam-form" method="GET" action="{{ route('absensi.index') }}"
                            class="row g-3">

                            {{-- Batasan Jam Masuk --}}
                            <div class="col-6">
                                <label for="masuk_from" class="form-label small mb-1">Masuk dari</label>
                                <input type="time" name="masuk_from" id="masuk_from"
                                    class="form-control form-control-sm" value="{{ old('masuk_from', $masuk_from) }}">
                            </div>
                            <div class="col-6">
                                <label for="masuk_to" class="form-label small mb-1">Masuk sampai</label>
                                <input type="time" name="masuk_to" id="masuk_to"
                                    class="form-control form-control-sm" value="{{ old('masuk_to', $masuk_to) }}">
                            </div>

                            {{-- Batasan Jam Pulang --}}
                            <div class="col-6">
                                <label for="pulang_from" class="form-label small mb-1">Pulang dari</label>
                                <input type="time" name="pulang_from" id="pulang_from"
                                    class="form-control form-control-sm" value="{{ old('pulang_from', $pulang_from) }}">
                            </div>
                            <div class="col-6">
                                <label for="pulang_to" class="form-label small mb-1">Pulang sampai</label>
                                <input type="time" name="pulang_to" id="pulang_to"
                                    class="form-control form-control-sm" value="{{ old('pulang_to', $pulang_to) }}">
                            </div>

                            {{-- Tombol submit pengaturan jam --}}
                            <div class="col-12 d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                                    Terapkan Batasan Jam
                                </button>
                                <a href="{{ route('absensi.reset_jam') }}" class="btn btn-sm btn-outline-secondary">
                                    Reset Jam
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>


        {{-- Tabel absensi --}}
        <div class="card">
            <div class="card-body position-relative">
                <h5 class="card-title mb-3">Daftar Absensi: <span
                        class="text-primary">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span></h5>
                {{-- Tampilkan filter aktif --}}
                @if ($masuk_from || $masuk_to || $pulang_from || $pulang_to)
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-clock me-2"></i>
                        <div>
                            <strong>Filter Waktu Diterapkan:</strong>
                            <ul class="mb-0">
                                @if ($masuk_from || $masuk_to)
                                    <li>
                                        <strong>Jam Masuk:</strong>
                                        {{ $masuk_from ? 'Dari ' . $masuk_from : '' }}
                                        {{ $masuk_from && $masuk_to ? ' - ' : '' }}
                                        {{ $masuk_to ? 'Sampai ' . $masuk_to : '' }}
                                    </li>
                                @endif
                                @if ($pulang_from || $pulang_to)
                                    <li>
                                        <strong>Jam Pulang:</strong>
                                        {{ $pulang_from ? 'Dari ' . $pulang_from : '' }}
                                        {{ $pulang_from && $pulang_to ? ' - ' : '' }}
                                        {{ $pulang_to ? 'Sampai ' . $pulang_to : '' }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="absensiTable" class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach ($absensis as $absen)
                                <tr id="row-{{ $absen->siswa->id }}" data-rfid="{{ $absen->siswa->rfid_uid ?? '' }}">
                                    <td><span class="badge bg-secondary">{{ $counter++ }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-primary-light rounded-circle fs-6">
                                                    {{ strtoupper(substr($absen->siswa->nama, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $absen->siswa->nama }}</div>
                                                <small class="text-muted">ID: {{ $absen->siswa->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info-light text-info">{{ $absen->siswa->kelas }}</span></td>
                                    <td class="waktu-masuk">
                                        {{ $absen->waktu_masuk ? \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i:s') : '-' }}
                                    </td>
                                    <td class="waktu-pulang">
                                        {{ $absen->waktu_pulang ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i:s') : '-' }}
                                    </td>
                                    <td class="keterangan">
                                        @if ($absen->keterangan)
                                            @php
                                                $badgeClass = match ($absen->keterangan) {
                                                    'terlambat' => 'bg-warning',
                                                    'izin' => 'bg-primary',
                                                    'alfa' => 'bg-danger',
                                                    default => 'bg-success',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $badgeClass }}">{{ ucfirst($absen->keterangan) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="status">
                                        @php
                                            $jamPulang = $absen->waktu_pulang
                                                ? \Carbon\Carbon::parse($absen->waktu_pulang)
                                                : null;
                                            $isJamPulangValid =
                                                $jamPulang &&
                                                $jamPulang->between(
                                                    \Carbon\Carbon::createFromTime(13, 0),
                                                    \Carbon\Carbon::createFromTime(15, 0),
                                                );
                                        @endphp

                                        @if ($absen->waktu_masuk && $absen->waktu_pulang && $isJamPulangValid)
                                            <span class="badge bg-success"><i
                                                    class="bi bi-check-circle me-1"></i>Lengkap</span>
                                        @elseif ($absen->waktu_masuk && $absen->waktu_pulang)
                                            <span class="badge bg-warning"><i
                                                    class="bi bi-exclamation-circle me-1"></i>Tidak Lengkap</span>
                                        @elseif ($absen->waktu_masuk)
                                            <span class="badge bg-info"><i
                                                    class="bi bi-arrow-right-circle me-1"></i>Masuk</span>
                                        @else
                                            <span class="badge bg-secondary">Belum</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- siswa tanpa absensi --}}
                            @foreach ($siswas as $siswa)
                                <tr id="row-{{ $siswa->id }}" data-rfid="{{ $siswa->rfid_uid ?? '' }}">
                                    <td><span class="badge bg-secondary">{{ $counter++ }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-primary-light rounded-circle fs-6">
                                                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $siswa->nama }}</div>
                                                <small class="text-muted">ID: {{ $siswa->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info-light text-info">{{ $siswa->kelas }}</span></td>
                                    <td class="waktu-masuk">-</td>
                                    <td class="waktu-pulang">-</td>
                                    <td class="keterangan">-</td>
                                    <td class="status">
                                        <span class="badge bg-secondary">Belum</span>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($absensis->isEmpty() && $siswas->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data untuk filter
                                        yang dipilih.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- Toast --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:1055;">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="bi bi-check-circle me-2"></i>
                    <div><span id="toast-message">Berhasil!</span></div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Pastikan bootstrap JS sudah ter-include di layout utama --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const highlightClass = 'table-success';

        function showToast(message, isSuccess = true) {
            const toastEl = document.getElementById('liveToast');
            const toastMessage = document.getElementById('toast-message');

            toastEl.classList.remove('text-bg-success', 'text-bg-danger');
            toastEl.classList.add(isSuccess ? 'text-bg-success' : 'text-bg-danger');
            toastMessage.innerText = message;

            const toastInstance = new bootstrap.Toast(toastEl);
            toastInstance.show();
        }

        function buildRow(siswa, absen = null, index) {
            const initial = (siswa.nama || '').charAt(0).toUpperCase();
            const hadirBadge = absen?.waktu_masuk && absen?.waktu_pulang ?
                '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lengkap</span>' :
                absen?.waktu_masuk ?
                '<span class="badge bg-info"><i class="bi bi-arrow-right-circle me-1"></i>Masuk</span>' :
                '<span class="badge bg-secondary">Belum</span>';

            let keteranganHtml = '-';
            if (absen?.keterangan) {
                let badgeClass = 'bg-success';
                if (absen.keterangan === 'terlambat') badgeClass = 'bg-warning';
                if (absen.keterangan === 'izin') badgeClass = 'bg-primary';
                if (absen.keterangan === 'alfa') badgeClass = 'bg-danger';
                keteranganHtml =
                    `<span class="badge ${badgeClass}">${absen.keterangan.charAt(0).toUpperCase() + absen.keterangan.slice(1)}</span>`;
            }

            return `
                <tr id="row-${siswa.id}" data-rfid="${siswa.rfid_uid || ''}">
                    <td><span class="badge bg-secondary">${index}</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-2">
                                <div class="avatar-title bg-primary-light rounded-circle fs-6">
                                    ${initial}
                                </div>
                            </div>
                            <div>
                                <div class="fw-semibold">${siswa.nama}</div>
                                <small class="text-muted">ID: ${siswa.id}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-info-light text-info">${siswa.kelas || ''}</span></td>
                    <td class="waktu-masuk">${absen?.waktu_masuk || '-'}</td>
                    <td class="waktu-pulang">${absen?.waktu_pulang || '-'}</td>
                    <td class="keterangan">${keteranganHtml}</td>
                    <td class="status">${hadirBadge}</td>
                </tr>
            `;
        }

        function updateRow(absenData) {
            const siswaId = absenData.siswa_id;
            const row = $(`#row-${siswaId}`);
            if (row.length) {
                // Update existing row
                if (absenData.waktu_masuk) row.find('.waktu-masuk').text(absenData.waktu_masuk);
                if (absenData.waktu_pulang) row.find('.waktu-pulang').text(absenData.waktu_pulang);

                // keterangan
                let ketHtml = '-';
                if (absenData.keterangan) {
                    let badgeClass = 'bg-success';
                    if (absenData.keterangan === 'terlambat') badgeClass = 'bg-warning';
                    if (absenData.keterangan === 'izin') badgeClass = 'bg-primary';
                    if (absenData.keterangan === 'alfa') badgeClass = 'bg-danger';
                    ketHtml =
                        `<span class="badge ${badgeClass}">${absenData.keterangan.charAt(0).toUpperCase() + absenData.keterangan.slice(1)}</span>`;
                }
                row.find('.keterangan').html(ketHtml);

                // status
                let statusBadge = '<span class="badge bg-secondary">Belum</span>';
                if (absenData.waktu_masuk && absenData.waktu_pulang) {
                    statusBadge = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lengkap</span>';
                } else if (absenData.waktu_masuk) {
                    statusBadge = '<span class="badge bg-info"><i class="bi bi-arrow-right-circle me-1"></i>Masuk</span>';
                }
                row.find('.status').html(statusBadge);

                flashRow(row);
            } else {
                // Jika belum ada, fetch siswa + absensi dan prepend
                fetchAbsensiRowById(absenData);
            }
        }

        function flashRow($row) {
            $row.addClass(highlightClass);
            setTimeout(() => {
                $row.removeClass(highlightClass);
            }, 1200);
        }

        function fetchAbsensiRowById(absenData) {
            // coba ambil detail siswa dan absensi via lookup (asumsi ada endpoint yang memberi siswa + absensi)
            $.getJSON("{{ route('absensi.lookup') }}", {
                rfid_uid: '', // tidak punya rfid langsung, akan fallback ambil siswa dari absenData.siswa_id lewat endpoint custom jika perlu
                tanggal: '{{ $tanggal }}',
                siswa_id: absenData
                    .siswa_id // kalau kamu menambahkan param ini di controller untuk lookup lebih fleksibel
            }, function(data) {
                if (data.success) {
                    // data.absensi mungkin null, kita butuh objek siswa
                    // Asumsi response ditingkatkan: return siswa juga. Jika belum, kamu bisa tambah endpoint kecil /api/siswa/{id}
                    const siswa = data.siswa || {
                        id: absenData.siswa_id,
                        nama: 'Unknown',
                        kelas: ''
                    };
                    const absensi = data.absensi || absenData;
                    const nextIndex = $('#absensiTable tbody tr').length + 1;
                    $('#absensiTable tbody').prepend(buildRow(siswa, absensi, nextIndex));
                    flashRow($(`#row-${siswa.id}`));
                }
            });
        }

        $(function() {
            // Export
            $('#btn-export').click(function() {
                const params = new URLSearchParams({
                    start: '{{ $tanggal }}',
                    end: '{{ $tanggal }}',
                });
                window.location.href = "{{ route('absensi.export') }}" + '?' + params.toString();
            });

            // Close dropdown after submit
            $('#filter-form').on('submit', function() {
                const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('filterDropdown'));
                if (dropdown) dropdown.hide();
            });

            // RFID input handling
            let debounceTimer = null;
            const $input = $('#rfid_input_global');
            const $spinner = $('#rfid-spinner');

            $input.on('input', function() {
                clearTimeout(debounceTimer);
                const uid = $(this).val().trim();
                if (uid.length < 3) return;
                debounceTimer = setTimeout(() => {
                    submitRfid(uid);
                    $input.val('');
                }, 250);
            });

            function submitRfid(uid) {
                const tanggal = $('#tanggal').val();
                const masukFrom = $('#masuk_from').val();
                const pulangFrom = $('#pulang_from').val();

                if (!tanggal || (!masukFrom && !pulangFrom)) {
                    showToast('Harap pilih tanggal dan atur jam masuk/pulang terlebih dahulu.', false);
                    return;
                }

                $spinner.show();

                $.ajax({
                    url: "{{ route('absensi.scanRfid') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rfid_uid: uid,
                        tanggal: tanggal,
                        masuk_from: masukFrom,
                        masuk_to: $('#masuk_to').val(),
                        pulang_from: pulangFrom,
                        pulang_to: $('#pulang_to').val()
                    },
                    success: function(res) {
                        showToast(res.message, true);
                        if (res.absensi) {
                            updateRow(res.absensi);
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Gagal scan RFID.';
                        if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
                        showToast(msg, false);
                    },
                    complete: function() {
                        $spinner.hide();
                        $input.focus();
                    }
                });
            }

            // keyboard shortcut: fokus RFID input dengan F2
            $(document).on('keydown', function(e) {
                if (e.key === 'F2') {
                    e.preventDefault();
                    $input.focus();
                }
            });
        });
    </script>
@endpush
