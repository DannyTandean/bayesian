$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblSakit").DataTable({
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
			url: base_url+'aktivitas/sakit/ajax_list',
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
			{ data:'jabatan' },
			{ data:'status' },
			{ data:'tgl_sakit' },
			{ data:'akhir_sakit' },
			{ data:'keterangans' },
			{ data:'file' },
			{ data:'lama' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
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
	$("#mulaiSakit").dateDropper( {
					dropWidth: 200,
					init_animation: "bounce",
					dropPrimaryColor: "#1abc9c",
					dropBorder: "1px solid #1abc9c",
					maxYear: "2050",
					format: "Y-m-d"
			});
	$("#akhirSakit").dateDropper( {
					dropWidth: 200,
					init_animation: "bounce",
					dropPrimaryColor: "#1abc9c",
					dropBorder: "1px solid #1abc9c",
					maxYear: "2050",
					format: "Y-m-d"
			});


});

 function readURL(input) {
 	var filePath = input.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){ // validate format extension file
        // alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
       
        swal({   
	            title: "<h2 style='color:red;'>Error File!</h2>",   
	            html: "File :  <small><span style='color:red;'> Jenis file yang Anda upload tidak diperbolehkan.</span> <br> Harap hanya upload file yang memiliki ekstensi <br> .jpeg | .jpg | .png </small>",
	            type: "warning",
	        });
	     

        input.value = '';
        return false;

    } else if (input.files[0].size > 1048576) { // validate size file 1 mb
		// alert("file size lebih besar dari 1 mb, file size yang di upload = "+input.files[0].size);
		swal({   
	            title: "<h2 style='color:orange;'>Notice Photo!</h2>",   
	            html: "File : <small style='color:orange;'>File Yang Diupload melebihi ukuran maksimal diperbolehkan yaitu 1 MB.</small>",
	            type: "warning",
	        });
	 // alert (
	 // 			"File yang diupload melebihi ukuran maksimal yang diperbolehkan yaitu 1 MB.",
	 // 	)
        input.value = '';
		return false;

	} else {
	   	if (input.files && input.files[0])
	   	{
		    var reader = new FileReader();
		    reader.onload = function (e)
		    {
		       $('#uploadfile').attr('src',e.target.result);
		       $('#popUpFileIn').attr('src',e.target.result);
		       $('#popUpFileOut').attr('href',e.target.result);
		    };
		    reader.readAsDataURL(input.files[0]);
	   	}
	 }

  }

function reloadTable() {
	$("#tblSakit").DataTable().ajax.reload(null,false);
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

//validasi tanggal
$("#mulaiSakit, #akhirSakit").change(function() {
	if($("#mulaiSakit").val() != "" && $("#akhirSakit").val() != ""){
		var startDate = new Date($('#mulaiSakit').val());
		var expiredDate = new Date($('#akhirSakit').val());
		if (startDate > expiredDate){
			swal({   
		            title: "Information Notice!",   
		            html: "<span style='color:orange'>Tanggal Akhir Sakit harus sama atau setelah hari dari Mulai Sakit</span>",
		            type: "warning",
		        });
			$("#akhirSakit").val("");
		}
	}
});


$(document).ready(function(){

	$("#karyawan").select2({
		placeholder: '-- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"aktivitas/izin/allKaryawanAjax",
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

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$('#uploadfile').attr('src',"https://via.placeholder.com/320x180?text=File+Image");
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Aktivitas Sakit karyawan");
	$("#karyawan").prop('disabled',false);
	$("#tanggal").prop('disabled',false);
	$("#karyawan").val("").trigger('change');
	save_method = "add";
});

$("#karyawan").change(function(){
	var val = $(this).val();
	if (val != "" || val != null) {
		$.post(base_url+"aktivitas/sakit/getNama/"+val,function(json) {
			if(json.status == true)
			{
				$("#departemen").val(json.data.departemen);
				$("#jabatan").val(json.data.jabatan);
			}else {
				if (val === null) {
					$("#inputMessage").html("");
				} else {
					$("#inputMessage").html(json.message);
				}
				
				$("#departemen").val("");
				$("#jabatan").val("");
			}
		});
	} else {
		$("#departemen").val("");
		$("#jabatan").val("");
	}
	$("#modalForm").css('overflow-y', 'scroll');
});

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'aktivitas/sakit/add';
	} else {
		url = base_url+'aktivitas/sakit/update/'+idData;
	}
	$("#modalButtonSave").attr("disabled",true);
	$("#modalButtonSave").html("Processing... <i class='fa fa-spinner fa-spin'></i>")
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
				 if (json.message == '') {
				$("#errorTanggal").html(json.error.tanggal);
				$("#errorKaryawan").html(json.error.karyawan);
				$("#errorMulaiSakit").html(json.error.mulaiSakit);
				$("#errorAkhirSakit").html(json.error.akhirSakit);
				$("#errorKeterangan").html(json.error.keterangans);
				}


				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"</i> Simpan')
					$("#inputMessage").html("");
					$("#errorTanggal").html("");
					$("#errorKaryawan").html("");
					$("#errorMulaiSakit").html("");
					$("#errorAkhirSakit").html("");
					$("#errorKeterangan").html("");
				},5000);
			}
		}
	});
});

function btnEdit(id) {
	idData = id;

	$(".modal-title").text("Update Aktivitas Sakit Karyawan");
	save_method = "update";
	$("#tanggal").prop('disabled',true);
	$.post(base_url+"aktivitas/sakit/getId/"+idData,function(json) {
		if (json.status == true) {
			if (json.data.status == "Diterima") {
				swal({
			            title: "<h2 style='color:orange;'>Status yang <u style='color:green;'>Diterima</u> tidak bisa di update.!</h2>",
			            type: "warning",
			            timer: 3000,
			            showConfirmButton: false
			        });
			} else if (json.data.status == "Proses") {
				$("#modalForm").modal("show");
				$('#tanggal').val(json.data.tanggal);
		        $("#keterangan").val(json.data.keterangans);
				// $("#karyawan").val(json.data.nama_karyawan);
				$("#mulaiSakit").val(json.data.tgl_sakit);
				$("#akhirSakit").val(json.data.akhir_sakit);
				$("#departemen").val(json.data.departemen);
				$("#jabatan").val(json.data.jabatan);
				$("#uploadfile").attr("src",json.data.file);
				$("#karyawan").empty().append('<option value="'+json.data.id_karyawan+'">'+json.data.nama+'</option>').val(json.data.id_karyawan).trigger('change');

			}else{
				swal({
			            title: "<h2 style='color:orange;'>Status yang <u style='color:red;'>Ditolak</u> tidak bisa di update.!</h2>",
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
			$("#mulaiSakit").val("");
			$("#akhirSakit").val("");
	
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},1000);
		}
	});

}

function btnDelete(id) {
	idData = id;

	$.post(base_url+"aktivitas/sakit/getId/"+idData,function(json) {
		if (json.status == true) {
			if(json.data.status == "Proses" || json.data.status == "Ditolak") {
			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Karyawan : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Mulai Sakit : <i>"+json.data.tgl_sakit+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Akhir Sakit : <i>"+json.data.akhir_sakit+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangans+"</i></small></li><br>";

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
		    		$.post(base_url+"aktivitas/sakit/delete/"+idData,function(json) {
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
	    } else if(json.data.status == "Diterima"){
			swal({
								title: "<h2 style='color:orange;'>Status yang <u style='color:green;'>Diterima</u> tidak bisa di Hapus.!</h2>",
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

/* Image Upload */

$("#btnZoomImg").click(function() {
	$("#popUpPhotoOut").click();
});


$("#uploadfile").change(function(event){
	readURL(document.getElementById('uploadfile'));
	$('#is_delete_photo').val(0);
});

$("#hapus_photo").click(function() {

   	$('#img_photo').attr('src',srcPhotoDefault);
   	$('#popUpPhotoOut').attr('href',srcPhotoDefault);
   	$('#popUpPhotoIn').attr('src',srcPhotoDefault);
   	$("#uploadfile").val("");
   	$('#is_delete_photo').val(1);	
});


