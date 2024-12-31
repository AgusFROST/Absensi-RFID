<?php
// Mulai session
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Jika belum login, arahkan ke halaman login dengan pesan alert
    echo "<script>
        alert('Anda harus login terlebih dahulu!');
        window.location.href = '../authentication/login.php';
    </script>";
    exit; // Hentikan eksekusi script lebih lanjut
}

include "../koneksi.php"; // Pastikan koneksi database sudah disertakan

// Ambil data pengguna dari database berdasarkan ID yang disimpan di session
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $konek->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Variabel untuk pesan sukses atau error
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi apakah username atau email sudah terpakai
    $checkSql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND id != ?";
    $checkStmt = $konek->prepare($checkSql);
    $checkStmt->bind_param("ssi", $username, $email, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $message = 'Username atau email sudah digunakan oleh pengguna lain!';
    } else {
        // Update password jika ada
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT); // Enkripsi password baru
            $sql = "UPDATE users SET nama = ?, username = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $konek->prepare($sql);
            $stmt->bind_param("ssssi", $nama, $username, $email, $password, $user_id);
        } else {
            $sql = "UPDATE users SET nama = ?, username = ?, email = ? WHERE id = ?";
            $stmt = $konek->prepare($sql);
            $stmt->bind_param("sssi", $nama, $username, $email, $user_id);
        }

        if ($stmt->execute()) {
            $message = 'Profile berhasil diperbarui!';
            // Update data session
            $_SESSION['username'] = $username;
            $_SESSION['nama'] = $nama;
        } else {
            $message = 'Terjadi kesalahan saat memperbarui profile!';
        }
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Profile</title>
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
                    <h1 class="h2">Profile</h1>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <!-- Menampilkan pesan sukses atau error -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <?= $message ?>
                                <button type=" button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Menampilkan informasi profile -->
                        <div class="card">
                            <div class="card-body">
                                <form action="profile.php" method="POST">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $user['nama'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengganti)</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </main>
        </div>
    </div>

    <?php include "partials/script.php"; ?>

</body>

</html>