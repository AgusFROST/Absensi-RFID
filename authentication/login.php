<?php
session_start();
include "../koneksi.php";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: ../dashboard/dashboard.php"); // Redirect jika sudah login
    exit;
}

$error = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username atau email
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $konek->prepare($sql);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];

            // Set success message untuk SweetAlert2
            $successMessage = "Login Berhasil! Selamat datang, " . $user['nama'] . "!";
        } else {
            $error = "Username/Email atau password salah!";
        }
    } else {
        $error = "Username/Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../partials/head.php"; ?>

    <title>Login</title>
</head>

<body>
    <?php include "../partials/navbar.php"; ?>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <h1 class="text-center mt-5 mb-2">Login</h1>

                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="username_or_email">Username atau Email:</label>
                        <input type="text" class="form-control" id="username_or_email" name="username_or_email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Login</button>
                </form>

                <div class="d-flex justify-content-center w-100 mt-4">
                    <span>Belum punya akun? <a href="register.php">Daftar di sini</a></span>
                </div>
            </div>
        </div>
    </main>

    <?php include "../partials/footer.php"; ?>
    <?php include "../partials/js.php"; ?>

    <script>
        // Menampilkan SweetAlert2 jika login berhasil atau gagal
        <?php if (!empty($successMessage)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: '<?= $successMessage ?>',
                showConfirmButton: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../dashboard/dashboard.php";
                }
            });
        <?php elseif (!empty($error)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal!',
                text: '<?= $error ?>',
                showConfirmButton: true
            });
        <?php endif; ?>
    </script>
</body>

</html>