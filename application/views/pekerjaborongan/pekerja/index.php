<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Pekerja</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblPekerja" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>No Induk</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                           <!--  <th>Item</th> -->
                            <th>No Telepon</th>
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
            <label class="col-md-4 col-form-label">No ID</label>
                <div class="col-md-12">
                    <input type="text" class="form-control" id="no_id" name="no_id" placeholder="Nomor Induk Karyawan">
                <span id="errorNoId"></span>
                </div>
            <label class="col-md-4 col-form-label">Nama</label>
                <div class="col-md-12">
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama Lengkap">
                <span id="errorNama"></span>
                </div>
  
            <label class="col-md-4 col-form-label">Alamat</label>
                <div class="col-md-12">
                    <textarea id="alamat" name="alamat" class="form-control" rows="3" style="resize: vertical;"></textarea>
                <span id="errorAlamat"></span>
                </div>
           
                <label class="col-md-4 col-form-label">Tempat Lahir</label>
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Masukan Tempat Lahir">
                    <span id="errorTempatLahir"></span>
                    </div>
               
                <label class="col-md-4 col-form-label">Tanggal Lahir</label>
                    <div class="col-md-12">
                        <input type="text" class="form-control tanggal" name="tgl_lahir" id="tgl_lahir" placeholder="Masukan Tanggal Lahir">
                    <span id="errorTanggalLahir"></span>
                    </div>

                <label class="col-md-4 col-form-label"> Telepon / No.Hp</label>
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="telepon" id="telepon" placeholder="Masukan Telepon/No.Hp">
                    <span id="errorTelepon"></span>
                    </div>
               </div>
            </div>     
         <div class="form-group">
            <div class="row">
                <label class="col-md-3 col-form-label">Jenis kelamin</label>
                    <div class="col-md-8">
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option value="">--Pilih Jenis Kelamin--</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                    <span id="errorJenisKelamin"></span>
           
     
                <label class="col-md-3 col-form-label"> Tanggal Masuk Kerja :</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control tanggal" name="tgl_masuk_kerja" id="tgl_masuk_kerja" placeholder="Tanggal Masuk Kerja">
                        <span id="errorTglMasukKerja"></span>
                    </div>
      
                <label class="col-sm-3 col-form-label"> No. Rekening :</label>
                    <div class="col-sm-8">
                        <input type="number" id="no_rekening" min="0" name="no_rekening" class="form-control">
                    </div>
       
            
            <label class="col-sm-3">Bank :</label>
            <div class="col-sm-8">
            <select id="bank" name="bank" class="form-control bank">
                <option value="">-- Pilih Bank --</option>
                <option value="">Tidak Ada</option>
                <?php foreach ($bank as $val): ?>
                    <option value="<?php echo $val['id_bank']; ?>"><?php echo $val['bank']; ?></option>
                <?php endforeach ?>
            </select>
            </div>

            <label class="col-md-3 col-form-label">Periode Gaji</label>
                    <div class="col-md-8">
                        <select name="periodeGaji" id="periodeGaji" class="form-control">
                            <option value="">--Pilih Periode Gaji--</option>
                            <option value="Harian">Harian</option>
                            <option value="1-Mingguan">1 Mingguan</option>
                        </select>
                    </div>
        
           
                <label class="col-sm-3 col-form-label">Atas Nama Bank :</label>
                    <div class="col-sm-8">
                    <input type="text" id="atas_nama" name="atas_nama" class="form-control">
                        <div class="checkbox-fade fade-in-success">
                            <label>
                                <input type="checkbox" id="sama_nama" value="0">
                                    <span class="cr">
                                        <i class="cr-icon icofont icofont-ui-check txt-success"></i>
                                    </span> <span>Sama dengan Nama</span>
                            </label>
                        </div>
                    </div>
                            <label class="col-sm-3 col-form-label">Upload Photo Karyawan</label>
                            <div class="col-sm-8">
                                <?php $defaultPhoto = base_url("/")."assets/images/default/no_user.png"; ?>
                                <div class="card rounded-card user-card" style="height: 180px; width: 180px;">
                                    <div class="card-block">
                                        <div class="img-hover">
                                            <img class="img-fluid img-circle" id="img_photo" src="<?php echo $defaultPhoto;?>" alt="karyawan-img" style="height: 125px;">
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

                                            <input name="photo_pekerja" id="photo_pekerja" type="file" style="display: none;">
                                            <input type="hidden" name="is_delete_photo" id="is_delete_photo" value="0">

                                            <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="popUpPhotoOut" data-title="Photo Karyawan" data-footer="">
                                                <img src="<?php echo $defaultPhoto;?>" id="popUpPhotoIn" class="img-fluid" alt="">
                                            </a>
                                        </div>
                                        <div class="user-content"><h4 class="">Photo Karyawan</h4></div>
                                    </div>
                                </div>
                            </div>
                     </div>
            </div>
                 
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>

<?php echo modalSaveOpen("modalQrCode","sm","black","Cetak Kartu Karyawan"); ?>
        <div id="inputMessage"></div>
        <div class="card user-card" id="viewPrintCard">
            <?php $cardBackground = base_url("/")."assets/images/default/sinar_surya.png"; ?>
            <div class="card-header-img" style="background-image: url('<?php echo $cardBackground; ?>'); background-size: 100%; height: 420px; background-repeat: no-repeat;">

                <img style="margin-top: 150px; width: 55%; height: 36%;" class="img-fluid img-circle" id="fotoKaryawan" src="<?php echo base_url("/");?>assets/images/user-card/card/card-img1.jpg" alt="card-img">

                <div id="nameJabatan" style="margin-top: 12px; color: white;">
                    <center>
                        <b><u id="namaKaryawan">Dewi Anggaraini</u></b><br>
                    </center>
                </div>

                
            </div>
            
        </div>
    <br>
    <div class="card-header-img"  id="canvasPrint">
        <!-- <canvas width="500" height="200"></canvas> -->
        <div id="printPreview" style="display: none;">
            <h4>Print Preview</h4><br>
            <img width="40%" id="screenQrCode">
        </div>
    </div>
    <?php //echo modalSaveClosePrint("<i class='fa fa-print'></i>print","btnPrintQrCode","primary"); ?>
    <?php 
        $btnCustom = '
                        <button type="button" id="btnGenerate" class="btn btn-warning btn-sm"><i class="fa fa-cog"></i>Generate</button>
                        <button type="button" class="btn btn-info btn-sm" id="btnPrintQrCodeDownload" style="display:none;"><i class="fa fa-download"></i>Unduh</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btnPrintQrCode"><i class="fa fa-print"></i>print</button>
                        <button type="button" class="btn btn-default btn-sm" id="modalButtonClose" data-dismiss="modal">Close</button>
                    ';
        echo modalCloseButtonCustom($btnCustom); 
    ?>

<?php assets_script("download2.js"); ?>
<?php assets_script_custom("pekerjaborongan/pekerja.js"); ?>