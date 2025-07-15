<!DOCTYPE html>
<html>

<head>
    <title>Import Excel AJAX</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Import Excel</title>
</head>

<body>
    <h2>Import Data Excel</h2>

    <form id="import-form" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="file" required>
        <button type="submit">Import</button>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kelas</th>
        </tr>
        @php
            $no = 1;
        @endphp
        <tbody>
            @foreach ($data as $siswa)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $siswa->name }}</td>
                    <td>{{ $siswa->kelas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#import-form').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                Swal.fire({
                    title: 'Mohon tunggu...',
                    text: 'Sedang mengimpor data Excel',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    url: "{{ route('import.excel') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        Swal.close();
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message
                            });
                            $('#file').val('');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        let msg = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat mengimpor.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: msg
                        });
                    }
                });
            });
        });
    </script>
</body>


</html>
