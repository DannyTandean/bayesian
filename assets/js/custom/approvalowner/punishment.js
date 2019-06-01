$(document).ready(function() {

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblPunishment").DataTable({
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
			url: base_url+'/approvalowner/punishment/ajax_list',
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
			{ data:'status' },
			{ data:'jabatan' },
			{ data:'nilai' },
			{ data:'keterangan' },
		],
	});
});

function reloadTable() {
	$("#tblPunishment").DataTable().ajax.reload(null,false);
}

$(document).on('click', '#btnRefresh', function(e) {
	e.preventDefault();
	reloadTable();
	$("#btnRefresh").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefresh").children().removeClass("fa-spin");
	}, 1000);
});

var idData;

function btnApproval(id) {
	idData = id;
	$.post(base_url+"approvalowner/punishment/getId/"+idData,function(json) {
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
					pesan += "<li class='pull-left'><small>Nilai : <i>"+json.data.nilai_rp+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangan+"</i></small></li><br>";
					pesan += "</div>";
					pesan += "</div>";

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

			    }).then((result) => {
			    	if (result.value) {
			    		$.post(base_url+"approvalowner/punishment/approve/"+idData,function(json) {
							if (json.status == true) {
								swal({
							            title: json.message,
							            type: "success",
							            timer: 2000,
							            showConfirmButton: false
							        });
								reloadTable();
							} else {
								swal({
							            title: json.message,
							            type: "error",
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
		            timer: 1000,
		            showConfirmButton: false
		        });
		}
	});
}

function btnReject(id) {
	idData = id;
	$.post(base_url+"approvalowner/punishment/getId/"+idData,function(json) {
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
					pesan += "<li class='pull-left'><small>Nilai : <i>"+json.data.nilai_rp+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangan+"</i></small></li><br>";
					pesan += "</div>";
					pesan += "</div>";

			    swal({
			        title: "Apakah anda yakin.?",
			        html: "<span style='color:red;'>Data yang di <b>Reject / Tolak</b> tidak bisa di edit/update lagi.</span>"+pesan,
			        type: "question",
					width: 400,
	  				showCloseButton: true,
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "Iya, Tolak",
			        closeOnConfirm: false,

			    }).then((result) => {
			    	if (result.value) {
			    		$.post(base_url+"approvalowner/punishment/reject/"+idData,function(json) {
							if (json.status == true) {
								swal({
							            title: json.message,
							            type: "success",
							            timer: 2000,
							            showConfirmButton: false
							        });
								reloadTable();
							} else {
								swal({
							            title: json.message,
							            type: "error",
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
		            timer: 1000,
		            showConfirmButton: false
		        });
		}
	});
}