<?php
include "koneksi.php";

// Baca mode absensi terakhir
$mode = mysqli_query($konek, "SELECT * FROM status");
$data_mode = mysqli_fetch_array($mode);
$mode_absen = $data_mode['mode'];

// Status terakhir di tambah 1
$mode_absen = $mode_absen + 1;
if ($mode_absen > 2) {
    $mode_absen = 1;
}

// Simpan mode absen di tabel status dengan cara update
$update = mysqli_query($konek, "UPDATE status SET mode='$mode_absen'");

if ($update) {
    echo "Berhasil";
} else {
    echo "Gagal";
}
