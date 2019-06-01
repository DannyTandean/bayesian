$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblSakit").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data"+btnRefresh,
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
		//load data
		ajax: {
			url: base_url+'statistik/sakit/ajax_list',
			type: 'POST',
		},

		order:[[1,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'departemen' },
			{ data:'jabatan' },
			{ data:'tgl_sakit' },
			{ data:'akhir_sakit' },
			{ data:'keterangans' },
			{ data:'lama' },
		],
		// lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
		
		dom : "<'row' <'col-md-4'l> <'col-md-4'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'excel', 'pdf', 'colvis'
    ],


	});

});

function reloadTable() {
	$("#tblSakit").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

//Select2 Karyawan

$("#btnDetailZoomImg").click(function() {
	$("#detailPopUpPhotoOut").click();
});

$(document).on('click', '#btnRefresh', function(e) {
	e.preventDefault();
	reloadTable();
	$("#btnRefresh").children().addClass("fa-spin");
	setTimeout(function(){
	  $("#btnRefresh").children().removeClass("fa-spin");
	}, 1000);
});

//validasi tanggal
$("#before, #after").change(function() {
	if($("#before").val() != "" && $("#after").val() != ""){
		var startDate = new Date($('#before').val());
		var expiredDate = new Date($('#after').val());
		if (startDate > expiredDate){
			swal({
		            title: "Information Notice!",
		            html: "<span style='color:orange'>Tanggal <b>Pertama</b> harus Sama atau lebih kecil dari tanggal <b>Kedua.</b</span>",
		            type: "warning",
		    //         background : '100%',
		    //         backdrop: `
				  //   rgba(0,0,123,0.4)
				  //   url("https://media0.giphy.com/media/AsNxyHoYDcCXe/giphy.gif")
				  //   center
				  // `
		        });
			$("#after").val("");
		}
	}
});


$("#searchDate").click(function() {
	var url;
	url = base_url+'statistik/sakit/ajax_list_after/'+String($("#before").val())+"/"+String($("#after").val());
	// url = base_url+'statistik/cuti/ajax_list';

	var table = $('#tblSakit').DataTable();

	table.ajax.url(url).load();

});
