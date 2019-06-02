<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Management User</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblIzinCuti" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Image</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>No Telepon</th>
                            <th>Email</th>
                            <th>Limit Transaction</th>
                            <th>Tanggal Bergabung</th>
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
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>

      <div class="form-group">

      <div class="row">

        <div class="col-md-6">
          <label for="tanggal" class="block">Tanggal :</label>
          <input id="tanggal" name="tanggal" type="text" class="form-control" value="<?php echo date("Y-m-d"); ?>" readonly>
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
                    <?php $defaultPhoto = base_url("/")."assets/images/default/no_file_.png"; ?>
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
                    <div class="form-group">
                        <label for="sdhDiambil">Sudah Diambil</label>
                        <input id="sdhDiambil" type="text" name="sdhDiambil" class="form-control" placeholder="Sudah Diambil" readonly>
                    </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                      <label for="cutiBersama">Cuti bersama</label>
                      <input id="cutiBersama" type="text" name="cutiBersama" class="form-control" placeholder="Cuti bersama" readonly>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="form-group">
                      <label for="sisaCuti">Sisa Cuti</label>
                      <input id="sisaCuti" type="text" name="sisaCuti" class="form-control" placeholder="Sisa Cuti" readonly>
                  </div>
                </div>
            </div>
            <hr>
        </div>
        <div id="errorDetailKaryawan"></div>
      </div>
    </div>
  </div>
  <div class="form-group">
      <div class="row">
        <div class="col-md-6">
          <label for="opsi_cuti">Opsi Cuti : </label>
          <select class="form-control" name="opsi_cuti" id="opsi_cuti">
            <option value="0">Normal</option>
            <option value="1">Khusus</option>
          </select>
        </div>
        <div class="col-md-6 showOpsiCuti">
          <label for="opsi_gaji_cuti">Gaji Cuti : </label>
          <input type="number" name="gaji_cuti" class="form-control" id="gaji_cuti" placeholder="gaji cuti per hari">
          <spa id="mata_uang_cuti"></span>
        </div>
        <div class="col-md-6">
          <label for="mulaiCuti">Mulai Cuti</label>
          <input type="text" name="mulaiCuti" id="mulaiCuti" class="form-control">
          <div id="errorMulaiCuti"></div>
        </div>
        <div class="col-md-6">
          <label for="akhirCuti">Akhir Cuti</label>
          <input type="text" name="akhirCuti" id="akhirCuti" class="form-control">
          <div id="errorAkhirCuti"></div>
        </div>
      </div>
  </div>


	    <div class="form-group">
            <label for="keterangan" class="block">Keterangan :</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3" style="resize: vertical;"></textarea>
            <div id="errorKeterangan"></div>
	    </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>
<!-- print space -->
<?php echo modalSaveOpen("Print","lg"); ?>
<div class="card" id="asd">
<div class="card-block">
  <table class="table invoice-table table-borderless" style="padding-left:0;">
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
                      <td><span id="tanggal1"></span></td>
                  </tr>
                  <tr>
                      <th>Status :</th>
                      <td>
                          <span class="label label-success" id="status1"></span>
                      </td>
                  </tr>
                  <tr>
                      <th>Id :</th>
                      <td>
                          #<span id="id_cuti"></span>
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
                          <td><span id="karyawan1"></span></td>
                      </tr>
                      <tr>
                          <td>Departemen</td>
                          <td><span id="departemen1"></span></td>
                      </tr>
                      <tr>
                          <td>Jabatan</td>
                          <td><span id="jabatan1"></span></td>
                      </tr>
                      <tr>
                          <td>Lama Cuti</td>
                          <td><span id="lama1"></span>Hari</td>
                      </tr>
                      <tr>
                          <td>Mulai Tanggal</td>
                          <td><span id="mulaiCuti1"></span></td>
                      </tr>
                      <tr>
                          <td>Sampai Tanggal</td>
                          <td><span id="akhirCuti1"></span></td>
                      </tr>
                      <tr>
                          <td>Untuk Keperluan</td>
                          <td><span id="keterangan1"></span></td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12">
          <p>Melalui Surat Ini Maka izin Cuti Saudara <b class="text-underline">Diterima</b>. Demikian Disampaikan Dan Dapat Dilaksanakan Dengan Baik.</p>
          <p>Terima Kasih</p>
          <br>
          <br>
          <p>Powered By Prima HRD System <?php echo date("Y"); ?></p>
      </div>
  </div>
  </div>
</div>
<?php echo modalSaveClose("Print","printing"); ?>

<?php assets_script_custom_aktivitas("cuti.js"); ?>
