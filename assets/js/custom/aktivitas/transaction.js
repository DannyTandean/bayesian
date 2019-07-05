$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblTransaksi").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data  "+btnRefresh,
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
		//load data
		ajax: {
			url: base_url+'aktivitas/transaction/ajax_list',
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
			{ data:'transaction_amount' },
			{ data:'payment_amount' },
			{ data:'payment_card' },
		],
		dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
	});
});

function reloadTable() {
	$("#tblTransaksi").DataTable().ajax.reload(null,false);
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

function btnPayment(id) {
	idData = id;

	// $(".modal-title").text("Detail Transaksi");

	$.post(base_url+"aktivitas/transaction/getPaymentId/"+idData,function(json) {
		if (json.status == true) {
			var pesan = "<hr>";
				pesan += "<li class='pull-left'><small>Jumlah Transaksi : <i>"+moneyFormat.to(parseInt(json.data.transaction_amount))+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Jumlah Pembayaran : <i>"+moneyFormat.to(parseInt(json.data.payment_amount))+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Sisa Transaksi : <i>"+moneyFormat.to(parseInt(json.data.transaction_amount) - parseInt(json.data.payment_amount))+"</i></small></li><br>";

				swal({
						title: "Cek Pembayaran",
						html: pesan,
						type: "info",
						width: 400,
						showConfirmButton : false,
				})

		} else {
			$("#inputMessage").html(json.message);
		}
	});

}

function btnDetail(id) {
	idData = id;

	$.post(base_url+"aktivitas/transaction/getCartId/"+idData,function(json) {
		if (json.status == true) {
			var pesan = "<hr>";
			pesan += "<div class='row'>";
			pesan += "<div class='col-md-4'>";
			pesan += '<img class="img-fluid img-circle" src="'+json.data.image+'" alt="user-img" style="height: 100px; width: 100px;">';
			pesan += "</div>";
			pesan += "<div class='col-md-8'>";
			pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>No Telepon : <i>"+json.data.no_telp+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>Nama Produk : <i>"+json.data.product_name+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>Jumlah : <i>"+json.data.qty+"</i></small></li><br>";
			pesan += "<li class='pull-left'><small>Harga : <i>"+moneyFormat.to(parseInt(json.data.total))+"</i></small></li><br>";
				swal({
						title: "Detail Transaksi",
						html: pesan,
						type: "info",
						width: 400,
						showConfirmButton : false,
				})

		} else {
			$("#inputMessage").html(json.message);
		}
	});

}

function btnPembeli(id) {
	idData = id;

	$.post(base_url+"aktivitas/transaction/getPembeliId/"+idData,function(json) {
		if (json.status == true) {
			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.image+'" alt="user-img" style="height: 100px; width: 100px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Jenis Kelamin : <i>"+json.data.jenis_kelamin+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Email : <i>"+json.data.email+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>No Telepon : <i>"+json.data.no_telp+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Transaction Limit : <i>"+moneyFormat.to(parseInt(json.data.transaction_limit))+"</i></small></li><br>";

		    swal({
		        title: "Detail pembeli.",
		        html: pesan,
		        type: "info",
						width: 400,
	  				showConfirmButton : false,
		    });
			}
	});
}

function btnLokasi(id) {
	idData = id
	$.post(base_url+"aktivitas/transaction/getPembeliId/"+idData,function(json) {
		if (json.status == true) {
			$.get('https://api.ipgeolocation.io/ipgeo?apiKey=4603b07d604e4ef6a1caeb476b83c4d5&ip='+json.data.ip_transaction,function(data) {
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
							pesan += "<li class='pull-left'><small>Ip Address : <i>"+json.data.ip_transaction+"</i></small></li><br>";
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
