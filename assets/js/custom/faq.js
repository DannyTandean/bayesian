$(document).ready(function() {
	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";
    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblFAQ").DataTable({
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
			url: base_url+'faq/ajax_list',
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
			{ data:'tanggal' },
			{ data:'pertanyaan' },
			{ data:'jawaban' },
			{ data:'penulis' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
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
			    CKEDITOR.replace("editor10",options);
				CKEDITOR.replace("editor9",options);

});

function reloadTable() {
	$("#tblFAQ").DataTable().ajax.reload(null,false);
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
	$(".modal-title").text("Tambah FAQ");
	save_method = "add";
});

function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update FAQ");
	save_method = "update";
	$.post(base_url+"faq/getId/"+idData,function(json) {
		if (json.status == true) {
			for (instance in CKEDITOR.instances) {
						CKEDITOR.instances[instance].updateElement();
				}
				var date = moment(); //Get the current date
				$("#tanggal").val(date.format("YYYY-MM-DD").toString());
				CKEDITOR.instances.editor9.setData(json.data.pertanyaan);
				CKEDITOR.instances.editor10.setData(json.data.jawaban);
		} else {
			$("#inputMessage").html(json.message);

			$("#tanggal").val("");
			$("#editor9").val("");
			$("#editor10").val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});

}

// $("#modalButtonSave").click(function() {
// 	var url;
// 	for (instance in CKEDITOR.instances) {
// 				CKEDITOR.instances[instance].updateElement();
// 		}
// 	if (save_method == "add") {
// 		url = base_url+'faq/add';
// 	} else {
// 		url = base_url+'faq/update/'+idData;
// 	}

// 	$.ajax({
// 		url: url,
// 		type:'POST',
// 		data:$("#formData").serialize(),
// 		dataType:'JSON',
// 		success: function(json) {
// 			if (json.status == true) {
// 				$("#inputMessage").html(json.message);

// 				/*swal({    
// 			            title: json.message,
// 			            type: "success",
// 			            timer: 2000,   
// 			            showConfirmButton: false 
// 			        });*/

// 				setTimeout(function() {
// 					$("#formData")[0].reset();
// 					$("#modalForm").modal("hide");
// 					$("#inputMessage").html("");
// 					reloadTable();
// 				}, 1500);
// 			} else {
// 				$("#inputMessage").html(json.message);
// 				$("#errorTanggal").html(json.error.tanggal);
// 				$("#errorPertanyaan").html(json.error.pertanyaan);
// 				$("#errorJawaban").html(json.error.jawaban);

// 				/*swal({   
// 			            title: "Error Form.!",   
// 			            html: json.message,
// 			            type: "error",
// 			        });*/

// 				setTimeout(function() {
// 					$("#inputMessage").html("");
// 					$("#errorTanggal").html("");
// 					$("#errorPertanyaan").html("");
// 					$("#errorJawaban").html("");
// 				},3000);
// 			}
// 		}
// 	});
// });

$("#modalButtonSave").click(function() {
	var url;
	for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
		}
	if (save_method == "add") {
		url = base_url+'faq/add';
	} else {
		url = base_url+'faq/update/'+idData;
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
				
				$("#errorPertanyaan").html(json.error.pertanyaan);
				$("#errorJawaban").html(json.error.jawaban);
				

				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorPertanyaan").html("");
					$("#errorJawaban").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"faq/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Tanggal : <i>"+json.data.tanggal+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Pertanyaan : <i>"+json.data.pertanyaan+"</i></small></li><br>";

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
		    		$.post(base_url+"faq/delete/"+idData,function(json) {
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

