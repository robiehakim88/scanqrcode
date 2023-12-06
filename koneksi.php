<?php
// Konfigurasi koneksi ke database
$servername = "localhost";
$username = "abse_abseni";
$password = "absensi";
$dbname = "abse_absensi";

// Buat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>