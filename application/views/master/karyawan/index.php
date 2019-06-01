
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap4.min.css"> -->

<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card" id="dataTable">
        <div class="card-header">
            <h5>Data Table Karyawan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblMasterKaryawan" class="table table-striped table-bordered table-sm" style="width: 100%;">
                    <thead class="thead-primary">
                        <tr>
                            <th>No</th>
                            <th>Opsi</th>
                            <th>Photo</th>
                            <th>No Induk</th>
                            <th>Nama</th>
                            <th>Tempat Lahir</th>
                            <th>Tgl Lahir</th>
                            <th>Jenis kelamin</th>
                            <th>Telp</th>
                            <th>Jabatan</th>
                            <th>Alamat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->

    <!-- Form prosess -->
	<div class="card" id="formProses" style="display: none;">
	    <div class="card-header">
	        <h5 id="titleForm"><i class="fa fa-plus"></i> Basic Inputs Validation</h5>
	        <div class="card-header-right">
	            <i class="ti-close btn btn-danger btn-outline-danger close-form"></i>
	        </div>
	    </div>
		<?php echo form_open("",array("id" => "formData")); ?>
	    	<div class="card-block">
	    		<div class="row">
	    			<div class="col-md-6">
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">No ID</label>
			                <div class="col-sm-8">
			                    <input type="text" class="form-control" id="no_id" name="no_id" placeholder="Nomor Induk Karyawan">
			                    <span id="errorNoId"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Nama</label>
			                <div class="col-sm-8">
			                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama Lengkap">
			                    <span id="errorNama"></span>
			                </div>
			            </div>
			            <div class="form-group row">
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

					                        <input name="photo_karyawan" id="photo_karyawan" type="file" style="display: none;">
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
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Alamat</label>
			                <div class="col-sm-8">
					            <textarea id="alamat" name="alamat" class="form-control" rows="3" style="resize: vertical;"></textarea>
					            <span id="errorAlamat"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Tempat Lahir</label>
			                <div class="col-sm-8">
			                    <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Masukan Tempat Lahir">
			                    <span id="errorTempatLahir"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
			                <div class="col-sm-8">
			                    <input type="text" class="form-control tanggal" name="tgl_lahir" id="tgl_lahir" placeholder="Masukan Tanggal Lahir">
			                    <span id="errorTanggalLahir"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Telepon / No.Hp</label>
			                <div class="col-sm-8">
			                    <input type="text" class="form-control" name="telepon" id="telepon" placeholder="Masukan Telepon/No.Hp">
			                    <span id="errorTelepon"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Email</label>
			                <div class="col-sm-8">
			                    <input type="text" class="form-control" name="email" id="email" placeholder="Masukan Email">
			                    <span id="errorEmail"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	No Telp/HP Wali</label>
			                <div class="col-sm-8">
			                    <input type="text" class="form-control" name="no_wali" id="no_wali" placeholder="No Telp/HP Wali">
			                    <span id="errorNoWali"></span>
			                </div>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Jenis kelamin</label>
			                <div class="col-sm-8">
			                	<select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
	                                <option value="">--Pilih Jenis Kelamin--</option>
	                                <option value="laki-laki">Laki-laki</option>
	                                <option value="perempuan">Perempuan</option>
	                            </select>
	                        </div>
		                    <span id="errorJenisKelamin"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Status Pernikahan</label>
			                <div class="col-sm-8">
			                	<select name="status_pernikahan" id="status_pernikahan" class="form-control">
	                                <option value="">--Pilih Status Pernikahan--</option>
	                                <option value="Belum">Belum Menikah</option>
	                                <option value="Menikah">Menikah</option>
	                                <option value="Cerai-Hidup">Cerai Hidup</option>
	                                <option value="Cerai-Mati">Cerai Mati</option>
	                            </select>
	                        </div>
		                    <span id="errorStatusPernikahan"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Pendidikan Terakhir</label>
			                <div class="col-sm-8">
			                	<select id="pendidikan" name="pendidikan" class="form-control">
		                          	<option value="">--- Pilih Pendidikan Terakhir ---</option>
		                          	<option value="SD">SD</option>
		                          	<option value="SMP">SMP</option>
		                          	<option value="SMA Sederajat">SMA / Sederajat </option>
		                          	<option value="Diploma 1">Diploma 1 </option>
		                          	<option value="Diploma 3">Diploma 3 </option>
		                          	<option value="Sarjana S1">Sarjana S1 </option>
		                          	<option value="Sarjana S2">Sarjana S2 </option>
		                          	<option value="Sarjana S3">Sarjana S3 </option>
		                          	<option value="Tidak Sekolah">Tidak Sekolah</option>
		                      	</select>
	                        </div>
		                    <span id="errorPendidikan"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Kewarganegaraan</label>
			                <div class="col-sm-8">
			                	<select id="kewarganegaraan" name="kewarganegaraan" class="form-control">
                        			<option value="">--- Pilih Kewarganegaraan ---</option>
			                        <option value="Indonesia"> Indonesia </option>
			                        <option value="Asing">Asing</option>
			                    </select>
	                        </div>
		                    <span id="errorKewarganegaraan"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Agama</label>
			                <div class="col-sm-8">
			                	<select id="agama" name="agama" class="form-control">
			                        <option value="">--- Pilih Agama ---</option>
			                        <option value="Islam">Islam</option>
			                        <option value="Protestan">Kristen Protestan</option>
			                        <option value="Katholik">Kristen Katholik</option>
			                        <option value="Hindu">Hindu</option>
			                        <option value="Buddha">Buddha</option>
			                        <option value="Konghucu">Kong Hu Cu</option>
			                    </select>
	                        </div>
		                    <span id="errorAgama"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Cabang</label>
			                <div class="col-sm-8">
			                	<select id="cabang" name="cabang" class="form-control">
			                    </select>
	                        </div>
		                    <span id="errorCabang"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Departemen</label>
			                <div class="col-sm-8">
			                	<select id="departemen" name="departemen" class="form-control">
			                    </select>
	                        </div>
		                    <span id="errorDepartemen"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Jabatan</label>
			                <div class="col-sm-8">
			                	<select id="jabatan" name="jabatan" class="form-control">
			                    </select>
	                        </div>
		                    <span id="errorJabatan"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Tunjangan Jabatan</label>
			                <div class="col-sm-8 form-radio">
			                	<div class="radio radiofill radio-success radio-inline">
                                    <label>
                                        <input type="radio" id="tunjanganIya" name="tunjangan_jabatan" value="1">
                                        <i class="helper"></i>Iya
                                    </label>
                                </div>
                                <div class="radio radiofill radio-danger radio-inline">
                                    <label>
                                        <input type="radio" id="tunjanganTidak" name="tunjangan_jabatan" value="0">
                                        <i class="helper"></i>Tidak
                                    </label>
                                </div>
			                    <span id="errorTunjanganJabatan"></span>
			                </div>
			            </div>

	    			</div>
	    			<div class="col-md-6">


			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Group</label>
			                <div class="col-sm-8">
			                	<select id="group" name="group" class="form-control select2">
			                    </select>
	                        </div>
		                    <span id="errorGroup"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Shift</label>
			                <div class="col-sm-8 form-radio">
			                	<div class="radio radiofill radio-success radio-inline">
                                    <label>
                                        <input type="radio" id="shiftYa" name="shift" value="ya">
                                        <i class="helper"></i>Ya <u>( Pagi, Sore, Malam )</u>
                                    </label>
                                </div>
                                <div class="radio radiofill radio-danger radio-inline">
                                    <label>
                                        <input type="radio" id="shiftTidak" name="shift" value="tidak">
                                        <i class="helper"></i>Tidak <u>( Regular )</u>
                                    </label>
                                </div>
			                </div>
			            </div>

			            <!-- <div class="form-group row" id="infoNamaShift" style="display: none;">
			                <label class="col-sm-3 col-form-label">Nama Shift</label>
			                <div class="col-sm-8">
			                	<select id="nama_shift" name="nama_shift" class="form-control">
		                          	<option value=""> -- Pilih Nama Shift -- </option>
		                          	<option value="REGULAR">REGULAR</option>
		                          	<option value="SHIFT-PAGI">SHIFT PAGI</option>
		                          	<option value="SHIFT-SORE">SHIFT SORE</option>
		                          	<option value="SHIFT-MALAM">SHIFT MALAM</option>
		                          	<option value="SHIFT-PAGI-12-JAM">SHIFT PAGI 12 JAM</option>
		                          	<option value="SHIFT-MALAM-12-JAM">SHIFT MALAM 12 JAM</option>
		                        </select>
	                        </div>
			            </div> -->


	    				<div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Tanggal Masuk Kerja</label>
			                <div class="col-sm-8">
			                    <input type="text" class="form-control tanggal" name="tgl_masuk_kerja" id="tgl_masuk_kerja" placeholder="Tanggal Masuk Kerja">
			                    <span id="errorTglMasukKerja"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Status Kontrak</label>
			                <div class="col-sm-8 form-radio">
			                	<div class="radio radiofill radio-success radio-inline">
                                    <label>
                                        <input type="radio" id="kontrakIya" name="status_kontrak" value="iya">
                                        <i class="helper"></i>Iya
                                    </label>
                                </div>
                                <div class="radio radiofill radio-danger radio-inline">
                                    <label>
                                        <input type="radio" id="kontrakTidak" name="status_kontrak" value="tidak">
                                        <i class="helper"></i>Tidak
                                    </label>
                                </div>
			                    <span id="errorStatusKontrak"></span>
			                </div>
			            </div>
			            <div id="dataKontrak" style="display: none;">
				            <div class="form-group row">
				                <label class="col-sm-3 col-form-label">	Tanggal Awal dan Akhir Kontrak</label>
				                <div class="col-sm-8">
					            	<!-- <input type="text" id="tanggal_kontrak" name="tanggal_kontrak" class="form-control daterange"> -->
					            	<div class="input-group">
                                        <input type="text" class="input-sm form-control tanggal" id="start_date" name="start_date" placeholder="Awal">
                                        <span class="input-group-addon">sampai</span>
                                        <input type="text" class="input-sm form-control tanggal" id="expired_date" name="expired_date" placeholder="Akhir">
                                    </div>
					        	</div>
					        </div>
					        <div class="form-group row">
					        	<label class="col-sm-3 col-form-label">	Upload file Kontrak</label>
				                <div class="col-sm-8">
				                	<?php $defaultFile = base_url("/")."assets/images/default/no_file_.png"; ?>
						        	<div class="card rounded-card user-card" style="height: 220px; width: 220px;">
			                            <div class="card-block">
			                                <div class="img-hover">
			                                    <img class="img-fluid" id="upload_kontrak" src="<?php echo $defaultFile;?>" alt="user-img" style="height: 150px;">
			                                    <div class="img-overlay">
			                                        <span>
			                                            <a href="javascript:void(0);" title="Zoom file" id="btnZoomFile" class="btn btn-sm btn-success btn-outline-success">
			                                            	<i class="fa fa-search-plus"></i>
			                                            </a>
			                                            <a href="javascript:void(0);" title="Pilih Upload file" class="btn btn-sm btn-primary btn-outline-primary" id="ganti_file" data-popup="lightbox">
			                                            	<i class="fa fa-upload"></i>
			                                              </a>
			                                            <a href="javascript:void(0);" title="Hapus file" id="hapus_file" class="btn btn-sm btn-danger btn-outline-danger">
			                                            	<i class="fa fa-trash-o"></i>
			                                            </a>
                                                  <a  title="Download" id="download_pdf" class="btn btn-sm btn-success btn-outline-success">
			                                            	<i class="fa fa-download"></i>
			                                            </a>
			                                      	</span>
			                                    </div>

						                        <input name="file_kontrak" id="file_kontrak" type="file" style="display: none;">
						                        <input type="hidden" name="is_delete_kontrak" id="is_delete_kontrak" value="0">

			                                    <a href="<?php echo $defaultFile;?>" style="display: none;" data-toggle="lightbox" id="popUpFileOut" data-title="File kontrak" data-footer="">
						                            <img src="<?php echo $defaultFile;?>" id="popUpFileIn" class="img-fluid" alt="">
						                        </a>
			                                </div>
			                              	<div class="user-content"><h4 class="">File kontrak</h4></div>
			                            </div>
			                        </div>
		                    	</div>
					        </div>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Nama Ibu Kandung</label>
			                <div class="col-sm-8">
				            	<input type="text" id="ibu_kandung" name="ibu_kandung" class="form-control">
				        	</div>
				        </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Nama Suami / Istri</label>
			                <div class="col-sm-8">
				            	<input type="text" id="suami_istri" name="suami_istri" class="form-control">
				        	</div>
				        </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Jumlah Tanggungan</label>
			                <div class="col-sm-8">
				            	<input type="number" id="jumlah_tanggungan" min="0" name="jumlah_tanggungan" class="form-control">
				        	</div>
				        </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	NPWP</label>
			                <div class="col-sm-8">
				            	<input type="number" id="npwp" min="0" name="npwp" class="form-control">
				        	</div>
				        </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Golongan</label>
			                <div class="col-sm-8">
			                	<select id="golongan" name="golongan" class="form-control select2">
			                    </select>
	                        </div>
		                    <span id="errorGolongan"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Periode Gajian</label>
			                <div class="col-sm-8">
			                	<select id="periode_gaji" name="periode_gaji" class="form-control">
		                          	<option value=""> -- Pilih Periode -- </option>
		                          	<option value="Bulanan">Bulanan</option>
		                          	<option value="2-Mingguan">2 Mingguan</option>
		                          	<option value="1-Mingguan">1 Mingguan</option>
		                        </select>
	                        </div>
		                    <span id="errorPeriode"></span>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Nilai Gaji</label>
			                <div class="col-sm-8">
				            	<input type="text" id="nilai_gaji" disabled class="form-control">
				            	<input type="hidden" name="nilai_gaji" id="nilai_gaji_int">
            					<small><span id="mata_uang_nilai_gaji"></span></small>
				        	</div>
				        </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	No. Rekening</label>
			                <div class="col-sm-8">
				            	<input type="number" id="no_rekening" min="0" name="no_rekening" class="form-control">
				        	</div>
				        </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Bank</label>
			                <div class="col-sm-8">
			                	<select id="bank" name="bank" class="form-control select2">
			                    </select>
	                        </div>
		                    <span id="errorBank"></span>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Atas Nama Bank</label>
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
				        </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">Otoritas</label>
			                <div class="col-sm-8">
			                	<select id="otoritas" name="otoritas" class="form-control">
			                        <option value=""> -- Pilih Otoritas -- </option>
			                        <option value="1">Karyawan</option>
			                        <option value="2">Kabag</option>
			                        <option value="4">Security</option>
			                        <option value="3">HRD</option>
			                    </select>
	                        </div>
		                    <span id="errorOtoritas"></span>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Status Kerja</label>
			                <div class="col-sm-8 form-radio">
			                	<div class="radio radiofill radio-success radio-inline">
                                    <label>
                                        <input type="radio" id="status_kerja_aktif" name="status_kerja" value="aktif">
                                        <i class="helper"></i>Aktif
                                    </label>
                                </div>
                                <div class="radio radiofill radio-danger radio-inline">
                                    <label>
                                        <input type="radio" id="status_kerja_non_aktif" name="status_kerja" value="non_aktif">
                                        <i class="helper"></i>Non Aktif
                                    </label>
                                </div>
			                    <span id="errorStatusKerja"></span>
			                </div>
			            </div>

                  <div class="form-group row" id="showFace">
			                <label class="col-sm-3 col-form-label">	Face </label>
			                <div class="col-sm-8 form-radio">
			                	<div class="radio radiofill radio-success radio-inline">
                                    <label>
                                        <input type="radio" id="status_face_aktif" name="status_face" value="1">
                                        <i class="helper"></i>Aktif
                                    </label>
                                </div>
                                <div class="radio radiofill radio-danger radio-inline">
                                    <label>
                                        <input type="radio" id="status_face_non_aktif" name="status_face" value="0">
                                        <i class="helper"></i>Non Aktif
                                    </label>
                                </div>
			                    <span id="errorStatusFace"></span>
			                </div>
			            </div>

	    			</div>
	    		</div>
	    	</div>
		    <div class="card-footer">
		    	<div class="form-group row">
	                <!-- <label class="col-sm-2"></label> -->
	                <div class="col-sm-10">
	                    <button type="button" id="btnSimpan" class="btn btn-primary m-b-0">
	                    	<i class="ti-save"></i>	Simpan
	                	</button>
	                	&nbsp;&nbsp;&nbsp;&nbsp;
	                    <button type="button" class="btn btn-danger m-b-0 close-form">
	                    	<i class="ti-close"></i> Batal
	                	</button>
	                </div>
	            </div>
		    </div>
        <!-- </form> -->
		<?php echo form_close(); ?>
	</div>
	<!-- End Form Prosess -->


	<!-- Detail Karyawan -->
	<div class="card" id="detailData" style="display: none;">
	    <div class="card-header">
	        <h5><i class="fa fa-search-plus"></i> Detail Data Karyawan</h5>
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
			                <label class="col-sm-3 col-form-label">	Telepon / No.Hp</label>
			                <div class="col-sm-8">
			                    <span id="detailTelepon"></span>
			                    <hr>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Email</label>
			                <div class="col-sm-8">
			                    <span id="detailEmail"></span>
			                    <hr>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	No Telp/HP Wali</label>
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
			                <label class="col-sm-3 col-form-label">	Tunjangan Jabatan</label>
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
			                <label class="col-sm-3 col-form-label">	Shift</label>
			                <div class="col-sm-8 form-radio">
			                    <span id="detailShift"></span>
			                    <hr>
			                </div>
			            </div>

	    				<div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Tanggal Masuk Kerja</label>
			                <div class="col-sm-8">
			                    <span id="detailTglMasukKerja"></span>
			                    <hr>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Status Kontrak</label>
			                <div class="col-sm-8 form-radio">
			                    <span id="detailStatusKontrak"></span>
			                    <hr>
			                </div>
			            </div>
			            <div id="detailDataKontrak" style="display: none;">
				            <div class="form-group row">
				                <label class="col-sm-3 col-form-label">	Tanggal Awal dan Akhir Kontrak</label>
				                <div class="col-sm-8">
                                    <span id="detailStartDate"></span> &nbsp;
                                    <label class="label label-md label-primary">Sampai</label>&nbsp;
                                    <span id="detailExpiredDate"></span>
                                    <hr>
					        	</div>
					        </div>
					        <div class="form-group row">
					        	<label class="col-sm-3 col-form-label">	file Kontrak</label>
				                <div class="col-sm-8">
				                	<?php $defaultFile = base_url("/")."assets/images/default/no_file_.png"; ?>
						        	<div class="card rounded-card user-card" style="height: 220px; width: 220px;">
			                            <div class="card-block">
			                                <div class="img-hover">
			                                    <img class="img-fluid" id="detail_upload_kontrak" src="<?php echo $defaultFile;?>" alt="user-img" style="height: 150px;">
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
			                            </div>
			                        </div>
		                    	</div>
					        </div>
			            </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Nama Ibu Kandung</label>
			                <div class="col-sm-8">
				            	<span id="detailIbuKandung"></span>
				            	<hr>
				        	</div>
				        </div>

			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Nama Suami / Istri</label>
			                <div class="col-sm-8">
				            	<span id="detailSuamiIstri"></span>
				            	<hr>
				        	</div>
				        </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	Jumlah Tanggungan</label>
			                <div class="col-sm-8">
				            	<span id="detailJumlahTanggungan"></span>
				            	<hr>
				        	</div>
				        </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	NPWP</label>
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
			                <label class="col-sm-3 col-form-label">	Nilai Gaji</label>
			                <div class="col-sm-8">
            					<span id="detailNilaiGaji"></span>
            					<hr>
				        	</div>
				        </div>
			            <div class="form-group row">
			                <label class="col-sm-3 col-form-label">	No. Rekening</label>
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
			                <label class="col-sm-3 col-form-label">	Atas Nama</label>
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
			                <label class="col-sm-3 col-form-label">	Status Kerja</label>
			                <div class="col-sm-8 form-radio">
			                    <span id="detailStatusKerja"></span>
			                    <hr>
			                </div>
			            </div>

	    			</div>
	    		</div>
	    	</div>
		    <div class="card-footer">
		    	<div class="form-group row">
	                <!-- <label class="col-sm-2"></label> -->
	                <div class="col-sm-10">
	                	&nbsp;&nbsp;&nbsp;&nbsp;
	                    <button type="button" class="btn btn-danger m-b-0 close-form">
	                    	<i class="ti-close"></i> Batal
	                	</button>
	                </div>
	            </div>
		    </div>
        </form>
	</div>
	<!-- End Detail Karyawan -->


	<?php echo modalSaveOpen("modalQrCode","sm","black","Cetak Kartu Karyawan"); ?>
	    <div id="inputMessage"></div>
	    <div class="card user-card" id="viewPrintCard">
	    	<?php
              $cardBackground = "";
              $settingLogo = base_url("/")."/uploads/setting/".$logo;
              if ($qrcode == "" || $qrcode == null) {
                $cardBackground = base_url("/")."assets/images/pattern5.png";
              }
              else {
                $cardBackground = base_url("/")."/uploads/setting/".$qrcode;
              }
         ?>
            <div class="card-header-img" style="background-image: url('<?php echo $cardBackground; ?>'); background-size: 100%; height: 420px; background-repeat: no-repeat;background-size: cover;">
                <img src="<?php echo $settingLogo; ?>" alt="logo" class="img-responsive" style="width:25%;height:15%;margin-bottom:20px;">
                <p style="font-weight: 700;text-align: center;"><?php echo $nama_perusahaan; ?></p>
                <img style="margin-top: 40px; width: 55%; height: 36%;" class="img-fluid img-circle" id="fotoKaryawan" src="<?php echo base_url("/");?>assets/images/user-card/card/card-img1.jpg" alt="card-img">

                <div id="nameJabatan" style="margin-top: 12px; color: black;">
                	<center>
                		<b><u id="namaKaryawan"></u></b><br>
	                	<span id="jabatanKaryawan"></span>
                	</center>
                </div>

                <div id="imgQrCode" class="">
                	<img id="QrCode" style="margin-top: 0px; margin-left: 197px; height: 65px; width: 60px;" class="img-fluid" src="<?php echo base_url("/");?>assets/images/default/empty_file.png" alt="card-img">
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

</div>


<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script> -->

<?php assets_script("download2.js"); ?>
<?php assets_script_custom_master("karyawan.js"); ?>
