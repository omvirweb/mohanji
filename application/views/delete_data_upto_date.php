<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Check All is Well?</h1>
        <h4 class="text-danger">
            Note : One by one Module check kar ke data delete krna ( Taki load jyada na le ) And First 5-5 days ka data delete krna ( Taki load jyada na le ) <br>
            Delete hote samay work mat krna
        </h4>
    </section>

    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <form class="form-horizontal" method="post" id="delete_data_form" novalidate enctype="multipart/form-data">
                    <div class="col-md-2">
                        <label for="datepicker1">Delete Upto <span class="required-sign">&nbsp;*</span></label>
                        <input type="text" name="delete_upto" id="datepicker1" class="form-control" value="<?php echo date("d-m-Y", strtotime("-1 months")); ?>" autocomplete="off">
                    </div>
                    <div class="col-md-10">
                        <h4 class="ajax_waiting_div text-danger blink_div" style="display: none;">Please Wait and "Do not Refresh or Close Tab" till display Success message on screen...</h4>
                    </div>
                    <div class="col-md-12">
                        <br>
                        <label>Module Names <span class="required-sign">&nbsp;*</span></label>
                        <br>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_order" value="module_order"> <label for="module_order">Order (Excluding Pending and Hold)</label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_issue_receive" value="module_issue_receive" > <label for="module_issue_receive">Manufacture Issue/Receive</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_issue_receive_silver" value="module_issue_receive_silver" > <label for="module_issue_receive_silver">Manufacture I/R Silver</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_hand_made" value="module_hand_made" > <label for="module_hand_made">Manufacture Hand Made</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_machine_chain" value="module_machine_chain" > <label for="module_machine_chain">Manufacture Machine Chain</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_casting" value="module_casting" disabled=""> <label for="module_casting">Manufacture Casting</label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_sale_purchase" value="module_sale_purchase" > <label for="module_sale_purchase">Sell/Purchase</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_other_entry" value="module_other_entry" disabled=""> <label for="module_other_entry">Other Entry</label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_journal" value="module_journal"> <label for="module_journal">Journal</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_cashbook" value="module_cashbook" > <label for="module_cashbook">Cashbook</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_stock_transfer" value="module_stock_transfer"> <label for="module_stock_transfer">Stock Transfer</label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <label for="module_hr_attendance"><input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_hr_attendance" value="module_hr_attendance"> HR Attendance (Attendance delete hone ke baad salary proper count nahi hoga.) </label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names[]" class="module_names_checkbox" id="module_employee_salary" value="module_employee_salary"> <label for="module_employee_salary">HR Employee Salary</label>
                        </div>
                        <br><br>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-1">
                        <button type="submit" name="delete_data_btn" id="delete_data_btn" class="btn btn-info" > Delete Data</button>
                    </div>
                    <div class="col-md-4"><br>
                        <input type="checkbox" name="delete_all_log_upto_selected_date" class="delete_all_log_upto_selected_date" id="delete_all_log_upto_selected_date" value="1" > <label for="delete_all_log_upto_selected_date">Delete All Log Upto Selected Date (Without any Conditions)</label>
                    </div>
                    <?php /*<div class="col-md-4"><br>
                        <input type="checkbox" name="make_account_opening_0" class="make_account_opening_0" id="make_account_opening_0" value="1" checked=""> <label for="make_account_opening_0">Make Opening 0 For Account Groups We Do Not Display In Balance Sheet</label>
                    </div>*/ ?>
                </form>
                <div class="col-md-12"><hr style="border-color: #999999;"></div>
                <form class="form-horizontal" method="post" id="delete_selected_data_form" novalidate enctype="multipart/form-data">
                    <div class="col-md-2">
                        <label for="datepicker2">Delete Upto <span class="required-sign">&nbsp;*</span></label>
                        <input type="text" name="delete_upto_selected" id="datepicker2" class="form-control" value="<?php echo date("d-m-Y", strtotime("-1 months")); ?>" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                        <label>All Accounts? (Excluding Banks)</label> <br />
                        <label for="allow_all_accounts_yes">
                            <input type="radio" name="allow_all_accounts" class="allow_all_accounts" id="allow_all_accounts_yes" value="<?php echo ALLOW_ALL_ACCOUNTS;?>" checked="" > Yes, All
                        </label> &nbsp; &nbsp;
                        <label for="allow_all_accounts_no">
                            <input type="radio" name="allow_all_accounts" class="allow_all_accounts" id="allow_all_accounts_no" value="<?php echo ALLOW_ONLY_SELECTED_ACCOUNTS;?>" > No, Only Selected
                        </label>
                    </div>
                    <div class="col-md-3 account_selection_div">
                        <label for="account_id">Select Accounts ( Customer, Creditors, Debtors)</label>
                        <select name="account_id[]" id="account_id" multiple="multiple">
                            <?php if(!empty($account_res)) { foreach ($account_res as $key => $account_row) { ?>
                                <option value="<?php echo $account_row['account_id']?>" account_group_id="<?php echo $account_row['account_group_id']?>" ><?php echo $account_row['text']?></option>
                            <?php } } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">&nbsp;</label><br>
                        <input type="checkbox" name="delete_only_for_balance_0" class="delete_only_for_balance_0" id="delete_only_for_balance_0" value="1"> <label for="delete_only_for_balance_0">Delete Transactions Only for Current Balance is 0</label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <br>
                        <label>Module Names <span class="required-sign">&nbsp;*</span></label>
                        <br>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names_selected[]" class="module_names_selected_checkbox" id="module_sale_purchase_selected" value="module_sale_purchase_selected" checked="" > <label for="module_sale_purchase_selected">Sell/Purchase</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names_selected[]" class="module_names_selected_checkbox" id="module_journal_selected" value="module_journal_selected" checked="" > <label for="module_journal_selected">Journal</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="module_names_selected[]" class="module_names_selected_checkbox" id="module_cashbook_selected" value="module_cashbook_selected" checked="" > <label for="module_cashbook_selected">Cashbook</label>
                        </div>
                        <br><br>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <button type="submit" name="delete_selected_data_btn" id="delete_selected_data_btn" class="btn btn-info" > Delete Data</button>
                    </div>
                </form>
                
                <div class="clearfix"></div>
                <div class="col-md-12"><br><hr style="border-color: #999999;">
                    <h4>Specifications</h4>
                    <ul>
                        <li>Sell/Purchase : Delete only Delivered Sell/Purchase Data</li>
                        <li>Hisab done ho utnaa hi delet karo (hisab done pending ho vo delete Mat karo)</li>
                        <li>Stock transfer me account selection or 0 balance vaali condition nahi lagu hogi</li>
                    </ul>
                    <img src="<?php echo base_url('/assets/dist/img/Delete_Data_Upto_Selected_Date_Specification.png'); ?>" alt="Delete_Data_Upto_Selected_Date_Specification" ><br>
                </div>
                <div class="clearfix"></div>
                
            </div>
        </div>
    </div>
</div>
<div id="delete_data_upto_date_db_modal" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="delete_data_upto_date_db_modal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete Upto Selected Date</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="delete_data_upto_date_db_password">Password</label>
                            <input type="text" id="delete_data_upto_date_db_password" class="form-control">
                            <input type="hidden" id="delete_data_upto_date_type" value="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="delete_data_upto_date_db_cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="delete_data_upto_date_db_submit">Delete Data</button>
            </div>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<script>
    var postData;
    $(document).ready(function () {
//        $('#datepicker1').datepicker({ dateFormat: "dd-mm-yy"});
        $('input[type="radio"]').iCheck({
            radioClass: 'iradio_flat-blue'
        });
        $(document).on('ifChecked',"#allow_all_accounts_no",function(){
            $(".account_selection_div").show();
        });

        $(document).on('ifChecked',"#allow_all_accounts_yes",function(){
            $(".account_selection_div").hide();
            $("#account_id").multipleSelect('uncheckAll');
        });
        
        $("#account_id").multipleSelect({
            filter: true,
            width:'100%'
        });
        $('input[data-name^=selectItemaccount_id]:disabled').closest('li').hide();

        if($(".allow_all_accounts:checked").val() == <?php echo ALLOW_ALL_ACCOUNTS?>) {
            $(".account_selection_div").hide();
        }
        
        $(document).on('submit', '#delete_data_form', function () {
            var delete_upto = $('#datepicker1').val();
            if (delete_upto == '' || delete_upto == null) {
                show_notify('Please Select Date!', false);
                return false;
            }
            if ($('.module_names_checkbox:checked').length > 0){ } else {
                show_notify('Please Select at least one Module!', false);
                return false;
            }
            postData = new FormData(this);

            $("#delete_data_upto_date_db_modal").modal('show');
            $("#delete_data_upto_date_db_password").attr('type','password');
            $("#delete_data_upto_date_type").val('1');
            return false;
        });
        
        $(document).on('submit', '#delete_selected_data_form', function () {
            var delete_upto_selected = $('#datepicker2').val();
            if (delete_upto_selected == '' || delete_upto_selected == null) {
                show_notify('Please Select Date!', false);
                return false;
            }
            if ($('.module_names_selected_checkbox:checked').length > 0){ } else {
                show_notify('Please Select at least one Module!', false);
                return false;
            }
            var allow_all_accounts = $("input[name='allow_all_accounts']:checked").val();
            var account_id = $("#account_id").val();
            if (allow_all_accounts == '2' && (account_id == '' || account_id == null)) {
                show_notify('Please Select at least one Account!', false);
                return false;
            }
            postData = new FormData(this);

            $("#delete_data_upto_date_db_modal").modal('show');
            $("#delete_data_upto_date_db_password").attr('type','password');
            $("#delete_data_upto_date_type").val('2');
            return false;
        });
        
        $(document).on('click', '#delete_data_upto_date_db_cancel', function (e) {
            $("#delete_data_upto_date_db_password").val('');
        });
        
        $('#delete_data_upto_date_db_modal').on('hidden.bs.modal', function () {
            $("#delete_data_upto_date_db_password").attr('type','text');
            $("#delete_data_upto_date_type").val('0');
        });

        $(document).on('click', '#delete_data_upto_date_db_submit', function (e) {
            var delete_data_upto_date_type = $("#delete_data_upto_date_type").val();
            var delete_data_upto_date_db_password = $("#delete_data_upto_date_db_password").val();
            if(delete_data_upto_date_db_password == '' || delete_data_upto_date_db_password == null) {
                show_notify("Please Enter Password");
                $("#delete_data_upto_date_db_password").focus();
                return false;
            }
            $.ajax({
                type: "POST",
                data: "user_password="+delete_data_upto_date_db_password,
                url: "<?= base_url('backup/check_password'); ?>",
                dataType: 'json',
                success: function (data) {
                    if(data.status == "success") {
                        $("#delete_data_upto_date_db_modal").modal('hide');
                        $("#delete_data_upto_date_db_password").val('');
                        $("#delete_data_upto_date_type").val('0');
                        
                        if (confirm('Are you sure, You want to Delete Data?')) {
                            $('#ajax-loader').show();
                            $('.ajax_waiting_div').show();
                            $('#delete_data_btn').attr('disabled', 'disabled');
                            $('#delete_selected_data_btn').attr('disabled', 'disabled');
                            
                            setTimeout( function(){
                                
                                if(delete_data_upto_date_type == '1') {
                                
                                    $.ajax({
                                        url: "<?php echo base_url('delete_data_upto_date/delete_selected_data'); ?>",
                                        type: "POST",
                                        processData: false,
                                        contentType: false,
                                        cache: false,
                                        data: postData,
                                        datatype: 'json',
                                        async: false,
                                        success: function (response) {
                                            $('#ajax-loader').hide();
                                            $('.ajax_waiting_div').hide();
                                            var json = $.parseJSON(response);
                                            if (json['success'] == 'Deleted') {
                                                show_notify('Deleted Successfully!', true);
                                                $("input[class='module_names_checkbox']:checkbox").prop('checked',false);
                                            } else if (json['empty'] == 'Empty') {
                                                show_notify('Data Not Found!', false);
                                            } else {
                                                show_notify('some error occurred!', false);
                                            }
                                            $('#delete_data_btn').removeAttr('disabled', 'disabled');
                                            $('#delete_selected_data_btn').removeAttr('disabled', 'disabled');
                                            return false;
                                        },
                                    });
                            
                                } else if(delete_data_upto_date_type == '2') {
                                
                                    $.ajax({
                                        url: "<?php echo base_url('delete_data_upto_date/delete_selected_accounts_data'); ?>",
                                        type: "POST",
                                        processData: false,
                                        contentType: false,
                                        cache: false,
                                        data: postData,
                                        datatype: 'json',
                                        async: false,
                                        success: function (response) {
                                            $('#ajax-loader').hide();
                                            $('.ajax_waiting_div').hide();
                                            var json = $.parseJSON(response);
                                            if (json['success'] == 'Deleted') {
                                                show_notify('Deleted Successfully!', true);
                                                $("input[class='module_names_selected_checkbox']:checkbox").prop('checked',true);
                                                $("input[class='delete_only_for_balance_0']:checkbox").prop('checked',false);
                                                $('#allow_all_accounts_no').prop('checked',false).iCheck('update');
                                                $(".account_selection_div").hide();
                                                $("#account_id").multipleSelect('uncheckAll');
                                                $('#allow_all_accounts_yes').prop('checked',true).iCheck('update');
                                            } else if (json['empty'] == 'Empty') {
                                                show_notify('Data Not Found!', false);
                                            } else {
                                                show_notify('some error occurred!', false);
                                            }
                                            $('#delete_data_btn').removeAttr('disabled', 'disabled');
                                            $('#delete_selected_data_btn').removeAttr('disabled', 'disabled');
                                            return false;
                                        },
                                    });
                                
                                }
                            
                            }  , 1000 ); // Do something after 1 second
                        }
                        
                    } else {
                        $("#ajax-loader").hide();
                        show_notify("Invalid Password!");
                        $("#delete_data_upto_date_db_password").focus();
                    }
                }
            });
        });
    });
</script>