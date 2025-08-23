$(document).ready(function () {
    // Setup CSRF token untuk semua AJAX request
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Fungsi untuk menampilkan alert
    function showAlert(type, message) {
        // Hapus alert sebelumnya jika ada
        $('.alert').remove();
        
        let alertClass = '';
        let icon = '';
        
        switch(type) {
            case 'success':
                alertClass = 'alert-success';
                icon = 'bi bi-check-circle-fill';
                break;
            case 'error':
            case 'danger':
                alertClass = 'alert-danger';
                icon = 'bi bi-exclamation-triangle-fill';
                break;
            case 'warning':
                alertClass = 'alert-warning';
                icon = 'bi bi-exclamation-triangle-fill';
                break;
            case 'info':
                alertClass = 'alert-info';
                icon = 'bi bi-info-circle-fill';
                break;
            default:
                alertClass = 'alert-primary';
                icon = 'bi bi-info-circle-fill';
        }
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${icon} me-1"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Insert alert setelah pagetitle
        $('.pagetitle').after(alertHtml);
        
        // Auto hide setelah 5 detik
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }

    // AJAX delete kelas dengan konfirmasi
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        
        let btn = $(this);
        let url = btn.data('url');
        let kelasId = btn.data('id');
        let kelasNama = btn.data('nama');
        
        // SweetAlert konfirmasi
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Kelas?',
                text: `Yakin ingin menghapus kelas "${kelasNama}"? Data yang dihapus tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    processDelete(url, btn);
                }
            });
        } else {
            // Fallback ke confirm biasa jika SweetAlert tidak tersedia
            if (confirm(`Yakin ingin hapus kelas "${kelasNama}"?`)) {
                processDelete(url, btn);
            }
        }
    });

    // Fungsi untuk memproses delete
    function processDelete(url, btn) {
        btn.prop('disabled', true);
        const originalHtml = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        $.ajax({
            url: url,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Hapus row dari tabel dengan animasi
                btn.closest('tr').fadeOut(500, function() {
                    $(this).remove();
                    
                    // Update nomor urut
                    updateRowNumbers();
                    
                    // Cek apakah tabel kosong
                    checkEmptyTable();
                });
                
                showAlert("success", "Kelas berhasil dihapus!");
                
                // SweetAlert success jika tersedia
                if (typeof Swal !== 'undefined') {
                    Swal.fire(
                        'Terhapus!',
                        'Kelas berhasil dihapus.',
                        'success'
                    );
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menghapus kelas.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage = 'Terjadi kesalahan: ' + xhr.statusText;
                }
                
                showAlert("danger", errorMessage);
                
                // SweetAlert error jika tersedia
                if (typeof Swal !== 'undefined') {
                    Swal.fire(
                        'Error!',
                        errorMessage,
                        'error'
                    );
                }
            },
            complete: function() {
                btn.prop('disabled', false);
                btn.html(originalHtml);
            }
        });
    }

    // Update nomor urut setelah delete
    function updateRowNumbers() {
        $('table tbody tr').each(function(index) {
            if (!$(this).find('td').hasClass('text-center')) {
                $(this).find('th:first').text(index + 1);
            }
        });
    }

    // Cek apakah tabel kosong dan tampilkan pesan
    function checkEmptyTable() {
        const visibleRows = $('table tbody tr:visible').length;
        
        if (visibleRows === 0) {
            const emptyRowHtml = `
                <tr>
                    <td colspan="3" class="text-center">
                        <div class="py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted">Belum ada data kelas</p>
                        </div>
                    </td>
                </tr>
            `;
            $('table tbody').append(emptyRowHtml);
        }
    }

    // Handle form submit dengan AJAX (opsional - untuk tambah/edit kelas)
    $(document).on('submit', 'form[action*="kelas.store"], form[action*="kelas.update"]', function(e) {
        e.preventDefault();
        
        let form = $(this);
        let submitBtn = form.find('button[type="submit"]');
        let modal = form.closest('.modal');
        
        // Disable submit button
        submitBtn.prop('disabled', true);
        const originalText = submitBtn.text();
        submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method') || 'POST',
            data: form.serialize(),
            success: function(response) {
                modal.modal('hide');
                showAlert("success", response.message || "Data kelas berhasil disimpan!");
                
                // Reload halaman setelah 1 detik
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                
                if (xhr.status === 422) {
                    // Validation errors
                    let errors = xhr.responseJSON.errors;
                    let errorList = [];
                    
                    for (let field in errors) {
                        errorList.push(errors[field][0]);
                    }
                    
                    errorMessage = errorList.join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert("danger", errorMessage);
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.text(originalText);
            }
        });
    });

    // Clear form saat modal ditutup
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').hide();
    });

    // Auto focus pada input pertama saat modal dibuka
    $('.modal').on('shown.bs.modal', function() {
        $(this).find('input:first').focus();
    });

    // Validasi real-time untuk nama kelas
    $(document).on('input', 'input[name="nama"]', function() {
        let input = $(this);
        let value = input.val().trim();
        
        // Reset validation state
        input.removeClass('is-invalid is-valid');
        input.siblings('.invalid-feedback').remove();
        
        if (value.length === 0) {
            input.addClass('is-invalid');
            input.after('<div class="invalid-feedback">Nama kelas tidak boleh kosong!</div>');
        } else if (value.length < 3) {
            input.addClass('is-invalid');
            input.after('<div class="invalid-feedback">Nama kelas minimal 3 karakter!</div>');
        } else if (value.length > 50) {
            input.addClass('is-invalid');
            input.after('<div class="invalid-feedback">Nama kelas maksimal 50 karakter!</div>');
        } else {
            input.addClass('is-valid');
        }
    });
});