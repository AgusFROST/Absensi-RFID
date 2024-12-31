<?php
include "../session.php";
include "../../koneksi.php";

// Ambil data jadwal berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $jadwal = mysqli_query($konek, "SELECT * FROM jadwal WHERE id = '$id'");
    $data = mysqli_fetch_assoc($jadwal);
}

// Proses update data jadwal
if (isset($_POST['btnUpdate'])) {
    $id = $_POST['id'];
    $matkul = $_POST['matkul'];
    $dosen = $_POST['dosen'];
    $nidn = $_POST['nidn'];
    $hari = $_POST['hari'];
    $mulai = $_POST['mulai'];
    $selesai = $_POST['selesai'];

    // Validasi apakah jadwal bentrok dengan jadwal lain
    $cek_jadwal = mysqli_query($konek, "SELECT * FROM jadwal WHERE matkul = '$matkul' AND dosen = '$dosen' AND hari = '$hari' AND mulai = '$mulai' AND selesai = '$selesai' AND id != '$id'");

    if (mysqli_num_rows($cek_jadwal) > 0) {
        $error_message = "Jadwal sudah ada";
    } else {
        // Update jadwal
        $update = mysqli_query($konek, "UPDATE jadwal SET matkul = '$matkul', dosen = '$dosen', nidn = '$nidn', hari = '$hari', mulai = '$mulai', selesai = '$selesai' WHERE id = '$id'");

        if ($update) {
            $success_message = "Jadwal berhasil diupdate";
        } else {
            $error_message = "Gagal mengupdate jadwal";
        }
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Jadwal</title>

    <?php include "../partials/head.php"; ?>
</head>

<body>
    <?php include "../partials/mode.php"; ?>
    <?php include "../partials/header.php"; ?>

    <div class="container-fluid">
        <div class="row">

            <?php include "../partials/sidebar.php"; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Jadwal</h1>
                </div>

                <!-- Content -->
                <div class="row">
                    <div class="col-lg-6">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $data['id'] ?>">

                            <div class="mb-3">
                                <label for="matkul" class="form-label">Mata Kuliah</label>
                                <input type="text" class="form-control" id="matkul" name="matkul" value="<?= $data['matkul'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="dosen" class="form-label">Dosen</label>
                                <input type="text" class="form-control" id="dosen" name="dosen" value="<?= $data['dosen'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nidn" class="form-label">NIDN</label>
                                <input type="text" class="form-control" id="nidn" name="nidn" value="<?= $data['nidn'] ?>" maxlength="10" required>
                            </div>
                            <div class="mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <select class="form-select" id="hari" name="hari" required>
                                    <option value="Senin" <?= $data['hari'] == 'Senin' ? 'selected' : '' ?>>Senin</option>
                                    <option value="Selasa" <?= $data['hari'] == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                                    <option value="Rabu" <?= $data['hari'] == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                                    <option value="Kamis" <?= $data['hari'] == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                                    <option value="Jumat" <?= $data['hari'] == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                                    <option value="Sabtu" <?= $data['hari'] == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
                                    <option value="Minggu" <?= $data['hari'] == 'Minggu' ? 'selected' : '' ?>>Minggu</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="mulai" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="mulai" name="mulai" value="<?= $data['mulai'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="selesai" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="selesai" name="selesai" value="<?= $data['selesai'] ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
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
        <?php elseif (!empty($error_message)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= $error_message ?>',
                showConfirmButton: true
            });
        <?php endif; ?>
    </script>
</body>

</html>