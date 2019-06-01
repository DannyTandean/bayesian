function reloadPemberitahuan() {
    $.post(base_url + "pemberitahuan/getAllData", function(json) {
        if (json.status == true) {
            // console.log(json.data);
            $("#badgeCount").html(json.count_badge);
            var contentData = "";
            $.each(json.data, function(k, v) {
        		var noRead = "";
            	if (v.status == 1) {
	        		noRead = " border: 1px solid blue;";
	        	}
                // e += '<li onclick="readItemNotif(' + v.id + ')" style="pointer-events: auto; margin-top:5px; margin-bottom:5px;'+noRead+'" >', e += '    <div class="media">', e += '        <img class="media-object img-circle comment-img" src="' + v.photo + '" alt="Generic placeholder image-20">', e += '        <div class="media-body">', e += '        <h5 class="notification-user">' + v.username + "</h5>", e += '             <p class="notification-msg">' + v.keterangan + "</p>", e += '                 <span class="notification-time">' + v.created_at + "</span>", e += "        </div>", e += "    </div>", e += "</li>"

                contentData  += '<li onclick="readItemNotif(' + v.id + ')" style="pointer-events: auto; margin-top:5px; margin-bottom:5px;'+noRead+'">'
				 	+ '		<div class="media">'
					+ ' 		<img class="media-object img-circle comment-img" src="' + v.photo + '" alt="Generic placeholder image-20">'
					+ ' 		<div class="media-body">'
					+ '				<h5 class="notification-user">' + v.username + '</h5>'
					+ '				<p class="notification-msg">' + v.keterangan + '</p>'
					+ ' 			<span class="notification-time">' + v.created_at + '</span>'
					+ ' 		</div>'
					+ ' 	</div>'
					+ '</li>';
            });
            $("#contentNotif").html(contentData);
        }
    })
}
var idNotif;

function readItemNotif(a) {
    idNotif = a, $.post(base_url + "pemberitahuan/readPerItem/" + idNotif, function(a) {
        1 == a.status && (window.location.href = base_url + a.data.url_direct)
    })
}

$.post(base_url + "pemberitahuan/getAllData", function(json) {
    if (json.status == true) {
        $("#badgeCount").html(json.count_badge);
        var contentData = "";
        $.each(json.data, function(k, v) {
    		var noRead = "";
        	if (v.status == 1) {
        		noRead = " border: 1px solid blue;";
        	}
            contentData  += '<li onclick="readItemNotif(' + v.id + ')" style="pointer-events: auto; margin-top:5px; margin-bottom:5px;'+noRead+'">'
			 	+ '		<div class="media">'
				+ ' 		<img class="media-object img-circle comment-img" src="' + v.photo + '" alt="Generic placeholder image-20">'
				+ ' 		<div class="media-body">'
				+ '				<h5 class="notification-user">' + v.username + '</h5>'
				+ '				<p class="notification-msg">' + v.keterangan + '</p>'
				+ ' 			<span class="notification-time">' + v.created_at + '</span>'
				+ ' 		</div>'
				+ ' 	</div>'
				+ '</li>';
        });
        $("#contentNotif").html(contentData);
    }
});

$("#lihatSemuanya").click(function() {
    window.location.href = base_url + "pemberitahuan"
});

var config = {
    apiKey: "AIzaSyCGnR7kf2_kQWNhY6WDzyv1mjKe1LVseX0",
    databaseURL: "https://poihrd-418d3.firebaseio.com"
};
firebase.initializeApp(config), $(document).ready(function() {
    firebase.database().ref("/"), firebase.database().ref("/denyanotif").on("child_added", function(a) {
        var e = a.val();
        e && e.level == user_level && (document.getElementById("myAudio").play(), reloadPemberitahuan())
    })
});

$("#bacaSemuanya").click(function() {

	$("#showNotif").show();
	swal({
	        title: "Apakah anda yakin baca Semuanya Pemberitahuan.?",
	        html: "<span style='color:red;'>Data yang di <b>baca semuanya pemberitahuan</b> tidak bisa ditandai lagi sepertih belum di baca.!!.</span>",
	        type: "warning",
			width: 400,
				showCloseButton: true,
	        showCancelButton: true,
	        confirmButtonColor: "#DD6B55",
	        confirmButtonText: "Iya, Baca Semuanya",
	        closeOnConfirm: false,
				// background: '#e9e9e9',

	    }).then((result) => {
	    	if (result.value) {
	    		$.post(base_url+"pemberitahuan/readAll",function(json) {
					if (json.status == true) {
						swal({
					            title: json.message,
					            type: "success",
					            // html: true,
					            timer: 2000,
					            showConfirmButton: false
					        });
						reloadPemberitahuan();
					} else {
						swal({
					            title: json.message,
					            type: "error",
					            // html: true,
					            timer: 1500,
					            showConfirmButton: false
					        });
						reloadPemberitahuan();
					}
				});
	    	}
	    });
});