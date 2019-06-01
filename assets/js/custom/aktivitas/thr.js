$(document).ready(function() {
	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";
    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblThr").DataTable({
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
			url: base_url+'aktivitas/thr/ajax_list',
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
				data:'button_tool',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal'},
			{ data:'nama' },
			{ data:'jabatan' },
			{ data:'departemen' },
			{ data:'nilai' },

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

		$("#printing").click(function(){
			$("#asd").print({
				globalStyles: true,
				mediaPrint: false,
				stylesheet: null,
				noPrintSelector: ".no-print",
				iframe: true,
				append: null,
				prepend: null,
				manuallyCopyFormValues: true,
				deferred: $.Deferred(),
				timeout: 750,
				title: null,
				doctype: '<!doctype html>'
				});
		});

});

function reloadTable() {
	$("#tblThr").DataTable().ajax.reload(null,false);
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


$("#Process").click(function() {
	var url;
	url = base_url+'aktivitas/thr/generate_penggajian';

	$.ajax({
		url: url,
		type:'POST',
		data:$("#formDataSearch").serialize(),
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				const toast = swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 1500
				});
				toast({
					type: 'success',
					title: json.message
				})
				$("#inputMessage").html(json.message);
				reloadTable();
				setTimeout(function() {
					$("#inputMessage").html("");
				}, 1500);
			} else {
				const toast = swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 1500
				});
				toast({
					type: 'warning',
					title: json.message
				})
				// $("#inputMessage").html(json.message);
				// setTimeout(function() {
				// 	$("#inputMessage").html("");
				// }, 1500);
			}
		}
	});
});

function btnPrint(id)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");
	//get data absensi
	$.post(base_url+"aktivitas/thr/getSlipGajiKaryawan/"+id,function(json) {
		if (json.status == true) {
			var tbody = "";
				$("#tableDataAbsensi").find('tbody').html(tbody);
				$("#idfp").html(" : " + json.data.idfp +" / "+json.data.nama);
				$("#periode1").empty().html(" : " + json.data.periode_gaji +" / "+json.data.tgl_masuk);
				$("#departemen").html(" : " + json.data.departemen +" / "+json.data.jabatan);
				$("#golongan").html(" : "+json.data.golongan);
				$("#cabang").html(" : "+json.data.cabang);
				$("#rekening").html(" : "+json.data.rekening);
				$("#bank").html(" : "+json.data.bank);
				$("#thr").html(" : "+json.data.nilai);
		} else {

		}
	});
}
