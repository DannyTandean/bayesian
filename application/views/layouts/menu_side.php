
    <!-- <nav class="pcoded-navbar"> -->
    <nav class="pcoded-navbar">

        <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
        <div class="pcoded-inner-navbar main-menu">

            <?php
                $class = $this->router->fetch_class();
                $method = $this->router->fetch_method();

                $uri1 = $this->uri->segment(1);
                $uri2 = $this->uri->segment(2);
                $uri3 = $this->uri->segment(3);
                $uri4 = $this->uri->segment(4);
                $uri5 = $this->uri->segment(5);
             ?>

            <div class="pcoded-navigatio-lavel " data-i18n="nav.category.navigation" menu-title-theme="theme2">Menu Utama</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo $class == 'dashboard' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('dashboard'); ?>">
                        <span class="pcoded-micon"><i class="ti-dashboard"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                        <!-- <span class="pcoded-badge label label-danger">100+</span> -->
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <?php
                    /*for master link active*/

                    $masterActive = "";
                    $masterKaryawan = "";
                    $masterCabang = "";
                    $masterDepartemen = "";
                    $masterJabatan = "";
                    $masterGolongan = "";
                    $masterShift = "";
                    $masterBank = "";
                    $masterGroup = "";
                    $masterKriteria = "";
                    if ($uri1 == "master" ) {
                        $masterActive = "pcoded-trigger active";
                        $masterKaryawan = $class == "karyawan" ? "active" : "";
                        $masterCabang = $class == "cabang" ? "active" : "";
                        $masterDepartemen = $class == "departemen" ? "active" : "";
                        $masterJabatan = $class == "jabatan" ? "active" : "";
                        $masterGolongan = $class == "golongan" ? "active" : "";
                        $masterShift = $class == "shift" ? "active" : "";
                        $masterBank = $class == "bank" ? "active" : "";
                        $masterGroup = $class == "grup" ? "active" : "";
                        $masterKriteria = $class == "kriteria" ? "active" : "";
                    }
                ?>
                <!-- Master Data -->
                <li class="pcoded-hasmenu <?php echo $masterActive; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-agenda"></i></span>
                        <span class="pcoded-mtext" >Master Data</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo $masterKaryawan; ?>">
                            <a href="<?php echo site_url('master/karyawan'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $masterCabang; ?>">
                            <a href="<?php echo site_url('master/cabang'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Cabang</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $masterDepartemen; ?>">
                            <a href="<?php echo site_url('master/departemen') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Departemen</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $masterJabatan; ?>">
                            <a href="<?php echo site_url('master/jabatan'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Jabatan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $masterGolongan; ?>">
                            <a href="<?php echo site_url('master/golongan'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Golongan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <!-- <li class="<?php echo $masterShift; ?>">
                            <a href="<?php echo site_url('master/shift'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Shift</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li> -->
                        <li class="<?php echo $masterBank; ?>">
                            <a href="<?php echo site_url('master/bank'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Bank</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $masterGroup; ?>">
                            <a href="<?php echo site_url('master/grup'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Group</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $masterKriteria; ?>">
                            <a href="<?php echo site_url('master/kriteria') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Kriteria</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php
                    /*for Aktivitas Owner link active*/
                    if ($this->user_level == "owner" || $this->user_level == "admin") {


                    $aktivitasownerActive = "";
                    $aktivitasownerPengumuman = "";
                    if ($uri1 == "aktivitasowner" ) {
                        $aktivitasownerActive = "pcoded-trigger active";
                        $aktivitasownerPengumuman = $class == "pengumuman" ? "active" : "";
                    }
                ?>


               <!-- Activites Owner -->

                <li class="pcoded-hasmenu <?php echo $aktivitasownerActive; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-loop"></i></span>
                        <span class="pcoded-mtext" >Aktivitas Owner </span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo $aktivitasownerPengumuman; ?>">
                            <a href="<?php echo site_url('aktivitasowner/pengumuman'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Pengumuman & Berita</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                </li>
                 <?php } ?>


               <?php
                    if ($this->user_level == "hrd" || $this->user_level == "admin") {
                    /*for Activites link active
                        act = activites */
                    $aktivitasActive = "";
                    // $aktivitasJadwalkerja = "";
                    $aktivitasPenggajianPayment = "";
                    $aktivitasJadwalkaryawan = "";
                    // $aktivitasPenggajian = "";
                    // $aktivitasSlipGaji = "";
                    $aktivitasSakit = "";
                    $aktivitasIzin = "";
                    $aktivitasCuti = "";
                    $aktivitasDinas = "";
                    $aktivitasDirumahkan = "";
                    $aktivitasPinjaman = "";
                    $aktivitasPromosi = "";
                    $aktivitasPengumuman = "";
                    $aktivitasAbsensi = "";
                    $aktivitasPenilaian = "";
                    $aktivitasThr = "";
                    if ($uri1 == "aktivitas" ) {
                        $aktivitasActive = "pcoded-trigger active";
                        // $aktivitasJadwalkerja = $class == "jadwalkerja" ? "active" : "";
                        $aktivitasJadwalkaryawan = $class == "jadwalkaryawan" ? "active" : "";
                        $aktivitasPenggajianPayment = $class == "penggajian_payment" ? "active" : "";
                        $aktivitasThr = $class == "thr" ? "active" : "";
                        // $aktivitasPenggajian = $class == "Penggajian" ? "active" : "";
                        // $aktivitasSlipGaji = $class == "slipgaji" ? "active" : "";
                        $aktivitasSakit = $class == "sakit" ? "active" : "";
                        $aktivitasIzin = $class == "izin" ? "active" : "";
                        $aktivitasCuti = $class == "cuti" ? "active" : "";
                        $aktivitasDinas = $class == "dinas" ? "active" : "";
                        $aktivitasDirumahkan = $class == "dirumahkan" ? "active" : "";
                        $aktivitasPinjaman = $class == "pinjaman" ? "active" : "";
                        $aktivitasPromosi = $class == "PDM" ? "active" : "";
                        $aktivitasAbsensi = $class == "LogAbsensi" ? "active" : "";
                        $aktivitasPenilaian = $class == "penilaian" ? "active" : "";

                    }
                ?>

                <!-- Activities -->

                <li class="pcoded-hasmenu <?php echo $aktivitasActive; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-loop"></i></span>
                        <span class="pcoded-mtext" >Aktivitas</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <!-- <li class="<?php echo $aktivitasJadwalkerja; ?>">
                            <a href="<?php echo site_url('aktivitas/jadwalkerja') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Jadwal Kerja Shift</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li> -->
                        <li class="<?php echo $aktivitasPenggajianPayment; ?>">
                            <a href="<?php echo site_url('aktivitas/penggajian_payment') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Penggajian</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasThr; ?>">
                            <a href="<?php echo site_url('aktivitas/thr') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >THR</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasJadwalkaryawan; ?>">
                            <a href="<?php echo site_url('aktivitas/jadwalkaryawan') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Jadwal karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasPenilaian; ?>">
                            <a href="<?php echo site_url('aktivitas/penilaian') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Penilaian karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>

                        <!-- <li class="<?php echo $aktivitasPenggajian; ?>">
                            <a href="<?php echo site_url('aktivitas/Penggajian'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Penggajian</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li> -->
                        <!-- <li class="<?php //echo $aktivitasSlipGaji; ?>">
                            <a href="<?php //echo site_url(''); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Slip Gaji</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li> -->
                        <li class="<?php echo $aktivitasSakit; ?>">
                            <a href="<?php echo site_url('aktivitas/sakit') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Sakit Karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasIzin; ?>">
                            <a href="<?php echo site_url('aktivitas/izin'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Izin Karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasCuti; ?>">
                            <a href="<?php echo site_url('aktivitas/cuti'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Cuti Karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasDinas; ?>">
                            <a href="<?php echo site_url('aktivitas/dinas'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Perjalanan Dinas</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasDirumahkan; ?>">
                            <a href="<?php echo site_url('aktivitas/dirumahkan'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Dirumahkan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasPinjaman; ?>">
                            <a href="<?php echo site_url('aktivitas/pinjaman'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Pinjaman Karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasPromosi; ?>">
                            <a href="<?php echo site_url('aktivitas/PDM'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Promosi-Demosi-Mutasi</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $aktivitasAbsensi; ?>">
                            <a href="<?php echo site_url('aktivitas/LogAbsensi'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Log Absensi</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>

                    </ul>
                </li>
                <?php } ?>


                  <!-- <?php
                    if ($this->user_level == "hrd" || $this->user_level == "admin") {
                    /*for Activites link active
                        act = activites */
                    $pekerjaboronganActive = "";
                    $pekerjaboronganKaryawan = "";
                    $pekerjaboronganDepartemen = "";
                    $pekerjaboronganProduksi = "";
                    $pekerjaboronganPenggajian = "";
                    $pekerjaboronganLaporanPenggajian = "";
                    $pekerjaboonganLaporanProduksi = "";

                    if ($uri1 == "pekerjaborongan" ) {
                        $pekerjaboronganActive = "pcoded-trigger active";
                        $pekerjaboronganKaryawan = $class == "pekerja" ? "active" : "";
                        $pekerjaboronganDepartemen = $class == "departemen" ? "active" : "";
                        $pekerjaboronganProduksi = $class == "produksi" ? "active" : "";
                        $pekerjaboronganPenggajian = $class == "penggajian" ? "active" : "";
                        $pekerjaboronganLaporanPenggajian = $class == "LaporanPenggajian" ? "active" : "";
                        $pekerjaboonganLaporanProduksi = $class == "laporanproduksi" ? "active" : "";


                    }
                ?>  -->

                <!-- Activities -->

                <!-- <li class="pcoded-hasmenu <?php echo $pekerjaboronganActive; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-hummer"></i></span>
                        <span class="pcoded-mtext" >Fitur Pekerja Borongan</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo $pekerjaboronganKaryawan; ?>">
                            <a href="<?php echo site_url('pekerjaborongan/pekerja') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Pekerja Borongan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $pekerjaboronganDepartemen; ?>">
                            <a href="<?php echo site_url('pekerjaborongan/departemen'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Item Borongan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>

                        <li class="<?php echo $pekerjaboronganProduksi; ?>">
                            <a href="<?php echo site_url('pekerjaborongan/produksi') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Produksi Borongan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $pekerjaboonganLaporanProduksi; ?>">
                            <a href="<?php echo site_url('pekerjaborongan/LaporanProduksi') ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan Produksi Borongan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $pekerjaboronganPenggajian; ?>">
                            <a href="<?php echo site_url('pekerjaborongan/penggajian'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Penggajian Borongan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $pekerjaboronganLaporanPenggajian; ?>">
                            <a href="<?php echo site_url('pekerjaborongan/LaporanPenggajian'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan Penggajian Borongan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <?php } ?>


                <?php
                if ($this->user_level == "owner" || $this->user_level == "admin") {

                    /*for approval link active*/
                    $ownapprovalActive = "";
                    $ownapprovalAbsensi = "";
                    $ownapprovalPinjaman = "";
                    $ownapprovalKaryawan = "";
                    $ownapprovalGolongan = "";
                    $ownapprovalPromosi = "";
                    $ownapprovalReward = "";
                    $ownapprovalPunish = "";
                    if ($uri1 == "approvalowner" ) {
                        $ownapprovalActive = "pcoded-trigger active";
                        $ownapprovalPinjaman = $class == "pinjaman" ? "active" : "";
                        $ownapprovalKaryawan = $class == "karyawan" ? "active" : "";
                        $ownapprovalGolongan = $class == "golongan" ? "active" : "";
                        $ownapprovalPromosi = $class == "PDM" ? "active" : "";
                        $ownapprovalReward = $class == "reward" ? "active" : "";
                        $ownapprovalPunish = $class == "punishment" ? "active" : "";
                        $ownapprovalAbsensi = $class == "absensi" ? "active" : "";
                    }
                ?>

                <!-- Approval Owner -->

                <li class="pcoded-hasmenu <?php echo $ownapprovalActive; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-crown"></i></span>
                        <span class="pcoded-mtext" >Approval Owner</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo $ownapprovalAbsensi; ?>">
                            <a href="<?php echo site_url('approvalowner/absensi'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Approval Penghapusan Absensi</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $ownapprovalPinjaman; ?>">
                            <a href="<?php echo site_url('approvalowner/pinjaman'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Approval Pinjaman</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $ownapprovalKaryawan; ?>">
                            <a href="<?php echo site_url('approvalowner/karyawan'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Approval Karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $ownapprovalGolongan; ?>">
                            <a href="<?php echo site_url('approvalowner/golongan'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Approval Golongan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $ownapprovalPromosi; ?>">
                            <a href="<?php echo site_url('approvalowner/PDM'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Approval Promosi Demosi Mutasi</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $ownapprovalReward; ?>">
                            <a href="<?php echo site_url('approvalowner/reward'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Approval Reward</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $ownapprovalPunish; ?>">
                            <a href="<?php echo site_url('approvalowner/punishment'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Approval Punishment</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

                 <?php
                    if ($this->user_level == "hrd" || $this->user_level == "admin") {
                    /*for approval link active*/
                    $approvalActive = "";
                    $approvalCuti = "";
                    $approvalSakit = "";
                    $approvalIzin = "";
                    $approvalDinas = "";
                    $approvalDirumahkan = "";
                    $approvalAbsensiTakTerduga = "";
                    $approvalAbsensiExtra = "";
                    // $approvalLogKabag = "";
                    // $approvalLogPos = "";
                    // $approvalLogJadwal = "";

                    if ($uri1 == "approval" ) {
                        $approvalActive = "pcoded-trigger active";
                        $approvalCuti = $class == "cuti" ? "active" : "";
                        $approvalSakit = $class == "sakit" ? "active" : "";
                        $approvalIzin = $class == "izin" ? "active" : "";
                        $approvalDinas = $class == "dinas" ? "active" : "";
                        $approvalDirumahkan = $class == "dirumahkan" ? "active" : "";
                        $approvalAbsensiTakTerduga = $class == "absensitdkterjadwal" ? "active" : "";
                        $approvalAbsensiExtra = $class == "absensiextra" ? "active" : "";
                        // $approvalLogKabag = $class == "logkabag" ? "active" : "";
                        // $approvalLogPos = $class == "logpos" ? "active" : "";
                        // $approvalLogJadwal = $class == "logjadwal" ? "active" : "";
                    }
                ?>

                <!-- Approval HRD -->

                <li class="pcoded-hasmenu <?php echo $approvalActive; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-check-box"></i></span>
                        <span class="pcoded-mtext" >Approval HRD</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo $approvalCuti; ?>">
                            <a href="<?php echo site_url('approval/cuti'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" > Cuti</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $approvalSakit; ?>">
                            <a href="<?php echo site_url('approval/sakit'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext"> Sakit</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $approvalIzin; ?>">
                            <a href="<?php echo site_url('approval/izin'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" > Izin</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $approvalDinas; ?>">
                            <a href="<?php echo site_url('approval/dinas'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" > Perjalanan Dinas</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $approvalDirumahkan; ?>">
                            <a href="<?php echo site_url('approval/dirumahkan'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" > Dirumahkan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $approvalAbsensiTakTerduga; ?>">
                            <a href="<?php echo site_url('approval/absensitdkterjadwal'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" > Absensi Tidak Terjadwal</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $approvalAbsensiExtra; ?>">
                            <a href="<?php echo site_url('approval/absensiextra'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" > Absensi Lembur</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                      <!--   <li class="<?php //echo $approvalLogKabag; ?>">
                            <a href="<?php //echo site_url(''); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" > Log Kabag</span>
                            </a>
                        </li> -->

                        <!-- <li class="<?php //echo $approvalLogJadwal; ?>">
                            <a href="<?php //echo site_url(''); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Approval Absensi Pengganti</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li> -->
                    </ul>
                </li>
            <?php } ?>



                <?php
                    /*for Reward link active*/
                    if ($this->user_level == "hrd" || $this->user_level == "admin") {


                    $rwdActive = "";
                    $rwdRewards = "";
                    $rwdPunish = "";
                    if ($uri1 == "rwd" ) {
                        $rwdActive = "pcoded-trigger active";
                        $rwdRewards = $class == "reward" ? "active" : "";
                        $rwdPunish = $class == "punishment" ? "active" : "";
                    }
                ?>


               <!-- Rewards & Punishment -->

                        <li class="pcoded-hasmenu <?php echo $rwdActive; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-gift"></i></span>
                        <span class="pcoded-mtext" >Rewards & Punishment </span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo $rwdRewards; ?>">
                            <a href="<?php echo site_url('rwd/reward'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Rewards</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $rwdPunish; ?>">
                            <a href="<?php echo site_url('rwd/punishment'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Punishment</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                </li>
                 <?php } ?>

                <?php
                    /*for master link active*/

                    $statistikActive = "";
                    $statistikCuti = "";
                    $statistikIzin = "";
                    $statistikSakit = "";
                    $statistikPinjaman = "";
                    $statistikPenggajian = "";
                    $statistikPenilaian = "";
                    $statistikBpjs = "";
                    if ($uri1 == "statistik" ) {
                        $statistikActive = "pcoded-trigger active";
                        $statistikCuti = $class == "cuti" ? "active" : "";
                        $statistikIzin = $class == "izin" ? "active" : "";
                        $statistikSakit = $class == "sakit" ? "active" : "";
                        $statistikPinjaman = $class == "pinjaman" ? "active" : "";
                        $statistikPenggajian = $class == "penggajian" ? "active" : "";
                        $statistikPenilaian = $class == "penilaian" ? "active" : "";
                        $statistikBpjs = $class == "bpjs" ? "active" : "";
                    }
                ?>


                <!-- Statistik & Laporan -->

                <li class="pcoded-hasmenu <?php echo $statistikActive; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-clipboard"></i></span>
                        <span class="pcoded-mtext" > Laporan </span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo $statistikPenilaian; ?>">
                            <a href="<?php echo site_url('statistik/penilaian'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan Penilaian </span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $statistikBpjs; ?>">
                            <a href="<?php echo site_url('statistik/bpjs'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan BPJS </span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $statistikCuti; ?>">
                            <a href="<?php echo site_url('statistik/cuti'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan Cuti Karyawan </span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $statistikIzin; ?>">
                            <a href="<?php echo site_url('statistik/izin'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan Izin Karyawan </span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $statistikSakit; ?>">
                            <a href="<?php echo site_url('statistik/sakit'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan Sakit Karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $statistikPinjaman; ?>">
                            <a href="<?php echo site_url('statistik/pinjaman'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan Pinjaman karyawan</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="<?php echo $statistikPenggajian; ?>">
                            <a href="<?php echo site_url('statistik/penggajian'); ?>">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" >Laporan Penggajian</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php $usersActive = $class == "users" ? "active" : ""; ?>
                <li class=" <?php echo $usersActive; ?>">
                    <a href="<?php echo site_url('users');?>" data-i18n="nav.widget.main">
                        <span class="pcoded-micon"><i class="ti-user"></i></span>
                        <span class="pcoded-mtext">Pengguna</span>
                        <!-- <span class="pcoded-badge label label-danger">100+</span> -->
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <?php $usersActive = $class == "calendar" ? "active" : ""; ?>
                <li class=" <?php echo $usersActive; ?>">
                    <a href="<?php echo site_url('calendar');?>" data-i18n="nav.widget.main">
                        <span class="pcoded-micon"><i class="ti-calendar"></i></span>
                        <span class="pcoded-mtext">Calendar</span>
                        <!-- <span class="pcoded-badge label label-danger">100+</span> -->
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <?php $usersActive = $class == "setting" ? "active" : ""; ?>
                <li class=" <?php echo $usersActive; ?>">
                    <a href="<?php echo site_url('setting');?>" data-i18n="nav.widget.main">
                        <span class="pcoded-micon"><i class="fa fa-wrench"></i></span>
                        <span class="pcoded-mtext">Settings</span>
                        <!-- <span class="pcoded-badge label label-danger">100+</span> -->
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
            </ul>
            <div class="pcoded-navigatio-lavel " data-i18n="nav.category.navigation" menu-title-theme="theme2">Support</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo $class == 'faq' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('faq'); ?>">
                        <span class="pcoded-micon"><i class="ti-info-alt"></i></span>
                        <span class="pcoded-mtext">FAQ</span>
                        <!-- <span class="pcoded-badge label label-danger">100+</span> -->
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
            </ul>
            <div class="pcoded-navigatio-lavel" data-i18n="nav.category.support" >End Menu</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
        </div>
    </nav>
