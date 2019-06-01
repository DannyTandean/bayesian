<style type="text/css">

    table.dataTable td.reorder {
      text-align: left;
      cursor: move; 
    }

</style>
<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card" id="dataTable">
        <div class="card-header">
            <h5>Data Table Approval Karyawan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblApproveOwnerKaryawan" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead class="thead-success">
                        <tr>
                            <th>No</th>
                            <th style="width: 20px;">Action</th>
                            <th>Status</th>	<!-- untuk status dan approve dan reject -->
                            <!-- <th>Info</th> -->
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Alamat</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->

    <!-- Detail Karyawan -->
    <div class="card" id="detailData" style="display: none;">
        <div class="card-header">
            <h5><i class="fa fa-search-plus"></i> Detail Data Approval Karyawan</h5>
            <div class="card-header-right">
                <i class="ti-close btn btn-danger btn-outline-danger close-form"></i>
            </div>
        </div>
        <form id="main" method="post" action="/" novalidate="">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">No ID</label>
                            <div class="col-sm-8">
                                <span id="detailNoId"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-8">
                                <span id="detailNama"></span>
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
                            <label class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-8">
                                <span id="detailAlamat"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-8">
                                <span id="detailTempatLahir"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-8">
                                <span id="detailTanggalLahir"></span>
                                <hr>
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Telepon / No.Hp</label>
                            <div class="col-sm-8">
                                <span id="detailTelepon"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Email</label>
                            <div class="col-sm-8">
                                <span id="detailEmail"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> No Telp/HP Wali</label>
                            <div class="col-sm-8">
                                <span id="detailNoWali"></span>
                                <hr>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jenis kelamin</label>
                            <div class="col-sm-8">
                                <span id="detailJenisKelamin"></span>
                                <hr>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status Pernikahan</label>
                            <div class="col-sm-8">
                                <span id="detailStatusPernikahan"></span>
                                <hr>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pendidikan Terakhir</label>
                            <div class="col-sm-8">
                                <span id="detailPendidikan"></span>
                                <hr>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kewarganegaraan</label>
                            <div class="col-sm-8">
                                <span id="detailKewarganegaraan"></span>
                                <hr>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Agama</label>
                            <div class="col-sm-8">
                                <span id="detailAgama"></span>
                                <hr>
                            </div>
                            
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Cabang</label>
                            <div class="col-sm-8">
                                <span id="detailCabang"></span>
                                <hr>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Departemen</label>
                            <div class="col-sm-8">
                                <span id="detailDepartemen"></span>
                                <hr>
                            </div>
                            
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jabatan</label>
                            <div class="col-sm-8">
                                <span id="detailJabatan"></span>
                                <hr>
                            </div>
                            
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Tunjangan Jabatan</label>
                            <div class="col-sm-8 form-radio">
                                <span id="detailTunjanganJabatan"></span>
                                <hr>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Group</label>
                            <div class="col-sm-8">
                                <span id="detailGroup"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Shift</label>
                            <div class="col-sm-8 form-radio">
                                <span id="detailShift"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Tanggal Masuk Kerja</label>
                            <div class="col-sm-8">
                                <span id="detailTglMasukKerja"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Status Kontrak</label>
                            <div class="col-sm-8 form-radio">
                                <span id="detailStatusKontrak"></span>
                                <hr>
                            </div>
                        </div>
                        <div id="detailDataKontrak" style="display: none;">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"> Tanggal Awal dan Akhir Kontrak</label>
                                <div class="col-sm-8">
                                    <span id="detailStartDate"></span><br>
                                    <label class="label label-md label-primary">Sampai</label><br>
                                    <span id="detailExpiredDate"></span>
                                    <hr>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"> file Kontrak</label>
                                <div class="col-sm-8">

                                    <?php $defaultFile = base_url("/")."assets/images/default/no_file_.png"; ?>
                                    <div class="row">
                                        <div class="col-md-6" id="fileKontrakMaster">
                                            <div class="card rounded-card user-card" style="height: 190px; width: 150px;">
                                                <div class="card-block">
                                                    <div class="img-hover">
                                                        <img class="img-fluid" id="detail_upload_kontrak" src="<?php echo $defaultFile;?>" alt="user-img" style="height: 100px;">
                                                        <div class="img-overlay">
                                                            <span>
                                                                <a href="javascript:void(0);" title="Zoom file" id="btnDetailZoomFile" class="btn btn-sm btn-success btn-outline-success">
                                                                    <i class="fa fa-search-plus"></i>
                                                                </a>
                                                                
                                                            </span>
                                                        </div>

                                                        <a href="<?php echo $defaultFile;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpFileOut" data-title="File kontrak" data-footer="">
                                                            <img src="<?php echo $defaultFile;?>" id="detailPopUpFileIn" class="img-fluid" alt="">
                                                        </a>
                                                    </div>
                                                    <div class="user-content">
                                                        <small><b class="">File kontrak master</b></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="fileKontrakApproval" style="display: none;">
                                            <div class="card rounded-card user-card" style="height: 190px; width: 150px;">
                                                <div class="card-block">
                                                    <div class="img-hover">
                                                        <img class="img-fluid" id="detail_upload_kontrak_tmp" src="<?php echo $defaultFile;?>" alt="user-img" style="height: 100px;">
                                                        <div class="img-overlay">
                                                            <span>
                                                                <a href="javascript:void(0);" title="Zoom file" id="btnDetailZoomFileTmp" class="btn btn-sm btn-success btn-outline-success">
                                                                    <i class="fa fa-search-plus"></i>
                                                                </a>
                                                                
                                                            </span>
                                                        </div>

                                                        <a href="<?php echo $defaultFile;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpFileOutTmp" data-title="File kontrak" data-footer="">
                                                            <img src="<?php echo $defaultFile;?>" id="detailPopUpFileInTmp" class="img-fluid" alt="">
                                                        </a>
                                                    </div>
                                                    <div class="user-content">
                                                        <small><b style="color:blue;">File kontrak Approval</b></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                            

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Nama Ibu Kandung</label>
                            <div class="col-sm-8">
                                <span id="detailIbuKandung"></span>
                                <hr>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Nama Suami / Istri</label>
                            <div class="col-sm-8">
                                <span id="detailSuamiIstri"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Jumlah Tanggungan</label>
                            <div class="col-sm-8">
                                <span id="detailJumlahTanggungan"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> NPWP</label>
                            <div class="col-sm-8">
                                <span id="detailNPWP"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Golongan</label>
                            <div class="col-sm-8">
                                <span id="detailGolongan"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Periode Gajian</label>
                            <div class="col-sm-8">
                                <span id="detailPeriodeGajian"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Nilai Gaji</label>
                            <div class="col-sm-8">
                                <span id="detailNilaiGaji"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> No. Rekening</label>
                            <div class="col-sm-8">
                                <span id="detailNoRekening"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bank</label>
                            <div class="col-sm-8">
                                <span id="detailBank"></span>
                                <hr>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Atas Nama</label>
                            <div class="col-sm-8">
                                <span id="detailAtasNama"></span>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Otoritas</label>
                            <div class="col-sm-8">
                                <span id="detailOtoritas"></span>
                                <hr>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"> Status Kerja</label>
                            <div class="col-sm-8 form-radio">
                                <span id="detailStatusKerja"></span>
                                <hr>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-footer">
                
                <button type="button" class="btn btn-outline-success m-b-0" id="btnApproval" onclick="">
                    <i class="fa fa-check-square"></i> Approval
                </button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-outline-danger m-b-0" id="btnReject" onclick="">
                    <i class="fa fa-ban"></i> Reject
                </button>

                <div class="form-group pull-right">
                    <button type="button" class="btn btn-danger m-b-0 close-form">
                        <i class="ti-close"></i> Tutup
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- End Detail Karyawan -->

</div>

<?php assets_script_custom("approvalowner/karyawan.js"); ?>