$(document).ready(function () {
    const $rfidInput = $("#rfidInput");
    const $jenisInput = $("#jenisInput");
    const $statusMessage = $("#statusMessage");
    const $progressBar = $("#progressBar");
    const $loadingSpinner = $("#loadingSpinner");
    const $alertContainer = $("#alertContainer");

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

    function showAlert(type, title, message, duration = 5000) {
        const alertId = "alert_" + Date.now();
        const iconMap = {
            success: "bi-check-circle-fill",
            error: "bi-exclamation-triangle-fill",
            warning: "bi-exclamation-triangle-fill",
            info: "bi-info-circle-fill",
        };

        const alertHtml = `
            <div class="alert alert-${
                type === "error" ? "danger" : type
            } alert-dismissible fade show shadow-lg"
                 id="${alertId}" role="alert"
                 style="border: none; border-radius: 15px; backdrop-filter: blur(10px);">
                <div class="d-flex align-items-center">
                    <i class="bi ${
                        iconMap[type]
                    } me-2" style="font-size: 1.2rem;"></i>
                    <div>
                        <strong>${title}</strong>
                        <div class="small">${message}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        $alertContainer.prepend(alertHtml);

        setTimeout(() => {
            $(`#${alertId}`).fadeOut(300, function () {
                $(this).remove();
            });
        }, duration);
    }

    setInterval(function () {
        $.get("/absensi/check-jenis", function (res) {
            $("#jenisAbsen").text(res.jenis.toUpperCase());
            $jenisInput.val(res.jenis);
        });
    }, 1000);

    $("#rfidForm").on("submit", function (e) {
        e.preventDefault();

        if (isProcessing) return;
        isProcessing = true;

        const data = $(this).serialize();
        const rfidValue = $rfidInput.val().trim();

        if (!rfidValue) {
            showAlert(
                "warning",
                "Peringatan",
                "Silahkan tempelkan kartu RFID terlebih dahulu"
            );
            isProcessing = false;
            return;
        }

        $loadingSpinner.show();
        $progressBar.addClass("pulse-animation").css("width", "100%");
        $rfidInput.prop("readonly", true);

        $.ajax({
            url: "/absensi/store",
            method: "POST",
            data: data,
            success: function (res) {
                $statusMessage
                    .text(res.message)
                    .removeClass("text-red-600")
                    .addClass("text-green-600 font-semibold");

                showAlert("success", "Berhasil!", res.message);
            },
            error: function (xhr) {
                const msg = xhr.responseJSON
                    ? xhr.responseJSON.message
                    : "Terjadi kesalahan.";
                $statusMessage
                    .text("❌ " + msg)
                    .removeClass("text-green-600")
                    .addClass("text-red-600 font-semibold");

                showAlert("error", "Gagal!", msg);
            },
            complete: function () {
                $progressBar
                    .removeClass("animate-pulse pulse-animation")
                    .css("width", "0%");
                $loadingSpinner.hide();
                $rfidInput.prop("readonly", false).val("").focus();

                setTimeout(() => {
                    $statusMessage
                        .text("")
                        .removeClass(
                            "text-green-600 text-red-600 font-semibold"
                        );
                }, 3000);

                isProcessing = false;
            },
        });
    });

    let typingTimer;
    $rfidInput.on("input", function () {
        clearTimeout(typingTimer);
        const value = $(this).val().trim();

        if (value.length >= 8 && !isProcessing) {
            typingTimer = setTimeout(() => {
                $("#rfidForm").submit();
            }, 500);
        }
    });
});
