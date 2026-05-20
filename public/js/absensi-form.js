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
            color: "#000000",
            background: "#ffffff",
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

            if (currentJenis !== newJenis) {
                $("#jenisAbsen").text(newJenis);
                $jenisInput.val(res.jenis);
            }
        }).fail(function () {
            console.warn("Failed to check jenis absen");
        });
    }, 2000);

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

                if (res.success && res.data) {
                    const status = (res.data.status || "").toLowerCase();
                    
                    let ttsMessage = "oke"; // Default suara untuk hadir / terlambat / pulang

                    if (status.includes("sudah")) {
                        ttsMessage = "sudah absen";
                    }

                    speakMessage(ttsMessage);
                }

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

                // Tampilkan notifikasi visual
                const alertType = msg.toLowerCase().includes("sudah")
                    ? "warning"
                    : "error";
                showAlert(alertType, "Informasi", msg);

                // --- LOGIKA SUARA UNTUK ERROR ---
                let ttsMessage = "gagal"; 

                if (msg.toLowerCase().includes("sudah")) {
                    ttsMessage = "sudah absen";
                }

                speakMessage(ttsMessage);

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

    let typingTimer;
    const TYPING_DELAY = 300;

    $rfidInput.on("input", function () {
        clearTimeout(typingTimer);
        const value = $(this).val().trim();

        if (value.length >= 8 && !isProcessing) {
            typingTimer = setTimeout(() => {
                $("#rfidForm").submit();
            }, TYPING_DELAY);
        }
    });

    $(window).on("beforeunload", function () {
        if (intervalId) {
            clearInterval(intervalId);
        }
    });

    // Suara voice Indonesia
    function speakMessage(message) {
        if ("speechSynthesis" in window) {
            window.speechSynthesis.cancel();

            const utterance = new SpeechSynthesisUtterance(message);
            utterance.lang = "id-ID";
            utterance.rate = 1.1;
            utterance.pitch = 1.1;

            const voices = window.speechSynthesis.getVoices();
            const indonesianVoice = voices.find(
                (voice) => voice.lang === "id-ID"
            );
            if (indonesianVoice) {
                utterance.voice = indonesianVoice;
            }

            window.speechSynthesis.speak(utterance);
        } else {
            console.warn("Browser tidak mendukung Speech Synthesis API");
        }
    }
});