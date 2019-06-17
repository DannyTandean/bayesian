<div class="col-md-12">
  <!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Transaksi</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblTransaksi" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Nama</th>
                            <th>Jumlah Transaksi</th>
                            <th>Jumlah Pembayaran</th>
                            <th>Kartu Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>
<?php assets_script_custom_aktivitas("transaction.js"); ?>
