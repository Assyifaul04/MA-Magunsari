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
    <h2>Import Data Excel</h2>

    <form id="import-form" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="file" required>
        <button type="submit">Import</button>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>UUID RFID</th>
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
                    <td>{{ $siswa->rfid_uid ?? 'RFID Belum diinput' }}</td>
                    <td><a href="" data-bs-toggle="modal" data-bs-target="#modalData"
                            data-id="{{ $siswa->id }}" data-name="{{ $siswa->name }}">{{ $siswa->name }}</a>
                    </td>
                    <td>{{ $siswa->kelas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade custom-backdrop" id="modalData" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJudul">Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h1 id="name">Nama Siswa</h1>
                    <br><br>
                    Silakan tempel kartu RFID
                    <br><br>
                    <form id="rfid-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="rfid-input">
                    </form>
                </div>
            </div>
        </div>
    </div>

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

            let siswaId = null;

            $("#modalData").on('shown.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                siswaId = button.data("id");
                const name = button.data("name");
                $("#name").text(name);
                $('#rfid-input').val('').focus();
            })

            let isSubmitting = false;

            $('#rfid-input').on('input', function() {
                const rfid = $(this).val().trim();

                if (rfid.length >= 10 && siswaId && !isSubmitting) {
                    isSubmitting = true; // kunci agar tidak submit berkali-kali
                    $(this).prop('disabled', true);

                    $.ajax({
                        url: `/update-uuid/siswa/${siswaId}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT',
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
