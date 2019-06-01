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
			url: base_url+'aktivitas/penggajian_payment/ajax_list',
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
			{ data: 'nama'},
			{ data: 'idfp'},
			{ data: 'jabatan'},
			{ data: 'departemen'},
			{ data: 'cabang'},
			// { data: 'keterangan'},
		],
		// dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    // buttons: [
    //     'colvis'
    // ],
	});

	$("#tblLogPenggajian").DataTable({
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
			url: base_url+'aktivitas/penggajian_payment/ajax_list_log',
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
			{ data: 'tanggal_proses'},
			{ data: 'nama'},
			{ data: 'idfp'},
			{ data: 'jabatan'},
			{ data: 'departemen'},
			{ data: 'cabang'},
			{ data: 'hari_kerja'},
			{ data: 'nilai_bayar'},
			{ data: 'sisa'},
		],
		// dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    // buttons: [
    //     'colvis'
    // ],
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

//Select2 Karyawan

$(document).ready(function(){

	$("#nama_karyawan").select2({
		placeholder: ' -- Pilih Karyawan --',
		ajax: {
		   	url: base_url+"aktivitas/penggajian_payment/allKaryawanAjax",
		   	type: "post",
		   	dataType: 'json',
		   	delay: 250,
		   	data: function (params) {
		    	return {
		      		searchTerm: params.term // search term
		    	};
		   	},
		   	processResults: function (response) {
		     	return {
		        	results: response
		     	};
		   	},
		   	cache: true
		},
	});

	var url = base_url+'aktivitas/penggajian_payment/last_payment/1-Mingguan';

	$.ajax({
		url: url,
		type:'POST',
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				$('#last_pay').html(json.data);
			}
			else {
				$('#last_pay').html(json.message)
			}
		}
	});

});

function reloadTable() {
	$("#tblPenggajian").DataTable().ajax.reload(null,false);
	$("#tblLogPenggajian").DataTable().ajax.reload(null,false);
	RekapPenggajian();
}

$('#ProcessRekap').click(function() {
	event.preventDefault();
	RekapPenggajian();
});

function RekapPenggajian() {
	url = base_url+'aktivitas/penggajian_payment/getRekapPenggajian/'+$('#beforeRekap').val()+"/"+$('#afterRekap').val()+"/"+$('#periodeRekap').val()+"/"+$('#ikutPotongBpjsRekap').val()+"/"+$('#ikutTunjanganRekap').val()+"/"+$('#rekapWaktu').val();

	$.ajax({
		url: url,
		type:'POST',
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				var tbody = "";
				var total = 0
					$.each(json.data,function(key, val) {
						tbody += '<tr>'+'<td>'+(key+1)+'</td>'+
														'<td>'+val.nama+'</td>'+
														'<td>'+val.hari_kerja_karyawan+'</td>'+
														'<td>'+ moneyFormat.to(parseInt(val.penggajian_tunjangan))+'</td>'+
														'<td>'+ moneyFormat.to(parseInt(val.penggajian_transport))+'</td>'+
														'<td>'+ moneyFormat.to(parseInt(val.penggajian_makan))+'</td>'+
														'<td>'+ moneyFormat.to(parseInt(val.penggajian_tunlain))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.penggajian_thr))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.reward))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.penggajian_libur))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.penggajian_kerajinan))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.potongan_bpjs))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.potongan_telat))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.punishment))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.potongan_pinjaman))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.pph))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.gaji_total_tambahan))+'</td>'+
														'<td>'+moneyFormat.to(parseInt(val.gaji_bersih))+'</td>'+
										'</tr>';
							total += val.gaji_bersih;
					});
					tbody += '<tr>'+'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td></td>'+
													'<td>Total Gaji Berish</td>'+
												  '<td>'+moneyFormat.to(parseInt(total))+'</td>'
					$("#tblrekappenggajian").find('tbody').html(tbody);
			}
		}
	})
}

function last_penggajian(url) {
	$.ajax({
		url: url,
		type:'POST',
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				$('#last_pay').html(json.data);
			}
			else {
				$('#last_pay').html(json.message)
			}
		}
	});
}

$('#periode').change(function(event) {
		var url;
		url = base_url+'aktivitas/penggajian_payment/last_payment/'+String($(this).val());
		last_penggajian(url);
});

$("#ProcessHistory").click(function() {
	var url;
	url = base_url+'aktivitas/penggajian_payment/ajax_list_log_after/'+String($("#beforeHistory").val())+"/"+String($("#afterHistory").val());
	// url = base_url+'statistik/cuti/ajax_list';

	var table = $('#tblLogPenggajian').DataTable();

	table.ajax.url(url).load();

});

$("#btnExcel3").click(function(){
	$("#tblrekappenggajian").table2excel({
		exclude: ".noExl",
		name: "Rekap Penggajian",
		filename: "Rekap Penggajian" //do not include extension

	});

});

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

$('#historypenggajian').hide();
$('#rekappenggajian').hide();

$('#navpenggajian').click(function() {
		$('#tabpenggajian').show();
		$('#historypenggajian').hide();
		$('#rekappenggajian').hide();
});

$('#navrekappenggajian').click(function() {
		$('#rekappenggajian').show();
		$('#tabpenggajian').hide();
		$('#historypenggajian').hide();
});

$('#navlogpenggajian').click(function() {
	$('#tabpenggajian').hide();
	$('#historypenggajian').show();
	$('#rekappenggajian').hide();
});

$("#Process").click(function() {
	var url;
	url = base_url+'aktivitas/penggajian_payment/generate_penggajian';

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

$("#multiSelect").click(function() {
	var url;
	url = base_url+'aktivitas/penggajian_payment/multipelKaryawan';

	$.ajax({
		url: url,
		type:'POST',
		data:$("#formDataSearchKaryawan").serialize(),
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

function btnSlip(id)
{
	$("#Print").modal("show");
	$(".modal-title").text("Print");
	//get data absensi
	$.post(base_url+"aktivitas/penggajian_payment/getbyidPenggajian/"+id,function(json) {
		if (json.status == true) {
			var tbody = "";
				$.each(json.dataAbsensi,function(key, val) {
					tbody += '<tr>'+'<td>'+(key+1)+'</td>'+
													'<td>'+val.tanggal+'</td>'+
													'<td>'+val.masuk+'</td>'+
													'<td>'+val.keluar+'</td>'+
													'<td>'+(val.telat > 0 ? val.telat : 0)+'</td>'+
													'<td>'+val.nama_jadwal+'</td>'+
													'<td>'+val.kerja+'</td>'+
													'<td>'+(val.token - val.telat)+'</td>'+
									'</tr>'
				});
				$("#tableDataAbsensi").find('tbody').html(tbody);
				$("#perusahaan").html(json.data2);
				$("#idfp").html(" : " + json.data.idfp +" / "+json.data.nama);
				$("#periode1").empty().html(" : " + json.data.periode_gaji +" / "+json.data.tgl_masuk);
				$("#departemen").html(" : " + json.data.departemen +" / "+json.data.jabatan);
				$("#golongan").html(" : "+json.data.golongan);
				$("#cabang").html(" : "+json.data.cabang);
				$("#rekening").html(" : "+json.data.rekening);
				$("#bank").html(" : "+json.data.bank);
				$("#periode2").html(" : "+json.data.start_date +" s/d " + json.data.end_date);
				$("#harikerja").html(" : "+json.data.hari_kerja_karyawan+"/"+json.data.hari_kerja +" Hari");
				$("#notrans").html(" : "+ "-");
				$("#reward").html(" : " + moneyFormat.to(parseInt(json.data.reward)));
				$("#punishment").html(" : " + moneyFormat.to(parseInt(json.data.punishment)));
				$("#totalGaji").html(" : " + moneyFormat.to(parseInt(json.data.gaji_total_tambahan)));
				$("#totalBersih").html(" : " + moneyFormat.to(parseInt(json.data.gaji_bersih)-parseInt(json.data.pph_bulan_ini)));
				$("#totalPotongan").html(" : " + moneyFormat.to(parseInt(json.data.total_potongan)));
				$("#gajiHariLibur").html(" : " + moneyFormat.to(parseInt(json.data.penggajian_libur)));
				$("#kerajinan").html(" : " + moneyFormat.to(parseInt(json.data.penggajian_kerajinan)));
				//dataGolongan
				$("#totalTelat").html(" : " + json.data.total_telat + " Menit");
				$("#gajiPokok").html(" : " + moneyFormat.to(parseInt(json.data.gaji)));
				$("#terlambat").html(" : " + moneyFormat.to(parseInt(json.data.potongan_telat)));
				$("#transport").html(" : " + moneyFormat.to(parseInt(json.data.penggajian_transport)));
				// $("#potonganAbsen").html(" : " + json.data3.transport);
				$("#extra").html(" : " +moneyFormat.to(parseInt(json.data.gaji_extra)));
				$("#bpjs").html(" : " + moneyFormat.to(parseInt(json.data.potongan_bpjs)));
				$("#pinjaman").html(" : " + moneyFormat.to(parseInt(json.data.potongan_pinjaman)));
				$("#makan").html(" : " + moneyFormat.to(parseInt(json.data.penggajian_makan)));
				$("#tunjanganJabatan").html(" : " + moneyFormat.to(parseInt(json.data.penggajian_tunjangan)));
				$("#thr").html(" : " + moneyFormat.to(parseInt(json.data.penggajian_thr)));
				$("#potLain").html(" : " + moneyFormat.to(parseInt(json.data.pot_lain)));
				$("#tunjanganLain").html(" : " + moneyFormat.to(parseInt(json.data.penggajian_tunlain)));
				$("#pph").html(" : " + moneyFormat.to(parseInt(json.data.pph_bulan_ini)));
		} else {

		}
	});
}

function btnPay(id)
{
	$("#pay").modal("show");
	$(".modal-title").text("payment");
	$('#message').html("");
	//get data absensi
	$('#pengambilan').val(0);
	$('#sisa').val(0);
	$.post(base_url+"aktivitas/penggajian_payment/getId/"+id,function(json) {
		if (json.status == true) {
			$('#gaji_absensi').val(Math.round(json.dataPenggajian.gaji_total));
			$('#makan1').val(json.dataPenggajian.penggajian_makan);
			$('#transport1').val(json.dataPenggajian.penggajian_transport);
			$('#extra1').val(json.dataPenggajian.gaji_extra);
			$('#reward1').val(json.dataPenggajian.reward);
			$('#thr1').val(json.dataPenggajian.penggajian_thr)
			$('#tunjangan1').val(json.dataPenggajian.penggajian_tunjangan);
			$('#tun_lain1').val(json.dataPenggajian.penggajian_tunlain);
			$('#bpjs1').val(json.dataPenggajian.potongan_bpjs);
			$('#telat1').val(json.dataPenggajian.potongan_telat);
			$('#pinjaman1').val(json.dataPenggajian.potongan_pinjaman);
			$('#punishment1').val(json.dataPenggajian.punishment);
			$('#pph1').val(json.dataPenggajian.pph);
			$('#penerimaan').val(Math.round(json.dataPenggajian.gaji_bersih - json.dataPenggajian.pph));
			$('#start_date').val(json.dataPenggajian.start_date);
			$('#end_date').val(json.dataPenggajian.end_date);
			$('#hari_kerja').val(json.dataPenggajian.hari_kerja);
			$('#id_karyawan').val(json.dataPenggajian.id_karyawan);
			$('#payment_sisa_sebelumnya').val(json.dataPenggajian.payment_sisa_sebelumnya == null ? 0 : json.dataPenggajian.payment_sisa_sebelumnya);
			$('#hariKerjaKaryawan').val(json.dataPenggajian.hari_kerja_karyawan);
			$('#pengambilan').val(Math.round(json.dataPenggajian.gaji_bersih - json.dataPenggajian.pph));
			$('#gajiTotalTambahan').val(json.dataPenggajian.gaji_total_tambahan);
			$('#penggajian_kerajinan').val(json.dataPenggajian.penggajian_kerajinan);
			$('#pengambilan').on('input',function(event) {
				event.preventDefault();
				$('#sisa').val(Math.round($('#penerimaan').val()) - $(this).val());
			});

			$('#payment').prop('disabled',false);
		} else {
			$('#message').html(json.message);
			$('#gaji_absensi').val(0);
			$('#makan1').val(0);
			$('#transport1').val(0);
			$('#extra1').val(0);
			$('#reward1').val(0);
			$('#thr1').val(0);
			$('#tunjangan1').val(0);
			$('#tun_lain1').val(0);
			$('#bpjs1').val(0);
			$('#telat1').val(0);
			$('#pinjaman1').val(0);
			$('#punishment1').val(0);
			$('#pph1').val(0);
			$('#penerimaan').val(0);
			$('#start_date').val(0);
			$('#end_date').val(0);
			$('#hari_kerja').val(0);
			$('#id_karyawan').val(0);
			$('#payment_sisa_sebelumnya').val(0);
			$('#hariKerjaKaryawan').val(0);
			$('#gajiTotalTambahan').val(0);
			$('#kerajinan').val(0);
			$('#payment').prop('disabled',true);
		}
	});
}

$('#payment').click(function() {
	var url;
	url = base_url+'aktivitas/penggajian_payment/add';

	$.ajax({
		url: url,
		type:'POST',
		data:$("#paymentData").serialize(),
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
				setTimeout(function() {
					reloadTable();
					$("#pay").modal("hide");
				},1000);
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
			}
		}
	});
});
