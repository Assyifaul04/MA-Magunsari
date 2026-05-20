@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Template WhatsApp</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Template WA</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                <!-- Pesan Sukses -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Pesan Info (Untuk info Generate Default) -->
                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Pesan Error Validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Terjadi Kesalahan!</strong>
                        </div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                            <h5 class="card-title p-0 m-0"><i class="bi bi-chat-square-text text-primary me-2"></i>Manajemen Pesan</h5>
                            
                            <div class="d-flex gap-2">
                                <form action="{{ route('templatewa.generate') }}" method="POST" onsubmit="return confirm('Sistem akan membuat otomatis template untuk jenis absensi yang kosong. Lanjutkan?');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning rounded-pill shadow-sm text-dark px-3">
                                        <i class="bi bi-magic me-1"></i> Default
                                    </button>
                                </form>

                                <button type="button" class="btn btn-primary rounded-pill shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#tambahTemplateModal">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-top">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="20%">Nama Template</th>
                                        <th width="12%">Jenis Absen</th>
                                        <th width="38%">Isi Pesan (Preview)</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($templates as $index => $template)
                                        <tr>
                                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <strong class="text-dark d-block">{{ $template->nama_template }}</strong>
                                                <small class="text-muted">ID: #{{ $template->id }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary rounded-pill text-uppercase px-3 py-2"><i class="bi bi-tag-fill me-1"></i>{{ $template->jenis }}</span>
                                            </td>
                                            <td>
                                                <!-- Desain Chat Bubble untuk Preview WA -->
                                                <div class="p-2 bg-light border rounded-3 text-dark shadow-sm" style="max-height: 90px; overflow-y: auto; font-size: 0.85rem; white-space: pre-wrap; border-bottom-right-radius: 0 !important;">
                                                    {{ $template->isi_pesan }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($template->is_active)
                                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                                                @else
                                                    <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group shadow-sm" role="group">
                                                    <!-- Tombol Edit (Trigger AJAX Modal) -->
                                                    <button type="button" 
                                                            class="btn btn-sm btn-light text-primary border btn-edit" 
                                                            data-url="{{ route('templatewa.edit', $template->id) }}"
                                                            data-update-url="{{ route('templatewa.update', $template->id) }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Edit Template">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    
                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('templatewa.destroy', $template->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus template ini? Tindakan ini tidak dapat dibatalkan.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light text-danger border" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Template">
                                                            <i class="bi bi-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <i class="bi bi-whatsapp display-4 text-secondary mb-3 opacity-50"></i>
                                                    <h5 class="fw-bold">Belum ada template WhatsApp</h5>
                                                    <p class="mb-0 text-muted">Silakan tambah secara manual atau gunakan tombol <strong>Generate Default</strong>.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== MODAL TAMBAH TEMPLATE ==================== -->
    <div class="modal fade" id="tambahTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-whatsapp me-2"></i>Tambah Template WA</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('templatewa.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-bold">Nama Template <span class="text-danger">*</span></label>
                                <input type="text" name="nama_template" class="form-control" required placeholder="Contoh: Info Masuk Pagi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-bold">Jenis Absensi <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih Jenis --</option>
                                    <option value="masuk">Masuk</option>
                                    <option value="pulang">Pulang</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="terlambat">Terlambat</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Isi Pesan <span class="text-danger">*</span></label>
                            <textarea name="isi_pesan" class="form-control" rows="5" required placeholder="Ketik format pesan WhatsApp di sini..."></textarea>
                            
                            <!-- Bantuan Variabel Berdesain Callout -->
                            <div class="bg-light p-3 rounded border-start border-4 border-info mt-3 shadow-sm">
                                <p class="mb-2 fw-bold text-dark"><i class="bi bi-info-circle text-info me-1"></i> Variabel Otomatis</p>
                                <div class="row text-muted" style="font-size: 0.85rem;">
                                    <div class="col-sm-6">
                                        <ul class="mb-1">
                                            <li><code>{nama_siswa}</code> : Nama Siswa</li>
                                            <li><code>{kelas}</code> : Kelas</li>
                                            <li><code>{status}</code> : Status Kehadiran</li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6">
                                        <ul class="mb-0">
                                            <li><code>{tanggal}</code> : Tgl (ex: 21-05-2026)</li>
                                            <li><code>{jam}</code> : Jam (ex: 07:15)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check form-switch mt-4 bg-light p-3 rounded">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" id="isActiveTambah" value="1" checked style="width: 2.5em; height: 1.2em; cursor:pointer;">
                            <label class="form-check-label fw-bold text-dark pt-1" for="isActiveTambah" style="cursor:pointer;">&nbsp; Aktifkan Template Ini Segera</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send-check me-1"></i> Simpan Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL EDIT TEMPLATE ==================== -->
    <div class="modal fade" id="editTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Template WA</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Action form diisi secara dinamis oleh JavaScript -->
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <!-- Indikator Loading -->
                        <div id="editLoading" class="text-center py-5 d-none">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                            <p class="mt-3 fw-bold text-primary">Mengambil data...</p>
                        </div>

                        <!-- Form Input Edit -->
                        <div id="editFormContent">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Nama Template <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_template" id="edit_nama" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Jenis Absensi <span class="text-danger">*</span></label>
                                    <select name="jenis" id="edit_jenis" class="form-select" required>
                                        <option value="masuk">Masuk</option>
                                        <option value="pulang">Pulang</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="terlambat">Terlambat</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Isi Pesan <span class="text-danger">*</span></label>
                                <textarea name="isi_pesan" id="edit_pesan" class="form-control" rows="5" required></textarea>
                                
                                <!-- Bantuan Variabel -->
                                <div class="bg-light p-3 rounded border-start border-4 border-info mt-3 shadow-sm">
                                    <p class="mb-2 fw-bold text-dark"><i class="bi bi-info-circle text-info me-1"></i> Variabel Otomatis</p>
                                    <div class="row text-muted" style="font-size: 0.85rem;">
                                        <div class="col-sm-6">
                                            <ul class="mb-1">
                                                <li><code>{nama_siswa}</code> : Nama Siswa</li>
                                                <li><code>{kelas}</code> : Kelas</li>
                                                <li><code>{status}</code> : Status Kehadiran</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <ul class="mb-0">
                                                <li><code>{tanggal}</code> : Tgl (ex: 21-05-2026)</li>
                                                <li><code>{jam}</code> : Jam (ex: 07:15)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch mt-4 bg-light p-3 rounded">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" id="edit_is_active" value="1" style="width: 2.5em; height: 1.2em; cursor:pointer;">
                                <label class="form-check-label fw-bold text-dark pt-1" for="edit_is_active" style="cursor:pointer;">&nbsp; Aktifkan Template Ini</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnUpdate"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Aktifkan Bootstrap Tooltip jika tersedia (opsional untuk mempercantik)
        if(typeof bootstrap !== 'undefined'){
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }

        // Event listener saat tombol Edit diklik
        $('.btn-edit').on('click', function() {
            let fetchUrl = $(this).data('url'); // Rute route('templatewa.edit', $id)
            let updateUrl = $(this).data('update-url'); // Rute route('templatewa.update', $id)
            let modal = $('#editTemplateModal');

            // Set tujuan rute form PUT ke updateUrl yang benar
            $('#editForm').attr('action', updateUrl);
            
            // Tampilkan Modal, sembunyikan form, dan tampilkan animasi loading
            modal.modal('show');
            $('#editFormContent').addClass('d-none');
            $('#editLoading').removeClass('d-none');
            $('#btnUpdate').prop('disabled', true); // Matikan tombol update selama loading

            // Ambil data template dari server menggunakan AJAX (GET)
            $.ajax({
                url: fetchUrl,
                type: 'GET',
                success: function(response) {
                    if(response.success) {
                        let data = response.data;

                        // Tembakkan data dari database ke dalam input HTML form edit
                        $('#edit_nama').val(data.nama_template);
                        $('#edit_jenis').val(data.jenis);
                        $('#edit_pesan').val(data.isi_pesan);
                        
                        // Logika untuk mencentang checkbox otomatis jika statusnya aktif
                        if(data.is_active == 1 || data.is_active == true) {
                            $('#edit_is_active').prop('checked', true);
                        } else {
                            $('#edit_is_active').prop('checked', false);
                        }

                        // Selesai diload: Sembunyikan animasi loading, tampilkan form
                        $('#editLoading').addClass('d-none');
                        $('#editFormContent').removeClass('d-none');
                        $('#btnUpdate').prop('disabled', false); // Aktifkan tombol update
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan! Gagal mengambil data template dari server.');
                    modal.modal('hide');
                }
            });
        });
    });
</script>
@endpush