
$(document).ready(function() {
	$("#btnLogin").click(function() {
		$("#btnLogin").attr("disabled",true);
		$("#btnLogin").html("Loading...<i class='fa fa-spinner fa-spin'></i>");
		var base_url = $("#base_url").val();
		$.ajax({
			url: base_url+'auth/login_ajax',
			type:'POST',
			dataType:'json',
			data:$("#formLogin").serialize(),
			success:function(json) {
				if (json.status == true) {

					$("#btnLogin").attr("disabled",true);
					$("#btnLogin").html("Harap menunggu...<i class='fa fa-spinner fa-spin'></i>");

					setTimeout(function() {
						$("#btnLogin").attr("disabled",false);
						$("#btnLogin").html(' Sign in');
						window.location.href = base_url+"aktivitas/transaction";
					},1000);
				} else {
					$("#errorUsername").html(json.error.username);
					$("#errorPassword").html(json.error.password);

					// $("#inputMessage").html(json.error.account);

					if (json.error.account) {
						const Toast = Swal.mixin({
						  toast: true,
						  position: 'top',
						  showConfirmButton: false,
							width: 430,
						  timer: 1500
						});

						Toast.fire({
							title: '<img src="' + base_url + '/assets/images/favicon.png" width="50" height="50">',
							html: "<span style='color:red;'>Terjadi Kesalahan.</span><br><h5>Username & Password Salah!</h5>"
						})
					}

					setTimeout(function() {
						$("#inputMessage").html("");

						$("#errorUsername").html("");
						$("#errorPassword").html("");
						// $("#inputMessage").html("");
						$("#btnLogin").attr("disabled",false);
						$("#btnLogin").html('Login');
					},1500);
				}
			}
		});
	});
});

$(document).keypress(function(e) {
    if(e.which == 13) {
        $("#btnLogin").click();
    }
});
