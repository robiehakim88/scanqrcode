<?php
// Koneksi ke database
$servername = "localhost";
$username = "abse_abseni";
$password = "absensi";
$dbname = "abse_absensi";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan nilai yang dikirim melalui AJAX
if (isset($_GET['kode_siswa'])) {
    $kode_siswa = $_GET['kode_siswa'];

    // Membagi nilai kode_siswa menjadi jenjang, jurusan, kelas, dan no_absen
    $data_siswa = explode("-", $kode_siswa);

    // Melindungi dari SQL Injection dengan prepared statement
    $sql = "SELECT barcode FROM tabel_siswa WHERE jenjang = ? AND jurusan = ? AND kelas = ? AND nama = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $data_siswa[0], $data_siswa[1], $data_siswa[2], $data_siswa[3]);
    $stmt->execute();
    $result = $stmt->get_result();

    // Memeriksa apakah hasil ditemukan
    if ($result->num_rows > 0) {
        // Menampilkan hasil pencarian
        while ($row = $result->fetch_assoc()) {
            echo "<img src='" . $row['barcode'] . "' alt='Barcode'>";
        }
    } else {
        echo "Data tidak ditemukan";
    }
    $stmt->close();
    $conn->close();
}
?>
