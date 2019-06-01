<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Golongan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblGolongan" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Nama Golongan</th>
                            <th>Gaji Harian</th>
                            <th>Gaji Per Menit</th>
                            <th>Tunj Makan</th>
                            <!-- <th>Tunj Hadir</th> -->
                            <th>Tunj Trans</th>
                            <th>THR</th>
                            <!-- <th>T.Disiplin</th> -->
                            <th>Tunj Lain</th>
                        <!--     <th>Pot Absen</th> -->
                            <th>Pot BPJS</th>
                            <th>Pot Lain</th>
                            <th>Lembur Tetap</th>
                            <th>Gaji Bulanan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<?php echo modalSaveOpen(false,"lg"); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>
	    <div class="form-group">
            <label for="golongan" class="block">Nama Golongan :</label>
            <input id="golongan" name="golongan" type="text" class="form-control" placeholder="Nama Golongan">
            <div id="errorGolongan"></div>

            <input type="hidden" name="idGolongan" id="idGolongan">
	    </div>

      <div class="form-group">
          <div class="row">
              <div class="col-md-4">
                <label for="gajiGolongan">Gaji Harian : </label>
                <input id="gajiGolongan" type="number" name="gajiGolongan" class="form-control" placeholder="Dalam Rupiah">
                <span id="mata_uang_gajiGol"></span>
                <div id="errorGajiGolongan"></div>
              </div>
              <div class="col-md-4">
                <label for="umk">Gaji Bulanan : </label>
                <input id="umk" type="number" name="umk" class="form-control" placeholder="Dalam Rupiah" >
                <span id="mata_uang_umk"></span>
                <div id="errorumk"></div>
              </div>
              <div class="col-md-4">
                <label for="permenit">Gaji Per Menit : </label>
                <input id="permenit" type="number" name="permenit" class="form-control" placeholder="Dalam Rupiah" >
                <span id="rekomendasi_gaji"></span>
                <br>
                <span id="mata_uang_permenit"></span>
                <div id="errorpermenit"></div>
              </div>

             <!--  <div class="col-md-3">
                <label for="tunjanganDisiplin">Tunjangan Disiplin :</label>
                <input id="tunjanganDisiplin" type="number" name="tunjanganDisiplin" class="form-control" placeholder="Dalam Rupiah">
                <span id="mata_uang_TunDisiplin"></span>
                <div id="errorTunDisiplin"></div>
              </div> -->
          </div>
      </div>

      <div class="form-group">
          <div class="row">
              <div class="col-md-4">
                <label for="THR">Tunjangan Hari Raya : </label>
                <input id="THR" type="number" name="thr" class="form-control" placeholder="Dalam Rupiah">
                <span id="mata_uang_THR"></span>
                <div id="errorTHR"></div>
              </div>
              <div class="col-md-4">
                <label for="tunjMakan">Tunj Makan (/Hari) : </label>
                <input id="tunjMakan" type="number" name="tunjMakan" class="form-control" placeholder="Dalam Rupiah">
                <span id="mata_uang_TunjMakan"></span>
                <div id="errorTunjMakan"></div>
              </div>
             <!--  <div class="col-md-3">
                <label for="TunjKehadiran">Tunj Kehadiran : </label>
                <input id="TunjKehadiran" type="number" name="tunjKehadiran" class="form-control" placeholder="Dalam Rupiah">
                <span id="mata_uang_TunjKehadiran"></span>
                <div id="errorTunjKehadiran"></div>
              </div> -->
              <div class="col-md-4">
                <label for="TunjTransport">Tunj Transport (/Hari) : </label>
                <input id="TunjTransport" type="number" name="TunjTransport" class="form-control" placeholder="Dalam Rupiah" >
                <span id="mata_uang_TunjTransport"></span>
                <div id="errorTunjTransport"></div>
              </div>

      </div>
      <div class="row">
        <!-- <div class="col-md-3">
          <label for="PotAbsen">Pot Absen (x Gaji) :</label>
          <input id="PotAbsen" type="number" name="PotAbsen" class="form-control" placeholder="Kali Gaji">
          <span id="mata_uang_PotAbsen"></span>
          <div id="errorPotAbsen"></div>
        </div> -->
        <div class="col-md-4">
          <label for="TunjLain">Tunj Lain : </label>
          <input id="TunjLain" type="number" name="TunjLain" class="form-control" placeholder="Dalam Rupiah">
          <span id="mata_uang_TunjLain"></span>
          <div id="errorTunjLain"></div>
        </div>
        <div class="col-md-4">
          <label for="PotBpjs">Pot BPJS (%) :</label>
          <input id="PotBpjs" type="number" name="PotBpjs" class="form-control" placeholder="Dalam Persen">
          <span id="mata_uang_PotBpjs"></span>
          <div id="errorPotBpjs"></div>
        </div>
        <div class="col-md-4">
          <label for="PotLain">Pot Lain : </label>
          <input id="PotLain" type="number" name="PotLain" class="form-control" placeholder="Dalam Rupiah">
          <span id="mata_uang_PotLain"></span>
          <div id="errorPotLain"></div>
        </div>
        <div class="col-md-4">
          <label for="SistemLembur">Sistem Lembur :</label>
          <select class="form-control" name="SistemLembur" id="SistemLembur">
              <option value="">-Lembur-</option>
              <option value="1">Hidup</option>
              <option value="2">Tetap</option>
          </select>
          <div id="errorSistemLembur"></div>
        </div>
        <!-- <div class="col-md-4">
          <label for="LemburHidup">Lembur Hidup :</label>
          <select class="form-control" name="LemburHidup" id="LemburHidup">
            <option value="">-- Pilih Jam --</option>
              <option value="1">1 Jam</option>
              <option value="2">2 Jam</option>
              <option value="3">3 Jam</option>
              <option value="4">4 Jam</option>
          </select>
          <div id="errorLemburHidup"></div>
        </div> -->
         <div class="col-md-4">
          <label for="LemburTetap">Lembur Tetap (/Jam) : </label>
          <input id="LemburTetap" type="number" name="LemburTetap" class="form-control" placeholder="Dalam Rupiah">
          <span id="mata_uang_Lembur"></span>
          <div id="errorLemburTetap"></div>
        </div>
      </div>
  </div>
	    <div class="form-group">
            <label for="keterangan" class="block">Keterangan :</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3" style="resize: vertical;"></textarea>
            <div id="errorKeterangan"></div>
	    </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>


<?php assets_script_custom_master("golongan.js"); ?>
