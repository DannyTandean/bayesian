$(document).ready(function() {
	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";
    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblPenggajian").DataTable({
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
			url: base_url+'pekerjaborongan/LaporanPenggajian/ajax_list',
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
			{ data:'kode_payroll'},
			{ data:'idfp' },
			{ data:'nama' },
			{ data:'gaji_total' },
			{ data:'tanggal_proses' },


		],

		dom : "<'row' <'col-md-4'l> <'col-md-4'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'excel', 'pdf', 'colvis'
    ],
	});

		// $("#printing").click(function(){
		// 	$("#asd").print({
		// 		globalStyles: true,
		// 		mediaPrint: false,
		// 		stylesheet: null,
		// 		noPrintSelector: ".no-print",
		// 		iframe: true,
		// 		append: null,
		// 		prepend: null,
		// 		manuallyCopyFormValues: true,
		// 		deferred: $.Deferred(),
		// 		timeout: 750,
		// 		title: null,
		// 		doctype: '<!doctype html>'
		// 		});
		// });

	// $(document).on("click", "tr[role='row']", function(){
  //   alert($(this).children('td:first-child').text())
	// });
});

function reloadTable() {
	$("#tblPenggajian").DataTable().ajax.reload(null,false);
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

function btnPrint(id,kodepayroll)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");
	$.post(base_url+"pekerjaborongan/penggajian/getSlipGajiKaryawan/"+id+"/"+kodepayroll,function(json) {
		if (json.status == true) {

				$("#perusahaan1").html(json.data2);
				$("#idfp1").html(" : " + json.data1.idfp +" / "+json.data1.nama);
				$("#periode11").empty().html(" : " + json.data1.periode_penggajian);
				// $("#departemen1").html(" : " + json.data1.departemen);
				$("#rekening1").html(" : "+json.data1.rekening);
				$("#bank1").html(" : "+json.data1.bank);
				$('#gajiTotal1').html(json.data2.gaji_total);
				// $('#harga1').html(json.data4.harga1);
				$('#totalItem1').html(json.data4.totalItem1);
				$("#harikerja1").html(" : "+json.data1.hari_kerja +" Hari");
				$("#totalGaji").html(" : " + json.data4.totalGaji);
				$("#totalGaji1").html(" : " + json.data4.totalGaji);
				$("#totalGaji11").html(" : " + json.data4.totalGaji);
				$("#totalBersih1").html(" : " + json.data4.gajiBersih);
				var tbody = "";
					$.each(json.data5,function(key, val) {
						tbody += '<tr>'+'<td>'+(key+1)+'</td>'+
														'<td>'+json.data1.nama+'</td>'+
														// '<td>'+ json.data1.idfp +" / "+json.data1.nama+'</td>'+
														'<td>'+val.tanggal+'</td>'+
														'<td>'+val.departemen+'</td>'+
														'<td>'+val.jumlah+'</td>'+
														'<td>'+val.harga+'</td>'+
														'<td>'+val.pendapatan+'</td>'+
										'</tr>'
					});
					tbody += '<tr>'+
										'<td>&nbsp;</td>' +
										'<td>&nbsp;</td>' +
										'<td>&nbsp;</td>' +
										'<td>&nbsp;</td>' +
										'<td>&nbsp;</td>' +
										'<td>Total Pendapatan</td>'+
										'<td>'+json.data4.totalPendapatan+'</td>'+
									 '</tr>'
					$("#listproduksi").find('tbody').html(tbody);
				} else {

		}
	});
}

$("#btnExcel").click(function(){

  $("#listproduksi").table2excel({
    exclude: ".noExl",
    name: "Detail Penggajian",
    filename: "Detail Penggajian" //do not include extension

  });

});


$("#Process").click(function() {
	var url;
	url = base_url+'pekerjaborongan/LaporanPenggajian/ajax_list_after/'+String($("#before").val())+"/"+String($("#after").val());
	// url = base_url+'statistik/cuti/ajax_list';

	var table = $('#tblPenggajian').DataTable();

	table.ajax.url(url).load();

});