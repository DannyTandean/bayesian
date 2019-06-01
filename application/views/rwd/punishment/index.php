<style>
.modal {
  overflow-y:auto;
  }

  #custome {
    width:100%;
  }

  #custome td{
    width:33%;
    overflow: hidden;
    /* display: inline-block; */
    white-space: pre-line;
  }
  /* table#custome > tr > td {
    max-width:33%;
  } */
</style>
<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Punishment</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblPunishment" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tool</th>
                            <th>Tanggal</th>
                            <!-- <th>Kode Karyawan</th> -->
                            <th>Nama Karyawan</th>
                            <th>Departemen</th>
                            <th>Status</th>
                            <th>Jabatan</th>
                            <!-- <th>Judul</th> -->
                            <th>Nilai</th>
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

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="block">Tanggal :</label>
                    <input id="tanggal" name="tanggal" type="text" class="form-control" placeholder="tanggal">
                    <div id="errorTanggal"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="block">Nama Karyawan :</label>
                    <select id="nama_karyawan" name="nama_karyawan" class="form-control select2">
                    </select>
                    <div id="errorNamaKaryawan"></div>
                </div>
            </div>
        </div>

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
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="kode_karyawan" class="block">Kode :</label>
                                <input id="kode_karyawan" name="kode_karyawan" type="text" disabled class="form-control">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="no_induk" class="block">No Induk :</label>
                                <input id="no_induk" name="no_induk" type="text" disabled class="form-control">
                            </div>
                        </div>
                    </div>

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

        <!-- <div class="form-group">
            <label for="judul" class="block">Judul Punishment :</label>
            <input id="judul" name="judul" type="text" class="form-control">
            <div id="errorJudul"></div>
        </div> -->
        <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <label for="nilai" class="block">Nilai Punishment :</label>
              <input id="nilai" name="nilai" type="number" class="form-control" placeholder="nilai">
              <span id="mata_uang_nilai"></span>
              <div id="errorNilai"></div>
            </div>
            <div class="col-md-6">
              <label for="sp">Pilih SP (Surat Peringatan) :</label>
              <select class="form-control" name="surat_peringatan" id="sp">
                <option value="sp-1">SP 1</option>
                <option value="sp-2">SP 2</option>
                <option value="sp-3">SP 3</option>
              </select>
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

<!-- SP modal -->

<?php echo modalSaveOpen("modalSp","md"); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formDataSp")); ?>
        <div class="form-group">
          <div class="row">
            <div class="col-md-12">
              <label for="sp">Pilih SP (Surat Peringatan) :</label>
              <select class="form-control" name="surat_peringatan" id="sp1">
                <option value="sp-1">SP 1</option>
                <option value="sp-2">SP 2</option>
                <option value="sp-3">SP 3</option>
              </select>

              <label for="nosp">Nomor SP</label>
              <input type="text" name="nosp" class="form-control" id="nosp">

              <input type="hidden" name="idKaryawan">
              <input type="hidden" name="idPunishment">
            </div>
          </div>
        </div>

	<?php echo form_close(); ?>
<?php echo modalSaveClose("save","optionSP"); ?>

<!-- print space -->
<?php echo modalSaveOpen("Print","lg"); ?>
  <div class="card" id="asd">
  <div class="card-block">
    <table class="table invoice-table table-borderless" style="padding-left:0;">
        <tbody>
            <tr>
                <td rowspan="5"><img src="<?php echo $defaultPhoto; ?>" class="m-b-10" alt="" height="100" width="100" id="logo"></td>
                <td></td>
            </tr>
            <tr>
                <td id="namaPerusahaan">test</td>
            </tr>
            <tr>
                <td id="alamat">jln </td>
            </tr>
            <tr>
                <td>No Telpon : <span id="noTelp"></span> , Fax <span id="noFax"></span> </td>
            </tr>
            <tr>
              <td>Email : <a href="mailto:demo@gmail.com" target="_top" id="emailPerusahaan">demo@gmail.com</a>
              </td>
            </tr>
        </tbody>
    </table>

    <div class="row">
        <div class="col-sm-12">
          <p align="center" style="font-size:16px;"><b><u id="printSpTitle"></u></b></p>
          <p align="center">Nomor  : <span id="nomorsp"></span> </p>
          <br>
          <br>
          <p>Nama : <span id="karyawan1"></span></p>
          <p>Jabatan : <span id="jabatan1"></span></p>
          <p>Alamat : <span id="alamatKaryawan"></span></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <p>Surat ini dikeluarkan sehubungan dengan sikap indisipliner dan pelanggaran terhadap tata tertib perusahaan yang saudara / saudari lakukan. Saudara / saudari <span id="alasan"></span>
               Sebagai karyawan, saudara / saudari seharusnya mampu menaati tata tertib kerja yang berlaku di perusahaan, seperti yang tertera dalam Surat perjanjian Kerja (SPK) yang telah disepakati Sebelumnya yakni:
            </p>
            <p>1. Pemotongan gaji sebesar <span id="potonganGaji"></span> </p>
            <!-- <p>Dan apabila terugran SP-2 ini tidak direspon dengan baik maka kami akan mengeluarkan SP-3 yang berarti bersifat pemecatan.</p> -->
            <p>Demikian surat peringatan ini kami buat agar dapat ditaati sebagaimana mestinya. Diharapkan saudara / saudari yang bersangkutan dapat merubah sikap dan mampu menunjukkan sikap
               profesionalisme dalam bekerja.
            </p>
            <br>
            <table id="custome">
              <!-- <thead> -->

              <!-- </thead> -->
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <th><span class="float-right" style="padding-right:35%;"><?php echo date_ind("d M Y",date("Y-m-d")); ?></span></th>
                </tr>
                <tr>
                  <td><p align="center">Penerima SP</p></td>
                  <td></td>
                  <td><p align="center">Pembuat SP</p></td>
                </tr>
                <tr>
                  <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td><p align="center"><span id="penerima"></span><br> <span id="penerimaJbt"></span> </p></td>
                  <td></td>
                  <td><p align="center"><span><?php echo $this->user->username; ?></span><br> <span><?php echo $this->user->level; ?></span></p></td>
                </tr>
              </tbody>
            </table>
            <br>
            <br>
            <p>Powered By Prima HRD System 2017</p>
        </div>
    </div>

    </div>
  </div>
<?php echo modalSaveClose("Print","printing"); ?>

<?php assets_script_custom("rwd/punishment.js"); ?>
