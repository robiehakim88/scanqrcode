<?php
// Termasuk file koneksi database
include 'koneksi.php';

// Ambil nilai yang diinput dari form
$kode_siswa = $_POST['kode_siswa'];
$waktu = $_POST['waktu'];

// Query untuk menyimpan data siswa ke dalam tabel 'Siswa'
$sql_siswa = "INSERT INTO absensi (kode_siswa, waktu) VALUES ('$kode_siswa', '$waktu')";

//if ($conn->query($sql_siswa) === TRUE) {
//    $id_siswa = $conn->insert_id; // Ambil ID siswa yang baru saja diinput
    
    // Query untuk menyimpan waktu input ke dalam tabel 'Input_Siswa'
  //  $waktu_input = date('Y-m-d'); // Waktu saat ini
    //$sql_input = "INSERT INTO absensi (kode_siswa, waktu) VALUES ('$kode_siswa', '$waktu')";
    
    if ($conn->query($sql_siswa) === TRUE) {
        echo "<h1 align='center'>Data berhasil disimpan.</h1>";
    } else {
        echo "Error: " . $sql_siswa . "<br>" . $conn->error;
    }
//} 
//else {
//    echo "Error: " . $sql_siswa . "<br>" . $conn->error;
//}

// Tutup koneksi ke database
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Keterlambatan SMK Taruna Bakti Kertosono</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">



</head>
<body>
    <div align="center"></div>
<a href="https://absensi.smktb.sch.id"><button type="button" class="btn btn-primary btn-lg">Kembali Ke Halaman Utama</button></a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>