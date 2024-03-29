$(document).ready(function() {
	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

  btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblUser").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		lengthMenu: [[10,50,10000], [10,50,"All"]],
		oLanguage: {
						sZeroRecords: "<center>Data not found</center>",
						sLengthMenu: "Show _MENU_ data   "+btnRefresh,
						sSearch: "Search data:",
						sInfo: "Show: _START_ - _END_ from total: _TOTAL_ data",
						oPaginate: {
								sFirst: "Start", "sPrevious": "Previous",
								sNext: "Next", "sLast": "Last"
						},
				},
		//load data
		ajax: {
			url: base_url+'aktivitas/manage_user/ajax_list',
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
			{ data:'image',
				searchable:false,
				orderable:false,
			 },
			{ data:'nama' },
			{ data:'jenis_kelamin' },
			{ data:'block',
				searchable:false,
				orderable:false,
			},
			{ data:'no_telp' },
			// { data:'email' },
			// { data:'transaction_limit' },
			// { data:'create_at' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
		buttons: [
				'colvis'
		],
	});

});

function reloadTable() {
	$("#tblUser").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

//Select2 Karyawan

$(document).ready(function(){
	$("#nama_karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"aktivitas/manage_user/allKaryawanAjax",
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
		$.post(base_url+"/aktivitas/manage_user/idKaryawan/"+val,function(json) {
			if (json.status == true) {
				$("#detailKaryawan").show(800);
				$("#detail_img_photo").attr("src",json.data.foto);
			   	$('#detailPopUpPhotoOut').attr('href',json.data.foto);
			   	$('#detailPopUpPhotoIn').attr('src',json.data.foto);
			   	$("#kode_karyawan").val(json.data.kode_karyawan);
				$("#departemen").val(json.data.departemen);
				$("#jabatan").val(json.data.jabatan);
				if (json.data.manage_user_diambil != null) {
					$("#sdhDiambil").val(json.data.manage_user_diambil);
				}
				else {
					$("#sdhDiambil").val(0);
				}
				$("#manage_userBersama").val(json.data.manage_user_bersama);
				$("#sisaCuti").val(json.data.sisa_manage_user);
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

function btnDetail(id)
{
  $("#Details").modal("show");
  $(".modal-title").text("Details");

  $.post(base_url+"/aktivitas/manage_user/getId/"+id,function(json) {
    if(json.status == true){
          $("#detail_img_photo").attr("src",json.data.image);
          $('#detailPopUpPhotoOut').attr('href',json.data.image);
          $('#detailPopUpPhotoIn').attr('src',json.data.image);
          $("#nama2").html(json.data.nama);
          $("#jk").html(json.data.jenis_kelamin);
					$('#no_telp2').html(json.data.no_telp);
          $("#email").html(json.data.email);
          $("#trans_limit").html(moneyFormat.to(parseInt(json.data.transaction_limit)));
          $("#tgl_bergabung").html(json.data.create_at);
    } else {
      $("#inputMessage").html(json.message);
      $("#cabang2").html("");
      $("#departemen2").html("");
      $("#jabatan2").html("");
      $("#grup2").html("");
      $("#golongan2").html("");
      $("#keterangan2").html("");
    }
  });
}

function btnBlock(id,status) {
	idData = id;

	$.post(base_url+"aktivitas/manage_user/getId/"+idData,function(json) {
		if (json.status == true) {
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.image+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tanggal Bergabung : <i>"+json.data.create_at+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Nomor Telepon : <i>"+json.data.no_telp+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Email : <i>"+json.data.email+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Transaction Limit : <i>"+moneyFormat.to(parseInt(json.data.transaction_limit))+"</i></small></li><br>";

		    swal({
		        title: "Apakah anda yakin.?",
		        html: "<span style='color:red;'>Data user akan di "+(status == 1 ? "<b>blokir</b>" : "<b>Unblokir</b>")+".</span>"+pesan,
		        type: "warning",
						width: 400,
	  				showCloseButton: true,
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Iya, Block",
		        closeOnConfirm: false,
  				// background: '#e9e9e9',

		    }).then((result) => {
		    	if (result.value) {
		    		$.post(base_url+"aktivitas/manage_user/block/"+idData+"/"+status,function(json) {
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
			}
	});
}

function btnDelete(id) {
	idData = id;

	$.post(base_url+"aktivitas/manage_user/getId/"+idData,function(json) {
		if (json.status == true) {
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.image+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tanggal Bergabung : <i>"+json.data.create_at+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Nomor Telepon : <i>"+json.data.no_telp+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Email : <i>"+json.data.email+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Transaction Limit : <i>"+moneyFormat.to(parseInt(json.data.transaction_limit))+"</i></small></li><br>";

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
		    		$.post(base_url+"aktivitas/manage_user/delete/"+idData,function(json) {
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
			}
	});
}
