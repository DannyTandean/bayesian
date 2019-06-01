$(document).ready(function() {
	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblProduksi").DataTable({
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
			url: base_url+'pekerjaborongan/produksi/ajax_list',
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
			{ data:'jumlah' },
			{ data:'harga',
			  searchable:false,
			  orderable:false, },
			{ data:'pendapatan' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
	$("#tanggal,#tanggal1").dateDropper( {
					dropWidth: 200,
					init_animation: "bounce",
					dropPrimaryColor: "#1abc9c",
					dropBorder: "1px solid #1abc9c",
					maxYear: "2050",
					format: "Y-m-d"
			});
});

function reloadTable() {
	$("#tblProduksi").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

$(document).ready(function(){

	$("#nama_karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"pekerjaborongan/produksi/allKaryawanAjax",
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

	$("#nama_karyawan1").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"pekerjaborongan/produksi/allKaryawanAjax",
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
		dropdownParent: $('#editModal')
	});

});

$(document).ready(function(){

	$("#departemen").select2({
		placeholder: '-- Pilih Item --',
		ajax: {
		   	url: base_url+"pekerjaborongan/produksi/allDepartemenAjax",
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
		dropdownParent: $('#editModal')
	});
});

$(document).ready(function () {
    $('#jumlah').on('input', function() {
    	var harga = $('#harga').val();
    	var jumlah = $('#jumlah').val();
    	if (harga != '' && jumlah != '') {
    		var total = parseInt(harga) * parseInt(jumlah);
    		$('#total').val( moneyFormat.to( parseInt(total) ) );
    	}
    });
});

$("#nama_karyawan1").change(function() {
	var val = $(this).val();
	if (val != "") {
		$.post(base_url+"/pekerjaborongan/produksi/idKaryawan/"+val,function(json) {
			if (json.status == true) {
				$("#detailKaryawan").show(800);
			   	$("#kode_karyawan").val(json.data.idfp);
				// $("#departemen").val(json.data.departemen);
				// $("#harga").val(json.data.harga);

			} else {
				$("#detailKaryawan").hide(800);
				$("#errorDetailKaryawan").html(json.message);
			   	$("#kode_karyawan").val("");
				// $("#departemen").val("");
				// $("#harga").val("");
			}
		});
	} else {
		$("#detailKaryawan").hide(800);
		$("#errorDetailKaryawan").html("");
	   	$("#kode_karyawan").val("");
		// $("#departemen").val("");
		// $("#harga").val("");
	}
	$("#editModal").css('overflow-y', 'scroll');
});

$("#departemen").change(function() {
	var val = $(this).val();
	if (val != "") {
		$.post(base_url+"/pekerjaborongan/produksi/idDepartemen/"+val,function(json) {
			if (json.status == true) {


				var harga = $("#harga").val(json.data.harga);
				var jumlah = $('#jumlah').val();

		    	if (harga != '' && jumlah != '') {
		    		var total = parseInt(json.data.harga) * parseInt(jumlah);
		    		$('#total').val( moneyFormat.to( parseInt(total) ) );
		    	}
			} else {

				$("#harga").val("");

			}
		});
	} else {

		$("#harga").val("");
	}
	$("#modalForm").css('overflow-y', 'scroll');
});

$(document).on('click', '#btnRefresh', function(e) {
	e.preventDefault();
	reloadTable();
	$("#btnRefresh").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefresh").children().removeClass("fa-spin");
	}, 1000);
});

function getPendapatan(val,harga,ids) {
	$('#item'+ids).val(money.to(parseInt(val)*parseInt(harga)));
	var total = 0;
	if (val == false) {
		$('#item'+ids).val(0);
	}
	for (var i = 1; i <= $('#allitemcount').val(); i++) {
		if ($('#item'+i).val() == 0 || $('#item'+i).val() == "") {
 			continue;
		}
		var split = String($('#item'+i).val()).split('.');
		var join = split.join();
		var split1 = join.split(',');
		var intSplit = "";
		for (var j = 0; j < split1.length; j++) {
			if (split1[i] == false) {
				continue;
			}
				intSplit += split1[j];
		}
		// var intSplit = parseInt(split[0]+split[1]);
		total += parseInt(intSplit);

	}
	$('#totalPendapatan').html(moneyFormat.to(parseInt(total)));
}

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Produksi Pekerja");
	$("#tanggal").prop('disabled',false);
	$("#nama_karyawan").val("").trigger("change");
	save_method = "add";
});

function btnEdit(id) {
	idData = id;

	$("#editModal").modal("show");
	$(".modal-title").text("Update Produksi Pekerja");
	save_method = "update";
	// $("#tanggal").prop('disabled',true);
	$.post(base_url+"pekerjaborongan/produksi/getId/"+idData,function(json) {
		if (json.status == true) {
				$('#tanggal').val(json.data.tanggal);
				$("#kode_karyawan").val(json.data.idfp);
				$("#departemen").val(json.data.departemen);
				$("#harga").val(json.data.harga);
				$("#jumlah").val(json.data.jumlah);

				$("#departemen").empty().append('<option value="'+json.data.id_departemen+'">'+json.data.departemen+'</option>').val(json.data.id_departemen).trigger('change');
				// $("#departemen").val(json.data.id_departemen);
				$("#total").val(json.data.pendapatan);
				$("#nama_karyawan1").empty().append('<option value="'+json.data.id_pekerja+'">'+json.data.nama+'</option>').val(json.data.id_pekerja).trigger('change');

		} else {
			$(".inputMessage").html(json.message);
			$('#tanggal').val("");
			$("#kode_karyawan").val("");
			$("#departemen").val("");
			$("#harga").val("");
			$("#jumlah").val("");
			$("#departemen").val("");
			$("#total").val("");
			setTimeout(function() {
				reloadTable();
				$("#editModal").modal("hide");
			},1000);
		}
	});

}

$("#modalButtonSave,#editBtn").click(function() {
	var url;
	var data;
	if (save_method == "add") {
		url = base_url+'pekerjaborongan/produksi/add';
		data = $("#formData").serialize();
	} else {
		url = base_url+'pekerjaborongan/produksi/update/'+idData;
		data = $("#formDataEdit").serialize();
	}
	$.ajax({
		url: url,
		type:'POST',
		data: data,
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				$(".inputMessage").html(json.message);


				/*swal({
			            title: json.message,
			            type: "success",
			            timer: 2000,
			            showConfirmButton: false
			        });*/

				setTimeout(function() {
					$("#formData")[0].reset();
					$("#modalForm").modal("hide");
					$("#formDataEdit")[0].reset();
					$("#editModal").modal("hide");
					$(".inputMessage").html("");
					reloadTable();
				}, 2000);
			} else {
				$(".inputMessage").html(json.message);
				$("#errorTanggal1").html(json.error.tanggal);
				$("#errorTanggal").html(json.error.tanggal);
				$("#errorKaryawan").html(json.error.karyawan);
				// $("#errorDepartemen").html(json.error.departemen);
				// $("#errorJumlah").html(json.error.jumlah);

				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$(".inputMessage").html("");
					$("#errorTanggal").html("");
					$("#errorTanggal1").html("");
					$("#errorKaryawan").html("");
					$("#errorDepartemen").html("");
					$("#errorJumlah").html("");

				},2000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"pekerjaborongan/produksi/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Pekerja : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>NIK : <i>"+json.data.idfp+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>jumlah produksi : <i>"+json.data.jumlah+"</i></small></li><br>";

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
		    		$.post(base_url+"pekerjaborongan/produksi/delete/"+idData,function(json) {
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
