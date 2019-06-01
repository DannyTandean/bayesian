$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblIzinCuti").DataTable({
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
			url: base_url+'aktivitas/cuti/ajax_list',
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
			{ data:'button_print',
				searchable:false,
				orderable:false,
			 },
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'jabatan' },
			{ data:'status' },
			{ data:'departemen' },
			{ data:'sisa_cuti' },
			{ data:'keterangans' },
			{ data:'mulai_berlaku' },
			{ data:'exp_date' },
			{ data:'lama_cuti' },
			{ data:'jatah_cuti' }
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

	$("#mulaiCuti").dateDropper( {
					dropWidth: 200,
					init_animation: "bounce",
					dropPrimaryColor: "#1abc9c",
					dropBorder: "1px solid #1abc9c",
					maxYear: "2050",
					format: "Y-m-d"
			});
	$("#akhirCuti").dateDropper( {
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

	$('#gaji_cuti').on('input', function() {
			$('#mata_uang_cuti').html( moneyFormat.to( parseInt($(this).val()) ) );
	});

});

function reloadTable() {
	$("#tblIzinCuti").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

//Select2 Karyawan

$(document).ready(function(){
	$("#nama_karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"aktivitas/cuti/allKaryawanAjax",
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

$("#nama_karyawan").change(function() {
	var val = $(this).val();
	if (val != "") {
		$.post(base_url+"/aktivitas/cuti/idKaryawan/"+val,function(json) {
			if (json.status == true) {
				$("#detailKaryawan").show(800);
				$("#detail_img_photo").attr("src",json.data.foto);
			   	$('#detailPopUpPhotoOut').attr('href',json.data.foto);
			   	$('#detailPopUpPhotoIn').attr('src',json.data.foto);
			   	$("#kode_karyawan").val(json.data.kode_karyawan);
				$("#departemen").val(json.data.departemen);
				$("#jabatan").val(json.data.jabatan);
				if (json.data.cuti_diambil != null) {
					$("#sdhDiambil").val(json.data.cuti_diambil);
				}
				else {
					$("#sdhDiambil").val(0);
				}
				$("#cutiBersama").val(json.data.cuti_bersama);
				$("#sisaCuti").val(json.data.sisa_cuti);
			} else {
				$("#detailKaryawan").hide(800);
				$("#errorDetailKaryawan").html(json.message);
				var srcPhotoDefault = base_url+"assets/images/default/no_user.png";
				$("#detail_img_photo").attr("src",srcPhotoDefault);
			   	$('#detailPopUpPhotoOut').attr('href',srcPhotoDefault);
			   	$('#detailPopUpPhotoIn').attr('src',srcPhotoDefault);
			   	$("#kode_karyawan").val("");
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
		$("#departemen").val("");
		$("#jabatan").val("");
	}
	$("#modalForm").css('overflow-y', 'scroll');
});

$("#btnDetailZoomImg").click(function() {
	$("#detailPopUpPhotoOut").click();
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
	$("#inputMessage").html("");
	$("#nama_karyawan").val("").trigger("change");
	$(".modal-title").text("Tambah Aktivitas Cuti");
	$('#mata_uang_gajiGol').html("");
	save_method = "add";
});

//validasi tanggal
$("#mulaiCuti, #akhirCuti").change(function() {
	if($("#mulaiCuti").val() != "" && $("#akhirCuti").val() != ""){
		var startDate = new Date($('#mulaiCuti').val());
		var expiredDate = new Date($('#akhirCuti').val());
		if (startDate > expiredDate){
			swal({
		            title: "Information Notice!",
		            html: "<span style='color:orange'>Tanggal Akhir Cuti harus sama atau setelah hari dari Mulai Cuti</span>",
		            type: "warning",
		        });
			$("#akhirCuti").val("");
		}
	}
});
$('.showOpsiCuti').hide(800);
$('#opsi_cuti').change(function(event) {
		if ($(this).val() == 0) {
			$('.showOpsiCuti').hide(800);
		}
		else {
			$('.showOpsiCuti').show(800);
		}
});

function btnPrint(id)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");

	$.post(base_url+"aktivitas/cuti/getIdForPrint/"+id,function(json) {
		if (json.status == true) {
				$('#tanggal1').html(json.data.tanggal);
		    $("#keterangan1").html(json.data.keterangans);
				$("#karyawan1").html(json.data.nama);
				$("#mulaiCuti1").html(json.data.mulai_berlaku);
				$("#akhirCuti1").html(json.data.exp_date);
				$("#departemen1").html(json.data.departemen);
				$("#jabatan1").html(json.data.jabatan);
				$("#status1").html(json.data.status);
				$("#id_cuti").html(json.data.id_cuti);
				$("#lama1").html(json.data.lama_cuti);
				//setting
				$("#logo").attr('src',json.data.setting.logo);
				$("#emailPerusahaan").attr('href','mailto:'+json.data.setting.email_perusahaan);
				$("#emailPerusahaan").html(json.data.setting.email_perusahaan);
				$("#namaPerusahaan").html(json.data.setting.nama_perusahaan);
				$("#alamat").html(json.data.setting.alamat);
				$("#noTelp").html(json.data.setting.no_telp);
				$("#noFax").html(json.data.setting.no_fax);
				$("#tanggal").prop('disabled',true);
		} else {
			$("#inputMessage").html(json.message);

			$('#tanggal').val("");
			$("#keterangan").val("");
			$("#karyawan").val("");
			$("#mulaiDinas").val("");
			$("#akhirDinas").val("");
			$("#status1").val("");
			// setTimeout(function() {
			// 	reloadTable();
			// 	$("#Print").modal("hse");
			// },1000);
		}
	});
}

function btnEdit(id) {
	idData = id;

	$(".modal-title").text("Update Aktivitas Cuti");
	save_method = "update";

	$.post(base_url+"aktivitas/cuti/getId/"+idData,function(json) {
		if (json.status == true) {
			if (json.data.status == "Diterima") {
				swal({
			            title: "<h2 style='color:#f1c40f;'>Data yang <u style='color:green'>Diterima</u> tidak bisa di update.!</h2>",
			            type: "warning",
			            timer: 4000,
			            showConfirmButton: false
			        });
			} else if (json.data.status == "Proses") {
				$("#modalForm").modal("show");
				$('#tanggal').val(json.data.tanggal);
				$("#sdhDiambil").val(json.data.umk);
				$("#cutiBersama").val(json.data.cuti_bersama);
				$("#sisaCuti").val(json.data.sisa_cuti);
				$("#mulaiCuti").val(json.data.mulai_berlaku1);
				$('#opsi_cuti').val(json.data.opsi_cuti);
				if (json.data.opsi_cuti == 0) {
					$('.showOpsiCuti').hide();
				}
				else {
					$('.showOpsiCuti').show();
					$('#gaji_cuti').val(json.data.gaji_cuti);
				}
				$("#akhirCuti").val(json.data.exp_date1);
		   	$("#keterangan").val(json.data.keterangans);
				$("#nama_karyawan").empty().append('<option value="'+json.data.id_karyawan+'">'+json.data.nama+'</option>').val(json.data.id_karyawan).trigger('change');

			} else {
				swal({
									title: "<h2 style='color:#f1c40f;'>Data yang <u style='color:green'>Ditolak</u> tidak bisa di update.!</h2>",
									type: "warning",
									timer: 4000,
									showConfirmButton: false
							});
			}
		} else {
			$("#inputMessage").html(json.message);

			$('#golongan').val("");
			$("#gajiCuti").val("");
			$("#umk").val("");
			$("#THR").val("");
			$("#tunjanganDisiplin").val("");
			$("#tunjMakan").val("");
			$("#TunjKehadiran").val("");
			$("#TunjTransport").val("");
			$("$TunjLain").val("");
			$("#PotAbsen").val("");
			$("#PotBpjs").val("");
			$("#PotLain").val("");
			$("#SistemLembur").val("");
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
		url = base_url+'aktivitas/cuti/add';
	} else {
		url = base_url+'aktivitas/cuti/update/'+idData;
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
				$("#inputMessage").html(json.message);

				/*swal({
			            title: json.message,
			            type: "success",
			            timer: 2000,
			            showConfirmButton: false
			        });*/

				setTimeout(function() {
					$("#formData")[0].reset();
					$("#modalForm").modal("hide");
					$("#inputMessage").html("");
					reloadTable();

					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');

				}, 1500);
			} else {
				$("#inputMessage").html(json.message);
				if (json.message == '') {
				$("#errorMulaiCuti").html(json.error.mulaiCuti);
				$("#errorAkhirCuti").html(json.error.akhirCuti);
				$("#errorNamaKaryawan").html(json.error.karyawan);
				$("#errorKeterangan").html(json.error.keterangan);
				}
				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {

					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');

					$("#inputMessage").html("");
					$("#errorMulaiCuti").html("");
					$("#errorAkhirCuti").html("");
					$("#errorNamaKaryawan").html("");
					$("#errorKeterangan").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"aktivitas/cuti/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status == "Proses") {
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Status : <i>"+json.data.status+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Mulai Cuti : <i>"+json.data.mulai_berlaku+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Akhir Cuti : <i>"+json.data.exp_date+"</i></small></li><br>";


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
		    		$.post(base_url+"aktivitas/cuti/delete/"+idData,function(json) {
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
			}else if(json.data.status == "Diterima"){
				swal({
									title: "<h2 style='color:#f1c40f;'>Data yang <u style='color:green'>Diterima</u> tidak bisa di hapus.!</h2>",
									type: "warning",
									timer: 4000,
									showConfirmButton: false
							});
			}else{
				$.post(base_url+"aktivitas/cuti/getId/"+idData,function(json) {
				if (json.status == true) {
					var pesan = "<hr>";
						pesan += "<div class='row'>";
						pesan += "<div class='col-md-4'>";
						pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
						pesan += "</div>";
						pesan += "<div class='col-md-8'>";
						pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
						pesan += "<li class='pull-left'><small>Status : <i>"+json.data.status+"</i></small></li><br>";
						pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
						pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
						pesan += "<li class='pull-left'><small>Mulai Cuti : <i>"+json.data.mulai_berlaku+"</i></small></li><br>";
						pesan += "<li class='pull-left'><small>Akhir Cuti : <i>"+json.data.exp_date+"</i></small></li><br>";
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
								}).then((result) => {
						    	if (result.value) {
						    		$.post(base_url+"aktivitas/cuti/delete/"+idData,function(json) {
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
