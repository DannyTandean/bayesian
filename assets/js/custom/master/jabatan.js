$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblJabatan").DataTable({
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
			url: base_url+'master/jabatan/ajax_list',
			type: 'POST',
		},

		order:[[1,'ASC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'jabatan' },
			{ data:'tunjangan' },
			{ data:'keterangan' },
			{
				data:'button_action',
				searchable:false,
				orderable:false,
				// createdCell: function (td, cellData, rowData, row, col) {
			  //     	if (rowData.tunjangan == "Rp.0") {
			  //     		$(td).css('background-color','grey');
			  //     		// $(td).addClass('bg-muted');
			  //     	}
			  //   }
			}
		],
		// createdRow: function( row, data, dataIndex ) {
	  //       if ( data.tunjangan == "Rp.0" ) {
	  //        	// $(row).css('background-color','red');
	  //        	$(row).addClass('bg-danger');
	  //      	}
		//
	  //   },
	});
});

function reloadTable() {
	$("#tblJabatan").DataTable().ajax.reload(null,false);
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
    $('#tunjangan').on('input', function() {
        $('#mata_uang_tunjangan').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
});

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Master Jabatan");
	$('#mata_uang_tunjangan').html("");
	save_method = "add";
});

function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update Master Jabatan");
	save_method = "update";

	$.post(base_url+"master/jabatan/getId/"+idData,function(json) {
		if (json.status == true) {
			$('#jabatan').val(json.data.jabatan);
		    $("#tunjangan").val(json.data.tunjangan);
		    $('#mata_uang_tunjangan').html(json.data.tunjangan_rp);
		    $("#keterangan").val(json.data.keterangan);
		} else {
			$("#inputMessage").html(json.message);

			$('#jabatan').val("");
		    $("#tunjangan").val("");
		    $('#mata_uang_tunjangan').html("");
		    $("#keterangan").val("");
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
		url = base_url+'master/jabatan/add';
	} else {
		url = base_url+'master/jabatan/update/'+idData;
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
				$("#errorJabatan").html(json.error.jabatan);
				$("#errorTunjangan").html(json.error.tunjangan);
				$("#errorKeterangan").html(json.error.keterangan);

				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$("#inputMessage").html("");
					$("#errorJabatan").html("");
					$("#errorTunjangan").html("");
					$("#errorKeterangan").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"master/jabatan/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Tunjangan : <i>"+json.data.tunjangan_rp+"</i></small></li><br>";
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
		    		$.post(base_url+"master/jabatan/delete/"+idData,function(json) {
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
