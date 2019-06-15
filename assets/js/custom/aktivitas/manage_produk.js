$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblProduk").DataTable({
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
			url: base_url+'aktivitas/manage_produk/ajax_list',
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
			{ data:'button_tool',
				searchable:false,
				orderable:false,
			 },
			{ data:'product_name' },
			{ data:'product_image',
				searchable:false,
				orderable:false,
			},
			{ data:'product_stock' },
			{ data:'product_description' },
			{ data:'product_price' },
			{ data:'status' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
});

function reloadTable() {
	$("#tblProduk").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

//Select2 Karyawan

$(document).ready(function(){

	$("#nama_karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"aktivitas/manage_produk/allKaryawanAjax",
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
		$.post(base_url+"/aktivitas/manage_produk/idKaryawan/"+val,function(json) {
			if (json.status == true) {
				$("#detailKaryawan").show(800);
				$("#detail_img_photo").attr("src",json.data.foto);
			   	$('#detailPopUpPhotoOut').attr('href',json.data.foto);
			   	$('#detailPopUpPhotoIn').attr('src',json.data.foto);
			   	$("#kode_karyawan").val(json.data.kode_karyawan);
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

//validasi tanggal
$("#mulaiDinas, #akhirDinas").change(function() {
	if($("#mulaiDinas").val() != "" && $("#akhirDinas").val() != ""){
		var startDate = new Date($('#mulaiDinas').val());
		var expiredDate = new Date($('#akhirDinas').val());
		if (startDate > expiredDate){
			swal({
		            title: "Information Notice!",
		            html: "<span style='color:orange'>Tanggal Akhir Dinas harus sama atau setelah hari dari Mulai Dinas</span>",
		            type: "warning",
		    //         background : '100%',
		    //         backdrop: `
				  //   rgba(0,0,123,0.4)
				  //   url("https://media0.giphy.com/media/AsNxyHoYDcCXe/giphy.gif")
				  //   center
				  // `
		        });
			$("#akhirDinas").val("");
		}
	}
});


$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Aktivitas Dinas");
	$("#tanggal").prop('disabled',false);
	$("#nama_karyawan").val("").trigger("change");
	save_method = "add";
});
function btnPrint(id)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");

	$.post(base_url+"aktivitas/manage_produk/getIdForPrint/"+id,function(json) {
		if (json.status == true) {
				$('#tanggal1').html(json.data.tanggal);
		    $("#keterangan1").html(json.data.keterangans);
				$("#karyawan1").html(json.data.nama);
				$("#mulaiDinas1").html(json.data.tgl_manage_produk);
				$("#akhirDinas1").html(json.data.akhir_manage_produk);
				$("#departemen1").html(json.data.departemen);
				$("#jabatan1").html(json.data.jabatan);
				$("#status1").html(json.data.status);
				$("#id_manage_produk1").html(json.data.id_manage_produk);
				$("#lama1").html(json.data.lama);
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

	$(".modal-title").text("Update Aktivitas Dinas");
	save_method = "update";
	$("#tanggal").prop('disabled',true);
	$.post(base_url+"aktivitas/manage_produk/getId/"+idData,function(json) {
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
		    	$("#keterangan").val(json.data.keterangans);
				$("#mulaiDinas").val(json.data.tgl_manage_produk1);
				$("#akhirDinas").val(json.data.akhir_manage_produk1);
				$("#departemen").val(json.data.departemen);
				$("#jabatan").val(json.data.jabatan);
				$("#nama_karyawan").empty().append('<option value="'+json.data.id_karyawan+'">'+json.data.nama+'</option>').val(json.data.id_karyawan).trigger('change');

			}else{
				swal({
			           title: "<h2 style='color:#f1c40f;'>Data yang <u style='color:green'>Ditolak</u> tidak bisa di update.!</h2>",
			            type: "warning",
			            timer: 4000,
			            showConfirmButton: false
			        });
			}
		} else {
			$("#inputMessage").html(json.message);

			$('#tanggal').val("");
			$("#keterangan").val("");
			$("#karyawan").val("");
			$("#mulaiDinas").val("");
			$("#akhirDinas").val("");
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
		url = base_url+'aktivitas/manage_produk/add';
	} else {
		url = base_url+'aktivitas/manage_produk/update/'+idData;
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
				$("#errorTanggal").html(json.error.tanggal);
				$("#errorKaryawan").html(json.error.karyawan);
				$("#errorMulaiDinas").html(json.error.mulaiDinas);
				$("#errorAkhirDinas").html(json.error.akhirDinas);
				$("#errorKeterangan").html(json.error.keterangans);

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
					$("#errorTanggal").html("");
					$("#errorKaryawan").html("");
					$("#errorMulaiDinas").html("");
					$("#errorAkhirDinas").html("");
					$("#errorKeterangan").html("");
				},5000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"aktivitas/manage_produk/getId/"+idData,function(json) {
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
				pesan += "<li class='pull-left'><small>Mulai Dinas : <i>"+json.data.tgl_manage_produk+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Akhir Dinas : <i>"+json.data.akhir_manage_produk+"</i></small></li><br>";


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
		    		$.post(base_url+"aktivitas/manage_produk/delete/"+idData,function(json) {
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
									title: "<h2 style='color:#c82333;'>Data yang sudah <u style='color:green'>Diterima</u> tidak bisa di Hapus.!</h2>",
									type: "warning",
									timer: 4000,
									showConfirmButton: false
							});
			}else{
				$.post(base_url+"aktivitas/manage_produk/getId/"+idData,function(json) {
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
						pesan += "<li class='pull-left'><small>Mulai Dinas : <i>"+json.data.tgl_manage_produk+"</i></small></li><br>";
						pesan += "<li class='pull-left'><small>Akhir Dinas : <i>"+json.data.akhir_manage_produk+"</i></small></li><br>";
					swal({
						title: "Apakah anda yakin.?",
						html: "<span style='color:red;'>Data yang di <b>Hapus</b> tidak bisa dikembalikan lagi.</span>"+pesan,
						type: "question",
						width: 400,
						showCloseButton: true,
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Iya, Hapus",
						closeOnConfirm: false,
								}).then((result) => {
						    	if (result.value) {
						    		$.post(base_url+"aktivitas/manage_produk/delete/"+idData,function(json) {
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
