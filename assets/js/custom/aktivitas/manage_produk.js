$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Add</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblProduk").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		oLanguage: {
						sZeroRecords: "<center>Data not found</center>",
						sLengthMenu: "Show _MENU_ data   "+btnTambah+btnRefresh,
						sSearch: "Search data:",
						sInfo: "Show: _START_ - _END_ from total: _TOTAL_ data",
						oPaginate: {
								sFirst: "Start", "sPrevious": "Previous",
								sNext: "Next", "sLast": "Last"
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
			// { data:'product_description' },
			// { data:'product_price' },
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
	$(".modal-title").text("Tambah Aktivitas Produk");
	$('#img_photo').attr('src',base_url+"/assets/images/default/no_file_.png");
		$('#popUpPhotoOut').attr('href',base_url+"/assets/images/default/no_file_.png");
		$('#popUpPhotoIn').attr('src',base_url+"/assets/images/default/no_file_.png");
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

	$(".modal-title").text("Update Aktivitas Produk");
	save_method = "update";
	$("#modalForm").modal("show");

	$.post(base_url+"aktivitas/manage_produk/getId/"+idData,function(json) {
		if (json.status == true) {
				$('#product_name').val(json.data.product_name);
		    $("#product_stock").val(json.data.product_stock);
				$("#product_price").val(json.data.product_price);
				$("#deskripsi").val(json.data.product_description);
				$('#img_photo').attr('src',json.data.product_image);
			   	$('#popUpPhotoOut').attr('href',json.data.product_image);
			   	$('#popUpPhotoIn').attr('src',json.data.product_image);

		} else {
			$('#product_name').val("");
			$("#product_stock").val("");
			$("#product_price").val("");
			$("#deskripsi").val("");
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
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.product_image+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Nama Produk : <i>"+json.data.product_name+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Stock Produk : <i>"+json.data.product_stock+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Harga Produk : <i>"+json.data.product_price+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Deskripsi Produk : <i>"+json.data.product_description+"</i></small></li><br>";

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
