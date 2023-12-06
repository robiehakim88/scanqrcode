
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Keterlambatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
   <style>
     
      /* CSS untuk membuat header tetap */
        #mainNavbar {
            position: fixed;
            width: 100%;
            z-index: 1000;
        }
        body {
            background-color: #30BAE8; /* Ganti dengan kode warna pastel yang Anda inginkan */
        }
    </style>
  
  
  </head>
<body>
    <!-- Header -->
    <header>
      
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Absensi</a>
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
      <form class="d-flex" role="search">
        
      </form>
    </div>
  </div>
</nav>
      
      
      
      
      
      
      
       
    </header>

    <!-- Content -->
    <div class="container my-5">
        
      <div align="center"><img src="http://absensi.smktb.sch.id/logo/smktb-absensi.png" width="110" height="110"></div>
      <h2 class="text-center">APLIKASI KETERLAMBATAN</h2>
        <h2 class="text-center"></h2>
        <div class="row mt-5">
            <div class="col">
                <div class="col-6">
                    <video id="previewKamera" style="width: 400px; height: 400px;"></video>
                    <br>
                </div>
            </div>
        </div>
        <div class="row mt-3 ">
            <div class="col justify-content-center">
                <label for=""><strong>Pilih Kamera:</strong></label> <br />
                <select class="form-select" id="pilihKamera" style="max-width: 400px;"></select>
                <br />
                <form action="proses_input.php" method="post">
                    <label for="kode_siswa"><strong>Kode Siswa:</strong></label><br />
                    <input type="text" id="hasilscan" name="kode_siswa" onmousedown="fetchData(this.value)" required readonly><br />*Tekan kolom kode siswa saat kode sudah muncul
                    <br />
                    <label for="nama"><strong>Tanggal Hari Ini:</strong></label><br />
                    <input type="text" id="waktu" name="waktu" value="<?php echo date('Y-m-d');?>" required readonly><br /><br />
                    <div id="result"></div><br />
                    <button type="submit" value="Simpan" class="btn btn-primary btn-lg">Simpan</button>
                </form>
            </div>
            <div class="col-5"></div>
            <div class="col"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-dark text-white">
        <div class="container text-center">
            <span>&copy; 2023 Keterlambatan Siswa SMK Taruna Bakti Kertosono</span>
        </div>
    </footer>
  
       <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
     <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

     <script>
        let selectedDeviceId = null;
        const codeReader = new ZXing.BrowserMultiFormatReader();
        const sourceSelect = $("#pilihKamera");

        $(document).on('change','#pilihKamera',function(){
            selectedDeviceId = $(this).val();
            if(codeReader){
                codeReader.reset()
                initScanner()
            }
        })

        function initScanner() {
            codeReader
            .listVideoInputDevices()
            .then(videoInputDevices => {
                videoInputDevices.forEach(device =>
                    console.log(`${device.label}, ${device.deviceId}`)
                );

                if(videoInputDevices.length > 0){
                    
                    if(selectedDeviceId == null){
                        if(videoInputDevices.length > 1){
                            selectedDeviceId = videoInputDevices[1].deviceId
                        } else {
                            selectedDeviceId = videoInputDevices[0].deviceId
                        }
                    }
                    
                    
                    if (videoInputDevices.length >= 1) {
                        sourceSelect.html('');
                        videoInputDevices.forEach((element) => {
                            const sourceOption = document.createElement('option')
                            sourceOption.text = element.label
                            sourceOption.value = element.deviceId
                            if(element.deviceId == selectedDeviceId){
                                sourceOption.selected = 'selected';
                            }
                            sourceSelect.append(sourceOption)
                        })
                
                    }

                    codeReader
                        .decodeOnceFromVideoDevice(selectedDeviceId, 'previewKamera')
                        .then(result => {

                                //hasil scan
                                console.log(result.text)
                                $("#hasilscan").val(result.text);
                            
                                if(codeReader){
                                    codeReader.reset()
                                }
                        })
                        .catch(err => console.error(err));
                    
                } else {
                    alert("Camera not found!")
                }
            })
            .catch(err => console.error(err));
        }


        if (navigator.mediaDevices) {
            

            initScanner()
            

        } else {
            alert('Cannot access camera.');
        }
      
     </script>
     
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
    xhr.open("GET", "fetch_data.php?kode_siswa=" + kode_siswa, true);
    xhr.send();
}


</script>

    <!-- Scripts -->
    <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script><!--
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Your existing scripts... -->
</body>
</html>
