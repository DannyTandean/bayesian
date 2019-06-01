<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Approval Golongan</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblGolongan" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tools</th>
                            <th>Nama Golongan</th>
                            <th>Gaji</th>
                            <th>status</th>
                            <th>status info</th>
                            <th>Tunj Makan</th>
                          <!--   <th>Tunj Hadir</th> -->
                            <th>Tunj Trans</th>
                            <th>THR</th>
                          <!--   <th>T.Disiplin</th> -->
                            <th>Tunj Lain</th>
                           <!--  <th>Pot Absen</th> -->
                            <th>Pot BPJS</th>
                            <th>Pot Lain</th>
                            <th>Lembur Tetap</th>
                            <th>Gaji Bulanan</th>
                            <th>Keterangan</th>
                            <th>Create At</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<?php echo modalSaveOpen(false,"lg"); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>
    <div class="form-group">
          <input id="status" class="form-control" type="hidden" name="status">
          <input id="statusInfo" class="form-control" type="hidden" name="statusInfo">
          <input id="id_golongan" class="form-control" type="hidden" name="idGolongan">
          <div id="errorStatus"></div>
    </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>

<!-- Details space -->
<?php echo modalSaveOpen("Details","lg"); ?>
<div class="col-md-12">
  <?php $defaultPhoto1 = base_url("/")."assets/images/default/no_file_.png"; ?>
  <div class="card-header">
    <center><b><span id="golongan1"></span></b></center>
  </div>
  <div class="card-body">
    <div class="row form-group">
       <label class="col-md-3 col-form-label">Golongan</label>
       <div class="col-md-9"><span id="golongan2"></span><hr></div>
    </div>

    <div class="row form-group">
       <label class="col-md-3 col-label-form">Gaji</label>
       <div class="col-md-9"><span id="gaji2"></span><hr></div>
     </div>

      <div class="row form-group">
       <label class="col-md-3 col-label-form">Gaji Menit</label>
       <div class="col-md-9"><span id="gaji_menit2"></span><hr></div>
     </div>

      <div class="row form-group">
       <label class="col-md-3 col-label-form">Makan</label>
       <div class="col-md-9"><span id="makan2"></span><hr></div>
     </div>

      <div class="row form-group">
       <label class="col-md-3 col-label-form">Transport</label>
       <div class="col-md-9"><span id="transport2"></span><hr></div>
     </div>

      <div class="row form-group">
       <label class="col-md-3 col-label-form">Thr</label>
       <div class="col-md-9"><span id="thr2"></span><hr></div>
     </div>

      <div class="row form-group">
       <label class="col-md-3 col-label-form">Tunjangan lain</label>
       <div class="col-md-9"><span id="tunjangan_lain2"></span><hr></div>
     </div>

     <!--  <div class="row form-group">
       <label class="col-md-3 col-label-form">Absen</label>
       <div class="col-md-9"><span id="absen2"></span><hr></div>
     </div> -->

      <div class="row form-group">
       <label class="col-md-3 col-label-form">bpjs</label>
       <div class="col-md-9"><span id="bpjs2"></span><hr></div>
     </div>

      <div class="row form-group">
       <label class="col-md-3 col-label-form">Pot lain</label>
       <div class="col-md-9"><span id="pot_lain2"></span><hr></div>
     </div>

   <!--    <div class="row form-group">
       <label class="col-md-3 col-label-form">Lembur</label>
       <div class="col-md-9"><span id="lembur2"></span><hr></div>
     </div>
 -->
     <div class="row form-group">
       <label class="col-md-3 col-label-form">Lembur Tetap</label>
       <div class="col-md-9"><span id="lembur_tetap2"></span><hr></div>
     </div>

      <div class="row form-group">
       <label class="col-md-3 col-label-form">Gaji Bulanan</label>
       <div class="col-md-9"><span id="umk2"></span><hr></div>
     </div>
      <div class="row form-group">
       <label class="col-md-3 col-label-form">Keterangan</label>
       <div class="col-md-9"><span id="keterangan2"></span><hr></div>
     </div>
    </div>

  </div>

  <div class="card-footer font-weight-bold">
    <p> Approve : <span id="tglDetail"></span> <span class="float-right">Approve By : Owner </span> </p>
  </div>
</div>
<?php echo modalDetailClose(); ?>
<?php assets_script_custom("approvalowner/golongan.js"); ?>
