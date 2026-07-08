<table>
    <tr>
        <th colspan="{{ $jumlahHari + 5 }}" style="text-align: center; font-size: 16px; font-weight: bold;">
            REKAP ABSENSI PERIODE {{ strtoupper($bulan) }} {{ $tahun }}
        </th>
    </tr>
    <tr><td></td></tr> <thead>
        <tr>
            <th rowspan="2" style="background-color: #D9E1F2; border: 1px solid #000000; text-align: center; vertical-align: middle; font-weight: bold;">No.</th>
            <th rowspan="2" style="background-color: #D9E1F2; border: 1px solid #000000; text-align: center; vertical-align: middle; font-weight: bold;">Nama Siswa</th>
            <th colspan="{{ $jumlahHari }}" style="background-color: #E2EFDA; border: 1px solid #000000; text-align: center; font-weight: bold;">Tanggal</th>
            <th colspan="3" style="background-color: #FCE4D6; border: 1px solid #000000; text-align: center; font-weight: bold;">Total</th>
        </tr>
        <tr>
            @for ($i = 1; $i <= $jumlahHari; $i++)
                <th style="background-color: #E2EFDA; border: 1px solid #000000; text-align: center; font-weight: bold;">{{ $i }}</th>
            @endfor
            <th style="background-color: #FCE4D6; border: 1px solid #000000; text-align: center; font-weight: bold;">Hadir</th>
            <th style="background-color: #FCE4D6; border: 1px solid #000000; text-align: center; font-weight: bold;">Izin</th>
            <th style="background-color: #FCE4D6; border: 1px solid #000000; text-align: center; font-weight: bold;">Alfa</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach ($rekapData as $index => $row)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $row['nama'] }}</td>
                
                @for ($i = 1; $i <= $jumlahHari; $i++)
                    <td style="border: 1px solid #000000; text-align: center; font-style: italic;">
                        {{ $row['detail'][$i] ?? '' }}
                    </td>
                @endfor

                <td style="border: 1px solid #000000; text-align: center; font-weight: bold;">{{ $row['total_hadir'] }}</td>
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold;">{{ $row['total_izin'] }}</td>
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold;">{{ $row['total_alfa'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>