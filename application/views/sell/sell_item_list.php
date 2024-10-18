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
                                            <div class="col-md-1">
                                                <label>From Date</label>
                                                <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y", strtotime($from_date));?>">
                                            </div>
                                            <div class="col-md-1">
                                                <label>To Date</label>
                                                <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y', strtotime($to_date));?>">
                                            </div>
                                            <div class="col-md-1">
                                                <input type="hidden" id="item_id" value="<?php echo isset($item_id) && !empty($item_id) ? $item_id : '' ?>">
                                                <label>Type</label>
                                                <select name="sell_type" id="sell_type" class="form-control select2" >
                                                    <option value="">All</option>
                                                    <option value="1">Sell</option>
                                                    <option value="2">Purchase</option>
                                                    <option value="3">Exchange</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Wastage</label>
                                                <select name="Wastage" id="wastage" class="form-control select2" >
                                                    <option value="">All</option>
                                                    <option value="1_0" selected="selected">Only Pending Approve Diff Wastage</option>
                                                    <option value="1_1">Approved Diff Wastage</option>
                                                    <option value="0_0">Default Wastage Only</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Account</label>
                                                <select name="account_id" id="account_id" class="form-control select2" ></select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Delivered / Not Delivered</label>
                                                <select name="delivery_type" id="delivery_type" class="form-control select2" >
                                                    <option value="0">All</option>
                                                    <option value="1">Delivered</option>
                                                    <?php if(isset($view_not) && !empty($view_not)){ ?>
                                                        <option value="2" selected="">Not Delivered</option>
                                                    <?php } else { ?>
                                                        <option value="2">Not Delivered</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label>Tunch</label>
                                                <select name="touch_id" id="touch_id" class="form-control select2 touch_id">
                                                    <option value=""> - Select - </option>
                                                    <?php foreach ($touch as $value) { ?>
                                                        <option value="<?= $value->purity; ?>"<?= isset($carat_id) && $value->purity == $carat_id ? 'selected="selected"' : ''; ?>><?= $value->purity; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                        </div><div class="clearfix"></div><br />
                                        <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="45px">Action</th>
                                                    <th>Account Name</th>
                                                    <th>Department</th>
                                                    <th width="10px">Sell No</th>
                                                    <th width="80px" class="text-nowrap">Sell Date</th>
                                                    <th width="10px">Sell Item No</th>
                                                    <th>Type</th>
                                                    <th>Category Name</th>
                                                    <th>Item Name</th>
                                                    <th>Gross.Wt</th>
                                                    <th>Less</th>
                                                    <th>Net.Wt</th>
                                                    <th>Tunch</th>
                                                    <th>Wastage</th>
                                                    <th>Fine</th>
                                                    <th>Image</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th style="text-align:Center">Total:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
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
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('.select2').select2();
        initAjaxSelect2($("#account_id"), "<?= base_url('app/party_name_with_number_select2_source') ?>");
        
        table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('sell/sell_item_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.item_id = $('#item_id').val();
                    d.delivery_type = $('#delivery_type').val();
                    d.sell_type = $('#sell_type').val();
                    d.account_id = $('#account_id').val();
                    d.wastage = $('#wastage').val();
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.touch_id = $('#touch_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
             "columnDefs": [{
                "className": "dt-right",
                "targets": [3,5,9,10,11,12,13,14],
            }],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                pageTotal9 = api.column( 9, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                $( api.column( 9 ).footer() ).html( pageTotal9.toFixed(3) );
        
                pageTotal10 = api.column( 10, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                $( api.column( 10 ).footer() ).html( pageTotal10.toFixed(3) );
                
                pageTotal11 = api.column( 11, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                $( api.column( 11 ).footer() ).html( pageTotal11.toFixed(3) );
                
                pageTotal13 = api.column( 13, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                $( api.column( 13 ).footer() ).html( pageTotal13.toFixed(3) );
                
                pageTotal14 = api.column( 14, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                $( api.column( 14 ).footer() ).html( pageTotal14.toFixed(3) );
            }
        });
        
        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');
        
        $(document).on('click', '#search', function (){
            $("#ajax-loader").show();
            table.draw();
        });

        $(document).on('click', '.wastage_change_approve' , function(){
            var sell_item_id = $(this).attr('id');
            if($(this). prop("checked") == true){
                var wastage = '1_1';
            } else {
                var wastage = '1_0';
            }
            if(sell_item_id != '' && sell_item_id != null){
                $.ajax({
                    url: "<?= base_url('sell/item_wastage_approve_action') ?>",
                    type: 'POST',
                    data: {sell_item_id : sell_item_id, wastage : wastage},
                    success: function (response) {
                        table.draw();
                    }
                });
            }
        });
        
    });
</script>
