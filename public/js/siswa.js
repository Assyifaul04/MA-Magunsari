$(document).ready(function() {
    $('#scanRfidForm').on('submit', function(e){
        e.preventDefault();
    });

    $('.editSiswaBtn').on('click', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var kelas = $(this).data('kelas');
        var rfid = $(this).data('rfid');
        var status = $(this).data('status');

        // isi form modal
        $('#edit_siswa_id').val(id);
        $('#edit_nama').val(nama);
        $('#edit_kelas_id').val(kelas);
        $('#edit_rfid').val(rfid || '');
        $('#edit_status').val(status);
        $('#editSiswaForm').attr('action', '/siswa/' + id);
    });

    $('#scanRfidModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var siswaId = button.data('siswa-id');
        var siswaNama = button.data('siswa-nama');

        var modal = $(this);
        modal.find('#siswa_id').val(siswaId);
        modal.find('#modalSiswaNama').text(siswaNama);
        modal.find('#rfid').val('').focus();
    });

    let typingTimer;
    $('#rfid').on('input', function() {
        clearTimeout(typingTimer);
        var uid = $(this).val().trim();
    
        typingTimer = setTimeout(() => {
            if(uid.length > 0) {
                var siswaId = $('#siswa_id').val();
                $.ajax({
                    url: "/siswa/scan",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        siswa_id: siswaId,
                        rfid: uid
                    },
                    success: function(response) {
                        if(response.success) {
                            $('#scanRfidModal').modal('hide');
                            $('#rfid').val('');
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Terjadi kesalahan';
                        if(xhr.status === 422 && xhr.responseJSON?.message) {
                            msg = xhr.responseJSON.message;
                        }
                        alert(msg);
                        $('#rfid').val('').focus();
                    }
                });
            }
        }, 200);
    });

    $('#editSiswaForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        var actionUrl = form.attr('action');
        
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#editSiswaModal').modal('hide');
                alert('Data siswa berhasil diperbarui!');
                window.location.reload();
            },
            error: function(xhr) {
                var msg = 'Terjadi kesalahan saat memperbarui data';
                
                if(xhr.status === 422 && xhr.responseJSON?.errors) {
                    // Handle validation errors
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = [];
                    
                    Object.keys(errors).forEach(function(key) {
                        errorMessages.push(errors[key][0]);
                    });
                    
                    msg = errorMessages.join('\n');
                } else if(xhr.responseJSON?.message) {
                    msg = xhr.responseJSON.message;
                }
                
                alert(msg);
            }
        });
    });
});