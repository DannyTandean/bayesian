<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Manage Produk</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblProduk" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tools</th>
                            <th>Nama Produk</th>
                            <th>Image</th>
                            <th>Produk Stock</th>
                            <th>Produk deskripsi</th>
                            <th>Harga Produk</th>
                            <th>Status</th>
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

<?php assets_script_custom_aktivitas("manage_produk.js"); ?>
