<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Perjalanan Dinas</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblDinas" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tools</th>
                            <th>Tgl Input</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Departemen</th>
                            <th>Keterangan</th>
                            <th>Tanggal Dinas</th>
                            <th>Akhir Dinas</th>
                            <th>Lama Dinas</th>
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
                <label for="mulaiDinas" class="block">Mulai Dinas :</label>
                <input id="mulaiDinas" class="form-control" type="text" placeholder="Mulai Dinas" name="mulaiDinas">
                <div id="errorMulaiDinas"></div>
              </div>

              <div class="col-md-6">
                <label for="akhirDinas" class="block">Akhir Dinas :</label>
                <input id="akhirDinas" class="form-control" type="text" placeholder="Akhir Dinas" name="akhirDinas">
                <div id="errorAkhirDinas"></div>
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
                            #<span id="id_dinas1"></span>
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
                            <td>Lama Dinas</td>
                            <td><span id="lama1"></span>Hari</td>
                        </tr>
                        <tr>
                            <td>Mulai Tanggal</td>
                            <td><span id="mulaiDinas1"></span></td>
                        </tr>
                        <tr>
                            <td>Sampai Tanggal</td>
                            <td><span id="akhirDinas1"></span></td>
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
<?php assets_script_custom_aktivitas("dinas.js"); ?>
