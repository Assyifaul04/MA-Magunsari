$(document).ready(function () {

    // Absensi Izin
    $("#formAbsensiMasuk, #formAbsensiKeluar, #formAbsensiIzin").on("submit", function (e) {
        e.preventDefault();
        sendAbsensi($(this));
    });

    function sendAbsensi(form) {
        $.ajax({
            url: "/absensi/store",
            method: "POST",
            data: form.serialize(),
            success: function (res) {
                alert(res.message);
                location.reload();
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseJSON.message);
            }
        });
    }
});
