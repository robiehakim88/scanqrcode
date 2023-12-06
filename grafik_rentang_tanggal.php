<?php
$host = 'localhost';
$username = 'abse_abseni';
$password = 'absensi';
$database = 'abse_absensi';

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

// Set locale untuk bahasa Indonesia
setlocale(LC_TIME, 'id_ID');


// Set default date range if empty
if (empty($start_date) || empty($end_date)) {
    $start_date = date('Y-m-d', strtotime('-7 days')); // default to 7 days ago
    $end_date = date('Y-m-d'); // default to today
}

$query_absensi = "SELECT DISTINCT(DATE(waktu)) as tanggal FROM absensi WHERE DATE(waktu) BETWEEN '$start_date' AND '$end_date' ORDER BY DATE(waktu)";
$result_absensi = $koneksi->query($query_absensi);

$tanggal = [];

//while ($row_absensi = $result_absensi->fetch_assoc()) {
//    $tanggal[] = $row_absensi['tanggal'];
//}

//while ($row_absensi = $result_absensi->fetch_assoc()) {
    // Ubah format tanggal menjadi nama hari, tanggal bulan tahun
//    $tanggal_label = date('l, j F Y', strtotime($row_absensi['tanggal']));
//    $tanggal[] = $tanggal_label;
//}


while ($row_absensi = $result_absensi->fetch_assoc()) {
    // Menggunakan strftime untuk mendapatkan tanggal dalam bahasa Indonesia
    $tanggal_label = strftime('%A, %e %B %Y', strtotime($row_absensi['tanggal']));
    $tanggal[] = $tanggal_label;
}

$daftar_kelas = [
    'x-tkj-1', 'x-tkj-2', 'x-tkj-3', 'xi-tkj-1', 'xi-tkj-2', 'xii-tkj-1', 'xii-tkj-2',
    'x-akl', 'xi-akl', 'x-te', 'xi-te', 'x-tokr-1', 'x-tokr-2', 'x-tokr-3', 'xi-tokr-1',
    'xi-tokr-2', 'xi-tokr-3', 'xii-tkro-1', 'xii-tkro-2', 'x-tosm-1', 'x-tosm-2',
    'x-tosm-3', 'x-tosm-4', 'xi-tosm-1', 'xi-tosm-2', 'xi-tosm-3', 'xi-tosm-4',
    'xii-tbsm-1', 'xii-tbsm-2', 'xii-tbsm-3', 'xii-tbsm-4', 'xii-ps', 'xii-tav'
];

$data_kelas = [];

$colors = ['red', 'blue', 'green', 'orange', 'purple', 'brown', 'pink', 'cyan', 'lime', 'teal', 'indigo', 'deeppink', 'gold', 'slategray', 'darkkhaki', 'navy', 'tomato', 'mediumspringgreen', 'sienna', 'lightcoral', 'dodgerblue', 'mediumvioletred', 'seagreen', 'orangered', 'steelblue', 'orchid', 'turquoise', 'salmon', 'darkolivegreen', 'royalblue', 'crimson', 'darkorange', 'mediumseagreen'];

foreach ($daftar_kelas as $kelas) {
    $query_absensi_kelas = "SELECT DISTINCT(date_table.tanggal) as tanggal, IFNULL(SUM(absensi.total), 0) as total
                            FROM (
                                SELECT DATE(waktu) as tanggal
                                FROM absensi
                                WHERE DATE(waktu) BETWEEN '$start_date' AND '$end_date'  -- Filtering by date range
                                GROUP BY DATE(waktu)
                            ) date_table
                            LEFT JOIN (
                                SELECT DATE(waktu) as tanggal, COUNT(kode_siswa) as total
                                FROM absensi
                                WHERE kode_siswa LIKE '%$kelas%'
                                AND DATE(waktu) BETWEEN '$start_date' AND '$end_date'  -- Filtering by date range
                                GROUP BY DATE(waktu)
                            ) absensi ON date_table.tanggal = absensi.tanggal
                            GROUP BY date_table.tanggal";
    $result_absensi_kelas = $koneksi->query($query_absensi_kelas);

    $labels = [];
    $data = [];

//    while ($row_absensi_kelas = $result_absensi_kelas->fetch_assoc()) {
//        $labels[] = $row_absensi_kelas['tanggal'];
//        $data[] = $row_absensi_kelas['total'];
//    }
  
  
//  while ($row_absensi_kelas = $result_absensi_kelas->fetch_assoc()) {
        // Ubah format tanggal menjadi nama hari, tanggal bulan tahun
//        $tanggal_label = date('l, j F Y', strtotime($row_absensi_kelas['tanggal']));
//        $labels[] = $tanggal_label;
//        $data[] = $row_absensi_kelas['total'];
//    }
  
  while ($row_absensi_kelas = $result_absensi_kelas->fetch_assoc()) {
        // Menggunakan strftime untuk mendapatkan tanggal dalam bahasa Indonesia
        $tanggal_label = strftime('%A, %e %B %Y', strtotime($row_absensi_kelas['tanggal']));
        $labels[] = $tanggal_label;
        $data[] = $row_absensi_kelas['total'];
    }

    $data_kelas[$kelas] = [
        'labels' => $labels,
        'data' => $data,
        'color' => $colors[array_search($kelas, $daftar_kelas)],
    ];
}

$koneksi->close();



?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Data Keterlambatan Per Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
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

    <h1>Rekap Data Keterlambatan Siswa Sesuai Jurusan</h1>
    <h2>SMK Taruna Bakti Kertosono</h2>

    <form method="GET" action="">
        <label for="start_date">Tanggal Awal:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">

        <label for="end_date">Tanggal Akhir:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">

        <input type="submit" value="Filter">
    </form>

    <div style="width: 1000px; height: 500px;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($tanggal); ?>,
                datasets: [
                    <?php foreach ($data_kelas as $kelas => $data) { ?>
                        {
                            label: '<?php echo $kelas; ?>',
                            data: <?php echo json_encode($data['data']); ?>,
                            fill: false,
                            tension: 0.1,
                            borderColor: '<?php echo $data['color']; ?>'
                        },
                    <?php } ?>
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


  
  
  
  <h1>Analisis Keterlambatan Siswa Per Kelas</h1>

    <?php
    // ... (kode sebelumnya)

    // Analisis Data
    $analisis_kelas = [];
    foreach ($data_kelas as $kelas => $data) {
        $total_keterlambatan = array_sum($data['data']);
        $rata_rata_keterlambatan = $total_keterlambatan / count($data['data']);

        $analisis_kelas[$kelas] = [
            'total' => $total_keterlambatan,
            'rata_rata' => $rata_rata_keterlambatan,
        ];
    }

    // Mengambil kelas dan nilai analisis sebagai label dan data untuk grafik
    $labels = array_keys($analisis_kelas);
    $totalValues = array_column($analisis_kelas, 'total');
    $rataRataValues = array_column($analisis_kelas, 'rata_rata');
    ?>

    <div style="width: 800px; height: 400px;">
        <canvas id="analisisChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('analisisChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Keterlambatan',
                    data: <?php echo json_encode($totalValues); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Rata-rata Keterlambatan',
                    data: <?php echo json_encode($rataRataValues); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        stacked: true,
                        beginAtZero: true
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });
    </script>
  
  
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>
