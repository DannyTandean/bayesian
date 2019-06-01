$(document).ready(function() {

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblAbsensi").DataTable({
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
			url: base_url+'/approvalowner/absensi/ajax_list',
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
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'token' },
			{ data:'payment' },
			{ data:'nama_jadwal' },
			{ data:'masuk' },
			{ data:'break_out' },
			{ data:'break_in' },
			{ data:'keluar' },
			{ data:'idfp' },
			{ data:'jabatan' },
		],
	});
});

function reloadTable() {
	$("#tblAbsensi").DataTable().ajax.reload(null,false);
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

function btnApprove(id,idTmp) {
	idData = id;
	$.post(base_url+"approvalowner/absensi/getId/"+idTmp,function(json) {
		$("#status").val("Diterima");
		if (json.status == true) {
			if(json.data.temp_status == "Proses") {
				var pesan = "<hr>";
					pesan += "<div class='row'>";
					pesan += "<div class='col-md-4'>";
					pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
					pesan += "</div>";
					pesan += "<div class='col-md-8'>";
					pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jadwal : <i>"+json.data.nama_jadwal+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Masuk : <i>"+json.data.masuk+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Keluar : <i>"+json.data.keluar+"</i></small></li><br>";
					pesan += "</div>";
					pesan += "</div>";

			    swal({
			        title: "Apakah anda yakin.?",
			         html: "<span style='color:green;'>Data yang sudah <b>Diterima</b> tidak bisa di edit/update lagi.</span>"+pesan,
			         type: "question",
					     width: 400,
	  				   showCloseButton: true,
			         showCancelButton: true,
			         confirmButtonColor: "#009900",
			         confirmButtonText: "Iya, Terima",
			         closeOnConfirm: false,

			     }).then((result) => {
					if (result.value) {
            var splturl = base_url.split('/')
						var saving = splturl[splturl.length-2]
            console.log(saving);
						$.ajax({
							url: "https://primahrd.com:468/port",
							type: 'POST',
							data : {id : saving},
							dataType: 'JSON',
							headers: {
									 'Access-Control-Allow-Origin': '*',
									 'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
									 'Access-Control-Allow-Headers': 'Content-Type'
							 },
							 success: function(port){
								 if (port.status == true) {
                       $.ajax({
                        url: "https://primahrd.com:"+(parseInt(port.data[0].port)+80)+"/approvalabsensi/delete",
                        type:'PUT',
                        data:{id_absensi : idData, status : "Diterima"},
                        headers: {
                             'Access-Control-Allow-Origin': '*',
                             'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
                             'Access-Control-Allow-Headers': 'Content-Type',
                             'Authorization' : "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1hIjoiVGVzMSIsImphYmF0YW4iOiJBZG1pbiBLYW50b3IiLCJpYXQiOjE1MzU5NDIxODV9.tNDAblikreV6R1VUPGvTNCqgoTPEbCRTUyR0987Hku4"
                         },
                        dataType:'JSON',
                        success: function(json) {
                      if (json.status == true) {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 1500
                        });
                        Toast.fire({
                          type: 'success',
                          title: "<span style='color:green'>"+json.message + "</span>"
                        })
                        reloadTable();
                      } else {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 1500
                        });
                        Toast.fire({
                          type: 'error',
                          title: "<span style='color:red'>"+json.message + "</span>"
                        })
                        reloadTable();
                      }
                    }
                  });
                 }
                 else {
                   const Toast = Swal.mixin({
                     toast: true,
                     position: 'top-end',
                     showConfirmButton: false,
                     timer: 1500
                   });
                   Toast.fire({
                     type: 'error',
                     title: "<span style='color:red'>"+port.message + "</span>"
                   })
                 }
              }
            });// end ajax por
			}
		});
  } else if (json.data.temp_status == "Diterima") {
				swal({
			            title:  "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa Diterima lagi.!</h2>",
						type: "warning",
			            timer: 3000,
			            showConfirmButton: false

			        });
			} else if (json.data.temp_status == "Ditolak") {
				swal({
			            title:  "<h2 style='color:orange;'>Data yang sudah <u style='color:red;'>Ditolak</u> tidak bisa Diterima lagi.!</h2>",
						type: "warning",
			            timer: 3000,
			            showConfirmButton: false

			        });
			}
		} else {
			reloadTable();
			swal({
		            title: json.message,
		            type: "error",
		            timer: 1000,
		            showConfirmButton: false
		        });
		}
	});
}

function btnReject(id,idTmp) {
	idData = id;
	$.post(base_url+"approvalowner/absensi/getId/"+idTmp,function(json) {
		if (json.status == true) {
			$("#status").val("Ditolak");
			if(json.data.temp_status == "Proses") {
				var pesan = "<hr>";
					pesan += "<div class='row'>";
					pesan += "<div class='col-md-4'>";
					pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
					pesan += "</div>";
					pesan += "<div class='col-md-8'>";
          pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Jadwal : <i>"+json.data.nama_jadwal+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Masuk : <i>"+json.data.masuk+"</i></small></li><br>";
					pesan += "<li class='pull-left'><small>Keluar : <i>"+json.data.keluar+"</i></small></li><br>";
					pesan += "</div>";
					pesan += "</div>";

			    swal({
			        title: "Apakah anda yakin.?",
			        html: "<span style='color:red;'>Data yang sudah <b>Ditolak</b> tidak bisa di edit/update lagi.</span>"+pesan,
			        type: "question",
					width: 400,
	  				showCloseButton: true,
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "Iya, Tolak",
			        closeOnConfirm: false,

			    }).then((result) => {
		    	if (result.value) {
            var splturl = base_url.split('/')
						var saving = splturl[splturl.length-2]
						$.ajax({
							url: "https://primahrd.com:468/port",
							type: 'POST',
							data : {id : saving},
							dataType: 'JSON',
							headers: {
									 'Access-Control-Allow-Origin': '*',
									 'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
									 'Access-Control-Allow-Headers': 'Content-Type'
							 },
							 success: function(port){
								 if (port.status == true) {
                       $.ajax({
                        url: "https://primahrd.com:"+(parseInt(port.data[0].port)+80)+"/approvalabsensi/delete",
                        type:'PUT',
                        data:{id_absensi : idData, status : "Ditolak"},
                        headers: {
                             'Access-Control-Allow-Origin': '*',
                             'Access-Control-Allow-Methods': 'POST, GET, PUT, DELETE, OPTIONS',
                             'Access-Control-Allow-Headers': 'Content-Type',
                             'Authorization' : "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1hIjoiVGVzMSIsImphYmF0YW4iOiJBZG1pbiBLYW50b3IiLCJpYXQiOjE1MzU5NDIxODV9.tNDAblikreV6R1VUPGvTNCqgoTPEbCRTUyR0987Hku4"
                         },
                        dataType:'JSON',
                        success: function(json) {
                      if (json.status == true) {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 1500
                        });
                        Toast.fire({
                          type: 'success',
                          title: "<span style='color:green'>"+json.message + "</span>"
                        })
                        reloadTable();
                      } else {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 1500
                        });
                        Toast.fire({
                          type: 'error',
                          title: "<span style='color:red'>"+json.message + "</span>"
                        })
                        reloadTable();
                      }
                    }
                  });
                 }
                 else {
                   const Toast = Swal.mixin({
                     toast: true,
                     position: 'top-end',
                     showConfirmButton: false,
                     timer: 1500
                   });
                   Toast.fire({
                     type: 'error',
                     title: "<span style='color:red'>"+port.message + "</span>"
                   })
                 }
              }
            });// end ajax por
			}
    });
  } else if (json.data.temp_status == "Diterima") {
				swal({
			            title: "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa Ditolak lagi.!</h2>",
						type: "warning",
			            timer: 3000,
			            showConfirmButton: false

			        });
			} else if (json.data.temp_status == "Ditolak") {
				swal({
			            title:  "<h2 style='color:orange;'>Data yang sudah <u style='color:red;'>Ditolak</u> tidak bisa Ditolak lagi.!</h2>",
						type: "warning",
			            timer: 3000,
			            showConfirmButton: false

			        });
			}
		} else {
			reloadTable();
			swal({
		            title: json.message,
		            type: "error",
		            timer: 1000,
		            showConfirmButton: false
		        });
		}
	});
}
