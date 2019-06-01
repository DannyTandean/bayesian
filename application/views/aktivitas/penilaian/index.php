<div class="col-md-12">
  <!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Penilaian Karyawan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblPenilaian" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Akhir</th>
                            <th>Nama Petugas 1</th>
                            <th>Nama Petugas 2</th>
                            <th>Grup</th>
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

<?php echo modalSaveOpen(); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>
      <div class="form-group">

      </div>

      <div class="form-group">
          <div class="row">

            <div class="col-md-6">
              <label for="tanggal_mulai" class="block">Tanggal Mulai :</label>
              <input id="tanggal_mulai" class="form-control tanggal" type="text" placeholder="Select Your Date" name="tanggal_mulai">
              <div id="errorTanggalMulai"></div>
            </div>

            <div class="col-md-6">
              <label for="tanggal_akhir" class="block">Tanggal Berakhir :</label>
              <input id="tanggal_akhir" class="form-control tanggal" type="text" placeholder="Select Your Date" name="tanggal_akhir">
              <div id="errorTanggalAkhir"></div>
            </div>

            <div class="col-md-6">
              <label for="periode" class="block">Periode :</label>
              <select class="form-control" name="periode" id="periode">

              </select>
              <div id="errorPeriode"></div>
            </div>

              <div class="col-md-6">
                <label for="karyawan">Opsi Petugas</label>
                <select class="form-control" name="opsi_petugas" id="opsi_petugas">
                  <option value="0">1 Petugas</option>
                  <option value="1">2 Petugas</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="block" for="nama_karyawan">Nama Karyawan :</label>
                <select id="nama_karyawan" name="karyawan" class="form-control select2">
                </select>
                <div id="errorNamaKaryawan"></div>
              </div>

              <div class="col-md-6" id="showPetugas">
                <label class="block" for="nama_karyawan1">Nama Karyawan :</label>
                <select id="nama_karyawan1" name="karyawan1" class="form-control select2">
                </select>
                <div id="errorNamaKaryawan1"></div>
              </div>

              <div class="col-md-6">
                <label class="block" for="target_penilaian">Target Penilaian Berdasarkan : </label>
                <select class="form-control" name="target_penilaian" id="target_penilaian">
                  <option value="0">Karyawan</option>
                  <option value="1">Grup</option>
                </select>
              </div>

              <div class="col-md-6 hideopsigrup">
                <label class="block" for="grup">Grup :</label>
                <select id="grup" name="grup" class="form-control select2">
                </select>
                <div id="errorGrup"></div>
              </div>

              <div class="col-md-6 hideopsiMultiSelect">
                <label class="block" for="multikaryawan"> :</label>
                <select id="multikaryawan" name="multikaryawan[]" class="form-control select2" multiple>
                </select>
                <div id="errorGrup"></div>
              </div>

              <div class="col-md-12">
                <label for="keterangan" class="block">Keterangan</label>
                <textarea id="keterangan" name="keterangan" class="form-control" style="resize: vertical;" placeholder="keterangan"></textarea>
                <div id="errorketerangan"></div>
              </div>
          </div>
      </div>
  </div>

  <?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>
<?php assets_script_custom_aktivitas("penilaian.js"); ?>
