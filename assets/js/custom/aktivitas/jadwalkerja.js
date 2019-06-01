
$(document).ready(function() {
	btnTambahSaatIni = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-mini' id='btnTambahSaatIni'><i class='fa fa-plus'></i> Tambah</button>";
    btnRefreshSaatIni = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-mini' id='btnRefreshSaatIni'><i class='fa fa-refresh'></i> Refresh</button>";

    btnTambahDepan = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-mini' id='btnTambahDepan'><i class='fa fa-plus'></i> Tambah</button>";
    btnRefreshDepan = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-warning btn-mini' id='btnRefreshDepan'><i class='fa fa-refresh'></i> Refresh</button>";

    // function datatable
    dataTableJadwalKerja("tblBulanLalu","aktivitas/jadwalkerja/ajax_list/lalu"); // table bulan lalu
    dataTableJadwalKerja("tblBulanSaatIniBerlalu","aktivitas/jadwalkerja/ajax_list/saat_ini/true","","","DESC"); // table bulan saat ini tanggal berlalu
    dataTableJadwalKerja("tblBulanSaatIni","aktivitas/jadwalkerja/ajax_list",btnTambahSaatIni,btnRefreshSaatIni); // table bulan saat ini
    dataTableJadwalKerja("tblBulanDepan","aktivitas/jadwalkerja/ajax_list/depan",btnTambahDepan,btnRefreshDepan); // table bulan depan

    btnRefreshKaryawanLalu = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-mini' id='btnRefreshKaryawanLalu'><i class='fa fa-refresh'></i> Refresh</button>";
	dataTableJumlahKaryawan("tblKaryawanBulanLalu",btnRefreshKaryawanLalu); // jumlah karyawan bulan lalu

	btnRefreshKaryawanSaatIni = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-mini' id='btnRefreshKaryawanSaatIni'><i class='fa fa-refresh'></i> Refresh</button>";
	btnAddKaryawanSaatIni = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-mini' id='btnAddKaryawanSaatIni'><i class='fa fa-plus'></i> Tambah Karyawan</button>";
	dataTableJumlahKaryawan("tblKaryawanBulanSaatIni",btnRefreshKaryawanSaatIni,btnAddKaryawanSaatIni); // jumlah karyawan bulan saat ini
	$("#btnAddKaryawanSaatIni").hide();

	btnRefreshKaryawanDepan = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-mini' id='btnRefreshKaryawanDepan'><i class='fa fa-refresh'></i> Refresh</button>";
	btnAddKaryawanDepan = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-mini' id='btnAddKaryawanDepan'><i class='fa fa-plus'></i> Tambah Karyawan</button>";
	dataTableJumlahKaryawan("tblKaryawanBulanDepan",btnRefreshKaryawanDepan,btnAddKaryawanDepan); // jumlah karyawan bulan saat ini
	$("#btnAddKaryawanDepan").hide();

});

function dataTableJadwalKerja(idTable,urlTable,btnTambah="",btnRefresh="",orderTgl="ASC") {
	$("#"+idTable).DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		oLanguage: {
            sProcessing: '<h1 style="color:blue; font-size:100px;"><span class="fa fa-refresh fa-spin"></span></h1>',
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
			url: base_url+urlTable,
			type: 'POST',
		},

		order:[[1, orderTgl],[2, 'ASC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal' },
			{ data:'grup' },
			{ data:'nama_shift' },
			{
				data:'button_opsi',
				searchable:false,
				orderable:false,
			},
		],
	});
}

$("#navKaryawan").click(function() {
	$("#tabKaryawan").show();
	$("#tabSecurity").hide();
});

$("#navSecurity").click(function() {
	$("#tabKaryawan").hide();
	$("#tabSecurity").show();
});

$("#tabBulanLalu").click(function() {
	$("#contentBulanSaatIni").hide();
	$("#contentBulanDepan").hide();
	$("#contentBulanLalu").show();
});

$("#tabBulanSaatIni").click(function() {
	$("#contentBulanDepan").hide();
	$("#contentBulanLalu").hide();
	$("#contentBulanSaatIni").show();
});

$("#tabBulanDepan").click(function() {
	$("#contentBulanSaatIni").hide();
	$("#contentBulanLalu").hide();
	$("#contentBulanDepan").show();
});

function reloadTable() {
	$("#tblBulanLalu").DataTable().ajax.reload(null,false);
	$("#tblBulanSaatIni").DataTable().ajax.reload(null,false);
	$("#tblBulanSaatIniBerlalu").DataTable().ajax.reload(null,false);
	$("#tblBulanDepan").DataTable().ajax.reload(null,false);
}

$(document).on('click', '#btnRefreshSaatIni', function(e) {
	e.preventDefault();
	reloadTable();
	$("#btnRefreshSaatIni").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefreshSaatIni").children().removeClass("fa-spin");
	}, 1000);
});

$(document).on('click', '#btnRefreshDepan', function(e) {
	e.preventDefault();
	reloadTable();
	$("#btnRefreshDepan").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefreshDepan").children().removeClass("fa-spin");
	}, 1000);
});

var save_method;
var idData;

$(document).on('click', "#btnTambahSaatIni", function(e) {
	e.preventDefault();
	$("#formData")[0].reset();
	$("#modalForm").modal("show");
	$(".modal-title").html("Tambah Jadwal Bulan Saat Ini");
	$(".modal-header").removeClass('modal-header-danger');
	$(".modal-header").addClass('modal-header-green');
	save_method = "add_bulan_saat_ini";
	$("#dataKabagGroup").hide();
	$("#id_kabag").val("");
	$("#otoritas_kabag").val("");
	$("#nama_kabag").val("");
	$("#no_induk_kabag").val("");
});

$(document).on('click', "#btnTambahDepan", function(e) {
	e.preventDefault();
	$("#formData")[0].reset();
	$("#modalForm").modal("show");
	$(".modal-title").html("Tambah Jadwal Bulan Depan");
	$(".modal-header").removeClass('modal-header-green');
	$(".modal-header").addClass('modal-header-danger');
	save_method = "add_bulan_depan";
	$("#dataKabagGroup").hide();
	$("#id_kabag").val("");
	$("#otoritas_kabag").val("");
	$("#nama_kabag").val("");
	$("#no_induk_kabag").val("");
});

var tglJadwal;
var idGroupJadwal;
var namaShiftJadwal;

//for tambah karyawan
var shiftIdJadwal; 
var tokenJadwal;

function btnEditSaatIni(tanggal,idGroup) {
	tglJadwal = tanggal;
	idGroupJadwal = idGroup;
	// namaShiftJadwal = nama_shift;
	var urlPath = base_url+'aktivitas/jadwalkerja/getData/'+tglJadwal+"/"+idGroupJadwal;
	$.post(urlPath, function(json) {
		if (json.status == true) {
			$("#tanggal").val(json.data.tanggal);
			$("#nama_group").val(json.data.group_id);
			$("#nama_shift").val(json.data.nama_shift);

			$.post(base_url+'aktivitas/jadwalkerja/checkGroupKabagJadwal/'+tglJadwal+"/"+idGroupJadwal,function(resp) {
				if (resp.status ==  true) {
					$("#id_kabag").val(resp.data.karyawan_id);
					$("#otoritas_kabag").val(resp.data.otoritas_kerja);
					$("#nama_kabag").val(resp.data.nama);
					$("#no_induk_kabag").val(resp.data.idfp);
					$("#nama_shift_kabag").val(resp.data.nama_shift);
					if (json.data.nama_shift == resp.data.nama_shift) {
						$("#sama_shift").prop("checked",true);
					} else {
						$("#sama_shift").prop("checked",false);
					}
					$("#dataKabagGroup").show(800);
				} else {
					$("#dataKabagGroup").hide(800);
					$("#id_kabag").val("");
					$("#otoritas_kabag").val("");
					$("#nama_kabag").val("");
					$("#no_induk_kabag").val("");
					$("#nama_shift_kabag").val("");
					$("#sama_shift").prop("checked",false);
				}
			});
		} else {
			swal({    
		            title: json.message,
		            type: "error",
		            timer: 2000,   
		            showConfirmButton: false 
		        });
			reloadTable();
			setTimeout(function() {
				$("#modalForm").modal("hide");
			},1000);
		}
	});

	$("#modalForm").modal("show");
	$(".modal-title").html("Update Jadwal Bulan Saat Ini");
	$(".modal-header").removeClass('modal-header-danger');
	$(".modal-header").addClass('modal-header-green');
	save_method = 'update_bulan_saat_ini';
}

function btnEditDepan(tanggal,idGroup) {
	tglJadwal = tanggal;
	idGroupJadwal = idGroup;
	// namaShiftJadwal = nama_shift;
	var urlPath = base_url+'aktivitas/jadwalkerja/getData/'+tglJadwal+"/"+idGroupJadwal;
	$.post(urlPath, function(json) {
		if (json.status == true) {
			$("#tanggal").val(json.data.tanggal);
			$("#nama_group").val(json.data.group_id);
			$("#nama_shift").val(json.data.nama_shift);

			$.post(base_url+'aktivitas/jadwalkerja/checkGroupKabagJadwal/'+tglJadwal+"/"+idGroupJadwal,function(resp) {
				if (resp.status ==  true) {
					$("#id_kabag").val(resp.data.karyawan_id);
					$("#otoritas_kabag").val(resp.data.otoritas_kerja);
					$("#nama_kabag").val(resp.data.nama);
					$("#no_induk_kabag").val(resp.data.idfp);
					$("#nama_shift_kabag").val(resp.data.nama_shift);
					if (json.data.nama_shift == resp.data.nama_shift) {
						$("#sama_shift").prop("checked",true);
					} else {
						$("#sama_shift").prop("checked",false);
					}
					$("#dataKabagGroup").show(800);
				} else {
					$("#dataKabagGroup").hide(800);
					$("#id_kabag").val("");
					$("#otoritas_kabag").val("");
					$("#nama_kabag").val("");
					$("#no_induk_kabag").val("");
					$("#nama_shift_kabag").val("");
					$("#sama_shift").prop("checked",false);
				}
			});
			
		} else {
			swal({    
		            title: json.message,
		            type: "error",
		            timer: 2000,   
		            showConfirmButton: false 
		        });
			reloadTable();
			setTimeout(function() {
				$("#modalForm").modal("hide");
			},1000);
		}
	});

	$("#modalForm").modal("show");
	$(".modal-title").html("Update Jadwal Bulan Depan");
	$(".modal-header").removeClass('modal-header-green');
	$(".modal-header").addClass('modal-header-danger');
	save_method = 'update_bulan_depan';
}

$("#nama_group").change(function() {
	var val = $(this).val();
	if (val != "") {
		$.post(base_url+'aktivitas/jadwalkerja/checkGroupKabag/'+val,function(json) {
			if (json.status ==  true) {
				$("#id_kabag").val(json.data.id);
				$("#otoritas_kabag").val(json.data.otoritas);
				$("#nama_kabag").val(json.data.nama);
				$("#no_induk_kabag").val(json.data.idfp);
				$("#dataKabagGroup").show(800);
			} else {
				$("#dataKabagGroup").hide(800);
				$("#id_kabag").val("");
				$("#otoritas_kabag").val("");
				$("#nama_kabag").val("");
				$("#no_induk_kabag").val("");
			}
		});
	} else {
		$("#dataKabagGroup").hide(800);
		$("#id_kabag").val("");
		$("#otoritas_kabag").val("");
		$("#nama_kabag").val("");
		$("#no_induk_kabag").val("");
	}
});

$("#nama_shift").change(function() {
	if ($("#nama_kabag").val() != "") {
		if ($(this).val() == "OFF") {
			$("#nama_shift_kabag").val("OFF");
			$("#sama_shift").prop("checked",true);
		} else {
			$("#nama_shift_kabag").val("");
		}
	} else {
		$("#nama_shift_kabag").val("");
	}
	$("#sama_shift").prop("checked",false);
});

$("#sama_shift").change(function() {
	shift = $("#nama_shift").val();
	if (shift != "") {
		if ($(this).is(':checked')) {
			// alert("data di checked");
			$("#nama_shift_kabag").val(shift);
		} else {
			$("#nama_shift_kabag").val("");
			$(this).prop("checked",false);
		}
	}
});

$("#nama_shift_kabag").change(function() {
	shift = $("#nama_shift").val();
	if (shift != "") {

		if ($(this).val() == "OFF") {
			$("#nama_shift").val("OFF");
			$("#sama_shift").prop("checked",true);
		} else {
			if (shift == "OFF" && $(this).val() != "OFF") {
				$("#nama_shift").val("");
			}
		}

		if (shift == $(this).val()) {
			$("#sama_shift").prop("checked",true);
		} else {
			$("#sama_shift").prop("checked",false);
		}
	} else {
		$("#sama_shift").prop("checked",false);
	}
});

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add_bulan_saat_ini") {
		url = base_url+"aktivitas/jadwalkerja/checkInsertDateNow";
	} else if (save_method == "update_bulan_saat_ini") {
		url = base_url+"aktivitas/jadwalkerja/updateBulanSaatIni/"+tglJadwal+"/"+idGroupJadwal;

	} else if (save_method == "add_bulan_depan") {
		url = base_url+"aktivitas/jadwalkerja/insertBulanDepan";
	} else if (save_method == "update_bulan_depan") {
		url = base_url+"aktivitas/jadwalkerja/updateBulanDepan/"+tglJadwal+"/"+idGroupJadwal;
	}

	if (save_method == "add_bulan_saat_ini") {
		$.ajax({
			url: url,
			type:'POST',
			data:$("#formData").serialize(),
			dataType:'JSON',
			success: function(json) {
				if (json.status == true) {
					if (json.message == "date_now") {
						swal({
							  	title: 'Apakah Anda Yakin.?',
							  	html: json.data,
							  	type: 'warning',
							  	showCancelButton: true,
							  	confirmButtonColor: '#3085d6',
							  	cancelButtonColor: '#d33',
							  	confirmButtonText: 'Iya, Tambah Data'
							}).then((result) => {
							  	if (result.value) {
							    	$.ajax({
										url: base_url+"aktivitas/jadwalkerja/insertBulanSaatIni",
										type:'POST',
										data:$("#formData").serialize(),
										dataType:'JSON',
										success: function(json) {
											if (json.status == true) {
												swal({    
											            title: json.message,
											            type: "success",
											            timer: 2500,   
											            showConfirmButton: false 
											        });
												reloadTable();
												setTimeout(function() {
													$("#modalForm").modal("hide");
												},2000);
											} else {
												swal({
											            title: "Error Form",
											            type: "error",
											            html: json.message,
											        });
											}
										}
									});
							  	}
							});
					} else if(json.message == "date_next") {
						$.ajax({
							url: base_url+"aktivitas/jadwalkerja/insertBulanSaatIni",
							type:'POST',
							data:$("#formData").serialize(),
							dataType:'JSON',
							success: function(json) {
								if (json.status == true) {
									swal({    
								            title: json.message,
								            type: "success",
								            timer: 2500,   
								            showConfirmButton: false 
								        });
									reloadTable();
									setTimeout(function() {
										$("#modalForm").modal("hide");
									},2000);
								} else {
									swal({
								            title: "Error Form",
								            type: "error",
								            html: json.message,
								        });
								}
							}
						});
					} else if (json.message == "date_prev") {
						swal({
				            title: "Error Form",
				            type: "error",
				            html: json.data,
				        });
					}
				} else {
					swal({
				            title: "Error Form",
				            type: "error",
				            html: json.message,
				        });
				}
			}
		});
	} else {
		$.ajax({
			url: url,
			type:'POST',
			data:$("#formData").serialize(),
			dataType:'JSON',
			success: function(json) {
				if (json.status == true) {
					swal({    
				            title: json.message,
				            type: "success",
				            timer: 2500,   
				            showConfirmButton: false 
				        });
					reloadTable();
					setTimeout(function() {
						$("#modalForm").modal("hide");
					},2000);
				} else {
					swal({
				            title: "Error Form",
				            type: "error",
				            html: json.message,
				        });
				}
			}
		});
	}	
});

/* show data jumlah karyawan*/
function btnKaryawanLalu(tanggal,idGroup,nama_shift) {
	tglJadwal = tanggal;
	idGroupJadwal = idGroup;
	namaShiftJadwal = nama_shift;

	$("#tblKaryawanBulanLalu").css({
		width: "100%!important",
	});

	var urlPath = base_url+'aktivitas/jadwalkerja/getData/'+tglJadwal+"/"+idGroupJadwal+"/"+namaShiftJadwal;
	$.post(urlPath, function(json) {
		if (json.status == true) {
			$("#tglLalu").html(json.data.tanggal);
			$("#groupLalu").html(json.data.grup);
			$("#shiftLalu").html(json.data.nama_shift);
		}
	});

	var tableJumlahKaryawan = $("#tblKaryawanBulanLalu").DataTable();
	var url = base_url+"aktivitas/jadwalkerja/ajax_list_jumlah_karyawan/"+tglJadwal+"/"+idGroupJadwal+"/"+namaShiftJadwal;
	tableJumlahKaryawan.ajax.url(url).load();

	$("#dataTableBulanLalu").hide();
	$("#dataTableKarywanBulanLalu").show();
}

$(".close-table-karyawan-lalu").click(function() {
	$("#dataTableKarywanBulanLalu").hide(800);
	$("#dataTableBulanLalu").show(800);
});

$(document).on('click', '#btnRefreshKaryawanLalu', function(e) {
	e.preventDefault();
	$("#tblKaryawanBulanLalu").DataTable().ajax.reload(null,false);
	$("#btnRefreshKaryawanLalu").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefreshKaryawanLalu").children().removeClass("fa-spin");
	}, 1000);
});

function btnKaryawanSaatIni(tanggal,idGroup,nama_shift) {
	tglJadwal = tanggal;
	idGroupJadwal = idGroup;
	namaShiftJadwal = nama_shift;

	$("#tblKaryawanBulanSaatIni").css({
		width: "100%!important",
	});

	var urlPath = base_url+'aktivitas/jadwalkerja/getData/'+tglJadwal+"/"+idGroupJadwal+"/"+namaShiftJadwal;
	$.post(urlPath, function(json) {
		if (json.status == true) {
			$("#tglSaatIni").html(json.data.tanggal);
			$("#groupSaatIni").html(json.data.grup);
			$("#shiftSaatIni").html(json.data.nama_shift);
			if (json.data_karyawan == "") {
				$("#btnAddKaryawanSaatIni").hide();
			} else {
				$("#btnAddKaryawanSaatIni").show();
			}
			shiftIdJadwal = json.data.shift_id;
			tokenJadwal = json.data.token;

			if (json.data_karyawan != "") {
				var option = '<option value="">--Pilih Karyawan--</option>';
				$.each(json.data_karyawan,function(i,v) {
					option += '<option value="'+v.id+'">'+v.nama+'</option>';
				});
				$("#tambahKaryawan").select2({
					dropdownParent: $('#modalTambahKaryawan')
				});
				$("#tambahKaryawan").html(option);
			}
		}
	});

	var tableJumlahKaryawan = $("#tblKaryawanBulanSaatIni").DataTable();
	var url = base_url+"aktivitas/jadwalkerja/ajax_list_jumlah_karyawan/"+tglJadwal+"/"+idGroupJadwal+"/"+namaShiftJadwal;
	tableJumlahKaryawan.ajax.url(url).load();

	$("#dataTableBulanSaatIni").hide(800);
	$("#dataTableKarywanBulanSaatini").show(800);
}

$(document).on('click', '#btnAddKaryawanSaatIni', function(e) { // tambah data karyawan jadwal bulan saat ini
	e.preventDefault();
	$(".modal-title").html("Tambah data karyawan jadwal kerja bulan saat ini");
	$(".modal-header").removeClass('modal-header-green');
	$(".modal-header").removeClass('modal-header-danger');
	$(".modal-header").addClass('modal-header-success');
	$("#modalTambahKaryawan").modal("show");
	$("#tambahKaryawan").val("").trigger('change');
});

$("#btnSimpanTambahKaryawan").click(function() {
	var dataInput = {
		tanggal : tglJadwal,
		tambahKaryawan: $("#tambahKaryawan").val(),
		nama_group: idGroupJadwal,
		shift_id: shiftIdJadwal,
		nama_shift: namaShiftJadwal,
		token: tokenJadwal
	}
	$.post(base_url+'aktivitas/jadwalkerja/addManualKaryawanJadwal', dataInput, function(json) {
		/*optional stuff to do after success */
		if (json.status == true) {
			swal({    
		            title: json.message,
		            type: "success",
		            timer: 2500,   
		            showConfirmButton: false 
		        });
			reloadTableKaryawanSaatIni();
			setTimeout(function() {
				$("#modalTambahKaryawan").modal("hide");

				var urlPath = base_url+'aktivitas/jadwalkerja/getData/'+tglJadwal+"/"+idGroupJadwal+"/"+namaShiftJadwal;
				$.post(urlPath, function(json) {
					if (json.status == true) {
						if (json.data_karyawan != "") {
							var option = '<option value="">--Pilih Karyawan--</option>';
							$.each(json.data_karyawan,function(i,v) {
								option += '<option value="'+v.id+'">'+v.nama+'</option>';
							});
							$("#tambahKaryawan").select2({
								dropdownParent: $('#modalTambahKaryawan')
							});
							$("#tambahKaryawan").html(option);
						} else {
							$("#tambahKaryawan").html('<option value="">--Tidak ada Karyawan--</option>');
						}
					}
				});

			},2000);
		} else {
			swal({
		            title: "Error Form",
		            type: "error",
		            html: json.message,
		        });
		}
	});
});

$(".close-table-karyawan-saatini").click(function() {
	$("#dataTableKarywanBulanSaatini").hide(800);
	$("#dataTableBulanSaatIni").show(800);
});

$(document).on('click', '#btnRefreshKaryawanSaatIni', function(e) {
	e.preventDefault();
	$("#tblKaryawanBulanSaatIni").DataTable().ajax.reload(null,false);
	$("#btnRefreshKaryawanSaatIni").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefreshKaryawanSaatIni").children().removeClass("fa-spin");
	}, 1000);
});

function reloadTableKaryawanSaatIni() {
	$("#tblBulanSaatIni").DataTable().ajax.reload(null,false);
	$("#tblKaryawanBulanSaatIni").DataTable().ajax.reload(null,false);
}

function btnHapusKaryawanSaatIni(id) {
	idData = id;
	hapusDataKaryawanJadwal(idData);
}

function hapusDataKaryawanJadwal(id) {
	$.post(base_url+"aktivitas/jadwalkerja/getIdJadwal/"+idData,function(json) {
		if (json.status == true) {
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>No Induk : <i>"+json.data.idfp+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Shift : <i>"+json.data.nama_shift+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tanggal Lahir : <i>"+json.data.tgl_lahir_indo+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Jenis Kelamin : <i>"+json.data.kelamin+"</i></small></li><br>";
				pesan += "</div>";

		    swal({
		        title: "Apakah anda yakin.?",
		        html: "<span style='color:red;'>Data yang di <b>Hapus</b> tidak bisa dikembalikan lagi.</span>"+pesan,
		        type: "warning",
				width: 400,
  				showCloseButton: true,
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Iya, Hapus",
		        // closeOnConfirm: false,
  				// background: '#e9e9e9',

		    }).then((result) => {
		    	if (result.value) {
		    		$.post(base_url+"aktivitas/jadwalkerja/deletekaryawan/"+idData,function(resp) {
						if (resp.status == true) {
							swal({
						            title: resp.message,
						            type: "success",
						            // html: true,
						            timer: 2000,
						            showConfirmButton: false
						        });
							reloadTableKaryawanSaatIni();
						} else {
							swal({
						            title: resp.message,
						            type: "error",
						            // html: true,
						            timer: 1500,
						            showConfirmButton: false
						        });
							reloadTableKaryawanSaatIni();
						}
					});
		    	}
		    });
		} else {
			reloadTableKaryawanSaatIni();
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

function btnKaryawanDepan(tanggal,idGroup,nama_shift) {
	tglJadwal = tanggal;
	idGroupJadwal = idGroup;
	namaShiftJadwal = nama_shift;

	$("#tblKaryawanBulanDepan").css({
		width: "100%!important",
	});

	var urlPath = base_url+'aktivitas/jadwalkerja/getData/'+tglJadwal+"/"+idGroupJadwal+"/"+namaShiftJadwal;
	$.post(urlPath, function(json) {
		if (json.status == true) {
			$("#tglDepan").html(json.data.tanggal);
			$("#groupDepan").html(json.data.grup);
			$("#shiftDepan").html(json.data.nama_shift);
		}
	});

	var tableJumlahKaryawan = $("#tblKaryawanBulanDepan").DataTable();
	var url = base_url+"aktivitas/jadwalkerja/ajax_list_jumlah_karyawan/"+tglJadwal+"/"+idGroupJadwal+"/"+namaShiftJadwal;
	tableJumlahKaryawan.ajax.url(url).load();

	$("#dataTableBulanDepan").hide();
	$("#dataTableKarywanBulanDepan").show();
}

$(".close-table-karyawan-depan").click(function() {
	$("#dataTableKarywanBulanDepan").hide(800);
	$("#dataTableBulanDepan").show(800);
});

$(document).on('click', '#btnRefreshKaryawanDepan', function(e) {
	e.preventDefault();
	$("#tblKaryawanBulanDepan").DataTable().ajax.reload(null,false);
	$("#btnRefreshKaryawanDepan").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefreshKaryawanDepan").children().removeClass("fa-spin");
	}, 1000);
});

function dataTableJumlahKaryawan(idTable,btnRefresh="",btnAdd=false) {
	if (!btnAdd) {
		btnAdd = "";
	}
	$("#"+idTable).DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		oLanguage: {
            sProcessing: '<h1 style="color:blue; font-size:100px;"><span class="fa fa-refresh fa-spin"></span></h1>',
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data   "+btnAdd+" &nbsp; "+btnRefresh,
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
		//load data
		ajax: {
			url: base_url+"aktivitas/jadwalkerja/ajax_list_jumlah_karyawan",
			type: 'POST',
		},

		order:[[2,'ASC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{
				data:'button_opsi',
				searchable:false,
				orderable:false,
			},
			{ data:'nama' },
			{ data:'idfp' },
			{ data:'nama_shift' },
			{ data:'tgl_lahir' },
			{ data:'kelamin' },
			{ data:'jabatan' },
		],
	});
}

$(document).ready(function(){

	$("#karyawan_ganti").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"aktivitas/jadwalkerja/allKaryawanAjax/1", // semua karyawan
		   	type: "post",
		   	dataType: 'json',
		   	delay: 250,
		   	data: function (params) {
		    	return {
		      		searchTerm: params.term // search term
		    	};
		   	},
		   	processResults: function (response) {
		     	return {
		        	results: response
		     	};
		   	},
		   	cache: true
		},
		dropdownParent: $('#modalGanti')
	});
});

var idJadwal;
function btnGanti(id) {
	$("#errorKaryawanGanti").html("");
	idJadwal = id;
	$.post(base_url+'/aktivitas/jadwalkerja/getIdJadwal/'+idJadwal, function(json) {
		/*optional stuff to do after success */
		if (json.status == true) {
			$(".modal-title").html("Form ganti jadwal karyawan bulan saat ini");
			$(".modal-header").removeClass('modal-header-green');
			$(".modal-header").removeClass('modal-header-danger');
			$(".modal-header").addClass('modal-header-success');
			$("#modalGanti").modal("show");

			var foto = json.data.foto;
			$("#detailPhotoUtama").attr("src", foto);
			$("#detailPopUpOutUtama").attr("href", foto);
			$("#detailPopUpInUtama").attr("src", foto);
			$("#namaUtama").html(json.data.nama);
			$("#noIndukUtama").html(json.data.idfp);
			$("#tglLahirUtama").html(json.data.tgl_lahir_indo);
			$("#kelaminUtama").html(json.data.kelamin);
			$("#jabatanUtama").html(json.data.jabatan);
			$("#groupUtama").html(json.data.grup);
			$("#karyawanId").val(json.data.karyawan_id);

			if (json.data.ganti_karyawan_id != null) {
				$("#karyawan_ganti").empty().append('<option value="'+json.data.ganti_karyawan_id+'">'+json.data.nama_karyawan_ganti+'</option>').val(json.data.ganti_karyawan_id).trigger('change');
				$("#errorKaryawanGanti").html("");
				$("#keterangan").val(json.data.keterangan_ganti);
			} else {
				$("#karyawan_ganti").val("").trigger('change');
				$("#errorKaryawanGanti").html("");
				$("#keterangan").val("");
			}
		} else {
			swal({    
		            title: json.message,
		            type: "error",
		            timer: 2500,   
		            showConfirmButton: false 
		        });
			reloadTable();
			setTimeout(function() {
				$("#dataTableKarywanBulanSaatini").hide(800);
				$("#dataTableBulanSaatIni").show(800);
			},2000);
		}
	});
}

$("#karyawan_ganti").change(function() {
	var val = $(this).val();
	if (val != "" || val != null) {
		console.log(val);
		$.post(base_url+"/aktivitas/jadwalkerja/getIdKaryawan/"+val,function(json) {
			if (json.status == true) {
				$("#dataKaryawanGanti").show(800);
				if (val != $("#karyawanId").val()) {
					var foto = json.data.foto;
					$("#detailPhotoGanti").attr("src", foto);
					$("#detailPopUpOutGanti").attr("href", foto);
					$("#detailPopUpInGanti").attr("src", foto);
					$("#namaGanti").html(json.data.nama);
					$("#noIndukGanti").html(json.data.idfp);
					$("#tglLahirGanti").html(json.data.tgl_lahir_indo);
					$("#kelaminGanti").html(json.data.kelamin);
					$("#jabatanGanti").html(json.data.jabatan);
					$("#groupGanti").html(json.data.grup);
					$("#errorKaryawanGanti").html("");
					$("#errorKaryawanGanti").css("margin-bottom", "0px");
				} else {
					swal({    
				            title: "Karyawan Ganti tidak boleh sama dengan karyawan utama.!",
				            type: "error",
				            timer: 2500,   
				            showConfirmButton: false 
				        });
					$("#karyawan_ganti").val("").trigger('change');
				}
			} else {
				if (val != null) {
					$("#errorKaryawanGanti").html(json.message);
				} else {
					$("#errorKaryawanGanti").html("");
				}
				$("#errorKaryawanGanti").css("margin-bottom", "-40px");
				var srcPhotoDefault = base_url+"assets/images/default/no_user.png";
				$("#dataKaryawanGanti").hide();
				$("#detailPhotoGanti").attr("src", srcPhotoDefault);
				$("#detailPopUpOutGanti").attr("href", srcPhotoDefault);
				$("#detailPopUpInGanti").attr("src", srcPhotoDefault);
				$("#namaGanti").html("");
				$("#noIndukGanti").html("");
				$("#tglLahirGanti").html("");
				$("#kelaminGanti").html("");
				$("#jabatanGanti").html("");
				$("#groupGanti").html("");
			}
		});
	} else {
		$("#dataKaryawanGanti").hide(800);
		$("#errorKaryawanGanti").html("");
		var srcPhotoDefault = base_url+"assets/images/default/no_user.png";
		$("#detailPhotoGanti").attr("src", srcPhotoDefault);
		$("#detailPopUpOutGanti").attr("href", srcPhotoDefault);
		$("#detailPopUpInGanti").attr("src", srcPhotoDefault);
		$("#namaGanti").html("");
		$("#noIndukGanti").html("");
		$("#tglLahirGanti").html("");
		$("#kelaminGanti").html("");
		$("#jabatanGanti").html("");
		$("#groupGanti").html("");
	}
		$("#modalGanti").css('overflow-y', 'scroll');
});

$("#btnZoomUtama").click(function() {
	$("#detailPopUpOutUtama").click();
});

$("#btnZoomGanti").click(function() {
	$("#detailPopUpOutGanti").click();
});

$("#btnSimpanGanti").click(function() {
	$.ajax({
		url: base_url+'aktivitas/jadwalkerja/gantiKaryawanSave/'+idJadwal,
		type: 'POST',
		dataType: 'JSON',
		data: $("#formDataGanti").serialize(),
	})
	.done(function(json) {
		console.log("success");
		if (json.status == true) {
			swal({    
		            title: json.message,
		            type: "success",
		            timer: 2500,   
		            showConfirmButton: false 
		        });
			$("#tblKaryawanBulanSaatIni").DataTable().ajax.reload(null,false);
			// $("#tblKaryawanBulanDepan").DataTable().ajax.reload(null,false);
			setTimeout(function() {
				$("#modalGanti").modal("hide");
			},2000);
		} else {
			swal({
		            title: "Error Form",
		            type: "error",
		            html: json.message,
		        });
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
});

$("#tabTglSudahLalu").click(function() {
	$("#tglLewat").show();
	$("#tglKedepan").hide();
});

$("#tabTglKedepan").click(function() {
	$("#tglLewat").hide();
	$("#tglKedepan").show();
});

// SECURItY section
/*For Security Content*/
$("#tabBulanLaluSecurity").click(function() {
	$("#contentBulanSaatIniSecurity").hide();
	$("#contentBulanDepanSecurity").hide();
	$("#contentBulanLaluSecurity").show();
});

$("#tabBulanSaatIniSecurity").click(function() {
	$("#contentBulanDepanSecurity").hide();
	$("#contentBulanLaluSecurity").hide();
	$("#contentBulanSaatIniSecurity").show();
});

$("#tabBulanDepanSecurity").click(function() {
	$("#contentBulanSaatIniSecurity").hide();
	$("#contentBulanLaluSecurity").hide();
	$("#contentBulanDepanSecurity").show();
});

$(document).ready(function() {
	btnTambahSaatIniSecurity = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-mini' id='btnTambahSaatIniSecurity'><i class='fa fa-plus'></i> Tambah</button>";
    btnRefreshSaatIniSecurity = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-mini' id='btnRefreshSaatIniSecurity'><i class='fa fa-refresh'></i> Refresh</button>";

    btnTambahDepanSecurity = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-mini' id='btnTambahDepanSecurity'><i class='fa fa-plus'></i> Tambah</button>";
    btnRefreshDepanSecurity = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-warning btn-mini' id='btnRefreshDepanSecurity'><i class='fa fa-refresh'></i> Refresh</button>";
	// datatable jadwal security bulan lalu
	dataTableSecurity("tblBulanLaluSecurity","lalu","ASC");
	// datatable jadwal security Saat ini tanggal sudah lewat
	dataTableSecurity("tblBulanSaatIniBerlaluSecurity","saat_ini/true","DESC");
	// datatable jadwal security Saat ini tanggal sekarang dan seterusnya
	dataTableSecurity("tblBulanSaatIniSecurity","saat_ini","ASC",btnTambahSaatIniSecurity,btnRefreshSaatIniSecurity);
	// datatable jadwal security Saat ini tanggal sudah lewat
	dataTableSecurity("tblBulanDepanSecurity","depan","ASC",btnTambahDepanSecurity,btnRefreshDepanSecurity);

});

function dataTableSecurity(idTable,urlPath,orderTgl="ASC",btnTambah="",btnRefresh="") {
	$("#"+idTable).DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		oLanguage: {
            sProcessing: '<h1 style="color:blue; font-size:100px;"><span class="fa fa-refresh fa-spin"></span></h1>',
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
			url: base_url+"aktivitas/jadwalkerja/ajax_list_security/"+urlPath,
			type: 'POST',
		},

		order:[[2,orderTgl]],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{
				data:'button_opsi',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'idfp' },
			{ data:'nama_shift' },
			{ data:'tgl_lahir' },
			{ data:'kelamin' },
			{ data:'jabatan' },
		],
	});
}

function reloadTablesSecurity() {
	$("#tblBulanLaluSecurity").DataTable().ajax.reload(null,false);
	$("#tblBulanSaatIniSecurity").DataTable().ajax.reload(null,false);
	$("#tblBulanSaatIniBerlaluSecurity").DataTable().ajax.reload(null,false);
	$("#tblBulanDepanSecurity").DataTable().ajax.reload(null,false);
}

$(document).on('click', '#btnRefreshSaatIniSecurity', function(e) {
	e.preventDefault();
	reloadTablesSecurity();
	$("#btnRefreshSaatIniSecurity").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefreshSaatIniSecurity").children().removeClass("fa-spin");
	}, 1000);
});

$(document).on('click', '#btnRefreshDepanSecurity', function(e) {
	e.preventDefault();
	reloadTablesSecurity();
	$("#btnRefreshDepanSecurity").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefreshDepanSecurity").children().removeClass("fa-spin");
	}, 1000);
});

$("#tabTglSudahLaluSecurity").click(function() {
	$("#tglLewatSecurity").show();
	$("#tglKedepanSecurity").hide();
});

$("#tabTglKedepanSecurity").click(function() {
	$("#tglLewatSecurity").hide();
	$("#tglKedepanSecurity").show();
});

$(document).ready(function() {
	$("#karyawan_security").select2({
		placeholder: '-- Pilih Security--',
		ajax: {
		   	url: base_url+"aktivitas/jadwalkerja/allKaryawanAjaxSecurity",
		   	type: "post",
		   	dataType: 'json',
		   	delay: 250,
		   	data: function (params) {
		    	return {
		      		searchTerm: params.term // search term
		    	};
		   	},
		   	processResults: function (response) {
		     	return {
		        	results: response
		     	};
		   	},
		   	cache: true
		},
		dropdownParent: $('#modalSecurity')
	});
});

var save_method_security;
var idSecurity;

$(document).on('click', "#btnTambahSaatIniSecurity", function(e) {
	e.preventDefault();
	$("#formDataSecurity")[0].reset();
	$("#modalSecurity").modal("show");
	$(".modal-title").html("Tambah Jadwal Security Bulan Saat Ini");
	$(".modal-header").removeClass('modal-header-danger');
	$(".modal-header").addClass('modal-header-green');
	$("#karyawan_security").val("").trigger('change');
	save_method_security = "add_bulan_saat_ini";
});

$(document).on('click', "#btnTambahDepanSecurity", function(e) {
	e.preventDefault();
	$("#formDataSecurity")[0].reset();
	$("#modalSecurity").modal("show");
	$(".modal-title").html("Tambah Jadwal Security Bulan Depan");
	$(".modal-header").removeClass('modal-header-green');
	$(".modal-header").addClass('modal-header-danger');
	$("#karyawan_security").val("").trigger('change');
	save_method_security = "add_bulan_depan";
});

$("#karyawan_security").change(function() {
	var val = $(this).val();
	if (val != "") {
		$.post(base_url+"/aktivitas/jadwalkerja/getIdKaryawan/"+val,function(json) {
			if (json.status == true) {
				$("#dataSecurity").show(800);

				var foto = json.data.foto;
				$("#detailPhotoSecurity").attr("src", foto);
				$("#detailPopUpOutSecurity").attr("href", foto);
				$("#detailPopUpInSecurity").attr("src", foto);
				$("#namaSecurity").html(json.data.nama);
				$("#noIndukSecurity").html(json.data.idfp);
				$("#tglLahirSecurity").html(json.data.tgl_lahir_indo);
				$("#kelaminSecurity").html(json.data.kelamin);
				$("#jabatanSecurity").html(json.data.jabatan);
				$("#groupSecurity").html(json.data.grup);
				$("#errorKaryawanSecurity").html("");
				$("#errorKaryawanSecurity").css("margin-bottom", "0px");
			} else {
				$("#errorKaryawanSecurity").html(json.message);
				// empty detail security
				emptyDetailSecurity();
			}
		});
	} else {
		$("#errorKaryawanSecurity").html("");
		// empty detail security
		emptyDetailSecurity();
	}
	$("#modalSecurity").css('overflow-y', 'scroll');
});

function emptyDetailSecurity() {
	$("#errorKaryawanSecurity").css("margin-bottom", "-40px");
	var srcPhotoDefault = base_url+"assets/images/default/no_user.png";
	$("#dataSecurity").hide();
	$("#detailPhotoSecurity").attr("src", srcPhotoDefault);
	$("#detailPopUpOutSecurity").attr("href", srcPhotoDefault);
	$("#detailPopUpInSecurity").attr("src", srcPhotoDefault);
	$("#namaSecurity").html("");
	$("#noIndukSecurity").html("");
	$("#tglLahirSecurity").html("");
	$("#kelaminSecurity").html("");
	$("#jabatanSecurity").html("");
	$("#groupSecurity").html("");
}

$("#btnZoomSecurity").click(function() {
	$("#detailPopUpOutSecurity").click();
});

function btnEditSaatIniSecurity(id) {
	idSecurity = id;
	$.post(base_url+'aktivitas/jadwalkerja/getIdSecurity/'+idSecurity, function(json) {
		if (json.status == true) {
			$("#formDataSecurity")[0].reset();
			$("#modalSecurity").modal("show");
			$(".modal-title").html("Update Jadwal Security Bulan Saat Ini");
			$(".modal-header").removeClass('modal-header-danger');
			$(".modal-header").addClass('modal-header-green');
			save_method_security = "update_bulan_saat_ini";

			$("#tgl_security").val(json.data.tanggal);
			$("#karyawan_security").empty().append('<option value="'+json.data.karyawan_id+'">'+json.data.nama+'</option>').val(json.data.karyawan_id).trigger('change');
			// $("#karyawan_security").val("").trigger('change');
			$("#shift_security").val(json.data.nama_shift);
		} else {
			swal({    
		            title: json.message,
		            type: "error",
		            timer: 2000,   
		            showConfirmButton: false 
		        });
			reloadTablesSecurity();
		}
	});
}

function btnEditDepanSecurity(id) {
	idSecurity = id;
	$.post(base_url+'aktivitas/jadwalkerja/getIdSecurity/'+idSecurity, function(json) {
		if (json.status == true) {
			$("#formDataSecurity")[0].reset();
			$("#modalSecurity").modal("show");
			$(".modal-title").html("Update Jadwal Security Bulan Depan");
			$(".modal-header").removeClass('modal-header-green');
			$(".modal-header").addClass('modal-header-danger');
			save_method_security = "update_bulan_depan";

			$("#tgl_security").val(json.data.tanggal);
			$("#karyawan_security").empty().append('<option value="'+json.data.karyawan_id+'">'+json.data.nama+'</option>').val(json.data.karyawan_id).trigger('change');
			$("#shift_security").val(json.data.nama_shift);
		} else {
			swal({    
		            title: json.message,
		            type: "error",
		            timer: 2000,   
		            showConfirmButton: false 
		        });
			reloadTablesSecurity();
		}
	});
}

$("#btnSimpanSecurity").click(function() {
	var url;
	if (save_method_security == "add_bulan_saat_ini") {
		url = base_url+'aktivitas/jadwalkerja/addSecurity';
	} else if (save_method_security == "update_bulan_saat_ini") {
		url = base_url+'aktivitas/jadwalkerja/updateSecurity/'+idSecurity;
	} else if (save_method_security == "add_bulan_depan") {
		url = base_url+'aktivitas/jadwalkerja/addSecurity/true';
	} else if (save_method_security == "add_bulan_depan") {
		url = base_url+'aktivitas/jadwalkerja/updateSecurity/'+idSecurity+'/true';
	}

	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'JSON',
		data: $("#formDataSecurity").serialize(),
	})
	.done(function(json) {
		console.log("success");
		if (json.status == true) {
			swal({    
		            title: json.message,
		            type: "success",
		            timer: 2500,   
		            showConfirmButton: false 
		        });
			reloadTablesSecurity();
			setTimeout(function() {
				$("#modalSecurity").modal("hide");
			},2000);
		} else {
			swal({
		            title: "Error Form",
		            type: "error",
		            html: json.message,
		        });
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
});

$(document).ready(function(){
	$("#karyawan_security_ganti").select2({
		placeholder: '-- Pilih Security --',
		ajax: {
		   	url: base_url+"aktivitas/jadwalkerja/allKaryawanAjax/4", // for karyawan security
		   	type: "post",
		   	dataType: 'json',
		   	delay: 250,
		   	data: function (params) {
		    	return {
		      		searchTerm: params.term // search term
		    	};
		   	},
		   	processResults: function (response) {
		     	return {
		        	results: response
		     	};
		   	},
		   	cache: true
		},
		dropdownParent: $('#modalGantiSecurity')
	});
});

function btnGantiSecurity(id) {
	$("#errorDataSecurity").html("");
	idSecurity = id;
	$.post(base_url+'/aktivitas/jadwalkerja/getIdJadwal/'+idSecurity, function(json) {
		/*optional stuff to do after success */
		if (json.status == true) {
			$("#modalGantiSecurity").modal("show");
			$(".modal-title").html("Form ganti Security");
			$(".modal-header").removeClass('modal-header-green');
			$(".modal-header").removeClass('modal-header-danger');
			$(".modal-header").addClass('modal-header-black');

			$("#tglGantiSecurity").html(json.data.tanggal);
			$("#shiftGantiSecurity").html(json.data.nama_shift);
			var foto = json.data.foto;
			$("#detailPhotoSecurityUtama").attr("src", foto);
			$("#detailPopUpOutSecurityUtama").attr("href", foto);
			$("#detailPopUpInSecurityUtama").attr("src", foto);
			$("#namaSecurityUtama").html(json.data.nama);
			$("#noIndukSecurityUtama").html(json.data.idfp);
			$("#tglLahirSecurityUtama").html(json.data.tgl_lahir_indo);
			$("#kelaminSecurityUtama").html(json.data.kelamin);
			$("#jabatanSecurityUtama").html(json.data.jabatan);
			$("#groupSecurityUtama").html(json.data.grup);
			$("#karyawanIdSecurity").val(json.data.karyawan_id);

			if (json.data.ganti_karyawan_id != null) {
				$("#karyawan_security_ganti").empty().append('<option value="'+json.data.ganti_karyawan_id+'">'+json.data.nama_karyawan_ganti+'</option>').val(json.data.ganti_karyawan_id).trigger('change');
				$("#errorDataSecurity").html("");
				$("#keterangan_security").val(json.data.keterangan_ganti);
			} else {
				$("#karyawan_security_ganti").val("").trigger('change');
				$("#errorDataSecurity").html("");
				$("#keterangan_security").val("");
			}
		} else {
			swal({    
		            title: json.message,
		            type: "error",
		            timer: 2500,   
		            showConfirmButton: false 
		        });
			reloadTable();
			setTimeout(function() {
				$("#dataTableKarywanBulanSaatini").hide(800);
				$("#dataTableBulanSaatIni").show(800);
			},2000);
		}
	});
}

$("#karyawan_security_ganti").change(function() {
	var val = $(this).val();
	if (val != "" || val != null) {
		console.log(val);
		if (val != $("#karyawanIdSecurity").val()) {
			$.post(base_url+"/aktivitas/jadwalkerja/getIdKaryawan/"+val,function(json) {
				if (json.status == true) {
					$("#dataSecurityGanti").show(800);

					var foto = json.data.foto;
					$("#detailPhotoSecurityGanti").attr("src", foto);
					$("#detailPopUpOutSecurityGanti").attr("href", foto);
					$("#detailPopUpInSecurityGanti").attr("src", foto);
					$("#namaSecurityGanti").html(json.data.nama);
					$("#noIndukSecurityGanti").html(json.data.idfp);
					$("#tglLahirSecurityGanti").html(json.data.tgl_lahir_indo);
					$("#kelaminSecurityGanti").html(json.data.kelamin);
					$("#jabatanSecurityGanti").html(json.data.jabatan);
					$("#groupSecurityGanti").html(json.data.grup);
					$("#errorDataSecurity").html("");
					$("#errorDataSecurity").css("margin-bottom", "0px");
				} else {
					$("#errorDataSecurity").html(json.message);
					$("#errorDataSecurity").css("margin-bottom", "-40px");
					var srcPhotoDefault = base_url+"assets/images/default/no_user.png";
					$("#dataSecurityGanti").hide();
					$("#detailPhotoSecurityGanti").attr("src", srcPhotoDefault);
					$("#detailPopUpOutSecurityGanti").attr("href", srcPhotoDefault);
					$("#detailPopUpInSecurityGanti").attr("src", srcPhotoDefault);
					$("#namaSecurityGanti").html("");
					$("#noIndukSecurityGanti").html("");
					$("#tglLahirSecurityGanti").html("");
					$("#kelaminSecurityGanti").html("");
					$("#jabatanSecurityGanti").html("");
					$("#groupSecurityGanti").html("");
				}
			});
		} else {
			swal({    
		            title: "Security Ganti tidak boleh sama dengan Security utama.!",
		            type: "error",
		            // timer: 2500,   
		            showConfirmButton: true, 
		        });
			$("#karyawan_security_ganti").val("").trigger('change');
		}
	} else {
		$("#dataSecurityGanti").hide(800);
		$("#errorDataSecurity").html("");
		var srcPhotoDefault = base_url+"assets/images/default/no_user.png";
		$("#detailPhotoGanti").attr("src", srcPhotoDefault);
		$("#detailPopUpOutSecurityGanti").attr("href", srcPhotoDefault);
		$("#detailPopUpInSecurityGanti").attr("src", srcPhotoDefault);
		$("#namaSecurityGanti").html("");
		$("#noIndukSecurityGanti").html("");
		$("#tglLahirSecurityGanti").html("");
		$("#kelaminSecurityGanti").html("");
		$("#jabatanSecurityGanti").html("");
		$("#groupSecurityGanti").html("");
	}
		$("#modalGantiSecurity").css('overflow-y', 'scroll');
});

$("#btnZoomSecurityUtama").click(function() {
	$("#detailPopUpOutSecurityUtama").click();
});

$("#btnZoomSecurityGanti").click(function() {
	$("#detailPopUpOutSecurityGanti").click();
});

$("#btnSimpanSecurityGanti").click(function() {
	$.ajax({
		url: base_url+'aktivitas/jadwalkerja/gantiSecuritySave/'+idSecurity,
		type: 'POST',
		dataType: 'JSON',
		data: $("#formDataSecurityGanti").serialize(),
	})
	.done(function(json) {
		console.log("success");
		if (json.status == true) {
			swal({    
		            title: json.message,
		            type: "success",
		            timer: 2500,   
		            showConfirmButton: false 
		        });
			$("#tblBulanSaatIniSecurity").DataTable().ajax.reload(null,false);
			
			setTimeout(function() {
				$("#modalGantiSecurity").modal("hide");
			},2000);
		} else {
			swal({
		            title: "Error Form",
		            type: "error",
		            html: json.message,
		        });
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
});