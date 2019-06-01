<div class="col-md-12">
	<!-- Zero config.table start -->

    <!-- Tab panes -->


          <div class="card">
              <div class="card-header">
                  <h5>Data Table Laporan Pinjaman Karyawan</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                <div class="row">
                  <div class="col-md-3">&nbsp;</div>
                  <div class="col-md-5">
                    <?php echo form_open("",array("id" => "formDataSearch")); ?>
                      <div class="form-inline">
                          <input type="text" name="before" class="form-control tanggal" id="before">
                          &nbsp;
                          <i class="fa fa-arrows-h"></i>
                          &nbsp;
                          <input type="text" name="after" class="form-control tanggal" id="after">
                          &nbsp;
                          <button type="button" class="btn btn-round btn-primary btn-outline-primary btn-icon m-l-5" id="searchDate"><i class="icofont icofont-search-alt-1"></i></button>
                      </div>
                    <?php echo form_close(); ?>
                    <br>
                  </div>
                  <div class="col-md-4">
                      &nbsp;
                  </div>
                </div>
                  <div class="dt-responsive table-responsive">
                      <table id="tblPinjaman" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Tanggal</th>
                                  <th>Nama Karyawan</th>
                                  <th>Departemen</th>
                                  <th>Jabatan</th>
                                  <th>Jumlah</th>
                                  <th>Bayar</th>
                                  <th>Sisa</th>
                                  <th>Cara Bayar</th>
                                  <th>Cicilan</th>
                                  <th>Keterangan</th>
                                  <th>Status</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-2">&nbsp;</div>
                  <div class="col-md-2 alert alert-secondary text-center" role="alert">
                    Total Pinjaman
                    <span id="totalPinjaman"></span>
                  </div>
                  <div class="col-md-1">&nbsp;</div>
                  <div class="col-md-2 alert alert-secondary text-center" role="alert">
                    Total Sisa
                    <span id="totalSisa"></span>
                  </div>
                  <div class="col-md-1">&nbsp;</div>
                  <div class="col-md-2 alert alert-dark text-center" role="alert">
                    Total Bayar
                    <span id="totalBayar"></span>
                  </div>
                </div>
              </div>
          </div>
    <!-- Zero config.table end -->
</div>

<?php assets_script_custom("statistik/pinjaman.js"); ?>
