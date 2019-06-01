<div class="col-md-12">
	<div class="card">
        <div class="card-header">
            <h5>Data Table Jadwal Kerja Shift Karyawan Per Bulan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
            </div>
        </div>

		<!-- Nav tabs Parent-->
	    <ul class="nav nav-tabs md-tabs " role="tablist">
            <li class="nav-item">
                <a class="nav-link f-16 active text-purple" data-toggle="tab" href="#tabKaryawan" id="navKaryawan" role="tab" aria-expanded="true">
                	<i class="icofont icofont-brand-myspace "></i> Karyawan
                </a>
                <div class="slide"></div>
            </li>
            <li class="nav-item">
                <a class="nav-link f-16 text-navy" data-toggle="tab" href="#tabSecurity" id="navSecurity" role="tab" aria-expanded="false">
                	<i class="icofont icofont-police"></i> Security
                </a>
                <div class="slide"></div>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
        	<!-- Content Karyawan -->
            <div class="tab-pane active" id="tabKaryawan" role="tabpanel" aria-expanded="true">
            	<!-- Content Tab karyawan -->
                <!-- Nav tabs -->
			    <ul class="nav nav-tabs md-tabs m-t-0" role="tablist" id="myTabJadwal">
			        <li class="nav-item">
			            <a class="nav-link text-info" data-toggle="tab" id="tabBulanLalu" href="#contenBulanLalu" role="tab" aria-selected="false">
			            	<i class="icofont icofont-brand-myspace "></i> Bulan Lalu
			            </a>
			            <div class="slide"></div>
			        </li>
			        <li class="nav-item">
			            <a class="nav-link text-success active" data-toggle="tab" id="tabBulanSaatIni" href="#contentBulanSaatIni" role="tab" aria-selected="true">
			            	<i class="icofont icofont-brand-myspace "></i> Bulan Saat Ini
			            </a>
			            <div class="slide"></div>
			        </li>
			        <li class="nav-item">
			            <a class="nav-link text-danger" data-toggle="tab" id="tabBulanDepan" href="#contentBulanDepan" role="tab" aria-selected="false">
			            	<i class="icofont icofont-brand-myspace "></i> Bulan Depan
			            </a>
			            <div class="slide"></div>
			        </li>
			    </ul>
			    <!-- Tab panes -->
			    <div class="tab-content card-body">
			    	<!-- Content Bulan lalu -->
			        <div class="tab-pane fade" id="contenBulanLalu" role="tabpanel" aria-labelledby="false">
			           	<div class="dt-responsive table-responsive" id="dataTableBulanLalu">
			                <table id="tblBulanLalu" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
			                    <thead class="thead-info">
			                        <tr>
			                            <th>No</th>
			                            <th>Tanggal</th>
			                            <th>Nama Group</th>
			                            <th>Shift</th>
			                            <th>Opsi</th>
			                        </tr>
			                    </thead>
			                    <tbody></tbody>
			                </table>
			            </div>
			            <div class="card" id="dataTableKarywanBulanLalu" style="display: none;">
						    <div class="card-header">
						        <h5><i class="icofont icofont-brand-myspace "></i> Data jumlah karyawan bulan lalu</h5>
						        <div class="card-header-right" title="Tutup">
						            <i class="ti-close btn btn-danger btn-outline-danger close-table-karyawan-lalu"></i>
						        </div>
						    </div>

						    <div class="card-body">
						        <code class="text-info">
							        <u>Tanggal : <i id="tglLalu">-</i></u> || <u>Nama Group : <i id="groupLalu">-</i></u> || <u>Shift : <i id="shiftLalu">-</i></u>
						        </code>
						        <br><br>
							    <table id="tblKaryawanBulanLalu" class="table table-responsive table-striped table-bordered table-sm nowrap" style="width: 100%;">
				                    <thead class="thead-primary">
				                        <tr>
				                            <th>No</th>
				                            <th>Action</th>
				                            <th>Nama</th>
				                            <th>No Induk</th>
				                            <th>Shift</th>
				                            <th>Tgl Lahir</th>
				                            <th>Jenis Kelamin</th>
				                            <th>Jabatan</th>
				                        </tr>
				                    </thead>
				                    <tbody></tbody>
				                </table>
								<br>
					            <button type="button" class="btn btn-danger btn-outline-danger close-table-karyawan-lalu">Tutup <i class="ti-close"></i></button>
				            </div>
						</div>
			        </div>
			        <!-- End Content Bulan saat ini -->

			        <!-- Content Bulan saat ini -->
			        <div class="tab-pane fade show active" id="contentBulanSaatIni" role="tabpanel" aria-labelledby="true">
			            <div class="dt-responsive table-responsive" id="dataTableBulanSaatIni">
	                        <!-- Nav tabs -->
	                        <ul class="nav nav-tabs md-tabs" role="tablist">
	                            <li class="nav-item">
	                                <a class="nav-link text-warning" data-toggle="tab" id="tabTglSudahLalu" href="#tglLewat" role="tab" aria-expanded="true">
	                                	<i class="icofont icofont-brand-myspace "></i> Tanggal sudah berlalu
	                                </a>
	                                <div class="slide"></div>
	                            </li>
	                            <li class="nav-item">
	                                <a class="nav-link active" data-toggle="tab" id="tabTglKedepan" href="#tglKedepan" role="tab" aria-expanded="false">
	                                	<i class="icofont icofont-brand-myspace "></i> Tanggal Sekarang dan seterusnya
	                                </a>
	                                <div class="slide"></div>
	                            </li>
	                        </ul>
	                        <!-- Tab panes -->
	                        <div class="tab-content card-body">
	                            <div class="tab-pane" id="tglLewat" role="tabpanel" aria-expanded="true">
	                               	<table id="tblBulanSaatIniBerlalu" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
					                    <thead class="thead-muted">
					                        <tr>
					                            <th>No</th>
					                            <th>Tanggal</th>
					                            <th>Nama Group</th>
					                            <th>Shift</th>
					                            <th>Opsi</th>
					                        </tr>
					                    </thead>
					                    <tbody></tbody>
					                </table>
	                            </div>
	                            <div class="tab-pane active" id="tglKedepan" role="tabpanel" aria-expanded="false">
	                            	<table id="tblBulanSaatIni" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
					                    <thead class="thead-green">
					                        <tr>
					                            <th>No</th>
					                            <th>Tanggal</th>
					                            <th>Nama Group</th>
					                            <th>Shift</th>
					                            <th>Opsi</th>
					                        </tr>
					                    </thead>
					                    <tbody></tbody>
					                </table>
	                            </div>
	                        </div>

			            </div>

			            <div class="card" id="dataTableKarywanBulanSaatini" style="display: none;">
						    <div class="card-header">
						        <h5><i class="icofont icofont-brand-myspace "></i> Data jumlah karyawan bulan saat ini</h5>
						        <div class="card-header-right" title="Tutup">
						            <i class="ti-close btn btn-danger btn-outline-danger close-table-karyawan-saatini"></i>
						        </div>
						    </div>
						    <div class="card-body">
						        <code class="text-success">
						        	<u>Tanggal : <i id="tglSaatIni">-</i></u> || <u>Nama Group : <i id="groupSaatIni">-</i></u> || <u>Shift : <i id="shiftSaatIni">-</i></u>
						        </code>
						    	<br><br>
						    	<table id="tblKaryawanBulanSaatIni" class="table table-striped table-bordered table-sm table-responsive" style="width: 100% !important;">
				                    <thead class="thead-success">
				                        <tr>
				                            <th>No</th>
				                            <th>Action</th>
				                            <th>Nama</th>
				                            <th>No Induk</th>
				                            <th>Shift</th>
				                            <th>Tgl Lahir</th>
				                            <th>Jenis Kelamin</th>
				                            <th>Jabatan</th>
				                        </tr>
				                    </thead>
				                    <tbody></tbody>
				                </table>
				                <br>
			                	<button type="button" class="btn btn-danger btn-outline-danger close-table-karyawan-saatini">Tutup <i class="ti-close"></i></button>
						    </div>
						</div>
			        </div>
			        <!-- End Content Bulan saat ini -->

			        <!-- Content Bulan depan -->
			        <div class="tab-pane fade" id="contentBulanDepan" role="tabpanel" aria-labelledby="false">
			            <div class="dt-responsive table-responsive" id="dataTableBulanDepan">
			                <table id="tblBulanDepan" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
			                    <thead class="thead-danger">
			                        <tr>
			                            <th>No</th>
			                            <th>Tanggal</th>
			                            <th>Nama Group</th>
			                            <th>Shift</th>
			                            <th>Opsi</th>
			                        </tr>
			                    </thead>
			                    <tbody></tbody>
			                </table>
			            </div>
			            <div class="card" id="dataTableKarywanBulanDepan" style="display: none;">
						    <div class="card-header">
						        <h5><i class="icofont icofont-brand-myspace "></i> Data jumlah karyawan bulan depan</h5>
						        <div class="card-header-right" title="Tutup">
						            <i class="ti-close btn btn-danger btn-outline-danger close-table-karyawan-depan"></i>
						        </div>
						    </div>

						    <div class="card-body">
						        <code class="text-danger">
							        <u>Tanggal : <i id="tglDepan">-</i></u> || <u>Nama Group : <i id="groupDepan">-</i></u> || <u>Shift : <i id="shiftDepan">-</i></u>
						        </code>
							    <br><br>
							    <table id="tblKaryawanBulanDepan" class="table table-striped table-bordered table-sm nowrap table-responsive" style="width: 100%;">
				                    <thead class="thead-maroon">
				                        <tr>
				                            <th>No</th>
				                            <th>Action</th>
				                            <th>Nama</th>
				                            <th>No Induk</th>
				                            <th>Shift</th>
				                            <th>Tgl Lahir</th>
				                            <th>Jenis Kelamin</th>
				                            <th>Jabatan</th>
				                        </tr>
				                    </thead>
				                    <tbody></tbody>
				                </table>
				                <br>
			                	<button type="button" class="btn btn-danger btn-outline-danger close-table-karyawan-depan">Tutup <i class="ti-close"></i></button>
			                </div>
						</div>
			        </div>
			        <!-- End Content bulan depan -->
			    </div>
			    <!-- End Content Tab Karyawan -->
            </div><!-- End Content Karyawan -->

            <!-- Conten nya Security -->
            <div class="tab-pane" id="tabSecurity" role="tabpanel" aria-expanded="false">
            	<!-- Content Tab Security -->
			    <ul class="nav nav-tabs md-tabs m-t-0" role="tablist" id="myTabJadwal">
			        <li class="nav-item">
			            <a class="nav-link text-info" data-toggle="tab" id="tabBulanLaluSecurity" href="#contenBulanLaluSecurity" role="tab" aria-selected="false">
			            	<i class="icofont icofont-police"></i> Bulan Lalu
			            </a>
			            <div class="slide"></div>
			        </li>
			        <li class="nav-item">
			            <a class="nav-link text-success active" data-toggle="tab" id="tabBulanSaatIniSecurity" href="#contentBulanSaatIniSecurity" role="tab" aria-selected="true">
			            	<i class="icofont icofont-police"></i> Bulan Saat Ini
			            </a>
			            <div class="slide"></div>
			        </li>
			        <li class="nav-item">
			            <a class="nav-link text-danger" data-toggle="tab" id="tabBulanDepanSecurity" href="#contentBulanDepanSecurity" role="tab" aria-selected="false">
			            	<i class="icofont icofont-police"></i> Bulan Depan
			            </a>
			            <div class="slide"></div>
			        </li>
			    </ul>
			    
			    <div class="tab-content card-body">
			    	<!-- Content Bulan lalu Security -->
			        <div class="tab-pane fade" id="contenBulanLaluSecurity" role="tabpanel" aria-labelledby="false">
			           	<div class="dt-responsive table-responsive">
			                <table id="tblBulanLaluSecurity" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
			                    <thead class="thead-info">
			                        <tr>
			                            <th>No</th>
			                            <th>Opsi</th> <!-- ada btn update dan ganti/ jika sama dengan tanggal sekarang tidak bisa di update tapi bisa di ganti orang aja. -->
			                            <th>Tanggal</th>
			                            <th>Nama</th>
			                            <th>No Induk</th>
			                            <th>Shift</th>
			                            <th>Tgl Lahir</th>
			                            <th>Jenis Kelamin</th>
			                            <th>Jabatan</th>
			                        </tr>
			                    </thead>
			                    <tbody></tbody>
			                </table>
			            </div>
			        </div>
			        <!-- End Content Bulan saat ini Security-->

			        <!-- Content Bulan saat ini Security-->
			        <div class="tab-pane fade show active" id="contentBulanSaatIniSecurity" role="tabpanel" aria-labelledby="true">
			            <div class="dt-responsive table-responsive" id="dataTableBulanSaatIni">
	                        <!-- Nav tabs -->
	                        <ul class="nav nav-tabs md-tabs" role="tablist">
	                            <li class="nav-item">
	                                <a class="nav-link text-warning" data-toggle="tab" id="tabTglSudahLaluSecurity" href="#tglLewatSecurity" role="tab" aria-expanded="true">
	                                	<i class="icofont icofont-police "></i> Tanggal sudah berlalu
	                                </a>
	                                <div class="slide"></div>
	                            </li>
	                            <li class="nav-item">
	                                <a class="nav-link active" data-toggle="tab" id="tabTglKedepanSecurity" href="#tglKedepanSecurity" role="tab" aria-expanded="false">
	                                	<i class="icofont icofont-police "></i> Tanggal Sekarang dan seterusnya
	                                </a>
	                                <div class="slide"></div>
	                            </li>
	                        </ul>
	                        <!-- Tab panes -->
	                        <div class="tab-content card-body">
	                            <div class="tab-pane" id="tglLewatSecurity" role="tabpanel" aria-expanded="true">
	                               	<table id="tblBulanSaatIniBerlaluSecurity" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
					                    <thead class="thead-muted">
					                        <tr>
					                            <th>No</th>
					                            <th>Opsi</th> <!-- ada btn update dan ganti/ jika sama dengan tanggal sekarang tidak bisa di update tapi bisa di ganti orang aja. -->
					                            <th>Tanggal</th>
					                            <th>Nama</th>
					                            <th>No Induk</th>
					                            <th>Shift</th>
					                            <th>Tgl Lahir</th>
					                            <th>Jenis Kelamin</th>
					                            <th>Jabatan</th>
					                        </tr>
					                    </thead>
					                    <tbody></tbody>
					                </table>
	                            </div>
	                            <div class="tab-pane active" id="tglKedepanSecurity" role="tabpanel" aria-expanded="false">
	                            	<table id="tblBulanSaatIniSecurity" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
					                    <thead class="thead-green">
					                        <tr>
					                            <th>No</th>
					                            <th>Opsi</th> <!-- ada btn update dan ganti/ jika sama dengan tanggal sekarang tidak bisa di update tapi bisa di ganti orang aja. -->
					                            <th>Tanggal</th>
					                            <th>Nama</th>
					                            <th>No Induk</th>
					                            <th>Shift</th>
					                            <th>Tgl Lahir</th>
					                            <th>Jenis Kelamin</th>
					                            <th>Jabatan</th>
					                        </tr>
					                    </thead>
					                    <tbody></tbody>
					                </table>
	                            </div>
	                        </div>

			            </div>
			        </div>
			        <!-- End Content Bulan saat ini Security-->

			        <!-- Content Bulan depan Security-->
			        <div class="tab-pane fade" id="contentBulanDepanSecurity" role="tabpanel" aria-labelledby="false">
			            <div class="dt-responsive table-responsive">
			                <table id="tblBulanDepanSecurity" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
			                    <thead class="thead-danger">
			                        <tr>
			                            <th>No</th>
			                            <th>Opsi</th> <!-- ada btn update dan ganti/ jika sama dengan tanggal sekarang tidak bisa di update tapi bisa di ganti orang aja. -->
			                            <th>Tanggal</th>
			                            <th>Nama</th>
			                            <th>No Induk</th>
			                            <th>Shift</th>
			                            <th>Tgl Lahir</th>
			                            <th>Jenis Kelamin</th>
			                            <th>Jabatan</th>
			                        </tr>
			                    </thead>
			                    <tbody></tbody>
			                </table>
			            </div>
			        </div>
			        <!-- End Content bulan depan Security-->
			    </div>
			    <!-- End Content Tab Security -->
            </div><!-- End Conten nya Security -->
        </div>
	    <!-- End Nav tabs parent -->
    </div>
</div>

<!-- #jadwal kerja karyawan biasa -->
<?php echo modalSaveOpen(false,"","green","Form Jadwal Bulan Saat Ini"); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>
	    <div class="form-group">
            <label class="block">Tanggal :</label>
            <input id="tanggal" name="tanggal" type="text" class="form-control tanggal" readonly placeholder="pilih tanggal">
	    </div>
	    <div class="row">
	    	<div class="col-md-6">
	    		<div class="form-group">
		            <label class="block">Nama Group :</label>
		            <select id="nama_group" name="nama_group" class="form-control">
		            	<option value="">-- Pilih Group --</option>
		            	<?php foreach ($dataGroup as $val): ?>
		            		<option value="<?php echo $val->id_grup; ?>"><?php echo $val->grup; ?></option>
		            	<?php endforeach ?>
		            </select>
			    </div>
	    	</div>
	    	<div class="col-md-6">
	    		<div class="form-group">
		            <label class="block">Shift Kerja:</label>
		            <select id="nama_shift" name="nama_shift" class="form-control">
		            	<option value="">-- Pilih Shift --</option>
		            	<?php foreach ($dataShift as $val): ?>
		            		<option value="<?php echo $val->shift; ?>"><?php echo $val->shift; ?></option>
		            	<?php endforeach ?>
		            	<option value="OFF">OFF</option>
		            </select>
			    </div>
	    	</div>
	    </div>

	    <div class="row" id="dataKabagGroup" style="display: none;">
	    	<div class="col-md-6">
	    		<div class="form-group">
		            <label class="block">Nama Kabag :</label>
		            <input id="nama_kabag" name="nama_kabag" type="text" class="form-control" readonly>
		            <input type="hidden" name="id_kabag" id="id_kabag">
		            <input type="hidden" name="otoritas_kabag" id="otoritas_kabag">
			    </div>
	    		<div class="form-group">
		            <label class="block">No Induk kabag :</label>
		            <input id="no_induk_kabag" name="no_induk_kabag" type="text" class="form-control" readonly>
			    </div>
	    	</div>
	    	<div class="col-md-6">
	    		<div class="form-group">
		            <label class="block">Shift Kerja Kabag:</label>
		            <select id="nama_shift_kabag" name="nama_shift_kabag" class="form-control">
		            	<option value="">-- Pilih Shift --</option>
		            	<?php foreach ($dataShift as $val): ?>
		            		<option value="<?php echo $val->shift; ?>"><?php echo $val->shift; ?></option>
		            	<?php endforeach ?>
		            	<option value="OFF">OFF</option>
		            </select>
			    </div>
            	<div class="checkbox-fade fade-in-success">
                    <label>
                        <input type="checkbox" id="sama_shift" value="0">
                        <span class="cr">
                            <i class="cr-icon icofont icofont-ui-check txt-success"></i>
                        </span> <span>Sama dengan shift group</span>
                    </label>
                </div>
	    	</div>
	    </div>
			   
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>

<?php $defaultPhoto = base_url("/")."assets/images/default/no_user.png"; ?>
<!-- For Ganti karyawan -->
<?php echo modalSaveOpen("modalGanti","","success","Form ganti jadwal karyawan bulan saat ini"); ?>
    <div id="inputMessageGanti"></div>
    <?php echo form_open("",array("id" => "formDataGanti")); ?>

		<div class="card rounded-card user-card">
            <div class="card-block">
            	<div class="row">
            		<div class="col-md-4">
            			<div class="img-hover">
		                    <img class="img-fluid img-circle" id="detailPhotoUtama" src="<?php echo $defaultPhoto;?>" style="height: 100px; width: 100px;" alt="gambar karyawan">
		                    <div class="img-overlay">
		                        <span>
		                          <a href="javascript:void();" id="btnZoomUtama"  class="btn btn-sm btn-outline-primary" data-popup="lightbox"><i class="fa fa-search-plus"></i></a>
		                      	</span>
		                    </div>
		                </div>
		                <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpOutUtama" data-title="Photo karyawan utama" data-footer="">
                            <img src="<?php echo $defaultPhoto;?>" id="detailPopUpInUtama" class="img-fluid" alt="">
                        </a>
            		</div>
            		<div class="col-md-8">
            			<div class="text-left">
		                    <b>Nama: <h5 id="namaUtama"></h5></b><br>
		                    <b>No Induk: <h5 id="noIndukUtama"></h5></b><br>
		                    <b>Tanggal Lahir: <h5 id="tglLahirUtama"></h5></b><br>
		                    <b>Jenis Kelamin: <h5 id="kelaminUtama"></h5></b><br>
		                    <b>Jabatan: <h5 id="jabatanUtama"></h5></b><br>
		                    <b>Nama Group: <h5 id="groupUtama"></h5></b><br>
		                </div>
		                <input type="hidden" id="karyawanId" name="karyawanId" value="">
            		</div>
            	</div>  
            </div>
        </div>

		<div class="form-group m-t-10">
            <label class="block">Karyawan pengganti :</label>
            <select id="karyawan_ganti" name="karyawan_ganti" class="form-control select2">
            </select>
	    </div>
		<div class="card rounded-card user-card" id="dataKaryawanGanti" style="border: 1px solid orange; display: none;">
		    <div class="card-block">
		    	<div class="row">
            		<div class="col-md-4">
		                <div class="img-hover">
		                    <img class="img-fluid img-circle" id="detailPhotoGanti" src="<?php echo $defaultPhoto;?>" alt="foto karyawan ganti" style="height: 100px; width: 100px;">
		                    <div class="img-overlay">
		                        <span>
		                          <a href="javascript:void();" id="btnZoomGanti"  class="btn btn-sm btn-primary" data-popup="lightbox"><i class="fa fa-search-plus"></i></a>
		                      	</span>
		                    </div>
		                </div>
		                <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpOutGanti" data-title="Photo karyawan Ganti" data-footer="">
                            <img src="<?php echo $defaultPhoto;?>" id="detailPopUpInGanti" class="img-fluid" alt="">
                        </a>
		            </div>
            		<div class="col-md-8">
		                <div class=" text-left">
		                    <b>Nama: <h5 id="namaGanti"></h5></b><br>
		                    <b>No Induk: <h5 id="noIndukGanti"></h5></b><br>
		                    <b>Tanggal Lahir: <h5 id="tglLahirGanti"></h5></b><br>
		                    <b>Jenis Kelamin: <h5 id="kelaminGanti"></h5></b><br>
		                    <b>Jabatan: <h5 id="jabatanGanti"></h5></b><br>
		                    <b>Nama Group: <h5 id="groupGanti"></h5></b><br>
		                </div>
		            </div>
		        </div>
            </div>
        </div>
        
        <div id="errorKaryawanGanti"></div>

    	<div class="form-group m-t-10">
            <label class="block">Keterangan :</label>
            <textarea class="form-control" name="keterangan" id="keterangan" rows="3" style="resize: vertical;"></textarea>
	    </div>	
	    
	<?php echo form_close(); ?>
<?php echo modalSaveClose("Simpan","btnSimpanGanti"); ?>

<!-- For tambah karyawan -->
<?php echo modalSaveOpen("modalTambahKaryawan","","success","Form tambah karyawan di jadwal bulan saat ini"); ?>
    <div id="inputMessageGanti"></div>
    <?php echo form_open("",array("id" => "formTambahKaryawan")); ?>

		<div class="form-group m-t-10">
            <label class="block">Karyawan Tambahan:</label>
            <select id="tambahKaryawan" name="tambahKaryawan" class="form-control select2">
            </select>
	    </div>
        
        <div id="errorTambahKaryawan"></div>
	    
	<?php echo form_close(); ?>
<?php echo modalSaveClose("Simpan","btnSimpanTambahKaryawan"); ?>
<!-- #end jadwal kerja karyawan biasa -->

<!-- For Security -->
<?php echo modalSaveOpen("modalSecurity","","success","Form ganti jadwal Security bulan saat ini"); ?>
    <?php echo form_open("",array("id" => "formDataSecurity")); ?>
    	<div class="form-group">
            <label class="block">Tanggal :</label>
            <input id="tgl_security" name="tgl_security" type="text" class="form-control tanggal" readonly placeholder="pilih tanggal">
	    </div>
	    <div class="row">
	    	<div class="col-md-6">
	    		<div class="form-group">
		            <label class="block">Security :</label>
		            <select id="karyawan_security" name="karyawan_security" class="form-control select2">
		            </select>
			    </div>
	    	</div>
	    	<div class="col-md-6">
	    		<div class="form-group">
		            <label class="block">Shift security:</label>
		            <select id="shift_security" name="shift_security" class="form-control">
		            	<option value="">-- Pilih Shift --</option>
		            	<?php foreach ($dataShift as $val): ?>
		            		<option value="<?php echo $val->shift; ?>"><?php echo $val->shift; ?></option>
		            	<?php endforeach ?>
		            	<option value="OFF">OFF</option>
		            </select>
			    </div>
	    	</div>
	    </div>

		<div class="card rounded-card user-card" id="dataSecurity" style="border: 1px solid black; display: none;">
		    <div class="card-block">
		    	<div class="row">
            		<div class="col-md-4">
		                <div class="img-hover">
		                    <img class="img-fluid img-circle" id="detailPhotoSecurity" src="<?php echo $defaultPhoto;?>" alt="foto Security" style="height: 100px; width: 100px;">
		                    <div class="img-overlay">
		                        <span>
		                          <a href="javascript:void();" id="btnZoomSecurity"  class="btn btn-sm btn-primary" data-popup="lightbox"><i class="fa fa-search-plus"></i></a>
		                      	</span>
		                    </div>
		                </div>
		                <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpOutSecurity" data-title="Photo Security" data-footer="">
                            <img src="<?php echo $defaultPhoto;?>" id="detailPopUpInSecurity" class="img-fluid" alt="">
                        </a>
		            </div>
            		<div class="col-md-8">
		                <div class=" text-left">
		                    <b>Nama: <h5 id="namaSecurity"></h5></b><br>
		                    <b>No Induk: <h5 id="noIndukSecurity"></h5></b><br>
		                    <b>Tanggal Lahir: <h5 id="tglLahirSecurity"></h5></b><br>
		                    <b>Jenis Kelamin: <h5 id="kelaminSecurity"></h5></b><br>
		                    <b>Jabatan: <h5 id="jabatanSecurity"></h5></b><br>
		                    <b>Nama Group: <h5 id="groupSecurity"></h5></b><br>
		                </div>
		            </div>
		        </div>
            </div>
        </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose("Simpan","btnSimpanSecurity"); ?>
<!-- #end jadwal security -->

<?php echo modalSaveOpen("modalGantiSecurity","","black","Form ganti Security"); ?>
    <?php echo form_open("",array("id" => "formDataSecurityGanti")); ?>
    	<code class="text-info">
	        <u>Tanggal : <i id="tglGantiSecurity">-</i></u> || <u>Shift : <i id="shiftGantiSecurity">-</i></u>
        </code>
    	<div class="card rounded-card user-card m-t-10" id="dataSecurityUtama" style="border: 1px solid navy;">
		    <div class="card-block">
		    	<div class="row">
            		<div class="col-md-4">
		                <div class="img-hover">
		                    <img class="img-fluid img-circle" id="detailPhotoSecurityUtama" src="<?php echo $defaultPhoto;?>" alt="foto Security" style="height: 100px; width: 100px;">
		                    <div class="img-overlay">
		                        <span>
		                          <a href="javascript:void();" id="btnZoomSecurityUtama"  class="btn btn-sm btn-primary" data-popup="lightbox"><i class="fa fa-search-plus"></i></a>
		                      	</span>
		                    </div>
		                </div>
		                <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpOutSecurityUtama" data-title="Photo Security" data-footer="">
                            <img src="<?php echo $defaultPhoto;?>" id="detailPopUpInSecurityUtama" class="img-fluid" alt="">
                        </a>
		            </div>
            		<div class="col-md-8">
		                <div class=" text-left">
		                    <b>Nama: <h5 id="namaSecurityUtama"></h5></b><br>
		                    <b>No Induk: <h5 id="noIndukSecurityUtama"></h5></b><br>
		                    <b>Tanggal Lahir: <h5 id="tglLahirSecurityUtama"></h5></b><br>
		                    <b>Jenis Kelamin: <h5 id="kelaminSecurityUtama"></h5></b><br>
		                    <b>Jabatan: <h5 id="jabatanSecurityUtama"></h5></b><br>
		                    <b>Nama Group: <h5 id="groupSecurityUtama"></h5></b><br>
		                </div>
		            </div>
		            <input type="hidden" id="karyawanIdSecurity">
		        </div>
            </div>
        </div>

		<div class="form-group m-t-10">
            <label class="block">Security Ganti:</label>
            <select id="karyawan_security_ganti" name="karyawan_security_ganti" class="form-control select2">
            </select>
	    </div>

		<div class="card rounded-card user-card" id="dataSecurityGanti" style="border: 1px solid orange; display: none;">
		    <div class="card-block">
		    	<div class="row">
            		<div class="col-md-4">
		                <div class="img-hover">
		                    <img class="img-fluid img-circle" id="detailPhotoSecurityGanti" src="<?php echo $defaultPhoto;?>" alt="foto Security" style="height: 100px; width: 100px;">
		                    <div class="img-overlay">
		                        <span>
		                          <a href="javascript:void();" id="btnZoomSecurityGanti"  class="btn btn-sm btn-primary" data-popup="lightbox"><i class="fa fa-search-plus"></i></a>
		                      	</span>
		                    </div>
		                </div>
		                <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="detailPopUpOutSecurityGanti" data-title="Photo Security" data-footer="">
                            <img src="<?php echo $defaultPhoto;?>" id="detailPopUpInSecurityGanti" class="img-fluid" alt="">
                        </a>
		            </div>
            		<div class="col-md-8">
		                <div class=" text-left">
		                    <b>Nama: <h5 id="namaSecurityGanti"></h5></b><br>
		                    <b>No Induk: <h5 id="noIndukSecurityGanti"></h5></b><br>
		                    <b>Tanggal Lahir: <h5 id="tglLahirSecurityGanti"></h5></b><br>
		                    <b>Jenis Kelamin: <h5 id="kelaminSecurityGanti"></h5></b><br>
		                    <b>Jabatan: <h5 id="jabatanSecurityGanti"></h5></b><br>
		                    <b>Nama Group: <h5 id="groupSecurityGanti"></h5></b><br>
		                </div>
		            </div>
		        </div>
            </div>
        </div>
        <!-- <div id="errorDataSecurity"></div>	 -->
    	<div class="form-group m-t-10">
            <label class="block">Keterangan :</label>
            <textarea class="form-control" name="keterangan_security" id="keterangan_security" rows="3" style="resize: vertical;"></textarea>
	    </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose("Simpan","btnSimpanSecurityGanti"); ?>

<?php assets_script_custom_aktivitas("jadwalkerja.js"); ?>