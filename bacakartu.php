<?php
include "koneksi.php";

// Set timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Pastikan koneksi berhasil
if (!$konek) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil mode absen
$sql = $konek->query("SELECT * FROM status LIMIT 1");
$data = $sql->fetch_assoc();
$mode_absen = isset($data['mode']) ? $data['mode'] : null;
$mode = ($mode_absen == 1) ? "Masuk" : (($mode_absen == 2) ? "Keluar" : "Tidak Diketahui");

// Nama hari dalam bahasa Indonesia
$hari_indonesia = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];
$hari_ini = $hari_indonesia[date('l')];

// Mendapatkan waktu sekarang
$jam_sekarang = date('H:i:s');
$tanggal_mysql = date('Y-m-d');

// Baca tabel tmprfid
$baca_kartu = $konek->query("SELECT * FROM tmprfid LIMIT 1");
$data_kartu = $baca_kartu->fetch_assoc();
$nokartu = isset($data_kartu['nokartu']) ? $data_kartu['nokartu'] : "";

?>
<div class="container text-center mt-5">
    <h3>Absen: <?php echo htmlspecialchars($mode); ?></h3>
    <h3>Silahkan Tempelkan Kartu RFID Anda</h3>
    <img src="/asset/images/rfid.png" class="img-fluid" width="200">
    <br>
    <img src="/asset/images/animasi2.gif">

    <?php if ($nokartu == "") { ?>
        <!-- Menunggu kartu ditempel -->
    <?php } else {
        $stmt = $konek->prepare("SELECT * FROM mahasiswa WHERE nokartu = ?");
        $stmt->bind_param("s", $nokartu);
        $stmt->execute();
        $result_mahasiswa = $stmt->get_result();

        if ($result_mahasiswa->num_rows === 0) {
            echo "<h1>Kartu RFID Anda Tidak Terdaftar</h1>";
        } else {
            $data_mahasiswa = $result_mahasiswa->fetch_assoc();
            $nama = htmlspecialchars($data_mahasiswa['nama']);

            // Cek jadwal yang sedang berlangsung atau belum dimulai
            $stmt_jadwal = $konek->prepare("
                SELECT * FROM jadwal 
                WHERE hari = ? AND ? <= selesai 
                ORDER BY mulai ASC 
                LIMIT 1
            ");
            $stmt_jadwal->bind_param("ss", $hari_ini, $jam_sekarang);
            $stmt_jadwal->execute();
            $result_jadwal = $stmt_jadwal->get_result();

            if ($result_jadwal->num_rows > 0) {
                $data_jadwal = $result_jadwal->fetch_assoc();
                $jadwal_id = $data_jadwal['id'];
                $matkul = $data_jadwal['matkul'];
                $mulai = $data_jadwal['mulai'];
                $selesai = $data_jadwal['selesai'];

                // Cek apakah sudah absen
                $stmt_absen = $konek->prepare("SELECT * FROM absensi WHERE nokartu = ? AND tanggal = ? AND jadwal_id = ?");
                $stmt_absen->bind_param("ssi", $nokartu, $tanggal_mysql, $jadwal_id);
                $stmt_absen->execute();
                $result_absen = $stmt_absen->get_result();

                if ($mode_absen == 1) { // Mode Masuk
                    if ($result_absen->num_rows === 0) {
                        $status = "";
                        if ($jam_sekarang < $mulai) {
                            $status = "Tepat Waktu";
                        } elseif ($jam_sekarang >= $mulai && $jam_sekarang <= $selesai) {
                            $status = "Telat";
                        } elseif ($jam_sekarang > $selesai) {
                            $status = "Tidak Hadir";
                        }

                        $stmt_insert = $konek->prepare("INSERT INTO absensi (nokartu, mahasiswa_id, jadwal_id, tanggal, masuk, status) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt_insert->bind_param("siisss", $nokartu, $data_mahasiswa['id'], $jadwal_id, $tanggal_mysql, $jam_sekarang, $status);
                        $stmt_insert->execute();
                        echo "<h1>Selamat datang: $nama</h1>";
                        echo "<h3>Status: <span class='" . ($status == 'Tepat Waktu' ? 'text-success' : 'text-danger') . "'>$status</span></h3>";
                        echo "<h3>Waktu Masuk : $jam_sekarang</h3>";

                        echo "<h3>Mata Kuliah: $matkul</h3>";
                        echo "<h3>Jadwal: $mulai - $selesai</h3>";
                    } else {
                        echo "<h1>Maaf, $nama</h1>";
                        echo "<h3>Anda sudah absen hari ini!</h3>";
                    }
                } elseif ($mode_absen == 2) { // Mode Keluar
                    if ($result_absen->num_rows > 0) {
                        $stmt_update = $konek->prepare("UPDATE absensi SET keluar = ? WHERE nokartu = ? AND tanggal = ? AND jadwal_id = ?");
                        $stmt_update->bind_param("sssi", $jam_sekarang, $nokartu, $tanggal_mysql, $jadwal_id);
                        $stmt_update->execute();
                        echo "<h1>Selamat jalan: $nama</h1>";
                        echo "<h3>Waktu Keluar: $jam_sekarang</h3>";
                        echo "<h3>Mata Kuliah: $matkul</h3>";
                        echo "<h3>Jadwal: $mulai - $selesai</h3>";
                    } else {
                        echo "<h1>Maaf, $nama</h1>";
                        echo "<h3>Anda sudah absen hari ini!</h3>";
                    }

                    if ($result_absen->num_rows === 0) {
                        $status = "Tidak Hadir";
                        $stmt_insert = $konek->prepare("INSERT INTO absensi (nokartu, mahasiswa_id, jadwal_id, tanggal, masuk, status) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt_insert->bind_param("siisss", $nokartu, $data_mahasiswa['id'], $jadwal_id, $tanggal_mysql, $jam_sekarang, $status);
                        $stmt_insert->execute();

                        echo "<h1>Selamat datang: $nama</h1>";
                        echo "<h3>Waktu Masuk : $jam_sekarang</h3>";
                        echo "<h3>Status: <span class='text-warning'>Tidak Hadir</span></h3>";
                        echo "<h3>Karena Tidak Mengikuti Perkuliahan: $matkul</h3>";
                        echo "<h3>Jadwal: $mulai - $selesai</h3>";
                    }
                }
            } else {
                echo "<h1>Tidak ada jadwal aktif</h1>";
            }
        }
        $konek->query("DELETE FROM tmprfid");
    }
    ?>
</div>