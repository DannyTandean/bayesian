
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

                  <li class="<?php echo $aktivitasPenggajianPayment; ?>">
                      <a href="<?php echo site_url('aktivitas/penggajian_payment') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Daftar Transaksi</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasThr; ?>">
                      <a href="<?php echo site_url('aktivitas/manage_produk') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Management Produk</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasJadwalkaryawan; ?>">
                      <a href="<?php echo site_url('aktivitas/jadwalkaryawan') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Deteksi Transaksi</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasPenilaian; ?>">
                      <a href="<?php echo site_url('aktivitas/penilaian') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Management IP</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasSakit; ?>">
                      <a href="<?php echo site_url('aktivitas/sakit') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Laporan Pengguna</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasIzin; ?>">
                      <a href="<?php echo site_url('aktivitas/manage_user'); ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Management User</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasDinas; ?>">
                      <a href="<?php echo site_url('aktivitas/dinas'); ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Logout</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
            </ul>
            <div class="pcoded-navigatio-lavel " data-i18n="nav.category.navigation" menu-title-theme="theme2">Support</div>

            <div class="pcoded-navigatio-lavel" data-i18n="nav.category.support" >End Menu</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
            <div class="">&nbsp;</div>
        </div>
    </nav>
