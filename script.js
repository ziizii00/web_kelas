// Fungsi untuk memberikan konfirmasi sebelum menghapus data
function konfirmasiHapus(event) {
    if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        event.preventDefault(); // Membatalkan aksi klik jika user memilih 'Cancel'
    }
}

// Menambahkan event listener ke semua tombol hapus secara otomatis
document.addEventListener("DOMContentLoaded", function() {
    const tombolHapus = document.querySelectorAll(".btn-danger");
    tombolHapus.forEach(btn => {
        btn.addEventListener("click", konfirmasiHapus);
    });
});