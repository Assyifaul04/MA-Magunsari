<!DOCTYPE html>
<html>
<head>
    <title>Cetak Absensi</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body onload="window.print()">
    <h2>Data Absensi</h2>
    <table>
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
                    <td>{{ $i+1 }}</td>
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
</body>
</html>
