<?php
include "../session.php";
include "../../koneksi.php";

if (isset($_POST['btnSimpan'])) {
    $uid = uniqid();
    $nokartu = $_POST['nokartu'];
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $kelas = $_POST['kelas'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $error_message = null;

    // Cek apakah nokartu atau nim sudah ada
    $stmt = $konek->prepare("SELECT nokartu, nim FROM mahasiswa WHERE nokartu = ? OR nim = ?");
    $stmt->bind_param("ss", $nokartu, $nim);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if ($row['nokartu'] === $nokartu) {
            $error_message = "Nomor Kartu Telah Terdaftar!";
        } elseif ($row['nim'] === $nim) {
            $error_message = "Nim Sudah Terdaftar!";
        }
    }

    if (!$error_message) {
        // Insert data mahasiswa
        $stmt = $konek->prepare("INSERT INTO mahasiswa (uid, nokartu, nama, nim, kelas, jenis_kelamin) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $uid, $nokartu, $nama, $nim, $kelas, $jenis_kelamin);
        $insert_result = $stmt->execute();

        if ($insert_result) {
            // Hapus data dari tmprfid
            $stmt = $konek->prepare("DELETE FROM tmprfid");
            $delete_result = $stmt->execute();

            if ($delete_result) {
                $success_message = "Data Mahasiswa Berhasil Ditambah";
            } else {
                $tmprfid = "Data tmp gagal terhapus";
            }
        } else {
            $error = "Data Mahasiswa gagal Ditambah";
        }
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Tambah Mahasiswa</title>

    <?php include "../partials/head.php"; ?>

    <script>
        $(document).ready(function() {
            setInterval(function() {
                $("#norfid").load('nokartu.php');
            }, 1000);
        });
    </script>
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
                    <h1 class="h2">Tambah Mahasiswa</h1>
                </div>

                <!-- Content -->
                <div class="row">
                    <div class="col-lg-6">
                        <form method="POST">
                            <div id="norfid"></div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim" name="nim" maxlength="9" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas" maxlength="5" required>
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
                    window.location.href = "mhs.php";
                }
            });
        <?php elseif (!empty($error_message)): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: '<?= $error_message ?>',
                showConfirmButton: true
            });
        <?php elseif (!empty($tmprfid)): ?>
            Swal.fire({
                icon: 'danger',
                title: 'error!',
                text: '<?= $tmprfid ?>',
                showConfirmButton: true
            });
        <?php elseif (!empty($error)): ?>
            Swal.fire({
                icon: 'danger',
                title: 'error!',
                text: '<?= $error ?>',
                showConfirmButton: true
            });
        <?php endif; ?>
    </script>
</body>

</html>