$(document).ready(function() {

	/*btnTambah = "";
	if (user_level != "hrd") {*/
		btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";
	// }

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblMasterKaryawan").DataTable({
		serverSide:true,
		responsive:true,
		/*responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.childRowImmediate,
                type: ''
            }
        },*/
		autoWidth: true,
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
			url: base_url+'/master/karyawan/ajax_list',
			type: 'POST',
		},

		order:[[3,'ASC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{
				data:'button_action',
				searchable:false,
				orderable:false
			},
			{
				data:'foto',
				searchable:false,
				orderable:false
			},
			{ data:'idfp' },
			{ data:'nama' },
			{ data:'tempat_lahir' },
			{ data:'tgl_lahir' },
			{ data:'kelamin' },
			{ data:'telepon' },
			{ data:'jabatan' },
			{ data:'alamat' },
			{ data:'status_kerja' }
		],
		/*dom: 'Bfrtip',
		buttons: [
		  	'excel', 'pdf', 'print','colvis'
		],*/

	});

	// var table = $('#tblMasterKaryawan').DataTable( {
 //        lengthChange: false,
 //        buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
 //    } );

    /*table.buttons().container()
        .appendTo( '#tblMasterKaryawan_wrapper .col-md-6:eq(0)' );*/

});

function reloadTable() {
	$("#tblMasterKaryawan").DataTable().ajax.reload(null,false);
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

var srcPhotoDefault = base_url+"assets/images/default/no_user.png";

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#formData")[0].reset();
	$("#titleForm").html("<i class='fa fa-plus'></i> Tambah Karyawan");
	$("#dataTable").hide(800);
	$("#formProses").show(800);
	$('#showFace').hide(800);
	$('#img_photo').attr('src',srcPhotoDefault);
   	$('#popUpPhotoOut').attr('href',srcPhotoDefault);
   	$('#popUpPhotoIn').attr('src',srcPhotoDefault);

	// clear kontrak
	$("#dataKontrak").hide();
	clearDataKontrak();
	$("#no_id").attr("readonly",false);
	$("#cabang").val("").trigger("change");
	$("#departemen").val("").trigger("change");
	$("#jabatan").val("").trigger("change");
	$("#golongan").val("").trigger("change");
	$("#group").val("").trigger("change");
	$("#bank").val("").trigger("change");
	$("#mata_uang_nilai_gaji").html("");

	setTimeout(function() {
		$("#btnSimpan").attr("disabled",false);
		$("#btnSimpan").html('<i class="fa fa-save"></i> Simpan');

	}, 2000);
	save_method = "add";
});

$(".close-form").click(function() {
	$("#dataTable").show(800);
	$("#formProses").hide(800);
	$("#detailData").hide(800);
});

function btnDetail(id) {
	idData = id;

	$("#dataTable").hide(800);
	$("#detailData").show(800);

	$.post(base_url+"/master/karyawan/getId/"+idData,function(json) {
		if (json.status == true) {
			$("#detailNoId").html(json.data.idfp);
			$("#detailNama").html(json.data.nama);
			$("#detail_img_photo").attr("src",json.data.foto);
		   	$('#detailPopUpPhotoOut').attr('href',json.data.foto);
		   	$('#detailPopUpPhotoIn').attr('src',json.data.foto);

			$("#detailAlamat").html(json.data.alamat);
			$("#detailTempatLahir").html(json.data.tempat_lahir);
			$("#detailTanggalLahir").html(json.data.tgl_lahir_indo);
			$("#detailTelepon").html(json.data.telepon);
			$("#detailEmail").html(json.data.email);
			$("#detailNoWali").html(json.data.no_wali);
			$("#detailJenisKelamin").html(json.data.kelamin);
			$("#detailStatusPernikahan").html(json.data.status_nikah);
			$("#detailPendidikan").html(json.data.pendidikan);
			$("#detailKewarganegaraan").html(json.data.wn);
			$("#detailAgama").html(json.data.agama);
			$("#detailCabang").html(json.data.cabang);
			$("#detailDepartemen").html(json.data.departemen);
			$("#detailJabatan").html(json.data.jabatan);

			var checkTunjangan = json.data.jab_index == "1" ? "Iya" : "Tidak";
			$("#detailTunjanganJabatan").html(checkTunjangan);

			$("#detailGolongan").html(json.data.golongan);
			$("#detailShift").html(json.data.shift_text);
			$("#detailGroup").html(json.data.grup);
			$("#detailTglMasukKerja").html(json.data.tgl_masuk_indo);

			$("#detailStatusKontrak").html(json.data.kontrak);
			if (json.data.kontrak == "iya") {

				$("#detailStartDate").html(json.data.start_date_indo);
				$("#detailExpiredDate").html(json.data.expired_date_indo);
				$("#detail_upload_kontrak").attr("src",json.data.file_kontrak);

			   	$('#detailPopUpFileOut').attr('href',json.data.file_kontrak);
			   	$('#detailPopUpFileIn').attr('src',json.data.file_kontrak);

				$("#detailDataKontrak").show(800);
			} else {
				var srcFileDefault = base_url+"assets/images/default/no_file_.png";
				$("#detailStartDate").html("");
				$("#detailExpiredDate").html("");
				$("#detail_upload_kontrak").attr("src",srcFileDefault);
			   	$('#detailPopUpFileOut').attr('href',srcFileDefault);
			   	$('#detailPopUpFileIn').attr('src',srcFileDefault);
				$("#detailDataKontrak").hide(800);
			}

			$("#detailIbuKandung").html(json.data.ibu_kandung);
			$("#detailSuamiIstri").html(json.data.suami_istri);
			$("#detailJumlahTanggungan").html(json.data.tanggungan);
			$("#detailNPWP").html(json.data.npwp);
			$("#detailNilaiGaji").html(json.data.gaji_rp);
			$("#detailPeriodeGajian").html(json.data.periode_gaji);
			$("#detailNoRekening").html(json.data.rekening);
			$("#detailBank").html(json.data.bank);
			$("#detailAtasNama").html(json.data.atas_nama);
			var checkOtoritas = "";
			if (json.data.otoritas == 1) {
				checkOtoritas = "Karyawan";
			} else if (json.data.otoritas == 2) {
				$('#showFace').show(800);
				checkOtoritas = "Kabag";
			} else if (json.data.otoritas == 3) {
				checkOtoritas = "HRD";
				$('#showFace').show(800);
			} else if (json.data.otoritas == 4) {
				checkOtoritas = "Security";
			}

			$("#detailOtoritas").html(checkOtoritas);

			if (json.data.status_kerja == "aktif") {
				$("#detailStatusKerja").html("Aktif");
			} else {
				$("#detailStatusKerja").html("Non Aktif");
			}
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

$("#btnDetailZoomImg").click(function() {
	$("#detailPopUpPhotoOut").click();
});

$("#btnDetailZoomFile").click(function() {
	$("#detailPopUpFileOut").click();
});

/*FOr select2 ajax dropdown*/
$(document).ready(function(){

	$("#cabang").select2({
		placeholder: '-- Pilih Cabang --',
		ajax: {
		   	url: base_url+"/master/karyawan/allCabangAjax",
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
	});

	$("#departemen").select2({
		placeholder: '-- Pilih Departemen --',
		ajax: {
		   	url: base_url+"/master/karyawan/allDepartemenAjax",
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
	});

	$("#jabatan").select2({
		placeholder: '-- Pilih Jabatan --',
		ajax: {
		   	url: base_url+"/master/karyawan/allJabatanAjax",
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
	});

	$("#golongan").select2({
		placeholder: '-- Pilih Golongan --',
		ajax: {
		   	url: base_url+"/master/karyawan/allGolonganAjax",
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
	});

	$("#group").select2({
		placeholder: '-- Pilih Group --',
		ajax: {
		   	url: base_url+"/master/karyawan/allGroupAjax",
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
	});

	$("#bank").select2({
		placeholder: '-- Pilih Bank --',
		ajax: {
		   	url: base_url+"/master/karyawan/allBankAjax",
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
	});

	$('#showFace').hide();
});

$("#sama_nama").change(function() {
	nama = $("#nama").val();
	if (nama != "") {
		if ($(this).is(':checked')) {
			// alert("data di checked");
			$("#atas_nama").val(nama);
		} else {
			$("#atas_nama").val("");
			$(this).prop("checked",false);
		}

	}
});

$("#nama").keyup(function() {
	$("#atas_nama").val("");
	$("#sama_nama").prop("checked",false);
});

$("#atas_nama").keyup(function() {
	nama = $("#nama").val();
	atas_nama = $(this).val();
	if(nama != "" && atas_nama != "") {
		if (nama != atas_nama) {
			$("#sama_nama").prop("checked",false);
		} else {
			$("#sama_nama").prop("checked",true);
		}
	}
});

/*$(document).ready(function () {
    $('#nilai_gaji').on('input', function() {
        $('#mata_uang_nilai_gaji').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
});
*/
// shift
/*$("input[name='shift']").change(function() {
	shift = $(this).val();
	if (shift == "iya") {
		$("#infoNamaShift").show(800);
	} else {
		$("#infoNamaShift").hide(800);
		$("#nama_shift").val("");
	}
});*/

$("#status_pernikahan").change(function() {
	if ($(this).val() == "Menikah" || $(this).val() == "") {
		$("#suami_istri").attr("disabled", false);
		$("#suami_istri").val("");
	} else {
		$("#suami_istri").attr("disabled", true);
		$("#suami_istri").val("");
	}
});

// status kontrak
$("input[name='status_kontrak']").change(function() {
	status_kontrak = $(this).val();
	if (status_kontrak == "iya") {
		$("#dataKontrak").show(800);
	} else {
		$("#dataKontrak").hide(800);
		// clear kontrak
		clearDataKontrak();
	}
});

$('#otoritas').change(function() {
	 if ($(this).val() == 2 || $(this).val() == 3) {
	 	$('#showFace').show(800);
	 }
	 else {
	 	$('#showFace').hide(800);
	 }
});

$("#golongan").change(function() {
	$("#periode_gaji").val("");
	$("#nilai_gaji").val("");
	$("#mata_uang_nilai_gaji").html("");
});

$("#periode_gaji").change(function() {
	var valGol = $("#golongan").val();
	var valPer = $("#periode_gaji").val();

	if (valGol != "" && valPer != "") {
		console.log(valGol);
		console.log(valPer);

		$.post(base_url+"master/golongan/getId/"+valGol, function(json) {
			if (json.status == true) {
				var gajiPerhari = moneyFormat.to(parseInt(json.data.gaji));
				if (valPer == "Bulanan") {
					$("#nilai_gaji").val(moneyFormat.to( parseInt(json.data.umk) ));
					$("#nilai_gaji_int").val(json.data.umk);
					$("#mata_uang_nilai_gaji").html("Gaji Bulanan");
				} else if (valPer == "2-Mingguan") {
					$("#nilai_gaji").val(moneyFormat.to( parseInt((json.data.gaji * 12)) ));
					$("#nilai_gaji_int").val((json.data.gaji * 12));
					$("#mata_uang_nilai_gaji").html("Gaji Perhari ("+gajiPerhari+" x 12 ) hari atau 2 minggu");
				} else if (valPer == "1-Mingguan") {
					$("#nilai_gaji").val(moneyFormat.to( parseInt((json.data.gaji * 6)) ));
					$("#nilai_gaji_int").val((json.data.gaji * 6));
					$("#mata_uang_nilai_gaji").html("Gaji Perhari ("+gajiPerhari+" x 6) hari atau 1 minggu");
				}
			} else {
				$("#nilai_gaji").val("");
				$("#nilai_gaji_int").val("");
				$("#mata_uang_nilai_gaji").html("");
			}
		});
	} else {
		$("#nilai_gaji").val("");
		$("#mata_uang_nilai_gaji").html("");
	}
});

function clearDataKontrak() {
	var srcFileDefault = base_url+"assets/images/default/no_file_.png";
	$("#start_date").val("");
	$("#expired_date").val("");
   	$('#upload_kontrak').attr('src',srcFileDefault);
   	$('#popUpFileOut').attr('href',srcFileDefault);
   	$('#popUpFileIn').attr('src',srcFileDefault);
   	$("#file_kontrak").val("");
   	$('#is_delete_kontrak').val(0);
}

$("#start_date, #expired_date").change(function() {
	if($("#start_date").val() != "" && $("#expired_date").val() != ""){
		var startDate = new Date($('#start_date').val());
		var expiredDate = new Date($('#expired_date').val());
		if (startDate > expiredDate){
			swal({
		            title: "Error Form.!",
		            html: "<span style='color:red'>Akhir kontrak harus lebih besar dari Awal kontrak</span>",
		            type: "error",
		        });
			$("#expired_date").val("");
		}
	}
});

// update master karyawan
function btnEdit(id) {
	idData = id;
	save_method = "update";

	$("#titleForm").html("<i class='fa fa-pencil'></i> Update Karyawan");
	$("#dataTable").hide(800);
	$("#formProses").show(800);

	$.post(base_url+"/master/karyawan/getId/"+idData,function(json) {
		if (json.status == true) {
			$("#no_id").attr("readonly",true);
			$("#no_id").val(json.data.idfp);
			$("#nama").val(json.data.nama);
			$("#img_photo").attr("src",json.data.foto);
		   	$('#popUpPhotoOut').attr('href',json.data.foto);
		   	$('#popUpPhotoIn').attr('src',json.data.foto);
			$("#alamat").val(json.data.alamat);
			$("#tempat_lahir").val(json.data.tempat_lahir);
			$("#tgl_lahir").val(json.data.tgl_lahir);
			$("#telepon").val(json.data.telepon);
			$("#email").val(json.data.email);
			$("#no_wali").val(json.data.no_wali);
			$("#jenis_kelamin").val(json.data.kelamin);
			$("#status_pernikahan").val(json.data.status_nikah);
			$("#pendidikan").val(json.data.pendidikan);
			$("#kewarganegaraan").val(json.data.wn);
			$("#agama").val(json.data.agama);
			$("#cabang").empty()
							   .append('<option value="'+json.data.id_cabang+'">'+json.data.cabang+'</option>')
							   .val(json.data.id_cabang)
							   .trigger('change');

			// $("#cabang").val(json.data.id_cabang).trigger("change");
			$("#departemen").empty().append('<option value="'+json.data.id_departemen+'">'+json.data.departemen+'</option>')
							    .val(json.data.id_departemen)
							    .trigger('change');
			// $("#departemen").val(json.data.id_departemen).trigger("change");
			$("#jabatan").empty().append('<option value="'+json.data.id_jabatan+'">'+json.data.jabatan+'</option>')
							     .val(json.data.id_jabatan)
							     .trigger('change');
			// $("#jabatan").val(json.data.id_jabatan).trigger("change");
			if (json.data.jab_index == "1") {
				$("#tunjanganIya").prop("checked", true);
				$("#tunjanganTidak").prop("checked", false);
			} else {
				$("#tunjanganIya").prop("checked", false);
				$("#tunjanganTidak").prop("checked", true);
			}
			// $("#golongan").val(json.data.id_golongan).trigger("change");
			$("#golongan").empty().append('<option value="'+json.data.id_golongan+'">'+json.data.golongan+'</option>')
							     .val(json.data.id_golongan)
							     .trigger('change');
			if (json.data.shift == "ya") {
				$("#shiftYa").prop("checked", true);
				$("#shiftTidak").prop("checked", false);
			} else {
				$("#shiftYa").prop("checked", false);
				$("#shiftTidak").prop("checked", true);
			}
			// $("#group").val(json.data.id_grup).trigger("change");
			$("#group").empty().append('<option value="'+json.data.id_grup+'">'+json.data.grup+'</option>')
							     .val(json.data.id_grup)
							     .trigger('change');

			$("#tgl_masuk_kerja").val(json.data.tgl_masuk);
			if (json.data.kontrak == "iya") {
				$("#kontrakIya").prop("checked", true);
				$("#kontrakTidak").prop("checked", false);
				$("#start_date").val(json.data.start_date);
				$("#expired_date").val(json.data.expired_date);
				var pdf = json.data.file_kontrak.split('.');
				if (pdf[pdf.length-1] == "pdf") {
					$("#upload_kontrak").attr("src",base_url+"assets/images/default/pdf_file.jpg");
					$('#popUpFileOut').attr('href',base_url+"assets/images/default/pdf_file.jpg");
					$('#popUpFileIn').attr('src',base_url+"assets/images/default/pdf_file.jpg");
					$('#download_pdf').show(800);
					$('#download_pdf').attr('href',json.data.file_kontrak);
					$("#dataKontrak").show(800);
				}
				else {
					$("#upload_kontrak").attr("src",json.data.file_kontrak);
					$('#popUpFileOut').attr('href',json.data.file_kontrak);
					$('#popUpFileIn').attr('src',json.data.file_kontrak);
					$('#download_pdf').hide(800);
					$("#dataKontrak").show(800);
				}
			} else {
				var srcFileDefault = base_url+"assets/images/default/no_file_.png";
				$("#kontrakIya").prop("checked", false);
				$("#kontrakTidak").prop("checked", true);
				$("#start_date").val("");
				$("#expired_date").val("");
				$("#upload_kontrak").attr("src",srcFileDefault);
			   	$('#popUpFileOut').attr('href',srcFileDefault);
			   	$('#popUpFileIn').attr('src',srcFileDefault);
				$("#dataKontrak").hide(800);
			}

			$("#ibu_kandung").val(json.data.ibu_kandung);
			$("#suami_istri").val(json.data.suami_istri);
			$("#jumlah_tanggungan").val(json.data.tanggungan);
			$("#npwp").val(json.data.npwp);
			$("#nilai_gaji").val(json.data.gaji_rp);
			$("#nilai_gaji_int").val(json.data.gaji);
			// $("#mata_uang_nilai_gaji").html(json.data.gaji_rp);
			$("#periode_gaji").val(json.data.periode_gaji);
			$("#no_rekening").val(json.data.rekening);
			// $("#bank").val(json.data.id_bank).trigger("change");
			$("#bank").empty().append('<option value="'+json.data.id_bank+'">'+json.data.bank+'</option>')
							     .val(json.data.id_bank)
							     .trigger('change');
			$("#atas_nama").val(json.data.atas_nama);
			$("#otoritas").val(json.data.otoritas);
			if (json.data.otoritas == 2 || json.data.otoritas == 3) {
				$('#showFace').show(800);
			}
			else {
				$('#showFace').hide();
			}
			if (json.data.wajah >= 1) {
				$("#status_face_aktif").prop("checked", true);
				$("#status_face_non_aktif").prop("checked", false);
			} else {
				$("#status_face_aktif").prop("checked", false);
				$("#status_face_non_aktif").prop("checked", true);
			}
			if (json.data.status_kerja == "aktif") {
				$("#status_kerja_aktif").prop("checked", true);
				$("#status_kerja_non_aktif").prop("checked", false);
			} else {
				$("#status_kerja_aktif").prop("checked", false);
				$("#status_kerja_non_aktif").prop("checked", true);
			}
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
				$("#formProses").hide(800);
			},1000);
		}
	});
}

$("#btnSimpan").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'/master/karyawan/add';
	} else {
		url = base_url+'/master/karyawan/update/'+idData;
	}

	$("#btnSimpan").attr("disabled",true);
	$("#btnSimpan").html("Prosessing...<i class='fa fa-spinner fa-spin'></i>");

	var formData = new FormData($("#formData")[0]);
	$.ajax({
		url: url,
		type:'POST',
		data:formData,
		contentType:false,
		processData:false,
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				// $("#inputMessage").html(json.message);
				swal({
			            title: json.message,
			            type: "success",
			            timer: 4000,
			            showConfirmButton: false
			        });

				setTimeout(function() {
					$("#formData")[0].reset();
					// $("#inputMessage").html("");
					$("#dataTable").show(800);
					$("#formProses").hide(800);
					reloadTable();

					$("#btnSimpan").attr("disabled",false);
					$("#btnSimpan").html('<i class="fa fa-save"></i> Simpan');

				}, 2000);
			} else {

				if (json.message == "error_foto") {

					var fotoError = "";
					var fileTitleError = "";

					if (json.error_foto_karyawan) {
						fotoError += "<u>Photo Karyawan : </u>"+json.error_foto_karyawan+"<br>";
						fileTitleError = "Error Photo.!";
					}
					if (json.error_file_kontrak) {
						fotoError += "<u>File Kontrak : </u>"+json.error_file_kontrak+"<br>";
						fileTitleError = "Error File.!";
					}

					swal({
			            title: "<h2 style='color:red;'>"+fileTitleError+"</h2>",
			            html: fotoError,
			            type: "error",
			        });

					setTimeout(function() {

						$("#btnSimpan").attr("disabled",false);
						$("#btnSimpan").html('<i class="fa fa-save"></i> Simpan');

					}, 3000);
				} else {
					swal({
			            title: "Error Form",
			            html: json.message,
			            type: "error",
			        });

					setTimeout(function() {

						$("#btnSimpan").attr("disabled",false);
						$("#btnSimpan").html('<i class="fa fa-save"></i> Simpan');

					}, 3000);
				}
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"/master/karyawan/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>No. induk : <i>"+json.data.idfp+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tempat lahir : <i>"+json.data.tempat_lahir+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tanggal lahir : <i>"+json.data.tgl_lahir_indo+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Telepon : <i>"+json.data.telepon+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Status kerja : <i>"+json.data.status_kerja+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Alamat : <i>"+json.data.alamat+"</i></small></li><br>";
				pesan += "</div>";
				pesan += "</div>";

		    swal({
		        title: "Apakah anda yakin.?",
		        html: "<span style='color:red;'>Data yang di <b>Hapus</b> tidak bisa dikembalikan lagi.</span>"+pesan,
		        type: "warning",
				width: 500,
  				showCloseButton: true,
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Iya, Hapus",
		        closeOnConfirm: false,
			  	// animation: false,
			  	// customClass: 'animated tada'

		    }).then((result) => {
		    	if (result.value) {
		    		$.post(base_url+"/master/karyawan/delete/"+idData,function(json) {
						if (json.status == true) {
							swal({
						            title: json.message,
						            type: "success",
						            timer: 5000,
						            showConfirmButton: false
						        });
							reloadTable();
						} else {
							swal({
						            title: json.message,
						            type: "error",
						            /*timer: 1500,
						            showConfirmButton: false */
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
		            /*timer: 1000,
		            showConfirmButton: false */
		        });
		}
	});
}

/* prosessing photo karyawan change*/
$("#btnZoomImg").click(function() {
	$("#popUpPhotoOut").click();
});

$("#ganti_photo").click(function() {
	$("#photo_karyawan").click();
});

$("#photo_karyawan").change(function(event){
	readURL(document.getElementById('photo_karyawan'));
	$('#is_delete_photo').val(0);
});

$("#hapus_photo").click(function() {

   	$('#img_photo').attr('src',srcPhotoDefault);
   	$('#popUpPhotoOut').attr('href',srcPhotoDefault);
   	$('#popUpPhotoIn').attr('src',srcPhotoDefault);
   	$("#photo_karyawan").val("");
   	$('#is_delete_photo').val(1);
});

function readURL(input)
{
   	var filePath = input.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){ // validate format extension file
        // alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
        swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "Photo Karyawan :  <small><span style='color:red;'> Jenis file yang Anda upload tidak diperbolehkan.</span> <br> Harap hanya upload file yang memiliki ekstensi <br> .jpeg | .jpg | .png | .gif </small>",
	            type: "error",
	        });

        input.value = '';
        return false;

    } else if (input.files[0].size > 1048576) { // validate size file 1 mb
		// alert("file size lebih besar dari 1 mb, file size yang di upload = "+input.files[0].size);
		swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "Photo Karyawan : <small style='color:red;'>File yang diupload melebihi ukuran maksimal diperbolehkan yaitu 1 mb.</small>",
	            type: "error",
	        });
        input.value = '';
		return false;

	} else {
	   	if (input.files && input.files[0])
	   	{
		    var reader = new FileReader();
		    reader.onload = function (e)
		    {
		       $('#img_photo').attr('src',e.target.result);
		       $('#popUpPhotoIn').attr('src',e.target.result);
		       $('#popUpPhotoOut').attr('href',e.target.result);
		    };
		    reader.readAsDataURL(input.files[0]);
	   	}
	}
}
/* end photo karyawan change*/

var srcFileDefault = base_url+"assets/images/default/no_file_.png";
/* prosessing file kontrak change*/
$("#btnZoomFile").click(function() {
	$("#popUpFileOut").click();
});

$("#ganti_file").click(function() {
	$("#file_kontrak").click();
});

$("#file_kontrak").change(function(event){
	readURLFile(document.getElementById('file_kontrak'));
	$('#is_delete_kontrak').val(0);
});

$("#hapus_file").click(function() {
   	$('#upload_kontrak').attr('src',srcFileDefault);
   	$('#popUpFileOut').attr('href',srcFileDefault);
   	$('#popUpFileIn').attr('src',srcFileDefault);
   	$("#file_kontrak").val("");
   	$('#is_delete_kontrak').val(1);
});

function readURLFile(input)
{
   	var filePath = input.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.pdf)$/i;
    if(!allowedExtensions.exec(filePath)){ // validate format extension file
        // alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
        swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "File Kontrak :  <small><span style='color:red;'> Jenis file yang Anda upload tidak diperbolehkan.</span> <br> Harap hanya upload file yang memiliki ekstensi <br> .jpeg | .jpg | .png | .gif | .pdf </small>",
	            type: "error",
	        });

        input.value = '';
        return false;

    } else if (input.files[0].size > 1048576) { // validate size file 1 mb
		// alert("file size lebih besar dari 1 mb, file size yang di upload = "+input.files[0].size);
		swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "File Kontrak : <small style='color:red;'>File yang diupload melebihi ukuran maksimal diperbolehkan yaitu 1 mb.</small>",
	            type: "error",
	        });
        input.value = '';
		return false;

	} else {
	   	if (input.files && input.files[0])
	   	{
		    var reader = new FileReader();

  			fileType = input.files[0].type;
  			// alert(fileType);
  			if (fileType == "application/pdf") {
  				reader.onload = function (e)
			    {
			    	var pdf_file = base_url+"assets/images/default/pdf_file.jpg";
			       	$('#upload_kontrak').attr('src',pdf_file);
			       	$('#popUpFileIn').attr('src',e.target.result);
			       	$('#popUpFileOut').attr('href',e.target.result);
			    };
  			} else {
  				reader.onload = function (e)
			    {
			       	$('#upload_kontrak').attr('src',e.target.result);
			       	$('#popUpFileIn').attr('src',e.target.result);
			       	$('#popUpFileOut').attr('href',e.target.result);
			    };
  			}
		    reader.readAsDataURL(input.files[0]);
	   	}
	}
}
/* end file kontrak change*/

function btnQRcode(id) {
	idData = id;

   //Check if browser is Firefox or not
    if (navigator.userAgent.search("Firefox") >= 0) {
        // alert("Browser is FireFox");
        swal({
	            title: "<h2 style='color:blue;'>Pemberitahuan!</h2>",
	            html: "<span style='color:red;'>Opps Maaf, untuk cetak kartu Qr Code jangan menggunakan browser Mozila Firefox karena tidak support atau mendukung. <br> Silahkan gunakan browser yang lain. <br> Di utamakan menggunakan browser Google Chrome.</span>",
	            type: "warning",
	        });
    } else {
		$("#modalQrCode").modal("show");
		$("#viewPrintCard").show();
		$("#printPreview").hide();
		$("#screenQrCode").attr("src","");
	    $("#btnPrintQrCode").hide();
	    $("#btnPrintQrCodeDownload").hide();
	    $("#btnGenerate").show();
    	$.post(base_url+"/master/karyawan/qrCodeGenerate/"+idData,function(json) {
			if (json.status == true) {
				$("#fotoKaryawan").attr("src",json.data.foto);
				$("#namaKaryawan").html(json.data.nama);
				$("#jabatanKaryawan").html(json.data.jabatan);
				// $("#imgQrCode").html(json.data.qr_code);
				$("#QrCode").attr("src",json.data.qrcode_file);
			} else {
				$("#inputMessage").html(json.message);
				$("#viewPrintCard").hide();
				setTimeout(function() {
					$("#inputMessage").html("");
					$("#modalQrCode").modal("hide");
					$("#viewPrintCard").show();
					reloadTable();
				},1500);
			}
		});
    }
}

var canvas = document.querySelector("canvas");
document.querySelector("button#btnGenerate").addEventListener("click", function() {
	$("#printPreview").show();

    html2canvas(document.querySelector("#viewPrintCard"), {canvas: canvas}).then(function(canvas) {
        console.log('Drew on the existing canvas');
	   	var pngUrl = canvas.toDataURL();
        console.log(canvas);
        console.log(pngUrl);
        if (canvas != null) {
        	$("#btnGenerate").hide();
        	$("#btnPrintQrCode").show();
        	$("#btnPrintQrCodeDownload").show();
        	$("#viewPrintCard").hide();
        	$("#screenQrCode").attr("src",pngUrl);

			$("#btnPrintQrCodeDownload").click(function() {
				download(pngUrl,"qrcode_"+$("#namaKaryawan").html(),"image/png");
	        });
        } else {
        	alert("Data not found.");
        }

    });
}, false);

$("#btnPrintQrCode").click(function() {
    var mode = 'iframe'; //popup
    var close = mode == "popup";
    var options = {
        mode: mode,
        popClose: close,
    };
    $("div#canvasPrint").printArea(options);
});
