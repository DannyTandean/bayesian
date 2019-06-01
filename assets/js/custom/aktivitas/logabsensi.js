$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";
	btnTambahNormal = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambahNormal'><i class='fa fa-plus'></i> Tambah</button>";
    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";


	$("#tblKerjaNormal").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		lengthMenu: [[50,10000], [50,"All"]],
		oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data   "+btnTambahNormal+btnRefresh,
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
		//load data
		ajax: {
			url: base_url+'aktivitas/LogAbsensi/ajax_list',
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
			{ data:'token' },
			{ data:'payment' },
			{ data:'nama_jadwal' },
			{ data:'masuk' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'keluar' },
			{ data:'idfp' },
			{ data:'jabatan' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

	$("#tblKerjaExtra").DataTable({
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
			url: base_url+'aktivitas/LogAbsensi/ajax_list_extra',
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
			{ data:'masuk' },
			{ data:'keluar' },
			{ data:'total' },
			{ data:'payment' },
			{ data:'kode_kabag' },
			{ data:'idfp' },
			{ data:'jabatan' },

		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

	$("#tblKerjaDisiplin").DataTable({
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
			url: base_url+'aktivitas/LogAbsensi/ajax_list_disiplin',
			type: 'POST',
		},

		order:[[2,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'idfp' },
			{ data:'nama' },
			{ data:'jabatan' },
			{ data:'total',
			  searchable : false,
			  orderable : false, },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable
        .tables( { visible: true, api: true } )
        .columns.adjust();
});

$(document).ready(function() {
	/*for kerja normal*/
	ajaxSearchSelect2("kabag","Kabag",base_url+"/aktivitas/LogAbsensi/ajaxAllKaryawanSelect2/2","modalForm");
    ajaxSearchSelect2("karyawan","Karyawan",base_url+"/aktivitas/LogAbsensi/ajaxAllKaryawanSelect2","modalForm");

	/*for kerja extra*/
	ajaxSearchSelect2("kabag11","Kabag",base_url+"/aktivitas/LogAbsensi/ajaxAllKaryawanSelect2/2","TambahExtra");
    ajaxSearchSelect2("karyawan11","Karyawan",base_url+"/aktivitas/LogAbsensi/ajaxAllKaryawanSelect2","TambahExtra");

});

$("#btnDetailZoomImg").click(function() {
	$("#detailPopUpPhotoOut").click();
});

$("#btnDetailZoomFile").click(function() {
	$("#detailPopUpFileOut").click();
});

//validasi tanggal
$("#before, #after").change(function() {
	if($("#before").val() != "" && $("#after").val() != ""){
		var startDate = new Date($('#before').val());
		var expiredDate = new Date($('#after').val());
		if (startDate > expiredDate){
			swal({
		            title: "Information Notice!",
		            html: "<span style='color:orange'>tanggal <b>Pertama</b> harus Sama atau lebih kecil dari tanggal <b>Kedua.</b></span>",
		            type: "warning",
		    //         background : '100%',
		    //         backdrop: `
				  //   rgba(0,0,123,0.4)
				  //   url("https://media0.giphy.com/media/AsNxyHoYDcCXe/giphy.gif")
				  //   center
				  // `
		        });
			$("#after").val("");
		}
	}
});

$("#before1, #after1").change(function() {
	if($("#before1").val() != "" && $("#after1").val() != ""){
		var startDate = new Date($('#before1').val());
		var expiredDate = new Date($('#after1').val());
		if (startDate > expiredDate){
			swal({
		            title: "Information Notice!",
		            html: "<span style='color:orange'>tanggal <b>Pertama</b> harus Sama atau lebih kecil dari tanggal <b>Kedua.</b></span>",
		            type: "warning",
		    //         background : '100%',
		    //         backdrop: `
				  //   rgba(0,0,123,0.4)
				  //   url("https://media0.giphy.com/media/AsNxyHoYDcCXe/giphy.gif")
				  //   center
				  // `
		        });
			$("#after1").val("");
		}
	}
});


$("#searchDate").click(function() {
	var url;
	url = base_url+'aktivitas/LogAbsensi/ajax_list_after/'+String($("#before").val())+"/"+String($("#after").val());

	var table = $('#tblKerjaNormal').DataTable();

	table.ajax.url(url).load();

});

$("#searchDate1").click(function() {
	var url;
	url = base_url+'aktivitas/LogAbsensi/ajax_list_after_extra/'+String($("#before1").val())+"/"+String($("#after1").val());

	var table = $('#tblKerjaExtra').DataTable();

	table.ajax.url(url).load();

});


function ajaxSearchSelect2(idSelect,phol,ur,idModal) {
    $("#"+idSelect).select2({
        placeholder: '-- Pilih '+phol+'--',
        ajax: {
            url: ur,
            type: "post",
            dataType: 'json',
            // delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                // console.log(response);
                return {
                    results: response.data
                };
            },
            cache: true
        },
        language: {
            noResults: function(){
               return "Data tidak ada.!";
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        templateResult: formatRepoKaryawan,
		dropdownParent: $('#'+idModal)
    });
}

function formatRepoKaryawan(repo) {
    var markup = "<div class='select2-result-repository clearfix' style='margin-bottom:0px; border-bottom: 1px solid purple'>"+
	                "<div class='select2-result-repository__meta'>"+
	                	"<div class='row'>"+
	                        "<div class='col-md-5'>"+
	                    		"<div class='select2-result-repository__title'><b>Nama</b> : " + repo.nama + "</div>"+
	                    		"<small>"+
		                    		"<div class='select2-result-repository__title'><b>Tgl lahir</b> : " + repo.tanggal_lahir_indo + "</div>"+
		                    		"<div class='select2-result-repository__forks'><b>Jenis Kelamin</b> : " + repo.kelamin + "</div>" +
		                    	"</small>"+
	                    	"</div>"+
	                        "<div class='col-md-5'>"+
	                    		"<div class='select2-result-repository__forks'><b>No induk</b> : " + repo.idfp + "</div>" +
	                    		"<small>"+
		                    		"<div class='select2-result-repository__forks'><b>Group</b> : " + repo.grup + "</div>" +
		                    		"<div class='select2-result-repository__title'><b>Jabatan</b> : " + repo.jabatan + "</div>"+
		                    	"</small>"+
	                    	"</div>"+
	                    "</div>"+
	                "</div>"+
                 "</div>";

    return markup;
}

		//date time picker
$('.date').datetimepicker({format: 'HH:mm:ss'});
// $('.datepicktime').datetimepicker({
  // use24hours: true

  	$("#contentKerjaNormal").click(function() {
	$("#kerjanormal").show();
	$("#kerjaextra").hide();
	$("#absensi").hide();
});

	$("#contentKerjaExtra").click(function() {
	$("#kerjanormal").hide();
	$("#kerjaextra").show();
	$("#absensi").hide();
});
	$("#contentAbsensi").click(function() {
	$("#kerjanormal").hide();
	$("#kerjaextra").hide();
	$("#absensi").show();
});


});
function reloadTable() {
	$("#tblKerjaNormal").DataTable().ajax.reload(null,false);
	$("#tblKerjaExtra").DataTable().ajax.reload(null,false);
	$("#tblKerjaDisiplin").DataTable().ajax.reload(null,false);
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

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();

	$("#TambahExtra").modal("show");
	$('#infoKabagExtra').show();
	$("#formDataExtra")[0].reset();
	$(".modal-title").text("Tambah Log Absensi Extra Karyawan");
	$("#tanggal11").attr('disabled',false);
	$("#status1").val("Extra(Manual)");
	$("#kabag11").val("").trigger("change");
	$("#karyawan11").val("").trigger("change");
	save_method = "addExtra";
});

$("#kosongout").change(function() {
	breakout = $("#break_out").val();
	if (breakout != "") {
		if ($(this).is(':checked')) {
			// alert("data di checked");
			$("#BreakOut").val("");
			$("#BreakOut").prop("disabled",true);
		} else {
			$("#BreakOut").val(breakout);
			$("#BreakOut").prop("disabled",false);
			$(this).prop("checked",false);
		}

	}
});

$("#kosongin").change(function() {
	breakin = $("#break_in").val();
	if (breakin != "") {
		if ($(this).is(':checked')) {
			// alert("data di checked");
			$("#BreakIn").val("");
			$("#BreakIn").prop("disabled",true	);
		} else {
			$("#BreakIn").val(breakin);
			$("#BreakIn").prop("disabled",false	);
			$(this).prop("checked",false);
		}

	}
});

function btnDetail(id) {
	idData = id;

	$("#Detail").modal("show");
	$(".modal-title").text("Detail Log Absensi");
	$("#dataTable").hide(800);
	$("#detailData").show(800);

	$.post(base_url+"aktivitas/LogAbsensi/getId/"+idData,function(json) {
		if (json.status == true) {
			if (json.data.telat <= 0) {
				json.data.telat = 0;
			}
			$("#tanggal3").html(json.data.tanggal);
			$("#kabag3").html(json.data.kabag);
			$("#nama3").html(json.data.nama);
			$("#jabatan3").html(json.data.jabatan);
			$("#shift3").html(json.data.nama_jadwal);
			$("#masuk3").html(json.data.masuk);
			$("#breakout3").html(json.data.break_out);
			$("#breakin3").html(json.data.break_in);
			$("#keluar3").html(json.data.keluar);
			$("#telat3").html(json.data.telat);
			$("#ketmasuk3").html(json.data.ket_masuk);
			$("#ketkeluar3").html(json.data.ket_keluar);
		} else {
			swal({
		            title: "Error Data.!",
		            html: json.message,
		            type: "error",
		            timer: 2000,
		        });

			setTimeout(function() {
				reloadTable();
				$("#dataTable").show(800);
				$("#detailData").hide(800);
			},1000);
		}
	});
}

function btnDetail11(id) {
	idData = id;

	$("#Detail11").modal("show");
	$(".modal-title").text("Detail Log Absensi");
	$("#dataTable").hide(800);
	$("#detailData").show(800);

	$.post(base_url+"aktivitas/LogAbsensi/getId1/"+idData,function(json) {
		if (json.status == true) {
			$("#tanggal4").html(json.data.tanggal);
			$("#kabag4").html(json.data.kode_kabag);
			$("#nama4").html(json.data.nama);
			$("#jabatan4").html(json.data.jabatan);
			$("#masuk4").html(json.data.masuk);
			$("#keluar4").html(json.data.keluar);
			$("#ketmasuk4").html(json.data.ket_masuk);
			$("#ketkeluar4").html(json.data.ket_keluar);
		} else {
			swal({
		            title: "Error Data.!",
		            html: json.message,
		            type: "error",
		            timer: 2000,
		        });

			setTimeout(function() {
				reloadTable();
				$("#dataTable").show(800);
				$("#detailData").hide(800);
			},1000);
		}
	});
}

function btnDelete(id) {
	var idData = id
	$.post(base_url+"aktivitas/LogAbsensi/getId/"+idData,function(json) {
			if (json.status == true) {
				var pesan = "<hr>";
					pesan += "<li class='pull-left'><small>Karyawan : <i>"+json.data.nama+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jadwal : <i>"+json.data.nama_jadwal+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Masuk : <i>"+json.data.masuk+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Keluar : <i>"+json.data.keluar+"</i></small></li><br>";
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
							$.post(base_url+"aktivitas/LogAbsensi/insertTempAbsensi/"+idData,function(json1) {
								if (json1.status == true) {
									const Toast = Swal.mixin({
										toast: true,
										position: 'top-end',
										showConfirmButton: false,
										timer: 1500
									});
									Toast.fire({
										type: 'success',
										title: "<span style='color:green'>"+json1.message + "</span>"
									})
								}
								else {
									const Toast = Swal.mixin({
										toast: true,
										position: 'top-end',
										showConfirmButton: false,
										timer: 1500
									});
									Toast.fire({
										type: 'error',
										title: "<span style='color:red'>"+json1.message + "</span>"
									})
								}
							}); // end ajax json1
						}
					});
			}
			else {
				const Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 1500
				});
				Toast.fire({
					type: 'error',
					title: "<span style='color:red'>"+json.message + "</span>"
				})
			}
	}); // end ajax json
}

function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update Log Absensi");
	save_method = "update";

	$('#kabagForm').hide();
	$("#tanggal").prop('disabled',true);
	$("#status").val("Normal(Manual)");
	$.post(base_url+"aktivitas/LogAbsensi/getId/"+idData,function(json) {
		if (json.status == true) {
			$('#karyawan').change(function(event) {
				$.post(base_url+"aktivitas/LogAbsensi/getJadwalKaryawan/"+$(this).val(),function(json) {
					if (json.status == true) {
						var option = "";
						$.each(json.data,function(key, val) {
							option +=  "<option value='"+val.id_jadwal+"'>"+val.nama_jadwal+"</option>"
						})
						$('#shift').html(option);
					}
					else {

					}
				})
			});
				$("#inputMessage").html("");
			$('#tanggal').val(json.data.tanggal);
			// $('#nama').val(json.data.nama);
			/*$("#kabag").empty()
					  .append('<option value="'+json.data.kabag+'">'+json.data.kabag+'</option>')
					  .val(json.data.kabag).trigger('change');*/
			$("#karyawan").empty()
					  .append('<option value="'+json.data.kode_karyawan+'">'+json.data.text+'</small></option>')
					  .val(json.data.kode_karyawan).trigger('change');

		    $("#masuk").val(json.data.masuk);
				$('#tanggalKeluar1').val(json.data.tanggal_keluar);
			$("#BreakOut").attr('disabled', false);
			$("#BreakIn").attr('disabled', false);
		    $("#BreakOut").val(json.data.break_out);
		    $('#BreakIn').val(json.data.break_in);
		    $("#keluar").val(json.data.keluar);
		    $("#shift").val(json.data.id_jadwal);
				if (json.data.lupa_keluar_kabag == 1) {
					$('#inputMessage').html('<div class="alert alert-warning text-center orange"><b>Lupa Check Out</b></div><br>');
				}
		} else {
			$("#inputMessage").html(json.message);
			$('#tanggal').val("");
			$('#tanggalKeluar1').val("");
			$('#karyawan').val("");
		    $("#masuk").val("");
		    $("#BreakOut").val("");
		    $("#BreakIn").val("");
		    $("#keluar").val("");
		    $("#shift").val("")
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},7000);
		}
	});

}

function btnEdit1(id) {
	idData = id;
	$("#TambahExtra").modal("show");
	$(".modal-title").text("Update Log Absensi");
	save_method = "updateExtra";

	// $('#infoKabagExtra').hide();
	// $("#tanggal11").attr('disabled',true);
	$("#status1").val("Extra(Manual)");
	$.post(base_url+"aktivitas/LogAbsensi/getId1/"+idData,function(json) {
		if (json.status == true) {
			$('#tanggal11').val(json.data.tanggal);
			$('#nama11').val(json.data.nama);
		  $("#masuk11").val(json.data.masuk);
			$('#tanggalKeluar').val(json.data.tanggal_keluar);
			$("#karyawan11").empty()
					  .append('<option value="'+json.data.kode_karyawan+'">'+json.data.nama+'</small></option>')
					  .val(json.data.kode_karyawan).trigger('change');
			$("#kabag11").empty()
					  .append('<option value="'+json.dataKabag.kode_karyawan+'">'+json.dataKabag.nama+'</small></option>')
					  .val(json.dataKabag.kode_karyawan).trigger('change');
		    $("#keluar11").val(json.data.keluar);
		} else {
			$("#inputMessage11").html(json.message);
			$('#tanggal11').val("");
			$('#nama11').val("");
		    $("#masuk11").val("");
		    $("#keluar11").val("");
			setTimeout(function() {
				reloadTable();
				$("#inputMessage11").html("");
				$("#TambahExtra").modal("hide");
			},1000);
		}
	});

}

$(document).on('click', '#btnTambahNormal', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$('#kabagForm').show();
	$('.shift').show();
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Log Absensi Karyawan");
	$("#tanggal").prop('disabled',false);
	$("#status").val("Normal(Manual)");
	$("#kabag").val("").trigger("change");
	$("#karyawan").val("").trigger("change");
	$('#karyawan').change(function(event) {
		$.post(base_url+"aktivitas/LogAbsensi/getJadwalKaryawan/"+$(this).val(),function(json) {
			if (json.status == true) {
				var option = "";
				$.each(json.data,function(key, val) {
					option +=  "<option value='"+val.id_jadwal+"'>"+val.nama_jadwal+"</option>"
				})
				$('#shift').html(option);
			}
			else {

			}
		})
	});
	$("#BreakOut").attr('disabled', false);
	$("#BreakIn").attr('disabled', false);
	save_method = "add";
});

/*function btnTambahNormal(id){
	$("#modalForm").modal("show");
	$(".modal-title").text("Tambah Log Absensi");
	save_method = "add";

	$.post(base_url+"aktivitas/LogAbsensi/getId/"+idData,function(json) {
		if (json.status == true) {
			$('#kabag').val(json.data.kabag);
			$('#nama').val(json.data.nama);
		    $("#masuk").val(json.data.masuk);
		    $("#BreakOut").val(json.data.break_out);
		    $('#BreakIn').val(json.data.break_in);
		    $("#keluar").val(json.data.keluar);
		    $("#shift").val(json.data.shift);
		} else {
			$("#inputMessage").html(json.message);
			$('#kabag').val("");
			$('#nama').val("");
		    $("#masuk").val("");
		    $("#BreakOut").val("");
		    $("#BreakIn").val("");
		    $("#keluar").val("");
		    $('#shift').val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});
}
*/
function btnDetails(id)
{
	$("#Details").modal("show");
	$(".modal-title").text("Detail kabag");

	$.post(base_url+"aktivitas/LogAbsensi/getKabag1/EMP-"+id,function(json) {
		if (json.status == true) {
				console.log(id)
				$("#detail_img_photo").attr("src",json.data.foto);
          		$('#detailPopUpPhotoOut').attr('href',json.data.foto);
          		$('#detailPopUpPhotoIn').attr('src',json.data.foto);
				$("#nama2").html(json.data.nama);
				$("#departemen2").html(json.data.departemen);
				$("#grup2").html(json.data.grup);
				$("#telepon2").html(json.data.telepon);

		} else {
			swal({
		            title: "Error Data.!",
		            html: json.message,
		            type: "error",
		            timer: 2000,
		        });

			setTimeout(function() {
				reloadTable();
				$("#dataTable").show(800);
				$("#detailData").hide(800);
			},1000);
		}
	});
}

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'aktivitas/LogAbsensi/add';
	} else {
		url = base_url+'aktivitas/LogAbsensi/update/'+idData;
	}

	// $("#modalButtonSave").attr("disabled",true);
	// $("#modalButtonSave").html("Prosessing...<i class='fa fa-spinner fa-spin'></i>");

	$.ajax({
		url: url,
		type:'POST',
		data:$("#formData").serialize(),
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				if (save_method != "add") {
					setTimeout(()=>{
						$("#modalForm").modal("hide");
					},5500)
				}
				$("#inputMessage").html(json.message);

				setTimeout(function() {
					$("#formData")[0].reset();
					// $("#modalForm").modal("hide");
					$("#inputMessage").html("");
					reloadTable();
					$('.form-radio').hide()
					// $("#modalButtonSave").attr("disabled",false);
					// $("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');
				}, 3000);
			} else {

				swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });

				/*$("#inputMessage").html(json.message);
				$("#errorKabag").html(json.error.kabag);
				$("#errorTanggal").html(json.error.tanggal);
				$("#errorKaryawan").html(json.error.karyawan);
				$("#errorKeluar").html(json.error.keluar);
				$("#errorMasuk").html(json.error.masuk);

				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorKabag").html("");
					$("#errorTanggal").html("");
					$("#errorKaryawan").html("");
					$("#errorKeluar").html("");
					$("#errorMasuk").html("");

				},3000);*/
			}
		}
	});
});

$("#tambahExtra").click(function() {
	var url;
	if (save_method == "addExtra") {
		url = base_url+'aktivitas/LogAbsensi/addExtra';
	} else {
		url = base_url+'aktivitas/LogAbsensi/updateExtra/'+idData;
	}
	$.ajax({
		url: url,
		type:'POST',
		data:$("#formDataExtra").serialize(),
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				$("#inputMessage11").html(json.message);

				setTimeout(function() {
					$("#formDataExtra")[0].reset();
					$("#TambahExtra").modal("hide");
					$("#inputMessage11").html("");
					reloadTable();
				}, 2000);
			} else {
				$("#inputMessage11").html(json.message);
				$("#errorKabag11").html(json.error.kabag);
				$("#errorTanggal11").html(json.error.tanggal);
				$("#errorKaryawan11").html(json.error.karyawan);
				$("#errorKeluar11").html(json.error.keluar);
				$("#errorMasuk11").html(json.error.masuk);

				setTimeout(function() {
					$("#inputMessage11").html("");
					$("#errorKabag11").html("");
					$("#errorTanggal11").html("");
					$("#errorKaryawan11").html("");
					$("#errorKeluar11").html("");
					$("#errorMasuk11").html("");

				},5000);
			}
		}
	});
});


var latData = null;
var longData = null;

var latlong = new google.maps.LatLng(latData,longData);
var propertiPeta = {
    center:latlong,
    zoom:18,
    mapTypeId:google.maps.MapTypeId.ROADMAP
};

var petaCheckIn = new google.maps.Map(document.getElementById("googleMapCheckIn"), propertiPeta);
var markerCheckIn = new google.maps.Marker({position: latlong,map: petaCheckIn,});
markerCheckIn.setMap(petaCheckIn);

var petaBreakOut = new google.maps.Map(document.getElementById("googleMapBreakOut"), propertiPeta);
var markerBreakOut = new google.maps.Marker({position: latlong,map: petaBreakOut,});
markerBreakOut.setMap(petaBreakOut);

var petaBreakIn = new google.maps.Map(document.getElementById("googleMapBreakIn"), propertiPeta);
var markerBreakIn = new google.maps.Marker({position: latlong,map: petaBreakIn,});
markerBreakIn.setMap(petaBreakIn);

var petaCheckOut = new google.maps.Map(document.getElementById("googleMapCheckOut"), propertiPeta);
var markerCheckOut = new google.maps.Marker({position: latlong,map: petaCheckOut,});
markerCheckOut.setMap(petaCheckOut);

function btnKordinat(id) {
	idData = id;
	$(".modal-title").text("Kordinat map absensi");
    $("#modalKordinatMap").modal("show");

    $.post(base_url+"aktivitas/LogAbsensi/getId/"+idData,function(json) {
		if (json.status == true) {
		    positionDynamic(markerCheckIn,petaCheckIn,json.data.lat_checkin,json.data.long_checkin); // check in
		    positionDynamic(markerBreakOut,petaBreakOut,json.data.lat_breakout,json.data.long_breakout); // break out
		    positionDynamic(markerBreakIn,petaBreakIn,json.data.lat_breakin,json.data.long_breakin); // break in
		    positionDynamic(markerCheckOut,petaCheckOut,json.data.lat_checkout,json.data.long_checkout); // check out
		}
	});
}


function positionDynamic(marker,peta,lat,long) {
    marker.setPosition(new google.maps.LatLng(lat,long));
    peta.panTo( new google.maps.LatLng(lat,long));
}

function loadMap() {
	petaCheckIn
	markerCheckIn

	petaBreakOut
	markerBreakOut

	petaBreakIn
	markerBreakIn

	petaCheckOut
	markerCheckOut
}

// event jendela di-load
google.maps.event.addDomListener(window, 'load', loadMap);
