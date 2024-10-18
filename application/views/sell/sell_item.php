<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <style>
        .dataTables_scroll {
            overflow:auto;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sell/Purchase Item List
            <?php
            $isView = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
            <a href="<?=base_url('sell/add') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Sell/Purchase</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <?php if($isView){ ?>
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <?php if(isset($view_not) && !empty($view_not)){ ?> <div class="filter" hidden=""> <?php } else { ?>
                                        <div class="filter">
                                        <?php } ?>
                                            <div class="col-md-2">
                                                <label>From Date</label>
                                                <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y", strtotime("first day of this month"));?>">
                                                <!--<input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="">-->
                                            </div>
                                            <div class="col-md-2">
                                                <label>To Date</label>
                                                <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y');?>">
                                                <!--<input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="">-->
                                            </div>
                                            <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                        </div><div class="clearfix"></div><br />
                                        <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Item</th>
                                                    <th>Total Exchange Net Wt</th>
                                                    <th>Total Exchange Fine</th>
                                                    <th>Total Purchase Net Wt</th>
                                                    <th>Total Purchase Fine</th>
                                                    <th>Total Sell Net Wt</th>
                                                    <th>Total Sell Fine</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th style="text-align:Center">Gold Total:</th>
                                                    <th id="gold_total_exchange_net_wt" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="gold_total_exchange_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="gold_total_purchase_net_wt" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="gold_total_purchase_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="gold_total_sell_net_wt" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="gold_total_sell_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th style="text-align:Center">Silver Total:</th>
                                                    <th id="silver_total_exchange_net_wt" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="silver_total_exchange_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="silver_total_purchase_net_wt" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="silver_total_purchase_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="silver_total_sell_net_wt" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                    <th id="silver_total_sell_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="gold_total_exchange_net_wt1">
<input type="hidden" id="gold_total_exchange_fine1">
<input type="hidden" id="gold_total_purchase_net_wt1">
<input type="hidden" id="gold_total_purchase_fine1">
<input type="hidden" id="gold_total_sell_net_wt1">
<input type="hidden" id="gold_total_sell_fine1">
<input type="hidden" id="silver_total_exchange_net_wt1">
<input type="hidden" id="silver_total_exchange_fine1">
<input type="hidden" id="silver_total_purchase_net_wt1">
<input type="hidden" id="silver_total_purchase_fine1">
<input type="hidden" id="silver_total_sell_net_wt1">
<input type="hidden" id="silver_total_sell_fine1">
<script>
    $(document).ready(function () {
        $("#ajax-loader").show();
        $('.select2').select2();
        
        table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('sell/item_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
                "dataSrc": function ( jsondata ) {
                    if(jsondata.gold_total_exchange_net_wt){
                        $('#gold_total_exchange_net_wt1').val(jsondata.gold_total_exchange_net_wt);
                    } else {
                        $('#gold_total_exchange_net_wt1').val('');
                    }
                    if(jsondata.gold_total_exchange_fine){
                        $('#gold_total_exchange_fine1').val(jsondata.gold_total_exchange_fine);
                    } else {
                        $('#gold_total_exchange_fine1').val('');
                    }
                    if(jsondata.gold_total_purchase_net_wt){
                        $('#gold_total_purchase_net_wt1').val(jsondata.gold_total_purchase_net_wt);
                    } else {
                        $('#gold_total_purchase_net_wt1').val('');
                    }
                    if(jsondata.gold_total_purchase_fine){
                        $('#gold_total_purchase_fine1').val(jsondata.gold_total_purchase_fine);
                    } else {
                        $('#gold_total_purchase_fine1').val('');
                    }
                    if(jsondata.gold_total_sell_net_wt){
                        $('#gold_total_sell_net_wt1').val(jsondata.gold_total_sell_net_wt);
                    } else {
                        $('#gold_total_sell_net_wt1').val('');
                    }
                    if(jsondata.gold_total_sell_fine){
                        $('#gold_total_sell_fine1').val(jsondata.gold_total_sell_fine);
                    } else {
                        $('#gold_total_sell_fine1').val('');
                    }
                    if(jsondata.silver_total_exchange_net_wt){
                        $('#silver_total_exchange_net_wt1').val(jsondata.silver_total_exchange_net_wt);
                    } else {
                        $('#silver_total_exchange_net_wt1').val('');
                    }
                    if(jsondata.silver_total_exchange_fine){
                        $('#silver_total_exchange_fine1').val(jsondata.silver_total_exchange_fine);
                    } else {
                        $('#silver_total_exchange_fine1').val('');
                    }
                    if(jsondata.silver_total_purchase_net_wt){
                        $('#silver_total_purchase_net_wt1').val(jsondata.silver_total_purchase_net_wt);
                    } else {
                        $('#silver_total_purchase_net_wt1').val('');
                    }
                    if(jsondata.silver_total_purchase_fine){
                        $('#silver_total_purchase_fine1').val(jsondata.silver_total_purchase_fine);
                    } else {
                        $('#silver_total_purchase_fine1').val('');
                    }
                    if(jsondata.silver_total_sell_net_wt){
                        $('#silver_total_sell_net_wt1').val(jsondata.silver_total_sell_net_wt);
                    } else {
                        $('#silver_total_sell_net_wt1').val('');
                    }
                    if(jsondata.silver_total_sell_fine){
                        $('#silver_total_sell_fine1').val(jsondata.silver_total_sell_fine);
                    } else {
                        $('#silver_total_sell_fine1').val('');
                    }
                    return jsondata.data;
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [2, 3, 4, 5, 6, 7],
            }],
            "footerCallback": function ( row, data, start, end, display ) {
                $('tfoot tr th#gold_total_exchange_net_wt').html($('#gold_total_exchange_net_wt1').val());
                $('tfoot tr th#gold_total_exchange_fine').html($('#gold_total_exchange_fine1').val());
                $('tfoot tr th#gold_total_purchase_net_wt').html($('#gold_total_purchase_net_wt1').val());
                $('tfoot tr th#gold_total_purchase_fine').html($('#gold_total_purchase_fine1').val());
                $('tfoot tr th#gold_total_sell_net_wt').html($('#gold_total_sell_net_wt1').val());
                $('tfoot tr th#gold_total_sell_fine').html($('#gold_total_sell_fine1').val());
                $('tfoot tr th#silver_total_exchange_net_wt').html($('#silver_total_exchange_net_wt1').val());
                $('tfoot tr th#silver_total_exchange_fine').html($('#silver_total_exchange_fine1').val());
                $('tfoot tr th#silver_total_purchase_net_wt').html($('#silver_total_purchase_net_wt1').val());
                $('tfoot tr th#silver_total_purchase_fine').html($('#silver_total_purchase_fine1').val());
                $('tfoot tr th#silver_total_sell_net_wt').html($('#silver_total_sell_net_wt1').val());
                $('tfoot tr th#silver_total_sell_fine').html($('#silver_total_sell_fine1').val());
            }
        });
        
        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');
        
        $(document).on('click', '#search', function (){
            $("#ajax-loader").show();
            table.draw();
        });
        
    });
</script>
