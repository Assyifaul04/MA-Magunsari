$(document).ready(function () {
    // ================= Configuration =================
    const CONFIG = {
        RFID_MIN_LENGTH: 8,
        TYPING_DELAY: 1000,
        BUTTON_LOADING_DELAY: 300,
        SUCCESS_TIMER: 1500
    };

    // ================= Utility Functions =================
    function isValidElement(element) {
        return element && element.length > 0;
    }

    function safeGetData(element, key, defaultValue = null) {
        if (!isValidElement(element)) return defaultValue;
        const value = element.data(key);
        return value !== undefined && value !== null ? value : defaultValue;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ================= Spinner Functions =================
    function showSpinner() {
        const spinner = $("#loading-spinner");
        if (isValidElement(spinner)) {
            spinner.removeClass("d-none");
        }
    }
    
    function hideSpinner() {
        const spinner = $("#loading-spinner");
        if (isValidElement(spinner)) {
            spinner.addClass("d-none");
        }
    }

    // ================= Button Loading Functions =================
    function showButtonLoading(button, originalContent) {
        if (!isValidElement(button)) {
            console.warn('Button element not found for loading state');
            return false;
        }
        
        try {
            button.prop('disabled', true);
            button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            button.data('original-content', originalContent || button.text());
            return true;
        } catch (error) {
            console.error('Error showing button loading:', error);
            return false;
        }
    }

    function hideButtonLoading(button) {
        if (!isValidElement(button)) {
            console.warn('Button element not found for hiding loading state');
            return false;
        }

        try {
            button.prop('disabled', false);
            const originalContent = button.data('original-content');
            if (originalContent) {
                button.html(originalContent);
            }
            return true;
        } catch (error) {
            console.error('Error hiding button loading:', error);
            return false;
        }
    }

    // ================= AJAX Error Handler =================
    function handleAjaxError(xhr, button = null) {
        if (button) hideButtonLoading(button);
        
        let message = "Terjadi kesalahan pada sistem";
        
        try {
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;
                message = Object.values(errors)
                    .flat()
                    .filter(error => error && typeof error === 'string')
                    .join("<br>");
            } else if (xhr.responseJSON?.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.status === 0) {
                message = "Koneksi terputus. Periksa koneksi internet Anda.";
            } else if (xhr.status === 404) {
                message = "Halaman tidak ditemukan.";
            } else if (xhr.status >= 500) {
                message = "Terjadi kesalahan server. Silakan coba lagi.";
            }
        } catch (error) {
            console.error('Error parsing AJAX response:', error);
        }

        Swal.fire({
            title: 'Error!',
            html: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }

    // ================= Success Handler =================
    function handleSuccess(response, button = null, shouldReload = true) {
        if (button) hideButtonLoading(button);
        
        const message = response?.message || "Operasi berhasil!";
        
        Swal.fire({
            title: 'Berhasil!',
            text: message,
            icon: 'success',
            timer: CONFIG.SUCCESS_TIMER,
            showConfirmButton: false
        }).then(() => {
            if (shouldReload) {
                window.location.reload();
            }
        });
    }

    // ================= Form Validation =================
    function validateForm(form) {
        if (!isValidElement(form)) return false;
        
        // Remove previous validation feedback
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        
        let isValid = true;
        
        // Check required fields
        form.find('[required]').each(function() {
            const field = $(this);
            const value = field.val()?.toString().trim();
            
            if (!value || value === '') {
                field.addClass('is-invalid');
                field.after('<div class="invalid-feedback">Field ini wajib diisi</div>');
                isValid = false;
            }
        });
        
        return isValid;
    }

    // ================= Tambah & Edit Siswa =================
    $("#addSiswaForm, #editSiswaForm").on("submit", function (e) {
        e.preventDefault();
        
        const form = $(this);
        if (!validateForm(form)) return;
        
        const submitButton = form.find('button[type="submit"]');
        const originalContent = submitButton.html();
        const actionUrl = form.attr("action");
        
        if (!actionUrl) {
            Swal.fire({
                title: 'Error!',
                text: 'Form action URL tidak ditemukan',
                icon: 'error'
            });
            return;
        }
        
        if (!showButtonLoading(submitButton, originalContent)) return;

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            timeout: 30000, // 30 second timeout
            success: function (response) {
                form.closest(".modal").modal("hide");
                handleSuccess(response, submitButton);
            },
            error: function (xhr) {
                handleAjaxError(xhr, submitButton);
            }
        });
    });

    // ================= Tombol Edit =================
    $(document).on("click", ".editSiswaBtn", function (e) {
        e.preventDefault();
        
        const button = $(this);
        const originalContent = button.html();
        
        // Get data with validation
        const id = safeGetData(button, "id");
        const nama = safeGetData(button, "nama", "");
        const kelas = safeGetData(button, "kelas", "");
        const rfid = safeGetData(button, "rfid", "");
        const status = safeGetData(button, "status", "");

        if (!id) {
            Swal.fire({
                title: 'Error!',
                text: 'ID siswa tidak ditemukan',
                icon: 'error'
            });
            return;
        }

        if (!showButtonLoading(button, originalContent)) return;

        // Simulate loading time for better UX
        setTimeout(function() {
            try {
                const editForm = $("#editSiswaForm");
                const editModal = $("#editSiswaModal");
                
                if (!isValidElement(editForm) || !isValidElement(editModal)) {
                    hideButtonLoading(button);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Form edit tidak ditemukan',
                        icon: 'error'
                    });
                    return;
                }

                // Set form action dinamis
                editForm.attr("action", "/siswa/" + id);

                // Set field form dengan validasi
                $("#edit_siswa_id").val(id);
                $("#edit_nama").val(nama);
                $("#edit_kelas_id").val(kelas);
                $("#edit_rfid").val(rfid);
                $("#edit_status").val(status);

                hideButtonLoading(button);
                editModal.modal("show");
            } catch (error) {
                hideButtonLoading(button);
                console.error('Error in edit form:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat membuka form edit',
                    icon: 'error'
                });
            }
        }, CONFIG.BUTTON_LOADING_DELAY);
    });

    // ================= Hapus Siswa =================
    $(document).on("click", ".deleteSiswaBtn", function (e) {
        e.preventDefault();
        
        const button = $(this);
        const form = button.closest('form');
        const originalContent = button.html();

        if (!isValidElement(form)) {
            Swal.fire({
                title: 'Error!',
                text: 'Form delete tidak ditemukan',
                icon: 'error'
            });
            return;
        }

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data siswa akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if (!showButtonLoading(button, originalContent)) return;
                
                $.ajax({
                    url: form.attr("action"),
                    type: "POST",
                    data: form.serialize(),
                    timeout: 30000,
                    success: function (response) {
                        handleSuccess(response, button);
                    },
                    error: function (xhr) {
                        handleAjaxError(xhr, button);
                    }
                });
            }
        });
    });

    // ================= Scan RFID Form Submit =================
    $("#scanRfidForm").on("submit", function (e) {
        e.preventDefault();
        
        // Prevent double submission
        if (isSubmitting) {
            console.log('Form already submitting, ignoring...');
            return false;
        }
        
        const form = $(this);
        const rfidInput = form.find("#rfid");
        const rfidValue = rfidInput.val()?.toString().trim();
        
        if (!rfidValue || rfidValue.length < CONFIG.RFID_MIN_LENGTH) {
            Swal.fire({
                title: 'Error!',
                text: `RFID minimal ${CONFIG.RFID_MIN_LENGTH} karakter`,
                icon: 'error'
            });
            rfidInput.focus();
            isSubmitting = false; // Reset flag
            return false;
        }
        
        const submitButton = form.find('button[type="submit"]');
        const originalContent = submitButton.html();
        const actionUrl = form.attr("action");

        if (!actionUrl) {
            Swal.fire({
                title: 'Error!',
                text: 'URL scan tidak ditemukan',
                icon: 'error'
            });
            isSubmitting = false; // Reset flag
            return false;
        }

        if (!showButtonLoading(submitButton, originalContent)) {
            isSubmitting = false; // Reset flag
            return false;
        }

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            timeout: 30000,
            success: function (response) {
                isSubmitting = false; // Reset flag
                hideButtonLoading(submitButton);
                
                if (response?.success) {
                    const modal = $("#scanRfidModal");
                    if (isValidElement(modal)) {
                        modal.modal("hide");
                    }
                    handleSuccess(response, null, true);
                } else {
                    Swal.fire({
                        title: 'Warning!',
                        text: response?.message || 'Response tidak valid',
                        icon: 'warning'
                    });
                }
            },
            error: function (xhr) {
                isSubmitting = false; // Reset flag
                hideButtonLoading(submitButton);
                handleAjaxError(xhr);
                
                // Clear and focus RFID input on error
                if (isValidElement(rfidInput)) {
                    rfidInput.val("").focus();
                }
            }
        });
    });

    // ================= Auto Submit RFID on Input =================
    let isSubmitting = false; // Flag to prevent double submission
    
    const debouncedRfidSubmit = debounce(function(rfidValue) {
        if (rfidValue.length >= CONFIG.RFID_MIN_LENGTH && !isSubmitting) {
            const form = $("#scanRfidForm");
            if (isValidElement(form)) {
                isSubmitting = true;
                form.submit();
            }
        }
    }, CONFIG.TYPING_DELAY);

    $(document).on("input", "#rfid", function () {
        const uid = $(this).val()?.toString().trim() || "";
        if (!isSubmitting) { // Only trigger if not already submitting
            debouncedRfidSubmit(uid);
        }
    });

    // ================= Set Nama Siswa di Modal Scan =================
    $('#scanRfidModal').on('show.bs.modal', function (event) {
        try {
            // Reset submission flag when modal opens
            isSubmitting = false;
            
            const button = $(event.relatedTarget);
            const modal = $(this);
            
            if (isValidElement(button)) {
                const siswaId = safeGetData(button, 'siswa-id');
                const siswaNama = safeGetData(button, 'siswa-nama', 'Nama tidak tersedia');

                if (siswaId) {
                    modal.find('#siswa_id').val(siswaId);
                    modal.find('.nama-siswa').text(siswaNama);
                }
            }
            
            // Clear and focus RFID input
            const rfidInput = modal.find('#rfid');
            if (isValidElement(rfidInput)) {
                rfidInput.val('').focus();
            }
        } catch (error) {
            console.error('Error setting up scan modal:', error);
        }
    });

    // ================= Reset Modal Forms =================
    $('.modal').on('hidden.bs.modal', function () {
        try {
            // Reset submission flag when modal closes
            if ($(this).attr('id') === 'scanRfidModal') {
                isSubmitting = false;
            }
            
            const modal = $(this);
            const forms = modal.find('form');
            
            // Reset forms
            forms.each(function() {
                if (this.reset) {
                    this.reset();
                }
            });
            
            // Clear validation states
            modal.find('.is-invalid').removeClass('is-invalid');
            modal.find('.invalid-feedback').remove();
            
            // Clear any button loading states
            modal.find('button').each(function() {
                const button = $(this);
                if (button.prop('disabled') && button.data('original-content')) {
                    hideButtonLoading(button);
                }
            });
        } catch (error) {
            console.error('Error resetting modal:', error);
        }
    });

    // ================= Global AJAX Setup =================
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            // Add CSRF token if available
            const token = $('meta[name="csrf-token"]').attr('content');
            if (token) {
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            if (textStatus === 'timeout') {
                Swal.fire({
                    title: 'Timeout!',
                    text: 'Request timeout. Silakan coba lagi.',
                    icon: 'warning'
                });
            }
        }
    });

    // ================= Global Error Handler =================
    window.onerror = function(msg, url, lineNo, columnNo, error) {
        console.error('Global error:', {
            message: msg,
            source: url,
            line: lineNo,
            column: columnNo,
            error: error
        });
        return false;
    };
});