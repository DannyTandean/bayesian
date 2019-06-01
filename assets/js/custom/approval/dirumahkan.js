$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblDirumahkan").DataTable({
		serverSide:true,
		responsive:true,
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
			url: base_url+'approval/dirumahkan/ajax_list',
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
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'departemen' },
			{ data:'jabatan' },
			{ data:'keterangans' },
			{ data:'status' },
			{ data:'tgl_dirumahkan' },
			{ data:'akhir_dirumahkan' },
			{ data:'lama' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

});

function reloadTable() {
	$("#tblDirumahkan").DataTable().ajax.reload(null,false);
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



function btnApproval(id) {
	idData = id;
	$.post(base_url+"approval/dirumahkan/getId/"+idData,function(json) {
		if (json.status == true) {
			$("#status").val("Diterima");
			if(json.data.status == "Proses"){

			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Karyawan : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Mulai Dinas : <i>"+json.data.tgl_dirumahkan+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Akhir Dinas : <i>"+json.data.akhir_dirumahkan+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Lama Dinas : <i>"+json.data.lama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangans+"</i></small></li><br>";

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
					// background: '#e9e9e9',

				}).then((result) => {
					if (result.value) {
						$.ajax({
							url: base_url+"approval/dirumahkan/update/"+idData,
							type:'POST',
							data:$("#formData").serialize(),
							dataType:'JSON',
							success: function(json) {
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
					}
				});
			}
		});
		}else if (json.data.status == "Diterima") {

			swal({
								title:  "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa Diterima lagi.!</h2>",
								type: "warning",
								timer: 3000,
								showConfirmButton: false

						});
		} else if (json.data.status == "Ditolak") {
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
								// html: true,
								timer: 1000,
								showConfirmButton: false
						});
		}
	});
}

function btnReject(id) {
	idData = id;

	$.post(base_url+"approval/dirumahkan/getId/"+idData,function(json) {
		if (json.status == true) {
			$("#status").val("Ditolak");
			if(json.data.status == "Proses"){
			var pesan = "<hr>";
			pesan += "<div class='row'>";
			pesan += "<div class='col-md-4'>";
			pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
			pesan += "</div>";
			pesan += "<div class='col-md-8'>";
			pesan += "<li class='pull-left'><small>Karyawan : <i>"+json.data.nama+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>Mulai Dinas : <i>"+json.data.tgl_dirumahkan+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>Akhir Dinas : <i>"+json.data.akhir_dirumahkan+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>Lama Dinas : <i>"+json.data.lama+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangans+"</i></small></li><br>";

		    swal({
		        title: "Apakah anda yakin.?",
		        html: "<span style='color:red;'>Data yang <b>Ditolak</b> tidak bisa di edit/update lagi.</span>"+pesan,
		        type: "question",
				width: 400,
  				showCloseButton: true,
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Iya, Tolak",
		        closeOnConfirm: false,
  				// background: '#e9e9e9',

		    }).then((result) => {
		    	if (result.value) {
						$.ajax({
							url: base_url+"approval/dirumahkan/update/"+idData,
							type:'POST',
							data:$("#formData").serialize(),
							dataType:'JSON',
							success: function(json) {
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
					}
				});
			}
		});
		}else if (json.data.status == "Diterima") {

			swal({
								title: "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa Ditolak lagi.!</h2>",
								type: "warning",
								timer: 3000,
								showConfirmButton: false

						});
		} else if (json.data.status == "Ditolak") {
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
		            // html: true,
		            timer: 1000,
		            showConfirmButton: false
		        });
		}
	});
}
