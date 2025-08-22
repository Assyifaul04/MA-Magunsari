<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>RFID</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensi as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $a->rfid }}</td>
                    <td>{{ $a->siswa->nama }}</td>
                    <td>{{ $a->siswa->kelas->nama ?? '-' }}</td>
                    <td>{{ ucfirst($a->jenis) }}</td>
                    <td>{{ ucfirst($a->status) }}</td>
                    <td>{{ $a->tanggal }}</td>
                    <td>{{ $a->jam }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
