@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Data Laporan Performa</h1>
</div>

<div class="card shadow-sm p-4">
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama Siswa" value="{{ request('nama') }}">
        </div>
        <div class="col-md-3">
            <select name="kelas" class="form-select">
                <option value="">-- Kelas --</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                        {{ $kelas->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="performa" class="form-select">
                <option value="">-- Performa --</option>
                <option value="Rajin" {{ request('performa') == 'Rajin' ? 'selected' : '' }}>Paling Rajin</option>
                <option value="Malas" {{ request('performa') == 'Malas' ? 'selected' : '' }}>Paling Malas</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
        </div>
    </form>

    @if(request()->hasAny(['nama','kelas','performa']))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Total Masuk</th>
                    <th>Tepat Waktu</th>
                    <th>Terlambat</th>
                    <th>Performa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d['nama'] }}</td>
                        <td>{{ $d['kelas'] }}</td>
                        <td>{{ $d['totalMasuk'] }}</td>
                        <td>{{ $d['tepatWaktu'] }}</td>
                        <td>{{ $d['terlambat'] }}</td>
                        <td>{{ $d['performa'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
