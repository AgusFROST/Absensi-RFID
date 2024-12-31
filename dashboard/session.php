<?php
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Jika belum login, arahkan langsung ke halaman login
    header("Location: /authentication/login.php");
    exit; // Pastikan eksekusi berhenti setelah redirect
}
