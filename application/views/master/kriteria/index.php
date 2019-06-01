<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table kriteria Penilaian</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblKriteria" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Nama</th>
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
            <div class="col-md-6">
              <label class="block" style="padding:8px;">Nama Kriteria :</label>
            </div>
              <div class="col-md-6">
                <input type="text" name="penilaian" class="form-control" id="penilaian">
                <div id="errorPenilaian"></div>
              </div>
          </div>
      </div>
  </div>

	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>


<?php assets_script_custom_master("kriteria.js"); ?>
