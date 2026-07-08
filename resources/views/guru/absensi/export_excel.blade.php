<table>
    <tr></tr>
    <tr>
        <th></th>
        <th colspan="{{ $jumlahHari + 5 }}" style="font-size: 20px; font-weight: bold; text-align: center; height: 40px; vertical-align: middle;">
            REKAP ABSENSI KELAS {{ strtoupper($namaKelas) }} - {{ strtoupper($namaBulan) }} {{ $tahun }}
        </th>
    </tr>
    <tr></tr>
    <tr>
        <th rowspan="2" style="background-color: #dbeecd; font-weight: bold; text-align: center; vertical-align: middle; width: 5px;">No.</th>
        <th rowspan="2" style="background-color: #dbeecd; font-weight: bold; text-align: center; vertical-align: middle; width: 25px;">Nama</th>
        <th colspan="{{ $jumlahHari }}" style="background-color: #dbeecd; font-weight: bold; text-align: center;">Tanggal</th>
        <th colspan="4" style="background-color: #fce4d6; font-weight: bold; text-align: center;">Total</th>
    </tr>
    <tr>
        @for($i = 1; $i <= $jumlahHari; $i++)
            <th style="background-color: #dbeecd; font-weight: bold; text-align: center; width: 4px;">{{ $i }}</th>
        @endfor
        <th style="background-color: #fce4d6; font-weight: bold; text-align: center; width: 6px;">Hadir</th>
        <th style="background-color: #fce4d6; font-weight: bold; text-align: center; width: 6px;">Izin</th>
        <th style="background-color: #fce4d6; font-weight: bold; text-align: center; width: 6px;">Sakit</th>
        <th style="background-color: #fce4d6; font-weight: bold; text-align: center; width: 6px;">Alfa</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($rekap as $row)
        <tr>
            <td style="text-align: center;">{{ $no++ }}</td>
            <td>{{ $row['siswa']->nama }}</td>
            @for($i = 1; $i <= $jumlahHari; $i++)
                @php 
                    $val = $row['data'][$i];
                    $txt = '';
                    if($val == 'H') $txt = 'Hadir';
                    elseif($val == 'I') $txt = 'Izin';
                    elseif($val == 'S') $txt = 'Sakit';
                    elseif($val == 'A') $txt = 'Alfa';
                @endphp
                <td style="text-align: center; {{ in_array($val, ['H','I','S','A']) ? 'font-style: italic;' : '' }}">
                    {{ $txt }}
                </td>
            @endfor
            <td style="text-align: center; font-weight: bold;">{{ $row['total']['hadir'] }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $row['total']['izin'] }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $row['total']['sakit'] }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $row['total']['alfa'] }}</td>
        </tr>
    @endforeach
</table>