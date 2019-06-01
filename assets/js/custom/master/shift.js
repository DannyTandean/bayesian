$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblShift").DataTable({
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
			url: base_url+'master/shift/ajax_list/REGULAR',
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
			// { data:'shift' },
			{ data:	'hari' },
			{ data:'awal_masuk' },
			{ data:'masuk' },
			{ data:'akhir_masuk' },
			{ data:'awal_keluar' },
			{ data:'keluar' },
			{ data:'akhir_keluar' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'durasi_break' },
			{ data:'opsi_break' }

		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

	//shift Pagi
	$("#tblShiftPagi").DataTable({
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
			url: base_url+'master/shift/ajax_list/SHIFT-PAGI',
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
			// { data:'shift' },
			{ data:	'hari' },
			{ data:'awal_masuk' },
			{ data:'masuk' },
			{ data:'akhir_masuk' },
			{ data:'awal_keluar' },
			{ data:'keluar' },
			{ data:'akhir_keluar' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'durasi_break' },
			{ data:'opsi_break' }

		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
	//shift Sore
	$("#tblShiftSore").DataTable({
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
			url: base_url+'master/shift/ajax_list/SHIFT-SORE',
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
			// { data:'shift' },
			{ data:	'hari' },
			{ data:'awal_masuk' },
			{ data:'masuk' },
			{ data:'akhir_masuk' },
			{ data:'awal_keluar' },
			{ data:'keluar' },
			{ data:'akhir_keluar' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'durasi_break' },
			{ data:'opsi_break' }

		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
	//shift Malam
	$("#tblShiftMalam").DataTable({
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
			url: base_url+'master/shift/ajax_list/SHIFT-MALAM',
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
			// { data:'shift' },
			{ data:	'hari' },
			{ data:'awal_masuk' },
			{ data:'masuk' },
			{ data:'akhir_masuk' },
			{ data:'awal_keluar' },
			{ data:'keluar' },
			{ data:'akhir_keluar' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'durasi_break' },
			{ data:'opsi_break' }

		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
	//shift Pagi 12 Jam
	$("#tblShiftPagi12").DataTable({
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
			url: base_url+'master/shift/ajax_list/SHIFT-PAGI-12-JAM',
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
			// { data:'shift' },
			{ data:	'hari' },
			{ data:'awal_masuk' },
			{ data:'masuk' },
			{ data:'akhir_masuk' },
			{ data:'awal_keluar' },
			{ data:'keluar' },
			{ data:'akhir_keluar' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'durasi_break' },
			{ data:'opsi_break' }

		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
	//shift Malam 12 Jam
	$("#tblShiftMalam12").DataTable({
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
			url: base_url+'master/shift/ajax_list/SHIFT-MALAM-12-JAM',
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
			// { data:'shift' },
			{ data:	'hari' },
			{ data:'awal_masuk' },
			{ data:'masuk' },
			{ data:'akhir_masuk' },
			{ data:'awal_keluar' },
			{ data:'keluar' },
			{ data:'akhir_keluar' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'durasi_break' },
			{ data:'opsi_break' }

		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});

	$("#tblShiftManual").DataTable({
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
			url: base_url+'master/shift/ajax_list/MANUAL',
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
			// { data:'shift' },
			{ data:	'hari' },
			{ data:'awal_masuk' },
			{ data:'masuk' },
			{ data:'akhir_masuk' },
			{ data:'awal_keluar' },
			{ data:'keluar' },
			{ data:'akhir_keluar' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'durasi_break' },
			{ data:'opsi_break' }

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

$("#reguler1").click(function(){
	$("#Reguler").show();
	$("#shiftPagi").hide();
	$("#shiftSore").hide();
	$("#shiftMalam").hide();
	$("#shiftPagi12").hide();
	$("#shiftMalam12").hide();
	$("#shiftManual").hide();
});

$("#pagi1").click(function(){
	$("#Reguler").hide();
	$("#shiftPagi").show();
	$("#shiftSore").hide();
	$("#shiftMalam").hide();
	$("#shiftPagi12").hide();
	$("#shiftMalam12").hide();
	$("#shiftManual").hide();
});

$("#sore1").click(function(){
	$("#shiftPagi").hide();
	$("#shiftPagi").hide();
	$("#shiftSore").show();
	$("#shiftMalam").hide();
	$("#shiftPagi12").hide();
	$("#shiftMalam12").hide();
	$("#shiftManual").hide();
});

$("#malam1").click(function(){
	$("#Reguler").hide();
	$("#shiftPagi").hide();
	$("#shiftSore").hide();
	$("#shiftMalam").show();
	$("#shiftPagi12").hide();
	$("#shiftMalam12").hide();
	$("#shiftManual").hide();
});

$("#pagi12").click(function(){
	$("#Reguler").hide();
	$("#shiftPagi").hide();
	$("#shiftSore").hide();
	$("#shiftMalam").hide();
	$("#shiftPagi12").show();
	$("#shiftMalam12").hide();
	$("#shiftManual").hide();
});

$("#malam12").click(function(){
	$("#Reguler").hide();
	$("#shiftPagi").hide();
	$("#shiftSore").hide();
	$("#shiftMalam").hide();
	$("#shiftPagi12").hide();
	$("#shiftMalam12").show();
	$("#shiftManual").hide();
});

$("#malam12").click(function(){
	$("#Reguler").hide();
	$("#shiftPagi").hide();
	$("#shiftSore").hide();
	$("#shiftMalam").hide();
	$("#shiftPagi12").hide();
	$("#shiftMalam12").show();
	$("#shiftManual").hide();
});

$("#manual1").click(function(){
	$("#Reguler").hide();
	$("#shiftPagi").hide();
	$("#shiftSore").hide();
	$("#shiftMalam").hide();
	$("#shiftPagi12").hide();
	$("#shiftMalam12").hide();
	$("#shiftManual").show();
});

//date time picker
$('.date').datetimepicker({format: 'HH:mm'});
// $('.datepicktime').datetimepicker({
//   use24hours: true
// });
// $('.date').css("z-index","1150");
//setup durasi and tetap pada modal
$("#durasi").hide(800);
$("#tetap").hide(800);
$("#break").change(function(){
	if($("#break").val() == 1)
	{
		$("#tetap").hide(800);
		$("#durasi").show(800);
	}
	else if($("#break").val() == 2)
	{
		$("#tetap").show(800);
		$("#durasi").hide(800);
	}
	else{
		$("#durasi").hide(800);
		$("#tetap").hide(800);
	}
});
});


function reloadTable() {
	$("#tblShift").DataTable().ajax.reload(null,false);
	$("#tblShiftPagi").DataTable().ajax.reload(null,false);
	$("#tblShiftSore").DataTable().ajax.reload(null,false);
	$("#tblShiftMalam").DataTable().ajax.reload(null,false);
	$("#tblShiftPagi12").DataTable().ajax.reload(null,false);
	$("#tblShiftMalam12").DataTable().ajax.reload(null,false);
	$("#tblShiftManual").DataTable().ajax.reload(null,false);

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
	$("#modalForm").modal("show");
	$("#formData")[0].reset();
	$(".modal-title").text("Tambah Master Shift");
	save_method = "add";
});

function btnEdit(id) {
	idData = id;
	$("#modalForm").modal("show");
	$(".modal-title").text("Update Master Shift");
	save_method = "update";
	$("#inputMessage").html("");

	$.post(base_url+"master/Shift/getId/"+idData,function(json) {
		if (json.status == true) {
			switch (json.data.hari) {
			    case "Sunday":
			        var converthari =  7;
			        break;
			    case "Monday":
			        var converthari =  1;
			        break;
			    case "Tuesday":
			        var converthari =  2;
			        break;
			    case "Wednesday":
			        var converthari =  3;
			        break;
			    case "Thursday":
			        var converthari =  4;
			        break;
			    case "Friday":
			        var converthari =  5;
			        break;
			    case "Saturday":
			        var converthari =  6;
			}

			if(json.data.opsi_break == 1)
			{
				$("#tetap").hide();
        		$("#durasi").show();
			}
			else if (json.data.opsi_break == 2)
			{
				$("#tetap").show();
        		$("#durasi").hide();
			}
			else
			{
				$("#tetap").hide();
				$("#durasi").hide();
			}
			$('#shift').val(json.data.shift);
			$('#hari').val(json.data.hari);
		    $("#awalMasuk").val(json.data.awal_masuk).trigger("change");
			$("#masuk").val(json.data.masuk).trigger("change");
			$("#akhirMasuk").val(json.data.akhir_masuk).trigger("change");
			$("#awalKeluar").val(json.data.awal_keluar).trigger("change");
			$("#keluar").val(json.data.keluar).trigger("change");
			$("#akhirKeluar").val(json.data.akhir_keluar).trigger("change");
			$("#break").val(json.data.opsi_break).trigger("change");
			$("#durasiBreak").val(json.data.durasi_break).trigger("change");
			$("#breakOut").val(json.data.break_out).trigger("change");
			$("#breakIn").val(json.data.break_in).trigger("change");
			// $("#hari").val(converthari);
		} else {
			$("#inputMessage").html(json.message);

			$('#shift').val("");
			$("#awalMasuk").val("");
			$("#masuk").val("");
			$("#akhirMasuk").val("");
			$("#awalKeluar").val("");
			$("#keluar").val("");
			$("#akhirKeluar").val("");
			$("#break").val("");
			$("#hari").val("");
			$("#durasiBreak").val("");
			$("#breakOut").val("");
			$("#breakIn").val("");
			setTimeout(function() {
				reloadTable();
				$("#modalForm").modal("hide");
				$("#inputMessage").html("");
			},1000);
		}
	});

}

$("#modalButtonSave").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'master/Shift/add';
	} else {
		url = base_url+'master/Shift/update/'+idData;
	}

	$.ajax({
		url: url,
		type:'POST',
		data:$("#formData").serialize(),
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				$("#inputMessage").html(json.message);

				swal({
			            title: json.message,
			            type: "success",
			            timer: 2000,
			            showConfirmButton: false
			        });

				setTimeout(function() {
					$("#formData")[0].reset();
					$("#modalForm").modal("hide");
					$("#inputMessage").html("");
					reloadTable();
				}, 1500);
			} else {
				$("#inputMessage").html(json.message);
				// $("#errorShift").html(json.error.shift);
				// $("#errorHari").html(json.error.hari);
				// $("#errorBreak").html(json.error.break);

				swal({
			            title: "Error Form.!",
			            html: json.message,
			            type: "error",
			        });

				setTimeout(function() {
					$("#inputMessage").html("");
					// $("#errorShift").html("");
					// $("#errorHari").html("");
					// $("#errorBreak").html("");

				},3000);
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"master/Shift/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Shift : <i>"+json.data.shift+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Hari : <i>"+json.data.hari+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Opsi Break : <i>"+json.data.opsi_break+"</i></small></li><br>";

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
		    		$.post(base_url+"master/Shift/delete/"+idData,function(json) {
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
