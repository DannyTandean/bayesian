$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";
   	
    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblCabang").DataTable({
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
			url: base_url+'master/cabang/ajax_list',
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
			{ data:'kode' },
			{ data:'cabang' },
			{ data:'alamat' },
			{ data:'kota' },
			{ data:'provinsi' },
			{ data:'kode_pos' },
			{ data:'negara' },
			{ data:'telepon' },
			{ data:'fax' },
			{ data:'email' },
		],
	});
});

function reloadTable() {
	$("#tblCabang").DataTable().ajax.reload(null,false);
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

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Master Cabang");
	// $('#mata_uang_tunjangan').html("");
	save_method = "add";
});

function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update Master Cabang");
	save_method = "update";

	$.post(base_url+"master/cabang/getId/"+idData,function(json) {
		if (json.status == true) {
			$('#kode').val(json.data.kode);
			$('#cabang').val(json.data.cabang);
			$('#alamat').val(json.data.alamat);
			$('#kota').val(json.data.kota);
			$('#provinsi').val(json.data.provinsi);
			$('#kode_pos').val(json.data.kode_pos);
			$('#negara').val(json.data.negara);
			$('#telepon').val(json.data.telepon);
			$('#fax').val(json.data.fax);
		    $("#email").val(json.data.email);
		    // $('#mata_uang_tunjangan').html(json.data.tunjangan_rp);
		} else {
			$("#inputMessage").html(json.message);

			$('#kode').val("");
		    $("#cabang").val("");
		    $("#alamat").val("");
		    $("#kota").val("");
		    $("#provinsi").val("");
		    $("#kode_pos").val("");
		    $("#negara").val("");
		    $("#telepon").val("");
		    $("#fax").val("");
		    $("#email").val("");
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
		url = base_url+'master/cabang/add';
	} else {
		url = base_url+'master/cabang/update/'+idData;
	}

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
				}, 1500);
			} else {
				$("#inputMessage").html(json.message);
				$("#errorKode").html(json.error.kode);
				$("#errorNama").html(json.error.cabang);
				$("#errorAlamat").html(json.error.alamat);
				$("#errorKota").html(json.error.kota);
				$("#errorProvinsi").html(json.error.provinsi);
				$("#errorKode_pos").html(json.error.kode_pos);
				$("#errorNegara").html(json.error.negara);
				$("#errorTelepon").html(json.error.telepon);
				$("#errorFax").html(json.error.fax);
				$("#errorEmail").html(json.error.email);

				/*swal({   
			            title: "Error Form.!",   
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorKode").html("");
					$("#errorNama").html("");
					$("#errorAlamat").html("");
					$("#errorKota").html("");
					$("#errorProvinsi").html("");
					$("#errorKode_pos").html("");
					$("#errorNegara").html("");
					$("#errorTelepon").html("");
					$("#errorFax").html("");
					$("#errorEmail").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"master/cabang/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Kode : <i>"+json.data.kode+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.cabang+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Alamat : <i>"+json.data.alamat+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Kota : <i>"+json.data.kota+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Provinsi : <i>"+json.data.provinsi+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Kode_pos : <i>"+json.data.kode_pos+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Negara : <i>"+json.data.negara+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Telepon : <i>"+json.data.telepon+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Fax : <i>"+json.data.fax+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Email : <i>"+json.data.email+"</i></small></li><br>";

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
		    		$.post(base_url+"master/cabang/delete/"+idData,function(json) {
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