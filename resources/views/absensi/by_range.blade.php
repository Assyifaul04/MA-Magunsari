@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Data Absensi By Range</h1>
</div>

<div class="card shadow-sm p-4">
    <form method="GET" action="{{ route('absensi.byRange') }}" class="row g-3 mb-3">
        <div class="col-md-2">
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
        </div>
        <div class="col-md-2">
            <select name="kelas" class="form-select">
                <option value="">-- Kelas --</option>
                @foreach(\App\Models\Kelas::all() as $kelas)
                    <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                        {{ $kelas->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="jenis" class="form-select">
                <option value="">-- Jenis --</option>
                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="pulang" {{ request('jenis') == 'pulang' ? 'selected' : '' }}>Pulang</option>
                <option value="izin" {{ request('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="nama" class="form-control" placeholder="Nama Siswa" value="{{ request('nama') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="pulang" {{ request('status') == 'pulang' ? 'selected' : '' }}>Pulang</option>
                <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>RFID</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $a->rfid }}</td>
                    <td>{{ $a->siswa->nama }}</td>
                    <td>{{ $a->siswa->kelas->nama ?? '-' }}</td>
                    <td>{{ ucfirst($a->jenis) }}</td>
                    <td>{{ ucfirst($a->status) }}</td>
                    <td>{{ $a->jam }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
