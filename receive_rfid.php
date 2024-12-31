<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "absenrfid"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Periksa apakah ada data yang dikirim dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['rfid'])) {
        $rfid = strtoupper(trim($_POST['rfid'])); // Bersihkan data dan ubah ke huruf kapital

        // Langsung lakukan INSERT ke tabel
        $stmt = $conn->prepare("INSERT INTO tmprfid (nokartu) VALUES (?)");
        $stmt->bind_param("s", $rfid);

        if ($stmt->execute()) {
            echo "Data RFID berhasil disimpan.";
        } else {
            echo "Gagal menyimpan data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Data RFID tidak ditemukan atau kosong.";
    }
} else {
    echo "Metode request tidak valid.";
}

$conn->close();
