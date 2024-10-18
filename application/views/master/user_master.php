<?php 
    $this->load->view('success_false_notify');
    $this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type'];
?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add User
            <?php $isEdit = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "add"); ?>
            <?php if (isset($category_data->category_id) && !empty($category_data->category_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isView){ ?>
                <a href="<?= base_url('master/user_master_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">User List</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <!-- Horizontal Form -->
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <?php if($isAdd || $isEdit) { ?>
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form class="form-horizontal" action="<?= base_url('master/save_user_master') ?>" method="post" id="user_master_form" novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($user_master_data->user_id) && !empty($user_master_data->user_id)) { ?>
                                                <input type="hidden" name="user_id" class="user_id" value="<?= $user_master_data->user_id ?>">
                                                <input type="hidden" name="old_user_type_id" value="<?= $user_master_data->user_type; ?>">
                                            <?php } ?>
                                            <div class="col-md-6">
                                                <label for="user_type">Type<span class="required-sign">&nbsp;*</span></label>
                                                <select name="user_type" id="user_type" class="form-control select2"></select>
                                            </div>
                                            <div class="clearfix"></div><br/>
                                            <div class="col-md-6">
                                                <label for="department_id">Department<span class="required-sign">&nbsp;*</span></label>
                                                <select name="department_id[]" id="department_id" class="form-control" multiple="multiple">
                                                    <?php 
    //                                                    $selected_plans = implode(',', $department->department_id);
                                                        foreach($user_department as $user) { 
                                                    ?>
                                                        <option value="<?= $user->account_id;?>" 
                                                        <?php if(!empty($department) && in_array($user->account_id, $department)){ echo ' Selected '; } ?> 
                                                        ><?= $user->account_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="department_id">Default Department<span class="required-sign">&nbsp;*</span></label>
                                                <select name="default_department_id" id="default_department_id" class="form-control select2">
                                                </select>
                                            </div>
                                            <div class="clearfix"></div><br/>
                                            <div class="col-md-6">
                                                <label for="user_name">Name <span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="user_name" id="user_name" class="form-control" value="<?= (isset($user_master_data->user_name)) ? $user_master_data->user_name : ''; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="login_username">Login Username <span class="required-sign login_username_required hide">&nbsp;*</span></label>
                                                <input type="text" name="login_username" id="login_username" class="form-control" value="<?= (isset($user_master_data->login_username)) ? $user_master_data->login_username : ''; ?>">
                                            </div>
                                            <div class="clearfix"></div><br/>
                                            <div id="user_fields">
                                                <div class="col-md-6">
                                                    <label for="password">Password<span class="required-sign">&nbsp;*</span></label>
                                                    <input type="password" name="user_password" id="user_password" class="form-control" value="" ><br />
                                                    <?php if (isset($user_master_data->user_id) && !empty($user_master_data->user_id)) { ?>
                                                        <span><small>Remain Password box blank, if you do Not want to change Password</small></span>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="confirm_pass">Confirm Password<span class="required-sign">&nbsp;*</span></label>
                                                    <input type="password" id="confirm_pass" class="form-control" value="<?= (isset($user_master_data->password)) ? $user_master_data->password : ''; ?>" ><br />
                                                </div>
                                                <div class="clearfix"></div><br/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="user_mobile">Mobile No.<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="user_mobile" maxlength="10" id="user_mobile" class="form-control num_only" value="<?= (isset($user_master_data->user_mobile)) ? $user_master_data->user_mobile : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="salary">Salary<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="salary" id="salary" class="form-control num_only" value="<?= (isset($user_master_data->salary)) ? $user_master_data->salary : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="opening_balance_in_rupees">Opening Balance In Rupees</label>
                                                <input type="text" name="opening_balance_in_rupees" id="opening_balance_in_rupees" class="form-control num_only" value="<?= (isset($user_master_data->opening_balance_in_rupees)) ? $user_master_data->opening_balance_in_rupees : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rupees_ob_credit_debit">Credit / Debit</label>
                                                <select name="rupees_ob_credit_debit" id="rupees_ob_credit_debit" class="form-control select2" style="margin-bottom: 10px;">
                                                    <option value="1" <?=(isset($user_master_data->rupees_ob_credit_debit) && $user_master_data->rupees_ob_credit_debit == '1') ? 'selected' : '' ?>>Credit</option>
                                                    <option value="2" <?=(isset($user_master_data->rupees_ob_credit_debit) && $user_master_data->rupees_ob_credit_debit == '2') ? 'selected' : '' ?>>Debit</option>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-6">
                                                <label for="otp_on_user">OTP go to User's Mobile</label><br />
                                                <span> Yes  &nbsp;</span> <input type="radio" name="otp_on_user" class="" id="otp_on_user1" value="1"<?= (isset($user_master_data->otp_on_user) && $user_master_data->otp_on_user == '1') ? 'checked' : ''; ?>>
                                                &nbsp;<span> No &nbsp; </span><input type="radio" name="otp_on_user" class="" id="otp_on_user2" value="0"<?= (isset($user_master_data->otp_on_user) && $user_master_data->otp_on_user == '0') ? 'checked' : ''; ?>><br />
                                            </div>
                                            <div class="col-md-4">
                                                <label for="designation">Designation <span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="designation" id="designation" class="form-control" value="<?= (isset($user_master_data->designation)) ? $user_master_data->designation : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-2">
                                                <br/>
                                                <label for="is_cad_designer">Is CAD Designer &nbsp;</label>
                                                <input type="checkbox" name="is_cad_designer" id="is_cad_designer" style="height:20px; width:20px;" <?= (isset($user_master_data->is_cad_designer)) && !empty($user_master_data->is_cad_designer) ? 'checked' : ''; ?>><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="aadhaar_no">Aadhaar No.<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="aadhaar_no" id="aadhaar_no" class="form-control num_only" value="<?= (isset($user_master_data->aadhaar_no)) ? $user_master_data->aadhaar_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="pan_no">Pan No.</label>
                                                <input type="text" name="pan_no" id="pan_no" class="form-control" value="<?= (isset($user_master_data->pan_no)) ? $user_master_data->pan_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="licence_no">Licence No.</label>
                                                <input type="text" name="licence_no" id="licence_no" class="form-control" value="<?= (isset($user_master_data->licence_no)) ? $user_master_data->licence_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="voter_id_no">Voter ID No.</label>
                                                <input type="text" name="voter_id_no" id="voter_id_no" class="form-control" value="<?= (isset($user_master_data->voter_id_no)) ? $user_master_data->voter_id_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="esi_no">ESI No.</label>
                                                <input type="text" name="esi_no" id="esi_no" class="form-control" value="<?= (isset($user_master_data->esi_no)) ? $user_master_data->esi_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="pf_no">PF No.</label>
                                                <input type="text" name="pf_no" id="pf_no" class="form-control" value="<?= (isset($user_master_data->pf_no)) ? $user_master_data->pf_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="date_of_birth">Date Of Birth</label>
                                                <input type="text" name="date_of_birth" id="date_of_birth" class="form-control datepicker" value="<?= (isset($user_master_data->date_of_birth)) ? date('d-m-Y', strtotime($user_master_data->date_of_birth)) : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="blood_group">Blood Group</label>
                                                <input type="text" name="blood_group" id="blood_group" class="form-control" value="<?= (isset($user_master_data->blood_group)) ? $user_master_data->blood_group : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="order_department_id">Add, View Orders Allowed For Department(s)</label>
                                                <select name="order_department_id[]" id="order_department_id" class="form-control" multiple="multiple">
                                                    <?php 
                                                        foreach($user_department as $user) { 
                                                    ?>
                                                        <option value="<?= $user->account_id;?>" 
                                                        <?php if(!empty($order_department) && in_array($user->account_id, $order_department)){ echo ' Selected '; } ?> 
                                                        ><?= $user->account_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="account_group_id">
                                                    Account Group
                                                    &nbsp; &nbsp;
                                                    <button type="button" id="btn_select_all_account_group" class="btn btn-xs btn-primary">Select All</button>
                                                    <button type="button" id="btn_unselect_all_account_group" class="btn btn-xs btn-danger">Un Select ALL</button>
                                                </label>
                                                <select name="account_group_id[]" id="account_group_id" class="form-control" multiple="multiple">
                                                    <?php 
                                                        foreach($account_group_res as $account_group_row) { 
                                                    ?>
                                                        <option value="<?= $account_group_row->account_group_id;?>" 
                                                        <?php if(!empty($user_account_group) && in_array($account_group_row->account_group_id, $user_account_group)){ echo ' Selected '; } ?> 
                                                        ><?= $account_group_row->account_group_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <br />
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-2">
                                                <br/>
                                                <label for="order_display_only_assigned_account">Order : Display Only Assigned Account &nbsp;</label>
                                                <input type="checkbox" name="order_display_only_assigned_account" id="order_display_only_assigned_account" style="height:20px; width:20px;" <?php echo (isset($user_master_data->order_display_only_assigned_account)) ? !empty($user_master_data->order_display_only_assigned_account) ? 'checked' : '' : 'checked'; ?>><br />
                                            </div>
                                            <div class="col-md-4">
                                                <br />
                                                <label>Allow All Accounts?</label> <br />
                                                <label for="allow_all_accounts_yes">
                                                    <input type="radio" name="allow_all_accounts" class="allow_all_accounts" id="allow_all_accounts_yes" value="<?=ALLOW_ALL_ACCOUNTS;?>" <?= (isset($user_master_data->allow_all_accounts) && $user_master_data->allow_all_accounts == ALLOW_ONLY_SELECTED_ACCOUNTS) ? '' : 'checked'; ?>> Yes, Allow All
                                                </label> &nbsp; &nbsp; &nbsp;
                                                <label for="allow_all_accounts_no">
                                                    <input type="radio" name="allow_all_accounts" class="allow_all_accounts" id="allow_all_accounts_no" value="<?=ALLOW_ONLY_SELECTED_ACCOUNTS;?>" <?= (isset($user_master_data->allow_all_accounts) && $user_master_data->allow_all_accounts == ALLOW_ONLY_SELECTED_ACCOUNTS) ? 'checked' : ''; ?>> No, Allow Only Selected
                                                </label>
                                            </div>
                                            <div class="col-md-6 account_selection_div">
                                                <br/>
                                                <label for="account_id">
                                                    Accounts
                                                </label>
                                                <select name="account_id[]" id="account_id" multiple="multiple">
                                                    <?php 
                                                        $user_account = array();
                                                        if(!empty($user_master_data->selected_accounts)) {
                                                            $user_account = explode(',',$user_master_data->selected_accounts);
                                                        }
                                                        if(!empty($account_res)) {
                                                            foreach ($account_res as $key => $account_row) {
                                                                if(!empty($user_account_group) && in_array($account_row['account_group_id'], $user_account_group)){
                                                                } else {
                                                                    continue;
                                                                }
                                                                ?>
                                                                <option 
                                                                    value="<?=$account_row['id']?>" 
                                                                    account_group_id="<?=$account_row['account_group_id']?>" 
                                                                    <?php if(!empty($user_account) && in_array($account_row['id'], $user_account)){ echo ' Selected '; } ?> 
                                                                    ><?=$account_row['text']?></option>
                                                                <?php

                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div><br/>
                                            <div class="box-header with-border">
                                                <h3 class="box-title pull-left"><b>Bank Account Details</b></h3>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bank_name">Bank Name</label>
                                                <input type="text" name="bank_name" id="bank_name" class="form-control" value="<?= (isset($user_master_data->bank_name)) ? $user_master_data->bank_name : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bank_branch">Branch</label>
                                                <input type="text" name="bank_branch" id="bank_branch" class="form-control" value="<?= (isset($user_master_data->bank_branch)) ? $user_master_data->bank_branch : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bank_acc_name">Account Name</label>
                                                <input type="text" name="bank_acc_name" id="bank_acc_name" class="form-control" value="<?= (isset($user_master_data->bank_acc_name)) ? $user_master_data->bank_acc_name : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bank_acc_no">Account No.</label>
                                                <input type="text" name="bank_acc_no" id="bank_acc_no" class="form-control" value="<?= (isset($user_master_data->bank_acc_no)) ? $user_master_data->bank_acc_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bank_ifsc">IFSC Code</label>
                                                <input type="text" name="bank_ifsc" id="bank_ifsc" class="form-control" value="<?= (isset($user_master_data->bank_ifsc)) ? $user_master_data->bank_ifsc : ''; ?>"><br />
                                            </div>
                                            <div class="clearfix"></div><br/>
                                            <div class="box-header with-border">
                                                <h3 class="box-title pull-left"><b>Family Member</b></h3>
                                            </div>
                                            <div id="" style="margin-top: 10px;">
                                                <div class="family_line_item_form family_item_fields_div">
                                                    <input type="hidden" name="family_line_items_index" id="family_line_items_index" />
                                                    <input type="hidden" name="family_line_items_data[fm_id]" id="fm_id" value="0"/>
                                                    <div class="col-md-4">
                                                        <label for="member_name">Name</label>
                                                        <input type="text" name="family_line_items_data[member_name]" id="member_name" class="form-control" value="" ><br />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="member_phone_no">Member Phone Name</label>
                                                        <input type="text" name="family_line_items_data[member_phone_no]" id="member_phone_no" class="form-control num_only" value="" ><br />
                                                    </div>
                                                    <input type="button" id="add_lineitem_family" class="btn btn-info btn-sm pull-right add_lineitem_family" value="Add Member" style="margin-top: 21px;"/>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <table style="" class="table custom-table border item-table">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">Action</th>
                                                            <th width="5%">Member Name</th>
                                                            <th width="5%">Member Phone No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="family_lineitem_list"></tbody>
                                                </table>
                                            </div>
                                            <div class="clearfix"></div><br/>
                                            
                                            <div id="" style="margin-top: 10px;">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title pull-left"><b>User Images</b></h3>
                                                </div>
                                                <div class="line_item_form item_fields_div">
                                                    <input type="hidden" name="deleted_user_ids[]" id="deleted_user_ids" value="" />
                                                    <input type="hidden" name="line_items_index" id="line_items_index" />
                                                    <?php if (isset($user_master_data) && !empty($user_master_data)) { ?>
                                                        <input type="hidden" name="line_items_data[user_id]" id="user_id" value="0"/>
                                                    <?php } ?>
                                                    <div class="col-md-4">
                                                        <label for="file_upload">Image</label>
                                                        <input type="file" name="line_items_data[file_upload]" id="file_upload" class="from-control" onchange="readURL(this);" accept="image/*" value="" ><br />
                                                        <input type="hidden" name="line_items_data[image]" id="image" class="from-control">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="default_image">Set Default</label><br>
                                                            <input type="checkbox" name="line_items_data[default_image]" id="default_image" class="from-control checkbox_ch" style="height: 20px; width: 20px;"><br />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <br /><span id="image_name"></span>
                                                    </div>
                                                    <input type="button" id="add_lineitem" class="btn btn-info btn-sm pull-right add_lineitem" value="Add Image" style="margin-top: 21px;"/>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <table style="" class="table custom-table border item-table">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">Action</th>
                                                            <th width="5%">Default Image</th>
                                                            <th width="5%">Image</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="lineitem_list"></tbody>
                                                </table>
                                            </div>
                                            <div class="clrearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn"><?=(isset($user_master_data->user_id) && !empty($user_master_data->user_id) ? 'Update' : 'Save'); ?> [ Ctrl + S ]</button>
                                            <?php if (isset($user_master_data->user_id) && !empty($user_master_data->user_id)) { ?>
                                            <div class="created_updated_info" style="margin-top: 10px;">
                                                Created by: <?php echo (isset($user_master_data->created_by_name)) ? $user_master_data->created_by_name :''; ?>
                                                @ <?php echo (isset($user_master_data->created_at)) ? date('d-m-Y h:i A',strtotime($user_master_data->created_at)) : ''; ?><br/>
                                                Updated by: <?php echo (isset($user_master_data->updated_by_name)) ? $user_master_data->updated_by_name :''; ?>
                                                @ <?php echo (isset($user_master_data->updated_at)) ?date('d-m-Y h:i A', strtotime($user_master_data->updated_at)) : '' ?>
                                            </div>
                                            <?php } ?>
                                        </form>
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
</div>
<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Document Image</h4>
                </div>
                <div class="modal-body edit-content">
                    <img id="doc_img_src" src="" class="img-responsive" height='500px' width='300px'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<script>
    var account_res = <?=json_encode($account_res)?>;
    var column = 6;
    var deleted_lineitems = [];
    var first_time_edit_mode = 1;
    var on_save_add_edit_item = 0;
    var edit_lineitem_inc = 0;
    var lineitem_objectdata = [];
    <?php if (isset($image_lineitems)) { ?>
        var li_lineitem_objectdata = [<?php echo $image_lineitems; ?>];
        first_time_edit_mode = 0;
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    display_lineitem_html(lineitem_objectdata);
    var family_deleted_lineitems = [];
    var family_lineitem_objectdata = [];
    <?php if (isset($member_lineitems)) { ?>
        var family_li_lineitem_objectdata = [<?php echo $member_lineitems; ?>];
        var family_lineitem_objectdata = [];
        if (family_li_lineitem_objectdata != '') {
            $.each(family_li_lineitem_objectdata, function (index, value) {
                family_lineitem_objectdata.push(value);
            });
        }
        console.log(family_lineitem_objectdata);
    <?php } ?>
    family_display_lineitem_html(family_lineitem_objectdata);
    
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    
    $(document).ready(function () {
        $('input[type="radio"]').iCheck({
            radioClass: 'iradio_flat-blue'
        });
        $('#department_id').select2();
        $('#order_department_id').select2();
        $('#account_group_id').select2();
        $('#default_department_id').select2();
        $('#rupees_ob_credit_debit').select2();

        $(document).on('click',"#btn_select_all_account_group",function(){
            var selectedItems = [];
            var allOptions = $("#account_group_id option");
            allOptions.each(function() {
                selectedItems.push( $(this).val() );
            });
            $("#account_group_id").val(selectedItems).trigger("change");

            var option_html = '';
            $(account_res).each(function(index,value){
                if(jQuery.inArray(value.account_group_id,selectedItems) !== -1) {
                    option_html += '<option value="'+value.id+'" account_group_id="'+value.account_group_id+'">'+value.text+'</option>';
                }
            });
            $("#account_id").append(option_html);
            $("#account_id").multipleSelect('refreshOptions',{});
            /*$("#account_id option").prop('disabled',false);
            $("#account_id").multipleSelect('refreshOptions',{});
            $('input[data-name^=selectItemaccount_id]:disabled').closest('li').hide();*/
        });

        $(document).on('click',"#btn_unselect_all_account_group",function(){
            $("#account_group_id").val(null).trigger("change");

            $("#account_id").html('');
            $("#account_id").multipleSelect('refreshOptions',{});

            /*$("#account_id option").prop('selected',false);
            $("#account_id option").prop('disabled',true);
            $("#account_id").multipleSelect('refreshOptions',{});
            $('input[data-name^=selectItemaccount_id]:disabled').closest('li').hide();*/
        });

        $(document).on('ifChecked',"#allow_all_accounts_no",function(){
            $(".account_selection_div").show();
        });

        $(document).on('ifChecked',"#allow_all_accounts_yes",function(){
            $(".account_selection_div").hide();
        });
        
        $("#account_id").multipleSelect({
            filter: true,
            width:'100%'
        });
        $('input[data-name^=selectItemaccount_id]:disabled').closest('li').hide();

        if($(".allow_all_accounts:checked").val() == <?=ALLOW_ALL_ACCOUNTS?>) {
            $(".account_selection_div").hide(); 
        }

        $('#worker_fields').hide();
        initAjaxSelect2($("#user_type"), "<?= base_url('app/user_type_select2_source') ?>");
//        <?php if (isset($user_master_data->user_type)) { ?>
//console.log();
            setSelect2Value($("#user_type"), "<?= base_url('app/set_user_type_select2_val_by_id/' . $user_master_data->user_type) ?>");
        <?php } ?>
//        initAjaxSelect2($("#default_department_id"), "<?= base_url('app/process_master_select2_source') ?>");
        <?php if (isset($user_master_data->default_department_id)) { ?>
            setSelect2Value($("#default_department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $user_master_data->default_department_id) ?>");
        <?php } ?>
            
        image_data_index = '1';
        $('#add_more').click(function () {
            image_data_index = parseInt(image_data_index) + 1;
            $(this).before($("<div/>", {
                id: 'filediv'
            }).fadeIn('slow').append($("<input/>", {
                name: 'image_'+image_data_index,
                type: 'file',
                id: 'file'
            }), $("<br/>")));
        });
        
        <?php if($this->user_type == USER_TYPE_USER){ ?>
            var column = 5;
        <?php } ?>
        
        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");
            setTimeout(function () {
                $("#doc_img_src").attr('src', src);
            }, 0);
            $('#edit-modal').modal('show');
        });

        <?php if($this->user_type == USER_TYPE_USER){ ?>
            table.columns( [6] ).visible( false, false ); // Password Column
        <?php } ?>
            
        $(document).on('change', '#user_type', function () {
            var user_type = $('#user_type').val();
            if(user_type == 3){
                $('#worker_fields').show();
                $('#user_fields').hide();
                $('.login_username_required').addClass('hide');
            } else if (user_type == 4){
                $('#worker_fields').show();
                $('#user_fields').hide();
                $('.login_username_required').addClass('hide');
            } else {
                $('#worker_fields').hide();
                $('#user_fields').show();
                $('.login_username_required').removeClass('hide');
            }
        });
        
        <?php if (isset($user_master_data->user_type)) { ?>
            var user_type = $('#user_type').val();
            if(user_type == 3){
                $('#worker_fields').show();
                $('#user_fields').hide();
            } else if (user_type == 4){
                $('#worker_fields').show();
                $('#user_fields').hide();
            } else {
                $('#worker_fields').hide();
                $('#user_fields').show();
            }
        <?php } ?>
            
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#user_master_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#user_master_form', function () {
            if ($.trim($("#user_type").val()) == '') {
                show_notify('Please Select User Type.', false);
                $("#user_type").select2('open');
                return false;
            }
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                return false;
            }
            if ($.trim($("#default_department_id").val()) == '') {
                show_notify('Please Select Default Department.', false);
                $("#default_department_id").select2('open');
                return false;
            }
            if ($.trim($("#user_name").val()) == '') {
                show_notify('Please Enter User Name.', false);
                $("#user_name").focus();
                return false;
            }

            //Admin or User
            if($("#user_type").val() == "1" || $("#user_type").val() == "2") {
                if($.trim($("#login_username").val()) == ""){
                    show_notify('Please Enter Login Username.',false);
                    $("#login_username").focus();
                    return false;
                }
            }

            if ($.trim($("#user_mobile").val()) == '') {
                show_notify('Please Enter User Mobile No.', false);
                $("#user_mobile").focus();
                return false;
            } else if($("#user_mobile").val().length!=10){
                show_notify('Please Enter 10 Digit Mobile No.', false);
                $("#user_mobile").focus();
                return false;

            }
            <?php if (isset($user_master_data->user_id) && !empty($user_master_data->user_id)) { ?>
                var password = $("#user_password").val();
                if ($.trim(password) == '') {
                    $("#user_password").val("<?=(isset($user_master_data->user_password)) ? $user_master_data->user_password : ''; ?>");
                } else {
                    var user_type = $('#user_type').val();
                    if(user_type != 3 && user_type != 4){
                        if($.trim($("#user_password").val()) != $.trim($("#confirm_pass").val())) {
                            show_notify('Please re-enter confirm password.',false);
                            $("#confirm_pass").val("");
                            $("#confirm_pass").focus();
                            return false;
                        }
                    }
                }
            <?php } else { ?>
            var user_type = $('#user_type').val();
            if(user_type != 3 && user_type != 4){
                if ($.trim($("#user_password").val()) == '') {
                    show_notify('Please Enter password.', false);
                    $("#user_password").focus();
                    return false;
                }
                if($.trim($("#confirm_pass").val()) == ""){
                    show_notify('Please enter confirm password.',false);
                    $("#confirm_pass").focus();
                    return false;
                }
                if($.trim($("#user_password").val()) != $.trim($("#confirm_pass").val())) {
                    show_notify('Please re-enter confirm password.',false);
                    $("#confirm_pass").val("");
                    $("#confirm_pass").focus();
                    return false;
                }
            }
            <?php } ?>
            if ($.trim($("#salary").val()) == '') {
                show_notify('Please Enter Salary.', false);
                $("#salary").focus();
                return false;
            }
            if ($.trim($("#designation").val()) == '') {
                show_notify('Please Enter Designation.', false);
                $("#designation").focus();
                return false;
            }
            if ($.trim($("#aadhaar_no").val()) == '') {
                show_notify('Please Enter Aadhaar No.', false);
                $("#aadhaar_no").focus();
                return false;
            } else {
                var adharcard = /^\d{12}$/;
                var aadhaar_no = $("#aadhaar_no").val();
                if (!aadhaar_no.match(adharcard)){
                    show_notify('Please Enter 12 Digit Aadhaar No.', false);
                    $("#aadhaar_no").focus();
                    return false;
                }
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            var family_lineitem_objectdata_stringify = JSON.stringify(family_lineitem_objectdata);
            postData.append('family_line_items_data', family_lineitem_objectdata_stringify);
            var deleted_lineitems_stringify = JSON.stringify(deleted_lineitems);
            postData.append('deleted_lineitems', deleted_lineitems_stringify);
            var family_deleted_lineitems_stringify = JSON.stringify(family_deleted_lineitems);
            postData.append('family_deleted_lineitems', family_deleted_lineitems_stringify);
            $.ajax({
                url: "<?= base_url('master/save_user_master') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    $('.changed-input').removeClass('changed-input');
                    var json = $.parseJSON(response);
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    if (json['error'] == 'accountExist') {
                        show_notify('Account Name Already Exist !', false);
                        jQuery("#account_name").focus();
                        return false;
                    }
                    if (json['error'] == 'mobileExist') {
                        show_notify('Mobile Already Exist in ' + json['msg'] + ' Account!', false);
                        return false;
                    }
                    if (json['error'] == 'Exist') {
                        show_notify(json['error_exist'], false);
                    } else if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('master/user_master_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/user_master_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });
        
        $('#add_lineitem').on('click', function () {
            if($('#line_items_index').val() == null || $('#line_items_index').val() == ''){
                var file_upload = $("#file_upload").val();    
                if (file_upload == '' || file_upload == null) {
                    $("#file_upload").focus();
                    show_notify("Please select Image!", false);
                    return false;
                }    
            }
            var key = '';
            var value = '';
            var lineitem = {};
            var is_validate = '0';

            $('input[name^="line_items_data"]').each(function (index) {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");

                if (key == 'default_image') {
                    if ($(this).prop("checked") == true) {
                        edit_index = $('#line_items_index').val();
                        $.each(lineitem_objectdata, function (index, value) {
                            var edit_index_val = index;
                            if (value.default_image == '1' && typeof (edit_index) != "undefined" && edit_index !== null && edit_index != '') {
                                if (edit_index != edit_index_val) {
                                    is_validate = '1';
                                    show_notify('You Have Selected Default Image.', false);
                                    return false;
                                }
                            } else if (value.default_image == '1') {
                                is_validate = '1';
                                show_notify('You Have Selected Default Image.', false);
                                return false;
                            }
                        });
                    }
                }
            });
            if (is_validate == '1') {
                return false;
            }

            $('select[name^="line_items_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('input[name^="line_items_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                if (key == 'default_image') {
                    if ($(this).prop("checked") == true) {
                        lineitem[key] = '1';
                    } else if ($(this).prop("checked") == false) {
                        lineitem[key] = '0';
                    }
                } else {
                    lineitem[key] = value;
                }
            });
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
            var line_items_index = $("#line_items_index").val();
            if (line_items_index != '') {
                lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
            } else {
                lineitem_objectdata.push(new_lineitem);
            } 
            display_lineitem_html(lineitem_objectdata);
            $("#image").val('');
            $("#file_upload").val('');
            $("#image_name").hide();
            $('#default_image').prop('checked', false);
            $("#line_items_index").val('');
        });
        
        $('#add_lineitem_family').on('click', function () {
            if($('#family_line_items_index').val() == null || $('#family_line_items_index').val() == ''){
                if ($.trim($("#member_name").val()) == '') {
                    $("#member_name").focus();
                    show_notify("Please Enter Family Member Name !", false);
                    return false;
                }    
            }
            var key = '';
            var value = '';
            var lineitem = {};

            $('input[name^="family_line_items_data"]').each(function (index) {
                key = $(this).attr('name');
                key = key.replace("family_line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });

            var family_new_lineitem = JSON.parse(JSON.stringify(lineitem));
            var family_line_items_index = $("#family_line_items_index").val();
            if (family_line_items_index != '') {
                family_lineitem_objectdata.splice(family_line_items_index, 1, family_new_lineitem);
            } else {
                family_lineitem_objectdata.push(family_new_lineitem);
            } 
            family_display_lineitem_html(family_lineitem_objectdata);
            $("#fm_id").val('');
            $("#member_name").val('');
            $("#member_phone_no").val('');
            $("#family_line_items_index").val('');
        });
        
        $("#department_id").on("select2:select", function (e) {
//            var item_id = $(this).val();       
            var department_id = e.params.data.id;
            var department_data = e.params.data.text;
            $('#default_department_id').append($("<option></option>").attr("value",department_id).text(department_data));
        })
        
        $('#department_id').on("select2:unselect", function(e){
            var unselected=e.params.data.id;
            $("#default_department_id option[value="+ unselected +"]").remove();
        }).trigger('change');

        $("#account_group_id").on("select2:select", function (e) {
            var account_group_id = e.params.data.id;
            var option_html = '';
            $(account_res).each(function(index,value){
                if(value.account_group_id == account_group_id) {
                    option_html += '<option value="'+value.id+'" account_group_id="'+value.account_group_id+'">'+value.text+'</option>';
                }
            });
            $("#account_id").append(option_html);
            $("#account_id").multipleSelect('refreshOptions',{});
        });

        $("#account_group_id").on("select2:unselect", function (e) {
            var account_group_id = e.params.data.id;
            $("#account_id option[account_group_id='"+account_group_id+"']").remove();
            $("#account_id").multipleSelect('refreshOptions',{});
        });

        set_default_department_list();
    });
    
    function set_default_department_list() {
       var current_default_department_id = $('#default_department_id').val();
       $.each($("#department_id").find(":selected"), function (i, item) {
           if($(item).val() != current_default_department_id){
               $('#default_department_id').append($("<option></option>").attr("value",$(item).val()).text($(item).text()));
           }
       });
    }
    
    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>';
            if (value.default_image == '1') {
                row_html += '<td><i class="fa fa-check"></td>';
            } else {
                row_html += '<td><i class="fa fa-remove"></td>';
            }
            if (value.image !== null && value.image !== '') {
                var value_image = value.image;
                var img_url = '<?php echo base_url(); ?>' + 'uploads/worker_images/' + value_image;
                row_html += '<td><a href="javascript:void(0)" class="btn btn-xs btn-primary image_model" data-img_src="' + img_url + '" ><i class="fa fa-image"></i></a></td>';
            }
            new_lineitem_html += row_html;
        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#ajax-loader').hide();
    }
    
    function family_display_lineitem_html(family_lineitem_objectdata) {
        $('#ajax-loader').show();
        var family_new_lineitem_html = '';
        $.each(family_lineitem_objectdata, function (index, value) {
            var family_lineitem_edit_btn = '';
            var family_lineitem_delete_btn = '';
            family_lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item family_edit_lineitem_' + index + '" href="javascript:void(0);" onclick="family_edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            family_lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_f_item" href="javascript:void(0);" onclick="family_remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            var family_row_html = '<tr class="family_lineitem_index_' + index + '"><td class="">' +
                    family_lineitem_edit_btn +
                    family_lineitem_delete_btn +
                    '</td>';
            family_row_html += '<td>'+value.member_name+'</td>';
            family_row_html += '<td>'+value.member_phone_no+'</td>';
            family_new_lineitem_html += family_row_html;
        });
        $('tbody#family_lineitem_list').html(family_new_lineitem_html);
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        var value = lineitem_objectdata[index];
//        console.log(value);
        $("#line_items_index").val(index);
        if (typeof (value.user_id) != "undefined" && value.user_id !== null) {
            $("#user_id").val(value.user_id);
        }
        $("#user_id").val(value.user_id);
        $("#image").val(value.image);
        $("#image_name").show();
        $("#image_name").text(value.image);
        $("#file_upload").val('');
        if (value.default_image == '1') {
            $('#default_image').prop('checked', true);
        }
    }
    
    function family_edit_lineitem(index) {
        var value = family_lineitem_objectdata[index];
//        console.log(value);
        $("#family_line_items_index").val(index);
        $(".delete_f_item").addClass('hide');
        if (typeof (value.fm_id) != "undefined" && value.fm_id !== null) {
            $("#fm_id").val(value.fm_id);
        } else {
            $("#fm_id").val('');
        }
        $("#u_user_id").val(value.user_id);
        $("#member_name").val(value.member_name);
        $("#member_phone_no").val(value.member_phone_no);
    }
    
    function remove_lineitem(index) {
        value = lineitem_objectdata[index];
        if (confirm('Are you sure ?')) {
            deleted_lineitems.push(value.image);
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }
    
    function family_remove_lineitem(index) {
        value = family_lineitem_objectdata[index];
        if (confirm('Are you sure ?')) {
            if(value.fm_id != '' || value.fm_id != null){
                family_deleted_lineitems.push(value.fm_id);
            }
            family_lineitem_objectdata.splice(index, 1);
            family_display_lineitem_html(family_lineitem_objectdata);
        }
    }
    
    function readURL(input) {
        if (input.files && input.files[0]) {
//            console.log(input.files);
            $("#ajax-loader").show();
            var form = new FormData();
            var myFormData = document.getElementById('file_upload').files[0];
            form.append('file_upload', myFormData);
            form.append('action', 'get_temp_path');
            $.ajax({
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                url: "<?= base_url('master/get_temp_path_image') ?>",
                success: function (html) {
                    $('#image').val(html);
                    $("#ajax-loader").hide();
                }
            });
        }
    }
</script>
