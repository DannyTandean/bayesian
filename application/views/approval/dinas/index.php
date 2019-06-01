<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Approval Perjalanan Dinas</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblDinasApproval" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tools</th>
                            <th>Tanggal</th>
                            <th>Nama Karyawan</th>
                            <th>Departemen</th>
                            <th>Status</th>
                            <th>Jabatan</th>
                            <th>Keterangan</th>
                            <th>Tanggal Dinas</th>
                            <th>Akhir Dinas</th>
                            <th>Lama Dinas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<?php echo modalSaveOpen(false,"md","success"); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>
    <p class="text-center">Apa Anda Yakin Untuk Melakukan Approval Pada Data ini ? </p>
      <div class="form-group">
            <input id="status" class="form-control" type="hidden" name="status">
            <div id="errorStatus"></div>
      </div>

	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>
<!-- print space -->
<?php echo modalSaveOpen("Print","lg"); ?>
<div class="card" id="asd">
<div class="card-block">
  <?php $defaultPhoto = base_url("/")."assets/images/default/no_file_.png"; ?>
  <table class="table invoice-table table-borderless" style="padding-left:0;">
        <tbody>
            <tr>
                <td><img src="<?php echo $defaultPhoto; ?>" class="m-b-10" alt="" height="50" width="50" id="logo"></td>
            </tr>
            <tr>
                <td id="namaPerusahaan"></td>
            </tr>
            <tr>
                <td id="alamat"></td>
            </tr>
            <tr>
                <td>Email : <a href="mailto:demo@gmail.com" target="_top" id="emailPerusahaan">demo@gmail.com</a>
                </td>
            </tr>
            <tr>
                <td>No Telpon : <span id="noTelp"></span> </td>
            </tr>
            <tr>
              <td>No Fax :  <span id="noFax"></span> </td>
            </tr>
        </tbody>
    </table>
  <div class="row invoive-info">
      <div class="col-md-6 col-sm-6">
          <h6>Order Information :</h6>
          <table class="table table-responsive invoice-table invoice-order table-borderless">
              <tbody>
                  <tr>
                      <th>Date : </th>
                      <td><span id="tanggal1"></span></td>
                  </tr>
                  <tr>
                      <th>Status :</th>
                      <td>
                          <span class="label label-success" id="status1"></span>
                      </td>
                  </tr>
                  <tr>
                      <th>Id :</th>
                      <td>
                          #<span id="id_dinas1"></span>
                      </td>
                  </tr>
              </tbody>
          </table>
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12">
          <div class="table-responsive">
              <table class="table  invoice-detail-table">
                  <tbody>
                      <tr>
                          <td>Nama Lengkap</td>
                          <td><span id="karyawan1"></span></td>
                      </tr>
                      <tr>
                          <td>Departemen</td>
                          <td><span id="departemen1"></span></td>
                      </tr>
                      <tr>
                          <td>Jabatan</td>
                          <td><span id="jabatan1"></span></td>
                      </tr>
                      <tr>
                          <td>Lama Dinas</td>
                          <td><span id="lama1"></span>Hari</td>
                      </tr>
                      <tr>
                          <td>Mulai Tanggal</td>
                          <td><span id="mulaiDinas1"></span></td>
                      </tr>
                      <tr>
                          <td>Sampai Tanggal</td>
                          <td><span id="akhirDinas1"></span></td>
                      </tr>
                      <tr>
                          <td>Untuk Keperluan</td>
                          <td><span id="keterangan1"></span></td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12">
          <p>Melalui Surat Ini Maka izin Cuti Saudara <b class="text-underline">Diterima</b>. Demikian Disampaikan Dan Dapat Dilaksanakan Dengan Baik.</p>
          <p>Terima Kasih</p>
          <br>
          <br>
          <p>Powered By Prima HRD System <?php echo date("Y"); ?></p>
      </div>
  </div>
  </div>
</div>
<?php echo modalSaveClose("Print","printing"); ?>
<?php assets_script_custom_approval("dinas.js"); ?>
