<style type="text/css">
  .select2-container--default .select2-selection--single .select2-selection__rendered {
      background-color: #ffffff;
      color: #0a0a0a;
      padding: 8px 30px 4px 20px;
      line-height: 20px;
  }
</style>
<div class="col-md-12">
	<!-- Zero config.table start -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs md-tabs " role="tablist">
        <!-- <li class="nav-item col-md-3">&nbsp;</li> -->
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#kerjanormal"  role="tab" aria-expanded="true" id="contentKerjaNormal">Kerja Normal</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#kerjaextra"  role="tab" aria-expanded="false" id="contentKerjaExtra">Kerja Extra</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#absensi"  role="tab" aria-expanded="false" id="contentAbsensi">Total Kerja Karyawan</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item col-md-3">&nbsp;</li>
    </ul>
    <br>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="kerjanormal" role="tabpanel" aria-expanded="true">
          <div class="card borderless-card">
              <div class="card-header">
                  <h5>Data Table Kerja Normal</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                <div class="row">
                  <div class="col-md-2">&nbsp;</div>
                  <div class="col-md-8">
                    <?php echo form_open("",array("id" => "formDataSearch")); ?>
                      <div class="form-inline">
                          &emsp;&emsp;&emsp;&emsp;&emsp;
                          <input type="text" name="before" class="form-control tanggal" id="before">
                          &nbsp;
                          <i class="fa fa-arrows-h"></i>
                          &nbsp;
                          <input type="text" name="after" class="form-control tanggal" id="after">
                          &nbsp;
                          <button type="button" class="btn btn-primary btn-outline-primary btn-icon m-l-5" id="searchDate"><i class="icofont icofont-search-alt-1"></i></button>
                      </div>
                    <?php echo form_close(); ?>
                    <br>
                  </div>
                  <div class="dt-responsive table-responsive">
                      <table id="tblKerjaNormal" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Kabag</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Token</th>
                                    <th>Payment</th>
                                    <th>Jadwal</th>
                                    <th>Masuk</th>
                                    <th>Break Out</th>
                                    <th>Break In</th>
                                    <th>Keluar</th>
                                    <th>NIK</th>
                                    <th>Jabatan</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
                </div>
              </div>
          </div>
        </div>

         <div class="tab-pane" id="kerjaextra" role="tabpanel" aria-expanded="true">
          <div class="card borderless-card">
              <div class="card-header">
                  <h5>Data Table Kerja Extra</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                <div class="row">
                  <div class="col-md-2">&nbsp;</div>
                  <div class="col-md-8">
                    <?php echo form_open("",array("id" => "formDataSearch1")); ?>
                      <div class="form-inline">
                          &emsp;&emsp;&emsp;&emsp;&emsp;
                          <input type="text" name="before" class="form-control tanggal" id="before1">
                          &nbsp;
                          <i class="fa fa-arrows-h"></i>
                          &nbsp;
                          <input type="text" name="after" class="form-control tanggal" id="after1">
                          &nbsp;
                          <button type="button" class="btn btn-primary btn-outline-primary btn-icon m-l-5" id="searchDate1"><i class="icofont icofont-search-alt-1"></i></button>
                      </div>
                    <?php echo form_close(); ?>
                    <br>
                  </div>

                  <div class="dt-responsive table-responsive">
                      <table id="tblKerjaExtra" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Total Kerja</th>
                                    <th>Payment</th>
                                    <th>Kabag</th>
                                    <th>NIK</th>
                                    <th>Jabatan</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="tab-pane" id="absensi" role="tabpanel" aria-expanded="true">
          <div class="card borderless-card">
              <div class="card-header">
                  <h5>Data Table Total Kerja Karyawan Bulan Lalu</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                  <div class="dt-responsive table-responsive">
                      <table id="tblKerjaDisiplin" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Total Kerja</th>
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
  <?php echo form_open("",array("id" => "formData")); ?>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="block">Tanggal Masuk :</label>
            <input id="tanggal" name="tanggal" type="text" class="form-control tanggal" placeholder="pilih tanggal">
            <div id="errorTanggal"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="block">Tanggal Keluar :</label>
            <input id="tanggalKeluar1" name="tanggalKeluar" type="text" class="form-control tanggal" placeholder="pilih tanggal">
            <div id="errorTanggalKeluar1"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="block shift">Jadwal :</label>
            <select id="shift" name="shift" class="form-control jadwal">
              <option value="">-- Pilih Shift --</option>

            </select>
          </div>
        </div>
      </div>

      <div class="form-group" id="kabagForm">
        <label class="block kabag">Kabag :</label>
        <select id="kabag" name="kabag" class="form-control kabag">
          <!-- <option value="">-- Pilih Kabag --</option> -->
          <?php //foreach ($kabag as $val): ?>
              <!-- <option value="<?php //echo $val['kode_karyawan']; ?>"><?php //echo $val['nama']; ?></option> -->
          <?php //endforeach ?>
        </select>
        <div id="errorKabag"></div>
      </div>

	    <div class="form-group">
        <label class="block">Nama Karyawan :</label>
        <select id="karyawan" name="karyawan" class="form-control">
        	<!-- <option value="">-- Pilih Karyawan --</option> -->
        	<?php //foreach ($karyawan as $val): ?>
        	 <!-- <option value="<?php //echo $val['kode_karyawan']; ?>"><?php //echo $val['nama']; ?></option> -->
        	<?php //endforeach ?>
        </select>
        <div id="errorKaryawan"></div>
	    </div>

      <div class="row">
          <div class="col-md-6">
            <label for="masuk">Masuk : </label>
            <div class="input-group time24">
              <span class="input-group-addon bg-default">
                <span class="icofont icofont-ui-clock"></span>
              </span>
              <input id="masuk" type="text" name="masuk" class="form-control time24" placeholder="insert Time">
              <div id="errorMasuk"></div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="keluar">Keluar : </label>
            <div class="input-group time24">
              <input id="keluar" type="text" name="keluar" class="form-control time24" placeholder="insert Time">
              <span class="input-group-addon bg-default">
                <span class="icofont icofont-ui-clock"></span>
              </span>
              <div id="errorKeluar"></div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="BreakOut">Break Out : </label>
            <div class="input-group time24">
              <span class="input-group-addon bg-default">
                <span class="icofont icofont-ui-clock"></span>
              </span>
              <input id="BreakOut" type="text" name="BreakOut" class="form-control time24" placeholder="insert Time">
               <div id="errorBreakOut"></div>
            </div>
            <div class="checkbox-fade fade-in-success">
                <label>
                    <input type="checkbox" id="kosongout" value="0">
                    <span class="cr">
                        <i class="cr-icon icofont icofont-ui-check txt-success"></i>
                    </span> <span>Kosongkan Jadwal Break Out</span>
                </label>
            </div>
          </div>

          <div class="col-md-6">
            <label for="BreakIn">Break In : </label>
            <div class="input-group time24">
              <input id="BreakIn" type="text" name="BreakIn" class="form-control date" placeholder="insert Time">

              <span class="input-group-addon bg-default">
                <span class="icofont icofont-ui-clock"></span>
              </span>
              <div id="errorBreakIn"></div>
            </div>
            <div class="checkbox-fade fade-in-success">
                <label>
                    <input type="checkbox" id="kosongin" value="0">
                    <span class="cr">
                        <i class="cr-icon icofont icofont-ui-check txt-success"></i>
                    </span> <span>Kosongkan Jadwal Break In</span>
                </label>
            </div>
            <input id="status" type="hidden" name="status">
          </div>
      </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>

<?php echo modalSaveOpen("TambahExtra"); ?>
    <div id="inputMessage11"></div>
    <?php echo form_open("",array("id" => "formDataExtra")); ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group" id="infoKabagExtra">
                    <label class="block kabag">Kabag :</label>
                    <select id="kabag11" name="kabag" class="form-control kabag">
                    </select>
                    <div id="errorKabag11"></div>
              </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
                  <label class="block">Nama Karyawan :</label>
                  <select id="karyawan11" name="karyawan" class="form-control">
                  </select>
                  <div id="errorKaryawan11"></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
                  <label class="block">Tanggal Masuk:</label>
                  <input id="tanggal11" name="tanggal" type="text" class="form-control tanggal" placeholder="pilih tanggal">
                  <div id="errorTanggal11"></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
                  <label class="block">Tanggal Keluar:</label>
                  <input id="tanggalKeluar" name="tanggalKeluar" type="text" class="form-control tanggal" placeholder="pilih tanggal">
                  <div id="errorTanggalKeluar"></div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="masuk">Masuk : </label>
            <div class="input-group date">
              <span class="input-group-addon bg-default">
                <span class="icofont icofont-ui-clock"></span>
              </span>
              <input id="masuk11" type="text" name="masuk" class="form-control time24" placeholder="Masukan waktu">
              <div id="errorMasuk11"></div>
           </div>
          </div>

          <div class="col-md-6">
            <label for="keluar">Keluar : </label>
            <div class="input-group date">
              <input id="keluar11" type="text" name="keluar" class="form-control time24" placeholder="Masukan waktu">
              <span class="input-group-addon bg-default">
                <span class="icofont icofont-ui-clock"></span>
              </span>
              <div id="errorKeluar11"></div>
           </div>
          </div>
          <input id="status1" type="hidden" name="status1">
      </div>
  <?php echo form_close(); ?>
<?php echo modalSaveClose("Tambah","tambahExtra"); ?>

<!-- Detail Karyawan -->
<?php echo modalSaveOpen("Details"); ?>
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
      <label class="col-sm-3 col-form-label">Departemen</label>
      <div class="col-sm-8">
       <span id="departemen2"></span>
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
      <label class="col-sm-3 col-form-label">Telepon</label>
      <div class="col-sm-8">
       <span id="telepon2"></span>
      <hr>
      </div>
  </div>
<?php echo modalDetailClose(); ?>
<?php echo modalSaveOpen("Detail","lg"); ?>

  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Tanggal</label>
    <div class="col-sm-8">
      <span id="tanggal3"></span>
      <hr>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Kabag</label>
    <div class="col-sm-8">
      <span id="kabag3"></span>
      <hr>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Nama karyawan </label>
    <div class="col-sm-8">
      <span id="nama3"></span>
      <hr>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Jabatan </label>
    <div class="col-sm-8">
      <span id="jabatan3"></span>
      <hr>
    </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Jadwal </label>
      <div class="col-sm-8">
       <span id="shift3"></span>
      <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Masuk </label>
      <div class="col-sm-8">
       <span id="masuk3"></span>
      <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Break Out </label>
      <div class="col-sm-8">
       <span id="breakout3"></span>
      <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Break In </label>
      <div class="col-sm-8">
       <span id="breakin3"></span>
      <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Keluar</label>
      <div class="col-sm-8">
       <span id="keluar3"></span>
      <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Telat Masuk</label>
      <div class="col-sm-8">
       <span id="telat3"></span>
      <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Keterangan Masuk</label>
      <div class="col-sm-8">
       <span id="ketmasuk3"></span>
      <hr>
      </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Keterangan Keluar</label>
      <div class="col-sm-8">
       <span id="ketkeluar3"></span>
      <hr>
      </div>
  </div>
<?php echo modalDetailClose(); ?>

<?php echo modalSaveOpen("Detail11","lg"); ?>

  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Tanggal</label>
    <div class="col-sm-8">
      <span id="tanggal4"></span>
      <hr>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Kabag</label>
    <div class="col-sm-8">
      <span id="kabag4"></span>
      <hr>
    </div>
  </div>
  <div class="form-group row">
      <label class="col-sm-3 col-form-label">Nama karyawan </label>
      <div class="col-sm-8">
       <span id="nama4"></span>
      <hr>
      </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Jabatan </label>
    <div class="col-sm-8">
     <span id="jabatan4"></span>
    <hr>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Masuk </label>
    <div class="col-sm-8">
     <span id="masuk4"></span>
    <hr>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Keluar</label>
    <div class="col-sm-8">
      <span id="keluar4"></span>
      <hr>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Keterangan Masuk</label>
    <div class="col-sm-8">
      <span id="ketmasuk4"></span>
      <hr>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Keterangan Keluar</label>
    <div class="col-sm-8">
      <span id="ketkeluar4"></span>
      <hr>
    </div>
  </div>
<?php echo modalDetailClose(); ?>

<?php echo modalSaveOpen("modalKordinatMap","lg"); ?>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs md-tabs" role="tablist" style="margin-top: -15px;">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#home3" role="tab" aria-expanded="true">Check In</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#profile3" role="tab" aria-expanded="false">Break Out</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#messages3" role="tab" aria-expanded="false">Break In</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#settings3" role="tab" aria-expanded="false">Check Out</a>
            <div class="slide"></div>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content card-block">
        <div class="tab-pane active" id="home3" role="tabpanel" aria-expanded="true" style="margin:-18px -20px;">
            <div id="googleMapCheckIn" style="width:100%;height:550px;"></div>
        </div>
        <div class="tab-pane" id="profile3" role="tabpanel" aria-expanded="false" style="margin:-18px -20px;">
            <div id="googleMapBreakOut" style="width:100%;height:550px;"></div>
        </div>
        <div class="tab-pane" id="messages3" role="tabpanel" aria-expanded="false" style="margin:-18px -20px;">
            <div id="googleMapBreakIn" style="width:100%;height:550px;"></div>
        </div>
        <div class="tab-pane" id="settings3" role="tabpanel" aria-expanded="false" style="margin:-18px -20px;">
            <div id="googleMapCheckOut" style="width:100%;height:550px;"></div>
        </div>
    </div>

<?php echo modalDetailClose(); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHQs_-toMkAV9741QshOQ_5RAsfKBCfSY"></script>
<?php assets_script_custom_aktivitas("logabsensi.js"); ?>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHQs_-toMkAV9741QshOQ_5RAsfKBCfSY&callback=myMap"></script> -->
