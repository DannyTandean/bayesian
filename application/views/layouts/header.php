<!DOCTYPE html>
<html lang="en">

<head>
    <?php
        if (!isset($page_title) || $page_title == "") {
            $page_title = "Empty Header Title";
        }
    ?>
    <title><?php echo $page_title; ?></title>
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
    <link rel="icon" href="<?php echo base_url(); ?>assets/images/poi.png" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Mada:300,400,500,600,700" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/css/bootstrap.min.css">
    <!-- Sweetalert2 -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/sweetalert2/dist/sweetalert2.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icon/font-awesome/css/font-awesome.min.css">
    <!-- themify icon -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icon/themify-icons/themify-icons.css">

    <!-- list css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/list-scroll/list.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/stroll/css/stroll.css">

    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icon/icofont/css/icofont.css">
    <!-- flag icon framework css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/flag-icon/flag-icon.min.css">
    <!--SVG Icons Animated-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/icon/SVG-animated/svg-weather.css">
    <!-- Menu-Search css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/menu-search/css/component.css">
    <!-- Select 2 css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/css/select2.min.css" />

    <!-- light-box css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/ekko-lightbox/css/ekko-lightbox.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/lightbox2/css/lightbox.css">

    <!-- C3 chart -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/c3/css/c3.css" type="text/css" media="all">
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/data-table/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/data-table/extensions/responsive/css/responsive.dataTables.css"> -->

    <!-- Horizontal-Timeline css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/dashboard/horizontal-timeline/css/style.css">
    <!-- amchart css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/dashboard/amchart/css/amchart.css">
    <!-- Calender css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/widget/calender/pignose.calendar.min.css">
    <!-- flag icon framework css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/flag-icon/flag-icon.min.css">

    <!--sticky Css-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/sticky/css/jquery.postitall.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/sticky/css/trumbowyg.css" type="text/css" media="all">

    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/pages/advance-elements/css/bootstrap-datetimepicker.css">
    <!-- Date-range picker css  -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/css/daterangepicker.css">
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/datedropper/css/datedropper.min.css">
    <!-- Color Picker css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/spectrum/css/spectrum.css">
      <!-- Select 2 css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/css/select2.min.css">
    <!-- mutli select -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/multiselect/css/multi-select.css" />
    <!-- form masking -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/jquery.steps/css/jquery.steps.css">

    <!-- clock picker css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-clockpicker.min.css">
    <?php assets_css_custom("general.css"); ?>

    <style type="text/css">
        .swal2-popup {
          font-size: 1rem !important;
        }
        .swal2-container {
          z-index: 10000;
        }
        div.dt-button-collection>a.dt-button.active {
        background: #f6f6f6 !important;
        box-shadow: none !important;
        border: 0 !important;
        }

        div.dt-button-collection>a.dt-button.active>span {
          color: #333;
        }
        .bootstrap-datetimepicker-widget.dropdown-menu{
            z-index: 1050 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
          background-color: #0073AA;
        }
        /* ::-webkit-input-placeholder {
           text-align: center;
        }

        :-moz-placeholder {
           text-align: center;
        }

        ::-moz-placeholder {
           text-align: center;
        }

        :-ms-input-placeholder {
           text-align: center;
        } */
    </style>

    <style type="text/css">
        /**  =====================
          Sticky css start
          ==========================  **/
          /*add Button css Start*/

          .pulse-ring {
            content: '';
            width: 140px;
            height: 55px;
            border: 20px solid #1b8bf9;
            position: absolute;
            top: 18px;
            left: 18px;
            background-color: #1b8bf9;
            animation: pulsate infinite 1.5s;
        }

        @-webkit-keyframes pulsate {
            0% {
                -moz-transform: scale(0);
                opacity: 0.0;
            }
            25% {
                -moz-transform: scale(0);
                opacity: 0.1;
            }
            50% {
                -moz-transform: scale(0.1);
                opacity: 0.3;
            }
            75% {
                -moz-transform: scale(0.5);
                opacity: 0.5;
            }
            100% {
                -moz-transform: scale(1);
                opacity: 0.0;
            }
        }

        @-moz-keyframes pulsate {
            0% {
                -moz-transform: scale(0);
                opacity: 0.0;
            }
            25% {
                -moz-transform: scale(0);
                opacity: 0.1;
            }
            50% {
                -moz-transform: scale(0.1);
                opacity: 0.3;
            }
            75% {
                -moz-transform: scale(0.5);
                opacity: 0.5;
            }
            100% {
                -moz-transform: scale(1);
                opacity: 0.0;
            }
        }


        /*====== Sticky End ======*/
    </style>


    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/linearicons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/simple-line-icons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ionicons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/css/btn-outline-custome.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/jquery/js/jquery.min.js"></script>
    <!-- Select 2 js -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/select2/js/select2.full.min.js"></script>
    <!-- Jquery.Printarea -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.Printarea.js"></script>
    <!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/jquery-printme.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/html2canvas.min.js"></script>

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

            <!-- for menu head -->
            <?php $this->load->view('layouts/menu_head'); ?>
            <!-- end for menu head -->

            <!-- Sidebar inner chat end-->
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">

                    <!-- for menu side -->
                    <?php $this->load->view('layouts/menu_side'); ?>
                    <!-- end for menu side -->

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="card borderless-card">
                                        <div class="card-block">
                                            <!-- <h5>Simple Breadcrumb</h5> -->
                                             <?php
                                                if (!isset($header_title) || $header_title == "") {
                                                  $header_title = "Empty Header Title";
                                                }

                                                if (!isset($small_title)) {
                                                    $small_title = "";
                                                }
                                             ?>

                                            <h5>
                                                <?php echo $header_title; ?>

                                                <?php if($small_title != "") : ?>
                                                    <small> <i class="fa fa-chevron-right"></i>
                                                        <?php echo $small_title; ?>
                                                    </small>
                                                <?php endif ?>
                                            </h5>

                                            <div class="page-header-breadcrumb">
                                                <ul class="breadcrumb-title">
                                                    <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
                                                    <?php
                                                        if (isset($breadcrumbs)) {
                                                            if (is_array($breadcrumbs)) {
                                                                foreach ($breadcrumbs as $key => $value) {
                                                    ?>
                                                                    <li class="breadcrumb-item <?php echo $value == '' ? 'active' : ''; ?>">
                                                                        <?php if ($value != "") { ?>
                                                                            <a href="<?php echo $value; ?>"><?php echo $key; ?></a>
                                                                        <?php } else { ?>
                                                                            <?php echo $key; ?>
                                                                        <?php } ?>
                                                                    </li>
                                                          <?php
                                                                }
                                                            } else {
                                                          ?>
                                                                <li class="breadcrumb-item active"><?php echo $breadcrumbs; ?></li>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="page-body">
                                        <div class="row">
                                            <!-- <div class="col-md-12"> -->
