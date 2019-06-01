<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Item</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblDepartemen" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Item</th>
                            <th>Harga</th>
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
            <label for="kode" class="block">Kode :</label>
            <input id="kode" name="kode" type="text" class="form-control">
            <div id="errorKode"></div>
	    </div>
	    <div class="form-group">
            <label for="departemen" class="block">Item :</label>
            <input id="departemen" name="departemen" type="text" class="form-control">
            <div id="errorDepartemen"></div>
	    </div>
         <div class="form-group">
            <label for="harga" class="block">Harga / Item :</label>
            <input id="harga" name="harga" type="number" placeholder="Harga / Item" class="form-control">
             <span id="mata_uang_harga"></span>
            <div id="errorHarga"></div>
        </div>
	    <div class="form-group">
            <label for="keterangan" class="block">Keterangan :</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3" style="resize: vertical;"></textarea>
            <div id="errorKeterangan"></div>
	    </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>


<?php assets_script_custom("pekerjaborongan/departemen.js"); ?>