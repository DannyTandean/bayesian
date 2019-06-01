$(document).ready(function() {
	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblProduksi").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		"lengthMenu": [ [100000], ["All"] ],
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
			url: base_url+'pekerjaborongan/LaporanProduksi/ajax_list',
			type: 'POST',
		},

		order:[[1,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'bulan' },
			{ data:'kode' },
			{ data:'departemen' },
			{ data:'totaljumlah' },
			{ data:'harga' },
			{ data:'totalpendapatan',
				render : $.fn.dataTable.render.number('.', ',', 0, 'Rp')
			},
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
		"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

						totalItem = api
							.column( 4, { page: 'current'} )
							.data()
							.reduce( function (a, b) {
									return intVal(a) + intVal(b);
							}, 0 );

            // Update footer
            $( api.column( 6 ).footer() ).html(
                moneyFormat.to(parseInt(pageTotal))
            );
						$( api.column( 4 ).footer() ).html(
                totalItem
            );
        },
		  buttons: [
							{ extend: 'excel',
								footer: true,
								orientation: 'portrait',
								pageSize: 'LEGAL',
							}
							,'colvis'
		  ],
	});
});

function reloadTable() {
	$("#tblProduksi").DataTable().ajax.reload(null,false);
}

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

$("#Process").click(function() {
	var url;
	url = base_url+'pekerjaborongan/LaporanProduksi/ajax_list_after/'+String($("#before").val())+"/"+String($("#after").val());
	// url = base_url+'statistik/cuti/ajax_list';

	var table = $('#tblProduksi').DataTable();

	table.ajax.url(url).load();

});
