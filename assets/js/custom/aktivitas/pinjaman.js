$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblPinjaman").DataTable({
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
			url: base_url+'aktivitas/pinjaman/ajax_list',
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
			{ data:'jabatan' },
			{ data:'jumlah' },
			{ data:'bayar' },
			{ data:'sisa' },
			{ data:'cara_pembayaran' },
			{ data:'cicilan' },
			{ data:'keterangans' },
			{ data:'status' }
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

	$("#tblPembayaran").DataTable({
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
			url: base_url+'aktivitas/pinjaman/ajax_list_pembayaran/Diterima',
			type: 'POST',
		},

		order:[[2,'ASC']],
		columns:[
			{
				data:'no',
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
			{ data:'jabatan' },
			{ data:'jumlah' },
			{ data:'bayar' },
			{ data:'sisa' },
			{ data:'cara_pembayaran' },
			{ data:'cicilan' },
			{ data:'keterangans' },
			{ data:'status' }
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

	$("#tblLogPembayaran").DataTable({
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
			url: base_url+'aktivitas/pinjaman/ajax_list_log_pembayaran',
			type: 'POST',
		},

		order:[[1,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'departemen' },
			{ data:'jabatan' },
			{ data:'jumlah' },
			{ data:'pembayaran' },
			{ data:'sisa' },
			{ data:'cara_pembayaran' },
			{ data:'cicilan' },
			{ data:'keterangan' },
		
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
	$("#tblLogPembayaran").DataTable().ajax.reload(null,false);

}

var save_method;
var idData;

//select2 karyawan ajax

$(document).ready(function(){

	$("#nama_karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"aktivitas/pinjaman/allKaryawanAjax",
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
		$.post(base_url+"/aktivitas/pinjaman/idKaryawan/"+val,function(json) {
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


$(document).ready(function () {
	$("#pinjaman1").click(function(){
			$("#pinjaman").show();
			$("#pembayaran").hide();
			$("#logpembayaran").hide();
	});

	$("#pembayaran1").click(function(){
			$("#pembayaran").show();
			$("#pinjaman").hide();
			$("#logpembayaran").hide();
	});

	$("#logpembayaran1").click(function(){
			$("#pembayaran").hide();
			$("#pinjaman").hide();
			$("#logpembayaran").show();
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

	$.post(base_url+"aktivitas/pinjaman/getIdForPrint/"+id,function(json) {
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
			// 	$("#Print").modal("hide");
			// },1000);
		}
	});
}
function btnPaid(id){
	$("#Payment").modal("show");
	$(".modal-title").text("Payment");
	idData = id;
	$.post(base_url+"aktivitas/pinjaman/getId/"+idData,function(json) {
		if (json.status == true) {
				$('#tanggal1').val(json.data.tanggal);
		   		$("#keterangan1").val(json.data.keterangans);
				$("#karyawan1").val(json.data.id);
				$("#jlhPinjaman1").val(json.data.jumlah);
				$("#departemen1").val(json.data.departemen);
				$("#jabatan1").val(json.data.jabatan);
				$("#sisaPinjaman").val(json.data.sisa);
				$("#karyawan1").prop('disabled',true);
		} else {
			$("#inputMessage").html(json.message);

			$('#tanggal1').val("");
			$("#keterangan1").val("");
			$("#karyawan1").val("");
			$("#jlhPinjaman1").val("");
			$("#caraPembayaran1").val("");
			$("#departemen1").val("");
			$("#jabatan1").val("");
			setTimeout(function() {
				reloadTable();
				$("#Payment").modal("hide");
			},1000);
		}
	});
}
function btnEdit(id) {
	idData = id;

	$(".modal-title").text("Update Aktivitas Pinjaman");
	save_method = "update";

	$.post(base_url+"aktivitas/pinjaman/getId/"+idData,function(json) {
		if (json.status == true) {

			if (json.data.status == "Diterima") {
				swal({
									title: "<h2 style='color:#f1c40f;'>Status <u style='color:green'>Diterima</u> tidak bisa di update.!</h2>",
									type: "error",
									timer: 4000,
									showConfirmButton: false
							});
			}else if (json.data.status == "Proses") {
				$("#modalForm").modal("show");
				var date = moment(); //Get the current date
				$("#tanggal").val(date.format("YYYY-MM-DD").toString());
		    $("#keterangan").val(json.data.keterangans);
				$("#jlhPinjaman").val(json.data.jumlah);
				$("#caraPembayaran").val(json.data.cara_pembayaran);
				$("#cicilan").val(json.data.cicilan);
				$("#nama_karyawan").empty().append('<option value="'+json.data.id_karyawan+'">'+json.data.nama+'</option>').val(json.data.id_karyawan).trigger('change');

				if(json.data.cara_pembayaran == "Cash")
				{
					$("#cicilanDisplay").hide();
				}
				else {
					$("#cicilanDisplay").show();
				}
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
			}else{
				swal({
			            title: "<h2 style='color:#f1c40f;'>Status <u style='color:red'>Ditolak</u> tidak bisa di update.!</h2>",
			            type: "error",
			            timer: 4000,
			            showConfirmButton: false
			        });
			}
		} else {
			$("#inputMessage").html(json.message);

			$('#tanggal').val("");
			$("#keterangan").val("");
			$("#karyawan").val("");
			$("#jlhPinjaman").val("");
			$("#caraPembayaran").val("");
			$("#cicilan").val("");
			$("#departemen").val("");
			$("#jabatan").val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});

}

$("#payment").click(function() {
	url = base_url+'aktivitas/pinjaman/updatePinjamans/'+idData;

	$("#payment").attr("disabled",true);
	$("#payment").html("Processing...<i class='fa fa-spinner fa-spin'></i>");
	$.ajax({
		url: url,
		type:'POST',
		data:$("#formDataPay").serialize(),
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				$("#inputMessage1").html(json.message);


				/*swal({
			            title: json.message,
			            type: "success",
			            timer: 2000,
			            showConfirmButton: false
			        });*/

				setTimeout(function() {
					$("#formDataPay")[0].reset();
					$("#Payment").modal("hide");
					$("#inputMessage1").html("");
					reloadTable();
					$("#payment").attr("disabled",false);
					$("#payment").html('<i class="fa fa-save"></i> Simpan');
				}, 1500);
			} else {
				$("#inputMessage1").html(json.message);
				$("#errorTanggal1").html(json.error.tanggal);
				$("#errorJlhPinjaman1").html(json.error.jlhPinjaman);
				$("#errorOpsi1").html(json.error.caraPembayaran);
				$("#errorKaryawan1").html(json.error.karyawan);
				$("#errorKeterangan1").html(json.error.keterangans);
				$("#errorPembPinjaman").html(json.error.pembpinjaman);

				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$("#payment").attr("disabled",false);
					$("#payment").html('<i class=" fa fa-save"></i> Simpan')
					$("#inputMessage").html("");
					$("#errorTanggal").html("");
					$("#errorJlhPinjaman").html("");
					$("#errorPembPinjaman").html("");
					$("#errorKaryawan").html("");
					$("#errorKeterangan").html("");
				},3000);
			}
		}
	});
});

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'aktivitas/pinjaman/add';
	} else {
		url = base_url+'aktivitas/pinjaman/update/'+idData;
	}

	$("#modalButtonSave").attr("disabled",true);
	$("#modalButtonSave").html("Processing...<i class='fa fa-spinner fa-spin'></i>");

	$.ajax({
		url: url,
		type:'POST',
		data:$("#formData").serialize(),
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
				$("#errorTanggal").html(json.error.tanggal);
				$("#errorJlhPinjaman").html(json.error.jlhPinjaman);
				$("#errorOpsi").html(json.error.caraPembayaran);
				$("#errorKaryawan").html(json.error.karyawan);
				$("#errorKeterangan").html(json.error.keterangans);

				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {

					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');
					
					$("#inputMessage").html("");
					$("#errorTanggal").html("");
					$("#errorJlhPinjaman").html("");
					$("#errorOpsi").html("");
					$("#errorKaryawan").html("");
					$("#errorKeterangan").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"aktivitas/pinjaman/getId/"+idData,function(json) {
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
		    		$.post(base_url+"aktivitas/pinjaman/delete/"+idData,function(json) {
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
									title: "<h2 style='color:#c82333;'>Status <u style='color:green'>Diterima</u> tidak bisa di Hapus.!</h2>",
									type: "error",
									timer: 4000,
									showConfirmButton: false
							});
			}else{
				$.post(base_url+"aktivitas/pinjaman/getId/"+idData,function(json) {
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
								}).then((result) => {
						    	if (result.value) {
						    		$.post(base_url+"aktivitas/pinjaman/delete/"+idData,function(json) {
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
