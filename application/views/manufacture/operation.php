<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('manu_hand_made/save_operation') ?>" method="post" id="save_operation" novalidate enctype="multipart/form-data">
        <?php if (isset($op_data->operation_id) && !empty($op_data->operation_id)) { ?>
            <input type="hidden" name="operation_id" class="operation_id" value="<?= $op_data->operation_id ?>">
        <?php } ?>
            <input type="hidden" id="total_grwt_sell" value=""/>

        <!-- Content Header (Page header) -->
     
        <section class="content-header">
            <h1>
                Operation
                <?php $isEdit = $this->app_model->have_access_role(OPERATION_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(OPERATION_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(OPERATION_MODULE_ID, "add"); ?>
                <?php if(isset($op_data->operation_id) && !empty($op_data->operation_id)) { } else { if(isset($isAdd) && $isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($op_data->operation_id) ? '' : $btn_disable;?>><?= isset($op_data->operation_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('manu_hand_made/operation_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Operation List</a>
                <?php } ?>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <?php if($isAdd || $isEdit) { ?>
                    <!-- Horizontal Form -->
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                    <div class="col-md-12">
                                        <?php if(isset($op_data->operation_id) && !empty($op_data->operation_id)) { ?>
                                            <input type="hidden" name="operation_id" value="<?php echo $op_data->operation_id; ?>">
                                        <?php } ?>
                                        <?php if(isset($op_data->operation_id) && ($op_data->operation_id == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID || $op_data->operation_id == MANUFACTURE_HM_OPERATION_MEENA_ID)) { ?>
                                            <label class="form-control"><?php echo (isset($op_data->operation_name)) ? $op_data->operation_name  : ''; ?></label>
                                            <input type="hidden" name="operation_name" id="operation_name" value="<?php echo (isset($op_data->operation_name)) ? $op_data->operation_name  : ''; ?>">
                                        <?php } else { ?>
                                            <label for="operation_name">Operation Name<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="operation_name" id="operation_name" class="form-control" value="<?= (isset($op_data->operation_name)) ? $op_data->operation_name  : ''; ?>">
                                        <?php } ?>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
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
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <label for="worker_id">Worker/Supplier(s)<span class="required-sign">&nbsp;*</span></label>
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
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <?php if (isset($op_data->operation_id) && !empty($op_data->operation_id)) { ?>
                                            <div class="created_updated_info">
                                                Created by : <?php echo (isset($op_data->created_by_name)) ? $op_data->created_by_name : ''; ?>
                                                @ <?php echo (isset($op_data->created_at)) ? date('d-m-Y h:i A', strtotime($op_data->created_at)) : ''; ?> <br/>
                                                Updated by : <?php echo (isset($op_data->updated_by_name)) ? $op_data->updated_by_name : ''; ?>
                                                @ <?php echo (isset($op_data->updated_at)) ?date('d-m-Y h:i A', strtotime($op_data->updated_at)) : '' ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-3">
                                            <label for="fix_loss">Fix Loss ?</label><br>
                                            <label><input type="radio" name="fix_loss" class="iradio_minimal-blue" value="1" <?= (isset($op_data->fix_loss)) && $op_data->fix_loss == 1 ? 'checked' : ''; ?> <?php if(isset($op_data->operation_id) && ($op_data->operation_id == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID || $op_data->operation_id == MANUFACTURE_HM_OPERATION_MEENA_ID)) { echo 'disabled=""'; } ?> > Yes</label> &nbsp;&nbsp;
                                            <label><input type="radio" name="fix_loss" class="iradio_minimal-blue" value="0" <?= (isset($op_data->fix_loss)) ? $op_data->fix_loss == 0 ? 'checked' : '' : 'checked'; ?> <?php if(isset($op_data->operation_id) && ($op_data->operation_id == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID || $op_data->operation_id == MANUFACTURE_HM_OPERATION_MEENA_ID)) { echo 'disabled=""'; } ?> > No</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">&nbsp;</label><br>
                                            <?php $readonly =  (isset($op_data->fix_loss)) ? $op_data->fix_loss == 0 ? 'readonly' : '' : 'readonly'; ?>
                                            <input type="text" name="fix_loss_per" id="fix_loss_per" class="num_only form-control" <?= $readonly; ?> value="<?= (isset($op_data->fix_loss_per)) ? $op_data->fix_loss_per  : ''; ?>">
                                        </div>
                                        <div class="max_loss_div  <?= !empty($readonly) ? 'hidden'  : ''; ?> ">
                                            <div class="col-md-3">
                                                <label for="max_loss_allow"></label>Allow Maximum Loss ?</label><br>
                                                <label><input type="radio" name="max_loss_allow" class="iradio_minimal-blue" value="1" <?= (isset($op_data->max_loss_allow)) && $op_data->max_loss_allow == 1 ? 'checked' : ''; ?>> Yes</label> &nbsp;&nbsp;
                                                <label><input type="radio" name="max_loss_allow" class="max_loss_allow_no iradio_minimal-blue" value="0" <?= (isset($op_data->max_loss_allow)) ? $op_data->max_loss_allow == 0 ? 'checked' : '' : 'checked'; ?>> No</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">&nbsp;</label><br>
                                                <?php $readonly_max_loss_allow =  (isset($op_data->max_loss_allow)) ? $op_data->max_loss_allow == 0 ? 'readonly' : '' : 'readonly'; ?>
                                                <input type="text" name="max_loss_wt" id="max_loss_wt" class="num_only form-control" <?= $readonly_max_loss_allow; ?> value="<?= (isset($op_data->max_loss_wt)) ? $op_data->max_loss_wt  : ''; ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="issue_finish_fix_loss">Issue Finish Fix Loss ?</label><br>
                                                <label><input type="radio" name="issue_finish_fix_loss" class="iradio_minimal-blue" value="1" <?= (isset($op_data->issue_finish_fix_loss)) && $op_data->issue_finish_fix_loss == 1 ? 'checked' : ''; ?>> Yes</label> &nbsp;&nbsp;
                                                <label><input type="radio" name="issue_finish_fix_loss" class="issue_finish_fix_loss_no iradio_minimal-blue" value="0" <?= (isset($op_data->issue_finish_fix_loss)) ? $op_data->issue_finish_fix_loss == 0 ? 'checked' : '' : 'checked'; ?>> No</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">&nbsp;</label><br>
                                                <?php $readonly =  (isset($op_data->issue_finish_fix_loss)) ? $op_data->issue_finish_fix_loss == 0 ? 'readonly' : '' : 'readonly'; ?>
                                                <input type="text" name="issue_finish_fix_loss_per" id="issue_finish_fix_loss_per" class="num_only form-control" <?= $readonly; ?> value="<?= (isset($op_data->issue_finish_fix_loss_per)) ? $op_data->issue_finish_fix_loss_per  : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark" id="remark" class="form-control"><?= (isset($op_data->remark)) ? $op_data->remark : ''; ?></textarea>
                                            <div class="clearfix"></div><br />
                                        </div>
                                        <div class="clearfix"></div>
                                   </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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

        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        
        $('#datepicker2, .datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            endDate: "today",
            maxDate: 0,
        });
        
        $('input[type=radio][name=fix_loss]').change(function() {
            if($(this).val() == 0){
                $('#fix_loss_per').attr('readonly', 'readonly');
                $('#fix_loss_per').val('');
                $('#max_loss_wt').attr('readonly', 'readonly');
                $('#max_loss_wt').val('');
//                $(".max_loss_allow_yes").prop("checked", true);
                $(".max_loss_allow_no").prop("checked", true);
                $(".issue_finish_fix_loss_no").prop("checked", true);
                $('#issue_finish_fix_loss_per').attr('readonly', 'readonly');
                $('#issue_finish_fix_loss_per').val('');
                $('.max_loss_div').hide();
            } else {
                $('#fix_loss_per').removeAttr('readonly', 'readonly');
                $('.max_loss_div').removeClass('hidden', 'hidden');
                $('.max_loss_div').show();
            }
        });
        
        $('input[type=radio][name=max_loss_allow]').change(function() {
            if($(this).val() == 0){
                $('#max_loss_wt').attr('readonly', 'readonly');
                $('#max_loss_wt').val('');
            } else {
                $('#max_loss_wt').removeAttr('readonly', 'readonly');
            }
        });
        
        $('input[type=radio][name=issue_finish_fix_loss]').change(function() {
            if($(this).val() == 0){
                $('#issue_finish_fix_loss_per').attr('readonly', 'readonly');
                $('#issue_finish_fix_loss_per').val('');
            } else {
                $('#issue_finish_fix_loss_per').removeAttr('readonly', 'readonly');
            }
        });
        
        $(document).on('change', '#issue_finish_fix_loss_per', function () {
            var fix_loss_per = $.trim($("#fix_loss_per").val()) || 0;
            var issue_finish_fix_loss_per = $.trim($("#issue_finish_fix_loss_per").val()) || 0;
            if(parseFloat(fix_loss_per) >= parseFloat(issue_finish_fix_loss_per)){
            } else {
                show_notify('Enter fix loss >= issue finish fix loss.', false);
                $("#issue_finish_fix_loss_per").focus();
                return false;
            }
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                e.preventDefault();
                if(module_submit_flag == 0 ){
                    $("#save_operation").submit();
                    return false;
                }
            }
        });

        $(document).on('submit', '#save_operation', function () {
            if ($.trim($("#operation_name").val()) == '') {
                show_notify('Please Enter Operation Name.', false);
                $("#operation_name").focus();
                return false;
            }
            if ($("input[name='fix_loss']:checked").val() == 0) {
            } else {
                if ($.trim($("#fix_loss_per").val()) == '') {
                    show_notify('Please Enter Fix Loss.', false);
                    $("#fix_loss_per").focus();
                    return false;
                }
                var fix_loss_per = $.trim($("#fix_loss_per").val()) || 0;
                var issue_finish_fix_loss_per = $.trim($("#issue_finish_fix_loss_per").val()) || 0;
                if(parseFloat(fix_loss_per) >= parseFloat(issue_finish_fix_loss_per)){
                } else {
                    show_notify('Enter fix loss >= issue finish fix loss.', false);
                    $("#issue_finish_fix_loss_per").focus();
                    return false;
                }
            }
            if ($("input[name='max_loss_allow']:checked").val() == 0) {
            } else {
                if ($.trim($("#max_loss_wt").val()) == '') {
                    show_notify('Please Enter Max Loss.', false);
                    $("#max_loss_wt").focus();
                    return false;
                }
            }
            if ($("input[name='issue_finish_fix_loss']:checked").val() == 0) {
            } else {
                if ($.trim($("#issue_finish_fix_loss_per").val()) == '') {
                    show_notify('Please Enter Issue Finish Fix Loss.', false);
                    $("#issue_finish_fix_loss_per").focus();
                    return false;
                }
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
            

//            $("#ajax-loader").show();
//            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('manu_hand_made/save_operation') ?>",
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
                        window.location.href = "<?php echo base_url('manu_hand_made/operation_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('manu_hand_made/operation_list') ?>";
                    }
                    return false;
                },
            });
            module_submit_flag = 1;
            return false;
        });
        
        
    });
    
</script>
