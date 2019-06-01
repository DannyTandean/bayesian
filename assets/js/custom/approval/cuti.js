$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblIzinCuti").DataTable({
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
			url: base_url+'approval/cuti/ajax_list',
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
			{ data:'departemen' },
			{ data:'jabatan' },
			{ data:'status' },
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
	$("#tblIzinCuti").DataTable().ajax.reload(null,false);
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
	$("#inputMessage").html("");
	$("#nama_karyawan").val("").trigger("change");
	$(".modal-title").text("Tambah Aktivitas Cuti");
	$('#mata_uang_gajiGol').html("");
	save_method = "add";
});


function btnPrint(id)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");

	$.post(base_url+"approval/cuti/getIdForPrint/"+id,function(json) {
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
				$("#tanggal").prop('disabled',true);
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
			$("#mulaiCuti").val("");
			$("#akhirCuti").val("");
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
	$.post(base_url+"approval/cuti/getId/"+idData,function(json) {
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
				pesan += "<li class='pull-left'><small>Mulai Cuti : <i>"+json.data.mulai_berlaku+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Akhir Cuti : <i>"+json.data.exp_date+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Lama Cuti : <i>"+json.data.lama_cuti+"</i></small></li><br>";
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
						var splturl = base_url.split('/')
						var saving = splturl[splturl.length-2]
						$.ajax({
							url: "https://primahrd.com:468/port",
							type: 'POST',
							data : {id : saving},
							dataType: 'JSON',
							headers: {
									 'Access-Control-Allow-Origin': '*',
									 'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
									 'Access-Control-Allow-Headers': 'Content-Type'
							 },
							 success: function(port){
								 if (port.status == true) {
											 $.ajax({
					 							url: "https://primahrd.com:"+(parseInt(port.data[0].port)+80)+"/approvalcuti/approval",
					 							type:'PUT',
					 							data:{id_cuti : idData, status : $('#status').val()},
					 							headers: {
					 									 'Access-Control-Allow-Origin': '*',
					 									 'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
					 									 'Access-Control-Allow-Headers': 'Content-Type',
														 'Authorization' : "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1hIjoiVGVzMSIsImphYmF0YW4iOiJBZG1pbiBLYW50b3IiLCJpYXQiOjE1MzU5NDIxODV9.tNDAblikreV6R1VUPGvTNCqgoTPEbCRTUyR0987Hku4"
					 							 },
					 							dataType:'JSON',
					 							success: function(json) {
					 						if (json.status == true) {
												const Toast = Swal.mixin({
												  toast: true,
												  position: 'top-end',
												  showConfirmButton: false,
												  timer: 1500
												});
												Toast.fire({
												  type: 'success',
												  title: "<span style='color:green'>"+json.message + "</span>"
												})
					 							reloadTable();
					 						} else {
												const Toast = Swal.mixin({
												  toast: true,
												  position: 'top-end',
												  showConfirmButton: false,
												  timer: 1500
												});
												Toast.fire({
												  type: 'error',
												  title: "<span style='color:red'>"+json.message + "</span>"
												})
					 							reloadTable();
					 						}
					 					}
					 				});
								 }
								 else {
									 const Toast = Swal.mixin({
										 toast: true,
										 position: 'top-end',
										 showConfirmButton: false,
										 timer: 1500
									 });
									 Toast.fire({
										 type: 'error',
										 title: "<span style='color:red'>"+port.message + "</span>"
									 })
								 }
							 }
						}); // end ajax port
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

	$.post(base_url+"approval/cuti/getId/"+idData,function(json) {
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
				pesan += "<li class='pull-left'><small>Mulai Cuti : <i>"+json.data.mulai_berlaku+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Akhir Cuti : <i>"+json.data.exp_date+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Lama Cuti : <i>"+json.data.lama_cuti+"</i></small></li><br>";
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
						var splturl = base_url.split('/')
						var saving = splturl[splturl.length-2]
						$.ajax({
							url: "https://primahrd.com:468/port",
							type: 'POST',
							data : {id : saving},
							dataType: 'JSON',
							headers: {
									 'Access-Control-Allow-Origin': '*',
									 'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
									 'Access-Control-Allow-Headers': 'Content-Type'
							 },
							 success: function(port){
								 if (port.status == true) {
											 $.ajax({
												url: "https://primahrd.com:"+(parseInt(port.data[0].port)+80)+"/approvalcuti/approval",
												type:'PUT',
												data:{id_cuti : idData, status : $('#status').val()},
												headers: {
														 'Access-Control-Allow-Origin': '*',
														 'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
														 'Access-Control-Allow-Headers': 'Content-Type',
														 'Authorization' : "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1hIjoiVGVzMSIsImphYmF0YW4iOiJBZG1pbiBLYW50b3IiLCJpYXQiOjE1MzU5NDIxODV9.tNDAblikreV6R1VUPGvTNCqgoTPEbCRTUyR0987Hku4"
												 },
												dataType:'JSON',
												success: function(json) {
											if (json.status == true) {
												const Toast = Swal.mixin({
													toast: true,
													position: 'top-end',
													showConfirmButton: false,
													timer: 1500
												});
												Toast.fire({
													type: 'success',
													title: "<span style='color:green'>"+json.message + "</span>"
												})
												reloadTable();
											} else {
												const Toast = Swal.mixin({
													toast: true,
													position: 'top-end',
													showConfirmButton: false,
													timer: 1500
												});
												Toast.fire({
													type: 'error',
													title: "<span style='color:red'>"+json.message + "</span>"
												})
												reloadTable();
											}
										}
									});
								 }
								 else {
									 const Toast = Swal.mixin({
										 toast: true,
										 position: 'top-end',
										 showConfirmButton: false,
										 timer: 1500
									 });
									 Toast.fire({
										 type: 'error',
										 title: "<span style='color:red'>"+port.message + "</span>"
									 })
								 }
							 }
						}); // end ajax port
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
