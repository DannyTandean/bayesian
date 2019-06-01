$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblGolongan").DataTable({
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
			url: base_url+'master/golongan/ajax_list',
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
			{ data:'golongan' },
			{ data:'gaji' },
			{ data : 'gaji_menit'},
			{ data:'makan' },
			// { data:'kehadiran' },
			{ data:'transport' },
			{ data:'thr' },
			// { data:'tundip' },
			{ data:'tunjangan_lain' },
			// { data:'absen' },
			{ data:'bpjs' },
			{ data:'pot_lain' },
			{ data:'lembur_tetap' },
			{ data:'umk' },
			{ data:'keterangan' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
});

function reloadTable() {
	$("#tblGolongan").DataTable().ajax.reload(null,false);
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
	var totalHari = 0;
		$.post(base_url+"/setting/getId/1",function(json) {
			if (json.status == true) {
				totalHari = json.data.total_hari;
			}
		})
    $('#gajiGolongan').on('input', function() {
        $('#mata_uang_gajiGol').html( moneyFormat.to( parseInt($(this).val()) ) );
				$('#mata_uang_umk').html("");
				if ($(this).val() == 0) {
					$('#rekomendasi_gaji').html("");
					$('#permenit').val("");
					$('#umk').val("");
				}
				else {
					// $('#rekomendasi_gaji').html("Rekomendasi : "+moneyFormat.to(parseInt($(this).val()/1440)));
					$('#permenit').val($(this).val()/1440);
					$('#umk').val($(this).val() * totalHari);
				}

    });
		$('#umk').on('input', function() {
        $('#mata_uang_umk').html( moneyFormat.to( parseInt($(this).val()) ) );
				$('#mata_uang_gajiGol').html("");
				if ($(this).val() == 0) {
					$('#gajiGolongan').val("");
					$('#permenit').val("");
				}
				else {
					$('#gajiGolongan').val($(this).val()/totalHari);
					$('#permenit').val($(this).val()/(1440*totalHari));
				}
    });
		$('#permenit').on('input', function() {
        $('#mata_uang_permenit').html( moneyFormat.to( parseInt($(this).val()) ) );
				// if ($(this).val() == 0) {
				// 	$('#gajiGolongan').val("");
				// 	$('#umk').val("");
				// }
				// else {
				// 	$('#gajiGolongan').val($(this).val() * 1440);
				// 	$('#umk').val($(this).val() *(totalHari * 1440));
				// }
    });
		$('#THR').on('input', function() {
        $('#mata_uang_THR').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
		$('#tunjanganDisiplin').on('input', function() {
        $('#mata_uang_TunDisiplin').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
		$('#tunjMakan').on('input', function() {
        $('#mata_uang_TunjMakan').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
		$('#TunjKehadiran').on('input', function() {
        $('#mata_uang_TunjKehadiran').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
		$('#TunjTransport').on('input', function() {
        $('#mata_uang_TunjTransport').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
		$('#TunjLain').on('input', function() {
        $('#mata_uang_TunjLain').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
		$('#PotLain').on('input', function() {
        $('#mata_uang_PotLain').html( moneyFormat.to( parseInt($(this).val()) ) );
    });
		$('#LemburTetap').on('input', function() {
        $('#mata_uang_Lembur').html( moneyFormat.to( parseInt($(this).val()) ) );
    });

});


$("#LemburTetap").attr("disabled",true);
$("#LemburHidup").attr("disabled", true);

$("#SistemLembur").change(function() {
	if ($(this).val() == "1") { // 1 for Hidup
		$("#LemburTetap").attr("disabled",true);
		// $("#LemburHidup").attr("disabled", false);
		$("#LemburTetap").val("");
		$('#mata_uang_Lembur').html("");
	}else if ($(this).val() == "") {
		$("#LemburTetap").attr("disabled",true);
		// $("#LemburHidup").attr("disabled", true);
		$("#LemburTetap").val("");
		$("#LemburHidup").val("");
		$('#mata_uang_Lembur').html("");
	}
	 else {
		$("#LemburHidup").attr("disabled", true);
		$("#LemburTetap").attr("disabled", false);
		$("#LemburHidup").val("");
		$('#mata_uang_Lembur').html("");
	}
});


$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Master Golongan");
	// $("#inputMessage").html("");
	$('#mata_uang_gajiGol').html("");
	save_method = "add";
});

function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update Master Golongan");
	save_method = "update";

	$.post(base_url+"master/Golongan/getId/"+idData,function(json) {
		if (json.status == true) {
				$('#idGolongan').val(json.data.id_golongan);
				$('#golongan').val(json.data.golongan);
		    	$("#gajiGolongan").val(json.data.gaji);
				$("#umk").val(json.data.umk);
				$("#THR").val(json.data.thr);
				$('#permenit').val(json.data.gaji_menit);
				$("#tunjanganDisiplin").val(json.data.tundip);
				$("#tunjMakan").val(json.data.makan);
				$("#TunjKehadiran").val(json.data.kehadiran);
				$("#TunjTransport").val(json.data.transport);
				$("#TunjLain").val(json.data.tunjangan_lain);
				$("#PotAbsen").val(json.data.absen);
				$("#PotBpjs").val(json.data.bpjs);
				$("#PotLain").val(json.data.pot_lain);
				$("#SistemLembur").val(json.data.lembur);
				$("#LemburTetap").val(json.data.lembur_tetap);
		    	$("#keterangan").val(json.data.keterangan);
		} else {
			// $("#inputMessage").html(json.message);
			const toast = swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000
			});
			toast({
			  type: 'warning',
			  title: json.message
			})
			$('#golongan').val("");
			$("#gajiGolongan").val("");
			$("#umk").val("");
			$("#THR").val("");
			$('#gaji_menit').val("");
			$("#tunjanganDisiplin").val("");
			$("#tunjMakan").val("");
			$("#TunjKehadiran").val("");
			$("#TunjTransport").val("");
			$("$TunjLain").val("");
			$("#PotAbsen").val("");
			$("#PotBpjs").val("");
			$("#PotLain").val("");
			$("#SistemLembur").val("");
			$("#LemburTetap"),val("");
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
		url = base_url+'master/Golongan/add';
	} else {
		url = base_url+'master/Golongan/update/'+idData;
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
				// $("#inputMessage").html(json.message);
				const toast = swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				toast({
					type: 'success',
					title: json.message
				})
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
				// $("#inputMessage").html(json.message);
				const toast = swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				toast({
					type: 'warning',
					title: json.message
				})
				$("#errorGolongan").html(json.error.golongan);
				$("#errorumk").html(json.error.umk);
				$('#errorpermenit').html(json.error.permenit);
				$("#errorGajiGolongan").html(json.error.gajiGolongan);
				$("#errorSistemLembur").html(json.error.SistemLembur);
				$("#errorKeterangan").html(json.error.keterangan);

				/*swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });*/

				setTimeout(function() {
					$("#modalButtonSave").attr("disabled",false);
					$("#modalButtonSave").html('<i class="fa fa-save"></i> Simpan');
					$("#inputMessage").html("");
					$("#errorGolongan").html("");
					$('#errorpermenit').html("");
					$("#errorumk").html("");
					$("#errorGajiGolongan").html("");
					$("#errorSistemLembur").html("");
					$("#errorKeterangan").html("");
				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"master/Golongan/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Golongan : <i>"+json.data.golongan+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>gajiGolongan : <i>"+json.data.gaji+"</i></small></li><br>";
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
		    		$.post(base_url+"master/Golongan/delete/"+idData,function(json) {
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
