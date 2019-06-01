$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";   	
    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblPekerja").DataTable({
		serverSide:true,
		responsive:true,
		
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
			url: base_url+'/pekerjaborongan/pekerja/ajax_list',
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
			
			{ data:'idfp' },
			{ data:'nama' },
			{ data:'kelamin' },
			// { data:'departemen' },
			{ data:'telepon' },
		],
		/*dom: 'Bfrtip',
		buttons: [
		  	'excel', 'pdf', 'print','colvis'
		],*/

	});


});

function reloadTable() {
	$("#tblPekerja").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

var srcPhotoDefault = base_url+"assets/images/default/no_user.png";

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
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$("#no_id").attr("readonly",false);
	$(".modal-title").text("Tambah borongan Departemen");

	$('#img_photo').attr('src',srcPhotoDefault);
   	$('#popUpPhotoOut').attr('href',srcPhotoDefault);
   	$('#popUpPhotoIn').attr('src',srcPhotoDefault);
	// $('#mata_uang_tunjangan').html("");
	save_method = "add";
});

function btnDetail(id) {
	idData = id;

	$("#dataTable").hide(800);
	$("#detailData").show(800);

	$.post(base_url+"/pekerjaborongan/pekerja/getId/"+idData,function(json) {
		if (json.status == true) {
			$("#detailNoId").html(json.data.idfp);
			$("#detailNama").html(json.data.nama);
			$("#detailAlamat").html(json.data.alamat);
			$("#detailTempatLahir").html(json.data.tempat_lahir);
			$("#detailTanggalLahir").html(json.data.tgl_lahir);
			$("#detailTelepon").html(json.data.telepon);
			$("#detailJenisKelamin").html(json.data.kelamin);
			$("#detailDepartemen").html(json.data.departemen);
			$("#detailTglMasukKerja").html(json.data.tgl_masuk);
			$("#detailNoRekening").html(json.data.rekening);
			$("#detailBank").html(json.data.bank);
			$("#detailPeriodeGajian").html(json.data.periode_gaji);
			$("#detailAtasNama").html(json.data.atas_nama);
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

function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update Data Pekerja");
	save_method = "update";

	$.post(base_url+"pekerjaborongan/pekerja/getId/"+idData,function(json) {
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
			$("#jenis_kelamin").val(json.data.kelamin);
			$("#departemen").val(json.data.id_departemen).trigger("change");
			$("#tgl_masuk_kerja").val(json.data.tgl_masuk);
			$("#no_rekening").val(json.data.rekening);
			$("#bank").val(json.data.id_bank).trigger("change");
			$("#periodeGaji").val(json.data.periode_gaji);
			$("#atas_nama").val(json.data.atas_nama);
		} else {
			$("#inputMessage").html(json.message);
			$('#nama').val("");
		    $("#bank").val("");
		    $("#alamat").val("");
		    $("#tempat_lahir").val("");
		    $("#tgl_lahir").val("");
		    $("#telepon").val("");
		    $("#jenis_kelamin").val("");
		    $("#departemen").val("");
		    $("#tgl_masuk_kerja").val("");
		    $("#no_rekening").val("");
		    $("#bank").val("");
		    $("#periodeGaji").val("");
		    $("#atas_nama").val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
			},4000);
		}
	});

}

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'pekerjaborongan/pekerja/add';
	} else {
		url = base_url+'pekerjaborongan/pekerja/update/'+idData;
	}

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
				}, 4500);
			} else {
				$("#inputMessage").html(json.message);
			    if (json.message == '') {
					$("#errorNoId").html(json.error.no_id);
					$("#errorNama").html(json.error.nama);
					$("#errorAlamat").html(json.error.alamat);
			    }
				
				// $("#errorHarga").html(json.error.harga);
				// $("#errorKeterangan").html(json.error.keterangan);

				/*swal({   
			            title: "Error Form.!",   
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorNoId").html("");
					$("#errorNama").html("");
					$("#errorAlamat").html("");
					// $("#errorHarga").html("");
					// $("#errorKeterangan").html("");
				},6000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"/pekerjaborongan/pekerja/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>No. induk : <i>"+json.data.idfp+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tempat lahir : <i>"+json.data.tempat_lahir+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tanggal lahir : <i>"+json.data.tgl_lahir+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Alamat : <i>"+json.data.alamat+"</i></small></li><br>";
				

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
		    		$.post(base_url+"/pekerjaborongan/pekerja/delete/"+idData,function(json) {
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


$("#btnZoomImg").click(function() {
	$("#popUpPhotoOut").click();
});

$("#ganti_photo").click(function() {
	$("#photo_pekerja").click();
});

$("#photo_pekerja").change(function(event){
	readURL(document.getElementById('photo_pekerja'));
	$('#is_delete_photo').val(0);
});

$("#hapus_photo").click(function() {

   	$('#img_photo').attr('src',srcPhotoDefault);
   	$('#popUpPhotoOut').attr('href',srcPhotoDefault);
   	$('#popUpPhotoIn').attr('src',srcPhotoDefault);
   	$("#photo_pekerja").val("");
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
    	$.post(base_url+"/pekerjaborongan/pekerja/getId/"+idData,function(json) {
			if (json.status == true) {
				$("#fotoKaryawan").attr("src",json.data.foto);
				$("#namaKaryawan").html(json.data.nama);
				// $("#jabatanKaryawan").html(json.data.jabatan);
				// $("#imgQrCode").html(json.data.qr_code);
				// $("#QrCode").attr("src",json.data.qrcode_file);
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
				download(pngUrl,"Kartu_"+$("#namaKaryawan").html(),"image/png");
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