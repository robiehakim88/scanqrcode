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

// Query untuk mendapatkan daftar kode kelas
$query_kelas = "SELECT DISTINCT jurusan FROM kelas";
$result_kelas = $koneksi->query($query_kelas);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
</head>
<body>

<!-- Dropdown untuk memilih kode kelas -->
<form method="get" action="">
    <select name="selected_kelas" class="form-select" aria-label="Pilih Kode Kelas">
        <option selected>Pilih Kode Kelas</option>
        <?php
        // Menampilkan opsi untuk setiap kode kelas
        while ($row_kelas = $result_kelas->fetch_assoc()) {
            $kode_kelas = $row_kelas['jurusan'];
            echo "<option value=\"$kode_kelas\">$kode_kelas</option>";
        }
        ?>
    </select>
    <input type="submit" value="Submit">
</form>

<!-- Bagian tabel data absensi -->
<?php
// Jika ada pilihan kode kelas yang dipilih
if (isset($_GET['selected_kelas'])) {
    $selected_kelas = $_GET['selected_kelas'];

    // Query untuk mendapatkan data absensi berdasarkan kode kelas yang dipilih
    $query_absensi = "SELECT absensi.kode_siswa, absensi.waktu, data_siswa.nama
                     FROM absensi
                     INNER JOIN data_siswa ON absensi.kode_siswa = data_siswa.kode_siswa
                     WHERE absensi.kode_siswa LIKE '%$selected_kelas%'";
    $result_absensi = $koneksi->query($query_absensi);

    if ($result_absensi->num_rows > 0) {
        // Output header laporan
        echo "<table border='1' id='tbl_exporttable_to_xls'>
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
        echo "Tidak ada data absensi untuk kode kelas ini.";
    }
}

// Tutup koneksi database
$koneksi->close();
?>
<button onclick="ExportToExcel('xlsx')">Unduh File Excel</button>

<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('tbl_exporttable_to_xls');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
            XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
