<?php
// Koneksi ke database (ganti informasi koneksi sesuai dengan konfigurasi Anda)
$host = 'localhost';
$username = 'abse_abseni';
$password = 'absensi';
$database = 'abse_absensi';

$koneksi = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Data Keterlambatan Berdasarkan Tanggal Tertentu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
      
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="https://absensi.smktb.sch.id">Absensi</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
       
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Rekap Data
          </a>
           <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/pencarian_siswa.php">Pencarian Barcode</a></li>
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/rekap_perjurusan.php">Berdasarkan Perjurusan</a></li>
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/rekap_perhari.php">Berdasarkan Tanggal</a></li>
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/rekap_range_hari.php">Berdasarkan Rentang Tanggal</a></li>
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/rekap_pertanggal.php">Berdasarkan Perjurusan dan Tanggal</a></li>
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/rekap_beberapa_hari.php">Berdasarkan Perjurusan dan Rentang Tanggal</a></li>
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/rekap_absensi_all.php">Semua Data</a></li>
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/grafik_rentang_tanggal.php">Grafik Berdasarkan Rentang Tanggal</a></li>
             <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/grafik_perjurusan.php">Grafik Semua Siswa Terlambat</a></li>
             
          </ul>
        </li>
        
      </ul>
    </div>
  </div>
</nav>
    </header>

<h1>Rekap Data Keterlambatan Siswa Berdasarkan Tanggal Tertentu</h1>
<h2>SMK Taruna Bakti Kertosono</h2>

<!-- Form untuk memilih tanggal -->
<form method="get" action="">
    <label for="tanggal">Pilih Tanggal:</label>
    <input type="date" id="tanggal" name="tanggal">
    <input type="submit" value="Submit">
</form>

<!-- Bagian tabel data absensi -->
<?php
// Jika ada tanggal yang dipilih
if (isset($_GET['tanggal'])) {
    $selected_date = $_GET['tanggal'];

    // Query untuk mendapatkan data absensi berdasarkan tanggal yang dipilih
    $query_absensi = "SELECT absensi.kode_siswa, absensi.waktu, data_siswa.nama
                     FROM absensi
                     INNER JOIN data_siswa ON absensi.kode_siswa = data_siswa.kode_siswa
                     WHERE DATE(absensi.waktu) = '$selected_date'";
    $result_absensi = $koneksi->query($query_absensi);

    if ($result_absensi->num_rows > 0) {
    
        // Hitung jumlah keterlambatan
        $jumlah_keterlambatan = $result_absensi->num_rows;

        // Output header laporan
        echo "<h3>Jumlah Keterlambatan pada Tanggal $selected_date adalah : $jumlah_keterlambatan Siswa</h3>";
        
        // Output header tabel
        echo "<table id='tbl_exporttable_to_xls' class='table table-striped table-hover'>
            <thead>
                <th>Kode Siswa</th>
                <th>Nama Lengkap</th>
                <th>Tanggal Terlambat</th>
            </thead>
            <tbody>";

        // Output data dari setiap baris
        while ($row_absensi = $result_absensi->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row_absensi['kode_siswa'] . "</td>";
            echo "<td>" . $row_absensi['nama'] . "</td>";
            echo "<td>" . $row_absensi['waktu'] . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "Tidak ada data absensi untuk tanggal tersebut.";
    }
}

// Tutup koneksi database
$koneksi->close();
?>

<!-- Tombol untuk mengunduh file Excel -->
<button onclick="ExportToExcel('xlsx')">Unduh File Excel</button>

<!-- Skrip JavaScript untuk fungsi ExportToExcel -->
<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('tbl_exporttable_to_xls');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
            XLSX.writeFile(wb, fn || ('Rekap_Keterlambatan.' + (type || 'xlsx')));
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
