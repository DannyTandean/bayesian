$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblDinasApproval").DataTable({
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
			url: base_url+'approval/dinas/ajax_list',
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
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'departemen' },
			{ data:'status' },
			{ data:'jabatan' },
			{ data:'keterangans' },
			{ data:'tgl_dinas' },
			{ data:'akhir_dinas' },
			{ data:'lama' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
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
	$("#mulaiDinas").dateDropper( {
					dropWidth: 200,
					init_animation: "bounce",
					dropPrimaryColor: "#1abc9c",
					dropBorder: "1px solid #1abc9c",
					maxYear: "2050",
					format: "Y-m-d"
			});
	$("#akhirDinas").dateDropper( {
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
	$("#tblDinasApproval").DataTable().ajax.reload(null,false);
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


function btnPrint(id)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");

	$.post(base_url+"approval/dinas/getIdForPrint/"+id,function(json) {
		if (json.status == true) {
				$('#tanggal1').html(json.data.tanggal);
		    $("#keterangan1").html(json.data.keterangans);
				$("#karyawan1").html(json.data.nama);
				$("#mulaiDinas1").html(json.data.tgl_dinas);
				$("#akhirDinas1").html(json.data.akhir_dinas);
				$("#departemen1").html(json.data.departemen);
				$("#jabatan1").html(json.data.jabatan);
				$("#status1").html(json.data.status);
				$("#id_dinas1").html(json.data.id_dinas);
				$("#lama1").html(json.data.lama);

				//setting
				$("#logo").attr('src',json.data.setting.logo);
				$("#emailPerusahaan").attr('href','mailto:'+json.data.setting.email_perusahaan);
				$("#emailPerusahaan").html(json.data.setting.email_perusahaan);
				$("#namaPerusahaan").html(json.data.setting.nama_perusahaan);
				$("#alamat").html(json.data.setting.alamat);
				$("#noTelp").html(json.data.setting.no_telp);
				$("#noFax").html(json.data.setting.no_fax);

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
function btnApproval(id) {
	idData = id;
	$.post(base_url+"approval/dinas/getId/"+idData,function(json) {
		$("#status").val("Diterima");
		if (json.status == true) {

			if(json.data.status == "Proses"){
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Karyawan : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Mulai Dinas : <i>"+json.data.tgl_dinas+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Akhir Dinas : <i>"+json.data.akhir_dinas+"</i></small></li><br>";
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
						// closeOnConfirm: false,
					// background: '#e9e9e9',

				}).then((result) => {
					if (result.value) {
						$.ajax({
							url: base_url+"approval/dinas/update/"+idData,
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

	$.post(base_url+"approval/dinas/getId/"+idData,function(json) {
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
				pesan += "<li class='pull-left'><small>Mulai Dinas : <i>"+json.data.tgl_dinas+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Akhir Dinas : <i>"+json.data.akhir_dinas+"</i></small></li><br>";
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
							url: base_url+"approval/dinas/update/"+idData,
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
