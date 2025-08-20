$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#tambahKelasModal form').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.post(url, data)
            .done(function (res) {
                alert('Kelas berhasil ditambahkan.');
                location.reload();
            })
            .fail(function (xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseJSON?.message || xhr.statusText);
            });
    });

    $('div[id^="editKelasModal"] form').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.post(url, data)
            .done(function (res) {
                alert('Kelas berhasil diperbarui.');
                location.reload();
            })
            .fail(function (xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseJSON?.message || xhr.statusText);
            });
    });

    $('form[action*="kelas/delete"]').submit(function (e) {
        e.preventDefault();
        if (!confirm('Yakin ingin hapus kelas ini?')) return;

        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            url: url,
            type: 'DELETE',
            data: form.serialize(),
            success: function (res) {
                alert('Kelas berhasil dihapus.');
                location.reload();
            },
            error: function (xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseJSON?.message || xhr.statusText);
            }
        });
    });

});
