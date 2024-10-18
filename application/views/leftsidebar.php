<?php
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" >
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= ($segment1 == 'dashboard') ? 'active' : '' ?>">
                <a href="<?= base_url(); ?>"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
            </li>
            <li class="treeview <?= ($segment1 == 'master' && $segment2 == 'item_master' || $segment2 == 'process_master' || $segment2 == 'party_entry' || $segment2 == 'category') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Master</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                     <li class="<?= ($segment1 == 'master' && $segment2 == 'category') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/category/"><i class="fa fa-circle-o"></i>Category</a></li>
                    <li class="treeview <?= ($segment1 == 'master') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span> Item Master</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'item_master') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>master/item_master/"><i class="fa fa-circle-o"></i> Add Item</a>
                            </li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'item_master_list') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>master/item_master_list/"><i class="fa fa-circle-o"></i> Item List</a>
                            </li>
                        </ul>
                    </li>
                    <!--<li class="<?= ($segment1 == 'master' && $segment2 == 'process_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/process_master/"><i class="fa fa-circle-o"></i>Department Master</a></li>-->
                    <!--<li class="<?= ($segment1 == 'master' && $segment2 == 'party_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/party_entry/"><i class="fa fa-circle-o"></i>Party Entry</a></li>-->
                </ul>
            </li>
            <li class="treeview <?= ($segment1 == 'account') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-users"></i> <span> Account</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($segment1 == 'account' && $segment2 == 'account') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>account/account/"><i class="fa fa-circle-o"></i> Add Account</a>
                    </li>
                    <li class="<?= ($segment1 == 'account' && $segment2 == 'account_list') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>account/account_list/"><i class="fa fa-circle-o"></i> Account List</a>
                    </li>
                </ul>
            </li>
            <li class="treeview <?= ($segment1 == 'new_order' && $segment2 == 'add' || $segment2 == 'new_order_list') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Order</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($segment1 == 'new_order' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>new_order/add/"><i class="fa fa-circle-o"></i>Add Order</a></li>
                    <li class="<?= ($segment1 == 'new_order' && $segment2 == 'new_order_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>new_order/new_order_list/"><i class="fa fa-circle-o"></i>Order List</a></li>
                </ul>
            </li>
             <li class="treeview <?= ($segment1 == 'sell' && $segment2 == 'add' || $segment2 == 'sell_list') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Sell/Purchase</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($segment1 == 'sell' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/add/"><i class="fa fa-circle-o"></i>Sell/Purchase Entry</a></li>
                    <li class="<?= ($segment1 == 'sell' && $segment2 == 'sell_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>sell/sell_list/"><i class="fa fa-circle-o"></i>Sell/Purchase List</a></li>
                </ul>
            </li>
            
             <li class="treeview <?= ($segment1 == 'worker' && $segment2 == 'add' || $segment2 == 'worker_list') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Worker</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($segment1 == 'worker' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>worker/add/"><i class="fa fa-circle-o"></i>Add Worker</a></li>
                    <li class="<?= ($segment1 == 'worker' && $segment2 == 'worker_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>worker/worker_list/"><i class="fa fa-circle-o"></i>Worker List</a></li>
                </ul>
            </li>
            <li class="treeview <?= ($segment1 == 'backup') ? 'active' : '' ?>">
                <a href="<?= base_url() ?>backup/">
                    <i class="fa fa-database"></i> <span>Backup DB</span>
                </a>
            </li>
<!--            <li class="treeview <?= ($segment1 == 'master' || $segment1 == 'order' || $segment1 == 'tree' || $segment1 == 'casting' || $segment1 == 'process_entry' || $segment1 == 'melting_entry' || $segment1 == 'component_issue_receive') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Old</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                

           
            <li class="treeview <?= ($segment1 == 'master' || $segment1 == 'order' || $segment1 == 'tree' || $segment1 == 'casting' || $segment1 == 'process_entry' || $segment1 == 'melting_entry' || $segment1 == 'component_issue_receive') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Production</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="treeview <?= ($segment1 == 'master' && $segment2 == 'item_master' || $segment2 == 'process_master' || $segment2 == 'party_entry' || $segment2 == 'worker_entry' || $segment2 == 'stone_company' || $segment2 == 'stone_shape' || $segment2 == 'stone_size' || $segment2 == 'stone_color' || $segment2 == 'component') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Master</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'item_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/item_master/"><i class="fa fa-circle-o"></i>Item Master</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'process_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/process_master/"><i class="fa fa-circle-o"></i>Process Master</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'party_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/party_entry/"><i class="fa fa-circle-o"></i>Party Entry</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'worker_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/worker_entry/"><i class="fa fa-circle-o"></i>Worker Entry</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'stone_company') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/stone_company/"><i class="fa fa-circle-o"></i>Stone Company</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'stone_shape') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/stone_shape/"><i class="fa fa-circle-o"></i>Stone Shape</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'stone_size') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/stone_size/"><i class="fa fa-circle-o"></i>Stone Size</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'stone_color') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/stone_color/"><i class="fa fa-circle-o"></i>Stone Color</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'component') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/component/"><i class="fa fa-circle-o"></i>Component</a></li>
                        </ul>
                    </li>
                    <li class="treeview <?= ($segment1 == 'master' && $segment2 == 'stone_master' || $segment2 == 'stone_master_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Stone Purchase</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'stone_master') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/stone_master/"><i class="fa fa-circle-o"></i>Stone Purchase Entry</a></li>
                            <li class="<?= ($segment1 == 'master' && $segment2 == 'stone_master_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>master/stone_master_list/"><i class="fa fa-circle-o"></i>Stone Purchase List</a></li>
                        </ul>
                    </li>
                    <li class="treeview <?= ($segment1 == 'order' && $segment2 == 'add' || $segment2 == 'order_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Order</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'order' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>order/add/"><i class="fa fa-circle-o"></i>Add Order</a></li>
                            <li class="<?= ($segment1 == 'order' && $segment2 == 'order_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>order/order_list/"><i class="fa fa-circle-o"></i>Order List</a></li>
                        </ul>
                    </li>
                    <li class="treeview <?= ($segment1 == 'tree' && $segment2 == 'add' || $segment2 == 'tree_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Tree</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'tree' && $segment2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url(); ?>tree/add/"><i class="fa fa-circle-o"></i>Add Tree</a></li>
                            <li class="<?= ($segment1 == 'tree' && $segment2 == 'tree_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>tree/tree_list/"><i class="fa fa-circle-o"></i>Tree List</a></li>
                        </ul>
                    </li>
                    <li class="treeview <?= ($segment1 == 'melting_entry' && $segment2 == 'add_melting_entry' || $segment2 == 'melting_entry_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Melting</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'melting_entry' && $segment2 == 'add_melting_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>melting_entry/add_melting_entry/"><i class="fa fa-circle-o"></i>Melting Entry</a></li>
                            <li class="<?= ($segment1 == 'melting_entry' && $segment2 == 'melting_entry_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>melting_entry/melting_entry_list/"><i class="fa fa-circle-o"></i>Melting List</a></li>
                        </ul>
                    </li>
                    <li class="treeview <?= ($segment1 == 'casting' && $segment2 == 'add_casting' || $segment2 == 'casting_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Casting</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'casting' && $segment2 == 'add_casting') ? 'active' : '' ?>"><a href="<?= base_url(); ?>casting/add_casting/"><i class="fa fa-circle-o"></i>Add Casting </a></li>
                            <li class="<?= ($segment1 == 'casting' && $segment2 == 'casting_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>casting/casting_list/"><i class="fa fa-circle-o"></i>Casting List</a></li>
                        </ul>
                    </li>
                    <li class="treeview <?= ($segment1 == 'process_entry' && $segment2 == 'add_process_entry' || $segment2 == 'process_entry_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Process</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'process_entry' && $segment2 == 'add_process_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>process_entry/add_process_entry/"><i class="fa fa-circle-o"></i>Process Entry</a></li>
                            <li class="<?= ($segment1 == 'process_entry' && $segment2 == 'process_entry_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>process_entry/process_entry_list/"><i class="fa fa-circle-o"></i>Process List</a></li>
                        </ul>
                    </li>
                    <li class="treeview <?= ($segment1 == 'component_issue_receive' && $segment2 == 'add_issue_receive' || $segment2 == 'issue_receive_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Component Issue/Receive</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($segment1 == 'component_issue_receive' && $segment2 == 'add_issue_receive') ? 'active' : '' ?>"><a href="<?= base_url(); ?>component_issue_receive/add_issue_receive/"><i class="fa fa-circle-o"></i>Add Issue/Receive</a></li>
                            <li class="<?= ($segment1 == 'component_issue_receive' && $segment2 == 'issue_receive_list') ? 'active' : '' ?>"><a href="<?= base_url(); ?>component_issue_receive/issue_receive_list/"><i class="fa fa-circle-o"></i>Issue/Receive List</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="treeview <?= ($segment1 == 'demo') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i> <span>Sales</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'barcode_details') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/barcode_details/"><i class="fa fa-circle-o"></i>Barcode Details</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'available_stock') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/available_stock/"><i class="fa fa-circle-o"></i>Available Stock</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'sell_entry') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/sell_entry/"><i class="fa fa-circle-o"></i>Sell Entry</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'sold_items') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/sold_items/"><i class="fa fa-circle-o"></i>Sold Items</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'scan_after_sell') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/scan_after_sell/"><i class="fa fa-circle-o"></i>Scan After Sell</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'final_sold_items') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/final_sold_items/"><i class="fa fa-circle-o"></i>Final Sold Items</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'final_available_stock') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/final_available_stock/"><i class="fa fa-circle-o"></i>Final Available Stock</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'sold_summary') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/sold_summary/"><i class="fa fa-circle-o"></i>Sold Summary</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'lock_item_stock') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/lock_item_stock/"><i class="fa fa-circle-o"></i>Lock Item Stock</a></li>
                    <li class="<?= ($segment1 == 'demo' && $segment2 == 'excel_upload') ? 'active' : '' ?>"><a href="<?= base_url(); ?>demo/excel_upload/"><i class="fa fa-circle-o"></i>Excel Upload</a></li>
                </ul>
            </li>-->
            
            </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>


