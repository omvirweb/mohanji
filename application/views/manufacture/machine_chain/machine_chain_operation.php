<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('machine_chain/save_operation') ?>" method="post" id="save_operation" novalidate enctype="multipart/form-data">
        <?php if (isset($op_data->operation_id) && !empty($op_data->operation_id)) { ?>
            <input type="hidden" name="operation_id" class="operation_id" value="<?= $op_data->operation_id ?>">
        <?php } ?>
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <h1>
                Machine Chain Operation
                <?php
                    $isEdit = $this->app_model->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, "edit");
                    $isView = $this->app_model->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, "view");
                    $isAdd = $this->app_model->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, "add");
                    if (isset($op_data->operation_id) && !empty($op_data->operation_id)) {
                    } else {
                        if (isset($isAdd) && $isAdd) {
                            $btn_disable = null;
                        } else {
                            $btn_disable = 'disabled';
                        }
                    } 
                ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($op_data->operation_id) ? '' : $btn_disable; ?>><?= isset($op_data->operation_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                <?php if ($isView) { ?>
                    <a href="<?= base_url('machine_chain/operation_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Machine Chain Operation List</a>
                <?php } ?>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <?php if ($isAdd || $isEdit) { ?>
                        <!-- Horizontal Form -->
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <label for="operation_name">Operation Name<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="operation_name" id="operation_name" class="form-control" value="<?= (isset($op_data->operation_name)) ? $op_data->operation_name : ''; ?>">
                                            <div class="clearfix"></div><br />
                                        </div>
                                        <div class="col-md-6">
                                            <label for="sequence_no">Sequence No.<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="sequence_no" id="sequence_no" class="form-control num_only" value="<?= (isset($op_data->sequence_no)) ? $op_data->sequence_no : ''; ?>">
                                            <div class="clearfix"></div><br />
                                        </div>
                                        <div class="col-md-6">
                                            <label for="department_id">Department(s)<span class="required-sign">&nbsp;*</span></label>
                                            <select name="department_id[]" id="department_id" class="form-control select2" multiple="">
                                                <?php 
                                                    foreach($user_department as $user) { 
                                                    ?>
                                                        <option value="<?= $user->account_id;?>" 
                                                        <?php if(!empty($department) && in_array($user->account_id, $department)){ echo ' Selected '; } ?> 
                                                        ><?= $user->account_name; ?></option>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="worker_id">Worker(s)<span class="required-sign">&nbsp;*</span></label>
                                            <select name="worker_id[]" id="worker_id" class="form-control select2" multiple="">
                                                <?php 
                                                    foreach($user_worker as $user) { 
                                                ?>
                                                    <option value="<?= $user->account_id;?>" 
                                                    <?php if(!empty($worker) && in_array($user->account_id, $worker)){ echo ' Selected '; } ?> 
                                                    ><?= $user->account_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="clearfix"></div><br />
                                        </div>
                                        <div class="col-md-6">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark" id="remark" class="form-control" rows="3"><?= (isset($op_data->remark)) ? $op_data->remark : ''; ?></textarea>
                                            <div class="clearfix"></div><br />
                                        </div>
                                        <div class="col-md-3">
                                            <br />
                                            <label for="allow_only_1_order_item">
                                                <input type="checkbox" name="allow_only_1_order_item" id="allow_only_1_order_item" value="1" <?= (isset($op_data->allow_only_1_order_item) && $op_data->allow_only_1_order_item == 1) ? 'checked' : ''; ?>> Allow Only 1 Order Item?
                                            </label>
                                            <div class="clearfix"></div>
                                            <label for="direct_issue_allow">
                                                <input type="checkbox" name="direct_issue_allow" id="direct_issue_allow" value="1" <?= (isset($op_data->direct_issue_allow) && $op_data->direct_issue_allow == 1) ? 'checked' : ''; ?>> Direct issue Allow
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <br />
                                            <label for="calculate_button">
                                                <input type="checkbox" name="calculate_button" id="calculate_button" value="1" <?= (isset($op_data->calculate_button) && $op_data->calculate_button == 1) ? 'checked' : ''; ?>> Calculate Button
                                            </label>
                                            <div class="clearfix"></div>
                                            <label for="use_selected_tunch">
                                                <input type="checkbox" name="use_selected_tunch" id="use_selected_tunch" value="1" <?= (isset($op_data->use_selected_tunch) && $op_data->use_selected_tunch == 1) ? 'checked' : ''; ?>> Use Selected Tunch
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            
                                        </div>
                                        <div class="col-md-3">
                                            <label for="issue_change_actual_tunch_allow">
                                                <input type="checkbox" name="issue_change_actual_tunch_allow" id="issue_change_actual_tunch_allow" value="1" <?= (isset($op_data->issue_change_actual_tunch_allow) && $op_data->issue_change_actual_tunch_allow == 1) ? 'checked' : ''; ?>> Issue : Change Actual Tunch Allow
                                            </label>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="receive_change_actual_tunch_allow">
                                                <input type="checkbox" name="receive_change_actual_tunch_allow" id="receive_change_actual_tunch_allow" value="1" <?= (isset($op_data->receive_change_actual_tunch_allow) && $op_data->receive_change_actual_tunch_allow == 1) ? 'checked' : ''; ?>> Receive : Change Actual Tunch Allow
                                            </label>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="col-md-12">
                                            <?php if (isset($op_data->operation_id) && !empty($op_data->operation_id)) { ?>
                                                <div class="created_updated_info">
                                                    Created by : <?php echo (isset($op_data->created_by_name)) ? $op_data->created_by_name : ''; ?>
                                                    @ <?php echo (isset($op_data->created_at)) ? date('d-m-Y h:i A', strtotime($op_data->created_at)) : ''; ?> <br/>
                                                    Updated by : <?php echo (isset($op_data->updated_by_name)) ? $op_data->updated_by_name : ''; ?>
                                                    @ <?php echo (isset($op_data->updated_at)) ? date('d-m-Y h:i A', strtotime($op_data->updated_at)) : '' ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var module_submit_flag = 0;
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    $(document).ready(function () {
        $('.select2').select2();
        $(document).bind("keydown", function (e) {
            if (e.ctrlKey && e.which == 83) {
                e.preventDefault();
                if(module_submit_flag == 0 ){
                    $("#save_operation").submit();
                    return false;
                }
            }
        });

        $(document).on('change','#calculate_button', function () {
            if($(this).is(":checked") && $("#use_selected_tunch").is(":checked")) {
                show_notify('Not Allow To Check <b>Calculate Button</b>, Because of You Have Checked <b>Use Selected Tunch</b>', false);
                $(this).prop("checked",false);
            }
        });

        $(document).on('change','#use_selected_tunch', function () {
            if($(this).is(":checked") && $("#calculate_button").is(":checked")) {
                show_notify('Not Allow To Check <b>Use Selected Tunch</b>, Because of You Have Checked <b>Calculate Button</b>', false);
                $(this).prop("checked",false);
            }
        });

        $(document).on('submit', '#save_operation', function () {
            if ($.trim($("#operation_name").val()) == '') {
                show_notify('Please Enter Operation Name.', false);
                $("#operation_name").focus();
                return false;
            }
            if ($.trim($("#sequence_no").val()) == '') {
                show_notify('Please Enter Sequence No.', false);
                $("#sequence_no").focus();
                return false;
            }
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                return false;
            }
            if ($.trim($("#worker_id").val()) == '') {
                show_notify('Please Select Worker Name.', false);
                $("#worker_id").select2('open');
                return false;
            }
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('machine_chain/save_operation') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                async: false,
                success: function (response) {
                    $('.changed-input').removeClass('changed-input');
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('machine_chain/operation_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('machine_chain/operation_list') ?>";
                    }
                    return false;
                },
            });
            module_submit_flag = 1;
            return false;
        });
    });
</script>
