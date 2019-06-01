$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblGolongan").DataTable({
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
			url: base_url+'approvalowner/golongan/ajax_list',
			type: 'POST',
		},

		order:[[16,'DESC']],
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
			{
				data:'button_tools',
				searchable:false,
				orderable:false,
			},
			{ data:'golongan' },
			{ data:'gaji' },
			{ data :'status' },
			{ data :'status_info' },
			{ data:'makan' },
			// { data:'kehadiran' },
			{ data:'transport' },
			{ data:'thr' },
			// { data:'tundip' },
			{ data:'tunjangan_lain' },
			// { data:'absen' },
			{ data:'bpjs' },
			{ data:'pot_lain' },
			{ data:'lembur_tetap' },
			{ data:'umk' },
			{ data:'keterangan' },
			{ data:'create_at' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
});

function reloadTable() {
	$("#tblGolongan").DataTable().ajax.reload(null,false);
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

function btnDetail(id)
{
	$("#Details").modal("show");
	$(".modal-title").text("Details");

	$.post(base_url+"approvalowner/golongan/getId2Model/"+id,function(json) {
		if(json.status == true){
					$("#golongan2").html(json.data.golongan);
					$("#keterangan2").html(json.data.keterangan);
					$("#gaji2").html(json.data.gaji);
					$("#gaji_menit2").html(json.data.gaji_menit);
					$("#makan2").html(json.data.makan);
					$("#transport2").html(json.data.transport);
					$("#thr2").html(json.data.thr);
					// $("#tundip2").html(json.data.tundip);
					$("#tunjangan_lain2").html(json.data.tunjangan_lain);
					// $("#absen2").html(json.data.absen);
					$("#bpjs2").html(json.data.bpjs);
					$("#pot_lain2").html(json.data.pot_lain);
					$("#lembur_tetap2").html(json.data.lembur_tetap);
					$("#umk2").html(json.data.umk);
					$("#tglDetail").html(json.data.approve_at);
		} else {
			$("#inputMessage").html(json.message);

			$("#golongan2").html("");
			$("#keterangan2").html("");
			$("#gaji2").html("");
			// $("#kehadiran2").html("");
			$("#makan2").html("");
			$("#transport2").html("");
			$("#thr2").html("");
			// $("#tundip2").html("");
			$("#tunjangan_lain2").html("");
			// $("#absen2").html("");
			$("#bpjs2").html("");
			$("#pot_lain2").html("");
			$("#lembur_tetap2").html("");
			$("#umk2").html("");
			// setTimeout(function() {
			// 	reloadTable();
			// 	$("#Print").modal("hse");
			// },1000);
		}
	});
}

function btnApproval(id) {
	idData = id;
	$.post(base_url+"approvalowner/golongan/getId/"+idData,function(json) {
		if (json.status == true) {
			$("#status").val("Diterima");
			$("#statusInfo").val(json.data.status_info);
			$("#id_golongan").val(json.data.id_golongan);
			if(json.data.status == "Proses"){
			var pesan = "<hr>";
			pesan += "<li class='pull-left'><small>Golongan : <i>"+json.data.golongan+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>gajiGolongan : <i>"+json.data.gaji+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangan+"</i></small></li><br>";

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
							url: base_url+"approvalowner/golongan/update/"+idData,
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
							title: "<h2 style='color:orange;'>Status yang sudah <u style='color:red;'>Ditolak</u> tidak bisa Diterima lagi.!</h2>",
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

	$.post(base_url+"approvalowner/golongan/getId/"+idData,function(json) {

		if (json.status == true) {
				$("#status").val("Ditolak");
			if(json.data.status == "Proses"){
			var pesan = "<hr>";
					pesan += "<li class='pull-left'><small>Golongan : <i>"+json.data.golongan+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>gajiGolongan : <i>"+json.data.gaji+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangan+"</i></small></li><br>";

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
  				// background: '#e9e9e9',

		    }).then((result) => {
		    	if (result.value) {
						$.ajax({
							url: base_url+"approvalowner/golongan/update/"+idData,
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
