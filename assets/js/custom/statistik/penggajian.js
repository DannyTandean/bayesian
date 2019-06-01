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
			url: base_url+'statistik/penggajian/ajax_list',
			type: 'POST',
		},

		order:[[1,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'idfp' },
			{ data:'nama' },
			{ data:'jabatan' },
			{ data:'departemen' },
			{ data:'cabang' },
			{ data:'tanggal_proses' },
			{ data:'hari_kerja' },
			{ data:'nilai_bayar' },
			{ data:'sisa'},
			{ data:'potongan_bpjs'},
			{ data:'potongan_telat'},
			{ data:'potongan_telat'},
			{ data:'potongan_pinjaman' },
			{ data:'penggajian_transport' },
			{ data:'penggajian_makan' },
			{ data:'penggajian_tunlain' },
			{ data:'penggajian_thr' },
			{ data:'reward' },
			{ data:'punishment' },
			{ data:'penggajian_libur' },
			{ data:'penggajian_kerajinan' },
			{ data:'gaji_total_tambahan' },
			{ data:'gaji_extra' },
			{ data:'gaji_bersih' },
			{ data:'keterangan' },

		],

		dom : "<'row' <'col-md-4'l> <'col-md-4'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'excel', 'pdf', 'colvis'
    ],
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
	var url;
	url = base_url+'statistik/penggajian/ajax_list_after/'+String($("#before").val())+"/"+String($("#after").val());
	// url = base_url+'statistik/cuti/ajax_list';

	var table = $('#tblPenggajian').DataTable();

	table.ajax.url(url).load();

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
													'<td>'+val.pulang_cepat+'</td>'+
													'<td>'+val.kerja+'</td>'+
													'<td>'+val.token+'</td>'+
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
				$("#harikerja").html(" : "+json.data1.hari_kerja +" Hari");
				$("#notrans").html(" : "+json.data1.kode_payroll);
				$("#reward").html(" : " + moneyFormat.to(parseInt(json.data1.reward)));
				$("#punishment").html(" : " + moneyFormat.to(parseInt(json.data1.punishment)));
				$("#totalGaji").html(" : " + json.data4.totalGaji);
				$("#totalBersih").html(" : " + json.data4.gajiBersih);
				$("#totalPotongan").html(" : " + json.data4.totalPotongan);
				//dataGolongan
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
