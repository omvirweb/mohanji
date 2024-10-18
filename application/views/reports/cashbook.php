<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <form class="form-horizontal" action="" method="post" id="cashbook_form" novalidate enctype="multipart/form-data">
        <?php if (isset($payment_receipt_data->pay_rec_id) && !empty($payment_receipt_data->pay_rec_id)) { ?>
            <input type="hidden" name="pay_rec_id" class="pay_rec_id" value="<?php echo $payment_receipt_data->pay_rec_id; ?>">
        <?php } ?>
        <section class="content-header">
            <h1>
                Cash Book <a href="<?= base_url('reports/cashbook') ?>" class="btn btn-primary btn-xs" style="margin: 5px;" ><i class="fa fa-refresh"></i></a>
                <?php if(!isset($payment_receipt_data->audit_status) || (isset($payment_receipt_data->audit_status) && $payment_receipt_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
                    <button type="submit" class="btn btn-primary pull-right module_save_btn btn-sm" ><?= isset($payment_receipt_data->pay_rec_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                <?php } ?>
            </h1>
            <?php
                $isAdd = $this->app_model->have_access_role(CASHBOOK_MODULE_ID, "add");
                $isEdit = $this->app_model->have_access_role(CASHBOOK_MODULE_ID, "edit");
                $allow_change_date = $this->app_model->have_access_role(CASHBOOK_MODULE_ID, "allow_change_date");
            ?>
        </section>
        <?php if($isAdd || $isEdit) { ?>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <div class="col-md-12 form-group">
                                        <?php if(isset($payment_receipt_data->pay_rec_id) && !empty($payment_receipt_data->pay_rec_id)) { ?>
                                            <div class="col-md-2">
                                                <label>Vou.No.</label>
                                                <b><input type="text" name="voucher_no" id="voucher_no" class="form-control" readonly="" value="<?= isset($payment_receipt_data->voucher_no) ? $payment_receipt_data->voucher_no : ''; ?>"></b><br />
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <label for="transaction_date">Transaction Date<span style="color: red">*</span></label>
                                            <input type="text" name="transaction_date" id="datepicker1" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control input-datepicker" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?= isset($payment_receipt_data->transaction_date) ? date('d-m-Y', strtotime($payment_receipt_data->transaction_date)) : date('d-m-Y'); ?>"><br />
                                        </div>
                                        <div class="col-md-3">
                                            <label for="payment_receipt">Type<span style="color: red">*</span></label>
                                            <select name="payment_receipt" id="payment_receipt" class="form-control select2">
                                                <option value="">--Select--</option>
                                                <option value="1" <?= isset($payment_receipt_data->payment_receipt) && ($payment_receipt_data->payment_receipt == 1) ? 'selected' : ''; ?>>Payment</option>
                                                <option value="2" <?= isset($payment_receipt_data->payment_receipt) ? ($payment_receipt_data->payment_receipt == 2) ? 'selected' : '' : 'selected'?>>Receipt</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-2">
                                            <label>Department<span style="color: red">*</span></label>
                                            <select name="department_id" id="department_id" class="form-control select2"></select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Account Name<span style="color: red">*</span></label>
                                            <select name="account_id" id="account_id" class="form-control select2"></select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>On Behalf Of<span style="color: red">*</span></label>
                                            <select name="on_behalf_of" id="on_behalf_of" class="form-control select2"></select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Amount<span style="color: red">*</span></label>
                                            <b> <input type="text" name="amount" id="amount" class="form-control num_only" value="<?= isset($payment_receipt_data->amount) ? $payment_receipt_data->amount : '0'; ?>"></b><br />
                                        </div>
                                        <div class="col-md-3">
                                            <label> Narration</label><br />
                                            <textarea name="narration" id="narration" rows="2" cols="37"><?= isset($payment_receipt_data->narration) ? $payment_receipt_data->narration : ''; ?></textarea>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-2">
                                            <div class="department_balance" id="department_balance"></div>
                                            <div class="all_department_balance" id="all_department_balance"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="account_balance" id="account_balance"></div>
                                        </div>
                                        <?php if (isset($payment_receipt_data->pay_rec_id) && !empty($payment_receipt_data->pay_rec_id)) { ?>
                                        <div class="created_updated_info" style="margin-left: 15px;">
                                            Created by : <?= isset($payment_receipt_data->created_by_name) ? $payment_receipt_data->created_by_name : '' ?>
                                            @ <?= isset($payment_receipt_data->created_at) ? date ('d-m-Y h:i A', strtotime($payment_receipt_data->created_at)) : '' ?><br/>
                                            Updated by : <?= isset($payment_receipt_data->updated_by_name) ? $payment_receipt_data->updated_by_name : '' ?>
                                            @ <?= isset($payment_receipt_data->updated_at) ? date('d-m-Y h:i A', strtotime($payment_receipt_data->updated_at)) :'' ; ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </form>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12 form-group">
                                    <div class="col-md-2">
                                        <label>Form Amount</label>
                                        <input type="text" name="from_amount" id="from_amount" class="form-control num_only" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>To Amount</label>
                                        <input type="text" name="to_amount" id="to_amount" class="form-control num_only" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Form Date<span style="color: red">*</span></label>
                                        <label style="font-size: 10px">Everything From Start</small> <input type="checkbox" name="everything_from_start" id="everything_from_start" ></label>
                                        <input type="text" name="from_date" id="datepicker2" class="form-control from_date" value="<?php echo date("d-m-Y");?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>To Date<span style="color: red">*</span></label>
                                        <input type="text" name="to_date" id="datepicker3" class="form-control to_date" value="<?= date('d-m-Y'); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Department</label>
                                        <select name="department_filter" id="department_filter" class="form-control select2">
                                            <option value="">All</option>
                                            <?php 
                                                if(isset($department_filter) && !empty($department_filter)){
                                                foreach ($department_filter as $value) {
                                                    echo '<option value="'.$value->process_id.'">'.$value->process_name.'</option>';
                                                }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Account Name</label>
                                        <select name="" id="account_filter" class="form-control select2"></select>
                                    </div>
                                    <div class="clearfix"></div><br />
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
                                        <label>Opening Balance</label>
                                        <b><input type="text" style="text-align:right" name="opening_balance" id="opening_balance" class="form-control" readonly="" val=""></b>
                                    </div>
                                    <div class="col-md-8">
                                        <a class="btn btn-primary btn-sm pull-right" id="search_button">Search</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <meta name="viewport" content="width=device-width, initial-scale=1">
                                        <h6 align="right">.</h6>
                                        <h4 align="center">Receipt Table</h4>
                                        <table id="receipt_table" align="center" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Received?</th>
                                                    <th>A/C.Name</th>
                                                    <th>Department</th>
                                                    <th>Amount(Cr)</th>
                                                    <th>Vno</th>
                                                    <th>Narration</th>
                                                    <th width="80px" class="text-nowrap">Date</th>
                                                    <th>Is Received</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" style="text-align: right;">Total</th>
                                                    <th></th>
                                                    <th colspan="4"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <meta name="viewport" content="width=device-width, initial-scale=1">
                                        <h6 align="right">.</h6>
                                        <h4 align="center">Payment Table</h4>
                                        <table id="payment_table" align="center" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Received?</th>
                                                    <th>A/C.Name</th>
                                                    <th>Department</th>
                                                    <th>Amount(Dr)</th>
                                                    <th>Vno</th>
                                                    <th>Narration</th>
                                                    <th width="80px" class="text-nowrap">Date</th>
                                                    <th>Is Received</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" style="text-align: right;">Total</th>
                                                    <th></th>
                                                    <th colspan="4"></th>
                                                </tr>
                                            </tfoot>
                                        </table><br>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <div class="col-md-2">
                                        <label>Closing Balance</label>
                                        <b><input type="text" style="text-align:right" readonly="" name="closing_balance" id="closing_balance" class="form-control" value=""></b><br>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Today Balance</label>
                                        <b><input type="text" style="text-align:right" readonly="" name="today_balance" id="today_balance" class="form-control" value=""></b><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="cash_adjust_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-6">
                    <h4 class="modal-title" id="myModalLabel">Cash Adjust</h4>
                </div>
                <div class="col-md-6">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </div>
            <div class="modal-body edit-content">
                <div class="col-md-12">
                    <div class="cash_adjust_div">
                        <div class="col-md-4">
                            <label for="after_adjust_amount">Amount<span class="required-sign">&nbsp;*</span></label>
                            <input type="text" name="after_adjust_amount" id="after_adjust_amount" class="form-control num_only" required="">
                            <input type="hidden" name="adjust_amount" id="adjust_amount" value="0">
                        </div>
                        <div class="col-md-4">
                            <label>Adjust Amount : </label><br />
                            <span class="adjust_amount">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="cash_adjust_button">Adjust</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
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
    var module_submit_flag = 0;
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('.select2').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        initAjaxSelect2($("#department_filter"), "<?= base_url('app/department_select2_source') ?>");
        <?php if (isset($payment_receipt_data->department_id)) { ?>
            get_department_cash(<?php echo $payment_receipt_data->department_id; ?>);
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $payment_receipt_data->department_id) ?>");
            initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_without_cash_customer_select2_source/' . $payment_receipt_data->department_id.'/1') ?>");
        <?php } else { ?>
            get_department_cash(<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']; ?>);
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
            setSelect2Value($("#on_behalf_of"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
            initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_without_cash_customer_select2_source/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id'].'/1') ?>");
        <?php } ?>
        get_all_department_cash();
        <?php if (isset($payment_receipt_data->account_id)) { ?>
        get_account_fine(<?php echo $payment_receipt_data->account_id; ?>);
        setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_select2_val_by_id/' . $payment_receipt_data->account_id) ?>");
        <?php } ?>
        initAjaxSelect2($("#on_behalf_of"), "<?= base_url('app/process_master_select2_source') ?>");
        <?php if (isset($payment_receipt_data->on_behalf_of)) { ?>
            setSelect2Value($("#on_behalf_of"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $payment_receipt_data->on_behalf_of) ?>");
        <?php } ?>
            initAjaxSelect2($("#account_filter"), "<?= base_url('app/account_name_with_number_without_department_without_case_customer_select2_source/')?>");
        
        if ($('#datepicker1').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker1').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: "today",
                maxDate: 0,
            });
        }
        
        $(document).on('click', '.cash_adjust_btn', function () {
            var amount = $(this).attr('data-amount');
            $('#after_adjust_amount').val(amount);
            $('#after_adjust_amount').attr('data-old_amount', amount);
            $('#cash_adjust_model').modal('show');
        });
        
        $(document).on('keyup change', '#after_adjust_amount', function () {
            var after_adjust_amount = $('#after_adjust_amount').val();
            var old_amount = $('#after_adjust_amount').attr('data-old_amount');
            var adjust_amount = parseFloat(after_adjust_amount) - parseFloat(old_amount);
            adjust_amount = adjust_amount.toFixed(2);
            $('#adjust_amount').val(adjust_amount);
            $('.adjust_amount').html(adjust_amount);
        });
        
        $('#cash_adjust_model').on('shown.bs.modal', function () {
            $("#after_adjust_amount").focus();
        });
        
        $('#cash_adjust_model').on('hidden.bs.modal', function () {
            $('#adjust_amount').val(0);
            $('.adjust_amount').html('0');
        });
        
        $(document).on('click', '#cash_adjust_button', function () {
            var adjust_amount = $('#adjust_amount').val(); 
            if ($.trim($("#after_adjust_amount").val()) == '' && $.trim($("#after_adjust_amount").val()) == '0') {
                show_notify('Please Enter Amount!', false);
                $("#after_adjust_amount").focus();
                return false;
            }
            var department_id = $('#department_id').val();
            var old_amount = $('#after_adjust_amount').attr('data-old_amount');
            $.ajax({
                url: "<?= base_url('reports/get_department_cash') ?>/" + department_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    var current_department_bal = round(json.amount, 2).toFixed(2);
                    if(current_department_bal != old_amount){
                        show_notify('Previous Balance changed After you have opened this screen. So, please Click Here to Refresh <a href="<?= base_url('reports/cashbook') ?>" class="btn btn-primary btn-xs" style="margin: 5px;" ><i class="fa fa-refresh"></i></a>', false);
                        return false;
                    } else {
                        if(adjust_amount != ''  && adjust_amount != 0){
                            if (confirm('Are you sure, You want to Cash Adjust?')) {
                                var transaction_date = $("#datepicker1").val();
                                var department_id = $("#department_id").val();
                                var account_id = <?php echo ADJUST_EXPENSE_ACCOUNT_ID; ?>;
                                if(adjust_amount > 0 ){
                                    var payment_receipt = 2;
                                } else {
                                    var payment_receipt = 1;
                                }
                                adjust_amount = Math.abs(adjust_amount);
                                $.ajax({
                                    url: "<?= base_url('reports/save_cashbook') ?>",
                                    type: "POST",
                                    data: {transaction_date: transaction_date, payment_receipt: payment_receipt, department_id: department_id, account_id: account_id, on_behalf_of: department_id, amount: adjust_amount, narration: 'Cash Adjust'},
                                    success: function (response) {
                                        var json = $.parseJSON(response);
                                        if (json['error'] == 'Exist') {
                                            show_notify(json['error_exist'], false);
                                        } else if (json['success'] == 'Added') {
                                            show_notify('Cash Adjust Successfully!', true);
                                            $("#datepicker1").val('<?php echo date('d-m-Y'); ?>');
                                            $("#payment_receipt").val('2').trigger("change");
                                            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/') ?>" + department_id);
                                            $("#account_id").val(null).trigger("change");
                                            $("#on_behalf_of").val(null).trigger("change");
                                            $('#amount').val(0);
                                            $('textarea').val('');
                                            $('#department_balance').html('');
                                            get_all_department_cash();
                                            $('#account_balance').html('');
                                            var from_date = $('#datepicker2').val();
                                            var to_date = $('#datepicker3').val();
                                            get_opening_closing_balance(from_date,to_date);
                                            payment_table.draw();
                                            receipt_table.draw();
                                        }
                                        $('#adjust_amount').val(0);
                                        $('.adjust_amount').html('0');
                                        $('#cash_adjust_model').modal('hide');
                                        return false;
                                    },
                                });
                            }
                        } else {
                            show_notify('Please Enter Amount!', false);
                            $("#after_adjust_amount").focus();
                            return false;
                        }
                    }
                }
            });
        });
        
        var from_date = $('#datepicker2').val();
        var to_date = $('#datepicker3').val();
        get_opening_closing_balance(from_date,to_date);

        $(document).on('change','#department_id', function(){
            var department_id = $('#department_id').val();
            if (department_id != '' && department_id != null) {
                initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_without_cash_customer_select2_source') ?>/" + department_id+"/1");
                setSelect2Value($("#on_behalf_of"), "<?= base_url('app/set_process_master_select2_val_by_id') ?>/" + department_id);
                get_department_cash(department_id);
            } else {
                $('#on_behalf_of').val(null).trigger('change');
                $('#department_balance').html('');
            }
        });
        
        $(document).on('change', '#account_id', function(){
            var account_id = $('#account_id').val();
            if (account_id != '' && account_id != null) {
                $.ajax({
                    url: "<?= base_url('reports/check_account_group') ?>",
                    type: 'POST',
                    data: {account_id : account_id},
                    success: function (response) {
                        if(response == 1){
                            $('#on_behalf_of').val(null).trigger('change');
                        } else {
                            var department_id = $('#department_id').val();
                            setSelect2Value($("#on_behalf_of"), "<?= base_url('app/set_process_master_select2_val_by_id') ?>/" + department_id);
                        }
                    }
                });
                get_account_fine(account_id);
            } else {
                var department_id = $('#department_id').val();
                setSelect2Value($("#on_behalf_of"), "<?= base_url('app/set_process_master_select2_val_by_id') ?>/" + department_id);
                $('#account_balance').html('');
            }
        });
        
        <?php if(!isset($payment_receipt_data->audit_status) || (isset($payment_receipt_data->audit_status) && $payment_receipt_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
            $(document).bind("keydown", function(e){
                if(e.ctrlKey && e.which == 83){
                    e.preventDefault();
                    if(module_submit_flag == 0 ){
                        $("#cashbook_form").submit();
                        return false;
                    }
                }
            });
        <?php } ?>

        $(document).on('submit', '#cashbook_form', function () {
            if ($.trim($("#datepicker1").val()) == '') {
                show_notify('Please Select Date!', false);
                $("#datepicker1").focus();
                return false;
            }
            if ($.trim($("#payment_receipt").val()) == '') {
                show_notify('Please Select Type!', false);
                $("#payment_receipt").select2('open');
                return false;
            }
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department!', false);
                $("#department_id").select2('open');
                return false;
            }
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Account!', false);
                $("#account_id").select2('open');
                return false;
            }
            if ($.trim($("#on_behalf_of").val()) == '') {
                show_notify('Please Select On Behalf Of!', false);
                $("#on_behalf_of").select2('open');
                return false;
            }
            if ($.trim($("#amount").val()) == '0') {
                show_notify('Please Enter Amount!', false);
                $("#amount").focus();
                return false;
            }
            var credit_limit = $('#credit_limit').text();
            var amount = $("#amount").val();

            var payment_receipt = $('#payment_receipt').val();
//            var is_grater = 0
            var postData = new FormData(this);
            if((parseFloat(amount) > parseFloat(credit_limit)) && payment_receipt == 1) {
//                swal({
//                    title: "Amount Exceed Credit Limit. Are you sure you want to save?",
//                    type: "warning",
//                    buttons: ["Cancel", "Ok"],
//                    className: "danger_alert"
//                }).then((willSave) => {
//                    if (willSave) {
//                        var postData = new FormData(this);
//                        save_form(postData);
//                    }
//                });
                if (confirm('Amount Exceed Credit Limit. Are you sure you want to save?')) {
                    save_form(postData);
                }
            } else {
                save_form(postData);
            }
            module_submit_flag = 1;
            return false;
        });

        payment_table = $('#payment_table').DataTable({
            "serverSide": true,
            "scrollY": true,
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports/payment_receipt_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.payment_receipt = '1';
                    d.from_amount = $('#from_amount').val();
                    d.to_amount = $('#to_amount').val();
                    d.from_date = $('#datepicker2').val();
                    d.to_date = $('#datepicker3').val();
                    d.department_filter = $('#department_filter').val();
                    d.account_filter = $('#account_filter').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                    d.audit_status_filter = $('#audit_status_filter').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [3,4],
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

                // Total over all pages
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 3 ).footer() ).html( total );
            },
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                if ( aData[7] == '0' ){
                    $('td', nRow).css('background-color', '#F2DEDE');
                }
                if ( aData[7] == '1' ){
                    $('td', nRow).css('background-color', '#DFF0D8');
                }
            },
        });

        receipt_table = $('#receipt_table').DataTable({
            "serverSide": true,
            "scrollY": true,
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports/payment_receipt_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.payment_receipt = '2';
                    d.from_amount = $('#from_amount').val();
                    d.to_amount = $('#to_amount').val();
                    d.from_date = $('#datepicker2').val();
                    d.to_date = $('#datepicker3').val();
                    d.department_filter = $('#department_filter').val();
                    d.account_filter = $('#account_filter').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                    d.audit_status_filter = $('#audit_status_filter').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [3,4],
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

                // Total over all pages
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 3 ).footer() ).html( total );
            },
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                if ( aData[7] == '0' ){
                    $('td', nRow).css('background-color', '#F2DEDE');
                }
                if ( aData[7] == '1' ){
                    $('td', nRow).css('background-color', '#DFF0D8');
                }
            },
        });
        receipt_table.columns( [7] ).visible( false );
        payment_table.columns( [7] ).visible( false );
        if(isEqual(from_date, to_date)){
            receipt_table.columns( [6] ).visible( false );
            payment_table.columns( [6] ).visible( false );
        }
        else {
            receipt_table.columns( [6] ).visible( true );
            payment_table.columns( [6] ).visible( true );
        }

        $('#select2-department_filter-container .select2-selection__placeholder').html(' All ');
        $(document).on('change', '#department_filter', function(){
            var department_filter = $('#department_filter').val();
            if(department_filter == '' || department_filter === null){
                $('#select2-department_filter-container .select2-selection__placeholder').html(' All ');
            }
        });

        $(document).on('click', '#search_button', function () {
            if ($.trim($("#datepicker2").val()) == '') {
                show_notify('Please Select From Date!', false);
                $("#datepicker2").focus();
                return false;
            }
            if ($.trim($("#datepicker3").val()) == '') {
                show_notify('Please Select To Date!', false);
                $("#datepicker3").focus();
                return false;
            }
            var from_amount = $('#from_amount').val();
            var to_amount = $('#to_amount').val();
            if(from_amount != '' || to_amount != ''){
                $('#opening_balance').val('');
                $('#closing_balance').val('');
                $('#today_balance').val('');
            } else {
                var from_date = $('#datepicker2').val();
                var to_date = $('#datepicker3').val();
                get_opening_closing_balance(from_date,to_date);
            }
            var everything_from_start = '0';
            if ($('#everything_from_start').is(":checked")){
                everything_from_start = '1';
                get_opening_closing_balance('everything_from_start',to_date);
            }
            $('#ajax-loader').show();
            receipt_table.draw();
            payment_table.draw();
            if(everything_from_start == '0' && isEqual(from_date, to_date)){
                receipt_table.columns( [6] ).visible( false );
                payment_table.columns( [6] ).visible( false );
            }
            else {
                receipt_table.columns( [6] ).visible( true );
                payment_table.columns( [6] ).visible( true );
            }
        });
        
        $(document).on("click", ".delete_button", function () {
            var value = confirm('Are you sure delete this records?');
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    //data: 'id_name=category_id&table_name=category',
                    success: function () {
                        receipt_table.draw();
                        payment_table.draw();
                        get_all_department_cash();
                        setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
                        show_notify('Cashbook Deleted Successfully!', true);
                        var from_date = $('#datepicker2').val();
                        var to_date = $('#datepicker3').val();
                        get_opening_closing_balance(from_date,to_date);
                    }
                });
            }
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
            <?php if($this->app_model->have_access_role(CASHBOOK_MODULE_ID, "allow to audit / suspect")){ ?>
                allow_to_audit = 1;
            <?php } ?>
            var allow_audit_to_pending = 0;
            <?php if($this->app_model->have_access_role(CASHBOOK_MODULE_ID, "allow audit / suspect to pending")){ ?>
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
                        url: "<?php echo site_url('reports/audit_status_cashbook') ?>",
                        type: "POST",
                        async: false,
                        data: {audit_status_pay_rec_id : audit_status_pay_rec_id, audit_status : audit_status},
                        success: function (response) {
                            var json = $.parseJSON(response);
                            $("#ajax-loader").hide();
                            if (json['success'] == 'Changed') {
                                receipt_table.draw();
                                payment_table.draw();
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

        $(document).on('click', '.received' , function(){
            var pr_id = $(this).attr('id');
            if($(this). prop("checked") == true){
                var is_checked = '1';
            } else {
                var is_checked = '0';
            }
            receive_payment(pr_id, is_checked);
        });
    });

    function isEqual(startDate, endDate) {
        return endDate.valueOf() == startDate.valueOf();
    }
    
    function get_opening_closing_balance(from_date,to_date){
        var department_filter = $('#department_filter').val();
        var account_filter = $('#account_filter').val();
        $.ajax({
            url: "<?php echo base_url('reports/get_opening_closing_balance'); ?>",
            type: "POST",
            data: {from_date: from_date, to_date: to_date, department_filter: department_filter, account_filter: account_filter},
            success: function(response){
                var json = $.parseJSON(response);
                $('#opening_balance').val(json.opening_balance);
                $('#closing_balance').val(json.closing_balance);
                $('#today_balance').val(json.today_balance);
            }
        });
    }

    function get_account_fine(account_id){
        if(account_id != '' && account_id != null){
            $.ajax({
                url: "<?= base_url('sell/get_account_old_balance') ?>/" + account_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    var gold_fine = round(json.gold_fine, 2).toFixed(3);
                    var silver_fine = round(json.silver_fine, 1).toFixed(3);
                    if(gold_fine > 0){
                        gold_fine = '<span style="color:red;">'+ gold_fine + '</span>';
                    } else {
                        gold_fine = '<span style="color:blue;">'+ gold_fine + '</span>';
                    }
                    if(silver_fine > 0){
                        silver_fine = '<span style="color:red;">'+ silver_fine + '</span>';
                    } else {
                        silver_fine = '<span style="color:blue;">'+ silver_fine + '</span>';
                    }
                    if(json.amount > 0){
                        var amount = '<span style="color:red;">'+ json.amount + '</span>';
                    } else {
                        var amount = '<span style="color:blue;">'+ json.amount + '</span>';
                    }
                    if(json.credit_limit == null){
                        json.credit_limit = 0;
                    }
                    $('#credit_limit').val(json.credit_limit);
                    $('#account_balance').html('<label>Old Balance</label><br /><span><b>Gold Fine : ' + gold_fine + '</b><br /></span><span><b>Silver Fine : ' + silver_fine + '</b></span><br /><span><b>Amount : ' + amount + '</b></span><br /><span><b>Credit Limit : <span id="credit_limit">' +json.credit_limit+'</span></b></span>');
                }
            });
        } else {
            $('#account_balance').html('');
        }
    }

    function get_department_cash(department_id){
        if(department_id != '' && department_id != null){
            $.ajax({
                url: "<?= base_url('reports/get_department_cash') ?>/" + department_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    var amount = round(json.amount, 2).toFixed(2);
                    var amount_data = '';
                    if(parseInt(amount) > 0){
                        amount_data = '<span style="color:red;">'+ amount + '</span>';
                    } else {
                        amount_data = '<span style="color:blue;">'+ amount + '</span>';
                    }
                    <?php if($this->app_model->have_access_role(CASH_ADJUST_ID, "view")){ ?>
                        $('#department_balance').html('<label>Selected Department Cash</label><br /><span><b>Amount : ' + amount_data + '</b><br /></span> <a class="btn btn-primary btn-xs cash_adjust_btn" data-amount="' + amount + '">Cash Adjust</a>');
                    <?php } else { ?>
                        $('#department_balance').html('<label>Selected Department Cash</label><br /><span><b>Amount : ' + amount_data + '</b><br /></span>');
                    <?php } ?>
                }
            });
        } else {
            $('#department_balance').html('');
        }
    }

    function get_all_department_cash(){
        $.ajax({
            url: "<?= base_url('reports/get_all_department_cash') ?>/",
            type: 'GET',
            data: '',
            success: function (response) {
                var json = $.parseJSON(response);
                var amount = round(json.amount, 2).toFixed(2);
                var amount_data = '';
                if(parseInt(amount) > 0){
                    amount_data = '<span style="color:red;">'+ amount + '</span>';
                } else {
                    amount_data = '<span style="color:blue;">'+ amount + '</span>';
                }
                $('#all_department_balance').html('<label>All Department Cash </label><span><b> : ' + amount_data + '</b><br /></span>');
            }
        });
    }
    
    function receive_payment(pr_id, is_checked){
        if(pr_id != '' && pr_id != null){
            $.ajax({
                url: "<?= base_url('reports/receive_payment') ?>",
                type: 'POST',
                data: {pr_id : pr_id, is_checked : is_checked},
                success: function (response) {
                    receipt_table.draw();
                    payment_table.draw();
                }
            });
        }
    }
    
    function save_form(postData){
        $('.module_save_btn').attr('disabled', 'disabled');
        $.ajax({
            url: "<?= base_url('reports/save_cashbook') ?>",
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            data: postData,
            async: false,
            success: function (response) {
                var json = $.parseJSON(response);
                if (json['error'] == 'Exist') {
                    show_notify(json['error_exist'], false);
                } else if (json['success'] == 'Added') {
                    show_notify('Cashbook Added Successfully!', true);
                    $("#datepicker1").datepicker('setDate', '<?php echo date('d-m-Y'); ?>');
                    $("#datepicker1").val('<?php echo date('d-m-Y'); ?>');
                    $("#payment_receipt").val('2').trigger("change");
                    setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
                    $("#account_id").val(null).trigger("change");
                    $('#amount').val(0);
                    $('textarea').val('');
                    $('#department_balance').html('');
                    get_all_department_cash();
                    $('#account_balance').html('');
                    var from_date = $('#datepicker2').val();
                    var to_date = $('#datepicker3').val();
                    get_opening_closing_balance(from_date,to_date);
                    payment_table.draw();
                    receipt_table.draw();
                } else if (json['success'] == 'Updated') {
                    window.location.href = "<?php echo base_url('reports/cashbook') ?>";
                }
                $('.module_save_btn').removeAttr('disabled', 'disabled');
                return false;
            },
        });
    }
</script>