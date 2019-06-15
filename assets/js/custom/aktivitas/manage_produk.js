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

		order:[[2,'DESC']],
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
			{ data:'product_name' },
			{ data:'product_image',
				searchable:false,
				orderable:false,
			},
			{ data:'product_stock' },
			{ data:'product_description' },
			{ data:'product_price' },
			// { data:'status' },
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

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Aktivitas Dinas");
	$("#tanggal").prop('disabled',false);
	$("#nama_karyawan").val("").trigger("change");
	save_method = "add";
});

/* prosessing photo Pengumuman change*/
$("#btnZoomImg").click(function() {
	$("#popUpPhotoOut").click();
});

$("#ganti_photo").click(function() {
	$("#photo_produk").click();
});

$("#photo_produk").change(function(event){
	readURL(document.getElementById('photo_produk'));
	$('#is_delete_photo').val(0);
});

$("#hapus_photo").click(function() {

		$('#img_photo').attr('src','assets/images/default/no_file_.png');
		$('#popUpImgOut').attr('href','assets/images/default/no_file_.png');
		$('#popUpImgIn').attr('src','assets/images/default/no_file_.png');
   	$("#photo_produk").val("");
   	$('#is_delete_photo').val(1);
});

function readURL(input)
{
   	var filePath = input.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){ // validate format extension file
        // alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
        swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "Photo Pengumuman :  <small><span style='color:red;'> Jenis file yang Anda upload tidak diperbolehkan.</span> <br> Harap hanya upload file yang memiliki ekstensi <br> .jpeg | .jpg | .png | .gif </small>",
	            type: "error",
	        });

        input.value = '';
        return false;

    } else if (input.files[0].size > 1048576) { // validate size file 1 mb
		// alert("file size lebih besar dari 1 mb, file size yang di upload = "+input.files[0].size);
		swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "Photo Pengumuman : <small style='color:red;'>File yang diupload melebihi ukuran maksimal diperbolehkan yaitu 1 mb.</small>",
	            type: "error",
	        });
        input.value = '';
		return false;

	} else {
	   	if (input.files && input.files[0])
	   	{
		    var reader = new FileReader();
		    reader.onload = function (e)
		    {
		       $('#img_photo').attr('src',e.target.result);
		       $('#popUpPhotoIn').attr('src',e.target.result);
		       $('#popUpPhotoOut').attr('href',e.target.result);
		    };
		    reader.readAsDataURL(input.files[0]);
	   	}
	}
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

	var formData = new FormData($("#formData")[0]);

	$.ajax({
		url: url,
		type:'POST',
		data: formData,
		contentType:false,
		processData:false,
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
				$("#error_product_name").html(json.error.product_name);
				$("#error_product_stock").html(json.error.product_stock);
				$("#error_product_price").html(json.error.product_price);
				$("#error_product_deskripsi").html(json.error.deskripsi);

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
					$("#error_product_name").html("");
					$("#error_product_stock").html("");
					$("#error_product_price").html("");
					$("#error_product_deskripsi").html("");
				},3000);
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
