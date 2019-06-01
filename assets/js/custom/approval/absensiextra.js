$(document).ready(function() {

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblAbsensiLembur").DataTable({
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
			url: base_url+'approval/absensiextra/ajax_list',
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
			{ data:'tanggal' },
      { data:'kode_kabag' },
			{ data:'nama' },
			{ data:'masuk' },
			{ data:'keluar' },
      { data:'jabatan' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

});

function reloadTable() {
	$("#tblAbsensiLembur").DataTable().ajax.reload(null,false);
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
  idData = id;
	$.post(base_url+"approval/absensiextra/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status == "Proses"){ // status masih prosess
				var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Karyawan : <i>"+json.data.nama+"</i></small></li><br>";
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
						$.post(base_url+"approval/absensiextra/approval/"+idData, function(resp) {
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

function btnReject(id) {
	idData = id;
	$.post(base_url+"approval/absensiextra/getId/"+idData,function(json) {
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
						$.post(base_url+"approval/absensiextra/reject/"+idData, function(resp) {
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
