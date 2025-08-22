$(document).ready(function () {

    function checkJamMasuk() {
        $.get("/pengaturan/check-jam-masuk", function(res) {
            if (res.jam_masuk_locked) {
                $("#jam_masuk_awal, #jam_masuk_akhir").prop('disabled', true)
                    .addClass('is-invalid');
                $("#jamMasukAlert").removeClass('d-none');
                $("#btnSimpan").html('<i class="bi bi-save"></i> Update jam pulang saja');
                $("#jamMasukInfo").text('Jam masuk terkunci karena siswa sudah mulai absen hari ini');
            } else {
                $("#jam_masuk_awal, #jam_masuk_akhir").prop('disabled', false)
                    .removeClass('is-invalid');
                $("#jamMasukAlert").addClass('d-none');
                $("#btnSimpan").html('<i class="bi bi-save"></i> Simpan / Update');
                $("#jamMasukInfo").text('Jika dikosongkan, otomatis pakai default 05:00–07:00');
            }
        });
    }

    checkJamMasuk();
    setInterval(checkJamMasuk, 3000);

    $("#formPengaturan").on("submit", function (e) {
        e.preventDefault();

        let jamMasukAwal = $("#jam_masuk_awal").val().split(':');
        if(jamMasukAwal.length > 1){
            $("#jam_masuk_awal").val(jamMasukAwal[0].padStart(2,'0') + ':' + jamMasukAwal[1].padStart(2,'0'));
        }

        let jamMasukAkhir = $("#jam_masuk_akhir").val().split(':');
        if(jamMasukAkhir.length > 1){
            $("#jam_masuk_akhir").val(jamMasukAkhir[0].padStart(2,'0') + ':' + jamMasukAkhir[1].padStart(2,'0'));
        }

        let jamPulang = $("#jam_pulang").val().split(':');
        if(jamPulang.length > 1){
            $("#jam_pulang").val(jamPulang[0].padStart(2,'0') + ':' + jamPulang[1].padStart(2,'0'));
        }

        let formData = $(this).serialize();

        $.ajax({
            url: "/pengaturan/update",
            method: "POST",
            data: formData,
            success: function (response) {
                if (response.success) {
                    let msg = response.message;
                    if (response.jam_masuk_locked) {
                        msg += " (Jam masuk tidak bisa diubah karena siswa sudah absen masuk hari ini)";
                    }
                    $("#alertSuccess").removeClass("d-none").html(msg);
                    $("#alertError").addClass("d-none");
                    checkJamMasuk();
                }
            },
            error: function (xhr) {
                let errorMsg = "Terjadi kesalahan.";
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = "";
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorMsg += value[0] + "<br>";
                    });
                }
                $("#alertError").removeClass("d-none").html(errorMsg);
                $("#alertSuccess").addClass("d-none");
            },
        });
    });
});
