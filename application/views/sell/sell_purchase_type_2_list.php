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
            <?=$page_label?> List
            <?php
            $isView = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
            <a href="<?=$entry_page_url;?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add <?=$page_label?></a>
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
                                        <input type="hidden" name="sell_purchase" id="sell_purchase" value="<?=$sell_purchase?>">
                                        <?php if(isset($view_not) && !empty($view_not)){ ?> <div class="filter" hidden=""> <?php } else { ?>
                                        <div class="filter">
                                        <?php } ?>
                                            <div class="col-md-3">
                                                <label>From Date</label>
                                                <label style="font-size: 10px">Everything From Start <input type="checkbox" name="everything_from_start" id="everything_from_start" ></label>
                                                <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y", strtotime("first day of this month"));?>">
                                                <!--<input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="">-->
                                            </div>
                                            <div class="col-md-3">
                                                <label>To Date</label>
                                                <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y');?>">
                                                <!--<input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="">-->
                                            </div>
                                            <div class="col-md-3">
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
                                            <div class="col-md-2">
                                                <label>Audit Status</label>
                                                <select name="audit_status_filter" id="audit_status_filter" class="form-control select2">
                                                    <option value="all">All</option>
                                                    <option value="<?php echo AUDIT_STATUS_PENDING; ?>">Pending</option>
                                                    <option value="<?php echo AUDIT_STATUS_AUDITED; ?>">Audited</option>
                                                    <option value="<?php echo AUDIT_STATUS_SUSPECTED; ?>">Suspected</option>
                                                </select>
                                            </div>
                                            <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                        </div>
                                        <table id="lot_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%;">Action</th>
                                                    <th>Account Name</th>
                                                    <th>Department</th>
                                                    <th>Sell No</th>
                                                    <th  width="80px" class="text-nowrap">Sell Date</th>
                                                    <th>Remark</th>
                                                    <th>Delivered / Not Delivered</th>
                                                    <?php if(isset($view_not) && !empty($view_not)){ ?>
                                                    <th>Delivery</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
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
<input type="hidden" id="clicked_item_id" value="-1" >
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 100%">

         Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sell Item List</h4>
            </div>
            <div class="modal-body">
                <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sell Item No</th>
                            <th>Type</th>
                            <th>Category Name</th>
                            <th>Item Name</th>
                            <th>Gross.Wt</th>
                            <th>Stone Wt</th>
                            <th>Sijat</th>
                            <th>Less</th>
                            <th>Net.Wt</th>
                            <th>Tunch</th>
                            <th>W/L</th>
                            <th>W/L Value</th>
                            <th>Labour Amount</th>
                            <th>Fine Gold/Silver</th>
                            <th>Gold/Silver Rate</th>
                            <th>Gold/Silver Amount</th>
                            <th>Stone Qty</th>
                            <th>Stone Rs.</th>
                            <th>Charge</th>
                            <th>Total Amount</th>
                            <th>Narration</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clicked_pay_rec_id" value="-1" >
<div id="pay_rec_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

         Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Payment Receipt List</h4>
            </div>
            <div class="modal-body">
                <table id="pay_rec_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Payment Receipt</th>
                            <th>Cash/Cheque</th>
                            <th>Bank Name</th>
                            <th>Amount</th>
                            <th>Narration</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clicked_metal_pr_id" value="-1" >
<div id="metal_pr_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

         Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Metal Issue Receive List</h4>
            </div>
            <div class="modal-body">
                <table id="metal_pr_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Issue Receive</th>
                            <th>Item Name</th>
                            <th>Gross.Wt</th>
                            <th>Tunch</th>
                            <th>Fine</th>
                            <th>Narration</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clicked_gold_id" value="-1" >
<div id="gold_bhav_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

         Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gold Bhav List</h4>
            </div>
            <div class="modal-body">
                <table id="gold_bhav_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sale / Purchase</th>
                            <th>Weight</th>
                            <th>Rate</th>
                            <th>Value</th>
                            <th>Narration</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clicked_silver_id" value="-1" >
<div id="silver_bhav_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

         Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Silver Bhav List</h4>
            </div>
            <div class="modal-body">
                <table id="silver_bhav_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sale / Purchase</th>
                            <th>Weight</th>
                            <th>Rate</th>
                            <th>Value</th>
                            <th>Narration</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clicked_transfer_id" value="-1" >
<div id="transfer_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

         Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Transfer List</h4>
            </div>
            <div class="modal-body">
                <table id="transfer_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Naam / Jaama</th>
                            <th>Party</th>
                            <th>Gold</th>
                            <th>Silver</th>
                            <th>Amount</th>
                            <th>Narration</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body edit-content">
                <img id="doc_img_src" alt="No Image Found" class="img-responsive" height='300px' width='600px'>
            </div>
        </div>
    </div>
</div>
<div id="audit_status_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Audit Status</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="audit_status_sell_id" id="audit_status_sell_id">
                <input type="hidden" name="audit_status_value" id="audit_status_value">
                <input type="radio" name="audit_status" id="audit_status_<?php echo AUDIT_STATUS_PENDING; ?>" value="<?php echo AUDIT_STATUS_PENDING; ?>" >
                <label for="audit_status_<?php echo AUDIT_STATUS_PENDING; ?>">Pending</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="audit_status" id="audit_status_<?php echo AUDIT_STATUS_AUDITED; ?>" value="<?php echo AUDIT_STATUS_AUDITED; ?>" >
                <label for="audit_status_<?php echo AUDIT_STATUS_AUDITED; ?>">Audited</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="audit_status" id="audit_status_<?php echo AUDIT_STATUS_SUSPECTED; ?>" value="<?php echo AUDIT_STATUS_SUSPECTED; ?>" >
                <label for="audit_status_<?php echo AUDIT_STATUS_SUSPECTED; ?>">Suspected</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="change_audit_status">Change Status</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('#delivery_type').select2();
        
        table = $('#lot_master_table').DataTable({
            <?php if(isset($view_not) && !empty($view_not)){ ?>
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    title: 'Not Delivered List',
                    exportOptions: {
                        columns: [ 1, 2, 3, 4, 5 ]
                    }
                }
            ],
            <?php } ?>
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
//            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('sell_purchase_type_2/sp_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    <?php if(isset($view_not) && !empty($view_not)){ ?>
                    <?php } else { ?>
                        d.from_date = $('#datepicker1').val();
                        d.to_date = $('#datepicker2').val();
                    <?php } ?>
                    d.delivery_type = $('#delivery_type').val();
                    <?php if(isset($view_not) && !empty($view_not)){ ?>
                        d.check = '1';
                    <?php } ?>
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                    d.audit_status_filter = $('#audit_status_filter').val();
                    d.sell_purchase = $('#sell_purchase').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [],
            }]
        });
        
        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('sell_purchase_type_2/sp_item_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.sell_id = $('#clicked_item_id').val()
                },
            },
             "columnDefs": [{
                    "className": "dt-right",
                    "targets": [4,5,6,7,8,9,11,12,13,14,15,16,17,18,19],
                }]
        });
        
        pay_rec_table = $('#pay_rec_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('sell/pay_rec_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.pay_rec_id = $('#clicked_pay_rec_id').val()
                },
            },
             "columnDefs": [{
                    "className": "dt-right",
                    "targets": [3],
                }]
        });
        
        metal_pr_table = $('#metal_pr_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('sell/metal_pr_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.metal_pr_id = $('#clicked_metal_pr_id').val()
                },
            },
             "columnDefs": [{
                    "className": "dt-right",
                    "targets": [2,3,4],
                }]
        });
        
        gold_bhav_table = $('#gold_bhav_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('sell/gold_bhav_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.gold_id = $('#clicked_gold_id').val()
                },
            },
             "columnDefs": [{
                    "className": "dt-right",
                    "targets": [1,2,3],
                }]
        });
        
        silver_bhav_table = $('#silver_bhav_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('sell/silver_bhav_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.silver_id = $('#clicked_silver_id').val()
                },
            },
             "columnDefs": [{
                    "className": "dt-right",
                    "targets": [1,2,3],
                }]
        });
        
        transfer_table = $('#transfer_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('sell/transfer_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.transfer_id = $('#clicked_transfer_id').val()
                },
            },
             "columnDefs": [{
                    "className": "dt-right",
                    "targets": [2,3,4],
                }]
        });
        
        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');
        
        $(document).on('click', '#search', function (){
            $("#ajax-loader").show();
            table.draw();
        });
        
        $(document).on('click', '.item_row', function () {
            $('#clicked_item_id').val($(this).attr('data-sell_id'));
            item_table.draw();
            $('#myModal').modal('show');
        });
        
        $(document).on('click', '.pay_rec_id', function () {
            $('#clicked_pay_rec_id').val($(this).attr('data-pay_rec_id'));
            pay_rec_table.draw();
            $('#pay_rec_modal').modal('show');
        });
        
        $(document).on('click', '.metal_pr_id', function () {
            $('#clicked_metal_pr_id').val($(this).attr('data-metal_pr_id'));
            metal_pr_table.draw();
            $('#metal_pr_modal').modal('show');
        });
        
        $(document).on('click', '.gold_id', function () {
            $('#clicked_gold_id').val($(this).attr('data-gold_id'));
            gold_bhav_table.draw();
            $('#gold_bhav_model').modal('show');
        });
        
        $(document).on('click', '.silver_id', function () {
            $('#clicked_silver_id').val($(this).attr('data-silver_id'));
            silver_bhav_table.draw();
            $('#silver_bhav_model').modal('show');
        });
        
        $(document).on('click', '.transfer_id', function () {
            $('#clicked_transfer_id').val($(this).attr('data-transfer_id'));
            transfer_table.draw();
            $('#transfer_model').modal('show');
        });
        
        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");
            $("#doc_img_src").attr('src', src);
            $('#edit-modal').modal('show');
        });
        
        $(document).on('change', '.update_delivery_type', function () {
            if ($('input.check_delivery').is(':checked')) {
                if (confirm('Are you sure delivered this records?')) {
                    $.ajax({
                        url: $(this).data('href'),
                        type: "POST",
                        data: '',
                        success: function (data) {
                            table.draw();
                            show_notify('<?=$page_label?> Delivered Successfully!', true);
                        }
                    });
                }
            }
        });
    
        $(document).on("click", ".delete_sell", function () {
            if (confirm('Are you sure delete this records?')) {
                var sell_id = $(this).data('sell_id');
                var href_url = $(this).data('href');
                $.ajax({
                    url: "<?php echo site_url('sell/check_rfid_in_lineitems_or_not') ?>" + '/' + sell_id,
                    type: "POST",
                    data: '',
                    async: false,
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['have_rfid'] == '1') {
                            if (confirm('If you have RFID, Then Click on Ok Button to open RIFD, Otherwise Click on Cancel Button to Inc. Loose Stock.')) {
                                href_url = href_url + '/1';
                                delete_sell(href_url);
                            } else {
                                delete_sell(href_url);
                            }
                        } else {
                            delete_sell(href_url);
                        }
                    }
                });
            }
        });
        
        $(document).on("click", ".audit_status_button", function () {
            var audit_status_sell_id = $(this).attr('data-audit_status_sell_id');
            var audit_status = $(this).attr('data-audit_status');
            $('#audit_status_sell_id').val(audit_status_sell_id);
            $('#audit_status_value').val(audit_status);
            $('input:radio[name=audit_status][id=audit_status_'+ audit_status +']').prop('checked', true);
            $('#audit_status_modal').modal('show');
        });
        
        $("#audit_status_modal").on("hidden.bs.modal", function () {
            $('#audit_status_sell_id').val('');
            $('#audit_status_value').val('');
        });
        
        $(document).on("change", "input[type=radio][name=audit_status]", function () {
            var allow_to_audit = 0;
            <?php if($this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow to audit / suspect")){ ?>
                allow_to_audit = 1;
            <?php } ?>
            var allow_audit_to_pending = 0;
            <?php if($this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow audit / suspect to pending")){ ?>
                allow_audit_to_pending = 1;
            <?php } ?>
            if ((this.value == '2' || this.value == '3') && allow_to_audit != 1) {
                var audit_status_value = $('#audit_status_value').val();
                $('input:radio[name=audit_status][id=audit_status_'+ audit_status_value +']').prop('checked', true);
                show_notify('Not Allow to Audit / Suspect!', false);
                return false;
            }
            if (this.value == '1' && allow_audit_to_pending != 1) {
                var audit_status_value = $('#audit_status_value').val();
                $('input:radio[name=audit_status][id=audit_status_'+ audit_status_value +']').prop('checked', true);
                show_notify('Not Allow Audit / Suspect to Pending!', false);
                return false;
            }
        });
        
        $(document).on("click", "#change_audit_status", function () {
            var audit_status_sell_id = $('#audit_status_sell_id').val();
            var audit_status = $("input[name='audit_status']:checked").val();
            if(audit_status_sell_id != '' && audit_status != ''){
                var value = confirm('Are you sure to Change Audit Status?');
                if (value) {
                    $("#ajax-loader").show();
                    $.ajax({
                        url: "<?php echo site_url('sell/audit_status_sell') ?>",
                        type: "POST",
                        async: false,
                        data: {audit_status_sell_id : audit_status_sell_id, audit_status : audit_status},
                        success: function (response) {
                            var json = $.parseJSON(response);
                            $("#ajax-loader").hide();
                            if (json['success'] == 'Changed') {
                                table.draw();
                                show_notify('Audit Status Changed Successfully!', true);
                                $('#audit_status_modal').modal('hide');
                            } else {
                                show_notify('Somthing was Wrong!', false);
                            }
                        }
                    });
                }
            }
        });
        
    });
    
    function delete_sell(href_url){
        $.ajax({
            url: href_url,
            type: "POST",
            data: '',
            async: false,
            success: function (response) {
                var json = $.parseJSON(response);
                if (json['error'] == 'Error') {
                    show_notify('You cannot delete this <?=$page_label?>. This <?=$page_label?> has been used.', false);
                } else if (json['success'] == 'Deleted') {
                    table.draw();
                    show_notify('<?=$page_label?> Deleted Successfully!', true);
                }
            }
        });
    }
</script>
<?php if($this->session->flashdata('saveformwithprint') === true  && $this->session->flashdata('saveformwithprinturl') != '') { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var a = $("<a>")
                .attr("href", '<?php echo base_url() . $this->session->flashdata('saveformwithprinturl') ?>')
                .attr("target", "_blank")
                .appendTo("body");
            a[0].click();
            a.remove();
        });
    </script>
<?php }
