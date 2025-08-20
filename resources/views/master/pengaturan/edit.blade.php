@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Pengaturan Jam</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengaturan</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm p-4">
        <form id="formPengaturan">
            @csrf
            <div class="mb-3">
                <label for="jam_masuk_awal" class="form-label">Jam Masuk Awal</label>
                <input type="time" name="jam_masuk_awal" id="jam_masuk_awal" step="60" class="form-control"
                    value="{{ $pengaturan->jam_masuk_awal ?? '05:00' }}">
            </div>

            <div class="mb-3">
                <label for="jam_masuk_akhir" class="form-label">Jam Masuk Akhir</label>
                <input type="time" name="jam_masuk_akhir" id="jam_masuk_akhir" step="60" class="form-control"
                    value="{{ $pengaturan->jam_masuk_akhir ?? '07:00' }}" @if ($sudahAdaMasuk) disabled @endif>
                <small class="form-text text-muted">
                    Jika sudah ada siswa yang absen, jam masuk akhir tidak bisa diubah
                </small>
            </div>

            <div class="mb-3">
                <label for="jam_pulang" class="form-label">Jam Pulang</label>
                <input type="time" name="jam_pulang" id="jam_pulang" step="60" class="form-control"
                    value="{{ $pengaturan->jam_pulang ?? '15:00' }}">
            </div>


            <button type="submit" id="btnSimpan" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
        </form>

        <div id="alertSuccess" class="alert alert-success mt-3 d-none"></div>
        <div id="alertError" class="alert alert-danger mt-3 d-none"></div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pengaturan.js') }}"></script>
@endpush
