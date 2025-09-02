$(document).ready(function () {
    const $rfidInput = $("#rfidInput");
    const $jenisInput = $("#jenisInput");
    const $statusMessage = $("#statusMessage");
    const $progressBar = $("#progressBar");
    const $loadingSpinner = $("#loadingSpinner");

    let isProcessing = false;

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $rfidInput.focus();

    function updateTime() {
        const now = new Date();
        $("#currentTime").text(now.toLocaleString("id-ID"));
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Updated SweetAlert2 configuration with white background and black bold text
    function showAlert(type, title, message, duration = 3000) {
        const alertConfigs = {
            success: {
                icon: "success",
                iconColor: "#28a745",
                timer: 3000,
            },
            error: {
                icon: "error",
                iconColor: "#dc3545",
                timer: 3000,
            },
            warning: {
                icon: "warning",
                iconColor: "#ffc107",
                timer: 3000,
            },
            info: {
                icon: "info",
                iconColor: "#17a2b8",
                timer: 3000,
            },
        };

        const config = alertConfigs[type];
        if (!config) return;

        Swal.fire({
            title: title,
            text: message,
            icon: config.icon,
            iconColor: config.iconColor,
            color: "#000000", // Black text
            background: "#ffffff", // White background
            timer: config.timer,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            allowEscapeKey: true,
            position: "center",
            width: "450px",
            padding: "1.5rem",
            customClass: {
                title: "swal2-title-bold",
                htmlContainer: "swal2-text-bold",
            },
            showClass: {
                popup: "swal2-show",
                backdrop: "swal2-backdrop-show",
            },
            hideClass: {
                popup: "swal2-hide",
                backdrop: "swal2-backdrop-hide",
            },
            didOpen: () => {
                // Add custom CSS for bold text
                const style = document.createElement("style");
                style.innerHTML = `
                    .swal2-title-bold {
                        font-weight: bold !important;
                        color: #000000 !important;
                    }
                    .swal2-text-bold {
                        font-weight: bold !important;
                        color: #000000 !important;
                    }
                `;
                document.head.appendChild(style);

                // Auto close untuk success alerts
                if (type === "success") {
                    setTimeout(() => Swal.close(), 3000);
                }
            },
        });
    }

    // Optimized interval checking with reduced frequency
    let intervalId = setInterval(function () {
        $.get("/admin/absensi/check-jenis", function (res) {
            const currentJenis = $("#jenisAbsen").text();
            const newJenis = res.jenis.toUpperCase();

            // Only update if changed to reduce DOM manipulation
            if (currentJenis !== newJenis) {
                $("#jenisAbsen").text(newJenis);
                $jenisInput.val(res.jenis);
            }
        }).fail(function () {
            // Handle connection errors gracefully
            console.warn("Failed to check jenis absen");
        });
    }, 2000); // Reduced frequency from 1000ms to 2000ms

    // Optimized form submission with faster processing
    $("#rfidForm").on("submit", function (e) {
        e.preventDefault();

        if (isProcessing) return;

        const rfidValue = $rfidInput.val().trim();
        if (!rfidValue) {
            showAlert(
                "warning",
                "Peringatan",
                "Silahkan tempelkan kartu RFID terlebih dahulu"
            );
            return;
        }

        isProcessing = true;
        const data = $(this).serialize();

        // Immediate UI feedback
        $loadingSpinner.show();
        $progressBar.addClass("pulse-animation").css("width", "100%");
        $rfidInput.prop("readonly", true);

        $.ajax({
            url: "/admin/absensi/store",
            method: "POST",
            data: data,
            timeout: 10000, // 10 second timeout
            success: function (res) {
                $statusMessage
                    .text(res.message)
                    .removeClass("text-red-600")
                    .addClass("text-green-600 font-semibold");

                showAlert("success", "Berhasil!", res.message);

                // Clear status message faster for success
                setTimeout(() => {
                    $statusMessage
                        .text("")
                        .removeClass("text-green-600 font-semibold");
                }, 1500);
            },
            error: function (xhr) {
                const msg =
                    xhr.responseJSON?.message || "Terjadi kesalahan sistem.";
                $statusMessage
                    .text("❌ " + msg)
                    .removeClass("text-green-600")
                    .addClass("text-red-600 font-semibold");

                showAlert("error", "Gagal!", msg);

                // Keep error message longer
                setTimeout(() => {
                    $statusMessage
                        .text("")
                        .removeClass("text-red-600 font-semibold");
                }, 2500);
            },
            complete: function () {
                // Immediate cleanup
                $progressBar.removeClass("pulse-animation").css("width", "0%");
                $loadingSpinner.hide();
                $rfidInput.prop("readonly", false).val("").focus();
                isProcessing = false;
            },
        });
    });

    // Optimized input handling with debouncing
    let typingTimer;
    const TYPING_DELAY = 300; // Reduced delay for faster response

    $rfidInput.on("input", function () {
        clearTimeout(typingTimer);
        const value = $(this).val().trim();

        if (value.length >= 8 && !isProcessing) {
            typingTimer = setTimeout(() => {
                $("#rfidForm").submit();
            }, TYPING_DELAY);
        }
    });

    // Clean up interval on page unload
    $(window).on("beforeunload", function () {
        if (intervalId) {
            clearInterval(intervalId);
        }
    });
});
