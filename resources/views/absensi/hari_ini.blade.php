@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Data Absensi Hari Ini</h1>
</div>

<div class="card shadow-sm p-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>RFID</th>
                <th>Nama</th>
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
                    <td>{{ ucfirst($a->jenis) }}</td>
                    <td>{{ ucfirst($a->status) }}</td>
                    <td>{{ $a->jam }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
