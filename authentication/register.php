<?php
session_start();
include "../koneksi.php"; // Pastikan koneksi database sudah disertakan

// Jika user sudah login, arahkan ke dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: ../dashboard/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Enkripsi password

    // Cek apakah username atau email sudah terdaftar
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $konek->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username atau Email sudah terdaftar!";
    } else {
        // Query untuk menyimpan data pengguna baru
        $sql = "INSERT INTO users (nama, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $konek->prepare($sql);
        $stmt->bind_param("ssss", $nama, $username, $email, $password);

        if ($stmt->execute()) {
            // Jika berhasil, tampilkan SweetAlert2 sukses dan arahkan ke halaman login
            $_SESSION['registration_success'] = "Registrasi berhasil! Silakan login.";
            header("Location: register.php"); // Reload halaman agar SweetAlert2 muncul
            exit();
        } else {
            $error = "Gagal mendaftar. Coba lagi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../partials/head.php"; ?>

    <title>Register</title>
</head>

<body>
    <?php include "../partials/navbar.php"; ?>


    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h1 class="text-center mt-5">Register</h1>

                <!-- Form Registrasi -->
                <form action="register.php" method="POST">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Register</button>
                </form>

                <div class="d-flex justify-content-center w-100 mt-4">
                    <span>Sudah punya akun? <a href="login.php">Login di sini</a></span>
                </div>
            </div>
        </div>
    </main>

    <?php include "../partials/footer.php"; ?>
    <?php include "../partials/js.php"; ?>

    <script>
        // Menampilkan SweetAlert2 jika terjadi error
        <?php if (isset($error)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal!',
                text: '<?= $error ?>',
                backdrop: 'rgba(0, 0, 0, 0.7)', // Static backdrop
                showConfirmButton: true
            });
        <?php endif; ?>

        // Menampilkan SweetAlert2 jika registrasi berhasil
        <?php if (isset($_SESSION['registration_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Registrasi Berhasil!',
                text: '<?= $_SESSION['registration_success'] ?>',
                showConfirmButton: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php"; // Redirect ke halaman login
                }
            });
            <?php unset($_SESSION['registration_success']); ?>
        <?php endif; ?>
    </script>
</body>

</html>