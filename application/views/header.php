<?php
if (isset($this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']) && !empty($this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in'])) {
    $logged_in_nickname = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['user_name'];
    $logged_in_email = $this->session->userdata(PACKAGE_FOLDER_NAME.'company_data')->account_name;
    $logged_in_id = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['user_id'];
    $user_type = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['user_type'];
//    $logged_in_profile_image = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['profile_image'];
}
$currUrl = $this->uri->segment(1);
if ($currUrl == '') {
    $currUrl = 'Dashboard';
}
?>
<?php
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
//$this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type'];
?>
<!DOCTYPE html>
<html>

    <head>
        <style>
            .dropdown-submenu{
                position: relative;
            }
            .dropdown-submenu .dropdown-menu {
                top: 0;
                left: 100%;
                margin-top: auto;
            }
            @media (max-width: 767px){
                .dropdown-menu>.active>a{
                    background-color: #00c0ef !important;
                    color: #ffffff !important;
                }
            }
        </style>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
            <?= ucwords($currUrl) ?> | <?php echo PACKAGE_NAME; ?> </title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="icon" href="<?= base_url(); ?>assets/dist/img/logo_favicon.png" sizes="32x32" />
        
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/ionicons.min.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/iCheck/all.css">
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/iCheck/minimal/blue.css">

        <!-- jvectormap -->
        <link rel="stylesheet" href="<?= base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css'); ?>">
        <!----------------Notify---------------->
        <link rel="stylesheet" href="<?= base_url('assets/plugins/notify/jquery.growl.css'); ?>">
        <!----------------Notify---------------->
        <!-- multiple-select -->
        <link rel="stylesheet" href="<?php echo base_url('assets/plugins/multiple-select/multiple-select.min.css'); ?>">
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                <![endif]-->

        <!-- bootstrap timepicker -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datepicker/datepicker3.css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css">
        <!-- bootstrap-year-calendar -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/bootstrap-year-calendar/bootstrap-year-calendar.min.css">
        <!-- jQuery 2.2.3 -->
        <link href="<?= base_url('assets/dist/css/newquote.css'); ?>" rel="stylesheet" type="text/css"/>

        <!--<script src="<?= base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>-->
        <script src="<?= base_url('assets/plugins/jQuery/jquery.min.js'); ?>"></script>
        <!-------- /Parsleyjs --------->
        <link href="<?= base_url('assets/plugins/parsleyjs/src/parsley.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- DataTables -->
        <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/media/css/jquery.dataTables.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/extensions/Scroller/css/scroller.dataTables.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css">
        <!-- select 2 -->
        <link rel="stylesheet" href="<?= base_url('assets/plugins/s2/select2.css'); ?>">
        <script src="<?= base_url('assets/plugins/s2/select2.full.js'); ?>"></script>
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css'); ?>">
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css'); ?>">
        <script src="<?= base_url() ?>assets/dist/js/sweetalert.min.js"></script>
        <link href="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/dist/css/custom.css?v=2'); ?>" rel="stylesheet" type="text/css" />
        <script src="<?= base_url('assets/dist/js/jquery.smartWizard.js'); ?>"></script>
            <script src="<?=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');?>"></script>
        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>

        <!-- multiple-select -->
        <script src="<?php echo base_url('assets/plugins/multiple-select/multiple-select.min.js'); ?>"></script>
        <!-- Latest compiled and minified JavaScript -->

        <?php
            if(isset($this->session->userdata()[PACKAGE_FOLDER_NAME . 'theme_color_code'])){
                $theme_color_code = $this->session->userdata()[PACKAGE_FOLDER_NAME . 'theme_color_code'];
            } else {
                $theme_color_code = '#3c8dbc';
            }
        ?>
        <style>
            .skin-blue .main-header .navbar {
                background-color: <?php echo $theme_color_code; ?>;
            }
            .skin-blue .main-header li.user-header {
                background-color: <?php echo $theme_color_code; ?>;
            }
            .skin-blue .wrapper,
            .skin-blue .main-sidebar,
            .skin-blue .left-side {
                background-color: <?php echo $theme_color_code; ?>;
            }
            .skin-blue .sidebar-menu>li:hover>a,
            .skin-blue .sidebar-menu>li.active>a {
                color: <?php echo $theme_color_code; ?>;
            }
            .digital_watch{
                background-color: <?php echo $theme_color_code; ?>;
            }
            .mega-menu-design>.active>a, .mega-menu-design>.active>a:focus, .mega-menu-design>.active>a:hover{
                background-color: <?php echo $theme_color_code; ?>;
            }
            <?php /*.btn.btn-primary{
                background-color: <?php echo $theme_color_code; ?>;
            }*/ ?>
        </style>
        
    </head>

    <body class="hold-transition skin-blue layout-top-nav">
    
    <div class="wrapper">
            <header class="main-header">

                <nav class="navbar navbar-static-top">
                    <div class="container" style="width: 100%;">
                        <div class="navbar-header">
                            <a href="<?php echo base_url(); ?>" class="navbar-brand" style="padding: 0px;">
                                <img src="<?php echo base_url(); ?>assets/dist/img/logo1.jpg" style="width: 160px; margin: 5px; box-shadow: 0px 2px 10px #000000; /*transform: scale(1.8);*/">
                            </a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                <?php
                                    $sell_purchase_type_2_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_2'));
                                    $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
                                    $inventory_data_modules_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'inventory_data_modules'));
                                    $sell_purchase_entry_with_gst_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_entry_with_gst'));
                                ?>
                                <!--<li class="<?= ($segment1 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>"><span> Dashboard</span></a></a></li>-->
                                <?php if($this->applib->have_access_role(MASTER_MODULE_ID,"view")) { ?>
                                <li class="dropdown <?= ($segment2 == 'category' || $segment2 == 'setting' || $segment2 == 'tunch' || $segment2 == 'item_master' || $segment2 == 'item_master_list' || $segment2 == 'user_master' || $segment2 == 'state' || $segment2 == 'city' || $segment2 == 'user_rights' || $segment2 == 'opening_stock' || $segment2 == 'design_master' || $segment2 == 'daybook' || $segment2 == 'feedback' || $segment2 == 'feedback_list' || $segment2 == 'yearly_leaves' || $segment2 == 'present_hours' || $segment2 == 'apply_leave' || $segment2 == 'department_attendance' || $segment2 == 'employee_salary' || $segment2 == 'ad_master' || $segment2 == 'stamp' || $segment2 == 'data_blank') ? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Master <span class="caret"></span></a>
                                    <!--<ul class="dropdown-menu" role="menu">-->
                                    <ul class="dropdown-menu mega-menu">
                                        <li class="mega-menu-column">
                                            <ul class="mega-menu-design" role="menu">
                                                <?php if($this->applib->have_access_role(COMPANY_DETAILS_MODULE_ID,"edit")) { ?>
                                                    <li class="<?= ($segment2 == 'company_details') ? 'active' : '' ?>"><a href="<?= base_url(); ?>company/company_details/"><i class="fa fa-list-alt"></i>Company Details</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(CATEGORY_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment2 == 'category') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/category/"><i class="fa fa-list-alt"></i>Category</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(DESIGN_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment2 == 'design_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/design_master/"><i class="fa fa-list-alt"></i>Design Master</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(OPENING_STOCK_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment2 == 'opening_stock') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/opening_stock/"><i class="fa fa-signal"></i>Opening Stock</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(TUNCH_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment2 == 'tunch') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/tunch/"><i class="fa fa-tags"></i>Tunch</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(ITEM_MASTER_MODULE_ID,"view")) { ?>
                                                    <?php if($this->applib->have_access_role(ITEM_MASTER_MODULE_ID,"add") || $this->applib->have_access_role(ITEM_MASTER_MODULE_ID,"edit")) { ?>
                                                    <li class="<?= ($segment1 == 'master' && $segment2 == 'item_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/item_master/"><i class="fa fa-plus"></i>Add Item</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(ITEM_MASTER_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'master' && $segment2 == 'item_master_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/item_master_list/"><i class="fa fa-list"></i>Item List</a></li>
                                                    <?php } ?>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li class="mega-menu-column">
                                            <ul class="mega-menu-design" role="menu">
                                                <?php if($this->applib->have_access_role(SETTING_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment2 == 'setting') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/setting/"><i class="fa fa-cog"></i>Method</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(AD_MASTER_ID,"view")) { ?>
                                                    <li class="<?= ($segment2 == 'ad_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/ad_master/"><i class="fa fa-list-alt"></i>AD Master</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(STAMP_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment2 == 'stamp') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/stamp/"><i class="fa fa-circle-o"></i>Stamp</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li class="mega-menu-column">
                                            <ul class="mega-menu-design" role="menu">
                                                <?php if($this->applib->have_access_role(USER_MASTER_MODULE_ID,"add") || $this->applib->have_access_role(USER_MASTER_MODULE_ID,"edit")) { ?>
                                                    <li class="<?= ($segment1 == 'master' && $segment2 == 'user_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/user_master/"><i class="fa fa-plus"></i>Add User</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(USER_MASTER_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'master' && $segment2 == 'user_master_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/user_master_list/"><i class="fa fa-list"></i>User List</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(USER_RIGHTS_MODULE_ID,"allow")) { ?>
                                                    <li class="<?= ($segment1 == 'master' && $segment2 == 'user_rights') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/user_rights/"><i class="fa fa-key"></i>User Rights</a></li>
                                                    <?php if($sell_purchase_type_3_menu == '1' ) { // Method : Sell/Purchase Type 3 Checkbox ?>
                                                        <li class="<?= ($segment1 == 'master' && $segment2 == 'data_blank') ? 'active' : '' ?>"><a href="<?= base_url(); ?>backup/data_blank/" onclick="return confirm('Are you sure, You want to blank all Data?!!!')"><i class="fa fa-square-o"></i>Data Blank</a></li>
                                                    <?php } ?>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li class="mega-menu-column">
                                            <ul class="mega-menu-design" role="menu">
                                                <?php if($this->applib->have_access_role(STATE_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'master' && $segment2 == 'state') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/state/"><i class="fa fa-flag"></i>State</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(CITY_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'master' && $segment2 == 'city') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/city/"><i class="fa fa-globe"></i>City</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(DEMO_MODULE_ID,"view")) { ?>
                                                <li class="dropdown-submenu <?= ($segment1 == 'feedback' || $segment2 == 'daybook' || $segment2 == 'feedback_list' ) ? 'active' : '' ?>">
                                                    <a href="#" class="item_master test" tabindex="-1"><i class="fa fa-bookmark"></i>Demo <span class="caret"></span></a>
                                                    <ul class="dropdown-menu item_master_m" role="menu">
                                                    <?php if($this->applib->have_access_role(DAYBOOK_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'reports' && $segment2 == 'daybook') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>reports/daybook/"><i class="fa fa-book"></i>Daybook</a></li>
                                                    <?php } ?>
                                                        <li class="<?= ($segment1 == 'feedback' && $segment2 == 'feedback') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>feedback/feedback/"><i class="fa fa-comment"></i>Feedback</a></li>
                                                        <li class="<?= ($segment1 == 'feedback' && $segment2 == 'feedback_list') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>feedback/feedback_list/"><i class="fa fa-comments"></i>Feedback_List</a></li>
                                                        <li class="<?= ($segment1 == 'whatsapp_demo' && $segment2 == 'test') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>whatsapp_demo/test/"><i class="fa fa-whatsapp"></i>WhatsApp</a></li>
                                                        <li class="<?= ($segment1 == 'twilio_whatsapp_demo' && $segment2 == 'test') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>twilio_whatsapp_demo/test/"><i class="fa fa-whatsapp"></i>Twilio WhatsApp</a></li>
                                                        <li class="<?= ($segment1 == 'twilio_whatsapp_demo' && $segment2 == 'whatsapp') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>twilio_whatsapp_demo/whatsapp/"><i class="fa fa-whatsapp"></i>Twilio WhatsApp 1</a></li>
                                                        <li class="<?= ($segment1 == 'kaleyra_whatsapp' && $segment2 == 'whatsapp') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>kaleyra_whatsapp/whatsapp/"><i class="fa fa-whatsapp"></i>Kaleyra WhatsApp</a></li>
                                                        <li class="<?= ($segment1 == 'tunch_testing' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>tunch_testing/add/"><i class="fa fa-book"></i>Tunch Testing</a></li>
                                                        <li class="<?= ($segment1 == 'hallmark1' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>hallmark1/add/"><i class="fa fa-book"></i>Hallmark1</a></li>
                                                        <li class="<?= ($segment1 == 'demo_views' && $segment2 == 'sales1') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>demo_views/sales1"><i class="fa fa-book"></i>Sales 1</a></li>
                                                        <li class="<?= ($segment1 == 'demo_views' && $segment2 == 'sales2') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>demo_views/sales2"><i class="fa fa-book"></i>Sales 2</a></li>
                                                    </ul>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if($this->applib->have_access_role(ACCOUNT_MODULE_ID,"view")) { ?>
                                    <li class="dropdown <?= ($segment1 == 'account') ? 'active' : '' ?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php if($this->applib->have_access_role(ACCOUNT_MODULE_ID,"add") || $this->applib->have_access_role(ACCOUNT_MODULE_ID,"edit")) { ?>
                                                <li class="<?= ($segment1 == 'account' && $segment2 == 'account') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>account/account/"><i class="fa fa-plus"></i> Add Account</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ACCOUNT_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'account' && $segment2 == 'account_list') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>account/account_list/"><i class="fa fa-list"></i> Account List</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ACCOUNT_GROUP_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'account' && $segment2 == 'account_group') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>account/account_group/"><i class="fa fa-list"></i> Account Group </a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if($this->applib->have_access_role(ORDER_MENU_ID,"view")) { ?>
                                    <li class="dropdown <?= ($segment1 == 'new_order' && $segment2 == 'add' || $segment2 == 'new_order_list' || $segment2 == 'new_order_item_list' || $segment2 == 'inquiry_list') ? 'active' : '' ?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Order <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php if($this->applib->have_access_role(ORDER_MODULE_ID,"add")) { ?>
                                                <li class="<?= ($segment1 == 'new_order' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>new_order/add/"><i class="fa fa-plus"></i>Add Order</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'new_order' && $segment2 == 'new_order_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>new_order/new_order_list/"><i class="fa fa-list"></i>Order List</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'new_order' && $segment2 == 'inquiry_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>new_order/inquiry_list/"><i class="fa fa-list"></i>Inquiry List</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'new_order' && $segment2 == 'new_order_item_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>new_order/new_order_item_list/"><i class="fa fa-list"></i>Order Item List</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ORDER_SLIDER_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'master' && $segment2 == 'slider') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/slider/"><i class="fa fa-sliders"></i>Order Slider</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ORDER_MODULE_ID,"add")) { ?>
                                                <hr>
                                                <li class="<?= ($segment1 == 'order' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>order/add/"><i class="fa fa-plus"></i>Add Order</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'order' && $segment2 == 'order_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>order/order_list/"><i class="fa fa-list"></i>Order List</a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"view")) { ?>
                                    <li class="dropdown <?= (($segment1 == 'manufacture' && $segment2 == 'issue_receive' || $segment2 == 'issue_receive_list' || $segment2 == 'hisab_total_list') || ($segment1 == 'manu_hand_made') || ($segment1 == 'machine_chain') || ($segment1 == 'manufacture_silver') || ($segment1 == 'inventory')) ? 'active' : '' ?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manufacture <span class="caret"></span></a>
                                        <ul class="dropdown-menu mega-menu manufacture_menus" role="menu">
                                            <li class="mega-menu-column mega-menu-column-sell">
                                                <ul class="mega-menu-design" role="menu">
                                                    <?php if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"view")) { ?>
                                                        <li class="text-center" style="width: 100%;"><h5>Issue/Receive</h5></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"add") || $this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'manufacture' && $segment2 == 'issue_receive') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manufacture/issue_receive/"><i class="fa fa-plus"></i>Issue/Receive Entry</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'manufacture' && $segment2 == 'issue_receive_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manufacture/issue_receive_list/"><i class="fa fa-list"></i>Issue/Receive List</a></li>
                                                    <?php } ?>
                                                    <?php
                                                        if($inventory_data_modules_menu == '1' ) { // Method : Inventory Data Modules Checkbox
                                                    ?>
                                                            <li class="text-center" style="width: 100%;"><h5>Inventory</h5></li>
                                                            <?php if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"add") || $this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"edit")) { ?>
                                                                <li class="<?= ($segment1 == 'inventory' && $segment2 == 'import_data') ? 'active' : '' ?>"><a href="<?= base_url(); ?>inventory/import_data/"><i class="fa fa-plus"></i>Import Data</a></li>
                                                            <?php } ?>
                                                            <?php if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"view")) { ?>
                                                                <li class="<?= ($segment1 == 'inventory' && $segment2 == 'data_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>inventory/data_list/"><i class="fa fa-list"></i>Data List</a></li>
                                                            <?php } ?>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"view")) { ?>
                                                        <li class="text-center" style="width: 100%;"><hr><h5>Issue/Receive Silver</h5></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"add") || $this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'manufacture_silver' && $segment2 == 'issue_receive_silver') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manufacture_silver/issue_receive_silver/"><i class="fa fa-plus"></i>I/R Silver Entry</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'manufacture_silver' && $segment2 == 'issue_receive_silver_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manufacture_silver/issue_receive_silver_list/"><i class="fa fa-list"></i>I/R Silver List</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"worker_hisab") || $this->applib->have_access_role(MANUFACTURE_MODULE_ID,"worker_hisab_i_r_silver") || 
                                                        $this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"worker_hisab_handmade") || $this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"worker_hisab_machine_chain")) { ?>
                                                        <li class="text-center" style="width: 100%;"><hr><h5>Hisab Done</h5></li>
                                                        <li class="<?= ($segment1 == 'manufacture' && $segment2 == 'hisab_total_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manufacture/hisab_total_list/"><i class="fa fa-list"></i>Hisab Total List</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <li class="mega-menu-column mega-menu-column-sell">
                                                <ul class="mega-menu-design" role="menu">
                                                    <?php if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"view")) { ?>
                                                        <li class="text-center" style="width: 100%;"><h5>Hand Made</h5></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(OPERATION_MODULE_ID,"add") || $this->applib->have_access_role(OPERATION_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'manu_hand_made' && $segment2 == 'operation') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manu_hand_made/operation/"><i class="fa fa-plus"></i>Add Operation</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(OPERATION_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'manu_hand_made' && $segment2 == 'operation_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manu_hand_made/operation_list/"><i class="fa fa-list"></i>Operation List</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"add") || $this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'manu_hand_made' && $segment2 == 'manu_hand_made') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manu_hand_made/manu_hand_made/"><i class="fa fa-plus"></i>Hand Made Entry</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'manu_hand_made' && $segment2 == 'manu_hand_made_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>manu_hand_made/manu_hand_made_list/"><i class="fa fa-list"></i>Hand Made List</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(CASTING_MODULE_ID,"view")) { ?>
                                                        <li class="text-center" style="width: 100%;"><hr style="    margin-top: 10px; margin-bottom: 10px;"><h5>Casting</h5></li>
                                                        <li class="<?= ($segment1 == 'new_order' && $segment2 == 'casting_item_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>new_order/casting_item_list/"><i class="fa fa-list"></i>Casting Item List</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(CASTING_MODULE_ID,"add") || $this->applib->have_access_role(CASTING_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'casting' && $segment2 == 'casting_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>casting/casting_entry/"><i class="fa fa-list"></i>Casting Entry</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(CASTING_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'casting' && $segment2 == 'casting_entry_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>casting/casting_entry_list/"><i class="fa fa-list"></i>Casting List</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <li class="mega-menu-column mega-menu-column-sell">
                                                <ul class="mega-menu-design" role="menu">
                                                    <?php if($this->applib->have_access_role(REFINERY_MODULE_ID,"view")) { ?>
                                                        <li class="text-center" style="width: 100%;"><h5>Refinery</h5></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(REFINERY_MODULE_ID,"add") || $this->applib->have_access_role(REFINERY_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'refinery' && $segment2 == 'refinery_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>refinery/refinery_entry/"><i class="fa fa-plus"></i>Refinery Entry</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(REFINERY_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'refinery' && $segment2 == 'refinery_list' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>refinery/refinery_list/"><i class="fa fa-list"></i>Refinery Entry List</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <li class="mega-menu-column mega-menu-column-sell">
                                                <ul class="mega-menu-design" role="menu">
                                                    <?php if($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID,"view")) { ?>
                                                        <li class="text-center" style="width: 100%;"><h5>Machine Chain</h5></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID,"add") || $this->applib->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'machine_chain' && $segment2 == 'operation') ? 'active' : '' ?>"><a href="<?= base_url(); ?>machine_chain/operation/"><i class="fa fa-plus"></i>Add Operation</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'machine_chain' && $segment2 == 'operation_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>machine_chain/operation_list/"><i class="fa fa-list"></i>Operation List</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID,"add") || $this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'machine_chain' && $segment2 == 'machine_chain') ? 'active' : '' ?>"><a href="<?= base_url(); ?>machine_chain/machine_chain/"><i class="fa fa-plus"></i>Machine Chain Entry</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'machine_chain' && $segment2 == 'machine_chain_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>machine_chain/machine_chain_list/"><i class="fa fa-list"></i>Machine Chain List</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MENU_ID,"view")) { ?>
                                    <?php
                                        $sell_purchase_difference = 0;
                                        if(isset($this->session->userdata()[PACKAGE_FOLDER_NAME . 'sell_purchase_difference'])){
                                            $sell_purchase_difference = $this->session->userdata()[PACKAGE_FOLDER_NAME . 'sell_purchase_difference'];
                                        }
                                    ?>
                                    <li class="dropdown <?= ($segment1 == 'sell' && $segment2 == 'add' || $segment2 == 'sell_list' || $segment3 == 'view' || $segment2 == 'sell_item_list' || $segment2 == 'sell_item_list_from_item') || ($segment1 == 'other' && $segment2 == 'add' || $segment2 == 'other_list')  || ($segment1 == 'sell_purchase') || ($segment1 == 'sell_purchase_type_2') || ($segment1 == 'sell_purchase_type_3') || ($segment1 == 'sell_with_gst') || ($segment1 == 'inventory_sell_purchase') ? 'active' : '' ?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sell/Purchase <span class="caret"></span></a>
                                        <ul class="dropdown-menu mega-menu sell" role="menu">
                                            <li class="mega-menu-column-sell">
                                                <ul class="mega-menu-design" role="menu">
                                                    <?php
                                                        if($sell_purchase_type_2_menu == '1' ) { // Method : Sell/Purchase Type 2 Checkbox
                                                    ?>
                                                            <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                <li class="<?= ($segment1 == 'sell_purchase_type_2' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_2/add/"><i class="fa fa-plus"></i>Sell/Purchase Entry</a></li>
                                                            <?php } ?>
                                                            <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                <li class="<?= ($segment1 == 'sell_purchase_type_2' && $segment2 == 'splist' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_2/splist/"><i class="fa fa-list"></i>Sell/Purchase List</a></li>
                                                            <?php } ?>
                                                    <?php 
                                                        } else if($sell_purchase_type_3_menu == '1' ) { // Method : Sell/Purchase Type 3 Checkbox
                                                    ?>
                                                            <?php if($sell_purchase_difference) {?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'add' && $segment3 == 'sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/add/sell"><i class="fa fa-plus"></i>Sell Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'splist' && $segment3 == 'sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/splist/sell"><i class="fa fa-list"></i>Sell List</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'add' && $segment3 == 'purchase') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/add/purchase"><i class="fa fa-plus"></i>Purchase Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'splist' && $segment3 == 'purchase') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/splist/purchase"><i class="fa fa-list"></i>Purchase List</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'add' && $segment3 == 'payment_receipt') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/add/payment_receipt"><i class="fa fa-plus"></i>Payment Receipt Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'splist' && $segment3 == 'payment_receipt') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/splist/payment_receipt"><i class="fa fa-list"></i>Payment Receipt List</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'add' && $segment3 == 'metal_issue_receive') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/add/metal_issue_receive"><i class="fa fa-plus"></i>Metal Issue Receive Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'splist' && $segment3 == 'metal_issue_receive') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/splist/metal_issue_receive"><i class="fa fa-list"></i>Metal Issue Receive List</a></li>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/add/"><i class="fa fa-plus"></i>Sell/Purchase Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell_purchase_type_3' && $segment2 == 'splist' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase_type_3/splist/"><i class="fa fa-list"></i>Sell/Purchase List</a></li>
                                                                <?php } ?>
                                                            <?php } ?>
                                                    <?php 
                                                        } else { // Default Sell/Purchase of guru
                                                    ?>
                                                        <?php if(PACKAGE_FOR == 'shanti') { ?>

                                                            <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                <li class="<?= ($segment1 == 'sell_purchase' && $segment2 == 'add' && $segment3 == 'sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase/add/sell/"><i class="fa fa-plus"></i>Sell Entry</a></li>
                                                            <?php } ?>
                                                            <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                <li class="<?= ($segment1 == 'sell_purchase' && $segment2 == 'splist' && $segment3 == 'sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_purchase/splist/sell"><i class="fa fa-list"></i>Sell List</a></li>
                                                            <?php } ?>
                                                            <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"sell/purchase item list")) { ?>
                                                                <li class="<?= ($segment1 == 'sell' && $segment2 == 'sell_item_list' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/sell_item_list/"><i class="fa fa-list"></i>Sell/Purchase Item List</a></li>
                                                            <?php } ?>

                                                        <?php } else { ?>

                                                            <?php if($sell_purchase_difference) {?>

                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell' && $segment2 == 'add' && $segment3 == 'sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/add/sell"><i class="fa fa-plus"></i>Sell Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell' && $segment2 == 'sell_list' && $segment3 == 'sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/sell_list/sell"><i class="fa fa-list"></i>Sell List</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell' && $segment2 == 'add' && $segment3 == 'purchase') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/add/purchase"><i class="fa fa-plus"></i>Purchase Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell' && $segment2 == 'sell_list' && $segment3 == 'purchase') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/sell_list/purchase"><i class="fa fa-list"></i>Purchase List</a></li>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/add/"><i class="fa fa-plus"></i>Sell/Purchase Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'sell' && $segment2 == 'sell_list' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/sell_list/"><i class="fa fa-list"></i>Sell/Purchase List</a></li>
                                                                <?php } ?>
                                                            <?php } ?>

                                                            <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                <li class="<?= ($segment1 == 'sell' && $segment2 == 'sell_list' && $segment3 == 'view') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/sell_list/view"><i class="fa fa-list"></i>Not Delivered List</a></li>
                                                            <?php } ?>

                                                            <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"sell/purchase item list")) { ?>
                                                                <li class="<?= ($segment1 == 'sell' && $segment2 == 'sell_item_list' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/sell_item_list/"><i class="fa fa-list"></i>Sell/Purchase Item List</a></li>
                                                            <?php } ?>

                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php 
                                                        if($inventory_data_modules_menu == '1' ) { // Method : Inventory Data Modules Checkbox
                                                    ?>
                                                            <li class="text-center" style="width: 100%;"><h5>Inventory</h5></li>
                                                            <?php // if($sell_purchase_difference) {?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'inventory_sell_purchase' && $segment2 == 'add' && $segment3 == 'sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>inventory_sell_purchase/add/sell"><i class="fa fa-plus"></i>Sell Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'inventory_sell_purchase' && $segment2 == 'splist' && $segment3 == 'sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>inventory_sell_purchase/splist/sell"><i class="fa fa-list"></i>Sell List</a></li>
                                                                <?php } ?>
                                                                <?php /*if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'inventory_sell_purchase' && $segment2 == 'add' && $segment3 == 'purchase') ? 'active' : '' ?>"><a href="<?= base_url(); ?>inventory_sell_purchase/add/purchase"><i class="fa fa-plus"></i>Purchase Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'inventory_sell_purchase' && $segment2 == 'splist' && $segment3 == 'purchase') ? 'active' : '' ?>"><a href="<?= base_url(); ?>inventory_sell_purchase/splist/purchase"><i class="fa fa-list"></i>Purchase List</a></li>
                                                                <?php } */?>
                                                            <?php // } else { ?>
                                                                <?php /*if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                                    <li class="<?= ($segment1 == 'inventory_sell_purchase' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>inventory_sell_purchase/add/"><i class="fa fa-plus"></i>Sell/Purchase Entry</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                                    <li class="<?= ($segment1 == 'inventory_sell_purchase' && $segment2 == 'splist' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>inventory_sell_purchase/splist/"><i class="fa fa-list"></i>Sell/Purchase List</a></li>
                                                                <?php }*/ ?>
                                                            <?php // } ?>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                             
                                            <li class="mega-menu-column-sell">
                                                <ul class="mega-menu-design" role="menu">
                                                    <?php if($this->applib->have_access_role(OTHER_ENTRY_MODULE_ID,"add") || $this->applib->have_access_role(OTHER_ENTRY_MODULE_ID,"edit")) { ?>
                                                        <li class="<?= ($segment1 == 'other' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>other/add/"><i class="fa fa-plus"></i>Other Entry</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(OTHER_ENTRY_MODULE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'other' && $segment2 == 'other_list' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>other/other_list/"><i class="fa fa-list"></i>Other Entry List</a></li>
                                                    <?php } ?>
                                                </ul>
                                                <?php if($sell_purchase_entry_with_gst_menu == '1'){ ?>
                                                    <ul class="mega-menu-design" role="menu">
                                                        <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) { ?>
                                                            <li class="<?= ($segment1 == 'sell_with_gst' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_with_gst/add/"><i class="fa fa-plus"></i>Entry with GST</a></li>
                                                        <?php } ?>
                                                        <?php if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) { ?>
                                                            <li class="<?= ($segment1 == 'sell_with_gst' && $segment2 == 'sell_with_gst_list' && $segment3 == '') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell_with_gst/sell_with_gst_list/"><i class="fa fa-list"></i>Entry with GST List</a></li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </li> 
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if($this->applib->have_access_role(JOURNAL_MODULE_ID,"view") || $this->applib->have_access_role(CASHBOOK_MODULE_ID,"view") || $this->applib->have_access_role(STOCK_TRANSFER_MODULE_ID,"view")) { ?>
                                <li class="dropdown <?= ($segment1 == 'journal' || ($segment1 == 'reports' && $segment2 == 'cashbook') || ($segment1 == 'stock_transfer') || $segment2 == 'import') ? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Transaction <span class="caret"></span></a>
                                    <!--<ul class="dropdown-menu" role="menu">-->
                                    <ul class="dropdown-menu mega-menu transaaction">
                                        <li class="mega-menu-column">
                                            <ul class="mega-menu-design" role="menu">
                                                <?php if($this->applib->have_access_role(JOURNAL_MODULE_ID,"add") || $this->applib->have_access_role(JOURNAL_MODULE_ID,"edit")) { ?>
                                                    <li class="<?= ($segment1 == 'journal' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>journal/add/"><i class="fa fa-plus"></i>Journal Entry</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(JOURNAL_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'journal' && $segment2 == 'journal_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>journal/journal_list/"><i class="fa fa-list"></i>Journal List</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(CASHBOOK_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'reports' && $segment2 == 'cashbook') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>reports/cashbook/"><i class="fa fa-circle-o"></i>Cash Book</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li class="mega-menu-column">
                                            <ul class="mega-menu-design" role="menu">
                                                <?php if($this->applib->have_access_role(STOCK_TRANSFER_MODULE_ID,"add") || $this->applib->have_access_role(STOCK_TRANSFER_MODULE_ID,"edit")) { ?> 
                                                    <li class="<?= ($segment1 == 'stock_transfer' && $segment2 == 'stock_transfer') ? 'active' : '' ?>"><a href="<?= base_url(); ?>stock_transfer/stock_transfer/"><i class="fa fa-plus"></i>Stock Transfer</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(STOCK_TRANSFER_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'stock_transfer' && $segment2 == 'stock_transfer_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>stock_transfer/stock_transfer_list/"><i class="fa fa-list"></i>Stock Transfer List</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(IMPORT_DATA_MODULE_ID,"allow")) { ?>
                                                    <li class="<?= ($segment1 == 'import' && $segment2 == 'import') ? 'active' : '' ?>"><a href="<?= base_url(); ?>import/import"><i class="fa fa-th-large"></i>Import Data</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if($this->applib->have_access_role(REPORT_MODULE_ID,"view")) { ?>
                                <li class="dropdown <?= ($segment1 == 'reports' && $segment2 == 'goldbook' || $segment2 == 'silverbook' || $segment2 == 'stock_status' || $segment2 == 'stock_ledger' || $segment2 == 'stock_check' || $segment2 == 'outstanding' || $segment2 == 'customer_ledger' || $segment2 == 'interest' || $segment2 == 'balance_sheet' || $segment2 == 'trading_pl' || $segment1 == 'reports_new' || $segment1 == 'reports_new_sp3' ) ? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
                                    <ul class="dropdown-menu mega-menu transaaction">
                                        <li class="mega-menu-column">
                                            <ul class="mega-menu-design" role="menu">
                                            <?php if($this->applib->have_access_role(GOLDBOOK_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'reports' && $segment2 == 'goldbook') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>reports/goldbook/"><i class="fa fa-book"></i>Gold Bullion</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(SILVERBOOK_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'reports' && $segment2 == 'silverbook') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>reports/silverbook/"><i class="fa fa-book"></i>Silver Bullion</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(STOCK_STATUS_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment2 == 'stock_status') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/stock_status/"><i class="fa fa-line-chart"></i>Stock Status</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(STOCK_STATUS_MODULE_ID,"stock_check")) { ?>
                                                <li class="<?= ($segment2 == 'stock_check') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/stock_check/"><i class="fa fa-line-chart"></i>Stock Check</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(BALANCE_SHEET_MODULE_ID,"view")) { ?>
                                                <?php if($sell_purchase_type_3_menu == '1' ) { ?>
                                                    <li class="<?= ($segment2 == 'balance_sheet') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports_new_sp3/balance_sheet/"><i class="fa fa-book"></i>Balance Sheet</a></li>
                                                <?php } else { ?>
                                                    <li class="<?= ($segment2 == 'balance_sheet') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/balance_sheet/"><i class="fa fa-book"></i>Balance Sheet</a></li>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(CALCULATIONS_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment2 == 'calculations') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/calculations/"><i class="fa fa-book"></i>Calculations</a></li>
                                            <?php } ?>
                                            </ul>
                                        </li>
                                        <li class="mega-menu-column">
                                            <ul class="mega-menu-design" role="menu">
                                            <?php if($this->applib->have_access_role(OUTSTANDING_MODULE_ID,"view")) { ?>
                                                <?php if($sell_purchase_type_3_menu == '1' ) { ?>
                                                    <li class="<?= ($segment2 == 'outstanding') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports_new_sp3/outstanding/"><i class="fa fa-bookmark"></i>OutStanding</a></li>
                                                <?php } else { ?>
                                                    <li class="<?= ($segment2 == 'outstanding') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/outstanding/"><i class="fa fa-bookmark"></i>OutStanding</a></li>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(ACCOUNT_MODULE_ID,"customer ledger")) { ?>
                                                <?php if($sell_purchase_type_2_menu == '1' ) { ?>
                                                    <li class="<?= ($segment1 == 'reports_new' && $segment2 == 'customer_ledger') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>reports_new/customer_ledger/"><i class="fa fa-bar-chart-o"></i>Ledger</a></li>
                                                <?php } else if($sell_purchase_type_3_menu == '1' ) { ?>
                                                    <li class="<?= ($segment1 == 'reports_new_sp3' && $segment2 == 'customer_ledger') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>reports_new_sp3/customer_ledger/"><i class="fa fa-bar-chart-o"></i>Ledger</a></li>
                                                <?php } else { ?>
                                                    <li class="<?= ($segment1 == 'reports' && $segment2 == 'customer_ledger') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>reports/customer_ledger/"><i class="fa fa-bar-chart-o"></i>Ledger</a></li>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(INTEREST_MODULE_ID,"view")) { ?>
                                                <?php if($sell_purchase_type_3_menu == '1' ) { ?>
                                                    <li class="<?= ($segment1 == 'reports_new_sp3' && $segment2 == 'interest') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports_new_sp3/interest/"><i class="fa fa-money"></i>Interest</a></li>
                                                <?php } else { ?>
                                                    <li class="<?= ($segment1 == 'reports' && $segment2 == 'interest') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/interest/"><i class="fa fa-money"></i>Interest</a></li>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(TRADING_PL_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'reports' && $segment2 == 'trading_pl') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/trading_pl/"><i class="fa fa-money"></i>Trading PL</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(REMINDER_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'reports' && $segment2 == 'reminder') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/reminder/"><i class="fa fa-clock-o"></i>Reminder</a></li>
                                            <?php } ?>
                                            <?php // if($this->applib->have_access_role(TRADING_PL_MODULE_ID,"view")) { ?>
                                                <!--<li class="<?= ($segment1 == 'reports' && $segment2 == 'log') ? 'active' : '' ?>"><a href="<?= base_url(); ?>reports/log/"><i class="fa fa-book"></i>Log</a></li>-->
                                            <?php // } ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if($this->applib->have_access_role(HR_MODULE_ID,"view")) { ?>
                                    <li class="dropdown <?= ($segment2 == 'yearly_leaves' || $segment2 == 'present_hours' || $segment2 == 'apply_leave' || $segment2 == 'department_attendance' || $segment1 == 'employee_salary' || $segment1 == 'hr_attendance') ? 'active' : '' ?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">HR <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php if($this->applib->have_access_role(YEARLY_LEAVES_ID,"add") || $this->applib->have_access_role(YEARLY_LEAVES_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'yearly_leaves' && $segment2 == 'yearly_leaves') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>yearly_leaves/yearly_leaves/"><i class="fa fa-file-code-o"></i>Yearly Leaves</a></li>
                                            <?php } ?>
                                            <?php /* <li class="<?= ($segment1 == 'weekly_leaves' && $segment2 == 'weekly_leaves') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>weekly_leaves/weekly_leaves/"><i class="fa fa-file-image-o"></i>Weekly Leaves</a></li> */ ?>
                                            <?php /* if($this->applib->have_access_role(PRESENT_HOURS_MODULE_ID,"add") || $this->applib->have_access_role(PRESENT_HOURS_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'present_hours' && $segment2 == 'present_hours') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>present_hours/present_hours/"><i class="fa fa-clock-o"></i>Present Hours</a></li>
                                            <?php } */ ?>
                                            <?php if($this->applib->have_access_role(APPLY_LEAVE_ID,"add") || $this->applib->have_access_role(APPLY_LEAVE_ID,"edit") || $this->applib->have_access_role(APPLY_LEAVE_ID,"delete") || $this->applib->have_access_role(APPLY_LEAVE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'apply_leave' && $segment2 == 'apply_leave') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>apply_leave/apply_leave/"><i class="fa fa-book"></i>Apply Leave</a></li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(HR_ATTENDANCE_MODULE_ID,"view") || $this->applib->have_access_role(HR_ATTENDANCE_MODULE_ID,"add")) { ?>
                                                <li class="<?= ($segment1 == 'hr_attendance' && $segment2 == '') ? 'active' : '' ?>">
                                                    <a href="<?php echo base_url() ?>hr_attendance"><i class="fa fa-pencil-square-o"></i>Attendance</a>
                                                </li>
                                                <?php if($this->applib->have_access_role(HR_ATTENDANCE_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'hr_attendance' && $segment2 == 'attendance_report') ? 'active' : '' ?>">
                                                        <a href="<?php echo base_url() ?>hr_attendance/attendance_report"><i class="fa fa-book"></i>Attendance Report</a>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                            <li class="<?= ($segment1 == 'department_attendance' && $segment2 == 'department_attendance') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>department_attendance/department_attendance/"><i class="fa fa-pencil-square-o"></i>Department Wise Attendance</a></li>
                                            <?php if($this->applib->have_access_role(EMPLOYEE_SALARY_MODULE_ID,"view") || $this->applib->have_access_role(EMPLOYEE_SALARY_MODULE_ID,"add")) { ?>
                                                <li class="<?= ($segment1 == 'employee_salary') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>employee_salary"><i class="fa fa-pencil-square-o"></i>Employee Salary</a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if($this->applib->have_access_role(HALLMARK_MODULE_ID,"view")) { ?>
                                <li class="dropdown <?= ($segment1 == 'hallmark') ? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hallmark <span class="caret"></span></a>
                                    <ul class="dropdown-menu mega-menu hallmark_menus" role="menu">
                                        <li class="mega-menu-column mega-menu-column-sell">
                                            <ul class="mega-menu-design" role="menu">
                                                <?php if($this->applib->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'item_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/item_master/"><i class="fa fa-list-alt"></i>Item Master</a></li>
                                                <?php } ?>
                                                <li class="text-center" style="width: 100%;"><h5>Receipt</h5></li>
                                                <?php if($this->applib->have_access_role(HALLMARK_RECEIPT_MODULE_ID,"add")) { ?>
                                                    <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'receipt') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/receipt/"><i class="fa fa-plus"></i>Receipt Entry</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(HALLMARK_RECEIPT_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'receipt_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/receipt_list/"><i class="fa fa-list"></i>Receipt List</a></li>
                                                <?php } ?>
                                                <li class="text-center" style="width: 100%;"><hr><h5>XRF / HM / Laser</h5></li>
                                                <?php if($this->applib->have_access_role(HALLMARK_XRF_MODULE_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'xrf') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/xrf/"><i class="fa fa-plus"></i>XRF / HM / Laser Entry</a></li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(HALLMARK_XRF_MODULE_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'xrf_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/xrf_list/"><i class="fa fa-list"></i>XRF / HM / Laser List</a></li>
                                                    <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'xrf_items') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/xrf_items/"><i class="fa fa-list"></i>XRF / HM / Laser Item List</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li class="mega-menu-column mega-menu-column-sell">
                                            <ul class="mega-menu-design" role="menu">
                                                <li class="text-center" style="width: 100%;"><h5>Report</h5></li>
                                                <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'complete_report') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/complete_report/"><i class="fa fa-list"></i>Complete Report</a></li>
                                                <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'job_cash_received') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/job_cash_received/"><i class="fa fa-list"></i>Job Cash Received</a></li>
                                                <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'voucher_transaction') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/voucher_transaction/"><i class="fa fa-list"></i>Voucher Transaction</a></li>
                                                <li class="<?= ($segment1 == 'hallmark' && $segment2 == 'cash_receipt_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>hallmark/cash_receipt_entry/"><i class="fa fa-list"></i>Cash Receipt Entry</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                
                                <?php if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) { ?>
                                    <li class="dropdown <?= ($segment1 == 'backup' || $segment1 == 'move_to_second_db') ? 'active' : '' ?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Index <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="<?= ($segment1 == 'backup') ? 'active' : '' ?>"><a href="javascript:void(0);" class="btn_backup_db"><span>ReIndex</span></a></li>
                                            <?php 
                                                $show_backup_email_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'show_backup_email_menu'));
                                                if($show_backup_email_menu == '1' ) {
                                            ?>
                                                    <li class="<?= ($segment1 == 'backup' && $segment2 == 'email') ? 'active' : '' ?>"><a href="<?= base_url() ?>backup/email" onclick="loading_show();"><span>ReCalculate</span></a></li>
                                            <?php } ?>
                                            <?php /*<li class="<?= ($segment1 == 'move_to_second_db') ? 'active' : '' ?>"><a href="<?= base_url() ?>move_to_second_db/"><span>TransferIndex</span></a></li>*/ ?>
                                            <li class="<?= ($segment1 == 'delete_data_upto_date') ? 'active' : '' ?>"><a href="<?= base_url() ?>delete_data_upto_date/"><span>Check All is Well?</span></a></li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if($this->applib->have_access_role(SERVER_SHUTDOWN_MODULE_ID,"view") && ALLOW_SERVER_SHUTDOWN == '1') { ?>
                                    <li><a href="<?php echo SERVER_SHUTDOWN_URL; ?>" target="_blank" onclick="return confirm('Are you sure, You want to Server Shutdown?')">ॐ</a></li>
                                <?php } ?>
                            </ul>
                            
                        </div>
                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">

                                <li class="digital_watch">
                                    <div id="date" class="float-left" style="float:left;"></div>&nbsp;&nbsp;&nbsp;
                                    <div id="clock" class="float-left" style="float:left;"><?php echo date('h:i:s A'); ?></div>
                                </li>
                                <!-- User Account: style can be found in dropdown.less -->
                                <li class="dropdown user user-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <?php if (!empty($logged_in_profile_image)) { ?>
                                            <img class="user-image" src="<?= base_url() ?>assets/uploads/profile_image/<?= $logged_in_profile_image; ?>" alt="User profile picture">
                                        <?php } else { ?>
                                            <img class="user-image" src="<?php echo base_url(); ?>assets/dist/img/logo2.jpg" alt="User profile picture">
                                        <?php } ?>
                                        <span class="hidden-xs"><?= isset($logged_in_nickname) ? ucwords($logged_in_nickname) : 'Admin'; ?></span>
                                    </a>

                                    <ul class="dropdown-menu">
                                         <!--User image--> 
                                        <li class="user-header">
                                            <?php if (!empty($logged_in_profile_image)) { ?>
                                                <img class="img-circle" src="<?= base_url() ?>assets/uploads/profile_image/<?= $logged_in_profile_image; ?>" alt="User profile picture">
                                            <?php } else { ?>
                                                <img class="img-circle" src="<?= base_url() ?>assets/dist/img/logo2.jpg" alt="User profile picture">
                                            <?php } ?>
                                            <p>
                                                <?= isset($logged_in_nickname) ? ucwords($logged_in_nickname) : 'Admin'; ?><br/>
                                                <?= isset($logged_in_email) ? $logged_in_email : ''; ?>
                                            </p>
                                        </li>

                                         <!--Menu Footer-->
                                        <li class="user-footer">
                                            <div class="pull-left">
                                                <a href="<?= base_url() ?>auth/change_profile" class="btn btn-default btn-flat">Profile</a>
                                            </div>
                                            <div class="pull-right">
                                                <a href="<?= base_url() ?>auth/logout" class="btn btn-default btn-flat">Sign out</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <!-- Control Sidebar Toggle Button -->
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                        <!-- Navbar Right Menu -->
                        <!-- /.navbar-custom-menu -->
                    </div>
                </nav>
            </header>
            <script>

                $(document).ready(function () {
                    $('.dropdown-submenu a.test').on("click", function (e) {
                        $(this).next('ul').toggle();
                        e.stopPropagation();
                        e.preventDefault();
                    });
                    $('.stock_tr').on("click", function () {
                        $('.item_master_m').hide();
                    });
                    $('.item_master').on("click", function () {
                        $('.stock_tr_m').hide();
                    });
                });
                function loading_show() {
                    $('#ajax-loader').show();
                }
            </script>