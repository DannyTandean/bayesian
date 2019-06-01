<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Cabang</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblCabang" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Kode</th>
                            <th>Nama Cabang</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Provinsi</th>
                            <th>Kode Pos</th>
                            <th>Negara</th>
                            <th>Telepon</th>
                            <th>Fax</th>
                            <th>Email</th>
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
            <label for="cabang" class="block">Nama :</label>
            <input id="cabang" name="cabang" type="text" class="form-control">
            <div id="errorNama"></div>
	    </div>
        <div class="form-group">
            <label for="alamat" class="block">Alamat :</label>
            <input id="alamat" name="alamat" type="text" class="form-control">
            <div id="errorAlamat"></div>
        </div>
        <div class="form-group">
              <label for="Provinsi" class="block">Provinsi :</label>
              <select class="form-control" name="provinsi" id="provinsi">
                <?php foreach ($provinsi as $value): ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                <?php endforeach; ?>
              </select>
              <div id="errorProvinsi"></div>
        </div>

        <div class="form-group">
              <label for="Kota/Kabupaten" class="block">Kota/Kabupaten :</label>
              <select class="form-control" name="kabupaten" id="kabupaten">
              </select>
              <div id="errorKabupaten"></div>
        </div>
        <!-- <div class="form-group">
            <label for="kota" class="block">Kota :</label>
            <input id="kota" name="kota" type="text" class="form-control">
            <div id="errorKota"></div>
        </div>
        <div class="form-group">
            <label for="provinsi" class="block">Provinsi :</label>
            <input id="provinsi" name="provinsi" type="text" class="form-control">
            <div id="errorProvinsi"></div>
        </div> -->
        <div class="form-group">
            <label for="kode_pos" class="block">Kode Pos :</label>
            <input id="kode_pos" name="kode_pos" type="number" class="form-control">
            <div id="errorKode_pos"></div>
        </div>
        <div class="form-group">
            <label for="negara" class="block">Negara :</label>
            <input id="negara" name="negara" type="text" class="form-control">
            <div id="errorNegara"></div>
        </div>
        <div class="form-group">
            <label for="telepon" class="block">Telepon :</label>
            <input id="telepon" name="telepon" type="number" class="form-control">
            <div id="errorTelepon"></div>
        </div>
        <div class="form-group">
            <label for="fax" class="block">Fax :</label>
            <input id="fax" name="fax" type="number" class="form-control">
            <div id="errorFax"></div>
        </div>
        <div class="form-group">
            <label for="email" class="block">Email :</label>
            <input id="email" name="email" type="email" class="form-control">
            <div id="errorEmail"></div>
        </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>


<?php assets_script_custom_master("cabang.js"); ?>
