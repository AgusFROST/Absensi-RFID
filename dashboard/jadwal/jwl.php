<?php
include "../session.php";

?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Jadwal</title>

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
                    <h1 class="h2">Jadwal</h1>
                </div>

                <a href="tambah.php" class="btn btn-primary mb-4">Tambah Jadwal</a>

                <table id="myTable" class="table table-bordered display">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 10px;">No</th>
                            <th scope="col" style="width: 400px;">Mata Kuliah</th>
                            <th scope="col" style="width: 600px;">Dosen</th>
                            <th scope="col">NIDN</th>
                            <th scope="col">Hari</th>
                            <th scope="col">Jam Masuk</th>
                            <th scope="col">Jam Keluar</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        include "../../koneksi.php";

                        $sql = mysqli_query($konek, "select * from jadwal");
                        $no = 0;
                        while ($data = mysqli_fetch_assoc($sql)) {
                            $no++;


                        ?>
                            <tr>
                                <th scope="row"><?php echo $no; ?></th>
                                <td><?php echo $data['matkul']; ?></td>
                                <td><?php echo $data['dosen']; ?></td>
                                <td><?php echo $data['nidn']; ?></td>
                                <td><?php echo $data['hari']; ?></td>
                                <td><?php echo $data['mulai']; ?></td>
                                <td><?php echo $data['selesai']; ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $data['id']; ?>">Edit</a> |
                                    <a href="#" onclick="hapusJadwal(<?php echo $data['id']; ?>)">Hapus</a>
                                </td>

                            </tr>
                        <?php   } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <?php include "../partials/script.php"; ?>

    <script>
        $('#myTable').DataTable({
            layout: {
                topStart: {
                    buttons: [{
                        extend: 'pdfHtml5',
                        title: 'Data Jadwal',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        // download: 'open',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    }]
                }
            }
        });

        function hapusJadwal(id) {
            // Menampilkan SweetAlert untuk konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Jadwal ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, redirect ke halaman hapus.php
                    window.location.href = 'hapus.php?id=' + id;
                }
            });
        }
    </script>
    <?php if (isset($_GET['message'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '<?= $_GET['message'] ?>',
                showConfirmButton: true
            });
        </script>
    <?php endif; ?>

</body>

</html>