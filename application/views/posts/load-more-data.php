<!-- <div id="postList"> -->
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
    <?php if($postNum > $postLimit){ ?>
        <div class="load-more" lastID="<?php echo $post['id']; ?>" style="display: none;">
            <span class="fa fa-spinner fa-spin"></span> Loading more posts...
        </div>
    <?php }else{ ?>
        <div class="load-more" lastID="0">
            That's All!
        </div>
    <?php } ?>    
<?php }else{ ?>    
    <div class="load-more" lastID="0">
            That's All!
    </div>    
<?php } ?>
<!-- </div> -->