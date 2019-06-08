    <nav class="navbar header-navbar pcoded-header" >
        <div class="navbar-wrapper">
            <div class="navbar-logo" data-navbar-theme="theme4">
                <a class="mobile-menu" id="mobile-collapse" href="#!">
                    <i class="ti-menu"></i>
                </a>
                <a class="mobile-search morphsearch-search" href="#">
                    <i class="ti-search"></i>
                </a>
                <div>
                <a href="<?php base_url();?>">
                    <img id="profileCompany" height="50" width="50" style="margin-left : -10px;" src="<?php echo base_url(); ?>assets/images/logo.png" alt="Theme-Logo" />
                </a>
                <a href="#"><span id="namaPerusahaan1" style="font-size : 13px;"></span></a>
                </div>
                <a class="mobile-options">
                    <i class="ti-more"></i>
                </a>
                <script type="text/javascript">
                    var base_url = '<?php echo base_url(); ?>';
                    var user_id = '<?php echo $this->user->id_user_admin; ?>';
                </script>
            </div>
            <div class="navbar-container container-fluid">
                <div>
                    <ul class="nav-left">
                        <li>
                            <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                        </li>
                        <!-- <li>
                            <a class="main-search morphsearch-search" href="#">
                                themify icon
                                <i class="ti-search"></i>
                            </a>
                        </li> -->
                        <li>
                            <a href="#!" onclick="javascript:toggleFullScreen()">
                                <i class="ti-fullscreen"></i>
                            </a>
                        </li>

                    </ul>
                    <ul class="nav-right">
                        <!-- notif -->
                        <li class="header-notification">
                            <audio id="myAudio">
                              <source src="<?php echo base_url();?>assets/audio/notif_web.mp3" type="audio/mpeg">
                            </audio>

                            <a href="javascript:void(0)">
                                <i class="ti-bell"></i>
                                <span class="badge" id="badgeCount">0</span>
                            </a>
                            <!-- <ul class="show-notification" id="showNotif">
                                <li>
                                    <h6>Pemberitahuan</h6>
                                </li>
                                <div class="card card-block user-card" id="waveNotif" style="height:300px;overflow-y:scroll">
                                    <ul class="" id="contentNotif"></ul>
                                </div>
                                <li>
                                    <button type="button" id="bacaSemuanya" class="btn btn-sm btn-danger">Baca Semuanya</button>
                                    <button type="button" id="lihatSemuanya" class="btn btn-sm btn-primary pull-right">Lihat Selengkapnya</button>
                                </li>
                            </ul> -->
                        </li>

                        <!-- pemberitahuan -->
                        <!-- <script src="https://www.gstatic.com/firebasejs/5.3.1/firebase.js"></script>
                        <?php assets_script_custom("statistik/pemberitahuan.js"); ?> -->

                        <li class="user-profile header-notification">
                            <a href="javascript:void(0)">
                                <img id="profilePhotoUser" src="<?php echo base_url(); ?>assets/images/default/no_user.png" alt="User-Profile-Image">
                                <span>
                                    <?php echo $this->username; ?>
                                </span>
                                <i class="ti-angle-down"></i>
                            </a>
                            <ul class="show-notification profile-notification">
                                <!-- <li>
                                    <a href="#!">
                                        <i class="ti-settings"></i> Settings
                                    </a>
                                </li> -->
                                <li>
                                    <a href="<?php echo site_url('users');?>">
                                        <i class="ti-user"></i> Profile
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="email-inbox.html">
                                        <i class="ti-email"></i> My Messages
                                    </a>
                                </li>
                                <li>
                                    <a href="auth-lock-screen.html">
                                        <i class="ti-lock"></i> Lock Screen
                                    </a>
                                </li> -->
                                <li>
                                    <a href="<?php echo site_url('auth/logout'); ?>">
                                        <i class="ti-layout-sidebar-left"></i> Logout
                                    </a>
                                </li>
                            </ul>
                            <script type="text/javascript">
                                // photo user di menu head
                                // profilePhotoUserMenuHead();
                                //
                                // function profilePhotoUserMenuHead() {
                                //     $.post(base_url+"users/getId/"+user_id,function(json) {
                                //         if (json.status == true) {
                                //             $("#profilePhotoUser").attr('src',base_url+json.data.photo);
                                //         }
                                //     });
                                // }


                                //  profileCompanyMenuHead();
                                //
                                // function profileCompanyMenuHead(){
                                //     $.post(base_url+"setting/getId/1",function(json){
                                //         if(json.status == true){
                                //             $("#profileCompany").attr('src',json.data.logo);
                                //             $("#namaPerusahaan1").html(json.data.nama_perusahaan);
                                //
                                //         }
                                //     });
                                // }

                            </script>
                        </li>
                    </ul>
                    <!-- search -->
                    <!-- <div id="morphsearch" class="morphsearch">
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
                        <span class="morphsearch-close"><i class="icofont icofont-search-alt-1"></i></span>
                    </div> -->
                    <!-- search end -->
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar chat start -->
    <!-- <div id="sidebar" class="users p-chat-user showChat">
        <div class="had-container">
            <div class="card card_main p-fixed users-main">
                <div class="user-box">
                    <div class="card-block">
                        <div class="right-icon-control">
                            <input type="text" class="form-control  search-text" placeholder="Search Friend" id="search-friends">
                            <div class="form-icon">
                                <i class="icofont icofont-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="main-friend-list">
                        <div class="media userlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-1.png" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Josephin Doe</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="2" data-status="online" data-username="Lary Doe" data-toggle="tooltip" data-placement="left" title="Lary Doe">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/task/task-u1.jpg" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Lary Doe</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="3" data-status="online" data-username="Alice" data-toggle="tooltip" data-placement="left" title="Alice">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-2.png" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Alice</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="4" data-status="online" data-username="Alia" data-toggle="tooltip" data-placement="left" title="Alia">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/task/task-u2.jpg" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Alia</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="5" data-status="online" data-username="Suzen" data-toggle="tooltip" data-placement="left" title="Suzen">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/task/task-u3.jpg" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Suzen</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="6" data-status="offline" data-username="Michael Scofield" data-toggle="tooltip" data-placement="left" title="Michael Scofield">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-3.png" alt="Generic placeholder image">
                                <div class="live-status bg-danger"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Michael Scofield</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="7" data-status="online" data-username="Irina Shayk" data-toggle="tooltip" data-placement="left" title="Irina Shayk">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-4.png" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Irina Shayk</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="8" data-status="offline" data-username="Sara Tancredi" data-toggle="tooltip" data-placement="left" title="Sara Tancredi">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-5.png" alt="Generic placeholder image">
                                <div class="live-status bg-danger"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Sara Tancredi</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="9" data-status="online" data-username="Samon" data-toggle="tooltip" data-placement="left" title="Samon">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-1.png" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Samon</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="10" data-status="online" data-username="Daizy Mendize" data-toggle="tooltip" data-placement="left" title="Daizy Mendize">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/task/task-u3.jpg" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Daizy Mendize</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="11" data-status="offline" data-username="Loren Scofield" data-toggle="tooltip" data-placement="left" title="Loren Scofield">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-3.png" alt="Generic placeholder image">
                                <div class="live-status bg-danger"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Loren Scofield</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="12" data-status="online" data-username="Shayk" data-toggle="tooltip" data-placement="left" title="Shayk">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-4.png" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Shayk</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="13" data-status="offline" data-username="Sara" data-toggle="tooltip" data-placement="left" title="Sara">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/task/task-u3.jpg" alt="Generic placeholder image">
                                <div class="live-status bg-danger"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Sara</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="14" data-status="online" data-username="Doe" data-toggle="tooltip" data-placement="left" title="Doe">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/avatar-1.png" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Doe</div>
                            </div>
                        </div>
                        <div class="media userlist-box" data-id="15" data-status="online" data-username="Lary" data-toggle="tooltip" data-placement="left" title="Lary">
                            <a class="media-left" href="#!">
                                <img class="media-object img-circle" src="<?php echo base_url(); ?>assets/images/task/task-u1.jpg" alt="Generic placeholder image">
                                <div class="live-status bg-success"></div>
                            </a>
                            <div class="media-body">
                                <div class="f-13 chat-header">Lary</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Sidebar inner chat start-->
    <!-- <div class="showChat_inner">
        <div class="media chat-inner-header">
            <a class="back_chatBox">
                <i class="icofont icofont-rounded-left"></i> Josephin Doe
            </a>
        </div>
        <div class="media chat-messages">
            <a class="media-left photo-table" href="#!">
                <img class="media-object img-circle m-t-5" src="<?php echo base_url(); ?>assets/images/avatar-1.png" alt="Generic placeholder image">
            </a>
            <div class="media-body chat-menu-content">
                <div class="">
                    <p class="chat-cont">I'm just looking around. Will you tell me something about yourself?</p>
                    <p class="chat-time">8:20 a.m.</p>
                </div>
            </div>
        </div>
        <div class="media chat-messages">
            <div class="media-body chat-menu-reply">
                <div class="">
                    <p class="chat-cont">I'm just looking around. Will you tell me something about yourself?</p>
                    <p class="chat-time">8:20 a.m.</p>
                </div>
            </div>
            <div class="media-right photo-table">
                <a href="#!">
                    <img class="media-object img-circle m-t-5" src="<?php echo base_url(); ?>assets/images/avatar-2.png" alt="Generic placeholder image">
                </a>
            </div>
        </div>
        <div class="chat-reply-box p-b-20">
            <div class="right-icon-control">
                <input type="text" class="form-control search-text" placeholder="Share Your Thoughts">
                <div class="form-icon">
                    <i class="icofont icofont-paper-plane"></i>
                </div>
            </div>
        </div>
    </div> -->
