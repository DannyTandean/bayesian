<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Penggajian</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-2">&nbsp;</div>
            <div class="col-md-9">
              <?php echo form_open("",array("id" => "formDataSearch")); ?>
                <div class="form-inline">
                    <label for="periode">Periode</label>
                    &nbsp;&nbsp;
                     <select id="periode" name="periode" class="form-control periode">
                        <option value="1-Mingguan">1 Mingguan</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" name="before" class="form-control tanggal" id="before">
                    &nbsp;
                    <i class="fa fa-arrows-h"></i>
                    &nbsp;
                    <input type="text" name="after" class="form-control tanggal" id="after">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-square btn-sucess btn-success" id="Process">Proses</button>
                </div>
              <?php echo form_close(); ?>
              <!-- <br> -->
            </div>
            <div class="col-md-4">
                &nbsp;
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              &nbsp;
            </div>
            <div class="col-md-8">
              <?php echo form_open("",array("id" => "printPeriode")); ?>

              <div class="form-inline">
                <label for="kode">Kode Payroll</label>
                &nbsp;&nbsp;
                <select id="kode" name="kode" class="form-control">
                  <?php foreach ($kode_payroll as $key => $value): ?>
                    <option value="<?php echo $value->kode_payroll; ?>"><?php echo $value->kode_payroll; ?></option>
                  <?php endforeach; ?>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-square btn-sucess btn-success" id="ProsesPrint">Proses</button>
              </div>

              <?php echo form_close(); ?>
            </div>
            <div class="col-md-4">
                &nbsp;
            </div>
          </div>
            <div class="dt-responsive table-responsive">
                <table id="tblPenggajian" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Slip</th>
                            <th>Kode Payroll</th>
                            <th>IDFP</th>
                            <th>Nama Karyawan</th>
                            <th>Gaji Total</th>
                            <th>Tanggal Proses</th>
                            <th>Total</th>
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
          <h6>SLIP GAJI KARYAWAN - <span id="perusahaan1"></span> : </h6>
          <div class="row">
            <div class="col-md-12 pull-left">
              <table>
                  <tbody>
                      <tr>
                          <th>IDFP / Nama </th>
                          <td id="idfp1"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Rekening</th>
                          <td id="rekening1"></td>
                      </tr>
                      <tr>
                          <!-- <th>Item </th>
                          <td id="departemen1"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td> -->
                          <th>Bank</th>
                          <td id="bank1">:</td>
                      </tr>
                      <tr>
                          <th>Periode Gajian   </th>
                          <td id="periode11"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Jumlah Hari Kerja / Minggu</th>
                          <td id="harikerja1">: 145698
                          </td>
                      </tr>
                  </tbody>
              </table>
            </div>
          </div>
          <hr style="border : 1px solid grey">
          <div class="row">
            <div class="col-md-12">
              <table id="listproduksi" class="table table-bordered">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama </th>
                    <!-- <td id="idfp1"></td> -->
                    <th>Tanggal</th>
                    <th>Item</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Pendapatan</th>
                  </tr>
                  <tbody>

                  </tbody>
                </thead>
              </table>
              <table>
                  <tbody>
                      <tr>
                          <th>Jumlah Gaji </th>
                          <td id="gajiTotal1"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&ensp;</td>
                      </tr>
                      <tr>
                          <th>Total Item : </th>
                          <td id="totalItem1"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Total Gaji  </th>
                          <td id="totalGaji">: 145698</td>
                      </tr>
                  </tbody>
              </table>
            </div>
          </div>
          <hr style="border : 1px solid grey">
          <div class="row">
            <div class="col-md-12">
              <table class="table invoice-table invoice-order table-borderless">
                <tbody>
                    <tr>
                        <th>Total Penerimaan</th>
                        <td id="totalGaji1">:</td>
                        <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
                        <th>Take Home Pay</th>
                        <td id="totalGaji11">:</td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <hr style="border : 1px solid grey">

  </div>
</div>
<?php
    $btnCustom = '

                      <button type="button" class="btn btn-info btn-sm" id="btnExcel" ><i class="fa fa-export"></i>Excel</button>
                      <button type="button" class="btn btn-primary btn-sm" id="btnPrint"><i class="fa fa-print"></i>Print</button>
                      <button type="button" class="btn btn-default btn-sm" id="modalButtonClose" data-dismiss="modal">Close</button>
          ';
    echo modalCloseButtonCustom($btnCustom);
  ?>
<?php //echo modalSaveClose("Print","printing"); ?>

<?php echo modalSaveOpen("printList","lg"); ?>
    <table id="listproduksi1" class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama </th>
          <!-- <td id="idfp1"></td> -->
          <th>Tanggal</th>
          <th>Item</th>
          <th>Jumlah</th>
          <th>Harga</th>
          <th>Pendapatan</th>
        </tr>
        <tbody>

        </tbody>
      </thead>
    </table>
<?php
    $btnCustom = '

                      <button type="button" class="btn btn-info btn-sm" id="btnExcel1" ><i class="fa fa-export"></i>Excel</button>
                      <button type="button" class="btn btn-primary btn-sm" id="btnPrint"><i class="fa fa-print"></i>Print</button>
                      <button type="button" class="btn btn-default btn-sm" id="modalButtonClose" data-dismiss="modal">Close</button>
          ';
    echo modalCloseButtonCustom($btnCustom);
  ?>

<?php assets_script_custom("pekerjaborongan/penggajian.js"); ?>
