$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblPengumuman").DataTable({
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
			url: base_url+'aktivitasowner/Pengumuman/ajax_list',
			type: 'POST',
		},

		order:[[3,'ASC']],
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
			{ data:'foto' },
			{ data:'tanggal' },
			{ data:'judul' },
			{ data:'tipe' },
			{ data:'penulis' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

			// $("#printing").click(function(){
			// 	$("#asd").print({
			// 		globalStyles: true,
			// 		mediaPrint: false,
			// 		stylesheet: null,
			// 		noPrintSelector: ".no-print",
			// 		iframe: true,
			// 		append: null,
			// 		prepend: null,
			// 		manuallyCopyFormValues: true,
			// 		deferred: $.Deferred(),
			// 		timeout: 750,
			// 		title: null,
			// 		doctype: '<!doctype html>'
			// 		});
			// });
			var options = {
			        toolbar: [
					    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-','Templates' ] },
					    { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
					    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
					    '/',
					    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
					    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
					    { name: 'links', items: [ 'Link', 'Unlink'] },
					    { name: 'insert', items: ['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },

					    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
					    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					    { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
					    { name: 'others', items: [ '-' ] }
					],
					height:'200px',
			    };

				CKEDITOR.replace("editor9",options);

});

function reloadTable() {
	$("#tblPengumuman").DataTable().ajax.reload(null,false);
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

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Aktivitas pengumuman");
	save_method = "add";
});

function btnDetail(id)
{
	$("#Details").modal("show");
	$(".modal-title").text("Details");

	$.post(base_url+"aktivitasowner/pengumuman/getId/"+id,function(json) {
		if (json.status == true) {
				$("#judulDetail").html(json.data.judul);

				$("#tglDetail").html(json.data.tanggal);
				$("#isiDetail").html(json.data.isi);
				$("#detailTipe").html(json.data.tipe);
				$("#penulisDetail").html(json.data.penulis);
				$('#detailfoto').attr('src',json.data.foto);
		} else {
			$("#inputMessage").html(json.message);

			$("#judulDetail").html("");
			$("#tglDetail").html("");
			$("#isiDetail").html("");
			$("#penulisDetail").html("");
			$('#detailfoto').attr('src',"");
			// setTimeout(function() {
			// 	reloadTable();
			// 	$("#Print").modal("hse");
			// },1000);
		}
	});
}
function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update Aktivitas Pengumuman");
	save_method = "update";
	$.post(base_url+"aktivitasowner/pengumuman/getId/"+idData,function(json) {
		if (json.status == true) {
			for (instance in CKEDITOR.instances) {
						CKEDITOR.instances[instance].updateElement();
				}
				var date = moment(); //Get the current date
				$("#tanggal").val(date.format("YYYY-MM-DD").toString());
		    $("#judul").val(json.data.judul);
				$("#tipePengumuman").val(json.data.tipe);
				CKEDITOR.instances.editor9.setData(json.data.isi);
				$('#img_photo').attr('src',json.data.foto);
			   	$('#popUpImgOut').attr('href',json.data.foto);
			   	$('#popUpImgIn').attr('src',json.data.foto);
		} else {
			$("#inputMessage").html(json.message);

			$("#tanggal").val("");
			$("#judul").val("");
			$("#tipePengumuman").val("");
			$("#editor9").val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});

}

/* prosessing photo Pengumuman change*/
$("#btnZoomImg").click(function() {
	$("#popUpPhotoOut").click();
});

$("#ganti_photo").click(function() {
	$("#photo_karyawan").click();
});

$("#photo_karyawan").change(function(event){
	readURL(document.getElementById('photo_karyawan'));
	$('#is_delete_photo').val(0);
});

$("#hapus_photo").click(function() {

		$('#img_photo').attr('src','assets/images/default/no_user.png');
		$('#popUpImgOut').attr('href','assets/images/default/no_user.png');
		$('#popUpImgIn').attr('src','assets/images/default/no_user.png');
   	$("#photo_karyawan").val("");
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

$("#modalButtonSave").click(function() {
	var url;
	for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
		}
	if (save_method == "add") {
		url = base_url+'aktivitasowner/pengumuman/add';
	} else {
		url = base_url+'aktivitasowner/pengumuman/update/'+idData;
	}
	var formData = new FormData($("#formData")[0]);
	$.ajax({
		url: url,
		type:'POST',
		data:formData,
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
				}, 1500);
			} else {
				$("#inputMessage").html(json.message);
				$("#errorJudul").html(json.error.judul);
				$("#errorTipe").html(json.error.tipe);
				$("#errorIsi").html(json.error.isi);
				if (json.messages == "error_foto") {

					var fotoError = "";
					var fileTitleError = "";

					if (json.error_photo_pengumuman) {
						fotoError += "<u>Photo Pengumuman : </u>"+json.error_photo_pengumuman+"<br>";
						fileTitleError = "Error Photo.!";
					}

					swal({
			            title: "<h2 style='color:red;'>"+fileTitleError+"</h2>",
			            html: fotoError,
			            type: "error",
			        });

					setTimeout(function() {

						$("#btnSimpan").attr("disabled",false);
						$("#btnSimpan").html('<i class="fa fa-save"></i> Simpan');

					}, 3000);
				} else {
					swal({
			            title: "Error Form",
			            html: json.messages,
			            type: "error",
									timer: 2000,
			        });

					setTimeout(function() {

						$("#btnSimpan").attr("disabled",false);
						$("#btnSimpan").html('<i class="fa fa-save"></i> Simpan');

					}, 3000);
				}

				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorJudul").html("");
					$("#errorTipe").html("");
					$("#errorIsi").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"/aktivitasowner/pengumuman/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Tanggal : <i>"+json.data.tanggal+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Judul : <i>"+json.data.judul+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tipe : <i>"+json.data.tipe+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Penulis : <i>"+json.data.penulis+"</i></small></li><br>";
				pesan += "</div>";
				pesan += "</div>";

		    swal({
		        title: "Apakah anda yakin.?",
		        html: "<span style='color:red;'>Data yang di <b>Hapus</b> tidak bisa dikembalikan lagi.</span>"+pesan,
		        type: "warning",
				width: 500,
  				showCloseButton: true,
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Iya, Hapus",
		        closeOnConfirm: false,
			  	// animation: false,
			  	// customClass: 'animated tada'

		    }).then((result) => {
		    	if (result.value) {
		    		$.post(base_url+"/aktivitasowner/pengumuman/delete/"+idData,function(json) {
						if (json.status == true) {
							swal({
						            title: json.message,
						            type: "success",
						            timer: 5000,
						            showConfirmButton: false
						        });
							reloadTable();
						} else {
							swal({
						            title: json.message,
						            type: "error",
						            /*timer: 1500,
						            showConfirmButton: false */
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
		            /*timer: 1000,
		            showConfirmButton: false */
		        });
		}
	});
}
