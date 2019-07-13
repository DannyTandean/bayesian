
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


               <?php
                    /*for Activites link active
                        act = activites */
                    $aktivitasActive = "";
                    $aktivitasTransaction = "";
                    $aktivitasDeteksi = "";
                    $aktivitasLaporan = "";
                    $aktivitasUser = "";
                    $aktivitasDinas = "";
                    $aktivitasIp = "";
                    $aktivitasProduk = "";
                    if ($uri1 == "aktivitas" ) {
                        $aktivitasActive = "pcoded-trigger active";
                        $aktivitasDeteksi = $class == "detection" ? "active" : "";
                        $aktivitasTransaction = $class == "transaction" ? "active" : "";
                        $aktivitasProduk = $class == "manage_produk" ? "active" : "";
                        $aktivitasLaporan = $class == "report" ? "active" : "";
                        $aktivitasUser = $class == "manage_user" ? "active" : "";
                        $aktivitasIp = $class == "manage_ip" ? "active" : "";
                    }
                ?>

                <!-- Activities -->
                  <li class="<?php echo $aktivitasTransaction; ?>">
                      <a href="<?php echo site_url('aktivitas/transaction') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Daftar Transaksi</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasDeteksi; ?>">
                      <a href="<?php echo site_url('aktivitas/detection') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Deteksi Transaksi</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasProduk; ?>">
                      <a href="<?php echo site_url('aktivitas/manage_produk') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Manajemen Produk</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasUser; ?>">
                      <a href="<?php echo site_url('aktivitas/manage_user'); ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Manajemen User</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasIp; ?>">
                      <a href="<?php echo site_url('aktivitas/manage_ip') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Manajemen IP</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasLaporan; ?>">
                      <a href="<?php echo site_url('aktivitas/report') ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Laporan Pengguna</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="<?php echo $aktivitasDinas; ?>">
                      <a href="<?php echo site_url('auth/logout'); ?>">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext" >Logout</span>
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
