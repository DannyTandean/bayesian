<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Calendar</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblCalendar" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Tanggal</th>
                            <th>Nama Event</th>
                            <th>Tipe</th>
                            <th>Ikut Potong Cuti</th>
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
            <label for="tanggal" class="block">Tanggal :</label>
            <input id="tanggal" class="form-control" type="text" placeholder="Select Your Date" name="tanggal">
            <div id="errorTanggal"></div>
	    </div>

      <div class="form-group">
            <label for="NamaEvent" class="block">Nama Event :</label>
            <input id="NamaEvent" name="event" type="text" class="form-control">
            <div id="errorNamaEvent"></div>
	    </div>

      <div class="form-group">
            <label for="Tipe" class="block">Tipe :</label>
            <select class="form-control" name="type" id="tipe">
              <option value="">-- Pilih Tipe Event --</option>
              <option value="Libur Nasional">Libur Nasional</option>
              <option value="Cuti Bersama"> Cuti Bersama</option>
              <option value="Memo">Memo/Reminder</option>
            </select>
            <div id="errorTipe"></div>
	    </div>
      <div class="form-group">
        <label for="ikut_potong">Ikut Potong Cuti</label>
        <select class="form-control" name="ikut_potong" id="ikutPotong">
          <option value="ya">Ya</option>
          <option value="tidak">Tidak</option>
        </select>
      </div>

	    <div class="form-group">
            <label for="keterangan" class="block">Keterangan :</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3" style="resize: vertical;"></textarea>
            <div id="errorKeterangan"></div>
	    </div>
	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>


<?php assets_script_custom("calendar.js"); ?>
