$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblPinjaman").DataTable({
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
			url: base_url+'approvalowner/pinjaman/ajax_list',
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
			{ data:'jumlah' },
			{ data:'bayar' },
			{ data:'sisa' },
			{ data:'cara_pembayaran' },
			{ data:'cicilan' },
			{ data:'keterangans' }
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

			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		    $.fn.dataTable
		        .tables( { visible: true, api: true } )
		        .columns.adjust();
		});
});

function reloadTable() {
	$("#tblPinjaman").DataTable().ajax.reload(null,false);
	$("#tblPembayaran").DataTable().ajax.reload(null,false);
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


$(document).ready(function () {
	var date = moment(); //Get the current date
	$("#tanggal").val(date.format("YYYY-MM-DD").toString());
	$("#pinjaman1").click(function(){
			$("#pinjaman").show();
			$("#pembayaran").hide();
	});

	$("#pembayaran1").click(function(){
			$("#pembayaran").show();
			$("#pinjaman").hide();
	});

  $('#jlhPinjaman').on('input', function() {
      $('#mata_uang_pinjaman').html( moneyFormat.to( parseInt($(this).val()) ) );
  });

	$('#PembPinjaman').on('input', function() {
      $('#mata_uang_pembPinjaman').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
});
$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Aktivitas Pinjaman");
	$("#nama_karyawan").val("").trigger("change");
	var date = moment(); //Get the current date
	$("#tanggal").val(date.format("YYYY-MM-DD").toString());
	//ajax karyawan


	// Opsi pembayaran
	$("#cicilanDisplay").hide();
	$("#caraPembayaran").change(function(){
		var val = $(this).val();
		if(val == "Potongan Gaji")
		{
			$("#cicilanDisplay").show();
		}
		else{
			$("#cicilanDisplay").hide();
		}
	});

	save_method = "add";
});
function btnPrint(id)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");

	$.post(base_url+"approvalowner/pinjaman/getId/"+id,function(json) {
		if (json.status == true) {
				$('#tanggal11').html(json.data.tanggal);
				$("#karyawan11").html(json.data.nama);
				$("#penerima").html(json.data.nama);
				$("#jumlah1").html(moneyFormat.to( parseInt(json.data.jumlah)));
				$("#caraPembayaran1").html(json.data.cara_pembayaran);
				$("#departemen11").html(json.data.departemen);
				$("#jabatan11").html(json.data.jabatan);
				$("#status11").html(json.data.status);
				$("#id_pinjaman1").html(json.data.id_pinjaman);
				$("#keterangan11").html(json.data.keterangans);
				if(json.data.sisa == "")
				{
					$("#sisaPinjaman11").html("Rp 0,00 ");
				}
				else {
					$("#sisaPinjaman11").html(moneyFormat.to( parseInt(json.data.sisa)));
				}
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
			// 	$("#Print").modal("hide");
			// },1000);
		}
	});
}

function btnApproval(id) {
	idData = id;

	$(".modal-title").text("Update Aktivitas Pinjaman");
	save_method = "update";

	$.post(base_url+"approvalowner/pinjaman/getId/"+idData,function(json) {
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
					pesan += "<li class='pull-left'><small>Jumlah : <i>"+json.data.jumlah+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Bayar : <i>"+json.data.bayar+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Sisa : <i>"+json.data.sisa+"</i></small></li><br>";
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
							url: base_url+"approvalowner/pinjaman/update/"+idData,
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
							title: "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa Diterima lagi.!</h2>",
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

	$.post(base_url+"approvalowner/pinjaman/getId/"+idData,function(json) {
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
				pesan += "<li class='pull-left'><small>Jumlah : <i>"+json.data.jumlah+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Bayar : <i>"+json.data.bayar+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Sisa : <i>"+json.data.sisa+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangans+"</i></small></li><br>";

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
							url: base_url+"approvalowner/pinjaman/update/"+idData,
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
