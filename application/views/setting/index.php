<div class="col-md-12">
	<!-- Zero config.table start -->
  <!-- Nav tabs -->
    <ul class="nav nav-tabs md-tabs " role="tablist">

        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#General"  role="tab" aria-expanded="true" id="contentGeneral">General Settings</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#THR"  role="tab" aria-expanded="false" id="contentTHR">THR Settings</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#System"  role="tab" aria-expanded="false" id="contentSystem">System Settings</a>
            <div class="slide"></div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#bpjs"  role="tab" aria-expanded="false" id="contentBpjs">Settings BPJS</a>
            <div class="slide"></div>
        </li>
    </ul>



    <!-- Tab panes -->
  <div class="tab-content card-block">
    <div class="tab-pane active" id="General" role="tabpanel" aria-expanded="true">
      <div class="card">
        <div class="card-header">
            <h5>General Settings</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div id="form" >
              <?php echo form_open("",array("id" => "formData")); ?>
               <!--  <table id="tblSetting" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;"> -->
                  <div class="row">
                    <div class="col-md-6">

                      <div class="form-group">
                            <label for="namaPerusahaan" class="block">Nama Perusahaan</label>
                            <input id="namaPerusahaan" name="namaPerusahaan" type="text" class="form-control">
                            <div id="errornamaPerusahaan"></div>
                      </div>

                      <div class="form-group">
                            <label for="Alamat" class="block">Alamat </label>
                            <input type="text" name="alamat" class="form-control" id="Alamat">
                            <div id="errorAlamat"></div>
                      </div>

                      <div class="form-group">
                            <label for="Provinsi" class="block">Provinsi :</label>
                            <select class="form-control" name="provinsi" id="provinsi">
                              <?php foreach ($provinsi as $value): ?>
                                  <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div id="errorProvinsi"></div>
                      </div>

                      <div class="form-group">
                            <label for="Kota/Kabupaten" class="block">Kota/Kabupaten :</label>
                            <select class="form-control" name="kabupaten" id="kabupaten">
                            </select>
                            <div id="errorKabupaten"></div>
                      </div>

                      <div class="form-group">
                            <label for="KodePos" class="block">Kode Pos </label>
                            <input type="number" name="kodePos" class="form-control" id="KodePos">
                            <div id="errorKodePos"></div>
                      </div>

                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                            <label for="noFax" class="block">No Fax </label>
                            <input type="text" name="noFax" class="form-control" id="noFax">
                            <div id="errornoFax"></div>
                      </div>

                      <div class="form-group">
                            <label for="emailPerusahaan" class="block">Email Perusahaan </label>
                            <input type="text" name="emailPerusahaan" class="form-control" id="emailPerusahaan">
                            <div id="erroremailPerusahaan"></div>
                      </div>

                      <div class="form-group">
                            <label for="Website" class="block">Website </label>
                            <input type="text" name="website" class="form-control" id="Website">
                            <div id="errorWebsite"></div>
                      </div>

                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-6">
                            <label class="label-form">Upload Photo Logo</label>
                            <div class="col-sm-8">
                              <?php $defaultPhoto = base_url("/")."assets/images/default/no_file_.png"; ?>
                              <div class="card rounded-card user-card" style="height: 160px; width: 160px;">
                                <div class="card-block">
                                  <div class="img-hover">
                                    <img class="img-fluid img-square" id="img_photo" src="<?php echo $defaultPhoto;?>" alt="logo-img" style="height: 125px;">
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

                                    <input name="photo_setting" id="photo_karyawan" type="file" style="display: none;">
                                    <input type="hidden" name="is_delete_photo" id="is_delete_photo" value="0">

                                    <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="popUpPhotoLogoOut" data-title="Photo Setting" data-footer="">
                                      <img src="<?php echo $defaultPhoto;?>" id="popUpPhotoLogoIn" class="img-fluid" alt="">
                                    </a>
                                  </div>
                                  <!-- <div class="user-content"><h4 class="">Photo Pengumuman</h4></div> -->
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <label class="label-form">Upload QR Code background</label>
                            <div class="col-sm-8">
                              <?php $defaultPhoto = base_url("/")."assets/images/default/no_file_.png"; ?>
                              <div class="card rounded-card user-card" style="height: 160px; width: 160px;">
                                <div class="card-block">
                                  <div class="img-hover">
                                    <img class="img-fluid img-square" id="img_photo1" src="<?php echo $defaultPhoto;?>" alt="QRcode-img" style="height: 125px;">
                                    <div class="img-overlay">
                                      <span>
                                        <a href="javascript:void(0);" title="Zoom Photo" id="btnZoomImg1" class="btn btn-sm btn-success btn-outline-success">
                                          <i class="fa fa-search-plus"></i>
                                        </a>
                                        <a href="javascript:void(0);" title="Pilih Upload photo" class="btn btn-sm btn-primary btn-outline-primary" id="ganti_photo1" data-popup="lightbox">
                                          <i class="fa fa-upload"></i>
                                        </a>
                                        <a href="javascript:void(0);" title="Hapus photo" id="hapus_photo1" class="btn btn-sm btn-danger btn-outline-danger">
                                          <i class="fa fa-trash-o"></i>
                                        </a>
                                      </span>
                                    </div>

                                    <input name="photo_qrcode" id="photo_qrcode" type="file" style="display: none;">
                                    <input type="hidden" name="is_delete_photo1" id="is_delete_photo1" value="0">

                                    <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpPhotoOut" data-title="Photo qrcode" data-footer="">
                                        <img src="<?php echo $defaultPhoto;?>" id="detailPopUpPhotoIn" class="img-fluid" alt="">
                                    </a>
                                  </div>
                                  <!-- <div class="user-content"><h4 class="">Photo Pengumuman</h4></div> -->
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                              <label for="noTelp" class="block">No Telp </label>
                              <input type="text" name="noTelp" class="form-control" id="noTelp">
                              <div id="errornoTelp"></div>
                          </div>
                    </div>
                    <!-- <div class="col-md-6">
                      <div class="form-group">
                        <label for="jatahCuti">Jatah Cuti</label>
                        <input type="number" name="jatahCuti" id="jatahCuti" class="form-control">
                      </div>
                    </div> -->

                    <div class="col-md-12" style="display:none;">
                      <div class="form-group">
                        <label for="bidangUsaha" class="block">Bidang Usaha(Keterangan usaha) :</label>
                        <div class="card">
                          <div class="card-block">
                              <textarea id="editor9" name="bidangUsaha" style="resize: vertical;"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-square btn-sucess btn-success" id="Save">Save Setting</button>
            </div>
        </div>
    </div>
    </div>

    <div class="tab-pane" id="System" role="tabpanel" aria-expanded="true">
       <div class="card">
              <div class="card-header">
                  <h5>System Settings</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>

               <div class="card-block">
                <div id="form" >
                  <?php echo form_open("",array("id" => "formData")); ?>
                   <!--  <table id="tblSetting" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;"> -->
                     <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="budgetPenggajian">Budget Gaji Perusahaan : </label>
                              <input type="number" name="budgetPenggajian" id="budgetPenggajian" class="form-control">
                              <span id="mata_uang_penggajian"></span>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="jatahCuti">Jatah Cuti :</label>
                                <input type="number" name="jatahCuti" id="jatahCuti" class="form-control">
                              </div>
                                </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="reward1">Reward Top 1 : </label>
                              <input type="number" name="reward1" id="reward1" class="form-control">
                              <span id="mata_uang_reward1"></span>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="punishment1">Punishment Top 1 : </label>
                              <input type="number" name="punishment1" id="punishment1" class="form-control">
                              <span id="mata_uang_punishment1"></span>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="reward2">Reward Top 2 : </label>
                              <input type="number" name="reward2" id="reward2" class="form-control">
                              <span id="mata_uang_reward2"></span>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="punishment2">Punishment Top 2 : </label>
                              <input type="number" name="punishment2" id="punishment2" class="form-control">
                              <span id="mata_uang_punishment2"></span>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="reward3">Reward Top 3 : </label>
                              <input type="number" name="reward3" id="reward3" class="form-control">
                              <span id="mata_uang_reward3"></span>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="punishment3">Punishment Top 3 : </label>
                              <input type="number" name="punishment3" id="punishment3" class="form-control">
                              <span id="mata_uang_punishment3"></span>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <label for="tipeHariPerBulan">Tipe Hari kerja Per Bulan : </label>
                            <select class="form-control" name="tipeHariPerBulan" id="opsi_hari">
                              <option value="1">Manual</option>
                              <option value="0">Dinamis</option>
                            </select>
                          </div>

                          <div class="col-md-6" id="col_kerja">
                            <div class="form-group">
                              <label for="totalkerja">Hari Kerja : </label>
                              <input type="number" name="totalkerja" id="totalkerja" class="form-control">
                              <!-- <span id="mata_uang_punishment3"></span> -->
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_cuti">Opsi Cuti : </label>
                              <select class="form-control" name="opsi_cuti" id="opsi_cuti">
                                <option value="0">Golongan</option>
                                <option value="1">Manual</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_golongan_cuti" class="hideopsicuti">Opsi gaji golongan cuti :</label>
                              <select class="form-control hideopsicuti" name="opsi_golongan_cuti" id="opsi_golongan_cuti">
                                <option value="0.5x">1/2 Gaji</option>
                                <option value="1x">1x Gaji</option>
                                <option value="2x">2x Gaji</option>
                                <option value="3x">3x Gaji</option>
                              </select>
                              <label for="cuti" class="hideopsicuti1">Cuti : </label>
                              <input type="number" name="cuti" id="cuti" class="form-control hideopsicuti1" placeholder="gaji cuti">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_izin">Opsi Izin : </label>
                              <select class="form-control" name="opsi_izin" id="opsi_izin">
                                <option value="0">Golongan</option>
                                <option value="1">Manual</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_golongan_izin" class="hideopsiizin">Opsi gaji golongan izin :</label>
                              <select class="form-control hideopsiizin" name="opsi_golongan_izin" id="opsi_golongan_izin">
                                <option value="0.5x">1/2 Gaji</option>
                                <option value="1x">1x Gaji</option>
                                <option value="2x">2x Gaji</option>
                                <option value="3x">3x Gaji</option>
                              </select>
                              <label for="izin" class="hideopsiizin1">Izin : </label>
                              <input type="number" name="izin" id="izin" class="form-control hideopsiizin1" placeholder="gaji izin">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_sakit">Opsi Sakit : </label>
                              <select class="form-control" name="opsi_sakit" id="opsi_sakit">
                                <option value="0">Golongan</option>
                                <option value="1">Manual</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_golongan_sakit" class="hideopsisakit">Opsi gaji golongan sakit :</label>
                              <select class="form-control hideopsisakit" name="opsi_golongan_sakit" id="opsi_golongan_sakit">
                                <option value="0.5x">1/2 Gaji</option>
                                <option value="1x">1x Gaji</option>
                                <option value="2x">2x Gaji</option>
                                <option value="3x">3x Gaji</option>
                              </select>
                              <label for="sakit" class="hideopsisakit1">Sakit : </label>
                              <input type="number" name="sakit" id="sakit" class="form-control hideopsisakit1" placeholder="gaji sakit">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <label for="opsi_dinas">Opsi Dinas : </label>
                            <select class="form-control" name="opsi_dinas" id="opsi_dinas">
                              <option value="0">Golongan</option>
                              <option value="1">Manual</option>
                            </select>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_golongan_dinas" class="hideopsidinas">Opsi gaji golongan dinas :</label>
                              <select class="form-control hideopsidinas" name="opsi_golongan_dinas" id="opsi_golongan_dinas">
                                <option value="0.5x">1/2 Gaji</option>
                                <option value="1x">1x Gaji</option>
                                <option value="2x">2x Gaji</option>
                                <option value="3x">3x Gaji</option>
                              </select>
                              <label for="dinas" class="hideopsidinas1">Dinas : </label>
                              <input type="number" name="dinas" id="dinas" class="form-control hideopsidinas1" placeholder="gaji dinas">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_dirumahkan">Opsi dirumahkan : </label>
                              <select class="form-control" name="opsi_dirumahkan" id="opsi_dirumahkan">
                                <option value="0">Golongan</option>
                                <option value="1">Manual</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_golongan_dirumahkan" class="hideopsidirumahkan">Opsi gaji golongan dirumahkan :</label>
                              <select class="form-control hideopsidirumahkan" name="opsi_golongan_dirumahkan" id="opsi_golongan_dirumahkan">
                                <option value="0.5x">1/2 Gaji</option>
                                <option value="1x">1x Gaji</option>
                                <option value="2x">2x Gaji</option>
                                <option value="3x">3x Gaji</option>
                              </select>
                              <label for="dirumahkan" class="hideopsidirumahkan1">Dirumahkan : </label>
                              <input type="number" name="dirumahkan" id="dirumahkan" class="form-control hideopsidirumahkan1" placeholder="gaji dirumahkan">
                            </div>
                          </div>
                          <!-- Hari Minggu -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_minggu">Opsi Gaji Minggu : </label>
                              <select class="form-control" name="opsi_minggu" id="opsi_minggu">
                                <option value="0">Golongan</option>
                                <option value="1">Manual</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_golongan_minggu" class="hideopsiminggu">Opsi gaji golongan minggu :</label>
                              <select class="form-control hideopsiminggu" name="opsi_golongan_minggu" id="opsi_golongan_minggu">
                                <option value="0.5x">1/2 Gaji</option>
                                <option value="1x">1x Gaji</option>
                                <option value="2x">2x Gaji</option>
                                <option value="3x">3x Gaji</option>
                              </select>
                              <label for="hari_minggu" class="hideopsiminggu1">Gaji Minggu : </label>
                              <input type="number" name="hari_minggu" id="hari_minggu" class="form-control hideopsiminggu1" placeholder="gaji hari_minggu">
                            </div>
                          </div>

                          <!-- Hari Libur -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_libur">Opsi Gaji Libur : </label>
                              <select class="form-control" name="opsi_libur" id="opsi_libur">
                                <option value="0">Golongan</option>
                                <option value="1">Manual</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="opsi_golongan_libur" class="hideopsilibur">Opsi gaji golongan libur :</label>
                              <select class="form-control hideopsilibur" name="opsi_golongan_libur" id="opsi_golongan_libur">
                                <option value="0.5x">1/2 Gaji</option>
                                <option value="1x">1x Gaji</option>
                                <option value="2x">2x Gaji</option>
                                <option value="3x">3x Gaji</option>
                              </select>
                              <label for="hari_libur" class="hideopsilibur1">Gaji Libur : </label>
                              <input type="number" name="hari_libur" id="hari_libur" class="form-control hideopsilibur1" placeholder="gaji hari_libur">
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-6">
                                <label for="opsi_kerajinan">Opsi Kerajinan : </label>
                                <select class="form-control" name="opsi_kerajinan" id="opsi_kerajinan">
                                  <option value="1">Ya</option>
                                  <option value="0">Tidak</option>
                                </select>
                              </div>
                              <div class="col-md-6" id="showKerajinan">
                                <label for="nilaiKerajinan">Kerajinan : </label>
                                <input type="number" name="nilaiKerajinan" id="nilaiKerajinan" class="form-control">
                              </div>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <label for="periode_penilaian">Periode Penilaian : </label>
                            <select class="form-control" name="periode_penilaian" id="periode_penilaian">
                              <option value="1">1 Tahun</option>
                              <option value="2">6 Bulan</option>
                              <option value="3">4 Bulan</option>
                              <option value="4">3 Bulan</option>
                            </select>
                          </div>

                          <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-6">
                                  <label for="">Set Tanggal Gajian : </label>
                                  <ul style="list-style-type : decimal;">
                                    <li><input type="number" name="tanggal_penggajian1" class="form-control" id="tanggal_penggajian1" style="margin-top:5px;"></li>
                                    <li><input type="number" name="tanggal_penggajian2" class="form-control" id="tanggal_penggajian2" style="margin-top:5px;"></li>
                                    <li><input type="number" name="tanggal_penggajian3" class="form-control" id="tanggal_penggajian3" style="margin-top:5px;"></li>
                                    <li><input type="number" name="tanggal_penggajian4" class="form-control" id="tanggal_penggajian4" style="margin-top:5px;"></li>
                                  </ul>

                              </div>

                              <div class="col-md-6">
                                <!-- <div class="form-group"> -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <label>Opsi Telat : </label>
                                    <ol style="list-style-type : decimal;">
                                      <li><input type="number" name="menitTelat1" id="menitTelat1" class="form-control" style="margin-top:5px;"><br></li>
                                      <li><input type="number" name="menitTelat2" id="menitTelat2" class="form-control" style="margin-top:5px;"><br><span id="errortelat2"></span></li>
                                      <li><input type="number" name="menitTelat3" id="menitTelat3" class="form-control" style="margin-top:5px;"><br><span id="errortelat3"></span></li>
                                    </ol>

                                  </div>

                                  <div class="col-md-6">
                                    <label>Jumlah Potongan : </label>
                                    <ol style="list-style-type : decimal;">
                                      <li><input type="number" name="jumlahPotongan1" id="jumlahPotongan1" class="form-control" style="margin-top:5px;"><br></li>
                                      <li><input type="number" name="jumlahPotongan2" id="jumlahPotongan2" class="form-control" style="margin-top:5px;"><br></li>

                                    </ol>
                                  </div>

                                </div>
                                <!-- </div> -->
                              </div>

                              <div class="col-md-6 showBreak">
                                <label for="lembur1">Jam pertama</label>
                                <input type="number" name="lembur1" id="lembur1" class="form-control" style="margin-top:5px;" placeholder="menit">
                              </div>

                              <div class="col-md-6 showBreak">
                                <label for="lembur_break1">jumlah break jam pertama</label>
                                <input type="number" name="lembur_break1" id="lembur_break1" class="form-control" style="margin-top:5px;" placeholder="menit">
                              </div>

                              <div class="col-md-6 showBreak">
                                <label for="lembur2">Jam kedua</label>
                                <input type="number" name="lembur2" id="lembur2" class="form-control" style="margin-top:5px;" placeholder="menit">
                              </div>

                              <div class="col-md-6 showBreak">
                                <label for="lembur_break2">jumlah break jam kedua</label>
                                <input type="number" name="lembur_break2" id="lembur_break2" class="form-control" style="margin-top:5px;" placeholder="menit">
                              </div>

                              <div class="col-md-6 showBreak">
                                <label for="lembur3">jam ketiga</label>
                                <input type="number" name="lembur3" id="lembur3" class="form-control" style="margin-top:5px;" placeholder="menit">
                              </div>

                              <div class="col-md-6 showBreak">
                                <label for="lembur_break3">jumlah break jam ketiga</label>
                                <input type="number" name="lembur_break3" id="lembur_break3" class="form-control" style="margin-top:5px;" placeholder="menit">
                              </div>
                              <div class="col-md-12">
                                &nbsp;
                              </div>
                              <div class="col-md-3">
                                <div class="border-checkbox-section">
                                  <div class="border-checkbox-group border-checkbox-group-info">
                                    <input class="border-checkbox" type="checkbox" id="checkbox3" value="1" name="opsi_lembur">
                                    <label class="border-checkbox-label" for="checkbox3" id="opsi_overtime">opsi overtime (istirahat)</label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-3">
                                <div class="border-checkbox-section">
                                  <div class="border-checkbox-group border-checkbox-group-info">
                                    <input class="border-checkbox" type="checkbox" id="checkbox4" value="1" name="approval_lembur">
                                    <label class="border-checkbox-label" for="checkbox4" id="approval">aktifkan approval overtime</label>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>
                  <?php echo form_close(); ?>
                </div>
          </div>
          <br><br>
           <button type="button" class="btn btn-square btn-sucess btn-success" id="Save1">Save Setting</button>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="THR" role="tabpanel" aria-expanded="true">
          <div class="card">
              <div class="card-header">
                  <h5>THR Settings</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>
              <div class="card-block">
                  <div class="dt-responsive table-responsive">
                      <table id="tblTHR" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                          <thead>
                              <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Tanggal</th>
                                    <th>Agama</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
          </div>
        </div>
        </div>

    <!-- bpjs setting -->
    <div class="tab-pane" id="bpjs" role="tabpanel" aria-expanded="true">
       <div class="card">
              <div class="card-header">
                  <h5>Settings BPJS</h5>
                  <div class="card-header-right">
                      <i class="icofont icofont-rounded-down"></i>
                      <!-- <i class="icofont icofont-refresh"></i> -->
                      <!-- <i class="icofont icofont-close-circled"></i> -->
                  </div>
              </div>

               <div class="card-block">
                <div id="form-bpjs">
                  <?php echo form_open("",array("id" => "formDataBpjs")); ?>
                     <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jht_perusahaan">Jaminan Hari TUA (JHT) Perusahaan</label>
                        <input type="number" name="jht_perusahaan" class="form-control" id="jht_perusahaan" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jht_karyawan">Jaminan Hari TUA (JHT) Karyawan</label>
                        <input type="number" name="jht_karyawan" class="form-control" id="jht_karyawan" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jkk_sangat_rendah">Jaminan kecelakaan Kerja (JKK) Resiko sangat rendah</label>
                        <input type="number" name="jkk_sangat_rendah" class="form-control" id="jkk_sangat_rendah" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jkk_rendah">Jaminan kecelakaan Kerja (JKK) Resiko rendah</label>
                        <input type="number" name="jkk_rendah" class="form-control" id="jkk_rendah" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jkk_sedang">Jaminan kecelakaan Kerja (JKK) Resiko sedang</label>
                        <input type="number" name="jkk_sedang" class="form-control" id="jkk_sedang" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jkk_tinggi">Jaminan kecelakaan Kerja (JKK) Resiko tinggi</label>
                        <input type="number" name="jkk_tinggi" class="form-control" id="jkk_tinggi" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jkk_sangat_tinggi">Jaminan kecelakaan Kerja (JKK) Resiko sangat tinggi</label>
                        <input type="number" name="jkk_sangat_tinggi" class="form-control" id="jkk_sangat_tinggi" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jkm">Jaminan Kematian (JKM)</label>
                        <input type="number" name="jkm" class="form-control" id="jkm" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jp_perusahaan">Jaminan Pensiun (JP)</label>
                        <input type="number" name="jp_perusahaan" class="form-control" id="jp_perusahaan" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jp_karyawan">Jaminan Pensiun (JP)</label>
                        <input type="number" name="jp_karyawan" class="form-control" id="jp_karyawan" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jp_perusahaan_max">Maksimal Jaminan Pensiun (JP)</label>
                        <input type="number" name="jp_perusahaan_max" class="form-control" id="jp_perusahaan_max">
                        <span id="moneyJpPerusahaan"></span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jp_karyawan_max">Maksimal Jaminan Pensiun (JP)</label>
                        <input type="number" name="jp_karyawan_max" class="form-control" id="jp_karyawan_max">
                        <span id="moneyJpKaryawan"></span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="bpjs_perusahaan">BPJS kesehatan perusahaan</label>
                        <input type="number" name="bpjs_perusahaan" class="form-control" id="bpjs_perusahaan" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="bpjs_karyawan">BPJS kesehatan karyawan</label>
                        <input type="number" name="bpjs_karyawan" class="form-control" id="bpjs_karyawan" placeholder="%">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="bpjs_perusahaan_max">Maksimal BPJS kesehatan perusahaan</label>
                        <input type="number" name="bpjs_perusahaan_max" class="form-control" id="bpjs_perusahaan_max">
                        <span id="moneyBpjsPerusahaan"></span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="bpjs_karyawan_max">Maksimal BPJS kesehatan perusahaan</label>
                        <input type="number" name="bpjs_karyawan_max" class="form-control" id="bpjs_karyawan_max">
                        <span id="moneyBpjsKaryawan"></span>
                      </div>
                    </div>

                </div> <!--end row -->
                <?php echo form_close(); ?>
          </div>
          <br><br>
           <button type="button" class="btn btn-square btn-sucess btn-success" id="SaveBpjs">Save BPJS</button>
        </div>
      </div>
    </div>
    <!-- Zero config.table end -->
</div>


<?php echo form_close(); ?>


<?php echo modalSaveOpen("TambahTHR"); ?>
    <div id="inputMessage11"></div>
    <?php echo form_open("",array("id" => "formDataExtra")); ?>

      <div class="form-group">
            <label for="tanggal11" class="block">Tanggal :</label>
            <input id="tanggal11" class="form-control" type="text" placeholder="Select Your Date" name="tanggal11">
            <div id="errorTanggal11"></div>
      </div>

      <div class="form-group">
            <label class="block">Agama :</label>
            <select id="agama11" name="agama11" class="form-control">
                              <option value="">--- Pilih Agama ---</option>
                              <option value="Islam">Islam</option>
                              <option value="Protestan">Kristen Protestan</option>
                              <option value="Katholik">Kristen Katholik</option>
                              <option value="Hindu">Hindu</option>
                              <option value="Budha">Buddha</option>
                              <option value="Konghucu">Kong Hu Cu</option>
                          </select>
            <div id="errorAgama11"></div>
      </div>

  <?php echo form_close(); ?>
<?php echo modalSaveClose("TambahTHR","tambahTHR"); ?>


<?php assets_script_custom("setting.js"); ?>
