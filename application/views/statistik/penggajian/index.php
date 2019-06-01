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
                <table id="tblPenggajian" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                          <th>No</th>
                        <!--   <th>Slip</th> -->
                          <!-- <th>Kode Payroll</th> -->
                          <th>IDFP</th>
                          <th>Nama Karyawan</th>
                          <th>Jabatan</th>
                          <th>Departemen</th>
                          <th>Cabang</th>
                          <th>Tanggal Proses</th>
                          <th>Hari Kerja</th>
                          <th>Nilai Bayar</th>
                          <th>Sisa</th>
                          <th>Tunjangan</th>
                          <th>Potongan Bpjs</th>
                          <th>Potogan Telat</th>
                          <th>Potongan Pinjaman</th>
                          <th>Transport</th>
                          <th>Makan</th>
                          <th>Tunjangan Lain</th>
                          <th>Thr</th>
                          <th>Reward</th>
                          <th>Punishment</th>
                          <th>Gaji Libur</th>
                          <th>Gaji Kerajinan</th>
                          <th>Gaji Total</th>
                          <th>Gaji Extra</th>
                          <th>Gaji Bersih</th>
                          <th>Keterangan</th>
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
                          <th>Rekening</th>
                          <td id="rekening"></td>
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
                          <th>Periode</th>
                          <td id="periode2">: 145698
                          </td>
                      </tr>
                      <tr>
                          <th>Cabang  </th>
                          <td id="cabang"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>No Transaksi</th>
                          <td id="notrans">: 145698
                          </td>
                      </tr>
                      <tr>
                          <th>Periode Gajian / Tgl msk kerja  </th>
                          <td id="periode1"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Jumlah Hari Kerja / Bln</th>
                          <td id="harikerja">: 145698
                          </td>
                      </tr>
                  </tbody>
              </table>
            </div>
          </div>
          <hr style="border : 1px solid grey">
          <div class="row">
            <div class="col-md-12">
              <table>
                  <tbody>
                      <tr>
                          <th>Gaji Pokok </th>
                          <td id="gajiPokok"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&ensp;</td>
                          <th>Potongan Terlambat</th>
                          <td id="terlambat"></td>
                      </tr>
                      <tr>
                          <th>Tunjangan Transport</th>
                          <td id="transport">:</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <!-- <th>Potongan Absen</th>
                          <td id="potonganAbsen"></td> -->
                      </tr>
                      <tr>
                          <th>Overtime / kerja extra  </th>
                          <td id="extra">: 145698</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Potongan BPJS</th>
                          <td id="bpjs">:</td>
                      </tr>
                      <tr>
                          <th>Tunjangan Makan</th>
                          <td id="makan">: 145698</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Potongan Pinjaman</th>
                          <td id="pinjaman">:</td>
                      </tr>
                      <tr>
                          <th>Tunjangan Jabatan</th>
                          <td id="tunjanganJabatan">: 145698</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Potongan Punishment</th>
                          <td id="punishment">: </td>
                      </tr>
                      <tr>
                          <th>Tunjangan Hari Raya</th>
                          <td id="thr">: 145698</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Potongan Lain</th>
                          <td id="potLain">: 145698</td>
                      </tr>
                      <tr>
                          <th>Reward</th>
                          <td id="reward">: 145698</td>
                      </tr>
                      <tr>
                          <th>Tunjangan Lain</th>
                          <td id="tunjanganLain">: 145698</td>
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
                        <td id="totalGaji">:</td>
                        <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
                        <th>Total Potongan</th>
                        <td id="totalPotongan">:</td>
                        <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
                        <th>Take Home Pay</th>
                        <td id="totalBersih">:</td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <hr style="border : 1px solid grey">
      <div class="row invoive-info">
          <div class="col-md-12 col-sm-12">
              <h6>Informasi Absensi : </h6>
              <table border="1" id="tableDataAbsensi" style="border-collapse : collapse; margin : 0 auto;text-align:center;width:100%;">
                  <thead>
                    <tr>
                      <td>No</td>
                      <td>Tanggal</td>
                      <td>C.In</td>
                      <td>C.Out</td>
                      <td>telat</td>
                      <td>pulang cepat</td>
                      <td>Status</td>
                      <td>Token</td>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
              </table>
          </div>
      </div>
      <div class="row">
          <div class="col-sm-12">
              <p>Melalui Surat Ini Maka Perjalanan Dinas Saudara <b class="text-underline">Diterima</b>. Demikian Disampaikan Dan Dapat Dilaksanakan Dengan Baik.</p>
              <p>Terima Kasih</p>
              <br>
              <br>
              <p>Powered By Prima HRD System <?php echo date("Y"); ?></p>
          </div>
      </div>
    </div>
  </div>
<?php echo modalSaveClose("Print","printing"); ?>

<?php assets_script_custom("statistik/penggajian.js"); ?>
