<div class="col-md-12">
  <!-- Zero config.table start -->
    <div class="card">
        <div class="card-header">
            <h5>Data Table Pengujian</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <!-- <i class="icofont icofont-refresh"></i> -->
                <!-- <i class="icofont icofont-close-circled"></i> -->
            </div>
        </div>
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="tblDetection" class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                          <th>No</th>
                          <th>Fraud</th>
                          <th>type</th>
                          <th>amount</th>
                          <th>nameOrig</th>
                          <th>oldbalanceOrg</th>
                          <th>newbalanceOrig</th>
                          <th>nameDest</th>
                          <th>oldbalanceDest</th>
                          <th>newbalanceDest</th>
                          <th>isFlaggedFraud</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card-block">
          <div class="row">
            <div class="col-md-6">
              <table class="table table-striped table-bordered table-sm nowrap" style="width: 100%;">
                <tr>
                  <td>&nbsp;</td>
                  <td>True</td>
                  <td>False</td>
                </tr>
                <tr>
                  <td>Positive</td>
                  <td id="tp"></td>
                  <td id="fp"></td>
                </tr>
                <tr>
                  <td>Negative</td>
                  <td id="tn"></td>
                  <td id="fn"></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <p>Accuracy = (TP + TN) / (TP + TN + FP + FN)</p>
              <p>Accuracy = <span id="accuracy"></span> </p>
              <p>Accuracy = <span id="resultTrial"></span> </p>
            </div>
          </div>
        </div>
    </div>
    <!-- Zero config.table end -->
</div>
<?php assets_script_custom_aktivitas("pengujian.js"); ?>
