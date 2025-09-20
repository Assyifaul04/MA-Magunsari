<table>
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Hadir</th>
            <th>Terlambat</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $siswa->nama }}</td>
            <td>{{ $siswa->kelas->nama ?? '-' }}</td>
            @php
                $bulanInt = (int) $bulan;
            @endphp
            <td>{{ \Carbon\Carbon::create()->month($bulanInt)->translatedFormat('F') }}</td>
            <td>{{ $tahun }}</td>
            <td>{{ $hadir }}</td>
            <td>{{ $terlambat }}</td>
        </tr>
    </tbody>
</table>
