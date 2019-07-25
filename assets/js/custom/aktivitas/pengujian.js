$(document).ready(function() {


	btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";
	$("#tblDetection").DataTable({
		serverSide:true,
		responsive:true,
		processing:true,
		oLanguage: {
						sZeroRecords: "<center>Data not found</center>",
						sLengthMenu: "Show _MENU_ data   "+btnRefresh,
						sSearch: "Search data:",
						sInfo: "Show: _START_ - _END_ from total: _TOTAL_ data",
						oPaginate: {
								sFirst: "Start", "sPrevious": "Previous",
								sNext: "Next", "sLast": "Last"
						},
				},
		//load data
		ajax: {
			url: base_url+'aktivitas/Pengujian/ajax_list',
			type: 'POST',
		},

		order:[[1,'DESC']],
		columns:[
			{
				data:'no',
				searchable:false,
				orderable:false,
			},
			{ data:'isFraud' },
			{ data:'type' },
			{ data:'amount' },
			{ data:'nameOrig' },
			{ data:'oldbalanceOrg' },
			{ data:'newbalanceOrig' },
			{ data:'nameDest' },
			{ data:'oldbalanceDest' },
			{ data:'newbalanceDest' },
			{ data:'isFlaggedFraud'}
		],
		// dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    // buttons: [
    //     'colvis','excel'
    // ],
	});
});

function reloadTable() {
	$("#tblDetection").DataTable().ajax.reload(null,false);
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

$(document).ready(function() {
	$.post(base_url+"/aktivitas/pengujian/getSimulation",function(json) {
    if(json.status == true){
          $('#tp').html(json.tp);
					$('#tn').html(json.tn);
					$('#fp').html(json.fp);
					$('#fn').html(json.fn);
					$('#accuracy').html(json.tp +"+"+ json.tn +"/ ("+ json.tp +"+"+ json.tn +json.fp +"+"+ json.fn +")" );
					$('#resultTrial').html(parseInt(((json.tp + json.tn) / (json.tp + json.tn + json.fp + json.fn)) * 100) + "%");
    } else {
      console.log("error");
    }
  });
});
