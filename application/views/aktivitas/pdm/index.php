<div class="col-md-12">
  <!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Promosi Demosi Mutasi Karyawan</h5>
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
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Nama Karyawan</th>
                            <th>Cabang</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Grup</th>
                            <th>Golongan</th>
                            <th>Keterangan</th>
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
          <label for="judul">Promosi / Demosi / Mutasi:</label>
          <select class="form-control" name="judul" id="judul">
              <option value="">-Pilih PDM-</option>
              <option value="Promosi">Promosi</option>
              <option value="Demosi">Demosi</option>
              <option value="Mutasi">Mutasi</option>
          </select>
          <div id="errorJudul"></div>
        </div>

            <div class="col-md-6">
              <label for="tanggal" class="block">Tanggal :</label>
              <input id="tanggal" class="form-control" type="text" placeholder="Select Your Date" name="tanggal" disabled="disabled">
              <input type="hidden" name="idPromosi" id="idPromosi">
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
                        <div class="col-md-8">
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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_karyawan" class="block">Kode Karyawan :</label>
                                <input id="kode_karyawan" name="kode_karyawan" type="text" disabled class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="cabang" class="block">Cabang Sebelumnya:</label>
                                <input id="cabang" name="cabang" type="text" disabled class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="departemen" class="block">Departemen Sebelumnya:</label>
                                <input id="departemen" name="departemen" type="text" disabled class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="jabatan" class="block">Jabatan Sebelumnya:</label>
                                <input id="jabatan" name="jabatan" type="text" disabled class="form-control">
                            </div>
                             <div class="form-group">
                                <label for="golongan" class="block">Golongan Sebelumnya:</label>
                                <input id="golongan" name="golongan" type="text" disabled class="form-control">
                            </div>
                          <!--   <div class="form-group">
                                <label for="shift" class="block">Shift Sebelumnya:</label>
                                <input id="shift" name="shift" type="text" disabled class="form-control">
                            </div> -->
                            <div class="form-group">
                                <label for="grup" class="block">Grup Sebelumnya:</label>
                                <input id="grup" name="grup" type="text" disabled class="form-control">
                            </div>

                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_karyawan1" class="block">Kode Karyawan :</label>
                                <input id="kode_karyawan1" name="kode_karyawan1" type="text" disabled class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="cabang1" class="block">Cabang Sekarang:</label>
                                <select id="cabang11" name="cabang1" class="form-control select">
                                    <?php foreach ($cabang as $value): ?>
                                     <option value="<?php echo $value['id_cabang']; ?>"><?php echo $value['cabang']; ?></option>
                                     <?php endforeach; ?>
                                </select>
                                <div id="errorCabang"></div>
                            </div>
                            <div class="form-group">
                                <label for="departemen1" class="block">Departemen Sekarang:</label>
                                <select id="departemen11" name="departemen1" class="form-control select">
                                    <?php foreach ($departemen as $value): ?>
                                     <option value="<?php echo $value['id_departemen']; ?>"><?php echo $value['departemen']; ?></option>
                                     <?php endforeach; ?>
                                </select>
                                <div id="errorDepartemen"></div>
                            </div>
                            <div class="form-group">
                                <label for="jabatan1" class="block">Jabatan Sekarang:</label>
                                <select id="jabatan11" name="jabatan1" class="form-control select">
                                    <?php foreach ($jabatan as $value): ?>
                                     <option value="<?php echo $value['id_jabatan']; ?>"><?php echo $value['jabatan']; ?></option>
                                     <?php endforeach; ?>
                                </select>
                                <div id="errorJabatan"></div>
                            </div>
                             <div class="form-group">
                                <label for="golongan1" class="block">Golongan Sekarang:</label>
                                <select  id="golongan11" name="golongan1" class="form-control select">
                                    <?php foreach ($golongan as $value): ?>
                                     <option value="<?php echo $value['id_golongan']; ?>"><?php echo $value['golongan']; ?></option>
                                     <?php endforeach; ?>
                                </select>
                                <div id="errorGolongan"></div>
                            </div>
                          
                            <div class="form-group">
                                <label for="grup1" class="block">Grup Sekarang:</label>
                                <select id="grup11" name="grup1" class="form-control select">
                                    <?php foreach ($grup as $value): ?>
                                     <option value="<?php echo $value['id_grup']; ?>"><?php echo $value['grup']; ?></option>
                                     <?php endforeach; ?>
                                </select>
                                <div id="errorGrup"></div>
                            </div>
                    </div>
                </div>
                    <hr>
                </div>
               <!--  <div id="errorDetailKaryawan"></div> -->
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
<?php assets_script_custom_aktivitas("pdm.js"); ?>
