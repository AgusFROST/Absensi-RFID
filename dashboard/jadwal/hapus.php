<?php
include "../../koneksi.php";

// Cek apakah ID ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data jadwal berdasarkan ID
    $hapus = mysqli_query($konek, "DELETE FROM jadwal WHERE id = '$id'");

    if ($hapus) {
        // Jika berhasil, tampilkan pesan sukses dan arahkan ke halaman jadwal
        header("Location: jwl.php?message=Jadwal berhasil dihapus");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal menghapus jadwal!";
    }
} else {
    echo "ID tidak ditemukan!";
}
