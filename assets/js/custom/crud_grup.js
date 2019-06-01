
// global variable
var manageMemberTable;

$(document).ready(function() {
	manageMemberTable = $("#dt-server-processing").DataTable({
		'processing':true,
    'serverSide':true,
		'responsive':true,
		"order": [],
		// 'ajax': 'crud_grup/fetchMemberData',
		"ajax":{
                url:'crud_grup/fetchMemberData',
                type:"POST"
           },
		"columnDefs": [
			 {
					 "targets": [2,3,4 ],
					 "orderable": false,
			 },
			 ]
	});
});

function addMemberModel()
{
	$("#createForm")[0].reset();

	//remove textdanger
	$(".text-danger").remove();
	// remove form-group
	$(".form-group").removeClass('has-error').removeClass('has-success');

	$("#createForm").unbind('submit').bind('submit', function() {
		var form = $(this);

		// remove the text-danger
		$(".text-danger").remove();

		$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success:function(response) {
				if(response.success === true) {
					$(".messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
					  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					  '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
					'</div>');

					// hide the modal
					$("#addMember").modal('hide');

					// update the manageMemberTable
					manageMemberTable.ajax.reload(null, false);

				} else {
					if(response.messages instanceof Object) {
						$.each(response.messages, function(index, value) {
							var id = $("#"+index);

							id
							.closest('.form-group')
							.removeClass('has-error')
							.removeClass('has-success')
							.addClass(value.length > 0 ? 'has-error' : 'has-success')
							.after(value);

						});
					} else {
						$(".messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
						  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
						  '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
						'</div>');
					}
				}
			}
		});

		return false;
	});

}

function editMember(id = null)
{
	if(id) {

		$("#editForm")[0].reset();
		$('.form-group').removeClass('has-error').removeClass('has-success');
		$('.text-danger').remove();

		$.ajax({
			url: 'crud_grup/getSelectedMemberInfo/'+id,
			type: 'post',
			dataType: 'json',
			success:function(response) {
				$("#editNamagrup").val(response.grup);
				$("#editKet").val(response.keterangan);

				$("#editForm").unbind('submit').bind('submit', function() {

					var form = $(this);
					$.ajax({
						url: form.attr('action') + '/' +id,
						type: 'post',
						data: form.serialize(),
						dataType: 'json',
						fail:function(res){
							alert("test");
						},
						success:function(response) {
							if(response.success === true) {
								$(".messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
								  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
								  '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
								'</div>');

								// hide the modal
								$("#editMemberModal").modal('hide');

								// update the manageMemberTable
								manageMemberTable.ajax.reload(null, false);

							} else {
								$('.text-danger').remove()
								if(response.messages instanceof Object) {
									$.each(response.messages, function(index, value) {
										var id = $("#"+index);

										id
										.closest('.form-group')
										.removeClass('has-error')
										.removeClass('has-success')
										.addClass(value.length > 0 ? 'has-error' : 'has-success')
										.after(value);

									});
								} else {
									$(".messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
									  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
									  '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
									'</div>');
								}
							}
						} // /succes
					}); // /ajax

					return false;
				});

			}
		});
	}
	else {
		alert('error');
	}
}

function removeMember(id = null)
{
	if(id) {
		// $("#removeMemberBtn").unbind('click').bind('click', function() {
			$.ajax({
				url: 'crud_grup/remove' + '/' + id,
				type: 'post',
				dataType: 'json',
				success:function(response) {
					if(response.success === true) {
						$(".messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
						  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
						  '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
						'</div>');

						// hide the modal
						$("#removeMemberModal").modal('hide');

						// update the manageMemberTable
						manageMemberTable.ajax.reload(null, false);

					} else {
						$('.text-danger').remove()
						if(response.messages instanceof Object) {
							$.each(response.messages, function(index, value) {
								var id = $("#"+index);

								id
								.closest('.form-group')
								.removeClass('has-error')
								.removeClass('has-success')
								.addClass(value.length > 0 ? 'has-error' : 'has-success')
								.after(value);

							});
						} else {
							$(".messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
							  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
							  '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
							'</div>');
						}
					}
				} // /succes
			}); // /ajax
		// });//remove end
	}
}
