@extends('layouts.app')

@section('content')
    <div class="pagetitle mb-4">
        <h1>Log Notifikasi WhatsApp</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Log Notifikasi WA</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Main Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body pt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title p-0 m-0">Riwayat Pengiriman Pesan</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-nowrap">Waktu</th>
                                        <th scope="col">Siswa</th>
                                        <th scope="col">Target (No. WA)</th>
                                        <th scope="col">Pesan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($notifikasis as $notif)
                                        <tr>
                                            <td class="text-nowrap text-secondary small">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ \Carbon\Carbon::parse($notif->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $notif->siswa->nama ?? 'Siswa Terhapus' }}</div>
                                                <div class="small text-muted">{{ $notif->siswa->kelas->nama ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-secondary">{{ $notif->orangTua->nama ?? '-' }}</div>
                                                <span class="badge bg-light text-success border border-success-subtle mt-1">
                                                    <i class="bi bi-whatsapp me-1"></i>{{ $notif->nomor_whatsapp }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="d-inline-block text-truncate text-secondary" style="max-width: 250px;" title="{{ $notif->pesan }}">
                                                    {{ $notif->pesan }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($notif->status === 'terkirim')
                                                    <span class="badge rounded-pill bg-success"><i class="bi bi-check-all me-1"></i> Terkirim</span>
                                                @elseif($notif->status === 'gagal')
                                                    <span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle me-1"></i> Gagal</span>
                                                @else
                                                    <span class="badge rounded-pill bg-warning text-dark"><i class="bi bi-clock me-1"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group shadow-sm" role="group">
                                                    <!-- Tombol Detail memanggil Modal lewat AJAX -->
                                                    <button type="button" 
                                                            class="btn btn-sm btn-light text-info btn-detail border" 
                                                            data-url="{{ route('notifikasiwa.show', $notif->id) }}"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    
                                                    <form action="{{ route('notifikasiwa.destroy', $notif->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log notifikasi ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-light text-danger border" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Log">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                                    <i class="bi bi-chat-square-text display-4 mb-3 opacity-50"></i>
                                                    <h6 class="fw-semibold">Belum ada log notifikasi WhatsApp</h6>
                                                    <span class="small">Riwayat pesan yang dikirim akan otomatis muncul di sini.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-4">
                            {{ $notifikasis->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Detail Notifikasi -->
    <div class="modal fade" id="detailNotifModal" tabindex="-1" aria-labelledby="detailNotifModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark" id="detailNotifModalLabel">
                        <i class="bi bi-whatsapp text-success me-2"></i>Detail Notifikasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Loading Spinner -->
                    <div id="modalLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-secondary fw-semibold">Mengambil data...</p>
                    </div>

                    <!-- Detail Content -->
                    <div id="modalContent" class="d-none">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm h-100">
                                    <small class="text-muted d-block mb-1">Status Pengiriman</small>
                                    <div id="dt-status" class="mb-3"></div>
                                    
                                    <small class="text-muted d-block mb-1">Waktu Dibuat</small>
                                    <div id="dt-waktu" class="fw-semibold text-dark mb-3"></div>

                                    <small class="text-muted d-block mb-1">Waktu Terkirim</small>
                                    <div id="dt-waktu-kirim" class="fw-semibold text-dark mb-3"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm h-100">
                                    <small class="text-muted d-block mb-1">Siswa / Kelas</small>
                                    <div id="dt-siswa" class="fw-semibold text-dark mb-3"></div>

                                    <small class="text-muted d-block mb-1">Orang Tua</small>
                                    <div id="dt-ortu" class="fw-semibold text-dark mb-3"></div>

                                    <small class="text-muted d-block mb-1">Nomor WhatsApp Target</small>
                                    <div id="dt-nomor" class="fw-bold text-primary"></div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="p-3 border rounded bg-light">
                                    <small class="text-muted fw-bold d-block mb-2"><i class="bi bi-chat-left-text me-2"></i>Pesan Dikirim</small>
                                    <div class="text-dark" style="white-space: pre-wrap; font-size: 0.95rem;" id="dt-pesan"></div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="p-3 border rounded bg-dark text-light shadow-sm">
                                    <small class="text-secondary fw-bold d-block mb-2"><i class="bi bi-terminal me-2"></i>Response Gateway (Fonnte)</small>
                                    <pre class="mb-0 text-success" id="dt-response" style="font-size: 0.85rem; max-height: 250px; overflow-y: auto;"></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi Tooltip Bootstrap (jika digunakan di NiceAdmin)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Handle klik tombol detail
        $('.btn-detail').on('click', function() {
            let url = $(this).data('url');
            let modal = $('#detailNotifModal');
            
            // Tampilkan modal dan reset UI
            modal.modal('show');
            $('#modalLoading').removeClass('d-none');
            $('#modalContent').addClass('d-none');

            // Ambil data via AJAX
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if(response.success) {
                        let data = response.data;
                        
                        // Set text data dasar
                        $('#dt-waktu').text(new Date(data.created_at).toLocaleString('id-ID'));
                        $('#dt-waktu-kirim').text(data.dikirim_pada ? new Date(data.dikirim_pada).toLocaleString('id-ID') : '-');
                        $('#dt-pesan').text(data.pesan);
                        $('#dt-nomor').text(data.nomor_whatsapp);
                        
                        // Set data Siswa & Ortu
                        let namaSiswa = data.siswa ? data.siswa.nama : 'Siswa Terhapus';
                        let namaKelas = (data.siswa && data.siswa.kelas) ? data.siswa.kelas.nama : '-';
                        $('#dt-siswa').text(namaSiswa + ' (Kelas: ' + namaKelas + ')');
                        $('#dt-ortu').text(data.orang_tua ? data.orang_tua.nama : '-');

                        // Styling Status
                        let statusHtml = '';
                        if(data.status === 'terkirim') {
                            statusHtml = '<span class="badge rounded-pill bg-success"><i class="bi bi-check-all me-1"></i> Terkirim</span>';
                        } else if(data.status === 'gagal') {
                            statusHtml = '<span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle me-1"></i> Gagal</span>';
                        } else {
                            statusHtml = '<span class="badge rounded-pill bg-warning text-dark"><i class="bi bi-clock me-1"></i> Pending</span>';
                        }
                        $('#dt-status').html(statusHtml);

                        // Set Response JSON Fonnte
                        let responseText = data.response_gateway;
                        try {
                            let parsed = typeof data.response_gateway === 'string' ? JSON.parse(data.response_gateway) : data.response_gateway;
                            responseText = JSON.stringify(parsed, null, 4);
                        } catch(e) {
                            // Biarkan jika bukan JSON
                        }
                        $('#dt-response').text(responseText || 'Tidak ada response dari server gateway.');

                        // Sembunyikan loading, tampilkan konten
                        $('#modalLoading').addClass('d-none');
                        $('#modalContent').removeClass('d-none');
                    }
                },
                error: function(xhr) {
                    $('#modalLoading').addClass('d-none');
                    alert('Gagal mengambil detail data! Pastikan koneksi internet Anda stabil.');
                    modal.modal('hide');
                }
            });
        });
    });
</script>
@endpush