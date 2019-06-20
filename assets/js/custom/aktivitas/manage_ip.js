$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblIp").DataTable({
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
			url: base_url+'aktivitas/manage_ip/ajax_list',
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
				data:'button_action',
				searchable:false,
				orderable:false,
			},
			{ data:'nama' },
			{ data:'email' },
			{ data:'ip_address' },
			{ data:'status' },
			{ data:'tipe' },
			{ data:'create_at' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
});

function reloadTable() {
	$("#tblIp").DataTable().ajax.reload(null,false);
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

function btnDetail(id) {
	idData = id;

	$(".modal-title").text("Detail");

	$.post(base_url+"aktivitas/manage_ip/getId/"+idData,function(json) {
		if (json.status == true) {
			$.get('https://api.ipgeolocation.io/ipgeo?apiKey=4603b07d604e4ef6a1caeb476b83c4d5&'+json.ip_address,function(data) {
				if (data) {
						var pesan = "<hr>";
							pesan += "<div class='row'>";
							pesan += "<div class='col-md-4'>";
							pesan += '<img class="img-fluid img-circle" src="'+json.data.image+'" alt="user-img" style="height: 100px; width: 100px;">';
							pesan += "</div>";
							pesan += "<div class='col-md-8'>";
							pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
							pesan += "<li class='pull-left'><small>Email : <i>"+json.data.email+"</i></small></li><br>";
							pesan += "<li class='pull-left'><small>No Telepon : <i>"+json.data.no_telp+"</i></small></li><br>";
							pesan += "<li class='pull-left'><small>Ip Address : <i>"+json.data.ip_address+"</i></small></li><br>";
							pesan += "<li class='pull-left'><small>Provinsi : <i>"+data.state_prov+"</i></small></li><br>";
							pesan += "<li class='pull-left'><small>Lokasi : <i>"+data.district+"</i></small></li><br>";

							swal({
									title: "Detail",
									html: pesan,
									type: "info",
									width: 400,
									showConfirmButton : false,
							})
				}
			});
		} else {
			$("#inputMessage").html(json.message);
		}
	});

}

function btnDelete(id) {
	idData = id;

	$.post(base_url+"aktivitas/manage_ip/getId/"+idData,function(json) {
		if (json.status == true) {
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.image+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Email : <i>"+json.data.email+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>No Telepon : <i>"+json.data.no_telp+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Ip Address : <i>"+json.data.ip_address+"</i></small></li><br>";

		    swal({
		        title: "Apakah anda yakin.?",
		        html: "<span style='color:red;'>Data yang di <b>Hapus</b> tidak bisa dikembalikan lagi.</span>"+pesan,
		        type: "warning",
						width: 400,
	  				showCloseButton: true,
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Iya, Hapus",
		        closeOnConfirm: false,
  				// background: '#e9e9e9',

		    }).then((result) => {
		    	if (result.value) {
		    		$.post(base_url+"aktivitas/manage_ip/delete/"+idData,function(json) {
						if (json.status == true) {
							swal({
						            title: json.message,
						            type: "success",
						            // html: true,
						            timer: 2000,
						            showConfirmButton: false
						        });
							reloadTable();
						} else {
							swal({
						            title: json.message,
						            type: "error",
						            // html: true,
						            timer: 1500,
						            showConfirmButton: false
						        });
							reloadTable();
						}
					});
		    	}
		    });
			}
	});
}
