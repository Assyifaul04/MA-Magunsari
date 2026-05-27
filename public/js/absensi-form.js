$(document).ready(function () {
    const $rfidInput      = $("#rfidInput");
    const $jenisInput     = $("#jenisInput");
    const $statusMessage  = $("#statusMessage");
    const $progressBar    = $("#progressBar");
    const $loadingSpinner = $("#loadingSpinner");

    let isProcessing = false;

    $.ajaxSetup({
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    });

    $rfidInput.focus();

    /* ── Clock ────────────────────────────── */
    function updateTime() {
        $("#currentTime").text(new Date().toLocaleString("id-ID"));
    }
    updateTime();
    setInterval(updateTime, 1000);

    /* ── SweetAlert helper ────────────────── */
    function showAlert(type, title, message) {
        const configs = {
            success: { icon: "success", iconColor: "#0ca678" },
            error:   { icon: "error",   iconColor: "#e03131" },
            warning: { icon: "warning", iconColor: "#f59f00" },
            info:    { icon: "info",    iconColor: "#1098ad" },
        };
        const cfg = configs[type];
        if (!cfg) return;

        Swal.fire({
            title,
            text: message,
            icon: cfg.icon,
            iconColor: cfg.iconColor,
            color: "#000",
            background: "#fff",
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            allowEscapeKey: true,
            position: "center",
            width: "450px",
            padding: "1.5rem",
        });
    }

    /* ── Poll jenis absen setiap 2 detik ─── */
    const intervalId = setInterval(function () {
        $.get("/admin/absensi/check-jenis", function (res) {
            const newJenis = res.jenis.toUpperCase();
            if ($("#jenisAbsen").text() !== newJenis) {
                $("#jenisAbsen").text(newJenis);
                $jenisInput.val(res.jenis);
            }
        }).fail(function () {
            console.warn("Gagal mengambil jenis absen");
        });
    }, 2000);

    /* ── Submit form ──────────────────────── */
    $("#rfidForm").on("submit", function (e) {
        e.preventDefault();

        if (isProcessing) return;

        const rfidValue = $rfidInput.val().trim();
        if (!rfidValue) {
            showAlert("warning", "Peringatan", "Silahkan tempelkan kartu RFID terlebih dahulu");
            return;
        }

        isProcessing = true;

        $loadingSpinner.show();
        $progressBar.addClass("pulse-animation").css("width", "100%");
        $rfidInput.prop("readonly", true);

        $.ajax({
            url: "/admin/absensi/store",
            method: "POST",
            data: $(this).serialize(),
            timeout: 10000,

            success: function (res) {
                $statusMessage
                    .text(res.message)
                    .removeClass("text-red-600")
                    .addClass("text-green-600 font-semibold");

                showAlert("success", "Berhasil!", res.message);

                if (res.success) {
                    /*
                     * Absen masuk  → "Oke"
                     * Absen pulang → "Sampai jumpa"
                     */
                    const jenisRaw = (res.data && res.data.jenis)
                        ? res.data.jenis
                        : $("#jenisAbsen").text();

                    const ttsMessage = jenisRaw.toLowerCase().includes("pulang")
                        ? "Sampai jumpa"
                        : "Oke";

                    console.log("[TTS] jenis raw:", jenisRaw, "| akan berbunyi:", ttsMessage);

                    speakMessage(ttsMessage);
                }

                setTimeout(() => {
                    $statusMessage.text("").removeClass("text-green-600 font-semibold");
                }, 1500);
            },

            error: function (xhr) {
                const msg      = xhr.responseJSON?.message || "";
                const msgLower = msg.toLowerCase();

                let alertType, alertTitle, ttsMessage;

                if (
                    msgLower.includes("sudah absen") ||
                    msgLower.includes("sudah tercatat") ||
                    msgLower.includes("sudah melakukan")
                ) {
                    /* ── Sudah absen ── */
                    alertType  = "warning";
                    alertTitle = "Sudah Tercatat";
                    ttsMessage = "Absensi sudah tercatat";

                } else if (
                    msgLower.includes("tidak dikenal") ||
                    msgLower.includes("tidak terdaftar") ||
                    msgLower.includes("kartu tidak") ||
                    msgLower.includes("rfid tidak") ||
                    msgLower.includes("not found") ||
                    msgLower.includes("karyawan tidak")
                ) {
                    /* ── Kartu tidak dikenal ── */
                    alertType  = "error";
                    alertTitle = "Kartu Tidak Dikenal";
                    ttsMessage = "Kartu tidak dikenal";

                } else {
                    /* ── Error lain ── */
                    alertType  = "error";
                    alertTitle = "Absensi Gagal";
                    ttsMessage = "Absensi gagal";
                }

                const displayMsg = msg || "Terjadi kesalahan sistem.";

                $statusMessage
                    .text("❌ " + displayMsg)
                    .removeClass("text-green-600")
                    .addClass("text-red-600 font-semibold");

                showAlert(alertType, alertTitle, displayMsg);
                speakMessage(ttsMessage);

                setTimeout(() => {
                    $statusMessage.text("").removeClass("text-red-600 font-semibold");
                }, 2500);
            },

            complete: function () {
                $progressBar.removeClass("pulse-animation").css("width", "0%");
                $loadingSpinner.hide();
                $rfidInput.prop("readonly", false).val("").focus();
                isProcessing = false;
            },
        });
    });

    /* ── Auto-submit setelah selesai mengetik ── */
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

    /* ── Cleanup saat halaman ditutup ───── */
    $(window).on("beforeunload", function () {
        clearInterval(intervalId);
    });

    /* ── Text-to-Speech Indonesia ─────────── */
    if ("speechSynthesis" in window) {
        window.speechSynthesis.getVoices();
        window.speechSynthesis.onvoiceschanged = function () {
            window.speechSynthesis.getVoices();
        };
    }

    function getBestVoice() {
        const voices = window.speechSynthesis.getVoices();
        const priority = [
            (v) => v.lang === "id-ID" && !v.name.toLowerCase().includes("google"),
            (v) => v.lang === "id-ID",
            (v) => v.lang.startsWith("id"),
            (v) => v.lang === "ms-MY",
            (v) => v.lang.startsWith("ms"),
        ];

        for (const test of priority) {
            const found = voices.find(test);
            if (found) return found;
        }

        return null;
    }

    function speakMessage(message) {
        if (!("speechSynthesis" in window)) {
            console.warn("Browser tidak mendukung Speech Synthesis API");
            return;
        }

        window.speechSynthesis.cancel();

        /*
         * Chrome membutuhkan jeda singkat setelah cancel()
         * sebelum utterance baru bisa diputar dengan benar.
         */
        setTimeout(function () {
            const utterance  = new SpeechSynthesisUtterance(message);
            utterance.lang   = "id-ID";
            utterance.rate   = 0.92;   
            utterance.pitch  = 1.0;    
            utterance.volume = 1.0;    

            const voice = getBestVoice();
            if (voice) {
                utterance.voice = voice;
                console.log("[TTS] Menggunakan suara:", voice.name, voice.lang);
            } else {
                console.warn("[TTS] Suara Indonesia tidak ditemukan, menggunakan default browser");
            }

            utterance.onerror = function (e) {
                console.error("[TTS] Error:", e.error);
            };

            window.speechSynthesis.speak(utterance);
        }, 120);
    }
});