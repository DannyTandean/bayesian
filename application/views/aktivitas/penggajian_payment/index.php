<div class="col-md-12">
	<!-- Zero config.table start -->
  <!-- nav tab -->
  <ul class="nav nav-tabs md-tabs " role="tablist">
      <!-- <li class="nav-item col-md-2">&nbsp;</li> -->

      <li class="nav-item ">
        <a class="nav-link active" data-toggle="tab" href="#tabelpenggajian"  role="tab" aria-expanded="true" id="navpenggajian">Penggajian</a>
        <div class="slide"></div>
      </li>

      <li class="nav-item ">
        <a class="nav-link" data-toggle="tab" href="#rekappenggajian"  role="tab" aria-expanded="false" id="navrekappenggajian">Rekap Penggajian</a>
        <div class="slide"></div>
      </li>

      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#historypenggajian"  role="tab" aria-expanded="false" id="navlogpenggajian">Log Penggajian</a>
          <div class="slide"></div>
      </li>
      <li class="nav-item col-md-3">&nbsp;</li>
  </ul>
  <br>
  <!-- tab panel -->
  <div class="tab-content">
      <div class="tab-pane active" id="tabpenggajian" role="tabpanel" aria-expanded="true">
        <div class="card borderless-card">
          <div class="card-header">
              <h5>Data Table Penggajian</h5>
              <div class="card-header-right">
                  <i class="icofont icofont-rounded-down"></i>
                  <!-- <i class="icofont icofont-refresh"></i> -->
                  <!-- <i class="icofont icofont-close-circled"></i> -->
              </div>
          </div>
        <div class="card-block">
          <p align="center"> <b>PROSES BERDASARKAN PERIODE GAJI KARYAWAN</b> </p>
          <div class="row">
            <div class="col-md-1">&nbsp;</div>
            <div class="col-md-9">
              <?php echo form_open("",array("id" => "formDataSearch")); ?>
                <div class="form-inline" style="margin-left:20%;">
                  <label for="ikutPotongBpjs">Ikut Potong BPJS</label>
                  &nbsp;&nbsp;
                  <select class="form-control" name="ikutPotongBpjs" id="ikutPotongBpjs">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                  </select>

                  <label for="ikutTunjangan">Ikut Tunjangan Jabatan</label>
                  &nbsp;&nbsp;
                  <select class="form-control" name="ikutTunjangan" id="ikutTunjangan">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                  </select>

                </div>
                <br>
                <div class="form-inline" style="margin-left:20%">
                  <label>Tanggal & Waktu</label>
                  &nbsp;
                    <input type="text" name="tanggal" class="form-control tanggal" id="beforeHistory">
                    &nbsp;
                    <i class="fa fa-arrows-h"></i>
                    &nbsp;
                    <input type="text" name="waktu" class="form-control time24" id="afterHistory" placeholder="waktu">
                </div>
                <br>
                <div class="form-inline" id="periodegaji" style="margin-left: 20%;">
                  <label for="ikutPotongSisa">Ikut Potong Sisa</label>
                  &nbsp;&nbsp;
                  <select class="form-control" name="ikutPotongSisa" id="ikutPotongSisa">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                  &nbsp;&nbsp;
                    <label for="periode">Periode</label>
                    &nbsp;&nbsp;
                     <select id="periode" name="periode" class="form-control periode">
                        <option value="1-Mingguan">1 Mingguan</option>
                        <option value="2-Mingguan">2 Mingguan</option>
                        <option value="Bulanan">Bulanan</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-square btn-sucess btn-success" id="Process">Proses</button>

                </div>
                <br>
                <p id="last_pay" align="center" style="font-weight:bold;"></p>
                <br>
              <?php echo form_close(); ?>

            </div>
\
          </div>
          <hr>
          <p align="center"> <b>PROSES MULTIPLE KARYAWAN</b> </p>
          <div class="row">
            <!-- <div class="col-md-1">&nbsp;</div> -->
            <div class="col-md-10">
              <?php echo form_open("",array("id" => "formDataSearchKaryawan")); ?>

                <div class="form-inline" style="margin-left: 10%;">
                  &emsp;&emsp;&emsp;&emsp;&emsp;
                  <label for="ikutPotongBpjs">Ikut Potong BPJS</label>
                  &nbsp;&nbsp;
                  <select class="form-control" name="ikutPotongBpjs1" id="ikutPotongBpjs1">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                  &nbsp;&nbsp;
                  <label for="ikutPotongSisa">Ikut Potong Sisa</label>
                  &nbsp;&nbsp;
                  <select class="form-control" name="ikutPotongSisa1" id="ikutPotongSisa1">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                  &emsp;
                  <label for="ikutTunjangan">Ikut Tunjangan Jabatan</label>
                  &nbsp;&emsp;
                  <select class="form-control" name="ikutTunjangan" id="ikutTunjangan1">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                  </select>

                  <div class="row col-md-12" style="margin-top:10px;">
                    <div class="col-md-1">
                      &nbsp;
                    </div>
                    <div class="col-md-9">
                      <select id="nama_karyawan" name="nama_karyawan[]" class="form-control" multiple="multiple">
                      </select>
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-square btn-sucess btn-success" id="multiSelect">Proses</button>
                    </div>
                  </div>

                </div>
                <br>
              <?php echo form_close(); ?>
              <br>
            </div>
          </div>


            <div class="dt-responsive table-responsive">
                <table id="tblPenggajian" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                          <th>No</th>
                          <th>Tools</th>
                          <th>Nama</th>
                          <th>IDFP</th>
                          <th>Jabatan</th>
                          <th>Departemen</th>
                          <th>Cabang</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
    <!-- ====================================== -->
    <div class="tab-pane active" id="historypenggajian" role="tabpanel" aria-expanded="false">
      <div class="card borderless-card">
        <div class="card-header">
            <h5>Data Table Log Penggajian</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
      <div class="card-block">
        <div class="row">
          <div class="col-md-3">&nbsp;</div>
          <div class="col-md-7">
            <?php echo form_open("",array("id" => "formDataSearchRekap")); ?>
              <div class="form-inline">
                  <input type="text" name="before" class="form-control tanggal" id="beforeHistory">
                  &nbsp;
                  <i class="fa fa-arrows-h"></i>
                  &nbsp;
                  <input type="text" name="after" class="form-control tanggal" id="afterHistory">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <button type="button" class="btn btn-square btn-sucess btn-success" id="ProcessHistory">Proses</button>
              </div>
            <?php echo form_close(); ?>
            <br>
          </div>
          <div class="col-md-2">
              &nbsp;
          </div>
        </div>
          <div class="dt-responsive table-responsive">
              <table id="tblLogPenggajian" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                  <thead>
                      <tr>
                        <th>No</th>
                        <th>Tools</th>
                        <th>Tanggal Proses</th>
                        <th>Nama</th>
                        <th>IDFP</th>
                        <th>Jabatan</th>
                        <th>Departemen</th>
                        <th>Cabang</th>
                        <th>Hari kerja</th>
                        <th>Nilai Bayar</th>
                        <th>Sisa</th>
                      </tr>
                  </thead>
                  <tbody></tbody>
              </table>
          </div>
    </div>
  </div>
  </div>
    <!-- end tab panel -->

    <!-- ====================================== -->
    <div class="tab-pane active" id="rekappenggajian" role="tabpanel" aria-expanded="false">
      <div class="card borderless-card">
        <div class="card-header">
            <h5>Data Table Rekap Penggajian</h5>
            <br>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
      <div class="card-block">

        <div class="row">
          <div class="col-md-2">
            &nbsp;
          </div>
          <div class="col-md-8">
            <div class="form-inline">
              <label for="ikutPotongBpjs">Ikut Potong BPJS</label>
              &nbsp;&nbsp;
              <select class="form-control" name="ikutPotongBpjs" id="ikutPotongBpjsRekap">
                <option value="ya">Ya</option>
                <option value="tidak">Tidak</option>
              </select>

              <label for="ikutTunjangan">Ikut Tunjangan Jabatan</label>
              &nbsp;&nbsp;
              <select class="form-control" name="ikutTunjangan" id="ikutTunjanganRekap">
                <option value="ya">Ya</option>
                <option value="tidak">Tidak</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            &nbsp;
          </div>
        </div>
        <br>
          <div class="row">
          <div class="col-md-2">&nbsp;</div>
            <div class="col-md-8">
              <?php echo form_open("",array("id" => "formDataSearchRekap")); ?>
                <div class="form-inline">
                    &emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;
                    <input type="text" name="before" class="form-control tanggal" id="beforeRekap">
                    &nbsp;
                    <i class="fa fa-arrows-h"></i>
                    &nbsp;
                    <input type="text" name="after" class="form-control tanggal" id="afterRekap">
                    <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
                </div>
                <br>
                <div class="form-inline">
                  &emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;
                 <input type="text" name="waktu" class="form-control time24" id="rekapWaktu" placeholder="waktu">
                 &emsp;
                 <select id="periodeRekap" name="periode" class="form-control periode" style="width:160px;">
                   <option value="1-Mingguan">1 Mingguan</option>
                   <option value="2-Mingguan">2 Mingguan</option>
                   <option value="Bulanan">Bulanan</option>
                 </select>
                 &emsp;&emsp;&emsp;
                  <button type="button" class="btn btn-square btn-sucess btn-success" id="ProcessRekap">Proses</button>
                </div>
                <br>
                <div class="form-inline">
                  <div class="col-md-5">
                    &nbsp;
                  </div>
                  <br>
                  <div class="col-md-1">
                    <button class='btn btn-success btn-sm' type="button" id="btnExcel3">Excel</button>
                  </div>
                </div>
              <?php echo form_close(); ?>
              <br>
            </div>
          <div class="col-md-2">
              &nbsp;
          </div>
        </div>
      </div>
          <div class="dt-responsive table-responsive">
              <table id="tblrekappenggajian" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                  <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>HK</th>
                        <th>Tunjangan</th>
                        <th>Transport</th>
                        <th>Makan</th>
                        <th>Tunjangan Lain</th>
                        <th>THR</th>
                        <th>Reward</th>
                        <th>Gaji Libur</th>
                        <th>Kerajinan</th>
                        <th>Bpjs</th>
                        <th>Telat</th>
                        <th>Punishment</th>
                        <th>Pinjaman</th>
                        <th>PPh Bulan Ini</th>
                        <th>Gaji Kotor</th>
                        <th>Gaji Bersih</th>
                        <!-- <th>Sisa</th> -->
                      </tr>
                  </thead>
                  <tbody></tbody>
              </table>
          </div>
    </div>
  </div>
  </div>
    <!-- end tab panel -->
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
                          <td id="periode2">:
                          </td>
                      </tr>
                      <tr>
                          <th>Cabang  </th>
                          <td id="cabang"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>No Transaksi</th>
                          <td id="notrans">:
                          </td>
                      </tr>
                      <tr>
                          <th>Periode Gajian / Tgl msk kerja  </th>
                          <td id="periode1"></td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Jumlah Hari Kerja / Bln</th>
                          <td id="harikerja">:
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
                        <th>Gaji Hari Libur</th>
                        <td id="gajiHariLibur"></td>
                        <td>&emsp;&emsp;&emsp;&emsp;</td>
                        <th>Total Telat</th>
                        <td id="totalTelat"></td>
                      </tr>
                      <tr>
                        <th>Kerajinan</th>
                        <td id="kerajinan"></td>
                        <td>&emsp;&emsp;&emsp;&emsp;</td>
                        <th>Potongan BPJS</th>
                        <td id="bpjs">:</td>
                      </tr>
                      <tr>
                          <th>Tunjangan Transport</th>
                          <td id="transport">:</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Potongan Pinjaman</th>
                          <td id="pinjaman">:</td>
                      </tr>
                      <tr>
                          <th>Overtime / kerja extra  </th>
                          <td id="extra">:</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Potongan Punishment</th>
                          <td id="punishment">: </td>
                      </tr>
                      <tr>
                          <th>Tunjangan Makan</th>
                          <td id="makan">:</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>Potongan Lain</th>
                          <td id="potLain">:</td>
                      </tr>
                      <tr>
                          <th>Tunjangan Jabatan</th>
                          <td id="tunjanganJabatan">:</td>
                          <td>&emsp;&emsp;&emsp;&emsp;</td>
                          <th>PPh Bulan Ini</th>
                          <td id="pph">:</td>
                      </tr>
                      <tr>
                          <th>Tunjangan Hari Raya</th>
                          <td id="thr">:</td>
                      </tr>
                      <tr>
                          <th>Reward</th>
                          <td id="reward">:</td>
                      </tr>
                      <tr>
                          <th>Tunjangan Lain</th>
                          <td id="tunjanganLain">:</td>
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
                        <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
                        <th>Total Potongan</th>
                        <td id="totalPotongan">:</td>
                        <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
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
                      <td>Jadwal</td>
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
<?php echo modalSaveClose("Print","printing"); ?>

<?php echo modalSaveOpen("pay"); ?>
<!-- <div class="card" > -->
<?php echo form_open("",array("id" => "paymentData")); ?>
  <div class="card-block">
    <div class="row invoive-info">
      <div class="col-md-12 col-sm-12">
        <div class="form-group">
          <div class="row">
            <div class="col-md-12">
              <span id="message"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Gaji Absensi </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="gaji_absensi" class="form-control" readonly id="gaji_absensi">
            </div>
          </div>
          <hr>
          <b>Penambahan</b>
          <br>
          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Makan </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="makan1" class="form-control" readonly id="makan1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Transport </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="transport1" class="form-control" readonly id="transport1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Extra </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="extra1" class="form-control" readonly id="extra1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Reward </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="reward1" class="form-control" readonly id="reward1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Kerajinan </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="kerajinan" class="form-control" readonly id="penggajian_kerajinan">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Tunjangan </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="tunjangan1" class="form-control" readonly id="tunjangan1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Tunjangan Lain </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="tun_lain1" class="form-control" readonly id="tun_lain1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> THR </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="thr1" class="form-control" readonly id="thr1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <span>Sisa Sebelumnya</span>
            </div>
            <div class="col-md-6">
              <input type="number" name="payment_sisa_sebelumnya" class="form-control" id="payment_sisa_sebelumnya" readonly>
            </div>
          </div>
          <hr>
          <b>Pengurangan</b>
          <br>
          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Potongan BPJS </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="bpjs1" class="form-control" readonly id="bpjs1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Potongan Telat </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="telat1" class="form-control" readonly id="telat1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Potongan Pinjaman </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="pinjaman1" class="form-control" readonly id="pinjaman1">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> Punishment </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="punishment1" class="form-control" readonly id="punishment1">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> PPh Bulan Ini </span>
            </div>
            <div class="col-md-6">
              <input type="number" name="pph" class="form-control" readonly id="pph1">
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-6" style="padding-top:10px">
              <span> <b>Total Penerimaan</b> </span>
            </div>
            <div class="col-md-6">
              <input type="hidden" name="hari_kerja_karyawan" id="hariKerjaKaryawan">
              <input type="hidden" name="gaji_total_tambahan" id="gajiTotalTambahan">
              <input type="hidden" name="start_date" id="start_date">
              <input type="hidden" name="end_date" id="end_date">
              <input type="hidden" name="id_karyawan" id="id_karyawan">
              <input type="hidden" name="hari_kerja" id="hari_kerja">
              <input type="number" name="penerimaan" class="form-control" id="penerimaan" readonly>

            </div>
          </div>

          <div class="row">
              <div class="col-md-6">
                <span> <b>Total Pengambilan</b> </span>
              </div>
              <div class="col-md-6">
                <input type="number" name="pengambilan" class="form-control" id="pengambilan">
              </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <span> <b>Sisa</b> </span>
            </div>

            <div class="col-md-6">
              <input type="number" name="sisa" class="form-control" id="sisa" readonly>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <?php echo form_close(); ?>
<!-- </div> -->
<?php echo modalSaveClose("pay","payment") ?>

<?php assets_script_custom("aktivitas/penggajianpayment.js"); ?>
