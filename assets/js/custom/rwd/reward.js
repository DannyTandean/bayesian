$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblReward").DataTable({
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
			url: base_url+'rwd/reward/ajax_list',
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
			{ data:'status' },
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'departemen' },
			{ data:'jabatan' },
			{ data:'nilai' },
			{ data:'keteranganr' },
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
});

function reloadTable() {
	$("#tblReward").DataTable().ajax.reload(null,false);
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


$(document).ready(function(){

	$("#nama_karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"rwd/reward/allKaryawanAjax",
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

$(document).ready(function () {
    $('#nilai').on('input', function() {
        $('#mata_uang_nilai').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
});

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Reward Karyawan");
	$("#nama_karyawan").val("").trigger("change");
	$('#mata_uang_nilai').html("");
	save_method = "add";
});

$("#nama_karyawan").change(function() {
	var val = $(this).val();
	if (val != "" || val != null) {
		console.log(val);

		$.post(base_url+"/rwd/reward/idKaryawan/"+val,function(json) {
			if (json.status == true) {
				$("#detailKaryawan").show(800);
				$("#detail_img_photo").attr("src",json.data.foto);
			   	$('#detailPopUpPhotoOut').attr('href',json.data.foto);
			   	$('#detailPopUpPhotoIn').attr('src',json.data.foto);
			   	$("#kode_karyawan").val(json.data.kode_karyawan);
			   	$("#no_induk").val(json.data.idfp);
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
			   	$("#no_induk").val("");
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
		$("#no_induk").val("");
		$("#departemen").val("");
		$("#jabatan").val("");
	}
});

$("#btnDetailZoomImg").click(function() {
	$("#detailPopUpPhotoOut").click();
});

function btnEdit(id) {
	idData = id;
	$(".modal-title").text("Update Rewards Karyawan");
	save_method = "update";

	$.post(base_url+"rwd/reward/getId/"+idData,function(json) {
		if (json.status == true) {
			if (json.data.status == "Diterima") {
				swal({
									title: "<h2 style='color:orange;'>Status yang <u style='color:green;'>Diterima</u> tidak bisa di Edit.!</h2>",
									type: "warning",
									timer: 3000,
									showConfirmButton: false
							});
			} else if (json.data.status == "Proses") {
				$("#modalForm").modal("show");
				$('#tanggal').val(json.data.tanggal);
		        $("#keterangan").val(json.data.keteranganr);
		        $("#nilai").val(json.data.nilai);
				$("#karyawan").val(json.data.id);
				$("#departemen").val(json.data.departemen);
				$("#jabatan").val(json.data.jabatan);
				$("#nama_karyawan").empty().append('<option value="'+json.data.id_karyawan+'">'+json.data.nama+'</option>').val(json.data.id_karyawan).trigger('change');

			}else{
				swal({
			            title: "<h2 style='color:orange;'>Data yang sudah <u style='color:red;'>Ditolak</u> tidak bisa di update.!</h2>",
			            type: "warning",
			            timer: 3000,
			            showConfirmButton: false
			        });
			}
		} else {
			$("#inputMessage").html(json.message);

			$('#tanggal').val("");
			$("#keterangan").val("");
			$("#karyawan").val("");
			$("#nilai").val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});

}

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'rwd/reward/add';
	} else {
		url = base_url+'rwd/reward/update/'+idData;
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

				// swal({
			 //            title: json.message,
			 //            type: "success",
			 //            timer: 2000,
			 //            showConfirmButton: false
			 //        });

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
				$("#errorKaryawan").html(json.error.karyawan);
				$("#errorNilai").html(json.error.nilai);

				// swal({
			 //            title: "Error Form.!",
			 //            html: json.message,
			 //            type: "error",
			 //        });

				setTimeout(function() {
					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');
					$("#inputMessage").html("");
					$("#errorTanggal").html("");
					$("#errorKaryawan").html("");
					$("#errorNilai").html("");
					$("#errorKeterangan").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"rwd/reward/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status == "Proses" || json.data.status == "Ditolak") {
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
					pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keteranganr+"</i></small></li><br>";
					pesan += "</div>";
					pesan += "</div>";

			    swal({
			        title: "Apakah anda yakin.?",
			        html:"<span style='color:red;'>Data yang sudah di <b>Hapus</b> tidak bisa dikembalikan lagi.</span>"+pesan,
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
			    		$.post(base_url+"rwd/reward/delete/"+idData,function(json) {
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
									title: "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa di Hapus.!</h2>",
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