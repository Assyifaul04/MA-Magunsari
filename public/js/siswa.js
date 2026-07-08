$(document).ready(function () {

    // ================= Configuration =================
    const CONFIG = {
        RFID_MIN_LENGTH: 8,
        TYPING_DELAY: 1000,
        BUTTON_LOADING_DELAY: 300,
        SUCCESS_TIMER: 1500
    };

    // ================= Flag & Debounce Handle =================
    let isSubmitting = false;
    let rfidDebounceTimer = null;

    // ================= Utility Functions =================
    function isValidElement(element) {
        return element && element.length > 0;
    }

    function safeGetData(element, key, defaultValue = null) {
        if (!isValidElement(element)) return defaultValue;
        const value = element.data(key);
        return (value !== undefined && value !== null) ? value : defaultValue;
    }

    function cancelRfidDebounce() {
        if (rfidDebounceTimer) {
            clearTimeout(rfidDebounceTimer);
            rfidDebounceTimer = null;
        }
    }

    // ================= SVG Helper =================
    // Menghasilkan HTML status icon (animated SVG, tanpa teks)
    function buildStatusIcon(status) {
        if (status === 'aktif') {
            return `<span class="status-icon" title="Aktif">
                <svg class="check-svg" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle class="check-circle" cx="14" cy="14" r="10.5"/>
                    <polyline class="check-tick" points="9,14 12.5,17.5 19,11"/>
                </svg>
            </span>`;
        } else {
            return `<span class="status-icon" title="Pending">
                <svg class="pending-svg" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle class="pending-circle" cx="13" cy="13" r="10"/>
                    <line class="pending-hands" x1="13" y1="8" x2="13" y2="13.5"/>
                    <line class="pending-hands" x1="13" y1="13.5" x2="16.5" y2="16"/>
                </svg>
            </span>`;
        }
    }

    // Paksa restart animasi SVG dengan clone trick
    function restartSvgAnimation(cell) {
        const old = cell.find('svg')[0];
        if (!old) return;
        const clone = old.cloneNode(true);
        old.parentNode.replaceChild(clone, old);
    }

    // ================= Button Loading =================
    function showButtonLoading(button, originalContent) {
        if (!isValidElement(button)) return false;
        button.prop('disabled', true);
        button.data('original-content', originalContent || button.html());
        button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        return true;
    }

    function hideButtonLoading(button) {
        if (!isValidElement(button)) return false;
        button.prop('disabled', false);
        const orig = button.data('original-content');
        if (orig) button.html(orig);
        return true;
    }

    // ================= AJAX Error Handler =================
    function handleAjaxError(xhr, button = null) {
        if (button) hideButtonLoading(button);
        let message = "Terjadi kesalahan pada sistem";
        try {
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                message = Object.values(xhr.responseJSON.errors).flat().join("<br>");
            } else if (xhr.responseJSON?.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.status === 0) {
                message = "Koneksi terputus.";
            } else if (xhr.status === 404) {
                message = "Halaman tidak ditemukan.";
            } else if (xhr.status >= 500) {
                message = "Terjadi kesalahan server.";
            }
        } catch (e) { console.error(e); }
        Swal.fire({ title: 'Error!', html: message, icon: 'error', confirmButtonText: 'OK' });
    }

    // ================= Success Handler (non-RFID) =================
    function handleSuccess(response, button = null, shouldReload = true) {
        if (button) hideButtonLoading(button);
        Swal.fire({
            title: 'Berhasil!',
            text: response?.message || "Operasi berhasil!",
            icon: 'success',
            timer: CONFIG.SUCCESS_TIMER,
            showConfirmButton: false
        }).then(() => { if (shouldReload) window.location.reload(); });
    }

    // ================= Form Validation =================
    function validateForm(form) {
        if (!isValidElement(form)) return false;
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        let isValid = true;
        form.find('[required]').each(function () {
            const field = $(this);
            if (!field.val()?.toString().trim()) {
                field.addClass('is-invalid');
                field.after('<div class="invalid-feedback">Field ini wajib diisi</div>');
                isValid = false;
            }
        });
        return isValid;
    }

    // ================= Konteks "datang dari Laporan RFID" =================
    // Dibaca dari data attribute pada #siswaSection (di-set oleh siswa.blade.php
    // berdasarkan query string ?from_laporan=1). Dipakai untuk menampilkan
    // SweetAlert "Kembali ke Laporan" setelah RFID berhasil disimpan.
    function getLaporanContext() {
        const section = document.getElementById('siswaSection');
        return {
            fromLaporan: section?.dataset.fromLaporan === '1',
            laporanUrl: section?.dataset.laporanUrl || null
        };
    }

    // ================= Scan RFID =================
    function doScanRfid() {
        cancelRfidDebounce();
        if (isSubmitting) return;

        const form      = $("#scanRfidForm");
        if (!isValidElement(form)) return;

        const rfidInput = form.find("#rfid");
        const rfidValue = rfidInput.val()?.toString().trim();
        const siswaId   = form.find("#siswa_id").val();

        if (!rfidValue || rfidValue.length < CONFIG.RFID_MIN_LENGTH) {
            Swal.fire({ title: 'Error!', text: `RFID minimal ${CONFIG.RFID_MIN_LENGTH} karakter`, icon: 'error' });
            rfidInput.focus();
            return;
        }

        if (!siswaId) {
            Swal.fire({ title: 'Error!', text: 'ID siswa tidak ditemukan. Tutup modal dan coba lagi.', icon: 'error' });
            return;
        }

        const actionUrl = form.attr("action");
        if (!actionUrl) {
            Swal.fire({ title: 'Error!', text: 'URL scan tidak ditemukan', icon: 'error' });
            return;
        }

        isSubmitting = true;
        const submitBtn = form.find('button[type="submit"]');
        showButtonLoading(submitBtn, submitBtn.html());

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            timeout: 30000,
            success: function (response) {
                isSubmitting = false;
                hideButtonLoading(submitBtn);

                if (response?.success) {
                    const usedRfid   = response.rfid   || rfidValue;
                    const usedStatus = response.status || 'aktif';
                    const usedId     = response.siswa_id || siswaId;

                    // ── Update kolom RFID ──
                    const rfidCell = $("#rfid-" + usedId);
                    if (isValidElement(rfidCell)) {
                        rfidCell.html('<span class="rfid-code">' + usedRfid + '</span>');
                        rfidCell.addClass('rfid-updated');
                        setTimeout(() => rfidCell.removeClass('rfid-updated'), 800);
                    }

                    // ── Update kolom Status → animated SVG (tanpa teks, tanpa Swal) ──
                    const row = rfidCell.closest('tr');
                    const statusCell = row.find('.status-cell');
                    if (isValidElement(statusCell)) {
                        statusCell.html(buildStatusIcon(usedStatus));
                        // restart animasi
                        restartSvgAnimation(statusCell);
                    }

                    // ── Tutup modal & reset form ──
                    $("#scanRfidModal").modal("hide");

                    // ── Jika datang dari Laporan RFID Hilang, tawarkan untuk kembali ──
                    const { fromLaporan, laporanUrl } = getLaporanContext();
                    if (fromLaporan && laporanUrl) {
                        setTimeout(function () {
                            Swal.fire({
                                title: 'RFID Berhasil Disimpan!',
                                text: 'Kartu RFID baru sudah tersimpan. Kembali ke Laporan RFID Hilang sekarang?',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Kembali ke Laporan',
                                cancelButtonText: 'Tetap di Halaman Ini',
                                confirmButtonColor: '#16a34a',
                                cancelButtonColor: '#6c757d',
                                reverseButtons: true
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    window.location.href = laporanUrl;
                                }
                            });
                        }, 350); // beri waktu modal scan tertutup dulu
                    }

                } else {
                    Swal.fire({
                        title: 'Warning!',
                        text: response?.message || 'Response tidak valid',
                        icon: 'warning'
                    });
                }
            },
            error: function (xhr) {
                isSubmitting = false;
                hideButtonLoading(submitBtn);
                handleAjaxError(xhr);
                rfidInput.val("").focus();
            }
        });
    }

    // Submit manual via tombol
    $("#scanRfidForm").on("submit", function (e) {
        e.preventDefault();
        doScanRfid();
    });

    // Auto-submit via debounce saat user mengetik/scan
    $(document).on("input", "#rfid", function () {
        if (isSubmitting) return;
        const val = $(this).val()?.toString().trim() || "";
        cancelRfidDebounce();
        if (val.length >= CONFIG.RFID_MIN_LENGTH) {
            rfidDebounceTimer = setTimeout(function () {
                rfidDebounceTimer = null;
                doScanRfid();
            }, CONFIG.TYPING_DELAY);
        }
    });

    // Set data siswa saat modal akan muncul
    $('#scanRfidModal').on('show.bs.modal', function (event) {
        cancelRfidDebounce();
        isSubmitting = false;
        const trigger = $(event.relatedTarget);
        if (isValidElement(trigger)) {
            $(this).find('#siswa_id').val(safeGetData(trigger, 'siswa-id') || '');
            $(this).find('.nama-siswa').text(safeGetData(trigger, 'siswa-nama', 'Nama tidak tersedia'));
        }
        $(this).find('#rfid').val('');
    });

    // Focus input RFID setelah modal tampil
    $('#scanRfidModal').on('shown.bs.modal', function () {
        $(this).find('#rfid').focus();
    });

    // ================= Tambah & Edit Siswa =================
    $("#addSiswaForm, #editSiswaForm").on("submit", function (e) {
        e.preventDefault();
        const form = $(this);
        if (!validateForm(form)) return;

        const submitBtn = form.find('button[type="submit"]');
        const actionUrl = form.attr("action");

        if (!actionUrl) {
            Swal.fire({ title: 'Error!', text: 'Form action URL tidak ditemukan', icon: 'error' });
            return;
        }

        showButtonLoading(submitBtn, submitBtn.html());

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            timeout: 30000,
            success: function (response) {
                form.closest(".modal").modal("hide");
                handleSuccess(response, submitBtn);
            },
            error: function (xhr) {
                handleAjaxError(xhr, submitBtn);
            }
        });
    });

    // ================= Tombol Edit =================
    $(document).on("click", ".editSiswaBtn", function (e) {
        e.preventDefault();
        const button = $(this);

        const id         = safeGetData(button, "id");
        const nisn       = safeGetData(button, "nisn", "");
        const nama       = safeGetData(button, "nama", "");
        const kelas      = safeGetData(button, "kelas", "");
        const angkatan   = safeGetData(button, "angkatan", "");
        const orangTuaId = safeGetData(button, "orangTuaId", "");
        const rfid       = safeGetData(button, "rfid", "");
        const status     = safeGetData(button, "status", "");
        const updateUrl  = safeGetData(button, "updateUrl");

        if (!id) {
            Swal.fire({ title: 'Error!', text: 'ID siswa tidak ditemukan', icon: 'error' });
            return;
        }

        showButtonLoading(button, button.html());

        setTimeout(function () {
            const editForm  = $("#editSiswaForm");
            const editModal = $("#editSiswaModal");

            if (!isValidElement(editForm) || !isValidElement(editModal)) {
                hideButtonLoading(button);
                Swal.fire({ title: 'Error!', text: 'Form edit tidak ditemukan', icon: 'error' });
                return;
            }

            editForm.attr("action", updateUrl);
            $("#edit_siswa_id").val(id);
            $("#edit_nisn").val(nisn);
            $("#edit_nama").val(nama);
            $("#edit_kelas_id").val(kelas);
            $("#edit_angkatan").val(angkatan);
            $("#edit_orang_tua_id").val(orangTuaId);
            $("#edit_rfid").val(rfid === 'null' || rfid === null ? '' : rfid);
            $("#edit_status").val(status);

            hideButtonLoading(button);
            editModal.modal("show");
        }, CONFIG.BUTTON_LOADING_DELAY);
    });

    // ================= Tombol Hapus =================
    $(document).on("click", ".deleteSiswaBtn", function (e) {
        e.preventDefault();
        const button = $(this);
        const form   = button.closest('form');

        if (!isValidElement(form)) {
            Swal.fire({ title: 'Error!', text: 'Form delete tidak ditemukan', icon: 'error' });
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
            if (!result.isConfirmed) return;
            showButtonLoading(button, button.html());
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: form.serialize(),
                timeout: 30000,
                success: function (response) { handleSuccess(response, button); },
                error:   function (xhr)      { handleAjaxError(xhr, button); }
            });
        });
    });

    // ================= Reset Modal =================
    $('.modal').on('hidden.bs.modal', function () {
        if ($(this).attr('id') === 'scanRfidModal') {
            cancelRfidDebounce();
            isSubmitting = false;
        }
        $(this).find('form').each(function () { if (this.reset) this.reset(); });
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').remove();
        $(this).find('button').each(function () {
            const btn = $(this);
            if (btn.prop('disabled') && btn.data('original-content')) hideButtonLoading(btn);
        });
    });

    // ================= AJAX Setup =================
    $.ajaxSetup({
        beforeSend: function (xhr) {
            const token = $('meta[name="csrf-token"]').attr('content');
            if (token) xhr.setRequestHeader('X-CSRF-TOKEN', token);
        }
    });

});