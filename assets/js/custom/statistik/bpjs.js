$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblBpjs").DataTable({
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
			url: base_url+'statistik/bpjs/ajax_list',
			type: 'POST',
		},

		order:[[1,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'nama' },
			{ data:'grup' },
			{ data:'jabatan' },
			{ data:'departemen' },
			{ data:'cabang' },
			{ data:'totalBpjs',
				render : $.fn.dataTable.render.number('.', ',', 0, 'Rp')
		  },
		],
		// dom: 'Blftip',
		dom : "<'row' <'col-md-4'l> <'col-md-4'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
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

            // Update footer
            $( api.column( 6 ).footer() ).html(
                moneyFormat.to(parseInt(pageTotal))
            );
        },
				buttons: [
		            { extend: 'excel',
									footer: true,
									orientation: 'portrait',
	        				pageSize: 'LEGAL',
								},
		            { extend: 'pdfHtml5', footer: true },
		        		'colvis'
		    ],

	});
});

function reloadTable() {
	$("#tblBpjs").DataTable().ajax.reload(null,false);
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
		            html: "<span style='color:orange'>tanggal <b>Pertama</b> harus Sama atau lebih kecil dari tanggal <b>Kedua</b></span>",
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
	url = base_url+'statistik/bpjs/ajax_list_after/'+$('#periode').val()+"/"+String($("#before").val())+"/"+String($("#after").val());
	// url = base_url+'statistik/bpjs/ajax_list';

	var table = $('#tblBpjs').DataTable();

	table.ajax.url(url).load();

});
