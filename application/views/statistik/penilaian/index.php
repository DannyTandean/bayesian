<div class="col-md-12">
	<!-- Zero config.table start -->
  <ul class="nav nav-tabs md-tabs " role="tablist">
      <!-- <li class="nav-item col-md-3">&nbsp;</li> -->
      <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#keseluruhan"  role="tab" aria-expanded="true" id="contentkeseluruhan">Penilaian Keseluruhan</a>
          <div class="slide"></div>
      </li>
      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#bygrup"  role="tab" aria-expanded="false" id="contentbygrup">Penilaian Grup</a>
          <div class="slide"></div>
      </li>
      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#bydepartemen"  role="tab" aria-expanded="false" id="contentbydepartemen">Penilaian Departmen</a>
          <div class="slide"></div>
      </li>
      <li class="nav-item col-md-3">&nbsp;</li>
  </ul>
  <br>
  <div class="tab-content">
      <div class="tab-pane active" id="keseluruhan" role="tabpanel" aria-expanded="true">
          <div class="card borderless-card">
              <div class="card-header">
                  <h5>Data Table Laporan Penilaian</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                <div class="row">
                  <div class="col-md-2">&nbsp;</div>
                  <div class="col-md-8">
                    <?php echo form_open("",array("id" => "formDataSearch")); ?>
                      <div class="form-inline">
                          &emsp;&emsp;&emsp;&emsp;&emsp;
                          <select class="form-control" name="periode" id="periode">

                          </select>
                          &nbsp;
                          <input type="text" name="before" class="form-control tanggal" id="before">
                          &nbsp;
                          <i class="fa fa-arrows-h"></i>
                          &nbsp;
                          <input type="text" name="after" class="form-control tanggal" id="after">
                          &nbsp;
                          <button type="button" class="btn btn-primary btn-outline-primary btn-icon m-l-5" id="searchDate"><i class="icofont icofont-search-alt-1"></i></button>
                      </div>
                    <?php echo form_close(); ?>
                    <br>
                  </div>
                  <!-- <div class="col-md-4">
                      &nbsp;
                  </div> -->
                <!-- </div>
                <div class="row">
                  <div class="col-md-4">
                    &nbsp;
                  </div>
                  <div class="col-md-3">
                    <div class="form-inline">
                      <select class="form-control" id="selectDepartemen" name="selectDepartemen">
                        <?php foreach ($departemen as $key => $val): ?>
                          <option value="<?php echo $val->id_departemen; ?>"><?php echo $val->departemen; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div> -->
                <br>
                  <div class="dt-responsive table-responsive">
                      <table id="tblHasilPenilaian" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Tanggal</th>
                                  <th>Nama Karyawan</th>
                                  <th>Grup</th>
                                  <th>Jabatan</th>
                                  <th>Departemen</th>
                                  <th>Cabang</th>
                                  <th>Nilai</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
          </div>
        </div>
      </div>

      <div class="tab-pane active" id="bygrup" role="tabpanel" aria-expanded="false">
          <div class="card borderless-card">
              <div class="card-header">
                  <h5>Data Table Laporan Penilaian</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                <div class="row">
                  <div class="col-md-2">&nbsp;</div>
                  <div class="col-md-8">
                    <?php echo form_open("",array("id" => "formDataSearch1")); ?>
                      <div class="form-inline">
                          &emsp;&emsp;&emsp;&emsp;&emsp;
                          <select class="form-control" name="periode" id="periode1">

                          </select>
                          &nbsp;
                          <input type="text" name="before" class="form-control tanggal" id="before1">
                          &nbsp;
                          <i class="fa fa-arrows-h"></i>
                          &nbsp;
                          <input type="text" name="after" class="form-control tanggal" id="after1">
                          &nbsp;
                          <button type="button" class="btn btn-primary btn-outline-primary btn-icon m-l-5" id="searchDate1"><i class="icofont icofont-search-alt-1"></i></button>
                      </div>
                    <?php echo form_close(); ?>
                    <br>
                  </div>
                  <div class="col-md-4">
                      &nbsp;
                  </div>
               </div>
                <div class="row">
                  <div class="col-md-4">
                    &nbsp;
                  </div>
                  <div class="col-md-3">
                    <div class="form-inline">
                      <select class="form-control" id="selectDepartemen" name="selectDepartemen">
                        <?php foreach ($departemen as $key => $val): ?>
                          <option value="<?php echo $val->id_departemen; ?>"><?php echo $val->departemen; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <br>
                  <div class="dt-responsive table-responsive">
                      <table id="tblHasilPenilaian1" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Tanggal</th>
                                  <th>Nama Karyawan</th>
                                  <th>Grup</th>
                                  <th>Jabatan</th>
                                  <th>Departemen</th>
                                  <th>Cabang</th>
                                  <th>Nilai</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>

      <div class="tab-pane active" id="bydepartemen" role="tabpanel" aria-expanded="false">
          <div class="card borderless-card">
              <div class="card-header">
                  <h5>Data Table Laporan Penilaian</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                <div class="row">
                  <div class="col-md-2">&nbsp;</div>
                  <div class="col-md-8">
                    <?php echo form_open("",array("id" => "formDataSearch2")); ?>
                      <div class="form-inline">
                          &emsp;&emsp;&emsp;&emsp;&emsp;
                          <select class="form-control" name="periode" id="periode2">

                          </select>
                          &nbsp;
                          <input type="text" name="before" class="form-control tanggal" id="before2">
                          &nbsp;
                          <i class="fa fa-arrows-h"></i>
                          &nbsp;
                          <input type="text" name="after" class="form-control tanggal" id="after2">
                          &nbsp;
                          <button type="button" class="btn btn-primary btn-outline-primary btn-icon m-l-5" id="searchDate2"><i class="icofont icofont-search-alt-1"></i></button>
                      </div>
                    <?php echo form_close(); ?>
                    <br>
                  </div>
                  <div class="col-md-4">
                      &nbsp;
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    &nbsp;
                  </div>
                  <div class="col-md-3">
                    <div class="form-inline">
                      <select class="form-control" id="select_grup" name="select_grup">
                        <?php foreach ($grup as $key => $val): ?>
                          <option value="<?php echo $val->id_grup; ?>"><?php echo $val->grup; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <br>
                  <div class="dt-responsive table-responsive">
                      <table id="tblHasilPenilaian2" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Tanggal</th>
                                  <th>Nama Karyawan</th>
                                  <th>Grup</th>
                                  <th>Jabatan</th>
                                  <th>Departemen</th>
                                  <th>Cabang</th>
                                  <th>Nilai</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
          </div>
        </div>
  </div>
    <!-- Zero config.table end -->
</div>

<?php assets_script_custom("statistik/penilaian.js"); ?>
