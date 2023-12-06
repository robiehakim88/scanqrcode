<?php
// Koneksi ke database
$servername = "localhost";
$username = "abse_abseni";
$password = "absensi";
$dbname = "abse_absensi";
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan nomor absen dari parameter GET
$kode_siswa = $_GET['kode_siswa'];

// Melakukan query ke database untuk mendapatkan data berdasarkan nomor absen
$sql = "SELECT * FROM data_siswa WHERE kode_siswa = '$kode_siswa'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Jika data ditemukan, menampilkan informasi dalam format yang diinginkan
    while($row = $result->fetch_assoc()) {
        echo "<b>Nama Lengkap: </b><br />" . $row["nama"] . "<br>";
       // echo "Kelas: " . $row["kelas"] . "<br>";
        // Tambahkan informasi lain yang diinginkan
    }
} else {
    echo "Data tidak ditemukan";
}

$conn->close();
?>
