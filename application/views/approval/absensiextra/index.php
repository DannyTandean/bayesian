<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Approval Absensi Lembur</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblAbsensiLembur" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tanggal</th>
                            <th>Kabag</th>
                            <th>Nama</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Jabatan</th>
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
                <select id="nama_karyawan" name="karyawan" class="form-control select2" disabled>
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
                <label for="masuk" class="block">Masuk :</label>
                <input id="masuk" class="form-control" type="text" placeholder="Masuk" name="masuk" disabled>
                <div id="errorMasuk"></div>
              </div>
              <div class="col-md-6">
                <label for="keluar" class="block">Keluar :</label>
                <input id="keluar" class="form-control" type="text" placeholder="-" name="keluar" disabled>
                <div id="errorKeluar"></div>
              </div>

              <div class="col-md-6">
                <label for="breakout" class="block">Break out :</label>
                <input id="breakout" class="form-control" type="text" placeholder="-" name="breakout" disabled>
                <div id="errorBreakout"></div>
              </div>

              <div class="col-md-6">
                <label for="breakin" class="block">Break in :</label>
                <input id="breakin" class="form-control" type="text" placeholder="-" name="breakin" disabled>
                <div id="errorBreakin"></div>
              </div>
              <!-- <div class="col-md-6">
                <label for="jadwalopsi">Menggunakan Jadwal : </label>
                <select class="form-control" name="jadwal_opsi" id="jadwalopsi">
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
                </select>
              </div> -->
              <div class="col-md-6" id="shiftshow">
                <label for="pickshift">Shift : </label>
                <select class="form-control" name="shift" id="pickshift">

                </select>
              </div>
          </div>
      </div>
  </div>




	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>

<?php assets_script_custom_approval("absensiextra.js"); ?>
