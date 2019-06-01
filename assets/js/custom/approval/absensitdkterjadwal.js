$(document).ready(function() {

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblAbsensiTakTerduga").DataTable({
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
			url: base_url+'approval/absensitdkterjadwal/ajax_list',
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
			{ data:'kabag' },
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'jabatan' },
			{ data:'shift' },
			{ data:'masuk' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'keluar' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

});

function reloadTable() {
	$("#tblAbsensiTakTerduga").DataTable().ajax.reload(null,false);
}

var idData;

$(document).on('click', '#btnRefresh', function(e) {
	e.preventDefault();
	reloadTable();
	$("#btnRefresh").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefresh").children().removeClass("fa-spin");
	}, 1000);
});

$(document).ready(function(){

	$("#nama_karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"aktivitas/dinas/allKaryawanAjax",
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
  // $('#shiftshow').hide(800)
});
//
// $('#jadwalopsi').change(function() {
//   if ($(this).val() == 0) {
//     $('#shiftshow').hide(800)
//   }
//   else {
//     $('#shiftshow').show(800)
//   }
// });

var cekmasuk = 0
var cekkeluar = 0

$('#pickshift').change(function() {
  	$.post(base_url+"approval/absensitdkterjadwal/getIdJadwal/"+$(this).val(),function(json) {
      if (json.status == true) {
          if (cekmasuk == 1) {
            $('#masuk').val(json.data.masuk)
          }
          if (cekkeluar == 1) {
            $('#keluar').val(json.data.keluar)
          }
      }
    })
});

$("#nama_karyawan").change(function() {
	var val = $(this).val();
	if (val != "") {
		$.post(base_url+"/aktivitas/dinas/idKaryawan/"+val,function(json) {
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

function btnApproval(id) {
  $("#modalForm").modal("show")
	$(".modal-title").text("Update Aktivitas Absensi tidak terjadwal");
	save_method = "update";
	idData = id;
	$.post(base_url+"approval/absensitdkterjadwal/getId/"+idData,function(json) {
		if (json.status == true) {
      $('#masuk').val(json.data.masuk)
      $('#keluar').val(json.data.keluar)
      $('#breakin').val(json.data.break_in)
      $('#breakout').val(json.data.break_out)
      $('#tanggal').val(json.data.tanggal)
      $("#nama_karyawan").empty().append('<option value="'+json.data.id+'">'+json.data.nama+'</option>').val(json.data.id).trigger('change');
      if (json.data.keterangan != null && json.data.keterangan != "") {
        $('#inputMessage').html('<div class="alert alert-warning text-center orange"><b>'+json.data.keterangan+'</div><br>');
      }
      else {
        $('#inputMessage').html('<div class="alert alert-warning text-center orange"><b>Tidak ada alasan.</div><br>');
      }
      var option = "";
      $.each(json.jadwal,function(key, val) {
        option +=  "<option value='"+val.id_jadwal+"'>"+val.nama_jadwal+"</option>"
      })
      $('#pickshift').html(option);
      if (json.data.masuk == null) {
        cekmasuk = 1
      }
      if (json.data.keluar == null) {
        cekkeluar = 1
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
	$.post(base_url+"approval/absensitdkterjadwal/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status == "Proses"){ // status masih prosess
				var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Karyawan : <i>"+json.data.nama+"</i></small></li><br>";
        if (json.data.keterangan != null && json.data.keterangan != "") {
          pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangan+"</i></small></li><br>";
        }
        else {
          pesan += "<li class='pull-left'><small>Keterangan : <i> Tidak ada alasan. </i></small></li><br>";
        }
				pesan += "</div>";
				pesan += "</div>";

				swal({
						title: "Apakah anda yakin.?",
						html: "<span style='color:red;'>Data yang di <b>Ditolak</b> tidak bisa di kembali lagi.</span>"+pesan,
						type: "question",
						width: 400,
						showCloseButton: true,
						showCancelButton: true,
				        confirmButtonColor: "#DD6B55",
				        confirmButtonText: "Iya, Tolak",
						// closeOnConfirm: false,
						// background: '#e9e9e9',
				}).then((result) => {
					if (result.value) {
						$.post(base_url+"approval/absensitdkterjadwal/reject/"+idData, function(resp) {
							if (resp.status == true) {
								swal({
										title: resp.message,
										type: "success",
										timer: 2000,
										showConfirmButton: false
									});
								reloadTable();
							} else {
								swal({
										title: resp.message,
										type: "error",
										timer: 1500,
										showConfirmButton: false
									});
								reloadTable();
							}
						});
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

function btnNoJadwal(id) {
	idData = id;
	$.post(base_url+"approval/absensitdkterjadwal/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status == "Proses"){ // status masih prosess
				var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Karyawan : <i>"+json.data.nama+"</i></small></li><br>";
        if (json.data.keterangan != null && json.data.keterangan != "") {
          pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangan+"</i></small></li><br>";
        }
        else {
          pesan += "<li class='pull-left'><small>Keterangan : <i> Tidak ada alasan. </i></small></li><br>";
        }
				pesan += "</div>";
				pesan += "</div>";

				swal({
						title: "Apakah anda yakin.?",
						html: "<span style='color:green;'>Data yang di <b>Diterima</b> tidak bisa di edit/update lagi.</span>"+pesan,
						type: "question",
						width: 400,
						showCloseButton: true,
						showCancelButton: true,
				        confirmButtonColor: "#008000",
				        confirmButtonText: "Iya, Terima",
						// closeOnConfirm: false,
						// background: '#e9e9e9',
				}).then((result) => {
					if (result.value) {
						$.post(base_url+"approval/absensitdkterjadwal/approval/"+idData, function(resp) {
							if (resp.status == true) {
								swal({
										title: resp.message,
										type: "success",
										timer: 2000,
										showConfirmButton: false
									});
								reloadTable();
							} else {
								swal({
										title: resp.message,
										type: "error",
										timer: 1500,
										showConfirmButton: false
									});
								reloadTable();
							}
						});
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

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'approval/absensitdkterjadwal/add';
	} else {
		url = base_url+'approval/absensitdkterjadwal/update/'+idData;
	}

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
				}, 1500);
			} else {
				$("#inputMessage").html(json.message);
				$("#errorKode").html(json.error.kode);
				$("#errorNama").html(json.error.cabang);
				$("#errorAlamat").html(json.error.alamat);
				$("#errorKota").html(json.error.kota);
				$("#errorProvinsi").html(json.error.provinsi);
				$("#errorKode_pos").html(json.error.kode_pos);
				$("#errorNegara").html(json.error.negara);
				$("#errorTelepon").html(json.error.telepon);
				$("#errorFax").html(json.error.fax);
				$("#errorEmail").html(json.error.email);

				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorKode").html("");
					$("#errorNama").html("");
					$("#errorAlamat").html("");
					$("#errorKota").html("");
					$("#errorProvinsi").html("");
					$("#errorKode_pos").html("");
					$("#errorNegara").html("");
					$("#errorTelepon").html("");
					$("#errorFax").html("");
					$("#errorEmail").html("");
				},3000);
			}
		}
	});
});
