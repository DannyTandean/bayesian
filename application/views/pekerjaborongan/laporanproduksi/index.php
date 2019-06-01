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
          <div class="row">
            <div class="col-md-3">&nbsp;</div>
            <div class="col-md-8">
              <?php echo form_open("",array("id" => "formDataSearch")); ?>
                <div class="form-inline">
                    <input type="text" name="before" class="form-control tanggal" id="before">
                    &nbsp;
                    <i class="fa fa-arrows-h"></i>
                    &nbsp;
                    <input type="text" name="after" class="form-control tanggal" id="after">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-square btn-sucess btn-success" id="Process">Proses</button>
                </div>
              <?php echo form_close(); ?>
              <br>
            </div>
            <div class="col-md-4">
                &nbsp;
            </div>
          </div>
            <div class="dt-responsive table-responsive">
                <table id="tblProduksi" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Kode Item</th>
                            <th>Item</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                      <tr>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th style="text-align:right">Total Item:</th>
                          <th></th>
                          <th style="text-align:right">Total Pendapatan:</th>
                          <th></th>
                      </tr>
                  </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<?php assets_script_custom("pekerjaborongan/laporanproduksi.js"); ?>
