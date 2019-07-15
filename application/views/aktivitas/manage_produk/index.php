<div class="col-md-12">
	<!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Product</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblProduk" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <!-- <th>Tools</th> -->
                            <th>Name</th>
                            <th>Image</th>
                            <th>Stock</th>
                            <th>Description</th>
                            <th>Price</th>
                            <!-- <th>Status</th> -->
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>

<?php echo modalSaveOpen(); ?>
    <div id="inputMessage"></div>
    <!-- <form class="floating-labels m-t-40"> -->
    <?php echo form_open("",array("id" => "formData")); ?>

      <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <label for="product_name" class="block">Nama Produk:</label>
              <input id="product_name" class="form-control" type="text" placeholder="Nama Produk" name="product_name">
              <span id="error_product_name"></span>
            </div>

            <div class="col-md-6">
              <label for="product_stock" class="block">Stok Produk:</label>
              <input id="product_stock" class="form-control" type="number" placeholder="Stok Produk" name="product_stock">
              <span id="error_product_stock"></span>
            </div>

            <div class="col-md-6">
              <label for="product_price" class="block">Harga produk:</label>
              <input id="product_price" class="form-control" type="number" placeholder="Harga produk" name="product_price">
              <span id="error_product_price"></span>
            </div>

            <div class="form-group col-md-12">
              <label class="label-form">Upload Foto Produk</label>
                <div class="col-sm-8">
                  <?php $defaultPhoto = base_url("/")."assets/images/default/no_file_.png"; ?>
                      <div class="card rounded-card user-card" style="height: 180px; width: 180px;">
                          <div class="card-block">
                              <div class="img-hover">
                                  <img class="img-fluid img-square" id="img_photo" src="<?php echo $defaultPhoto;?>" alt="produk-img" style="height: 125px;">
                                  <div class="img-overlay">
                                      <span>
                                          <a href="javascript:void(0);" title="Zoom Photo" id="btnZoomImg" class="btn btn-sm btn-success btn-outline-success">
                                            <i class="fa fa-search-plus"></i>
                                          </a>
                                          <a href="javascript:void(0);" title="Pilih Upload photo" class="btn btn-sm btn-primary btn-outline-primary" id="ganti_photo" data-popup="lightbox">
                                            <i class="fa fa-upload"></i>
                                            </a>
                                          <a href="javascript:void(0);" title="Hapus photo" id="hapus_photo" class="btn btn-sm btn-danger btn-outline-danger">
                                            <i class="fa fa-trash-o"></i>
                                          </a>
                                      </span>
                                  </div>

                                <input name="photo_produk" id="photo_produk" type="file" style="display: none;">
                                <input type="hidden" name="is_delete_photo" id="is_delete_photo" value="0">

                                  <a href="<?php echo $defaultPhoto;?>" style="display: none;" data-toggle="lightbox" id="popUpPhotoOut" data-title="Foto Produk" data-footer="">
                                <img src="<?php echo $defaultPhoto;?>" id="popUpPhotoIn" class="img-fluid" alt="">
                                </a>
                              </div>
                          </div>
                      </div>
                  </div>
            </div>

            <div class="col-md-12">
              <label for="deskripsi" class="block">Deskripsi :</label>
              <textarea id="deskripsi" name="deskripsi" class="form-control" style="resize: vertical;" placeholder="deskripsi"></textarea>
              <span id="error_product_deskripsi"></span>
            </div>
          </div>
      </div>
  </div>




	<?php echo form_close(); ?>
<?php echo modalSaveClose(); ?>
<!-- print space -->

<?php assets_script_custom_aktivitas("manage_produk.js"); ?>
