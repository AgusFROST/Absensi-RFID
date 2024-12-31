<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "partials/head.php"; ?>
    <title>Absensi | Scan Kartu</title>

    <!-- Scanning membaca kartu rfid -->
    <script>
        $(document).ready(function() {
            setInterval(function() {
                $("#cekkartu").load('bacakartu.php');
            }, 5000);
        });
    </script>
</head>

<body>
    <header>
        <?php include "partials/navbar.php"; ?>
    </header>

    <!-- isi -->
    <main class="container">
        <div id="cekkartu"></div>
    </main>


    <?php include "partials/footer.php"; ?>
    <?php include "partials/js.php"; ?>
</body>

</html>