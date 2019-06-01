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
			url: base_url+'pekerjaborongan/penggajian/ajax_list',
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
			{ data:'total' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'excel','colvis'
    ],
	});

		$("#btnPrint").click(function(){
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


$("#Process").click(function() {
	// var total = Date.parse($('#after').val())-Date.parse($('#before').val());
	// if (total == 518400000 ) {

	var url;
	url = base_url+'pekerjaborongan/penggajian/generate_penggajian';

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
					timer: 3000
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
					timer: 3000
				});
				toast({
					type: 'warning',
					title: json.message
				})
				$("#inputMessage").html(json.message);
				setTimeout(function() {
					$("#inputMessage").html("");
				}, 1500);
			}
		}

	});
	// } else{ swal({
	// 	            title: "Information Notice!",
	// 	            html: "<span style='color:Black'>Periode Penggajian Tidak boleh kurang / lebih dari 1 minggu.</span>",
	// 	            type: "warning",
	// 	        });};
});

$('#ProsesPrint').click(function(event) {
	 $('#printList').modal("show");
	 $(".modal-title").text("Print");

	 $.post(base_url+"pekerjaborongan/penggajian/getSlipGajiKaryawanPeriode/"+$('#kode').val(),function(json) {
		 if (json.status == true) {
				 var tbody = "";
					 $.each(json.data,function(key, val) {
						 $.each(val.data_karyawan,function(key1, val1) {
							 tbody += '<tr>'+'<td>'+(key1+1)+'</td>'+
															 '<td>'+val1.nama+'</td>'+
															 // '<td>'+ json.data.idfp +" / "+json.data.nama+'</td>'+
															 '<td>'+val1.tanggal+'</td>'+
															 '<td>'+val1.departemen+'</td>'+
															 '<td>'+val1.jumlah+'</td>'+
															 '<td>'+moneyFormat.to(parseInt(val1.harga))+'</td>'+
															 '<td>'+moneyFormat.to(parseInt(val1.pendapatan))+'</td>'+
											 '</tr>'
						 })
						 tbody += '<tr>'+
											 '<td>&nbsp;</td>' +
											 '<td>&nbsp;</td>' +
											 '<td>&nbsp;</td>' +
											 '<td>&nbsp;</td>' +
											 '<td>&nbsp;</td>' +
											 '<td>Total Pendapatan</td>'+
											 '<td>'+val.totalGaji+'</td>'+
											'</tr>'
					 });

					 $("#listproduksi1").find('tbody').html(tbody);
					 $('#btnExcel1').focus();
				 } else {

		 }
	 });

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

$("#btnExcel1").click(function(){
	$("#listproduksi1").table2excel({
		exclude: ".noExl",
		name: "Detail Penggajian",
		filename: "Detail Penggajian" //do not include extension

	});

});
