<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Promosi Demosi Mutasi</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblPromosi" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tools</th>
                            <th>Judul</th>
                            <th>Nama Karyawan</th>
                            <th>Cabang</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Grup</th>
                            <th>Golongan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Status Info</th>
                            <th>Create At</th>
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
          <input id="status" class="form-control" type="hidden" name="status">
          <input id="statusInfo" class="form-control" type="hidden" name="statusInfo">
          <input id="id_promosi" class="form-control" type="hidden" name="idPromosi">
          <input id="id_karyawan3" name="idKaryawan" type="hidden">
          <div id="errorStatus"></div>
    </div>
  <?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>

<!-- Detail Karyawan -->
<?php echo modalSaveOpen("Details","lg"); ?>

                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">No ID</label>
                            <div class="col-sm-8">
                                <span id="noid2"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-8">
                                <span id="nama2"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Photo Karyawan</label>
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
                                                    <small><b class="">Photo master</b></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="photoApproval" style="display: none;">
                                        <div class="card rounded-card user-card" style="height: 190px; width: 150px;">
                                            <div class="card-block">
                                                <div class="img-hover">
                                                    <img class="img-fluid img-circle" id="detail_img_photo_tmp" src="<?php echo $defaultPhoto;?>" alt="karyawan-img" style="height: 100px;">
                                                    <div class="img-overlay">
                                                        <span>
                                                            <a href="javascript:void(0);" title="Zoom Photo" id="btnDetailZoomImgTmp" class="btn btn-sm btn-success btn-outline-success">
                                                                <i class="fa fa-search-plus"></i>
                                                            </a>
                                                            
                                                        </span>
                                                    </div>

                                                    <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpPhotoOutTmp" data-title="Photo Karyawan" data-footer="">
                                                        <img src="<?php echo $defaultPhoto;?>" id="detailPopUpPhotoInTmp" class="img-fluid" alt="">
                                                    </a>
                                                </div>
                                                <div class="user-content">
                                                    <small><b style="color: blue;">Photo Approval</b></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                                    <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Cabang</label>
                                    <div class="col-sm-8">
                                    <span id="cabang2"></span>
                                    <hr>
                                    </div>
                                    </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Departemen</label>
                                    <div class="col-sm-8">
                                     <span id="departemen2"></span>
                                    <hr>
                                    </div>
                                </div>

                                    <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jabatan</label>
                            <div class="col-sm-8">
                                <span id="jabatan2"></span>
                                <hr>
                            </div>
                        </div>
                                      <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Grup</label>
                            <div class="col-sm-8">
                                <span id="grup2"></span>
                                <hr>
                            </div>
                        </div>

                                     <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Golongan</label>
                            <div class="col-sm-8">
                                <span id="golongan2"></span>
                                <hr>
                            </div>
                        </div>

                                      <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-8">
                                <span id="keterangan2"></span>
                                <hr>
                            </div>
                        </div>
<?php echo modalDetailClose(); ?>
<?php assets_script_custom("approvalowner/pdm.js"); ?>
