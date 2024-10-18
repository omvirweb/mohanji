<?php $this->load->view('success_false_notify'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Account List
            <?php
            $isView = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?= base_url('account/account'); ?>" class="btn btn-primary pull-right btn-sm">Add New</a>
            <?php } ?> 
        </h1>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if($isView) { ?>
            <div class="col-md-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="0">All</option>
                                <option value="1">Approve</option>
                                <option value="2">Not Approve</option>
                            </select>
                        </div>
                        <!---content start --->
                        <table class="table row-border table-bordered table-striped" style="width:100%" id="example">
                            <thead>
                                <tr>
                                    <th style="width:80px;">Action</th>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Mobile</th>
                                    <th>Account Group</th>
                                    <th>Is Supplier?</th>
                                    <th>Interest</th>
                                    <th>Credit Limit</th>
                                    <th>OB In Gold</th>
                                    <th>Credit / Debit</th>
                                    <th>OB In Silver</th>
                                    <th>Credit / Debit</th>
                                    <th>OB In Rupees</th>
                                    <th>Credit / Debit</th>
                                    <th>Bank Name</th>
                                    <th>Bank Account No</th>
                                    <th>IFSC Code</th>
                                    <th>Bank Interest</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!---content end--->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <?php } ?>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
<input type="hidden" id="clicked_item_id" value="-1" >
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">

         Modal content
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
                            <th>Wastage</th>
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
<input type="hidden" id="clicked_ledger_id" value="-1" >
<div id="ledfer_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ledger</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="m_account_id" value="">
                <div class="col-md-3">
                    <label>From Date<span class="required-sign">&nbsp;*</span></label>
                    <input type="text" id="datepicker1" class="form-control from_date" tabindex="1" required="" value="<?php echo date('d-m-Y');?>">
                </div>
                <div class="col-md-3">
                    <label>To Date<span class="required-sign">&nbsp;*</span></label>
                    <input type="text" id="datepicker2" class="form-control to_date" tabindex="2" required="" value="<?php echo date('d-m-Y');?>">
                </div>
                <div class="col-md-4">
                    <br/>
                    <button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                    <button type="submit" class="btn btn-primary btn-sm print_btn" style="vertical-align: bottom;"><span class="fa fa-print">&nbsp;</span> Print</button>
                </div>
                <br /><br />
                <table id="ledger_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Sell No</th>
                            <th>Sell Date</th>
                            <th>Delivered / Not Delivered</th>
                            <th>Grwt</th>
                            <th>Gold Fine</th>
                            <th>Silver Fine</th>
                            <th>Amount</th>
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
<div id="account_approve_modal" class="modal fade" role="dialog">
    <input type="hidden" id="approve_account_id">
    <div class="modal-dialog modal-sm">
         Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Approve Account</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="approve_account_group_id" class="control-label"> Account Group<span class="required-sign">&nbsp;*</span></label>
                    <select name="approve_account_group_id" id="approve_account_group_id" class="form-control account_group_id select2" ></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_approve_account">Approve</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        $('#status').select2();
        $('#ajax-loader').show();
        initAjaxSelect2($("#approve_account_group_id"), "<?= base_url('app/account_group_select2_source_for_account/') ?>");

        table = $('#example').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "ordering": [1, "desc"],
            "order": [],
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('account/account_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.status = $('#status').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [8, 9, 10, 12, 14, 19],
            }]
        });
    
        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('account/account_item_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.account_id = $('#clicked_item_id').val()
                },
            },
             "columnDefs": [{
                    "className": "dt-right",
                    "targets": [],
                }]
        });
        
        ledger_table = $('#ledger_table').DataTable({
            "serverSide": true,
//            "scrollY": "480px",
//            "scrollX": true,
//            "search": true,
            "paging": false,
//            "ordering":[1, "desc"],
//            "order": [],
            "ajax": {
                "url": "<?php echo site_url('account/ledger_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.account_id = $('#m_account_id').val();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [4,5,6,7],
            }]
        });
        
        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');
        
        $(document).on('click', '.item_row', function () {
            $('#clicked_item_id').val($(this).attr('data-account_id'));
            item_table.draw();
            $('#myModal').modal('show');
        });
        
        $(document).on('click', '.ledger', function () {
            $('#ledfer_modal').modal('show');
            $('#m_account_id').val($(this).attr('data-account_id'));
        });
        
        $(document).on('click', '#search', function (){
            ledger_table.draw();
        });
        
        $(document).on('click', '.print_btn', function () {
            if ($.trim($(".from_date").val()) == '') {
                show_notify('Please Enter From Date.', false);
                $(".from_date").focus();
                return false;
            }
            if ($.trim($(".to_date").val()) == '') {
                show_notify('Please Enter To Date.', false);
                $(".to_date").focus();
                return false;
            }
            var m_account_id = $('#m_account_id').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            var href = "<?php echo base_url('account/ledger_print/') ?>" + m_account_id + "/" + from_date + "/" + to_date;
            window.open(href, '_blank');
        });
        
        $(document).on('click', '.print_btn', function () {
            if ($.trim($(".from_date").val()) == '') {
                show_notify('Please Enter From Date.', false);
                $(".from_date").focus();
                return false;
            }
            if ($.trim($(".to_date").val()) == '') {
                show_notify('Please Enter To Date.', false);
                $(".to_date").focus();
                return false;
            }
            var m_account_id = $('#m_account_id').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            var href = "<?php echo base_url('account/ledger_print/') ?>" + m_account_id + "/" + from_date + "/" + to_date;
            //window.open(href, '_blank');
        });

        $(document).on("click", ".delete_button", function () {
            var value = confirm('Are you sure delete this Account?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=account_id&table_name=account',
                    success: function (response) {
                        console.log(response);
                        var json = $.parseJSON(response);

                        if(json.success==false){
                            show_notify(json.message, false);
                        }

                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Account. This Account has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Account Deleted Successfully!', true);
                        }
                    }
                });
            }
        });
        
        $(document).on("click", ".ac_dc_button", function () {
            if($(this).data("status") == '0'){
                var status = 'Deactivate';
                var status_id = '0';
            } else {
                var status = 'Activate';
                var status_id = '1';
            }
            var value = confirm('Are you sure to '+status+' ?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'status='+status_id,
                    success: function (response) {
                        table.draw();
                        show_notify('Account <b>'+status+'</b> Successfully!', true);
                    }
                });
            }
        });
        
        $(document).on("click", ".change_approve_status", function () {
            var account_id = $(this).attr('data-account_id');
            var confirm_only = $(this).attr('data-confirm_only');
            if(confirm_only == 1) {
                var value = confirm('Are you sure approve this Account?');
                if (value) {
                    $.ajax({
                        url: "<?php echo site_url('account/approve_account/') ?>"+account_id,
                        type: "GET",
                        data: '',
                        success: function (data) {
                            table.draw();
                            show_notify('Approved Successfully!', true);
                        }
                    });    
                }                
            } else {
                $("#approve_account_id").val(account_id);
                $("#account_approve_modal").modal('show');    
            }            
        });
        
        $(document).on("click", "#btn_approve_account", function () {
            var account_id = $("#approve_account_id").val();
            var account_group_id = $("#approve_account_group_id").val();
            if(account_group_id == '' || account_group_id == null) {
                show_notify("Please Select Account Group",false);
                $("#approve_account_group_id").select2('open');
                return false;
            }

            $.ajax({
                url: "<?php echo site_url('account/approve_account/') ?>"+account_id+"/"+account_group_id,
                type: "GET",
                data: '',
                success: function (data) {
                    $("#account_approve_modal").modal('hide');
                    table.draw();
                    show_notify('Approved Successfully!', true);
                }
            });
        });
        
        $('#account_approve_modal').on('hidden.bs.modal', function () {
            $("#approve_account_id").val(0);
            $("#approve_account_group_id").val(null).trigger('change');
        });

        $(document).on('change', '#status', function(){
            $('#ajax-loader').show();
            table.draw();
        });
    });

    <?php /*$(document).ready(function(){
     table = $('.account-table').DataTable({
     "serverSide": true,
     "ordering": true,
     "searching": true,
     "aaSorting": [[1, 'asc']],
     "ajax": {
     "url": "<?php echo base_url('account/account_datatable') ?>",
     "type": "POST"
     },
     "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT; ?>',
     "scroller": {
     "loadingIndicator": true
     },
     columnDefs: [{
     orderable: false,
     className: 'select-checkbox',
     targets: 0
     }],
     select: {
     style: 'os',
     selector: 'td:first-child'
     }
     });
     
     
     
     });*/ ?>
</script>
