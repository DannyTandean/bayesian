
<!-- Font Awesome -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icon/font-awesome/css/font-awesome.min.css">

<div id="postList">
    <?php $no = 1; ?>
    <?php if(!empty($posts)){ foreach($posts as $post){ ?>
        <div class="list-item">
            <h2><?php echo $post['user_id']; ?></h2>
            <p><?php echo $post['keterangan']; ?></p>
            <p><?php echo $post['level']; ?></p>
            <p><?php echo $post['tanggal']; ?></p>
            <p><?php echo $post['jam']; ?></p>
            <p><?php echo $post['url_direct']; ?></p>
            <p><?php echo $post['created_at']; ?></p>
            <b>No <?php echo $no++; ?></b>
            <hr>
        </div>
    <?php } ?>
        <div class="load-more" lastID="<?php echo $post['id']; ?>" style="display: none;">
            <span class="fa fa-spinner fa-spin"></span> Loading more posts...
        </div>
    <?php }else{ ?>
        <p>Post(s) not available.</p>
    <?php } ?>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/jquery/js/jquery.min.js"></script>

<script type="text/javascript">

    /*$(window).scroll(function() {
        var lastID = $('.load-more').attr('lastID');
        if(($(window).scrollTop() + $(window).height() >= $(document).height()) && (lastID != 0)) {
            $.ajax({
                    type:'POST',
                    url:'<?php echo base_url('posts/loadMoreData'); ?>',
                    data:'id='+lastID,
                    beforeSend:function(){
                        $('.load-more').show();
                    },
                    success:function(html){
                        $('.load-more').remove();
                        $('#postList').append(html);
                    }
                });
        }
    });*/

    $(document).ready(function(){
        $(window).scroll(function(){
            var lastID = $('.load-more').attr('lastID');
            if(($(window).scrollTop() == $(document).height() - $(window).height()) && (lastID != 0)){
                $.ajax({
                    type:'POST',
                    url:'<?php echo base_url('posts/loadMoreData'); ?>',
                    data:'id='+lastID,
                    beforeSend:function(){
                        $('.load-more').show();
                    },
                    success:function(html){
                        $('.load-more').remove();
                        $('#postList').append(html);
                    }
                });
            }
        });
    });
</script>