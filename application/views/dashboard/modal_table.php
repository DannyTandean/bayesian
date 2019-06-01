
<!-- for total karyawan modal table -->
<?php echo modalDetailOpen("modalTotalKaryawan","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableTotalKaryawan" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for total karyawan modal table -->

<!-- for hadir modal table -->
<?php echo modalDetailOpen("modalHadir","lg"); ?> <!-- for hadir dan total kehadiran karyawan -->
	<div class="dt-responsive table-responsive">
		<table id="tableHadir" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for hadir modal table -->

<!-- for mangkir modal table -->
<?php echo modalDetailOpen("modalMangkir","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableMangkir" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for mangkir modal table -->

<!-- for sakit modal table -->
<?php echo modalDetailOpen("modalSakit","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableSakit" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for sakit modal table -->

<!-- for izin modal table -->
<?php echo modalDetailOpen("modalIzin","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableIzin" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for izin modal table -->

<!-- for cuti modal table -->
<?php echo modalDetailOpen("modalCuti","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableCuti" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for cuti modal table -->

<!-- for off modal table -->
<?php echo modalDetailOpen("modalOff","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableOff" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for off modal table -->

<!-- for dinas modal table -->
<?php echo modalDetailOpen("modalDinas","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableDinas" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for dinas modal table -->

<!-- FOR KEHADIRAN PER SHIFT -->
<!-- for shift regular modal table -->

<?php foreach ($jadwal as $key => $value): ?>
	<?php echo modalDetailOpen("modal".$value->id_jadwal,"lg"); ?>
		<div class="dt-responsive table-responsive">
			<table id="<?php echo "table".$value->id_jadwal; ?>" class="table table-striped table-bordered table-sm" style="width: 100%;">
				<thead>
					<tr>
						<th>No</th>
						<th>Nik</th>
						<th>Nama</th>
						<th>Jabatan</th>
					</tr>
				</thead>
			</table>
		</div>
	<?php echo modalDetailClose() ?>
<?php endforeach; ?>

<?php echo modalDetailOpen("modalTakterjadwal","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tabletakterjadwal" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for shift regular modal table -->

<!-- for shift Pagi modal table -->
<?php echo modalDetailOpen("modalShiftPagi","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableShiftPagi" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for shift Pagi modal table -->

<!-- for shift Sore modal table -->
<?php echo modalDetailOpen("modalShiftSore","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableShiftSore" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for shift Sore modal table -->

<!-- for shift Malam modal table -->
<?php echo modalDetailOpen("modalShiftMalam","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableShiftMalam" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for shift Malam modal table -->

<!-- for shift Pagi12 modal table -->
<?php echo modalDetailOpen("modalShiftPagi12","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableShiftPagi12" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for shift Pagi12 modal table -->

<!-- for shift Malam12 modal table -->
<?php echo modalDetailOpen("modalShiftMalam12","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableShiftMalam12" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for shift Malam12 modal table -->

<!-- for shift Manual modal table -->
<?php echo modalDetailOpen("modalShiftManual","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableShiftManual" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for shift Manual modal table -->

<!-- for shift Extra modal table -->
<?php echo modalDetailOpen("modalShiftExtra","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableShiftExtra" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for shift Extra modal table -->
<!-- END FOR KEHADIRAN PER SHIFT -->

<!-- FOR DATA KETEPATAN KEHADIRAN -->
<!-- for hadir tepat modal table -->
<?php echo modalDetailOpen("modalHadirTepat","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableHadirTepat" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for hadir tepat modal table -->

<!-- for hadir terlambat modal table -->
<?php echo modalDetailOpen("modalHadirTerlambat","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableHadirTerlambat" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Shift</th>
					<th>Jam Masuk</th>
					<th>Terlambat</th>
				</tr>
			</thead>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for hadir terlambat modal table -->
<!-- END FOR DATA KETEPATAN KEHADIRAN -->

<!-- FOR KONTRAK KARYAWAN -->
<!-- for kontrak sisa 1 bulan modal table -->
<?php echo modalDetailOpen("modalKontrakSisa_1","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableKontrakSisa_1" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Awal Kontrak</th>
					<th>Akhir Kontrak</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for kontrak sisa 1 bulan modal table -->

<!-- for kontrak sisa 2 bulan modal table -->
<?php echo modalDetailOpen("modalKontrakSisa_2","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableKontrakSisa_2" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Awal Kontrak</th>
					<th>Akhir Kontrak</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for kontrak sisa 2 bulan modal table -->

<!-- for kontrak sisa 3 bulan modal table -->
<?php echo modalDetailOpen("modalKontrakSisa_3","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableKontrakSisa_3" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Awal Kontrak</th>
					<th>Akhir Kontrak</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for kontrak sisa 3 bulan modal table -->

<!-- for kontrak habis bulan modal table -->
<?php echo modalDetailOpen("modalKontrakHabis","lg"); ?>
	<div class="dt-responsive table-responsive">
		<table id="tableKontrakHabis" class="table table-striped table-bordered table-sm" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>Nik</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Awal Kontrak</th>
					<th>Akhir Kontrak</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
<?php echo modalDetailClose() ?>
<!-- end for kontrak habis bulan modal table -->
<!-- END FOR KONTRAK KARYAWAN -->
