<div class="col-md-12">
  <!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Produksi</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblProduksi" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tanggal</th>
                            <th>Nama Karyawan</th>
                            <th>Item</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Pendapatan</th>
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
    <div class="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>
      <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <label for="tanggal1" class="block">Tanggal :</label>
              <input id="tanggal1" class="form-control" type="text" placeholder="Select Your Date" name="tanggal">
              <div id="errorTanggal1"></div>
            </div>

              <div class="col-md-6">
                <label class="block">Nama Karyawan :</label>
                <select id="nama_karyawan" name="karyawan" class="form-control select2">
                </select>
                <div id="errorNamaKaryawan"></div>
              </div>

              <div class="col-md-12">
                <label>Item : </label>
              <?php $i=1; foreach ($item_list as $key => $item): ?>
                <div class="form-inline">
                    <input type="hidden" name="itemId[]" value="<?php echo $item->id_departemen; ?>">
                    <input type="text" name="item[]" class="form-control" value="<?php echo $item->departemen; ?>" readonly style="width:49%;margin-right:1%;">
                    <input type="number" name="qty[]" class="form-control" placeholder="qty" style="width:14%;margin-right:1%;" oninput="getPendapatan(this.value,<?php echo $item->harga; ?>,<?php echo $i; ?>)" value="0">
                    <input type="text" name="total[]" class="form-control autonumber" placeholder="pendapatan" style="width:34%;" id="item<?php echo $i;$i++; ?>" value="0" readonly>
                </div>
              <?php endforeach;?>
              <input type="hidden" name="allItem" value="<?php echo sizeof($item_list); ?>" id="allitemcount">

              </div>
              <div class="col-md-12 row">
                <div class="col-md-4">
                  &nbsp;
                </div>
                <div class="col-md-5">
                  Total Pendapatan :
                </div>
                <div class="col-md-3">
                  <span id="totalPendapatan"></span>
                </div>
              </div>
            </div>
            <hr>
        </div>

  <?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>

<?php echo modalSaveOpen("editModal"); ?>
  <div class="inputMessage"></div>
  <?php echo form_open("",array("id" => "formDataEdit")); ?>
  <div class="form-group">
      <div class="row">

        <div class="col-md-6">
          <label for="tanggal" class="block">Tanggal :</label>
          <input id="tanggal" class="form-control" type="text" placeholder="Select Your Date" name="tanggal">
          <div id="errorTanggal"></div>
        </div>

          <div class="col-md-6">
            <label class="block">Nama Karyawan :</label>
            <select id="nama_karyawan1" name="karyawan" class="form-control select2">
            </select>
            <div id="errorNamaKaryawan"></div>
          </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="kode_karyawan" class="block">NIK :</label>
                    <input id="kode_karyawan" name="kode_karyawan" type="text" readonly class="form-control">
                </div>
                <div class="form-group">
                    <label for="departemen" class="block">Item :</label>
                    <select id="departemen" name="departemen" class="form-control select2">
                    </select>
                    <div id="errorNamaDepartemen"></div>
                </div>
                <div class="form-group">
                    <label for="harga" class="block">Per Item :</label>
                    <input id="harga" name="harga" type="text" readonly class="form-control">
                </div>

                <div class="form-group">
                    <label for="jumlah" class="block">Jumlah Produksi Item :</label>
                    <input id="jumlah"  type="number" placeholder="Jumlah Item" name="jumlah" class="form-control">
                </div>
                <div class="form-group">
                    <label for="total" class="block">Total Pendapatan :</label>
                    <input id="total" name="total" type="text" readonly class="form-control">
                </div>
            </div>
          </div>
        </div>
        <hr>
  </div>
<?php echo form_close(); ?>
<?php echo modalSaveClose("save","editBtn") ?>
<?php assets_script_custom("pekerjaborongan/produksi.js"); ?>
