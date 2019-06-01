<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Pengumuman & Berita</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblPengumuman" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tools</th>
                            <th>Photo</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Tipe</th>
                            <th>Penulis</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<?php echo modalSaveOpen(false,"lg"); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>
      <div class="form-group">
            <label for="tanggal" class="block">Tanggal :</label>
            <input id="tanggal" class="form-control" type="text" placeholder="Select Your Date" name="tanggal" readonly value="<?php echo date("Y-m-d"); ?>">
            <input type="hidden" name="username" value="<?php echo $this->user->username; ?>">
      </div>

      <div class="form-group">
          <div class="row">

            <div class="col-md-6">
              <label for="judul" class="block">Judul</label>
              <input id="judul" class="form-control" type="text" placeholder="Judul" name="judul">
              <div id="errorJudul"></div>
            </div>

              <div class="col-md-6">
                <label for="tipePengumuman" class="block">Tipe : </label>
                <select class="form-control" name="tipe" id="tipePengumuman">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="berita">Berita</option>
                    <option value="pengumuman">Pengumuman</option>
                </select>
                <div id="errorTipe"></div>
              </div>

              <div class="form-group col-md-12">
                <label class="label-form">Upload Photo Pengumuman</label>
                  <div class="col-sm-8">
                    <?php $defaultPhoto = base_url("/")."assets/images/default/no_file_.png"; ?>
                <div class="card rounded-card user-card" style="height: 180px; width: 180px;">
                            <div class="card-block">
                                <div class="img-hover">
                                    <img class="img-fluid img-square" id="img_photo" src="<?php echo $defaultPhoto;?>" alt="karyawan-img" style="height: 125px;">
                                    <div class="img-overlay">
                                        <span>
                                            <a href="javascript:void(0);" title="Zoom Photo" id="btnZoomImg" class="btn btn-sm btn-success btn-outline-success">
                                              <i class="fa fa-search-plus"></i>
                                            </a>
                                            <a href="javascript:void(0);" title="Pilih Upload photo" class="btn btn-sm btn-primary btn-outline-primary" id="ganti_photo" data-popup="lightbox">
                                              <i class="fa fa-upload"></i>
                                              </a>
                                            <a href="javascript:void(0);" title="Hapus photo" id="hapus_photo" class="btn btn-sm btn-danger btn-outline-danger">
                                              <i class="fa fa-trash-o"></i>
                                            </a>
                                        </span>
                                    </div>

                              <input name="photo_pengumuman" id="photo_karyawan" type="file" style="display: none;">
                              <input type="hidden" name="is_delete_photo" id="is_delete_photo" value="0">

                                    <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="popUpPhotoOut" data-title="Photo Karyawan" data-footer="">
                                  <img src="<?php echo $defaultPhoto;?>" id="popUpPhotoIn" class="img-fluid" alt="">
                                  </a>
                                </div>
                                <!-- <div class="user-content"><h4 class="">Photo Pengumuman</h4></div> -->
                            </div>
                        </div>
                    </div>
              </div>

              <div class="col-md-12">
                <label for="keterangan" class="block">Isi Pengumuman & Berita :</label>
                <div class="card">
                  <div class="card-block">
                      <textarea id="editor9" name="isiPengumuman" style="resize: vertical;"></textarea>
                  </div>
                </div>
                <div id="errorIsi"></div>
              </div>


          </div>
      </div>
  </div>




	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>
<!-- Details space -->
<?php echo modalSaveOpen("Details","lg"); ?>
<div class="col-md-12">
  <?php $defaultPhoto1 = base_url("/")."assets/images/default/no_file_.png"; ?>
  <div class="card-header">
    <center><b><span id="judulDetail"></span></b></center>

  </div>
  <div class="card-body">
    <img class="img-square mx-auto d-block" id="detailfoto" src="<?php echo $defaultPhoto1;?>" alt="pengumuman-img" height="150" width="150">
    <br>
      <div id="isiDetail"></div>
  </div>

  <div class="card-footer font-weight-bold">
    <p>Tanggal <span id="detailTipe"></span> :  &nbsp;<span id="tglDetail"></span> <span id="penulisDetail" class="float-right"></span><span class="float-right">Oleh : &nbsp; </span> </p>
  </div>
</div>
<?php echo modalDetailClose(); ?>
<?php assets_script_custom_aktivitasowner("pengumuman.js"); ?>
