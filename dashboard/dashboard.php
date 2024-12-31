<?php

include "session.php";

include "../koneksi.php"; // Pastikan koneksi database sudah disertakan

// Query untuk menghitung total mahasiswa
$sql = "SELECT COUNT(*) AS total_mahasiswa FROM mahasiswa";
$result = $konek->query($sql);
$data = $result->fetch_assoc();
$total_mahasiswa = $data['total_mahasiswa'];

?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

  <?php include "partials/head.php"; ?>
</head>

<body>
  <?php include "partials/mode.php"; ?>
  <?php include "partials/header.php"; ?>

  <div class="container-fluid">
    <div class="row">

      <?php include "partials/sidebar.php"; ?>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Dashboard</h1>
        </div>

        <!-- content -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="alert alert-warning" role="alert">
              <h4 class="alert-heading"><?= $total_mahasiswa ?></h4>
              <p>Data Mahasiswa</p>
              <hr>
              <p class="mb-0">
                <a href="/dashboard/mahasiswa/mhs.php">
                  More info
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z" />
                  </svg>
                </a>
              </p>
            </div>
          </div>
      </main>
    </div>
  </div>

  <?php include "partials/script.php"; ?>



</body>

</html>