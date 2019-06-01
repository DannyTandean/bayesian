$(document).ready(function() {

	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

	$("#tblHasilPenilaian").DataTable({
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
			url: base_url+'statistik/penilaian/ajax_list',
			type: 'POST',
		},

		order:[[7,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'grup' },
			{ data:'jabatan' },
			{ data:'departemen' },
			{ data:'cabang' },
			{ data:'hasil_nilai' },
		],
		// dom: 'Blftip',
		dom : "<'row' <'col-md-4'l> <'col-md-4'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'excel', 'pdf', 'colvis'
    ],


	});

	$("#tblHasilPenilaian1").DataTable({
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
			url: base_url+'statistik/penilaian/ajax_list',
			type: 'POST',
		},

		order:[[7,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'grup' },
			{ data:'jabatan' },
			{ data:'departemen' },
			{ data:'cabang' },
			{ data:'hasil_nilai' },
		],
		// dom: 'Blftip',
		dom : "<'row' <'col-md-4'l> <'col-md-4'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'excel', 'pdf', 'colvis'
    ],


	});

	$("#tblHasilPenilaian2").DataTable({
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
			url: base_url+'statistik/penilaian/ajax_list',
			type: 'POST',
		},

		order:[[7,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'tanggal' },
			{ data:'nama' },
			{ data:'grup' },
			{ data:'jabatan' },
			{ data:'departemen' },
			{ data:'cabang' },
			{ data:'hasil_nilai' },
		],
		// dom: 'Blftip',
		dom : "<'row' <'col-md-4'l> <'col-md-4'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'excel', 'pdf', 'colvis'
    ],


	});

});

function reloadTable() {
	$("#tblHasilPenilaian").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

//Select2 Karyawan

$("#btnDetailZoomImg").click(function() {
	$("#detailPopUpPhotoOut").click();
});

$(document).ready(function() {
	$('#keseluruhan').show();
	$('#bygrup').hide();
	$('#bydepartemen').hide();

	$('#contentkeseluruhan').click(function() {
		$('#keseluruhan').show();
		$('#bygrup').hide();
		$('#bydepartemen').hide();
	});

	$('#contentbygrup').click(function() {
		$('#keseluruhan').hide();
		$('#bygrup').show();
		$('#bydepartemen').hide();
	});

	$('#contentbydepartemen').click(function() {
		$('#keseluruhan').hide();
		$('#bygrup').hide();
		$('#bydepartemen').show();
	});

	$.post(base_url+"setting/getId/1",function(json) {
		if (json.status == true) {
			var option = ""
			if (json.data.periode_penilaian == 1) {
				option += '<option value="periode-1">Periode 1</option>';
			}
			else if (json.data.periode_penilaian == 2) {
				option += '<option value="periode-1">Periode 1</option>'+
									'<option value="periode-2">Periode 2</option>'

			}
			else if (json.data.periode_penilaian == 3) {
				option += '<option value="periode-1">Periode 1</option>'+
									'<option value="periode-2">Periode 2</option>'+
									'<option value="periode-3">Periode 3</option>'
			}
			else if (json.data.periode_penilaian == 4) {
				option += '<option value="periode-1">Periode 1</option>'+
									'<option value="periode-2">Periode 2</option>'+
									'<option value="periode-3">Periode 3</option>'+
									'<option value="periode-4">Periode 4</option>'
			}
			$('#periode,#periode1,#periode2').html(option);
		}
	});

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
$("#before, #after,#before1, #after1,#before2, #after2").change(function() {
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
	if($("#before1").val() != "" && $("#after1").val() != ""){
		var startDate = new Date($('#before1').val());
		var expiredDate = new Date($('#after1').val());
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
			$("#after1").val("");
		}
	}
	if($("#before2").val() != "" && $("#after2").val() != ""){
		var startDate = new Date($('#before2').val());
		var expiredDate = new Date($('#after2').val());
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
			$("#after2").val("");
		}
	}
});

$("#searchDate").click(function() {
	var url;
	url = base_url+'statistik/penilaian/ajax_list_after/'+$('#periode').val()+"/"+String($("#before").val())+"/"+String($("#after").val())+"/zero/zero";
	// url = base_url+'statistik/penilaian/ajax_list';

	var table = $('#tblHasilPenilaian').DataTable();

	table.ajax.url(url).load();

});

$("#searchDate1").click(function() {
	var url;
	url = base_url+'statistik/penilaian/ajax_list_after/'+$('#periode1').val()+"/"+String($("#before1").val())+"/"+String($("#after1").val())+"/"+$('#selectDepartemen').val()+"/zero";
	// url = base_url+'statistik/penilaian/ajax_list';

	var table = $('#tblHasilPenilaian1').DataTable();

	table.ajax.url(url).load();

});

$("#searchDate2").click(function() {
	var url;
	url = base_url+'statistik/penilaian/ajax_list_after/'+$('#periode2').val()+"/"+String($("#before2").val())+"/"+String($("#after2").val())+"/zero/"+$('#select_grup').val();
	// url = base_url+'statistik/penilaian/ajax_list';

	var table = $('#tblHasilPenilaian2').DataTable();

	table.ajax.url(url).load();

});
