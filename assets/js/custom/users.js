$(document).ready(function() {

	btnTambah = "";
	if (user_level != "hrd") {
		btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";
	}
   	
    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblUsers").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
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
			url: base_url+'users/ajax_list',
			type: 'POST',
		},

		order:[[2,'ASC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{
				data:'photo',
				searchable:false,
				orderable:false,
			},
			{ data:'username' },
			{ data:'level' },
			{ data:'nama_pengguna' },
			// { data:'keterangan' },
			{
				data:'button_action',
				searchable:false,
				orderable:false,
			}
		],
	});
});

function reloadTable() {
	$("#tblUsers").DataTable().ajax.reload(null,false);
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
 

$(document).on('click', '#btnTambah', function (e) {
	e.preventDefault();
	$("#formData")[0].reset();
	$("#titleForm").html("<i class='fa fa-plus'></i> Tambah Pengguna");
	$("#dataTable").hide(800);
	$("#formProses").show(800);

	$("#username").attr("readonly",false);
	srcPhoto = "assets/images/default/no_user.png";
	$('#img_photo').attr('src',srcPhoto);
   	$('#popUpImgOut').attr('href',srcPhoto);
   	$('#popUpImgIn').attr('src',srcPhoto);

	save_method = "add";
});

$(".close-form").click(function() {
	$("#dataTable").show(800);
	$("#formProses").hide(800);
});

function btnEdit(id) {
	idData = id;
	$("#titleForm").html("<i class='fa fa-pencil-square-o'></i> Update Pengguna");
	$("#dataTable").hide(800);
	$("#formProses").show(800);
	save_method = "update";

	$.post(base_url+"users/getId/"+idData,function(json) {
		if (json.status == true) {
			$("#nama").val(json.data.nama_pengguna);
			$("#username").val(json.data.username);
			$("#username").attr("readonly",true);
			if(json.data.level == "admin"){
				$("#level").html('<option value="admin">Admin</option>');
			} else {
				var option = '';
				option += '<option value="hrd">HRD</option>';
				option += '<option value="owner">Owner</option>';
				$("#level").html(option);

			}
			if (user_level == "hrd") {
				$("#formLevel").hide();
			} else {
				$("#formLevel").show();
			}
			$("#level").val(json.data.level);

			$('#img_photo').attr('src',json.data.photo);
		   	$('#popUpImgOut').attr('href',json.data.photo);
		   	$('#popUpImgIn').attr('src',json.data.photo);
		} else {
			swal({   
		            title: "Error Form.!",   
		            html: json.message,
		            type: "error",
		        });

			setTimeout(function() {
				reloadTable();
				$("#dataTable").show(800);
				$("#formProses").hide(800);
			},1000);
		}
	});

}

$("#btnSimpan").click(function() {
	var url;
	if (save_method == "add") {
		url = base_url+'users/add';
	} else {
		url = base_url+'users/update/'+idData;
	}

	var formData = new FormData($("#formData")[0]);
	$.ajax({
		url: url,
		type:'POST',
		data:formData,
		contentType:false,
		processData:false,
		dataType:'JSON',
		success: function(json) {
			if (json.status == true) {
				swal({    
			            title: json.message,
			            type: "success",
			            timer: 2000,   
			            showConfirmButton: false 
			        });

				setTimeout(function() {
					$("#formData")[0].reset();
					$("#dataTable").show(800);
					$("#formProses").hide(800);
					$("#inputMessage").html("");
					reloadTable();
                    // photo user di menu head
                    profilePhotoUserMenuHead();
				}, 1500);
			} else {

				if (json.error.photo) {	
					errorMessage = json.error.photo;
				} else {
					errorMessage = json.message;
				}

				swal({   
			            title: "Error Form.!",   
			            html: errorMessage,
			            type: "error",
			        });
			}
		}
	});
});

function btnDelete(id) {
	idData = id;

	$.post(base_url+"users/getId/"+idData,function(json) {
		if (json.status == true) {

			var pesan = "<hr>";
				pesan += "<div class='row'>";
				pesan += "<div class='col-md-4'>";
				pesan += '<img class="img-fluid img-circle" src="'+json.data.photo+'" alt="user-img" style="height: 60px; width: 60px;">';
				pesan += "</div>";
				pesan += "<div class='col-md-8'>";
				pesan += "<li class='pull-left'><small>Username : <i>"+json.data.username+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Level : <i>"+json.data.level+"</i></small></li><br>";
				pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama_pengguna+"</i></small></li><br>";
				pesan += "</div>";
				pesan += "</div>";

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

		    }).then((result) => {
		    	if (result.value) {
		    		$.post(base_url+"users/delete/"+idData,function(json) {
						if (json.status == true) {
							swal({    
						            title: json.message,
						            type: "success",
						            timer: 2000,   
						            showConfirmButton: false 
						        });
							reloadTable();
						} else {
							swal({    
						            title: json.message,
						            type: "error",
						            timer: 1500,   
						            showConfirmButton: false 
						        });
							reloadTable();
						}
					});
		    	}
		    });
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

/* prosessing photo change*/
$("#btnZoomImg").click(function() {
	$("#popUpImgOut").click();
});

$("#ganti_photo").click(function() {
	$("#photo_user").click();
});

$("#photo_user").change(function(event){
	readURL(document.getElementById('photo_user'));
	$('#is_delete').val(0);
});

$("#hapus_photo").click(function() {
   $('#img_photo').attr('src','assets/images/default/no_user.png');
   $('#popUpImgOut').attr('href','assets/images/default/no_user.png');
   $('#popUpImgIn').attr('src','assets/images/default/no_user.png');
   $("#photo_user").val("");
   $('#is_delete').val(1);	
});

function readURL(input)
{
   if (input.files && input.files[0])
   {
     var reader = new FileReader();
     reader.onload = function (e)
     {
       $('#img_photo').attr('src',e.target.result);
       $('#popUpImgIn').attr('src',e.target.result);
       $('#popUpImgOut').attr('href',e.target.result);
     };
     reader.readAsDataURL(input.files[0]);
   }
}
/* end photo change*/