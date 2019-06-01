<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card" id="dataTable">
        <div class="card-header">
            <h5>Data Table Pengguna</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblUsers" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th width="1%">No</th>
                            <th>Photo</th>
                            <th>Username</th>
                            <th>Level</th>
                            <th>Nama</th>
                            <!-- <th>Keterangan</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
	<div class="card" id="formProses" style="display: none;">
	    <div class="card-header">
	        <h5 id="titleForm"><i class="fa fa-plus"></i> Basic Inputs Validation</h5>
	        <div class="card-header-right">
	            <i class="ti-close btn btn-danger btn-outline-danger close-form"></i>
	        </div>
	    </div>
		<?php echo form_open("",array("id" => "formData")); ?>
        <!-- <form id="main" method="post" action="/" novalidate=""> -->
	    	<div class="card-block">
	    		<div class="row">
	    			<div class="col-md-8">
			            <div class="form-group row">
			                <label class="col-sm-2 col-form-label">Nama</label>
			                <div class="col-sm-10">
			                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama Lengkap">
			                    <span id="errorNama"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-2 col-form-label">Username</label>
			                <div class="col-sm-10">
			                    <input type="text" class="form-control" name="username" id="username" placeholder="Masukan Username">
			                    <span id="errorUsername"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-2 col-form-label">Password</label>
			                <div class="col-sm-10">
			                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukan Password">
			                    <span id="errorPassword"></span>
			                </div>
			            </div>
			            <div class="form-group row">
			                <label class="col-sm-2 col-form-label">Ulangi Password</label>
			                <div class="col-sm-10">
			                    <input type="password" class="form-control" id="ulangi_password" name="ulangi_password" placeholder="Ketik Ulangi Password">
			                    <span id="errorUlangiPassword"></span>
			                </div>
			            </div>
			            <div class="form-group row" id="formLevel">
			                <label class="col-sm-2 col-form-label">Level</label>
			                <div class="col-sm-10">
			                	<select name="level" id="level" class="form-control">
	                                <option value="">--Pilih Level--</option>
	                                <option value="hrd">HRD</option>
	                                <option value="owner">Owner</option>
                                <?php if ($this->user_level == "admin") :  ?>
	                                <option value="admin">Admin</option>
	                            <?php  endif; ?>
	                                <!-- <option value="admin">Admin</option> -->
	                            </select>
	                        </div>
		                    <span id="errorLevel"></span>
			            </div>
			            <!-- <div class="form-group row">
			                <label class="col-sm-2 col-form-label">Keterangan</label>
			                <div class="col-sm-10">
					            <textarea id="keterangan" name="keterangan" class="form-control" rows="5" style="resize: vertical;"></textarea>
					            <span id="errorKeterangan"></span>
			                </div>
			            </div> -->

	    			</div>
	    			<div class="col-md-4">

	    				<div class="row">
	    					<div class="col-md-1"></div>
	    					<div class="col-md-10">
			    				<div class="card rounded-card user-card">
		                            <div class="card-block">
		                                <div class="img-hover">
		                                    <img class="img-fluid img-circle" id="img_photo" src="assets/images/default/no_user.png" alt="user-img" style="height: 180px;">
		                                    <div class="img-overlay">
		                                        <span>
		                                            <a href="javascript:void(0);" title="Zoom Photo" id="btnZoomImg" class="btn btn-sm btn-success btn-outline-success">
		                                            	<i class="fa fa-search-plus"></i>
		                                            </a>
		                                            <a href="javascript:void(0);" title="Pilih Upload Photo" class="btn btn-sm btn-primary btn-outline-primary" id="ganti_photo" data-popup="lightbox">
		                                            	<i class="fa fa-upload"></i>
		                                              </a>
		                                            <a href="javascript:void(0);" title="Hapus Photo" id="hapus_photo" class="btn btn-sm btn-danger btn-outline-danger">
		                                            	<i class="fa fa-trash-o"></i>
		                                            </a>
		                                      	</span>
		                                    </div>

					                        <input name="photo" id="photo_user" type="file" style="display: none;">
					                        <input type="hidden" name="is_delete" id="is_delete" value="0">

		                                    <a href="assets/images/default/no_user.png" style="display: none;" data-toggle="lightbox" id="popUpImgOut" data-title="Photo Pengguna" data-footer="">
					                            <img src="assets/images/default/no_user.png" id="popUpImgIn" class="img-fluid" alt="">
					                        </a>
		                                </div>
		                              	<div class="user-content"><h4 class="">Photo Pengguna</h4></div>
		                            </div>
		                        </div>
					            
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
</div>

<?php assets_script_custom("users.js"); ?>