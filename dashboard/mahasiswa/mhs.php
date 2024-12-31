<?php
include "../session.php";
include "../../koneksi.php";

// Cek apakah ada parameter uid untuk penghapusan
if (isset($_GET['uid']) && !empty($_GET['uid'])) {
    $uid = $_GET['uid'];

    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $konek->prepare("SELECT uid FROM mahasiswa WHERE uid = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika uid ada di database, lakukan penghapusan
    if ($result->num_rows > 0) {
        $stmt = $konek->prepare("DELETE FROM mahasiswa WHERE uid = ?");
        $stmt->bind_param("s", $uid);

        if ($stmt->execute()) {
            $success_message = "Data berhasil dihapus";
        } else {
            $error = "Gagal menghapus data";
        }
    } else {
        $error = "UID tidak ditemukan di database";
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Data Mahasiswa</title>

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
                    <h1 class="h2">Data Mahasiswa</h1>
                </div>

                <a href="tambah.php" class="btn btn-primary mb-4">Tambah Data Mahasiswa</a>

                <table id="myTable" class="table table-bordered display">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 10px;">No</th>
                            <th scope="col" style="width: 200px;">No Kartu</th>
                            <th scope="col" style="width: 800px;">Nama</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($konek, "SELECT * FROM mahasiswa");
                        $no = 0;
                        while ($data = mysqli_fetch_assoc($sql)) {
                            $no++;
                        ?>
                            <tr>
                                <th scope="row"><?php echo $no; ?></th>
                                <td><?php echo $data['nokartu']; ?></td>
                                <td><?php echo $data['nama']; ?></td>
                                <td><?php echo $data['jenis_kelamin']; ?></td>
                                <td><?php echo $data['nim']; ?></td>
                                <td><?php echo $data['kelas']; ?></td>
                                <td>
                                    <a href="edit.php?uid=<?php echo $data['uid']; ?>">Edit</a> |
                                    <a href="?uid=<?php echo $data['uid']; ?>" class="btn-hapus" data-uid="<?php echo $data['uid']; ?>">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
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
                        title: 'Data Mahasiswa',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    }]
                }
            }
        });

        // Hapus data dengan konfirmasi
        document.addEventListener("DOMContentLoaded", function() {
            const hapusButtons = document.querySelectorAll(".btn-hapus");

            hapusButtons.forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault(); // Mencegah pengalihan langsung

                    const uid = this.getAttribute("data-uid");

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `?uid=${uid}`;
                        }
                    });
                });
            });
        });
    </script>

    <?php if (!empty($success_message)): ?>
        <script>
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
        </script>
    <?php elseif (!empty($error)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= $error ?>',
                showConfirmButton: true
            });
        </script>
    <?php endif; ?>

</body>

</html>