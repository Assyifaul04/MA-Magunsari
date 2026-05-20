@extends('layouts.app')

@section('content')

<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="pagetitle">
    <h1>Data Orang Tua</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i> Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Orang Tua</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 p-0">Daftar Orang Tua</h5>
                    <button class="btn btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Data
                    </button>
                </div>
                
                <div class="card-body pt-3">
                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" width="25%">Nama</th>
                                    <th scope="col" width="20%">No WhatsApp</th>
                                    <th scope="col" width="40%">Alamat</th>
                                    <th scope="col" class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orangTuas as $o)
                                <tr>
                                    <td class="fw-semibold text-primary">{{ $o->nama }}</td>
                                    <td>
                                        <a href="https://wa.me/{{ $o->nomor_whatsapp }}" target="_blank" class="badge bg-success text-decoration-none p-2">
                                            <i class="bi bi-whatsapp"></i> {{ $o->nomor_whatsapp }}
                                        </a>
                                    </td>
                                    <td class="text-muted small">{{ $o->alamat ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $o->id }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('orangtua.destroy', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- EDIT MODAL -->
                                <div class="modal fade" id="edit{{ $o->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <form method="POST" action="{{ route('orangtua.update', $o) }}" class="modal-content border-0 shadow">
                                            @csrf @method('PUT')
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Orang Tua</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body row g-3 p-4">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                        <input type="text" name="nama" class="form-control" value="{{ $o->nama }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Nomor WhatsApp</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-success text-white"><i class="bi bi-whatsapp"></i></span>
                                                        <input type="text" name="nomor_whatsapp" class="form-control" value="{{ $o->nomor_whatsapp }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-semibold d-flex justify-content-between">
                                                        Alamat
                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0" onclick="toggleMap('mapEdit{{ $o->id }}', 'alamatEdit{{ $o->id }}')">
                                                            <i class="bi bi-map"></i> Pilih dari Peta
                                                        </button>
                                                    </label>
                                                    <div id="mapEdit{{ $o->id }}" class="mb-2 rounded border" style="height: 0px; display: none;"></div>
                                                    <textarea class="form-control" id="alamatEdit{{ $o->id }}" name="alamat" rows="3" placeholder="Masukkan alamat atau pilih dari peta">{{ $o->alamat }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CREATE MODAL -->
<div class="modal fade" id="modalCreate" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('orangtua.store') }}" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Tambah Orang Tua Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3 p-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama" value="{{ old('nama') }}" required>
                    </div>
                    @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor WhatsApp</label>
                    <div class="input-group">
                        <span class="input-group-text bg-success text-white"><i class="bi bi-whatsapp"></i></span>
                        <input type="text" name="nomor_whatsapp" class="form-control @error('nomor_whatsapp') is-invalid @enderror" placeholder="Contoh: 0812345678" value="{{ old('nomor_whatsapp') }}" required>
                    </div>
                    @error('nomor_whatsapp') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold d-flex justify-content-between">
                        Alamat
                        <button type="button" class="btn btn-sm btn-outline-primary py-0" onclick="toggleMap('mapCreate', 'alamatCreate')">
                            <i class="bi bi-map"></i> Pilih dari Peta Indonesia
                        </button>
                    </label>
                    <!-- Map Container -->
                    <div id="mapCreate" class="mb-2 rounded border" style="height: 0px; display: none;"></div>
                    
                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamatCreate" name="alamat" rows="3" placeholder="Ketik alamat manual atau gunakan tombol peta di atas">{{ old('alamat') }}</textarea>
                    @error('alamat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet JS & Map Script -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Menyimpan instance peta agar tidak terjadi error saat dibuka tutup
    const maps = {};
    const markers = {};

    function toggleMap(mapId, inputId) {
        const mapContainer = document.getElementById(mapId);
        
        // Animasi buka/tutup peta
        if (mapContainer.style.display === "none") {
            mapContainer.style.display = "block";
            mapContainer.style.height = "250px";
            
            // Inisialisasi peta hanya jika belum ada
            if (!maps[mapId]) {
                // Default kordinat tengah Indonesia (Jawa / Kaltim)
                maps[mapId] = L.map(mapId).setView([-0.789275, 113.921327], 5);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(maps[mapId]);

                // Event ketika peta diklik
                maps[mapId].on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    // Pindahkan atau buat marker
                    if (markers[mapId]) {
                        markers[mapId].setLatLng(e.latlng);
                    } else {
                        markers[mapId] = L.marker(e.latlng).addTo(maps[mapId]);
                    }

                    // Tampilkan loading di textarea
                    document.getElementById(inputId).value = "Mencari alamat...";

                    // Fetch ke Nominatim untuk Reverse Geocoding (mengubah kordinat ke alamat)
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                document.getElementById(inputId).value = data.display_name;
                            } else {
                                document.getElementById(inputId).value = "Alamat tidak ditemukan, silakan ketik manual.";
                            }
                        })
                        .catch(err => {
                            document.getElementById(inputId).value = "Gagal mengambil data alamat. Silakan ketik manual.";
                        });
                });
            }

            // Fix masalah render peta di dalam modal Bootstrap
            setTimeout(() => {
                maps[mapId].invalidateSize();
            }, 300);

        } else {
            mapContainer.style.display = "none";
            mapContainer.style.height = "0px";
        }
    }
</script>

@endsection