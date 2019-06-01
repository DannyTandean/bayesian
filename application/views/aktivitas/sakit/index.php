<div class="col-md-12">
  <!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Sakit Karyawan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblSakit" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tanggal</th>
                            <th>Nama Karyawan</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Tanggal Sakit</th>
                            <th>Sampai</th>
                            <th>Keterangan</th>
                            <th>File</th>
                            <th>Lama Sakit</th>
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
            <label for="tanggal" class="block">Tanggal :</label>
            <input id="tanggal" class="form-control" type="text" placeholder="Select Your Date" name="tanggal" disabled="disabled">
            <div id="errorTanggal"></div>
      </div>

      <div class="form-group">
          <div class="row">
              <div class="col-md-12">
                <label for="karyawan">Nama karyawan</label>
                <select  class="form-control" name="karyawan" id="karyawan" style="width: 100%;">
                  <!-- <option value="">-- Select karyawan --</option>
                  <?php //foreach ($karyawan as $item):?>
                    <option value="<?php //echo $item["id"];?>"><?php //echo $item["nama"];?></option>
                  <?php //endforeach;?> -->
                </select>
                <div id="errorKaryawan"></div>
              </div>

              <div class="col-md-6">
                <label for="departemen">Departemen : </label>
                <input id="departemen" type="text" name="departemen" class="form-control" placeholder="Departemen" readonly>

              </div>
              <div class="col-md-6">
                <label for="jabatan">Jabatan : </label>
                <input id="jabatan" type="text" name="jabatan" class="form-control" placeholder="Jabatan" readonly>
              </div>

              <div class="col-md-6">
                <label for="mulaiSakit" class="block">Mulai Sakit :</label>
                <input id="mulaiSakit" class="form-control" type="text" placeholder="Mulai Sakit" name="mulaiSakit">
                <div id="errorMulaiSakit"></div>
              </div>

              <div class="col-md-6">
                <label for="akhirSakit" class="block">Sampai :</label>
                <input id="akhirSakit" class="form-control" type="text" placeholder="Sampai" name="akhirSakit">
                <div id="errorAkhirSakit"></div>
              </div>

              <div class="col-md-12">
                <label for="keterangan" class="block">Keterangan :</label>
                <textarea id="keterangan" name="keterangan" class="form-control" style="resize: vertical;" placeholder="keterangan"></textarea>
                <div id="errorKeterangan"></div>
              </div>
              
              <div class="col-md-12">
                <label for="file">Upload Foto Surat Sakit : </label>
              </div>
              <div class="container">
                <img id="uploadfile" src="http://placehold.it/320x180" alt="your image" width="320 px" height="180 px" />
              </div>
             <input type='file' accept=".jpeg,.jpg,.png" onchange="readURL(this);" name="uploadfile" />
          </div>
      </div>
  </div>




  <?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>
<?php assets_script_custom_aktivitas("sakit.js"); ?>
