$(document).ready(function(){
    $(window).scroll(function(){
        var lastID = $('.load-more').attr('lastID');
        if(($(window).scrollTop() == $(document).height() - $(window).height()) && (lastID != 0)){
            $.ajax({
                type:'POST',
                url: base_url+'pemberitahuan/loadMoreData',
                data:'id='+lastID,
                beforeSend:function(){
                    $('.load-more').show();
                },
                success:function(html){
                	setTimeout(function() {
                		$('.load-more').remove();
	                    $('#listPemberitahuan').append(html);
                	},1000);
                }
            });
        }
    });
});
