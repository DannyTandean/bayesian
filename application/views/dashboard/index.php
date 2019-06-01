<div class="col-md-12">


    <!-- Page-body start -->
<div class="page-body">
    <div class="row">
        <!-- counter-card-1 start-->
        <!-- <div class="col-md-12 col-xl-4">
            <div class="card counter-card-1">
                <div class="card-block-big">
                    <div class="row">
                        <div class="col-6 counter-card-icon">
                            <i class="icofont icofont-chart-histogram"></i>
                        </div>
                        <div class="col-6  text-right">
                            <div class="counter-card-text">
                                <h3>23%</h3>
                                <p>ACTIVE USER</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
<!-- counter-card-1 end-->
<!-- counter-card-2 start -->
        <!-- <div class="col-md-6 col-xl-4">
            <div class="card counter-card-2">
                <div class="card-block-big">
                    <div class="row">
                        <div class="col-6 counter-card-icon">
                          <i class="icofont icofont-chart-line-alt"></i>
                        </div>
                        <div class="col-6 text-right">
                            <div class="counter-card-text">
                               <h3>15%</h3>
                               <p>DOWN RATE</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
<!-- counter-card-2 end -->
<!-- counter-card-3 start -->
        <!-- <div class="col-md-6 col-xl-4">
            <div class="card counter-card-3">
                <div class="card-block-big">
                    <div class="row">
                        <div class="col-6 counter-card-icon">
                          <i class="icofont icofont-chart-line"></i>
                        </div>
                        <div class="col-6 text-right">
                            <div class="counter-card-text">
                               <h3>35%</h3>
                               <p>SALE RATIO</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
<!-- counter-card-3 end -->
    </div>
</div>



    <!-- minute spent start -->
    <div class="row">
        <div class="col-md-9">
            <div class="sticky-card">
                <!-- <div class="row">
                    <div class="col-sm-2">
                        <span id="notes" class="notes"></span>
                    </div>
                    <div class="col-sm-2">
                        <span id="notes1" class="notes1"></span>
                    </div>
                </div> -->
            </div>
            <div class="card minute-card card-border-primary">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <h5> Chart kehadiran </h5>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm form-control-bold form-control-center tanggal" id="tglChartKehadiran" style="margin-bottom: -15px; margin-top: -5px;">
                        </div>
                        <div class="col-md-2">
                            <h5 id="tanggalKehadiran"></h5>
                        </div>
                        <div class="col-md-2">
                          <button type="button" id="saveNote" class="btn btn-primary btn-mini waves-effect waves-light"  title="Save note">
                            <i class="fa fa-plus"></i> Save Note
                          </button>
                        </div>

                        <div class="col-md-2">
                            <button type="button" id="idRunTheCode" class="btn btn-primary btn-mini waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Add note">
                                <i class="fa fa-plus"></i> Add Note
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive ">
                            <table class="table table-sm ">
                                <tbody>
                                     <tr>
                                        <th scope="row">Total Karyawan</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <a href="javascript:void(0);">
                                                <span style="color: #239a55;" id="totalKaryawanCount">0</span> <i style="color:black;"> Orang</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Hadir</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <a href="javascript:void(0);">
                                                <span style="color: #239a55;" id="hadirCount">0</span> <i style="color:black;"> Orang</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tidak Hadir</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <a href="javascript:void(0);">
                                                <span style="color: #e01c04;" id="mangkirCount">0</span> <i style="color:black;"> Orang</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Sakit</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <a href="javascript:void(0);">
                                                <span style="color: #0073b7;" id="sakitCount">0</span> <i style="color:black;"> Orang</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Izin</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <a href="javascript:void(0);">
                                                <span style="color: #f39c12;" id="izinCount">0</span> <i style="color:black;"> Orang</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Cuti</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <a href="javascript:void(0);">
                                                <span style="color: #00c0ef;" id="cutiCount">0</span> <i style="color:black;"> Orang</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <th scope="row">OFF</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <a href="javascript:void(0);">
                                                <span style="color: #001f3f;" id="offCount">0</span> <i style="color:black;"> Orang</i>
                                            </a>
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <th scope="row">Perjalanan Dinas</th>
                                        <td>:</td>
                                        <td style="text-align: right;">
                                            <a href="javascript:void(0);">
                                                <span style="color: #605ca8;" id="dinasCount">0</span> <i style="color:black;"> Orang</i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="chart" style="height:300px; margin-top: 0px;"></div>
                    </div>
                </div>

                <div class="p-20 z-depth-bottom-1 waves-effect">
                    <div class="row">
                        <div class="col-md">
                            <div class="card card-border-inverse">
                                <div class="card-header">
                                    <h5>Data Kehadiran Per Shift</h5>
                                </div>
                                <div class="card-block">
                                    <div class="table-responsive  table-sm ">
                                        <table class="table table-striped">
                                            <tbody>
                                              <?php foreach ($jadwal as $key => $value): ?>
                                                <tr>
                                                    <th scope="row"><?php echo $value->nama_jadwal; ?></th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="<?php echo $value->id_jadwal."Count"; ?>">0</span> <i style="color:black;"> Orang</i>
                                                            <input type="hidden" value="<?php echo $value->id_jadwal; ?>" id="<?php echo $value->id_jadwal; ?>">
                                                        </a>
                                                    </td>
                                                </tr>
                                              <?php endforeach; ?>
                                              <tr>
                                                <th scope="row">Jadwal Tidak Ditentukan</th>
                                                <td>:</td>
                                                <td style="text-align: right;">
                                                    <a href="javascript:void(0);">
                                                        <span id="takterjadwalCount">0</span> <i style="color:black;"> Orang</i>
                                                    </a>
                                                </td>
                                              </tr>
                                                <!-- <tr>
                                                    <th scope="row">REGULAR</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="regularCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">SHIFT PAGI</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="shiftPagiCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">SHIFT SORE</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="shiftSoreCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">SHIFT MALAM</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="shiftMalamCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">SHIFT PAGI 12 JAM</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="shiftPagi12Count">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">SHIFT MALAM 12 JAM</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="shiftMalam12Count">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">SHIFT MANUAL</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="shiftManualCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">EXTRA KERJA</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="extraKerjaCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="card card-border-inverse">
                                <div class="card-header">
                                    <h5>Data Ketepatan Kehadiran</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Hadir Tepat Waktu</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="hadirTepatCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Hadir Terlambat</th>
                                                    <td>:</td>
                                                    <td style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="hadirTerlambatCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                     </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Total Kehadiran Karyawan</th>
                                                    <td>:</td>
                                                    <th style="text-align: right;">
                                                        <a href="javascript:void(0);">
                                                            <span id="totalKehadiranCount">0</span> <i style="color:black;"> Orang</i>
                                                        </a>
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">

            <!-- speedometer -->
                <!-- <div class="card">
                    <div class="card-header">
                        <h5> SERVER LOAD</h5>
                        <div class="card-header-right">
                            <i class="icofont icofont-rounded-down"></i>
                        </div>
                    </div>
                    <div class="card-block">
                        <div id="server-load" style="height:300px"></div>
                    </div>
                </div> -->
            <!-- speedometer -->

            <div class="card minute-card card-border-warning">
                <div class="card-header">
                    <h5>Kontrak Karyawan</h5>
                </div>
                <div class="table-responsive table-sm ">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row" style="text-align: center;">Sisa 1 bulan</th>
                                <td>:</td>
                                <td style="text-align: right; padding-right: 20px;">
                                    <a href="javascript:void(0);">
                                        <span style="color: orange;" id="sisa1Bulan">0</span> <i style="color:black;"> Orang</i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"  style="text-align: center;">Sisa 2 bulan</th>
                                <td>:</td>
                                <td style="text-align: right; padding-right: 20px;">
                                    <a href="javascript:void(0);">
                                        <span class="text-info" id="sisa2Bulan">0</span> <i style="color:black;"> Orang</i>
                                    </a>
                                 </td>
                            </tr>
                            <tr>
                                <th scope="row"  style="text-align: center;">Sisa 3 bulan</th>
                                <td>:</td>
                                <td style="text-align: right; padding-right: 20px;">
                                    <a href="javascript:void(0);">
                                        <span class="text-success" id="sisa3Bulan">0</span> <i style="color:black;"> Orang</i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"  style="text-align: center;">Habis Kontrak</th>
                                <td>:</td>
                                <td style="text-align: right; padding-right: 20px;">
                                    <a href="javascript:void(0);">
                                        <span class="text-danger" id="habisKontrakBulan">0</span> <i style="color:black;"> Orang</i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-border-success" id="headerUlangTahun" style="display: none;">
                <div class="card-header">
                    <h5>Ulang Tahun Karyawan</h5>
                    <div class="card-header-right">
                        <i class="icofont icofont-rounded-down"></i>
                    </div>
                </div>
                <!-- <div id="dataUlangTahun"></div> -->
                <div class="card-block p-t-0" id="dataUlangTahun">
                    <!-- <div class="card-msg b-b-default p-t-10 p-b-10">
                        <div class="card-msg-img">
                            <a href="assets/images/light-box/l2.jpg" data-toggle="lightbox" data-title="A random title">
                                <img src="assets/images/light-box/sl2.jpg" class="img-circle" alt="" style="height: 60px; width: 60px;">
                            </a>
                        </div>
                        <div class="card-msg-contain">
                            <h6>Muhammad Irfan Lubis</h6>
                            <span>Operator QC</span>
                        </div>
                        <table class="m-t-10 table-responsive" style="font-size: 12px;">
                            <tr>
                                <th>Ulang Tahun Ke</th>
                                <td>&ensp;:&ensp;</td>
                                <td><u>19 Tahun</u></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>&ensp;:&ensp;</td>
                                <td>02 Nov 1999</td>
                            </tr>
                            <tr>
                                <th>Tempat Lahir</th>
                                <td>&ensp;:&ensp;</td>
                                <td>Medan, Sumatra Utara</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>&ensp;:&ensp;</td>
                                <td>Laki-laki</td>
                            </tr>
                        </table>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-border-info">
                <div class="card-header">
                    <h5>Statistik Kehadiran Per Departemen</h5>
                    <div class="card-header-right">
                        <i class="icofont icofont-rounded-down"></i>
                    </div>
                </div>
                <div class="card-block text-center">
                    <div class="row" id="absensiDepartemen">
                        <!-- <div class="col-md-2">
                            <label>Produksi</label>
                            <br>
                            <span class="mytooltip tooltip-effect-5">
                                <input type="text" class="dial tooltip-item" value="120" data-min="0" data-max="150" data-width="70" data-height="70" data-linecap="round" data-displayprevious="true" data-displayInput="true" data-readonly="true" data-fgColor="#239a55">
                                <span class="tooltip-content clearfix">
                                    <span class="tooltip-text">
                                        <b style="font-size: 20px;">Produksi</b>
                                        <br>
                                        <span class="text-success">Hadir : 120 Orang</span>
                                        <br>
                                        <span class="text-danger">Mangkir : 20 Orang</span>
                                        <br>
                                        <span class="text-info">Total : 140 Orang</span>
                                    </span>
                                </span>
                            </span>
                        </div>
                        <br><br><br><br><br><br> -->
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- minute spent end -->
</div>

<?php $this->load->view('dashboard/modal_table'); ?>

<!-- echart js -->
<!-- <script src="<?php //echo base_url(); ?>assets/pages/chart/echarts/js/echarts-all.js" type="text/javascript"></script> -->
<?php //assets_script_custom("server_load.js"); ?>

<!-- c3 chart js -->
<script src="<?php echo base_url(); ?>assets/bower_components/c3/js/c3.js"></script>
<!-- knob js -->
<script src="<?php echo base_url(); ?>assets/pages/chart/knob/jquery.knob.js?bug=12"></script>

<!-- script dashboard -->
<?php assets_script_custom("dashboard.js"); ?>
