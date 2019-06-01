<div class="col-md-12">
	<!-- Zero config.table start -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs md-tabs " role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#pinjaman" id="pinjaman1" role="tab" aria-expanded="true">Pinjaman</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#pembayaran" id="pembayaran1" role="tab" aria-expanded="false">Pembayaran</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#logpembayaran" id="logpembayaran1" role="tab" aria-expanded="false">Log Pembayaran</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">&nbsp;</li>
    </ul>
    <!-- Tab panes -->
    <br>
    <div class="tab-content">
        <div class="tab-pane active" id="pinjaman" role="tabpanel" aria-expanded="true">
          <div class="card">
              <div class="card-header">
                  <h5>Data Table Pinjaman Karyawan</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                  <div class="dt-responsive table-responsive">
                      <table id="tblPinjaman" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Action</th>
                                  <th>Tools</th>
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
          </div>
        </div>
        <div class="tab-pane" id="pembayaran" role="tabpanel" aria-expanded="false">
          <div class="card">
              <div class="card-header">
                  <h5>Data Table Pembayaran Karyawan</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                  <div class="dt-responsive table-responsive">
                      <table id="tblPembayaran" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Tools</th>
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
          </div>
        </div>
        <div class="tab-pane" id="logpembayaran" role="tabpanel" aria-expanded="false">
          <div class="card">
              <div class="card-header">
                  <h5>Data Table Log Pembayaran Karyawan</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                  <div class="dt-responsive table-responsive">
                      <table id="tblLogPembayaran" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
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

<?php echo modalSaveOpen(); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>

      <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <label for="tanggal" class="block">Tanggal :</label>
              <input id="tanggal" class="form-control" type="text" placeholder="Select Your Date" name="tanggal" disabled="disabled">
              <div id="errorTanggal"></div>
            </div>

              <div class="col-md-6">
                <label class="block">Nama Karyawan :</label>
                <select id="nama_karyawan" name="karyawan" class="form-control select2">
                </select>
                <div id="errorNamaKaryawan"></div>
              </div>

              <div class="col-md-12">
                <div id="detailKaryawan" style="display: none;">
                    <hr>
                    <div class="row">
                        <div class="col-md-5">
                            <label class="block">Foto Karyawan :</label>
                            <?php $defaultPhoto = base_url("/")."assets/images/default/no_user.png"; ?>
                            <div class="card rounded-card user-card" style="height: 180px; width: 180px;">
                                <div class="card-block">
                                    <div class="img-hover">
                                        <img class="img-fluid img-circle" id="detail_img_photo" src="<?php echo $defaultPhoto;?>" alt="karyawan-img" style="height: 125px;">
                                        <div class="img-overlay">
                                            <span>
                                                <a href="javascript:void(0);" title="Zoom Photo" id="btnDetailZoomImg" class="btn btn-sm btn-success btn-outline-success">
                                                    <i class="fa fa-search-plus"></i>
                                                </a>
                                            </span>
                                        </div>

                                        <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpPhotoOut" data-title="Photo Karyawan" data-footer="">
                                            <img src="<?php echo $defaultPhoto;?>" id="detailPopUpPhotoIn" class="img-fluid" alt="">
                                        </a>
                                    </div>
                                    <!-- <div class="user-content"><h4 class="">Photo Karyawan</h4></div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="departemen" class="block">Departemen :</label>
                                <input id="departemen" name="departemen" type="text" disabled class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="jabatan" class="block">Jabatan :</label>
                                <input id="jabatan" name="jabatan" type="text" disabled class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div id="errorDetailKaryawan"></div>
              </div>

              <div class="col-md-6">
                <label for="jlhPinjaman">Jumlah Pinjaman</label>
                <input type="number" name="jlhPinjaman" class="form-control" id="jlhPinjaman" placeholder="Jumlah Pinjaman">
                <span id="mata_uang_pinjaman"></span>
                <div id="errorJlhPinjaman"></div>
              </div>

              <div class="col-md-6">
                <label for="caraPembayaran">Cara Pembayaran : </label>
                <select class="form-control" name="caraPembayaran" id="caraPembayaran">
                  <option value="">-- Opsi Pembayaran --</option>
                  <option value="Potongan Gaji">Potong Gaji</option>
                  <option value="Cash">Cash</option>
                </select>
                <div id="errorOpsi"></div>
              </div>

              <div class="col-md-12" id="cicilanDisplay">
                <label for="cicilan">Jumlah Cicilan Per Periode Gajian </label>
                <input type="number" name="cicilan" class="form-control" id="cicilan" placeholder="Jumlah Cicilan Per Periode Gajian">
              </div>

              <div class="col-md-12">
                <label for="keterangan" class="block">Keterangan :</label>
                <textarea id="keterangan" name="keterangan" class="form-control" style="resize: vertical;" placeholder="keterangan"></textarea>
                <div id="errorKeterangan"></div>
              </div>

          </div>
      </div>
  </div>

	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>
<!-- payment modal -->
<?php echo modalSaveOpen("Payment"); ?>
    <div id="inputMessage1"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formDataPay")); ?>
      <div class="form-group">
            <label for="tanggal1" class="block">Tanggal :</label>
            <input id="tanggal1" class="form-control" type="text" placeholder="Select Your Date" name="tanggal1" readonly>
            <div id="errorTanggal1"></div>
      </div>

      <div class="form-group">
          <div class="row">
              <div class="col-md-12">
                <label for="karyawan">Nama karyawan</label>
                <select  class="form-control" name="karyawan" id="karyawan1" disabled="disabled">
                  <option value="">-- select karyawan --</option>
                  <?php foreach ($karyawan as $item):?>
                    <option value="<?php echo $item["id"];?>"><?php echo $item["nama"];?></option>
                  <?php endforeach;?>
                </select>
                <div id="errorKaryawan1"></div>
              </div>
              <div class="col-md-6">
                <label for="departemen">Departemen : </label>
                <input id="departemen1" type="text" name="departemen" class="form-control" placeholder="Departemen" readonly>
              </div>

              <div class="col-md-6">
                <label for="jabatan">Jabatan : </label>
                <input id="jabatan1" type="text" name="jabatan" class="form-control" placeholder="Jabatan" readonly>
              </div>

              <div class="col-md-6">
                <label for="jlhPinjaman">Jumlah Pinjaman</label>
                <input type="number" name="jlhPinjaman1" class="form-control" id="jlhPinjaman1" placeholder="Jumlah Pinjaman" readonly>
                <span id="mata_uang_pinjaman1"></span>
                <div id="errorJlhPinjaman1"></div>
              </div>

              <div class="col-md-6">
                <label for="sisaPinjaman">Sisa Pinjaman</label>
                <input id="sisaPinjaman" type="number" name="sisaPinjaman" class="form-control" placeholder="Sisa Pinjaman" readonly>
              </div>

              <div class="col-md-12">
                <label for="PembPinjaman">Jumlah Pembayaran</label>
                <input id="PembPinjaman" type="number" name="pembpinjaman" class="form-control" placeholder="Jumlah Pembayaran">
                <span id="mata_uang_pembPinjaman"></span>
                <div id="errorPembPinjaman"></div>
              </div>

              <div class="col-md-12">
                <label for="keterangan" class="block">Keterangan :</label>
                <textarea id="keterangan1" name="keterangan" class="form-control" style="resize: vertical;" placeholder="keterangan"></textarea>
                <div id="errorKeterangan1"></div>
              </div>

          </div>
      </div>
  </div>
  <?php echo form_close(); ?>
	<?php echo modalSaveClose("Payment","payment"); ?>
<!-- end of payment modal -->
<!-- print modal -->
<?php echo modalSaveOpen("Print","lg"); ?>
<div class="card" id="asd">
<div class="card-block">
  <table class="table invoice-table table-borderless">
      <tbody>
          <tr>
              <td><img src="<?php echo $defaultPhoto; ?>" class="m-b-10" alt="" height="50" width="50" id="logo"></td>
          </tr>
          <tr>
              <td id="namaPerusahaan"></td>
          </tr>
          <tr>
              <td id="alamat"></td>
          </tr>
          <tr>
              <td>Email : <a href="mailto:demo@gmail.com" target="_top" id="emailPerusahaan">demo@gmail.com</a>
              </td>
          </tr>
          <tr>
              <td>No Telpon : <span id="noTelp"></span> </td>
          </tr>
          <tr>
            <td>No Fax :  <span id="noFax"></span> </td>
          </tr>
      </tbody>
  </table>
  <div class="row invoive-info">
      <div class="col-md-6 col-sm-6">
          <h6>Order Information :</h6>
          <table class="table table-responsive invoice-table invoice-order table-borderless">
              <tbody>
                  <tr>
                      <th>Date : </th>
                      <td><span id="tanggal11"></span></td>
                  </tr>
                  <tr>
                      <th>Status :</th>
                      <td>
                          <span class="label label-success" id="status11"></span>
                      </td>
                  </tr>
                  <tr>
                      <th>Id Pinjaman :</th>
                      <td>
                          #<span id="id_pinjaman1"></span>
                      </td>
                  </tr>
              </tbody>
          </table>
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12">
          <div class="table-responsive">
              <table class="table  invoice-detail-table">
                  <tbody>
                      <tr>
                          <td>Nama Lengkap</td>
                          <td><span id="karyawan11"></span></td>
                      </tr>
                      <tr>
                          <td>Departemen</td>
                          <td><span id="departemen11"></span></td>
                      </tr>
                      <tr>
                          <td>Jabatan</td>
                          <td><span id="jabatan11"></span></td>
                      </tr>
                      <tr>
                          <td>Jumlah Pinjaman</td>
                          <td><span id="jumlah1"></span></td>
                      </tr>
                      <tr>
                        <td>Sisa Pinjaman</td>
                        <td><span id="sisaPinjaman11"></span></td>
                      </tr>
                      <tr>
                          <td>Cara Pembayaran</td>
                          <td><span id="caraPembayaran1"></span></td>
                      </tr>
                      <tr>
                          <td>Untuk Keperluan</td>
                          <td><span id="keterangan11"></span></td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12">
          <br>
          <p>Melalui Surat Ini Maka Permohonan Pinjaman Saudara <b>DISETUJUI</b>. Untuk Selanjutnya Silahkan Tanda Tangani
            Surat Ini Sebagai Bukti Serah Terima Uang Pinjaman, Terima Kasih.</p>
            <div class="row">
              <div class="col-md-6" style="width:50%;">
                <center>Diserahkan Oleh,</center>
                <br>
                <br>
                <br>
                <br>
                <center>(.........................)</center>
              </div>
              <div class="col-md-6" style="width:50%;">
                <center> Diterima Oleh,</center>
                <br>
                <br>
                <br>
                <br>
                <center>(<span id="penerima"></span>)</center>
              </div>
            </div>
            <br><br>
            <p class="text-left p-0 m-0">Powered By Prima HRD System <?php echo date("Y"); ?></p>
      </div>
  </div>
  </div>
</div>
<?php echo modalSaveClose("Print","printing"); ?>
<?php assets_script_custom_aktivitas("pinjaman.js"); ?>
