@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Absensi Izin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item">Absensi</li>
                <li class="breadcrumb-item active">Absensi Izin</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Form Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Absensi Izin</h5>

                        <!-- General Form Elements -->
                        <form id="formAbsensiIzin">
                            @csrf
                            <input type="hidden" name="jenis" value="izin">

                            <div class="row mb-3">
                                <label for="inputRFID" class="col-sm-2 col-form-label">RFID</label>
                                <div class="col-sm-10">
                                    <input type="text" name="rfid" class="form-control" id="inputRFID" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputKeterangan" class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea name="keterangan" class="form-control" id="inputKeterangan" style="height: 100px" required
                                        placeholder="Masukkan keterangan..."></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select" aria-label="Default select example" required>
                                        <option selected disabled value="">-- Pilih Status --</option>
                                        <option value="hadir">Hadir</option>
                                        <option value="terlambat">Terlambat</option>
                                        <option value="pulang">Pulang</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Simpan Data
                                    </button>
                                    <button type="reset" class="btn btn-secondary ms-2">
                                        <i class="bi bi-x-circle"></i> Reset
                                    </button>
                                </div>
                            </div>

                        </form><!-- End General Form Elements -->

                    </div>
                </div><!-- End Form Card -->

                <!-- Data Table Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Absensi Izin</h5>

                        <!-- Table with stripped rows -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">RFID</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absensi as $i => $a)
                                    <tr>
                                        <th scope="row">{{ $i + 1 }}</th>
                                        <td>{{ $a->keterangan }}</td>
                                        <td>
                                            @if ($a->status == 'hadir')
                                                <span class="badge bg-success">{{ ucfirst($a->status) }}</span>
                                            @elseif($a->status == 'terlambat')
                                                <span class="badge bg-warning">{{ ucfirst($a->status) }}</span>
                                            @elseif($a->status == 'izin')
                                                <span class="badge bg-info">{{ ucfirst($a->status) }}</span>
                                            @elseif($a->status == 'sakit')
                                                <span class="badge bg-secondary">{{ ucfirst($a->status) }}</span>
                                            @else
                                                <span class="badge bg-primary">{{ ucfirst($a->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $a->rfid }}</code>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                        @if (count($absensi) == 0)
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle flex-shrink-0 me-2"></i>
                                <div>
                                    Belum ada data absensi yang tersedia.
                                </div>
                            </div>
                        @endif

                    </div>
                </div><!-- End Data Table Card -->

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/absensi.js') }}"></script>
@endpush
