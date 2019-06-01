<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Laporan BPJS</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-1">&nbsp;</div>
            <div class="col-md-9">
              <?php echo form_open("",array("id" => "formDataSearch")); ?>
                <div class="form-inline" id="periodegaji" style="margin-left: 10%;">
                    <label for="periode">Periode</label>
                    &nbsp;&nbsp;
                     <select id="periode" name="periode" class="form-control periode">
                       <option value="zero">All</option>
                        <option value="1-Mingguan">1 Mingguan</option>
                        <option value="2-Mingguan">2 Mingguan</option>
                        <option value="Bulanan">Bulanan</option>
                    </select>
                    &nbsp;
                    <input type="text" name="before" class="form-control tanggal" id="before">
                    &nbsp;
                    <i class="fa fa-arrows-h"></i>
                    &nbsp;
                    <input type="text" name="after" class="form-control tanggal" id="after">
                    &nbsp;
                    <button type="button" class="btn btn-square btn-sucess btn-success" id="searchDate">Proses</button>

                </div>
                <br>
              <?php echo form_close(); ?>
              <br>
            </div>
            <div class="col-md-4">
                &nbsp;
            </div>
          </div>
            <div class="dt-responsive table-responsive">
                <table id="tblBpjs" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            <th>Grup</th>
                            <th>Jabatan</th>
                            <th>Departemen</th>
                            <th>Cabang</th>
                            <th>Total BPJS</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                      <tr>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th style="text-align:right">Total:</th>
                          <th></th>
                      </tr>
                  </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>
<?php assets_script_custom("statistik/bpjs.js"); ?>
