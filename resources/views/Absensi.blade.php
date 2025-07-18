<!DOCTYPE html>
<html>

<head>
    <title>Import Excel AJAX</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Import Excel</title>
</head>

<body>


    <h1>Tempel Kartu</h1>


    <input type="text" name="rfid" id="rfid-absensi" autofocus>

    <br><br>
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>UUID RFID</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Tanggal Dan Jam</th>
        </tr>
        @php
            $no = 1;
        @endphp
        <tbody>
            @foreach ($data as $absensi)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $absensi->siswa->rfid_uid ?? 'RFID Belum diinput' }}</td>
                    <td>{{ $absensi->siswa->name }}
                    </td>
                    <td>{{ $absensi->status }}</td>
                    <td>{{ $absensi->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <script>
        $(document).ready(function() {

            let isSubmitting = false;

            $('#rfid-absensi').on('input', function() {
                const rfid = $(this).val().trim();

                console.log('RFID YANG DIKIRIM', rfid);


                if (rfid.length >= 10 && !isSubmitting) {
                    isSubmitting = true; // kunci agar tidak submit berkali-kali
                    $(this).prop('disabled', true);

                    $.ajax({
                        url: `/create-absensi`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'POST',
                            rfid: rfid
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message || 'RFID berhasil diperbarui!',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                isSubmitting = false; // buka kunci
                                $('#rfid-input').val('').prop('disabled', false)
                                    .focus();
                                $('#modalData').modal('hide'); // jika ingin tutup modal
                            });
                        },
                        error: function(xhr) {
                            let msg = xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menyimpan RFID';
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: msg,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                isSubmitting = false; // buka kunci
                                $('#rfid-input').val('').prop('disabled', false)
                                    .focus();
                            });
                        }
                    });
                }
            });



        });
    </script>
</body>


</html>
