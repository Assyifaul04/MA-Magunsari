<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: 'Calibri', 'Arial', sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        th,
        td {
            border: 1px solid #2c2c2c;
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
        }

        th {
            background-color: #2E7D32;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
            height: 25px;
            text-transform: uppercase;
        }

        td {
            background-color: #ffffff;
            height: 20px;
            font-size: 10px;
        }

        /* Pengaturan lebar kolom yang spesifik */
        th:nth-child(1),
        td:nth-child(1) {
            /* No */
            width: 40px;
            min-width: 40px;
            max-width: 40px;
        }

        th:nth-child(2),
        td:nth-child(2) {
            /* RFID */
            width: 80px;
            min-width: 80px;
            max-width: 80px;
        }

        th:nth-child(3),
        td:nth-child(3) {
            /* Nama */
            width: 150px;
            min-width: 150px;
            max-width: 150px;
            text-align: left;
            padding-left: 8px;
        }

        th:nth-child(4),
        td:nth-child(4) {
            /* Kelas */
            width: 80px;
            min-width: 80px;
            max-width: 80px;
        }

        th:nth-child(5),
        td:nth-child(5) {
            /* Jenis */
            width: 70px;
            min-width: 70px;
            max-width: 70px;
        }

        th:nth-child(6),
        td:nth-child(6) {
            /* Status */
            width: 85px;
            min-width: 85px;
            max-width: 85px;
        }

        th:nth-child(7),
        td:nth-child(7) {
            /* Tanggal */
            width: 85px;
            min-width: 85px;
            max-width: 85px;
        }

        th:nth-child(8),
        td:nth-child(8) {
            /* Jam */
            width: 60px;
            min-width: 60px;
            max-width: 60px;
        }

        /* Styling untuk status */
        .status-hadir {
            background-color: #E8F5E8;
            color: #2E7D32;
        }

        .status-terlambat {
            background-color: #FFF3E0;
            color: #F57C00;
        }

        .status-izin {
            background-color: #E3F2FD;
            color: #1976D2;
        }

        .status-sakit {
            background-color: #FCE4EC;
            color: #C2185B;
        }

        .status-pulang {
            background-color: #F3E5F5;
            color: #7B1FA2;
        }

        .status-tidak-hadir {
            background-color: #FFEBEE;
            color: #D32F2F;
        }

        /* Zebra striping untuk readability */
        tbody tr:nth-child(even) td {
            background-color: #F8F9FA;
        }

        /* Print optimization */
        @media print {
            table {
                page-break-inside: auto;
                font-size: 10px;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
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
                    <td class="status-{{ strtolower(str_replace(' ', '-', $a->status)) }}">{{ ucfirst($a->status) }}</td>
                    <td>{{ $a->tanggal }}</td>
                    <td>{{ $a->jam }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
