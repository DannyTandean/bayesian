$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblJadwalKaryawan").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data   "+btnTambah+btnRefresh,
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
		//load data
		ajax: {
			url: base_url+'aktivitas/jadwalkaryawan/ajax_list',
			type: 'POST',
		},

		order:[[3,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{
				data:'button_action',
				searchable:false,
				orderable:false,
			},
			{ data:'nama_jadwal' },
			{ data:'awal_masuk' },
			{ data:'masuk' },
			{ data:'akhir_masuk' },
			{ data:'keluar' },
			{ data:'akhir_keluar' },
			{ data:'max_jam_kerja' },
			{ data:'break_in' },
			{ data:'break_out' },
			{ data:'shift' },
			{ data:'break_punishment' },
			{ data:'username' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
	$("#tanggal").dateDropper( {
					dropWidth: 200,
					init_animation: "bounce",
					dropPrimaryColor: "#1abc9c",
					dropBorder: "1px solid #1abc9c",
					maxYear: "2050",
					format: "Y-m-d"
			});
});

function reloadTable() {
	$("#tblJadwalKaryawan").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;
$('.form-radio').hide()
$('#break').hide()
$('#tipedurasi').hide()
$('#punishment').hide()

$("#tipebreak").change(function(){
	if($("#tipebreak").val() == 0)
	{
	 $('.form-radio').hide(800)
	 $('#tipedurasi').hide(800)
	 $('#punishment').hide(800)
	}
	else{
	 $('.form-radio').show(800)
	 $('#tipedurasi').show(800)
	 $('#punishment').show(800)
	}
});

$('input[type=radio][name=breaktipe]').change(function() {
    if (this.value == 0) {
			$('#tipedurasi').show(800)
			$('#break').hide(800)
    }
    else {
			$('#tipedurasi').hide(800)
			$('#break').show(800)
    }
});

$('#maxjamkerja').on('input',function() {
	if ($(this).val() == 0) {
		$('#menit_kerja').html("")
	}
	else {
		$('#menit_kerja').html($(this).val()*60 + " Menit")
	}
});

$(document).on('click', '#btnRefresh', function(e) {
	e.preventDefault();
	reloadTable();
	$("#btnRefresh").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefresh").children().removeClass("fa-spin");
	}, 1000);
});

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Jadwal Karyawan");
	$('.form-radio').hide()
	$('#break').hide()
	$('#tipedurasi').hide()
	$('#punishment').hide()
	save_method = "add";
});

function btnEdit(id) {
	idData = id;
	$('.form-radio').hide()
	$('#break').hide()
	$('#tipedurasi').hide()
	$('#punishment').hide()

	$(".modal-title").text("Update Jadwal Karyawan");
	save_method = "update";

	$.post(base_url+"aktivitas/jadwalkaryawan/getId/"+idData,function(json) {
		if (json.status == true) {
				$("#modalForm").modal("show");
				$('#jadwal').val(json.data.nama_jadwal);
		    $("#masuk").val(json.data.masuk);
				$("#keluar").val(json.data.keluar);
				$('#shift').val(json.data.shift);
				$('#tipebreak').val(json.data.break);
				$('#awal_masuk').val(json.data.awal_masuk);
				$('#akhir_masuk').val(json.data.akhir_masuk);
				$('#akhirKeluar').val(json.data.akhir_keluar);
				$('#checkbox3').val(json.data.lewat_hari);
				$('#maxjamkerja').val(json.data.max_jam_kerja);
				$('#id_jadwal_1').val(json.data.id_jadwal);
				if (json.data.max_jam_kerja == 0) {
					$('#menit_kerja').html("")
				}
				else {
					$('#menit_kerja').html(json.data.max_jam_kerja*60 + " Menit")
				}
				if (json.data.lewat_hari == 1) {
					$('#checkbox3').prop('checked', true);
				}

				if (json.data.break == 1) {

					$('#punishment').show(800)
					$('#breakPunishment').val(json.data.break_punishment)

					if (json.data.break_option == 0) {
						$('#durasiya').prop('checked', true).val(0);
						$('#durasi').val(json.data.break_durasi)
						$('#tipedurasi').show(800)
						$('.form-radio').show(800)
					}
					else {
						$('.form-radio').show(800)
						$('#break').show(800)
						$('#tetap').prop('checked', true).val(1);
						$("#breakout").val(json.data.break_out);
						$("#breakin").val(json.data.break_in);
					}
				}
				else {
					$('.form-radio').hide(800)
					$('#tipedurasi').hide(800)
					$('#break').hide(800)
					$('#punishment').hide(800)
				}


		} else {
			$("#inputMessage").html(json.message);
			$('#id_jadwal_1').val("");
			$('#jadwal').val("");
			$("#masuk").val("");
			$("#keluar").val("");
			$("#breakout").val("");
			$("#breakin").val("");
			$('#awal_masuk').val("");
			$('#akhir_masuk').val("");
			$('#akhirKeluar').val("");
			$('#maxjamkerja').val("");
			// setTimeout(function() {
			// 	reloadTable();
			// 	$("#modalForm").modal("hide");
			// },1000);
		}
	});

}

$('#checkbox3').change(function() {
		if ($('#checkbox3').prop('checked') == true) {
			$('#checkbox3').val(1)
		}
		else {
			$('#checkbox3').val(0)
		}
});


$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'aktivitas/jadwalkaryawan/add';
	} else {
		url = base_url+'aktivitas/jadwalkaryawan/update/'+idData;
	}

	$("#modalButtonSave").attr("disabled",true);
	$("#modalButtonSave").html("Prosessing...<i class='fa fa-spinner fa-spin'></i>");
	$.ajax({
		url: url,
		type:'POST',
		data:$("#formData").serialize(),
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				if (save_method != "add") {
					setTimeout(()=>{
						$("#modalForm").modal("hide");
					},2500)
				}
				$("#inputMessage").html(json.message);


				/*swal({
			            title: json.message,
			            type: "success",
			            timer: 2000,
			            showConfirmButton: false
			        });*/

				setTimeout(function() {
					$("#formData")[0].reset();
					// $("#modalForm").modal("hide");
					$("#inputMessage").html("");
					reloadTable();
					$('.form-radio').hide()
					$('#break').hide()
					$('#tipedurasi').hide()
					$('#punishment').hide()
					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');

				}, 2500);
			} else {
				if (json.message != "") {
					$("#inputMessage").html(json.message);
				}
				else {
					$("#errorNamaJadwal").html(json.error.namaJadwal);
					$("#errorMasuk").html(json.error.masuk);
					$("#errorKeluar").html(json.error.keluar);
					$("#errorAwalMasuk").html(json.error.awal_masuk);
					$("#errorAkhirMasuk").html(json.error.akhir_masuk);
					$('#errorAkhirKeluar').html(json.error.akhir_keluar);
					$('#errorJamKerja').html(json.error.jam_kerja);
				}

				setTimeout(function(){
					$("#modalButtonSave").attr("disabled",false);
					$('#menit_kerja').html("");
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');
					$("#inputMessage").html("");
					$("#errorNamaJadwal").html("");
					$("#errorMasuk").html("");
					$("#errorKeluar").html("");
					$("#errorAwalMasuk").html("");
					$("#errorAkhirMasuk").html("");
					$('#errorAkhirKeluar').html("");
					$('#errorJamKerja').html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"aktivitas/jadwalkaryawan/getId/"+idData,function(json) {
		if (json.status == true) {
			swal({
					title: "Apakah anda yakin.?",
					html: "<span style='color:red;'>Data yang di <b>Hapus</b> tidak bisa dikembalikan lagi.</span>",
					type: "warning",
			width: 400,
				showCloseButton: true,
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Iya, Hapus",
					closeOnConfirm: false,
				// background: '#e9e9e9',

			}).then((result) => {
				if (result.value) {
					$.post(base_url+"aktivitas/jadwalkaryawan/delete/"+idData,function(json) {
					if (json.status == true) {
						swal({
											title: json.message,
											type: "success",
											// html: true,
											timer: 2000,
											showConfirmButton: false
									});
						reloadTable();
					} else {
						swal({
											title: json.message,
											type: "error",
											// html: true,
											timer: 1500,
											showConfirmButton: false
									});
						reloadTable();
					}
				});
				}
			});
		} else {
			reloadTable();
			swal({
		            title: json.message,
		            type: "error",
		            // html: true,
		            timer: 1000,
		            showConfirmButton: false
		        });
		}
	});
}
