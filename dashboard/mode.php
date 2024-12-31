<?php
include "session.php";
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mode = $_POST['mode'] ?? null;

    // Validasi input mode
    if ($mode && in_array($mode, ['1', '2'])) {
        // Update mode di tabel status
        $stmt = $konek->prepare("UPDATE status SET mode = ? WHERE id = 1");
        $stmt->bind_param("s", $mode);
        if ($stmt->execute()) {
            // Tentukan pesan sukses berdasarkan mode
            $modeText = ($mode === '1') ? 'Masuk' : 'Keluar';
            $successMessage = "Mode berhasil diperbarui menjadi Absen $modeText.";
        } else {
            $error = "Gagal memperbarui mode.";
        }
        $stmt->close();
    } else {
        $error = "Mode tidak valid.";
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Mode Absensi</title>

    <?php include "partials/head.php"; ?>
</head>

<body>
    <?php include "partials/mode.php"; ?>
    <?php include "partials/header.php"; ?>

    <div class="container-fluid">
        <div class="row">

            <?php include "partials/sidebar.php"; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Mode Absensi</h1>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <!-- Tampilkan Mode Saat Ini -->
                        <div class="mb-3">
                            <?php
                            $query = mysqli_query($konek, "SELECT mode FROM status WHERE id = 1 LIMIT 1");
                            $currentMode = mysqli_fetch_assoc($query)['mode'] ?? '1'; // Default ke mode 1
                            ?>
                            <h4>Mode saat ini: <strong><?= ($currentMode == '1') ? 'Absen Masuk' : 'Absen Keluar'; ?></strong></h4>
                        </div>

                        <!-- Form untuk Update Mode -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="mode" class="form-label">Pilih Mode Absen</label>
                                <select class="form-select" id="mode" name="mode" required>
                                    <option value="1" <?= ($currentMode == '1') ? 'selected' : ''; ?>>Masuk</option>
                                    <option value="2" <?= ($currentMode == '2') ? 'selected' : ''; ?>>Keluar</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Mode</button>
                        </form>
                    </div>
                </div>
            </main>

        </div>
    </div>

    <?php include "partials/script.php"; ?>

    <!-- Notifikasi SweetAlert -->
    <script>
        <?php if (!empty($successMessage)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Mode Berhasil Diubah!',
                text: '<?= $successMessage ?>',
                showConfirmButton: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "mode.php";
                }
            });
        <?php elseif (!empty($error)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= $error ?>',
                showConfirmButton: true
            });
        <?php endif; ?>
    </script>
</body>

</html>