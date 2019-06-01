
<?php 
	$no = 1;
	if(!empty($posts)){
		foreach($posts as $post){ 
			$srcPhoto = base_url().'assets/images/default/no_user.png';
			if ($post["photo"] != "") {
				$srcPhoto = base_url()."uploads/pengguna/".$post["photo"];
			}

			$post["photo"] = $srcPhoto;
			$borderStatus = $post["status"] == 1 ? "border: 1px solid blue;" : "";
?>
		    <li onclick="readItemNotif(<?php echo $post["id"]; ?>)">
		    	<a href="javascript:void();" style="pointer-events: auto;">
		        <div class="card user-card">
		            <div class="card-block" style="<?php echo $borderStatus; ?>">
		                <div class="media">
		                    <a class="media-left" href="#">
		                        <img class="media-object img-circle card-list-img" src="<?php echo $post["photo"]; ?>" alt="Generic placeholder image">
		                    </a>
		                    <div class="media-body">
		                        <div class="col-xs-12">
		                            <h6 class="d-inline-block"><?php echo $post["username"]; ?></h6>
		                            <label class="label label-info"><?php echo $post["user_level"]; ?></label>
		                        </div>
		                        <div class="f-13 text-muted m-b-15"><?php echo $post["nama_pengguna"]; ?></div>
		                        <p><?php echo $post["keterangan"]; ?></p>
		                        <b class="text-muted"><?php echo $post["created_at"]; ?></b>
		                        
		                    </div>
		                </div>
		            </div>
		        </div>
		    	</a>
		    </li>
<?php 	
		} 
		if($postNum > $postLimit){ 
?>
		    <div id="loadMoreView" class="load-more" lastID="<?php echo $post['id']; ?>" style="display: none;">
		    	<center>
		    		<h2 style="color:blue;"><span class="fa fa-spinner fa-spin"></span> Loading more...</h2>
		    	</center>
		    </div>

<?php 		
		}else{ 	
?>
			<div class="load-more" lastID="0">
		        <center><h2 style="color:blue;">Berakhir Ini Saja</h2></center>
		    </div>
<?php 
		} 
	}else{ 
?>
	    <div class="load-more" lastID="0">
	        <center><h2 style="color:blue;">Berakhir Ini Saja</h2></center>
	    </div>
<?php 
	} 
?>