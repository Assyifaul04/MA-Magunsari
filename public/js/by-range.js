// public/js/by-range.js

// Toast notification function
function showToast(message, type = "success") {
    const toastContainer = document.getElementById("toastContainer");
    const toastId = "toast-" + Date.now();

    const toastHtml = `
        <div class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-${
                        type === "success"
                            ? "check-circle"
                            : type === "danger"
                            ? "exclamation-triangle"
                            : "info-circle"
                    } me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML("beforeend", toastHtml);

    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000,
    });

    toast.show();

    toastElement.addEventListener("hidden.bs.toast", function () {
        toastElement.remove();
    });
}

// Export functionality
document.getElementById("btnExport").addEventListener("click", function () {
    const button = this;
    const originalHtml = button.innerHTML;

    button.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Exporting...';
    button.disabled = true;

    let query = new URLSearchParams(window.location.search).toString();

    setTimeout(() => {
        window.location.href = exportRoute + (query ? "?" + query : "");
        button.innerHTML = originalHtml;
        button.disabled = false;
        showToast("Data berhasil diekspor ke Excel!", "success");
    }, 1000);
});

// Print functionality
document.getElementById("btnPrint").addEventListener("click", function () {
    const button = this;
    const originalHtml = button.innerHTML;

    button.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Preparing...';
    button.disabled = true;

    let query = new URLSearchParams(window.location.search).toString();

    setTimeout(() => {
        window.open(printRoute + (query ? "?" + query : ""), "_blank");
        button.innerHTML = originalHtml;
        button.disabled = false;
        showToast("Halaman print telah dibuka!", "info");
    }, 1000);
});

// Reset form function
function resetForm() {
    document.getElementById("filterForm").reset();
    window.location.href = byRangeRoute;
}

// Auto-submit on date change
document
    .getElementById("tanggal_mulai")
    .addEventListener("change", function () {
        const tanggalSelesai = document.getElementById("tanggal_selesai");
        if (this.value && !tanggalSelesai.value) {
            tanggalSelesai.value = this.value;
        }
    });

// Form validation
document.getElementById("filterForm").addEventListener("submit", function (e) {
    const tanggalMulai = document.getElementById("tanggal_mulai").value;
    const tanggalSelesai = document.getElementById("tanggal_selesai").value;

    if (tanggalMulai && tanggalSelesai && tanggalMulai > tanggalSelesai) {
        e.preventDefault();
        showToast(
            "Tanggal mulai tidak boleh lebih besar dari tanggal selesai!",
            "danger"
        );
        return false;
    }

    if (
        !tanggalMulai &&
        !tanggalSelesai &&
        !document.getElementById("kelas").value &&
        !document.getElementById("jenis").value &&
        !document.getElementById("nama").value &&
        !document.getElementById("status").value
    ) {
        showToast("Silakan pilih minimal satu filter!", "warning");
    }
});

// Kondisi dari blade
if (typeof showSuccessToast !== "undefined" && showSuccessToast) {
    document.addEventListener("DOMContentLoaded", function () {
        showToast(successMessage, "success");
    });
}
if (typeof showWarningToast !== "undefined" && showWarningToast) {
    document.addEventListener("DOMContentLoaded", function () {
        showToast(warningMessage, "warning");
    });
}
