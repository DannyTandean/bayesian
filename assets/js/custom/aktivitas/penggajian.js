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
			url: base_url+'aktivitas/penggajian/ajax_list',
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
			{ data: 'kode_payroll'},
			{ data:'idfp' },
			{ data:'nama' },
			{ data:'golongan' },
			{ data:'token' },
			{ data:'hari_kerja' },
			{ data:'gaji_extra' },
			{ data:'gaji' },
			{ data:'reward' },
			{ data:'punishment' },
			{ data:'potongan_telat' },
			{ data:'potongan_pinjaman' },
			{ data:'gaji_bersih' },
			{ data:'tanggal_proses' },

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


    $('#multiple').multiselect({
			includeSelectAllOption: true,
      enableFiltering: true
		});

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
	var url;
	url = base_url+'aktivitas/penggajian/generate_penggajian';

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
});

function btnPrint(id,kodepayroll)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");
	//get data absensi
	$.post(base_url+"aktivitas/penggajian/getSlipGajiKaryawan/"+id+"/"+kodepayroll,function(json) {
		if (json.status == true) {
			var tbody = "";
				$.each(json.data,function(key, val) {
					tbody += '<tr>'+'<td>'+(key+1)+'</td>'+
													'<td>'+val.tanggal+'</td>'+
													'<td>'+val.masuk+'</td>'+
													'<td>'+val.keluar+'</td>'+
													'<td>'+val.telat+'</td>'+
													'<td>'+val.nama_jadwal+'</td>'+
													'<td>'+val.kerja+'</td>'+
													'<td>'+val.token - val.telat+'</td>'+
									'</tr>'
				});
				$("#tableDataAbsensi").find('tbody').html(tbody);
				$("#perusahaan").html(json.data2);
				$("#idfp").html(" : " + json.data1.idfp +" / "+json.data1.nama);
				$("#periode1").empty().html(" : " + json.data1.periode_penggajian +" / "+json.data1.tgl_masuk);
				$("#departemen").html(" : " + json.data1.departemen +" / "+json.data1.jabatan);
				$("#golongan").html(" : "+json.data1.golongan);
				$("#cabang").html(" : "+json.data1.cabang);
				$("#rekening").html(" : "+json.data1.rekening);
				$("#bank").html(" : "+json.data1.bank);
				$("#periode2").html(" : "+json.data1.start_date +" s/d " + json.data1.end_date);
				$("#harikerja").html(" : "+json.data1.absensiCount+"/"+json.data1.hari_kerja +" Hari");
				$("#notrans").html(" : "+json.data1.kode_payroll);
				$("#reward").html(" : " + moneyFormat.to(parseInt(json.data1.reward)));
				$("#punishment").html(" : " + moneyFormat.to(parseInt(json.data1.punishment)));
				$("#totalGaji").html(" : " + json.data4.totalGaji);
				$("#totalBersih").html(" : " + json.data4.gajiBersih);
				$("#totalPotongan").html(" : " + json.data4.totalPotongan);
				//dataGolongan
				$("#totalTelat").html(" : " + json.data4.total_telat + " Menit");
				$("#gajiPokok").html(" : " + moneyFormat.to(parseInt(json.data3.umk)));
				$("#terlambat").html(" : " + json.data4.potongan_telat);
				$("#transport").html(" : " + moneyFormat.to(parseInt(json.data3.transport)));
				$("#potonganAbsen").html(" : " + json.data3.transport);
				$("#extra").html(" : " + json.data4.totalExtra);
				$("#bpjs").html(" : " + json.data3.hasilBpjs);
				$("#pinjaman").html(" : " + json.data4.potongan_pinjaman);
				$("#makan").html(" : " + json.data3.makan);
				$("#tunjanganJabatan").html(" : " + json.data3.totalTunjangan);
				$("#thr").html(" : " + moneyFormat.to(parseInt(json.data3.thr)));
				$("#potLain").html(" : " + moneyFormat.to(parseInt(json.data3.pot_lain)));
				$("#tunjanganLain").html(" : " + moneyFormat.to(parseInt(json.data3.tunjangan_lain)));
		} else {

		}
	});
}
