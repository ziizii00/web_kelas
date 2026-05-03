<?php
// Pengaturan Database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kelas_ti2b"; // Nama database sesuai file SQL Anda

// Membuat Koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek Koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>