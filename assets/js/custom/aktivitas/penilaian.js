$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblPenilaian").DataTable({
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
			url: base_url+'aktivitas/penilaian/ajax_list',
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
			{ data:'tanggal_mulai' },
			{ data:'tanggal_akhir' },
			{ data:'petugas1' },
			{ data:'petugas2' },
			{ data:'grup' },
			{ data:'keterangan'},
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

});

function reloadTable() {
	$("#tblPenilaian").DataTable().ajax.reload(null,false);

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

	$("#nama_karyawan,#nama_karyawan1").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
				url: base_url+"aktivitas/penilaian/allKaryawanAjax",
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

	$("#grup").select2({
		placeholder: '-- Pilih Grup --',
		ajax: {
				url: base_url+"aktivitas/penilaian/allKaryawanAjaxGrup",
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

	$('#showPetugas').hide(800);
	$.post(base_url+"setting/getId/1",function(json) {
		if (json.status == true) {
			var option = ""
			if (json.data.periode_penilaian == 1) {
				option += '<option value="periode-1">Periode 1</option>';
			}
			else if (json.data.periode_penilaian == 2) {
				option += '<option value="periode-1">Periode 1</option>'+
									'<option value="periode-2">Periode 2</option>'

			}
			else if (json.data.periode_penilaian == 3) {
				option += '<option value="periode-1">Periode 1</option>'+
									'<option value="periode-2">Periode 2</option>'+
									'<option value="periode-3">Periode 3</option>'
			}
			else if (json.data.periode_penilaian == 4) {
				option += '<option value="periode-1">Periode 1</option>'+
									'<option value="periode-2">Periode 2</option>'+
									'<option value="periode-3">Periode 3</option>'+
									'<option value="periode-4">Periode 4</option>'
			}
			$('#periode').html(option);
		}
	});
});

$('#opsi_petugas').change(function(event) {
	event.preventDefault();
	if ($(this).val() == 0) {
		$('#showPetugas').hide(800);
	}
	else {
		$('#showPetugas').show(800);
	}
});

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$('#showPetugas').hide();
	$(".modal-title").text("Tambah Aktivitas Penilaian");
	$("#nama_karyawan").val("").trigger("change");
	$("#nama_karyawan1").val("").trigger("change");
	$("#grup").val("").trigger("change");
	$("#modalButtonSave").attr("disabled",false);
	$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');
	$("#inputMessage").html("");
	var date = moment(); //Get the current date
	$("#tanggal").val(date.format("YYYY-MM-DD").toString());


	save_method = "add";
});

function btnEdit(id) {
	idData = id;

	$(".modal-title").text("Update Aktivitas Penilaian");
	save_method = "update";

	$.post(base_url+"aktivitas/penilaian/getId/"+idData,function(json) {
		if (json.status == true) {
				$("#modalForm").modal("show");
				// var date = moment(); //Get the current date
				$('#tanggal_mulai').val(json.data.tanggal_mulai);
				$('#tanggal_akhir').val(json.data.tanggal_akhir);
				$("#tanggal").val(json.data.tanggal);
		    $("#keterangan").val(json.data.keterangan);
				$('#opsi_petugas').val(json.data.opsi_petugas);
				$('#periode').val(json.data.periode);
				$("#nama_karyawan").empty().append('<option value="'+json.data.id_petugas1+'">'+json.data.petugas1+'</option>').val(json.data.id_petugas1).trigger('change');
				$("#nama_karyawan1").empty().append('<option value="'+json.data.id_petugas2+'">'+json.data.petugas2+'</option>').val(json.data.id_petugas2).trigger('change');
				$('#grup').empty().append('<option value="'+json.data.id_grup+'">'+json.data.grup+'</option>').val(json.data.id_grup).trigger('change');
				if (json.data.opsi_petugas == 1) {
					$('#showPetugas').show();
				}
		} else {
			$("#inputMessage").html(json.message);
			$('#periode').val("");
			$("#tanggal").val("");
			$("#keterangan").val("");
			$('#opsi_petugas').val("");
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
		url = base_url+'aktivitas/penilaian/add';
	} else {
		url = base_url+'aktivitas/penilaian/update/'+idData;
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
				$("#errorketerangan").html(json.error.keterangan);

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
					$("#errorketerangan").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"aktivitas/penilaian/getId/"+idData,function(json) {
		if (json.status == true) {
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-12'>";
				pesan += "<li class='pull-left'><small>tanggal Mulai: <i>"+json.data.tanggal_mulai+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>tanggal Berakhir: <i>"+json.data.tanggal_akhir+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Petugas 1 : <i>"+json.data.petugas1+"</i></small></li><br>";
				if (json.data.opsi_petugas == 1) {
					pesan += "<li class='pull-left'><small>Petugas 2 : <i>"+json.data.petugas2+"</i></small></li><br>";
				}
				else {
					pesan += "<li class='pull-left'><small>Petugas 2 : <i> - </i></small></li><br>";
				}
				pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangan+"</i></small></li><br>";


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
		    		$.post(base_url+"aktivitas/penilaian/delete/"+idData,function(json) {
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
