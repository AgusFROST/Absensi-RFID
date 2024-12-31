<?php
include "../session.php";
include "../../koneksi.php";

if (isset($_POST['btnSimpan'])) {
    $matkul = $_POST['matkul'];
    $dosen = $_POST['dosen'];
    $nidn = $_POST['nidn'];
    $hari = $_POST['hari'];
    $mulai = $_POST['mulai'];
    $selesai = $_POST['selesai'];

    // Validasi apakah jadwal sudah ada
    $cek_jadwal = mysqli_query($konek, "SELECT * FROM jadwal WHERE matkul = '$matkul' AND dosen = '$dosen' AND hari = '$hari' AND mulai = '$mulai' AND selesai = '$selesai'");

    if (mysqli_num_rows($cek_jadwal) > 0) {
        $warning_message = "Jadwal sudah terdaftar";
    } else {
        // Validasi apakah mata kuliah sudah ada dengan dosen yang sama
        $cek_matkul = mysqli_query($konek, "SELECT * FROM jadwal WHERE matkul = '$matkul' AND dosen = '$dosen'");

        if (mysqli_num_rows($cek_matkul) > 0) {
            $warning_message = "Mata kuliah sudah terdaftar!";
        } else {
            // Simpan ke tabel jadwal
            $simpan = mysqli_query($konek, "INSERT INTO jadwal(matkul, dosen, nidn, hari, mulai, selesai) VALUES('$matkul', '$dosen', '$nidn', '$hari', '$mulai', '$selesai')");

            if ($simpan) {
                $success_message = "Jadwal berhasil disimpan";
            } else {
                $error_message = "Jadwal gagal disimpan";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Tambah Jadwal</title>

    <?php include "../partials/head.php"; ?>
</head>

<body>
    <?php include "../partials/mode.php"; ?>
    <?php include "../partials/header.php"; ?>

    <div class="container-fluid">
        <div class="row">

            <?php include "../partials/sidebar.php"; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tambah Jadwal</h1>
                </div>

                <!-- Content -->
                <div class="row">
                    <div class="col-lg-6">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="matkul" class="form-label">Mata Kuliah</label>
                                <input type="text" class="form-control" id="matkul" name="matkul" required>
                            </div>
                            <div class="mb-3">
                                <label for="dosen" class="form-label">Dosen</label>
                                <input type="text" class="form-control" id="dosen" name="dosen" required>
                            </div>
                            <div class="mb-3">
                                <label for="nidn" class="form-label">NIDN</label>
                                <input type="text" class="form-control" id="nidn" name="nidn" maxlength="10" required>
                            </div>
                            <div class="mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <select class="form-select" id="hari" name="hari" required>
                                    <option value="" disabled selected>Pilih Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="mulai" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="mulai" name="mulai" required>
                            </div>
                            <div class="mb-3">
                                <label for="selesai" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="selesai" name="selesai" required>
                            </div>

                            <button type="submit" class="btn btn-primary" name="btnSimpan" id="btnSimpan">Submit</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include "../partials/script.php"; ?>
    <script>
        <?php if (!empty($success_message)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $success_message ?>',
                showConfirmButton: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "jwl.php";
                }
            });
        <?php elseif (!empty($warning_message)): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: '<?= $warning_message ?>',
                showConfirmButton: true
            });
        <?php elseif (!empty($error_message)): ?>
            Swal.fire({
                icon: 'danger',
                title: 'Gagal!',
                text: '<?= $error_message ?>',
                showConfirmButton: true
            });
        <?php endif; ?>
    </script>

</body>

</html>