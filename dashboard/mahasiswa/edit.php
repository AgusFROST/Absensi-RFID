<?php
include "../session.php";

include "../../koneksi.php";

// Ambil id dari URL
$uid = $_GET['uid'];

// Ambil data mahasiswa berdasarkan id
$query = mysqli_query($konek, "SELECT * FROM mahasiswa WHERE uid = '$uid'");
$data = mysqli_fetch_assoc($query);

// Proses update data jika form disubmit
if (isset($_POST['btnUpdate'])) {

    $nokartu = $_POST['nokartu'];
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $kelas = $_POST['kelas'];

    // Update query
    $update = mysqli_query($konek, "UPDATE mahasiswa SET nokartu = '$nokartu', nama = '$nama', nim = '$nim', kelas = '$kelas' WHERE uid = '$uid'");

    if ($update) {
        $success_message = "Data Mahasiswa Berhasil Diupdate!";
    } else {
        $error_message = "Data Mahasiswa Gagal Diupdate!";
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Edit Mahasiswa</title>

    <?php include "../partials/head.php"; ?>

    <script>
        $(document).ready(function() {
            setInterval(function() {
                $("#norfid").load('nokartu.php');
            }, 0);
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
                    <h1 class="h2">Edit Mahasiswa</h1>
                </div>

                <!-- Content -->
                <div class="row">
                    <div class="col-lg-6">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nokartu" class="form-label">No Kartu</label>
                                <input type="text" class="form-control" id="nokartu" name="nokartu" value="<?php echo $data['nokartu']; ?>" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data['nama']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $data['nim']; ?>" maxlength="9" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo $data['kelas']; ?>" maxlength="5" required>
                            </div>

                            <button type="submit" class="btn btn-primary" name="btnUpdate" id="btnUpdate">Update</button>
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
                icon: 'danger',
                title: 'error!',
                text: '<?= $error_message ?>',
                showConfirmButton: true
            });
        <?php endif; ?>
    </script>
</body>

</html>