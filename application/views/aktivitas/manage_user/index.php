<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table User</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblUser" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Phone Number</th>
                            <!-- <th>Email</th>
                            <th>Limit Transaction</th>
                            <th>Join Date</th> -->
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<!-- Detail User -->
<?php echo modalSaveOpen("Details"); ?>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Nama</label>
      <div class="col-sm-8">
          <span id="nama2"></span>
          <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Photo User</label>
      <div class="col-sm-8">
          <?php $defaultPhoto = base_url("/")."assets/images/default/no_user.png"; ?>
          <div class="row">
              <div class="col-md-6" id="photoMaster">
                  <div class="card rounded-card user-card" style="height: 190px; width: 150px;">
                      <div class="card-block">
                          <div class="img-hover">
                              <img class="img-fluid img-circle" id="detail_img_photo" src="<?php echo $defaultPhoto;?>" alt="karyawan-img" style="height: 100px;">
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
                          <div class="user-content">
                              <small><b class="">Photo user</b></small>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
      <div class="col-sm-8">
      <span id="jk"></span>
      <hr>
      </div>
  </div>

  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Nomor Telepon</label>
      <div class="col-sm-8">
       <span id="no_telp2"></span>
      <hr>
      </div>
  </div>

  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Transaction Limit</label>
      <div class="col-sm-8">
          <span id="trans_limit"></span>
          <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Tanggal Bergabung</label>
      <div class="col-sm-8">
          <span id="tgl_bergabung"></span>
          <hr>
      </div>
  </div>

</div>
<?php echo modalDetailClose(); ?>

<?php assets_script_custom_aktivitas("manage_user.js"); ?>
