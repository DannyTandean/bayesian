<!DOCTYPE html>
<html lang="en">

<head>
    <title>e-Absensi | Sistem Manajemen Absensi</title>
    <!-- HTML5 Shim and Respond.js IE9 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <!-- Meta -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
      <meta name="description" content="#">
      <meta name="keywords" content="flat ui, Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
      <meta name="author" content="#">
      <!-- Favicon icon -->
      <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
      <!-- Google font-->
      <link href="https://fonts.googleapis.com/css?family=Mada:300,400,500,600,700" rel="stylesheet">
      <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
     <!-- Google font-->
     <link href="https://fonts.googleapis.com/css?family=Mada:300,400,500,600,700" rel="stylesheet">
     <!-- Required Fremwork -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/css/bootstrap.min.css">
     <!-- sweet alert framework -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/sweetalert/css/sweetalert.css">
     <!-- themify-icons line icon -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icon/themify-icons/themify-icons.css">
     <!-- ico font -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icon/icofont/css/icofont.css">
     <!-- flag icon framework css -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/flag-icon/flag-icon.min.css">
     <!-- Menu-Search css -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/menu-search/css/component.css">
     <!-- animation nifty modal window effects css -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/component.css">
     <!-- Style.css -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
     <!--color css-->

     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/linearicons.css" >
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/simple-line-icons.css">
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ionicons.css">
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.mCustomScrollbar.css">
     <!-- Data Table Css -->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/data-table/css/buttons.dataTables.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
     <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/datatables/datatables.min.css"> -->
  </head>

  <body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div></div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <!-- Menu header start -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header" >
                <div class="navbar-wrapper">
                    <div class="navbar-logo" data-navbar-theme="theme4">
                        <a class="mobile-menu" id="mobile-collapse" href="#!">
                            <i class="ti-menu"></i>
                        </a>
                        <a class="mobile-search morphsearch-search" href="#">
                            <i class="ti-search"></i>
                        </a>
                        <a href="index.html">
                            <img class="img-fluid" src="<?php echo base_url(); ?>assets/images/logo.png" alt="Theme-Logo" />
                        </a>
                        <a class="mobile-options">
                            <i class="ti-more"></i>
                        </a>
                    </div>
                    <div class="navbar-container container-fluid">
                        <div>
                            <ul class="nav-left">
                                <li>
                                    <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                                </li>
                                <li>
                                    <a class="main-search morphsearch-search" href="#">
                                        <!-- themify icon -->
                                        <i class="ti-search"></i>
                                    </a>
                                </li>
                             </li>
                            </ul>
                            <ul class="nav-right">
                                <li class="header-notification lng-dropdown">
                                    <a href="#" id="dropdown-active-item">
                                        <i class="flag-icon flag-icon-gb m-r-5"></i> English
                                    </a>
                                    <ul class="show-notification">
                                        <li>
                                            <a href="#" data-lng="en">
                                                <i class="flag-icon flag-icon-gb m-r-5"></i> English
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-lng="es">
                                                <i class="flag-icon flag-icon-es m-r-5"></i> Spanish
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-lng="pt">
                                                <i class="flag-icon flag-icon-pt m-r-5"></i> Portuguese
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-lng="fr">
                                                <i class="flag-icon flag-icon-fr m-r-5"></i> French
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="header-notification">
                                    <a href="#!">
                                        <i class="ti-bell"></i>
                                        <span class="badge">5</span>
                                    </a>
                                    <ul class="show-notification">
                                        <li>
                                            <h6>Notifications</h6>
                                            <label class="label label-danger">New</label>
                                        </li>
                                        <li>
                                            <div class="media">
                                                <img class="d-flex align-self-center" src="<?php echo base_url(); ?>assets/images/user.png" alt="Generic placeholder image">
                                                <div class="media-body">
                                                    <h5 class="notification-user">John Doe</h5>
                                                    <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                                    <span class="notification-time">30 minutes ago</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="media">
                                                <img class="d-flex align-self-center" src="<?php echo base_url(); ?>assets/images/user.png" alt="Generic placeholder image">
                                                <div class="media-body">
                                                    <h5 class="notification-user">Joseph William</h5>
                                                    <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                                    <span class="notification-time">30 minutes ago</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="media">
                                                <img class="d-flex align-self-center" src="<?php echo base_url(); ?>assets/images/user.png" alt="Generic placeholder image">
                                                <div class="media-body">
                                                    <h5 class="notification-user">Sara Soudein</h5>
                                                    <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                                    <span class="notification-time">30 minutes ago</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="user-profile header-notification">
                                    <a href="#!">
                                        <img src="<?php echo base_url(); ?>assets/images/user.png" alt="User-Profile-Image">
                                        <span>John Doe</span>
                                        <i class="ti-angle-down"></i>
                                    </a>
                                    <ul class="show-notification profile-notification">
                                        <li>
                                            <a href="#!">
                                                <i class="ti-settings"></i> Settings
                                            </a>
                                        </li>
                                        <li>
                                            <a href="user-profile.html">
                                                <i class="ti-user"></i> Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a href="email-inbox.html">
                                                <i class="ti-email"></i> My Messages
                                            </a>
                                        </li>
                                        <li>
                                            <a href="auth-lock-screen.html">
                                                <i class="ti-lock"></i> Lock Screen
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#!">
                                                <i class="ti-layout-sidebar-left"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <!-- search -->
                            <div id="morphsearch" class="morphsearch">
                                <form class="morphsearch-form">
                                    <input class="morphsearch-input" type="search" placeholder="Search..." />
                                    <button class="morphsearch-submit" type="submit">Search</button>
                                </form>
                                <div class="morphsearch-content">
                                    <div class="dummy-column">
                                        <h2>People</h2>
                                        <a class="dummy-media-object" href="#!">
                                            <img class="round" src="http://0.gravatar.com/avatar/81b58502541f9445253f30497e53c280?s=50&d=identicon&r=G" alt="Sara Soueidan" />
                                            <h3>Sara Soueidan</h3>
                                        </a>
                                        <a class="dummy-media-object" href="#!">
                                            <img class="round" src="http://1.gravatar.com/avatar/9bc7250110c667cd35c0826059b81b75?s=50&d=identicon&r=G" alt="Shaun Dona" />
                                            <h3>Shaun Dona</h3>
                                        </a>
                                    </div>
                                    <div class="dummy-column">
                                        <h2>Popular</h2>
                                        <a class="dummy-media-object" href="#!">
                                            <img src="<?php echo base_url(); ?>assets/images/avatar-1.png" alt="PagePreloadingEffect" />
                                            <h3>Page Preloading Effect</h3>
                                        </a>
                                        <a class="dummy-media-object" href="#!">
                                            <img src="<?php echo base_url(); ?>assets/images/avatar-1.png" alt="DraggableDualViewSlideshow" />
                                            <h3>Draggable Dual-View Slideshow</h3>
                                        </a>
                                    </div>
                                    <div class="dummy-column">
                                        <h2>Recent</h2>
                                        <a class="dummy-media-object" href="#!">
                                            <img src="<?php echo base_url(); ?>assets/images/avatar-1.png" alt="TooltipStylesInspiration" />
                                            <h3>Tooltip Styles Inspiration</h3>
                                        </a>
                                        <a class="dummy-media-object" href="#!">
                                            <img src="<?php echo base_url(); ?>assets/images/avatar-1.png" alt="NotificationStyles" />
                                            <h3>Notification Styles Inspiration</h3>
                                        </a>
                                    </div>
                                </div>
                                <!-- /morphsearch-content -->
                                <span class="morphsearch-close"><i class="icofont icofont-search-alt-1"></i></span>
                            </div>
                            <!-- search end -->
                        </div>
                    </div>
                </div>
            </nav>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <nav class="pcoded-navbar" >
                        <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                        <div class="pcoded-inner-navbar main-menu">

<!--                             <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation" >Navigation</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="pcoded-hasmenu active pcoded-trigger">
                                    <a href="index.html">
                                        <span class="pcoded-micon"><i class="ti-home"></i></span>
                                        <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            </ul> -->
                             <div class="pcoded-navigatio-lavel" data-i18n="nav.category.support" >Home</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="">
                                    <a href="index.html" data-i18n="nav.category.navigation">
                                        <span class="pcoded-micon"><i class="ti-home"></i></span>
                                        <span class="pcoded-mtext">Dashboard</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                
                            </ul>
                            <div class="pcoded-navigatio-lavel" data-i18n="nav.category.tables" >Tables</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="pcoded-hasmenu">
                                    <a href="javascript:void(0)" data-i18n="nav.data-table.main">
                                        <span class="pcoded-micon"><i class="ti-server"></i></span>
                                        <span class="pcoded-mtext">Master Data</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Data Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Data Departemen</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Data Jabatan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Data Golongan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Data Shift</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Data Bank</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Data Grup</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="pcoded-hasmenu">
                                    <a href="javascript:void(0)" data-i18n="nav.data-table-extensions.main">
                                        <span class="pcoded-micon"><i class="ti-loop"></i></span>
                                        <span class="pcoded-mtext">Aktivitas</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Penggajian</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Slip Gaji</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Sakit Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Izin Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Cuti Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Perjalanan Dinas</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Dirumahkan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Pinjaman Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Off Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Promosi-Demosi-Mutasi</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Pengumuman & Berita</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Log Absensi Pos</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Log Absensi Kabag</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="pcoded-hasmenu ">
                                    <a href="javascript:void(0)" data-i18n="nav.user-profile.main">
                                        <span class="pcoded-micon"><i class="ti-stamp"></i></span>
                                        <span class="pcoded-mtext">Release HRD</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>

                                    <ul class="pcoded-submenu">
                                        <li class="">
                                            <a href="task-list.html" data-i18n="nav.task.task-list">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Release Cuti</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="task-list.html" data-i18n="nav.task.task-list">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Release Izin</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                  </li>
                                  <li class="pcoded-hasmenu ">
                                    <a href="javascript:void(0)" data-i18n="nav.user-profile.main">
                                        <span class="pcoded-micon"><i class="ti-gift"></i></span>
                                        <span class="pcoded-mtext">Rewards & Punishment</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>

                                    <ul class="pcoded-submenu">
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Rewards</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                         <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Punishment</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                  </li>
                                   <li class="pcoded-hasmenu ">
                                    <a href="javascript:void(0)" data-i18n="nav.user-profile.main">
                                        <span class="pcoded-micon"><i class="ti-bar-chart-alt"></i></span>
                                        <span class="pcoded-mtext">Statistik & Laporan</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Laporan Cuti Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                         <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Laporan Izin Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Laporan Sakit Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Laporan Pinjaman Karyawan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Laporan Penggajian</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                  </li>
                                  <li class="pcoded-hasmenu ">
                                    <a href="javascript:void(0)" data-i18n="nav.user-profile.main">
                                        <span class="pcoded-micon"><i class="ti-plus"></i></span>
                                        <span class="pcoded-mtext"> Penambahan Pengguna </span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="">
                                            <a href="task-list.html" data-i18n="nav.task.task-list">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext"> Pengaturan Pengguna </span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                  </li>
                                  <li class="pcoded-hasmenu ">
                                    <a href="javascript:void(0)" data-i18n="nav.user-profile.main">
                                        <span class="pcoded-micon"><i class="ti-settings"></i></span>
                                        <span class="pcoded-mtext">Setup & Maintenance</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                     <ul class="pcoded-submenu">
                                        <li class=" ">
                                            <a href="dt-server-side.html" data-i18n="nav.data-table.server-side">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Setup Password</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="event-full-calender.html" data-i18n="nav.full-calendar.full-calendar">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Setup Kalender</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>  
                                </li>
                            </ul>
                            </li>                            
                            <div class="pcoded-navigatio-lavel" data-i18n="nav.category.support" >Support</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="">
                                    <a href="#" data-i18n="nav.documentation.main" target="_blank">
                                        <span class="pcoded-micon"><i class="ti-file"></i></span>
                                        <span class="pcoded-mtext">FAQ</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>                                
                            </ul>
                        </div>
                    </nav>
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-header">
                                        <div class="page-header-title">
                                            <h4>Dashboard</h4>
                                        </div>
                                        <div class="page-header-breadcrumb">
                                            <ul class="breadcrumb-title">
                                                <li class="breadcrumb-item">
                                                    <a href="index.html">
                                                        <i class="icofont icofont-home"></i>
                                                    </a>
                                                </li>
                                                <li class="breadcrumb-item"><a href="#!">Pages</a>
                                                </li>
                                                <li class="breadcrumb-item"><a href="#!">Dashboard</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                              <!-- Server Side Processing table start -->
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>List data Grup</h5>
                                                        <span>server side processing</span>
                                                        <div class="card-header-right">
                                                            <i class="icofont icofont-rounded-down"></i>
                                                            <i class="icofont icofont-refresh"></i>
                                                            <i class="icofont icofont-close-circled"></i>
                                                        </div>
                                                            <button type="button" class="btn btn-primary btn-outline-primary waves-effect md-trigger float-right" data-modal="addMember" onclick="addMemberModel()">Add</button>
                                                    </div>
                                                    <div class="messages"></div>
                                                    <div class="card-block">
                                                        <div class="dt-responsive table-responsive">
                                                            <table id="dt-server-processing" class="table table-striped table-bordered nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Name Grup</th>
                                                                        <th>Keterangan</th>
                                                                        <th>Edit</th>
                                                                        <th>Remove</th>
                                                                    </tr>
                                                                </thead>
                                                                <tfoot>
                                                                    <tr>
                                                                      <th>No</th>
                                                                      <th>Name Grup</th>
                                                                      <th>Keterangan</th>
                                                                      <th>Edit</th>
                                                                      <th>Remove</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    <div class="md-modal md-effect-8" id="addMember">
                                                      <div class="md-content">
                                                          <h3>Add Grup</h3>
                                                          <div>
                                                            <!-- form data -->
                                                              <form action="crud_grup/create" id="createForm" method="post">
                                                                <div class="form-group">
                                                                  <label for="namagrup">Nama Grup</label>
                                                                  <input type="text" name="namagrup"  class="form-control" placeholder="Nama Grup" id="namagrup" required>
                                                                </div>
                                                                <div class="form-group">
                                                                  <label for="Ket">Keterangan</label>
                                                                  <textarea name="keterangan" rows="8" cols="80" placeholder="Keterangan" id="Ket"></textarea>
                                                                </div>
                                                                <div class="row">
                                                                  <div class="col-md-6">
                                                                      <button type="button" class="btn btn-primary waves-effect md-close">Close</button>
                                                                  </div>
                                                                  <div class="col-md-6">
                                                                      <button type="submit" name="addgrup" class="btn btn-success waves-effect md-close">Add</button>
                                                                  </div>
                                                                </div>
                                                              </form>
                                                              <!-- end of form data -->
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <!-- Edit Modal -->
                                                  <div class="modal fade" tabindex="-1" role="dialog" id="editMemberModal">
                                                  <div class="modal-dialog" role="dialog">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h4 class="modal-title">Edit Grup</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                      </div>
                                                      <!-- form data -->
                                                      <form action="crud_grup/edit" id="editForm" method="post">
                                                          <div class="modal-body">
                                                              <div class="form-group">
                                                                <label for="editNamagrup">Nama Grup</label>
                                                                <input type="text" name="editnamagrup"  class="form-control" placeholder="Nama Grup" id="editNamagrup" required>
                                                              </div>
                                                              <div class="form-group">
                                                                <label for="editKet">Keterangan</label>
                                                                <textarea name="editKet" rows="8" cols="20" placeholder="Keterangan" id="editKet" class="form-control"></textarea>
                                                              </div>
                                                              <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-success waves-effect">Save changes</button>
                                                              </div>
                                                            </div>
                                                      </form>
                                                            <!-- end of form data -->
                                                        </div>
                                                    </div>
                                                </div>
                                                  <!-- End of Edit Modal -->
                                                  <!-- Remove Modal -->
                                                  <div class="md-modal md-effect-3" id="removeMemberModal">
                                                    <div class="md-content">
                                                        <h3>Remove Grup</h3>
                                                        <div>
                                                          do you wan to remove this data ?
                                                        </div>
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                              <button type="button" class="btn btn-primary waves-effect md-close">Close</button>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <button type="submit" name="addgrup" class="btn btn-success waves-effect" id="removeMemberBtn">remove</button>
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                  <!-- End of Remove Modal -->
                                              </div>
                                                </div>
                                            </div>
                                            <!-- Product table End -->

<!-- Warning Section Ends -->
<!-- Required Jquery -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/jquery/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/bootstrap/js/bootstrap.min.js"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
<!-- modernizr js -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/modernizr/js/modernizr.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/modernizr/js/css-scrollbars.js"></script>
<!-- classie js -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/classie/js/classie.js"></script>
<!-- sweet alert js -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/sweetalert/js/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/modal.js"></script>
<!-- sweet alert modal.js intialize js -->
<!-- modalEffects js nifty modal window effects -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/modalEffects.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/classie.js"></script>
<!-- i18next.min.js -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/i18next/js/i18next.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
<!-- Custom js -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/script.js"></script>
<script src="<?php echo base_url(); ?>assets/js/pcoded.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/demo-12.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.mousewheel.min.js"></script>
<!-- datatables -->
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/datatables/datatables.min.js"></script> -->
<!-- data-table js -->
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/pages/data-table/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/pages/data-table/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/pages/data-table/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<?php assets_script("crud_grup.js"); ?>
</body>

</html>
