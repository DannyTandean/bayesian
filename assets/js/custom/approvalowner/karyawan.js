$(document).ready(function() {
   	
    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblApproveOwnerKaryawan").DataTable({
		serverSide:true,
		responsive:true,
		autoWidth: true,
		processing:true,
		oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data   "+btnRefresh,
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",                                   
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
		//load data
		ajax: {
			url: base_url+'/approvalowner/karyawan/ajax_list',
			type: 'POST',
		},

		order:[[8,'DESC']],
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
			{ data:'status_approve' },
			// { data:'info_approve' },
			{ data:'nama' },
			{ data:'departemen' },
			{ data:'jabatan' },
			{ data:'alamat' },
			{ data:'kelamin' },
			{ data:'created_at' },
		],

		/*columnDefs: [
	        { "width": "5px", "targets": [0,1] },       
	        { "width": "40px", "targets": [2,3,4,5,6,7,8] }
	      ]*/

	});
});

function reloadTable() {
	$("#tblApproveOwnerKaryawan").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

$(document).on('click', '#btnRefresh', function(e) {
	e.preventDefault();
	reloadTable();
	$("#btnRefresh").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefresh").children().removeClass("fa-spin");
	}, 1000);
});

function btnDetail(id) {
	idData = id;

	$("#dataTable").hide(800);
	$("#detailData").show(800);

	$.post(base_url+"/approvalowner/karyawan/getIdDetail/"+idData,function(json) {
		if (json.status == true) {
			$("#detailNoId").html(json.data.idfp);
			$("#detailNama").html(json.data.nama);

			$("#detail_img_photo").attr("src",json.data.foto_master);
		   	$('#detailPopUpPhotoOut').attr('href',json.data.foto_master);
		   	$('#detailPopUpPhotoIn').attr('src',json.data.foto_master);

		   	if (json.data.foto_status == 1) {
		   		$("#photoApproval").show();
		   		$("#detail_img_photo_tmp").attr("src",json.data.foto_temporer);
			   	$('#detailPopUpPhotoOutTmp').attr('href',json.data.foto_temporer);
			   	$('#detailPopUpPhotoInTmp').attr('src',json.data.foto_temporer);
		   	} else {
		   		$("#photoApproval").hide();
		   	}

			$("#detailAlamat").html(json.data.alamat);
			$("#detailTempatLahir").html(json.data.tempat_lahir);
			$("#detailTanggalLahir").html(json.data.tgl_lahir_indo);
			$("#detailTelepon").html(json.data.telepon);
			$("#detailEmail").html(json.data.email);
			$("#detailNoWali").html(json.data.no_wali);
			$("#detailJenisKelamin").html(json.data.kelamin);
			$("#detailStatusPernikahan").html(json.data.status_nikah);
			$("#detailPendidikan").html(json.data.pendidikan);
			$("#detailKewarganegaraan").html(json.data.wn);
			$("#detailAgama").html(json.data.agama);
			$("#detailCabang").html(json.data.cabang);
			$("#detailDepartemen").html(json.data.departemen);
			$("#detailJabatan").html(json.data.jabatan);

			var checkTunjangan = json.data.jab_index == "1" ? "Iya" : "Tidak";
			$("#detailTunjanganJabatan").html(checkTunjangan);

			$("#detailGolongan").html(json.data.golongan);
			$("#detailShift").html(json.data.shift_text);
			$("#detailGroup").html(json.data.grup);
			$("#detailTglMasukKerja").html(json.data.tgl_masuk_indo);

			$("#detailStatusKontrak").html(json.data.kontrak);
			if (json.data.kontrak == "iya") {

				$("#detailStartDate").html(json.data.start_date_indo);
				$("#detailExpiredDate").html(json.data.expired_date_indo);

				$("#detail_upload_kontrak").attr("src",json.data.file_kontrak_master);
			   	$('#detailPopUpFileOut').attr('href',json.data.file_kontrak_master);
			   	$('#detailPopUpFileIn').attr('src',json.data.file_kontrak_master);

			   	if (json.data.file_status == 1) {
			   		$("#fileKontrakApproval").show();
			   		$("#detail_upload_kontrak_tmp").attr("src",json.data.file_kontrak_temporer);
				   	$('#detailPopUpFileOutTmp').attr('href',json.data.file_kontrak_temporer);
				   	$('#detailPopUpFileInTmp').attr('src',json.data.file_kontrak_temporer);
			   	} else {
			   		$("#fileKontrakApproval").hide();
			   	}

				$("#detailDataKontrak").show(800);
			} else {
				var srcFileDefault = base_url+"assets/images/default/no_file_.png";
				$("#detailStartDate").html("");
				$("#detailExpiredDate").html("");
				$("#detailDataKontrak").hide(800);
				$("#detail_upload_kontrak").attr("src",srcFileDefault);
			   	$('#detailPopUpFileOut').attr('href',srcFileDefault);
			   	$('#detailPopUpFileIn').attr('src',srcFileDefault);
			}

			$("#detailIbuKandung").html(json.data.ibu_kandung);
			$("#detailSuamiIstri").html(json.data.suami_istri);
			$("#detailJumlahTanggungan").html(json.data.tanggungan);
			$("#detailNPWP").html(json.data.npwp);
			$("#detailNilaiGaji").html(json.data.gaji_rp);
			$("#detailPeriodeGajian").html(json.data.periode_gaji);
			$("#detailNoRekening").html(json.data.rekening);
			$("#detailBank").html(json.data.bank);
			$("#detailAtasNama").html(json.data.atas_nama);
			$("#detailOtoritas").html(json.data.otoritas);

			if (json.data.status_kerja == "aktif") {
				$("#detailStatusKerja").html("Aktif");
			} else {
				$("#detailStatusKerja").html("Non Aktif");
			}

			if (json.data.status_approve == "Proses") {
				$("#btnApproval").show();
				$("#btnReject").show();
			} else {
				$("#btnApproval").hide();
				$("#btnReject").hide();
			}
		} else {
			swal({   
		            title: "Error Data.!",   
		            html: json.message,
		            type: "error",
		            // timer: 2000,
		        });

			setTimeout(function() {
				reloadTable();
				$("#dataTable").show(800);
				$("#detailData").hide(800);
			},1000);
		}
	});
}

$("#btnApproval").click(function() {
	btnApproval(idData);
});

$("#btnReject").click(function() {
	btnReject(idData);
});

$("#btnDetailZoomImg").click(function() {
	$("#detailPopUpPhotoOut").click();
});

$("#btnDetailZoomFile").click(function() {
	$("#detailPopUpFileOut").click();
});

$("#btnDetailZoomImgTmp").click(function() {
	$("#detailPopUpPhotoOutTmp").click();
});

$("#btnDetailZoomFileTmp").click(function() {
	$("#detailPopUpFileOutTmp").click();
});


$(".close-form").click(function() {
	$("#detailData").hide(800);
	$("#dataTable").show(800);
});
 
function btnApproval(id) {
	idData = id;

	$.post(base_url+"approvalowner/karyawan/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status_approve == "Proses") {
				var pesan = "<hr>";
					pesan += "<div class='row'>";
					pesan += "<div class='col-md-4'>";
					pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
					pesan += "</div>";
					pesan += "<div class='col-md-8'>";
					pesan += "<li class='pull-left'><small>Tanggal : <i>"+json.data.tanggal_approve_indo+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Status : <i>"+json.data.status_approve+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Info : <i>"+json.data.info_approve+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jenis kelamin : <i>"+json.data.kelamin+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Alamat : <i>"+json.data.alamat+"</i></small></li><br>";
					pesan += "</div>";
					pesan += "</div>";

			    swal({
			        title: "Apakah anda yakin.?",
			       	html: "<span style='color:green;'>Data yang di <b>Diterima</b> tidak bisa di edit/update lagi.</span>"+pesan,
					type: "question",
					width: 400,
	  				showCloseButton: true,
			        showCancelButton: true,
			        confirmButtonColor: "#009900",
			        confirmButtonText: "Iya, Terima",
			        closeOnConfirm: false,

			    }).then((result) => {
			    	if (result.value) {
			    		$.post(base_url+"approvalowner/karyawan/approve/"+idData,function(json) {
							if (json.status == true) {
								swal({
							            title: json.message,
							            type: "success",
							            timer: 3000,
							            showConfirmButton: false
							        });
								reloadTable();
								$("#detailData").hide(800);
								$("#dataTable").show(800);
							} else {
								swal({
							            title: json.message,
							            type: "error",
							            timer: 1500,
							            showConfirmButton: false
							        });
								reloadTable();
							}
						});
			    	}
			    });
			} else if (json.data.status_approve == "Diterima") {

				swal({
			            title:  "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa Diterima lagi.!</h2>",
						type: "warning",
			            timer: 3000,
			            showConfirmButton: false

			        });
			} else if (json.data.status_approve == "Tolak") {
				swal({
			            title:  "<h2 style='color:orange;'>Data yang sudah <u style='color:red;'>Ditolak</u> tidak bisa Diterima lagi.!</h2>",
						type: "warning",
			            timer: 3000,
			            showConfirmButton: false

			        });
			}
		} else {
			reloadTable();
			swal({
		            title: json.message,
		            type: "error",
		            timer: 1000,
		            showConfirmButton: false
		        });
		}
	});
}

function btnReject(id) {
	idData = id;

	$.post(base_url+"approvalowner/karyawan/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status_approve == "Proses") {
				var pesan = "<hr>";
					pesan += "<div class='row'>";
					pesan += "<div class='col-md-4'>";
					pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
					pesan += "</div>";
					pesan += "<div class='col-md-8'>";
					pesan += "<li class='pull-left'><small>Tanggal : <i>"+json.data.tanggal_approve_indo+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Status : <i>"+json.data.status_approve+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Info : <i>"+json.data.info_approve+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jenis kelamin : <i>"+json.data.kelamin+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Alamat : <i>"+json.data.alamat+"</i></small></li><br>";
					pesan += "</div>";
					pesan += "</div>";

			    swal({
			        title: "Apakah anda yakin.?",
			        html: "<span style='color:red;'>Data yang di <b>Reject / Tolak</b> tidak bisa di edit/update lagi.</span>"+pesan,
			        type: "question",
					width: 400,
	  				showCloseButton: true,
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "Iya, Tolak",
			        closeOnConfirm: false,

			    }).then((result) => {
			    	if (result.value) {
			    		$.post(base_url+"approvalowner/karyawan/reject/"+idData,function(json) {
							if (json.status == true) {
								swal({
							            title: json.message,
							            type: "success",
							            timer: 3000,
							            showConfirmButton: false
							        });
								reloadTable();
								$("#detailData").hide(800);
								$("#dataTable").show(800);
							} else {
								swal({
							            title: json.message,
							            type: "error",
							            timer: 1500,
							            showConfirmButton: false
							        });
								reloadTable();
							}
						});
			    	}
			    });
			} else if (json.data.status_approve == "Diterima") {

				swal({
			            title: "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa Ditolak lagi.!</h2>",
						type: "warning",
			            timer: 3000,
			            showConfirmButton: false

			        });
			} else if (json.data.status_approve == "Tolak") {
				swal({
			           	title:  "<h2 style='color:orange;'>Data yang sudah <u style='color:red;'>Ditolak</u> tidak bisa Ditolak lagi.!</h2>",
						type: "warning",
			            timer: 3000,
			            showConfirmButton: false

			        });
			}
		} else {
			reloadTable();
			swal({
		            title: json.message,
		            type: "error",
		            timer: 1000,
		            showConfirmButton: false
		        });
		}
	});
}