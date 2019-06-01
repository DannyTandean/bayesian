<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Approval Dirumahkan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblDirumahkan" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tanggal</th>
                            <th>Nama Karyawan</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Tanggal Dirumahkan</th>
                            <th>Akhir Dirumahkan</th>
                            <th>Lama Dirumahkan</th>
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
            <div class="form-group">
                  <input id="status" class="form-control" type="hidden" name="status">
                  <div id="errorStatus"></div>
            </div>

          </div>
      </div>
  </div>




	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>


<?php assets_script_custom_approval("dirumahkan.js"); ?>
