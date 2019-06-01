$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";



	$("#tblSetting").DataTable({
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
			url: base_url+'setting/ajax_list',
			type: 'POST',
		},

		order:[[2,'ASC']],
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
			{ data: 'foto'},
			{ data : 'nama_perusahaan' },
			{ data : 'alamat' },
			{ data : 'provinsi' },
			{ data : 'kota_kabupaten' },
			{ data : 'timezone' },
			{ data : 'kode_pos' },
			{ data : 'no_telp' },
			{ data : 'no_fax' },
			{ data : 'email_perusahaan' },
			{ data : 'website' },
			{ data : 'jatah_cuti' },
			{ data : 'edit_at'}
		],
	});

	var options = {
					toolbar: [
					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-','Templates' ] },
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
					'/',
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
					{ name: 'links', items: [ 'Link', 'Unlink'] },
					{ name: 'insert', items: ['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },

					{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
					{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
					{ name: 'others', items: [ '-' ] }
			],
			height:'200px',
			};

		CKEDITOR.replace("editor9",options);

 $("#tblTHR").DataTable({
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
			url: base_url+'setting/ajax_list_thr',
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
			{ data:'agama' },

		],
	});

 $("#tblSystem").DataTable({
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
			url: base_url+'setting/ajax_list',
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
			{ data:'budget_penggajian' },
			{ data:'jatah_cuti' },
		],
	});

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable
        .tables( { visible: true, api: true } )
        .columns.adjust();
});

// 		//date time picker
// $('.date').datetimepicker({format: 'HH:mm:ss'});
// // $('.datepicktime').datetimepicker({
//   // use24hours: true

  $("#tanggal11").dateDropper( {
					dropWidth: 200,
					init_animation: "bounce",
					dropPrimaryColor: "#1abc9c",
					dropBorder: "1px solid #1abc9c",
					maxYear: "2050",
					format: "Y-m-d"
			});

	$("#THR").hide();
	$("#System").hide();
	$('#bpjs').hide();

  $("#contentGeneral").click(function() {
		$("#General").show();
		$("#THR").hide();
		$("#System").hide();
		$('#bpjs').hide();
	});

	$("#contentTHR").click(function() {
		$("#General").hide();
		$("#THR").show();
		$("#System").hide();
		$('#bpjs').hide();
	});

	$("#contentSystem").click(function() {
		$("#General").hide();
		$("#THR").hide();
		$("#System").show();
		$('#bpjs').hide();
	});

	$("#contentBpjs").click(function() {
		$("#General").hide();
		$("#THR").hide();
		$("#System").hide();
		$('#bpjs').show();
	});

});

function reloadTable() {
	$("#tblSetting").DataTable().ajax.reload(null,false);
	$("#tblTHR").DataTable().ajax.reload(null,false);
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
	$("#TambahTHR").modal("show");
	$("#formDataExtra")[0].reset();
	$(".modal-title").text("Tambah Setting THR");
	save_method = "addExtra";
});

$('#opsi_hari').change(function() {
	if ($(this).val() == 0) {
		$('#col_kerja').hide(800)
		$('#totalkerja').val(0);
	}
	else {
		$('#col_kerja').show(800)
	}
});

$(document).ready(function(){
	ajaxid();
	getBpjs();
})

$(document).ready(function(){
	$('#budgetPenggajian').on('input', function() {
      $('#mata_uang_penggajian').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#punishment1').on('input', function() {
      $('#mata_uang_punishment1').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#reward1').on('input', function() {
      $('#mata_uang_reward1').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#reward2').on('input', function() {
      $('#mata_uang_reward2').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#punishment2').on('input', function() {
      $('#mata_uang_punishment2').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#reward3').on('input', function() {
      $('#mata_uang_reward3').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#punishment3').on('input', function() {
      $('#mata_uang_punishment3').html( moneyFormat.to( parseInt($(this).val()) ) );
  });

	$('#jp_perusahaan_max').on('input', function() {
      $('#moneyJpPerusahaan').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#jp_karyawan_max').on('input', function() {
      $('#moneyJpKaryawan').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#bpjs_perusahaan_max').on('input', function() {
      $('#moneyBpjsPerusahaan').html( moneyFormat.to( parseInt($(this).val()) ) );
  });
	$('#bpjs_karyawan_max').on('input', function() {
      $('#moneyBpjsKaryawan').html( moneyFormat.to( parseInt($(this).val()) ) );
  });

	$("#provinsi").change(function(){
		var id = $(this).val();
		$('#kabupaten').empty();
		$.post(base_url+"setting/getRegencies/"+id,function(json){
				if(json.status == true)
				{
					var i;
					for(i=0; i<json.data.length; i++)
					{
						$('#kabupaten').append($('<option>', {
				        value: json.data[i].id_regencies,
				        text : json.data[i].name
				    }));
					}
				}
				else {
					$("#errorKabupaten").val(json.message);
				}

			});
	});

});

function btnEdit1(id) {
	idData = id;
	$("#TambahTHR").modal("show");
	$(".modal-title").text("Update Setting THR");
	save_method = "updateExtra";

	$.post(base_url+"setting/getId1/"+idData,function(json) {
		if (json.status == true) {
			$('#tanggal11').val(json.data.tanggal);
		  	$("#agama11").val(json.data.agama);
		} else {
			$("#inputMessage11").html(json.message);
			$('#tanggal11').val("");
		 	$("#agama11").val("");
			setTimeout(function() {
				reloadTable();
				$("#TambahTHR").modal("hide");
			},1000);
		}
	});

}

function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update Setting");
	save_method = "update";

	$.post(base_url+"setting/getId/"+idData,function(json) {
		if (json.status == true) {
			$('#namaPerusahaan').val(json.data.nama_perusahaan);
			$('#Alamat').val(json.data.alamat);
			CKEDITOR.instances.editor9.setData(json.data.bidang_usaha);
			$("#provinsi").val(json.data.provinsi);
			$('#kabupaten').append($('<option>', {
					value: json.data.kota_kabupaten,
					text : json.data.kota_kabupaten1
			}));
			$('#img_photo').attr('src',json.data.logo);
				$('#popUpImgOut').attr('href',json.data.logo);
				$('#popUpImgIn').attr('src',json.data.logo);
			$('#img_photo1').attr('src',json.data.qrcode);
				$('#popUpImgOut1').attr('href',json.data.qrcode);
				$('#popUpImgIn1').attr('src',json.data.qrcode);
			$("#KodePos").val(json.data.kode_pos);
			$("#noFax").val(json.data.no_fax);
			$("#emailPerusahaan").val(json.data.email_perusahaan);
			$("#Website").val(json.data.website);
			$("#noTelp").val(json.data.no_telp);
			$("#jatahCuti").val(json.data.jatah_cuti);
		} else {
			$("#inputMessage").html(json.message);
			$('#namaPerusahaan').val("");
			$('#Alamat').val("");
			CKEDITOR.instances.editor9.setData("");
			$("#provinsi").val("");
		  $("#kabupaten").val("");
			$('#img_photo').attr('src',"");
				$('#popUpImgOut').attr('href',"");
				$('#popUpImgIn').attr('src',"");
			$('#img_photo1').attr('src',"");
				$('#popUpImgOut1').attr('href',"");
				$('#popUpImgIn1').attr('src',"");
			$("#KodePos").val("");
			$("#noFax").val("");
			$("#emailPerusahaan").val("");
			$("#Website").val("");
			$("#noTelp").val("");
			$("#jatahCuti").val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});

}

function readURL(input)
{
   	var filePath = input.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){ // validate format extension file
        // alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
        swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "Photo Logo :  <small><span style='color:red;'> Jenis file yang Anda upload tidak diperbolehkan.</span> <br> Harap hanya upload file yang memiliki ekstensi <br> .jpeg | .jpg | .png | .gif </small>",
	            type: "error",
	        });

        input.value = '';
        return false;

    } else if (input.files[0].size > 1048576) { // validate size file 1 mb
		// alert("file size lebih besar dari 1 mb, file size yang di upload = "+input.files[0].size);
		swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "Photo Logo : <small style='color:red;'>File yang diupload melebihi ukuran maksimal diperbolehkan yaitu 1 mb.</small>",
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
		       $('#popUpPhotoLogoIn').attr('src',e.target.result);
		       $('#popUpPhotoLogoOut').attr('href',e.target.result);
		    };
		    reader.readAsDataURL(input.files[0]);
	   	}
	}
}

function readURL1(input)
{
   	var filePath = input.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){ // validate format extension file
        // alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
        swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "Photo qrcode :  <small><span style='color:red;'> Jenis file yang Anda upload tidak diperbolehkan.</span> <br> Harap hanya upload file yang memiliki ekstensi <br> .jpeg | .jpg | .png | .gif </small>",
	            type: "error",
	        });

        input.value = '';
        return false;

    } else if (input.files[0].size > 1048576) { // validate size file 1 mb
		// alert("file size lebih besar dari 1 mb, file size yang di upload = "+input.files[0].size);
		swal({
	            title: "<h2 style='color:red;'>Error Photo!</h2>",
	            html: "Photo qrcode : <small style='color:red;'>File yang diupload melebihi ukuran maksimal diperbolehkan yaitu 1 mb.</small>",
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
		       $('#img_photo1').attr('src',e.target.result);
		       $('#detailPopUpPhotoIn').attr('src',e.target.result);
		       $('#detailPopUpPhotoOut').attr('href',e.target.result);
		    };
		    reader.readAsDataURL(input.files[0]);
	   	}
	}
}

/* prosessing photo Setting change*/
$("#btnZoomImg").click(function() {
	$("#popUpPhotoLogoOut").click();
});

$("#ganti_photo").click(function() {
	$("#photo_karyawan").click();
});

$("#photo_karyawan").change(function(event){
	readURL(document.getElementById('photo_karyawan'));
	$('#is_delete_photo').val(0);
});

$("#hapus_photo").click(function() {

		$('#img_photo').attr('src','assets/images/default/no_file_.png');
		$('#popUpPhotoLogoOut').attr('href','assets/images/default/no_file_.png');
		$('#popUpPhotoLogoIn').attr('src','assets/images/default/no_file_.png');
   	$("#photo_karyawan").val("");
   	$('#is_delete_photo').val(1);
});

// qrcode photo

$("#btnZoomImg1").click(function() {
	$("#detailPopUpPhotoOut").click();
});

$("#ganti_photo1").click(function() {
	$("#photo_qrcode").click();
});

$("#photo_qrcode").change(function(event){
	readURL1(document.getElementById('photo_qrcode'));
	$('#is_delete_photo1').val(0);
});

$("#hapus_photo1").click(function() {

		$('#img_photo1').attr('src','assets/images/default/no_file_.png');
		$('#detailPopUpPhotoOut').attr('href','assets/images/default/no_file_.png');
		$('#detailPopUpPhotoIn').attr('src','assets/images/default/no_file_.png');
   	$("#photo_qrcode").val("");
   	$('#is_delete_photo1').val(1);
});

$('#checkbox3').change(function() {

	if ($('#checkbox3').prop('checked') != true) {
		$('.showBreak').hide(800);
	}
	else {
		$('.showBreak').show(800);
	}
});

$('#opsi_kerajinan').change(function(event) {
	if ($(this).val() == 0) {
		$('#showKerajinan').hide(800);
		$('#nilaiKerajinan').val(0);
	}
	else {
		$('#showKerajinan').show(800);
	}
});

function ajaxid(){
	$.post(base_url+"setting/getId/1",function(json) {

		if (json.status == true) {
			$('#namaPerusahaan').val(json.data.nama_perusahaan);
			$('#Alamat').val(json.data.alamat);
			CKEDITOR.instances.editor9.setData(json.data.bidang_usaha);
			$("#provinsi").val(json.data.provinsi);
			$('#kabupaten').append($('<option>', {
					value: json.data.kota_kabupaten,
					text : json.data.kota_kabupaten1
			}));
			$('#img_photo').attr('src',json.data.logo);
				$('#popUpPhotoLogoOut').attr('href',json.data.logo);
				$('#popUpPhotoLogoin').attr('src',json.data.logo);

			$('#img_photo1').attr('src',json.data.qrcode);
				$('#detailPopUpPhotoOut').attr('href',json.data.qrcode);
				$('#detailPopUpPhotoIn').attr('src',json.data.qrcode);

			$("#KodePos").val(json.data.kode_pos);
			$("#noFax").val(json.data.no_fax);
			$("#emailPerusahaan").val(json.data.email_perusahaan);
			$("#Website").val(json.data.website);
			$("#noTelp").val(json.data.no_telp);
			$("#jatahCuti").val(json.data.jatah_cuti);
			$("#budgetPenggajian").val(json.data.budget_penggajian);
			$("#reward1").val(json.data.reward_top1);
			$("#reward2").val(json.data.reward_top2);
			$("#reward3").val(json.data.reward_top3);
			$("#punishment1").val(json.data.punishment_top1);
			$("#punishment2").val(json.data.punishment_top2);
			$("#punishment3").val(json.data.punishment_top3);
			$("#totalkerja").val(json.data.total_hari);
			$('#opsi_hari').val(json.data.otomatis_hari);
			// $('#dinas').val(json.data.dinas);
			// $('#cuti').val(json.data.cuti);
			// $('#izin').val(json.data.izin);
			// $('#sakit').val(json.data.sakit);
			// $('#dirumahkan').val(json.data.dirumahkan);
			$('#tanggal_penggajian1').val(json.data.tanggal_payment1);
			$('#tanggal_penggajian2').val(json.data.tanggal_payment2);
			$('#tanggal_penggajian3').val(json.data.tanggal_payment3);
			$('#tanggal_penggajian4').val(json.data.tanggal_payment4);
			$('#menitTelat1').val(json.data.telat1);
			$('#menitTelat2').val(json.data.telat2);
			$('#menitTelat3').val(json.data.telat3);
			$('#jumlahPotongan1').val(json.data.potongan_telat1);
			$('#jumlahPotongan2').val(json.data.potongan_telat2);
			$('#jumlahPotongan3').val(json.data.potongan_telat3);
			$('#hari_minggu').val(json.data.hari_minggu);
			$('#hari_libur').val(json.data.hari_libur);
			$('#opsi_kerajinan').val(json.data.opsi_kerajinan);
			$('#nilaiKerajinan').val(json.data.kerajinan);
			$('#periode_penilaian').val(json.data.periode_penilaian);
			$('#lembur1').val(json.data.lembur1);
			$('#lembur2').val(json.data.lembur2);
			$('#lembur3').val(json.data.lembur3);
			$('#lembur_break1').val(json.data.break1);
			$('#lembur_break2').val(json.data.break2);
			$('#lembur_break3').val(json.data.break3);
			$('#opsi_cuti').val(json.data.opsi_cuti);
			$('#opsi_dinas').val(json.data.opsi_dinas);
			$('#opsi_sakit').val(json.data.opsi_sakit);
			$('#opsi_izin').val(json.data.opsi_izin);
			$('#opsi_dirumahkan').val(json.data.opsi_dirumahkan);
			$('#opsi_libur').val(json.data.opsi_libur);
			$('#opsi_minggu').val(json.data.opsi_minggu);

			if (json.data.opsi_cuti == 0) {
				$('#opsi_golongan_cuti').val(json.data.golongan_cuti);
				$('.hideopsicuti').show(800);
				$('.hideopsicuti1').hide(800);
			}
			else {
				$('.hideopsicuti').hide(800);
				$('.hideopsicuti1').show(800);
				$('#cuti').val(json.data.cuti);
			}

			if (json.data.opsi_sakit == 0) {
				$('#opsi_golongan_sakit').val(json.data.golongan_sakit);
				$('.hideopsisakit').show(800);
				$('.hideopsisakit1').hide(800);
			}
			else {
				$('.hideopsisakit').hide(800);
				$('.hideopsisakit1').show(800);
				$('#sakit').val(json.data.sakit);
			}

			if (json.data.opsi_izin == 0) {
				$('#opsi_golongan_izin').val(json.data.golongan_izin);
				$('.hideopsiizin').show(800);
				$('.hideopsiizin1').hide(800);
			}
			else {
				$('.hideopsiizin').hide(800);
				$('.hideopsiizin1').show(800);
				$('#izin').val(json.data.izin);
			}

			if (json.data.opsi_dirumahkan == 0) {
				$('#opsi_golongan_dirumahkan').val(json.data.golongan_dirumahkan);
				$('.hideopsidirumahkan').show(800);
				$('.hideopsidirumahkan1').hide(800);
			}
			else {
				$('.hideopsidirumahkan').hide(800);
				$('.hideopsidirumahkan1').show(800);
				$('#dirumahkan').val(json.data.dirumahkan);
			}

			if (json.data.opsi_dinas == 0) {
				$('#opsi_golongan_dinas').val(json.data.golongan_dinas);
				$('.hideopsidinas').show(800);
				$('.hideopsidinas1').hide(800);
			}
			else {
				$('.hideopsidinas').hide(800);
				$('.hideopsidinas1').show(800);
				$('#dinas').val(json.data.dinas);
			}

			if (json.data.opsi_libur == 0) {
				$('#opsi_golongan_libur').val(json.data.golongan_libur);
				$('.hideopsilibur').show(800);
				$('.hideopsilibur1').hide(800);
			}
			else {
				$('.hideopsilibur').hide(800);
				$('.hideopsilibur1').show(800);
				$('#hari_libur').val(json.data.hari_libur);
			}

			if (json.data.opsi_minggu == 0) {
				$('#opsi_golongan_minggu').val(json.data.golongan_minggu);
				$('.hideopsiminggu').show(800);
				$('.hideopsiminggu1').hide(800);
			}
			else {
				$('.hideopsiminggu').hide(800);
				$('.hideopsiminggu1').show(800);
				$('#hari_minggu').val(json.data.hari_minggu);
			}

			if (json.data.opsi_kerajinan == 0) {
				$('#showKerajinan').hide(800);
			}
			else {
				$('#showKerajinan').show(800);
			}
			if (json.data.extra_break == 1) {
				$('#checkbox3').prop('checked', true);
				$('.showBreak').show(800);
			}
			else {
				$('#checkbox3').prop('checked', false);
				$('#lembur1').val(0);
				$('#lembur2').val(0);
				$('#lembur3').val(0);
				$('#lembur_break1').val(0);
				$('#lembur_break2').val(0);
				$('#lembur_break3').val(0);
				$('.showBreak').hide(800);
			}

			if (json.data.extra_approval == 1) {
				$('#checkbox4').prop('checked', true);
			}
			else {
				$('#checkbox4').prop('checked', false);
			}

			if (json.data.otomatis_hari == 0) {
				$('#col_kerja').hide(800)
			}
			else {
				$('#col_kerja').show(800)
			}
		} else {

			$("#inputMessage").html(json.message);
			$('#namaPerusahaan').val("");
			$('#Alamat').val("");
			CKEDITOR.instances.editor9.setData("");
			$("#provinsi").val("");
		  $("#kabupaten").val("");
			$('#img_photo').attr('src',"");
				$('#popUpPhotoLogoOut').attr('href',"");
				$('#popUpPhotoLogoIn').attr('src',"");
			$('#img_photo1').attr('src',"");
				$('#detailPopUpPhotoOut').attr('href',"");
				$('#detailPopUpPhotoIn').attr('src',"");
			$("#KodePos").val("");
			$("#noFax").val("");
			$("#emailPerusahaan").val("");
			$("#Website").val("");
			$("#noTelp").val("");
			$("#jatahCuti").val("");
			$("#budgetPenggajian").val("");
			$("#reward1").val("");
			$("#reward2").val("");
			$("#reward3").val("");
			$("#punishment1").val("");
			$("#punishment2").val("");
			$("#punishment3").val("");
			$("#totalkerja").val("");
			$('#opsi_hari').val("");
			$('#dinas').val("");
			$('#cuti').val("");
			$('#izin').val("");
			$('#sakit').val("");
			$('#dirumahkan').val("");
			$('#tanggal_penggajian1').val("");
			$('#tanggal_penggajian2').val("");
			$('#tanggal_penggajian3').val("");
			$('#tanggal_penggajian4').val("");
			$('#periode_penilaian').val("");
			$('#opsi_golongan_dinas').val("");
			$('#dinas').val("");
			$('#opsi_golongan_cuti').val("");
			$('#cuti').val("");
			$('#opsi_golongan_izin').val("");
			$('#izin').val("");
			$('#opsi_golongan_dirumahkan').val("");
			$('#dirumahkan').val("");
			$('#opsi_golongan_sakit').val("");
			$('#sakit').val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});
}

function getBpjs() {
	$.post(base_url+"setting/getBpjs",function(json) {
		if (json.status == true) {
			$('#jht_perusahaan').val(json.data.jht_perusahaan);
			$('#jht_karyawan').val(json.data.jht_karyawan);
			$('#jkk_sangat_rendah').val(json.data.jkk1);
			$('#jkk_rendah').val(json.data.jkk2);
			$('#jkk_sedang').val(json.data.jkk3);
			$('#jkk_tinggi').val(json.data.jkk4);
			$('#jkk_sangat_tinggi').val(json.data.jkk5);
			$('#jkm').val(json.data.jkm);
			$('#jp_perusahaan').val(json.data.jp_perusahaan);
			$('#jp_karyawan').val(json.data.jp_karyawan);
			$('#jp_perusahaan_max').val(json.data.jp_perusahaan_max);
			$('#jp_karyawan_max').val(json.data.jp_karyawan_max);
			$('#bpjs_perusahaan').val(json.data.bpjs_kesehatan_perusahaan);
			$('#bpjs_karyawan').val(json.data.bpjs_kesehatan_karyawan);
			$('#bpjs_perusahaan_max').val(json.data.bpjs_kesehatan_perusahaan_max);
			$('#bpjs_karyawan_max').val(json.data.bpjs_kesehatan_karyawan_max);

		}
		else {
			$('#jht_perusahaan').val("");
			$('#jht_karyawan').val("");
			$('#jkk_sangat_rendah').val("");
			$('#jkk_rendah').val("");
			$('#jkk_sedang').val("");
			$('#jkk_tinggi').val("");
			$('#jkk_sangat_tinggi').val("");
			$('#jkm').val("");
			$('#jp_perusahaan').val("");
			$('#jp_karyawan').val("");
			$('#jp_perusahaan_max').val("");
			$('#jp_karyawan_max').val("");
			$('#bpjs_perusahaan').val("");
			$('#bpjs_karyawan').val("");
			$('#bpjs_perusahaan_max').val("");
			$('#bpjs_karyawan_max').val("");
		}
	});
}

$('#opsi_cuti').change(function(event) {
		if ($(this).val() == 0) {
			$('.hideopsicuti').show(800);
			$('.hideopsicuti1').hide(800);
			$('#cuti').val(0);
		}
		else {
			$('.hideopsicuti').hide(800);
			$('.hideopsicuti1').show(800);
		}
});

$('#opsi_izin').change(function(event) {
		if ($(this).val() == 0) {
			$('.hideopsiizin').show(800);
			$('.hideopsiizin1').hide(800);
			$('#izin').val(0);
		}
		else {
			$('.hideopsiizin').hide(800);
			$('.hideopsiizin1').show(800);
		}
});

$('#opsi_sakit').change(function(event) {
		if ($(this).val() == 0) {
			$('.hideopsisakit').show(800);
			$('.hideopsisakit1').hide(800);
			$('#sakit').val(0);
		}
		else {
			$('.hideopsisakit').hide(800);
			$('.hideopsisakit1').show(800);
		}
});

$('#opsi_dinas').change(function(event) {
		if ($(this).val() == 0) {
			$('.hideopsidinas').show(800);
			$('.hideopsidinas1').hide(800);
			$('#dinas').val(0);
		}
		else {
			$('.hideopsidinas').hide(800);
			$('.hideopsidinas1').show(800);
		}
});

$('#opsi_dirumahkan').change(function(event) {
		if ($(this).val() == 0) {
			$('.hideopsidirumahkan').show(800);
			$('.hideopsidirumahkan1').hide(800);
			$('#dirumahkan').val(0);
		}
		else {
			$('.hideopsidirumahkan').hide(800);
			$('.hideopsidirumahkan1').show(800);
		}
});

$('#opsi_minggu').change(function(event) {
		if ($(this).val() == 0) {
			$('.hideopsiminggu').show(800);
			$('.hideopsiminggu1').hide(800);
			$('#hari_minggu').val(0);
		}
		else {
			$('.hideopsiminggu').hide(800);
			$('.hideopsiminggu1').show(800);
		}
});

$('#opsi_libur').change(function(event) {
		if ($(this).val() == 0) {
			$('.hideopsilibur').show(800);
			$('.hideopsilibur1').hide(800);
			$('#hari_libur').val(0);
		}
		else {
			$('.hideopsilibur').hide(800);
			$('.hideopsilibur1').show(800);
		}
});

$("#Save").click(function() {
	var url;
	for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
		}

		url = base_url+'setting/update/1';

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
				$("#inputMessage").html(json.message);
				swal({
						 title: json.message,
						 type: "success",
						            // html: true,
						 timer: 2000,
						 showConfirmButton: false
						        });
							reloadTable();
				setTimeout(function() {
					// $("#formData")[0].reset();
					$("#modalForm").modal("hide");
					$("#inputMessage").html("");
					reloadTable();
				}, 1500);
			} else {
				$("#inputMessage").html(json.message);
				$("#errorTanggal").html(json.error.tanggal);
				$("#errorNamaEvent").html(json.error.event);
				$("#errorTipe").html(json.error.tipe);
				$("#errorKeterangan").html(json.error.keterangan);



				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorTanggal").html("");
					$("#errorNamaEvent").html("");
					$("#errorTipe").html("");
					$("#errorKeterangan").html("");
				},3000);
			}
		}
	});
});

$("#Save1").click(function() {
	var url;
	for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
		}

		url = base_url+'setting/update/1';

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
				$("#inputMessage").html(json.message);
				swal({
						 title: json.message,
						 type: "success",
						            // html: true,
						 timer: 2000,
						 showConfirmButton: false
						        });
							reloadTable();
				setTimeout(function() {
					// $("#formData")[0].reset();
					$("#modalForm").modal("hide");
					$("#inputMessage").html("");
					reloadTable();
				}, 1500);
			} else {
				$("#inputMessage").html(json.message);
				$("#errorTanggal").html(json.error.tanggal);
				$("#errorNamaEvent").html(json.error.event);
				$("#errorTipe").html(json.error.tipe);
				$("#errorKeterangan").html(json.error.keterangan);
				$('#errortelat2').html(json.error.menitTelat2);
				$('#errortelat3').html(json.error.menitTelat3);



				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorTanggal").html("");
					$("#errorNamaEvent").html("");
					$("#errorTipe").html("");
					$("#errorKeterangan").html("");
					$('#errortelat2').html("");
					$('#errortelat3').html("");
				},3000);
			}
		}
	});
});

$("#SaveBpjs").click(function() {
	var url;
	url = base_url+'setting/updateBpjs';
	var formData = new FormData($("#formDataBpjs")[0]);
	$.ajax({
		url: url,
		type:'POST',
		data: formData,
		contentType:false,
		processData:false,
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				swal({
						 title: json.message,
						 type: "success",
						            // html: true,
						 timer: 1500,
						 showConfirmButton: false
						        });
				getBpjs();
				$('#moneyJpPerusahaan,#moneyJpKaryawan,#moneyBpjsPerusahaan,#moneyBpjsKaryawan').html("");
			} else {
				swal({
						 title: json.message,
						 type: "error",
						            // html: true,
						 timer: 1500,
						 showConfirmButton: false
						        });
			}
		}
	});
});

$("#tambahTHR").click(function() {
	var url;
	if (save_method == "addExtra") {
		url = base_url+'setting/addExtra';
	} else {
		url = base_url+'setting/updateExtra/'+idData;
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
					$("#TambahTHR").modal("hide");
					$("#inputMessage11").html("");
					reloadTable();
				}, 1500);
			} else {
				$("#inputMessage11").html(json.message);
				$("#errorTanggal11").html(json.error.tanggal);
				$("#errorAgama11").html(json.error.agama);
				setTimeout(function() {
					$("#inputMessage11").html("");
					$("#errorTanggal11").html("");
					$("#errorAgama11").html("");

				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"setting/getId1/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Tanggal : <i>"+json.data.tanggal+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.agama+"</i></small></li><br>";

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
		    		$.post(base_url+"setting/delete/"+idData,function(json) {
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
