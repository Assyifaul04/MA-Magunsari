@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Data Siswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active">Data Siswa</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Daftar Siswa</h5>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-list fs-4"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('siswa.create') }}">
                                                <i class="bi bi-person-plus me-2"></i> Tambah Siswa
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="document.getElementById('file').click(); return false;">
                                                <i class="bi bi-file-earmark-excel me-2"></i> Import Excel
                                            </a>
                                        </li>
                                    </ul>
                            
                                    <!-- Form Import Excel -->
                                    <form id="form-import" action="{{ route('siswa.import') }}" method="POST"
                                        enctype="multipart/form-data" class="d-none">
                                        @csrf
                                        <input type="file" name="file" id="file" accept=".xlsx,.xls" required>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="siswaTable" class="table table-hover datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th><i class="bi bi-person-circle me-1"></i>Nama</th>
                                        <th><i class="bi bi-house-door me-1"></i>Kelas</th>
                                        <th><i class="bi bi-credit-card me-1"></i>RFID UID</th>
                                        <th><i class="bi bi-calendar3 me-1"></i>Status</th>
                                        <th><i class="bi bi-gear me-1"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswas as $idx => $siswa)
                                        <tr data-kelas="{{ $siswa->kelas }}"
                                            data-rfid="{{ $siswa->rfid_uid ? 'ada' : 'kosong' }}">
                                            <td><span class="badge bg-secondary">{{ $idx + 1 }}</span></td>
                                            <td>
                                                <button class="btn btn-link p-0 text-start btn-rfid"
                                                    data-id="{{ $siswa->id }}" data-bs-toggle="tooltip"
                                                    title="Atur RFID">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-2">
                                                            <div class="avatar-title bg-primary-light rounded-circle">
                                                                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold text-primary">{{ $siswa->nama }}</div>
                                                            <small class="text-muted">ID: {{ $siswa->id }}</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </td>
                                            <td><span class="badge bg-info-light text-info">{{ $siswa->kelas }}</span>
                                            </td>
                                            <td class="rfid-col" data-id="{{ $siswa->id }}">
                                                @if ($siswa->rfid_uid)
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-credit-card text-success me-2"></i>
                                                        <code class="text-success">{{ $siswa->rfid_uid }}</code>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-credit-card-2-front text-muted me-2"></i>
                                                        <span class="text-muted">Belum diatur</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($siswa->rfid_uid)
                                                    <span class="badge bg-success"><i
                                                            class="bi bi-check-circle me-1"></i>Aktif</span>
                                                @else
                                                    <span class="badge bg-warning"><i
                                                            class="bi bi-exclamation-triangle me-1"></i>Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-outline-primary btn-sm btn-rfid"
                                                        data-id="{{ $siswa->id }}" data-bs-toggle="tooltip"
                                                        title="Atur RFID">
                                                        <i class="bi bi-credit-card"></i>
                                                    </button>
                                                    <a href="{{ route('siswa.edit', $siswa->id) }}"
                                                        class="btn btn-outline-warning btn-sm" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="confirmDelete(this)" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST"
                                                        class="d-none">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Modal RFID tetap disertakan --}}
    <div class="modal fade" id="rfidModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-credit-card me-2"></i>Pengaturan RFID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-primary-light rounded-circle">
                                <i class="bi bi-credit-card display-6 text-primary"></i>
                            </div>
                        </div>
                        <h6 class="modal-student-name fw-bold">Nama Siswa</h6>
                        <p class="text-muted">Tempelkan kartu RFID pada reader</p>
                    </div>
                    <input type="hidden" id="rfid_siswa_id">
                    <div class="mb-3">
                        <label class="form-label">RFID UID</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                            <input type="text" id="rfid_input" class="form-control form-control-lg"
                                placeholder="Tempelkan kartu RFID…" autofocus>
                        </div>
                        <div class="form-text"><i class="bi bi-info-circle me-1"></i>Kartu akan terdeteksi otomatis saat
                            ditempelkan</div>
                    </div>
                    <div id="rfid-status" class="alert alert-info d-none">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm me-2" role="status"><span
                                    class="visually-hidden">Loading...</span></div>
                            <span>Menyimpan data RFID…</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-1"></i>Batal</button>
                    <button type="button" class="btn btn-danger" id="btn-clear-rfid"><i
                            class="bi bi-trash me-1"></i>Hapus RFID</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast Container --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i><span id="toast-message">Berhasil!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function showToast(message, isSuccess = true) {
        const toastEl = document.getElementById('liveToast');
        const toastMessage = document.getElementById('toast-message');

        toastEl.classList.remove('text-bg-success', 'text-bg-danger');
        toastEl.classList.add(isSuccess ? 'text-bg-success' : 'text-bg-danger');
        toastMessage.innerText = message;

        const toast = new bootstrap.Toast(toastEl);
        toast.show();

        // Reload setelah toast selesai
        setTimeout(() => {
            location.reload();
        }, 2000);
    }

    $(document).ready(function () {
        // Trigger form import setelah pilih file
        $('#file').change(function () {
            if (this.files.length > 0) {
                $('#form-import').submit();
            }
        });

        // Import Excel AJAX
        $('#form-import').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function () {
                    showToast('Import berhasil!');
                },
                error: function () {
                    showToast('Gagal import file Excel.', false);
                }
            });
        });

        // RFID modal buka
        $('.btn-rfid').click(function () {
            let id = $(this).data('id');
            $('#rfid_siswa_id').val(id);
            $('#rfid_input').val('');
            $('#rfidModal').modal('show');

            setTimeout(() => {
                $('#rfid_input').focus();
            }, 500);
        });

        // Simpan RFID otomatis
        $('#rfid_input').on('input', function () {
            let uid = $(this).val();
            let id = $('#rfid_siswa_id').val();

            if (uid.length >= 8) {
                $('#rfid-status').removeClass('d-none');
                $.ajax({
                    url: `/admin/siswa/${id}`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rfid_uid: uid
                    },
                    success: function () {
                        $('#rfidModal').modal('hide');
                        showToast('RFID berhasil disimpan!');
                    },
                    error: function (xhr) {
                        $('#rfid-status').addClass('d-none');
                        let msg = 'Gagal menyimpan RFID.';
                        if (xhr.responseJSON?.errors?.rfid_uid) {
                            msg += ' ' + xhr.responseJSON.errors.rfid_uid[0];
                        }
                        showToast(msg, false);
                    }
                });
            }
        });
    });
</script>
@endpush

