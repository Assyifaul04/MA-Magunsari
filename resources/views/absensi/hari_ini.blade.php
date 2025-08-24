@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Data Absensi Hari Ini</h1>
</div>

<div class="card shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">RFID</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Jenis</th>
                    <th scope="col">Status</th>
                    <th scope="col">Jam</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensi as $i => $a)
                    {{-- Hanya tampilkan siswa yang sudah punya RFID --}}
                    @if ($a->siswa && $a->siswa->rfid)
                        <tr>
                            <th scope="row">{{ $i + 1 }}</th>
                            <td>{{ $a->rfid ?? $a->siswa->rfid }}</td>
                            <td>{{ $a->siswa->nama }}</td>
                            <td>
                                @if ($a->siswa->kelas)
                                    <span class="badge bg-secondary">{{ $a->siswa->kelas->nama }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($a->jenis == 'masuk')
                                    <span class="badge bg-success">{{ ucfirst($a->jenis) }}</span>
                                @elseif($a->jenis == 'pulang')
                                    <span class="badge bg-warning text-dark">{{ ucfirst($a->jenis) }}</span>
                                @elseif($a->jenis)
                                    <span class="badge bg-info">{{ ucfirst($a->jenis) }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($a->status == 'hadir')
                                    <span class="badge bg-success">{{ ucfirst($a->status) }}</span>
                                @elseif($a->status == 'terlambat')
                                    <span class="badge bg-warning text-dark">{{ ucfirst($a->status) }}</span>
                                @elseif($a->status == 'izin')
                                    <span class="badge bg-info">{{ ucfirst($a->status) }}</span>
                                @elseif($a->status == 'sakit')
                                    <span class="badge bg-danger">{{ ucfirst($a->status) }}</span>
                                @elseif($a->status == 'tidak hadir')
                                    <span class="badge bg-dark">{{ ucfirst($a->status) }}</span>
                                @else
                                    <span class="badge bg-primary">{{ ucfirst($a->status ?? '-') }}</span>
                                @endif
                            </td>
                            <td>{{ $a->jam ?? '-' }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <br>
                                <strong>Tidak ada data absensi</strong>
                                <br>
                                <small>Silakan ubah filter atau periode tanggal untuk menampilkan data</small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
