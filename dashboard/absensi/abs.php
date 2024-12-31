<?php

include "../session.php";

?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Rekapitulasi Absensi</title>

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
                    <h1 class="h2">Data Rekapitulasi Absensi</h1>
                </div>

                <table id="myTable" class="table table-bordered display">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 10px;">No</th>
                            <th scope="col" style="width: 150px;">No Kartu</th>
                            <th scope="col" style="width: 400px;">Nama</th>
                            <th scope="col" style="width: 400px;">Mata Kuliah</th>
                            <th scope="col" style="width: 150px;">Status</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jam Masuk</th>
                            <th scope="col">Jam Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../../koneksi.php";

                        // Query dengan JOIN untuk mengambil data dari tabel absensi, mahasiswa, dan jadwal
                        $sql = mysqli_query($konek, "
                            SELECT 
                                absensi.id,
                                mahasiswa.nokartu,
                                mahasiswa.nama,
                                jadwal.matkul,
                                absensi.status,
                                absensi.tanggal,
                                absensi.masuk,
                                absensi.keluar
                            FROM 
                                absensi
                            JOIN mahasiswa ON absensi.mahasiswa_id = mahasiswa.id
                            JOIN jadwal ON absensi.jadwal_id = jadwal.id
                        ");

                        $no = 0;
                        while ($data = mysqli_fetch_assoc($sql)) {
                            $no++;
                        ?>
                            <tr>
                                <th scope="row"><?php echo $no; ?></th>
                                <td><?php echo $data['nokartu']; ?></td>
                                <td><?php echo $data['nama']; ?></td>
                                <td><?php echo $data['matkul']; ?></td>
                                <td class="text-center">
                                    <?php if ($data['status'] == 'Tepat Waktu') { ?>
                                        <span class="badge text-bg-success">
                                            <?php echo $data['status']; ?>
                                        </span>
                                    <?php } elseif ($data['status'] == 'Telat') { ?>
                                        <span class="badge text-bg-danger">
                                            <?php echo $data['status']; ?>
                                        </span>
                                    <?php } else { ?>
                                        <span class="badge text-bg-warning">
                                            <?php echo $data['status']; ?>
                                        </span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $data['tanggal']; ?></td>
                                <td><?php echo $data['masuk']; ?></td>
                                <td><?php echo $data['keluar']; ?></td>
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
                            title: 'Rekapitulasi Absensi',
                            orientation: 'portrait',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Rekapitulasi Absensi',
                            orientation: 'portrait',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        }
                    ]
                }
            }
        });
    </script>
</body>

</html>