<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table THR</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-8">
              <?php echo form_open("",array("id" => "formDataSearch")); ?>
                <div class="form-inline">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-square btn-sucess btn-success" id="Process">Proses THR</button>

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
                <table id="tblThr" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tools</th>
                            <th>Tanggal</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Departemen</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<?php echo modalSaveOpen("Print","lg"); ?>
  <div class="card" >
    <div class="card-block" id="asd">
      <div class="row invoive-info">
        <div class="col-md-12 col-sm-12">
          <h6>SLIP GAJI KARYAWAN - <span id="perusahaan"></span> : </h6>
          <div class="row">
            <div class="col-md-12 pull-left">
              <table>
                  <tbody>
                      <tr>
                          <th>IDFP / Nama </th>
                          <td id="idfp"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Periode Gajian / Tgl msk kerja  </th>
                          <td id="periode1"></td>
                      </tr>
                      <tr>
                          <th>Departemen / Jabatan </th>
                          <td id="departemen"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Bank</th>
                          <td id="bank">:</td>
                      </tr>
                      <tr>
                          <th>Golongan  </th>
                          <td id="golongan"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Rekening</th>
                          <td id="rekening"></td>
                      </tr>
                      <tr>
                          <th>Cabang  </th>
                          <td id="cabang"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>THR</th>
                          <td id="thr">: 145698
                          </td>
                      </tr>
                  </tbody>
              </table>
            </div>
          </div>
          <hr style="border : 1px solid grey">
          <div class="row">
            <div class="col-sm-12">
                <p>Tanggal : <?php echo date_ind("d M Y",date("Y-m-d"));?></p>
                <br>
                <p>Diterima Oleh , </p>
                <br><br>
                <p>(........................)</p>
                <p>Powered By Prima HRD System <?php echo date("Y"); ?></p>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<?php echo modalSaveClose("Print","printing"); ?>

<?php assets_script_custom("aktivitas/thr.js"); ?>
