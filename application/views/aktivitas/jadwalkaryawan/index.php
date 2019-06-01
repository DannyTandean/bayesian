<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Jadwal Karyawan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblJadwalKaryawan" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Nama Jadwal</th>
                            <th>Awal Masuk</th>
                            <th>Masuk</th>
                            <th>Akhir Masuk</th>
                            <th>Keluar</th>
                            <th>Akhir Keluar</th>
                            <th>Maksimal Jam Kerja</th>
                            <th>Break in</th>
                            <th>Break out</th>
                            <th>Shift</th>
														<th>Punishment Break</th>
                            <th>Username</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<?php echo modalSaveOpen(); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>

      <div class="form-group">
          <div class="row">
            <div class="col-md-12">
              <label for="jadwal" class="block">Nama Jadwal :</label>
              <input id="jadwal" class="form-control" type="text" placeholder="nama jadwal" name="namaJadwal">
              <div id="errorNamaJadwal"></div>
            </div>

              <div class="col-md-6">
                <label class="block">Awal Masuk :</label>
                <input id="awal_masuk" class="form-control time24" type="text" name="awal_masuk">
                <div id="errorAwalMasuk"></div>
              </div>

              <div class="col-md-6">
                <label class="block">Masuk :</label>
                <input id="masuk" class="form-control time24" type="text" name="masuk">
                <div id="errorMasuk"></div>
              </div>

              <div class="col-md-6">
                <label class="block">Akhir Masuk :</label>
                <input id="akhir_masuk" class="form-control time24" type="text" name="akhir_masuk">
                <div id="errorAkhirMasuk"></div>
              </div>

              <div class="col-md-6">
                <label for="keluar" class="block">Keluar :</label>
                <input id="keluar" class="form-control time24" type="text" name="keluar">
                <div id="errorKeluar"></div>
                <div class="border-checkbox-section">
                  <div class="border-checkbox-group border-checkbox-group-info">
                    <input class="border-checkbox" type="checkbox" id="checkbox3" value="1" name="lewatHari">
                    <label class="border-checkbox-label" for="checkbox3" id="lewat">Lewat Hari</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <label for="akhirKeluar" class="block">Akhir Keluar :</label>
                <input id="akhirKeluar" class="form-control time24" type="text" name="akhir_keluar">
                <div id="errorAkhirKeluar"></div>
              </div>
              <div class="col-md-6">
                <label for="maxjamkerja" class="block">Maksimal Jam Kerja :</label>
                <input id="maxjamkerja" class="form-control" type="number" name="jam_kerja" placeholder="jam">
                <span id="menit_kerja"></span>
                <div id="errorJamKerja"></div>
              </div>
              <div class="col-md-6">
                <label for="shift">Tipe Shift :</label>
                <select class="form-control" name="shift" id="shift">
                  <option value="0">tidak</option>
                  <option value="1">ya</option>
                </select>
              </div>
              <div class="col-md-6">
                &nbsp;
              </div>
              <div class="col-md-6">
                <label for="tipebreak">Break</label>
                <select class="form-control" name="tipebreak" id="tipebreak">
                  <option value="0">tidak</option>
                  <option value="1">ya</option>
                </select>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-sm-8 form-radio">
                   <div class="radio radiofill radio-success radio-inline">
                        <label>
                            <input type="radio" id="durasiya" name="breaktipe" value="0" checked>
                            <i class="helper"></i>Durasi
                        </label>
                    </div>
                    <div class="radio radiofill radio-danger radio-inline">
                        <label>
                            <input type="radio" id="tetap" name="breaktipe" value="1">
                            <i class="helper"></i>Tetap
                        </label>
                    </div>
                     <!-- <span id="errorTipeBreak"></span> -->
                 </div>
                </div>
              </div>
              <div class="col-md-6" id="tipedurasi">
                <label for="durasi" class="block">Durasi :</label>
                <input id="durasi" class="form-control" type="number" name="durasi" placeholder="Menit">
                <div id="errorDurasi"></div>
              </div>
              <div class="col-md-12" id="break">
                <div class="row">
                  <div class="col-md-6">
                    <label for="breakout" class="block">Break Out :</label>
                    <input id="breakout" class="form-control time24" type="text" name="breakout">
                    <div id="errorBreakout"></div>
                  </div>

                  <div class="col-md-6">
                    <label for="breakin" class="block">Break In :</label>
                    <input id="breakin" class="form-control time24" type="text" name="breakin">
                    <div id="errorBreakin"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6" id="punishment">
                <label for="breakPunishment">Break Punishment</label>
                <input type="number" name="breakPunishment" class="form-control" id="breakPunishment">
                <input type="hidden" name="idjadwal" id="id_jadwal_1">
              </div>
          </div>
      </div>
  </div>


	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>


<?php assets_script_custom_aktivitas("jadwalkaryawan.js"); ?>
