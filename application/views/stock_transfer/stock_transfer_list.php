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
            Stock Transfer List
            <?php
            $isView = $this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?=base_url('stock_transfer/stock_transfer') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Stock Transfer</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>From Date</label> <label style="font-size: 10px"> Everything From Start <input type="checkbox" name="everything_from_start" id="everything_from_start" ></label>
                                    <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y", strtotime("first day of this month")); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label>To Date</label>
                                    <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y'); ?>">
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
                                <div class="col-md-2">
                                    <label>Guard Checked</label>
                                    <select name="guard_checked_filter" id="guard_checked_filter" class="form-control select2">
                                        <option value="all">All</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                <div class="clearfix"></div>
                                <?php if($isView) { ?>
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="lot_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 100px;">Action</th>
                                                    <th style="width: 50px;">Guard</th>
                                                    <th style="width: 70px;">Ref. No.</th>
                                                    <th>From Department</th>
                                                    <th>To Department</th>
                                                    <th>Transfer Date</th>
                                                    <th>Narration</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" id="clicked_item_id" value="-1" >
<div id="myModal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Item List</h4>
            </div>
            <div class="modal-body">
                <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Item Name</th>
                            <th>Tunch</th>
                            <th>Gross.Wt</th>
                            <th>Less</th>
                            <th>Net.Wt</th>
                            <th>Wastage</th>
                            <th>Fine</th>
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
</div>
<div id="guard_checked_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Guard Checked</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="guard_checked_stock_transfer_id" id="guard_checked_stock_transfer_id">
                <input type="hidden" name="guard_checked_first_at_hidden" id="guard_checked_first_at_hidden">
                <input type="checkbox" name="guard_checked" id="guard_checked_checkbox" value="1" >
                <label for="guard_checked_checkbox">Guard Checked</label>
                <div class="clearfix"></div>
                <label for="guard_checked_narration">Narration</label><br />
                <textarea name="guard_checked_narration" id="guard_checked_narration" rows="4" class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                <div class="pull-left created_updated_info" style="margin: -15px -15px -15px 0px;">
                    <label>First Checked at</label> : <span id="guard_checked_first_at"></span><br />
                    <label>Last Updated at</label> : <span id="guard_checked_last_at"></span>
                </div>
                <button type="button" class="btn btn-primary" id="apply_guard_checked">Apply Changes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                <input type="hidden" name="audit_status_pay_rec_id" id="audit_status_pay_rec_id">
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
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('#ajax-loader').show();
        table = $('#lot_master_table').DataTable({
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
                "url": "<?php echo site_url('stock_transfer/stock_transfer_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                    d.audit_status_filter = $('#audit_status_filter').val();
                    d.guard_checked_filter = $('#guard_checked_filter').val();
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
                "url": "<?php echo site_url('stock_transfer/stock_transfer_detail_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.stock_transfer_id = $('#clicked_item_id').val()
                },
            },
             "columnDefs": [{
                    "className": "dt-right",
                    "targets": [2,3,4,5,6,7],
                }]
        });
        
        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');
        
        $(document).on('click', '.item_row', function () {
            $('#clicked_item_id').val($(this).attr('data-sell_id'));
            item_table.draw();
            $('#myModal').modal('show');
        });
        
        $(document).on('click', '#search', function () {
            $("#ajax-loader").show();
            table.draw();
        });
        
        $(document).on("click", ".audit_status_button", function () {
            var audit_status_pay_rec_id = $(this).attr('data-audit_status_pay_rec_id');
            var audit_status = $(this).attr('data-audit_status');
            $('#audit_status_pay_rec_id').val(audit_status_pay_rec_id);
            $('#audit_status_value').val(audit_status);
            $('input:radio[name=audit_status][id=audit_status_'+ audit_status +']').prop('checked', true);
            $('#audit_status_modal').modal('show');
        });
        
        $("#audit_status_modal").on("hidden.bs.modal", function () {
            $('#audit_status_pay_rec_id').val('');
            $('#audit_status_value').val('');
        });
        
        $(document).on("change", "input[type=radio][name=audit_status]", function () {
            var allow_to_audit = 0;
            <?php if($this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "allow to audit / suspect")){ ?>
                allow_to_audit = 1;
            <?php } ?>
            var allow_audit_to_pending = 0;
            <?php if($this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "allow audit / suspect to pending")){ ?>
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
            var audit_status_pay_rec_id = $('#audit_status_pay_rec_id').val();
            var audit_status = $("input[name='audit_status']:checked").val();
            if(audit_status_pay_rec_id != '' && audit_status != ''){
                var value = confirm('Are you sure to Change Audit Status?');
                if (value) {
                    $("#ajax-loader").show();
                    $.ajax({
                        url: "<?php echo site_url('stock_transfer/audit_status_stock') ?>",
                        type: "POST",
                        async: false,
                        data: {audit_status_pay_rec_id : audit_status_pay_rec_id, audit_status : audit_status},
                        success: function (response) {
                            var json = $.parseJSON(response);
                            $("#ajax-loader").hide();
                            if (json['success'] == 'Changed') {
                                table.draw();
                                show_notify('Audit Status Changed Successfully!', true);
                                $('#audit_status_modal').modal('hide');
                            } else {
                                show_notify('Somthing was Wrong!', true);
                            }
                        }
                    });
                }
            }
        });
        
        $(document).on("click", ".delete_stock_transfer", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Stock Transfer. This Stock Transfer has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Stock Transfer Deleted Successfully!', true);
                        }
                    }
                });
            }
        });
        
        $(document).on("click", ".guard_checked_button", function () {
            var guard_checked_stock_transfer_id = $(this).attr('data-guard_checked_stock_transfer_id');
            var guard_checked = $(this).attr('data-guard_checked');
            $('#guard_checked_stock_transfer_id').val(guard_checked_stock_transfer_id);
            if(guard_checked == '1'){
                $('input:checkbox[name=guard_checked]').prop('checked', true);
            }
            $.ajax({
                url: "<?php echo site_url('stock_transfer/get_stock_transfer_details') ?>",
                type: "POST",
                async: false,
                data: {guard_checked_stock_transfer_id : guard_checked_stock_transfer_id},
                success: function (response) {
                    var json = $.parseJSON(response);
                    $('#guard_checked_narration').val(json['guard_checked_narration']);
                    $('#guard_checked_first_at_hidden').val(json['guard_checked_first_at']);
                    $('#guard_checked_first_at').html(json['guard_checked_first_at']);
                    $('#guard_checked_last_at').html(json['guard_checked_last_at']);
                    $('#guard_checked_modal').modal('show');
                }
            });
        });
        $("#guard_checked_modal").on("hidden.bs.modal", function () {
            $('#guard_checked_stock_transfer_id').val('');
            $('input:checkbox[name=guard_checked]').prop('checked', false);
            $('#guard_checked_narration').val('');
            $('#guard_checked_first_at_hidden').val('');
            $('#guard_checked_first_at').val('');
            $('#guard_checked_last_at').val('');
        });
        $(document).on("click", "#apply_guard_checked", function () {
            var guard_checked_stock_transfer_id = $('#guard_checked_stock_transfer_id').val();
            var guard_checked = $("input[name='guard_checked']:checked").val();
            var guard_checked_narration = $("#guard_checked_narration").val();
            var guard_checked_first_at_hidden = $("#guard_checked_first_at_hidden").val();
            if(guard_checked_stock_transfer_id != '' && guard_checked != ''){
                var value = confirm('Are you sure to Apply Changes?');
                if (value) {
                    $("#ajax-loader").show();
                    $.ajax({
                        url: "<?php echo site_url('stock_transfer/guard_checked_stock_transfer') ?>",
                        type: "POST",
                        async: false,
                        data: {guard_checked_stock_transfer_id : guard_checked_stock_transfer_id, guard_checked : guard_checked, guard_checked_narration : guard_checked_narration, guard_checked_first_at_hidden : guard_checked_first_at_hidden},
                        success: function (response) {
                            var json = $.parseJSON(response);
                            $("#ajax-loader").hide();
                            if (json['success'] == 'Changed') {
                                table.draw();
                                show_notify('Guard Checked Changes Applyed Successfully!', true);
                                $('#guard_checked_modal').modal('hide');
                            } else {
                                show_notify('Somthing was Wrong!', true);
                            }
                        }
                    });
                }
            }
        });
        
    });
</script>
