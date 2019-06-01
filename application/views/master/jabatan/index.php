<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Jabatan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblJabatan" class="table table-striped table-bordered table-hover table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jabatan</th>
                            <th>Tunjangan</th>
                            <th>Keterangan</th>
                            <th>Action</th>
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
            <label for="jabatan" class="block">Jabatan :</label>
            <input id="jabatan" name="jabatan" type="text" class="form-control">
            <div id="errorJabatan"></div>
	    </div>
	    <div class="form-group">
            <label for="tunjangan" class="block">Tunjangan :</label>
            <input id="tunjangan" name="tunjangan" type="number" class="form-control">
            <span id="mata_uang_tunjangan"></span>
            <div id="errorTunjangan"></div>
	    </div>
	    <div class="form-group">
            <label for="keterangan" class="block">Keterangan :</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3" style="resize: vertical;"></textarea>
            <div id="errorKeterangan"></div>
	    </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>


<?php assets_script_custom_master("jabatan.js"); ?>