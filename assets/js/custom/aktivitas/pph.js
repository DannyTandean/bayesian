$(document).ready(function(){
  btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

  $("#tblPenggajian").DataTable({
    serverSide:true,
    responsive:true,
    // processing:true,
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
      url: base_url+'aktivitas/pph/ajax_list',
      type: 'POST',
    },

    order:[[2,'DESC']],
    columns:[
      {
        data:'no',
        searchable:false,
        orderable:false,
      },
      { data: 'nama'},
      { data:'status_nikah' },
      { data:'tanggungan' },
      { data:'status_kerja' },
      { data:'npwp' },
      {
        data:'btn_action',
        searchable:false,
        orderable:false,
      },
    ],
    dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
  });
});

function btnDetail(id) {
	idData = id;

	$("#Detail").modal("show");
	$(".modal-title").text("Detail Log Absensi");
	$("#dataTable").hide(800);
	$("#detailData").show(800);

	$.post(base_url+"aktivitas/LogAbsensi/getId/"+idData,function(json) {
		if (json.status == true) {
			if (json.data.telat <= 0) {
				json.data.telat = 0;
			}
			$("#tanggal3").html(json.data.tanggal);
			$("#kabag3").html(json.data.kabag);
			$("#nama3").html(json.data.nama);
			$("#jabatan3").html(json.data.jabatan);
			$("#shift3").html(json.data.nama_jadwal);
			$("#masuk3").html(json.data.masuk);
			$("#breakout3").html(json.data.break_out);
			$("#breakin3").html(json.data.break_in);
			$("#keluar3").html(json.data.keluar);
			$("#telat3").html(json.data.telat);
			$("#ketmasuk3").html(json.data.ket_masuk);
			$("#ketkeluar3").html(json.data.ket_keluar);
		} else {
			swal({
		            title: "Error Data.!",
		            html: json.message,
		            type: "error",
		            timer: 2000,
		        });

			setTimeout(function() {
				reloadTable();
				$("#dataTable").show(800);
				$("#detailData").hide(800);
			},1000);
		}
	});
}

$( "#Process" ).click(function() {
  	$.post(base_url+"aktivitas/pph/countAllData",function(json) {
      console.log(json);
    });
});
