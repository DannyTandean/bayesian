$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblPunishment").DataTable({
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
			url: base_url+'/rwd/punishment/ajax_list',
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
			{
				data:'button_tools',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal' },
			// { data:'kode_karyawan' },
			{ data:'nama' },
			{ data:'departemen' },
			{ data:'status' },
			{ data:'jabatan' },
			// { data:'judul' },
			{ data:'nilai' },
			{ data:'keterangan' },
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

	$("#printing").click(function(){
		$("#asd").print({
			globalStyles: true,
			mediaPrint: false,
			stylesheet: null,
			noPrintSelector: ".no-print",
			iframe: true,
			append: null,
			prepend: null,
			manuallyCopyFormValues: true,
			deferred: $.Deferred(),
			timeout: 750,
			title: null,
			doctype: '<!doctype html>'
			});
	});
});

function reloadTable() {
	$("#tblPunishment").DataTable().ajax.reload(null,false);
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


$(document).ready(function(){

	$("#nama_karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"rwd/punishment/allKaryawanAjax",
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
		dropdownParent: $('#modalForm')
	});
});

$(document).ready(function () {
    $('#nilai').on('input', function() {
        $('#mata_uang_nilai').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
});

function btnSp(id) {
	$("#modalSp").modal("show");
	$('.modal-title').text("Pilih SP");
	$.post(base_url+"rwd/punishment/getIdForPrint/"+id,function(json) {
		if (json.status == true) {
				$('#optionSP').click(function() {
				$('#modalSp').modal("hide");
				$('#Print').modal("show");
				$(".modal-title").text("Print");
				$('#tanggal1').html(json.data.tanggal);
		    $("#keterangan1").html(json.data.keterangan);
				$("#karyawan1").html(json.data.nama);
				$('#penerima').html(json.data.nama)
				$('#penerimaJbt').html(json.data.jabatan);
				$('#nomorsp').html($('#nosp').val());
				if ($('#sp1').val() == "sp-1") {
					$('#printSpTitle').html("SURAT PERINGATAN PERTAMA (SP-1)");
				}
				else if ($('#sp1').val() == "sp-2") {
					$('#printSpTitle').html("SURAT PERINGATAN KE-DUA (SP-2)");
				}
				else if ($('#sp1').val() == "sp-3") {
					$('#printSpTitle').html("SURAT PERINGATAN KE-TIGA (SP-3)");
				}
				$('#potonganGaji').html(moneyFormat.to(parseInt(json.data.nilai)));
				$("#jabatan1").html(json.data.jabatan);
				$("#namaKaryawan").html(json.data.nama);
				$("#jabatanprint").html(json.data.jabatan);
				//setting
				$("#logo").attr('src',json.setting.logo);
				$("#emailPerusahaan").attr('href','mailto:'+json.setting.email_perusahaan);
				$("#emailPerusahaan").html(json.setting.email_perusahaan);
				$("#namaPerusahaan").html(json.setting.nama_perusahaan);
				$("#alamat").html(json.setting.alamat);
				$("#noTelp").html(json.setting.no_telp);
				$("#noFax").html(json.setting.no_fax);
				$('#printing').focus();
			});
		}
		else {

		}
	});
}

// function btnPrint(id)
// {
// 	$("#Print").modal("show");
// 	$(".modal-title").text("Print");
//
// 	$.post(base_url+"rwd/punishment/getIdForPrint/"+id,function(json) {
// 		if (json.status == true) {
// 				$('#tanggal1').html(json.data.tanggal);
// 		    $("#keterangan1").html(json.data.keterangan);
// 				$("#karyawan1").html(json.data.nama);
// 				if (json.data.surat_peringatan == "sp-1") {
// 					$('#printSpTitle').html("SURAT PERINGATAN PERTAMA (SP-1)");
// 				}
// 				else if (json.data.surat_peringatan == "sp-2") {
// 					$('#printSpTitle').html("SURAT PERINGATAN KE-DUA (SP-2)");
// 				}
// 				else if (json.data.surat_peringatan == "sp-3") {
// 					$('#printSpTitle').html("SURAT PERINGATAN KE-TIGA (SP-3)");
// 				}
// 				$('#potonganGaji').html(moneyFormat.to(parseInt(json.data.nilai)));
// 				$("#jabatan1").html(json.data.jabatan);
// 				$("#namaKaryawan").html(json.data.nama);
// 				$("#jabatanprint").html(json.data.jabatan);
// 				//setting
// 				$("#logo").attr('src',json.setting.logo);
// 				$("#emailPerusahaan").attr('href','mailto:'+json.setting.email_perusahaan);
// 				$("#emailPerusahaan").html(json.setting.email_perusahaan);
// 				$("#namaPerusahaan").html(json.setting.nama_perusahaan);
// 				$("#alamat").html(json.setting.alamat);
// 				$("#noTelp").html(json.setting.no_telp);
// 				$("#noFax").html(json.setting.no_fax);
//
// 		} else {
// 			$("#inputMessage").html(json.message);
//
// 			$('#tanggal1').html("");
// 			$("#keterangan1").html("");
// 			$("#karyawan1").html("");
// 			$('#printSpTitle').html("");
// 			$("#jabatan1").html("");
// 			//setting
// 			$("#logo").attr('src',"");
// 			$("#emailPerusahaan").html("");
// 			$("#namaPerusahaan").html("");
// 			$("#alamat").html("");
// 			$("#noTelp").html("");
// 			$("#noFax").html("");
// 			// setTimeout(function() {
// 			// 	reloadTable();
// 			// 	$("#Print").modal("hse");
// 			// },1000);
// 		}
// 	});
// }

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Punishment");
	$("#nama_karyawan").val("").trigger("change");
	$('#mata_uang_nilai').html("");
	save_method = "add";
});

$("#nama_karyawan").change(function() {
	var val = $(this).val();
	if (val != "" || val != null) {
		console.log(val);

		$.post(base_url+"/rwd/punishment/idKaryawan/"+val,function(json) {
			if (json.status == true) {
				$("#detailKaryawan").show(800);
				$("#detail_img_photo").attr("src",json.data.foto);
			   	$('#detailPopUpPhotoOut').attr('href',json.data.foto);
			   	$('#detailPopUpPhotoIn').attr('src',json.data.foto);
			   	$("#kode_karyawan").val(json.data.kode_karyawan);
			   	$("#no_induk").val(json.data.idfp);
				$("#departemen").val(json.data.departemen);
				$("#jabatan").val(json.data.jabatan);
			} else {
				$("#detailKaryawan").hide(800);
				$("#errorDetailKaryawan").html(json.message);
				var srcPhotoDefault = base_url+"assets/images/default/no_user.png";
				$("#detail_img_photo").attr("src",srcPhotoDefault);
			   	$('#detailPopUpPhotoOut').attr('href',srcPhotoDefault);
			   	$('#detailPopUpPhotoIn').attr('src',srcPhotoDefault);
			   	$("#kode_karyawan").val("");
			   	$("#no_induk").val("");
				$("#departemen").val("");
				$("#jabatan").val("");
			}
		});
	} else {
		$("#detailKaryawan").hide(800);
		$("#errorDetailKaryawan").html("");
		var srcPhotoDefault = base_url+"assets/images/default/no_user.png";
		$("#detail_img_photo").attr("src",srcPhotoDefault);
	   	$('#detailPopUpPhotoOut').attr('href',srcPhotoDefault);
	   	$('#detailPopUpPhotoIn').attr('src',srcPhotoDefault);
	   	$("#kode_karyawan").val("");
		$("#no_induk").val("");
		$("#departemen").val("");
		$("#jabatan").val("");
	}
	$("#modalForm").css('overflow-y', 'scroll');
});

$("#btnDetailZoomImg").click(function() {
	$("#detailPopUpPhotoOut").click();
});

function btnEdit(id) {
	idData = id;
	save_method = "update";

	$.post(base_url+"rwd/punishment/getId/"+idData,function(json) {
		if (json.status == true) {

			if (json.data.status == "Diterima") {

				swal({
			            title: "<h2 style='color:#f1c40f;'>Status <u style='color:green;'>Diterima</u> tidak bisa di update.!</h2>",
			            type: "error",
			            timer: 3000,
			            showConfirmButton: false
			        });
			} else if (json.data.status == "Ditolak") {
				swal({
			            title: "<h2 style='color:#f1c40f;'>Status <u style='color:red;'>Tolak</u> tidak bisa di update.!</h2>",
			            type: "error",
			            timer: 3000,
			            showConfirmButton: false

			        });
			} else if (json.data.status == "Proses") {

				$("#modalForm").modal("show");
				$(".modal-title").text("Update Punishment");

				$('#tanggal').val(json.data.tanggal);
			    // $("#nama_karyawan").val(json.data.id_karyawan).trigger("change");
			    $("#nama_karyawan").empty().append('<option value="'+json.data.id_karyawan+'">'+json.data.nama+'</option>').val(json.data.id_karyawan).trigger('change');
					$('#sp').val(json.data.surat_peringatan);
			    $("#judul").val(json.data.judul);
			    $("#nilai").val(json.data.nilai);
			    $('#mata_uang_nilai').html(json.data.nilai_rp);
			    $("#keterangan").val(json.data.keterangan);
			}
		} else {
				$("#inputMessage").html(json.message);

				$('#tanggal').val("");
		    $("#nama_karyawan").val("").trigger("change");
				$('#sp').val("");
		    $("#judul").val("");
		    $("#nilai").val("");
		    $('#mata_uang_nilai').html("");
		    $("#keterangan").val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});

}

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'rwd/punishment/add';
	} else {
		url = base_url+'rwd/punishment/update/'+idData;
	}
	$("#modalButtonSave").attr("disabled",true);
	$("#modalButtonSave").html("Processing...<i class='fa fa-spinner fa-spin'></i>");

	$.ajax({
		url: url,
		type:'POST',
		data:$("#formData").serialize(),
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				// $("#inputMessage").html(json.message);

				swal({
			            title: json.message,
			            type: "success",
			            timer: 2000,
			            showConfirmButton: false
			        });

				setTimeout(function() {
					$("#formData")[0].reset();
					$("#modalForm").modal("hide");
					$("#inputMessage").html("");
					reloadTable();
					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');
				}, 1500);
			} else {
				// $("#inputMessage").html(json.message);
				$("#errorTanggal").html(json.error.tanggal);
				$("#errorNamaKaryawan").html(json.error.tunjangan);
				// $("#errorJudul").html(json.error.judul);
				$("#errorNilai").html(json.error.nilai);
				// $("#errorKeterangan").html(json.error.keterangan);

				swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });

				setTimeout(function() {

					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');
					$("#inputMessage").html("");
					$("#errorTanggal").html("");
					$("#errorNamaKaryawan").html("");
					// $("#errorJudul").html("");
					$("#errorNilai").html("");
					// $("#errorKeterangan").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"rwd/punishment/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status == "Proses" || json.data.status == "Ditolak") {
				var pesan = "<hr>";
					pesan += "<div class='row'>";
					pesan += "<div class='col-md-4'>";
					pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
					pesan += "</div>";
					pesan += "<div class='col-md-8'>";
					// pesan += "<li class='pull-left'><small>Kode : <i>"+json.data.kode_karyawan+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Status : <i>"+json.data.status+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
					// pesan += "<li class='pull-left'><small>Judul : <i>"+json.data.judul+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Nilai : <i>"+json.data.nilai_rp+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangan+"</i></small></li><br>";
					pesan += "</div>";
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
			        closeOnConfirm: false,
	  				// background: '#e9e9e9',

			    }).then((result) => {
			    	if (result.value) {
			    		$.post(base_url+"rwd/punishment/delete/"+idData,function(json) {
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
			} else if (json.data.status == "Diterima") {

				swal({
			            title: "<h2 style='color:#c82333;'>Status <u style='color:green;'>Diterima</u> tidak bisa di Hapus.!</h2>",
			            type: "error",
			            timer: 3000,
			            showConfirmButton: false

			        });
			}
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
