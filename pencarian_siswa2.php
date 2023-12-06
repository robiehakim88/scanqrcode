<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Barcode Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">



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
            Cari Barcode
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="https://absensi.smktb.sch.id/pencarian_siswa2.php">Pencarian Barcode</a></li>
</ul>
        </li>
        
      </ul>
    </div>
  </div>
</nav>
    </header>
    <h1>Pencarian Barcode Siswa</h1>
       
     <form action="" method="post">

 * Isi dengan format Jenjang-Jurusan-Kelas-No_Absen. Misal (xi-tkj-2-10)<br />
<div class="input-group mb-3">
   
  <span class="input-group-text" id="basic-addon1">Kode Siswa</span>
  <input type="text" class="form-control"  id="hasilscan" placeholder="TKJ,AKL,TE,TOKR,TKRO,TOSM,TBSM,PS,TAV" name="kode_siswa" onkeyup="fetchData(this.value)" required aria-label="Username" aria-describedby="basic-addon1">

</div>





<div id="result" align="center"></div>

        
        
       <!-- <input type="submit" value="Simpan">-->
     </form>
    
    
     <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
     <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>


     
<script type="text/javascript">
    function fetchData(kode_siswa) {
    // Membuat request ke server menggunakan XMLHttpRequest
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Ketika permintaan berhasil, menampilkan hasil di div dengan id 'result'
            document.getElementById("result").innerHTML = this.responseText;
        }
    };
    // Mengirim data nomor absen ke script PHP untuk pengolahan data
    xhr.open("GET", "fetch_data2.php?kode_siswa=" + kode_siswa, true);
    xhr.send();
}


</script>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>