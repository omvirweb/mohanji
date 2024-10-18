<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Rights
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
                                <div class="col-md-12">
                                    <form class="main-frm">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">User Type<span class="required-sign">*</span></label>
                                            <div class="col-sm-4">
                                                <select class="form-control select2" id="user_type" name="user_type" onchange="window.location='<?php echo base_url(); ?>master/user_rights?user_type='+$(this).val();">
                                                    <option value="">- Select User - </option>
                                                    <?php foreach($users as $user):?>
                                                    <option <?php echo $user_type_id == $user->user_id ? 'selected="selected"':''; ?> value="<?php echo $user->user_id; ?>"><?php echo $user->user_name; ?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <a class="btn btn-success btn-update-roles pull-right" style="position:fixed; right:30px;">Update [ Ctrl + S ]</a>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                Module AND Roles
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="20%">Module</th>
                                                            <th width="80%">
                                                                Roles
                                                                <a class="btn btn-xs btn-danger un-chk-all pull-right">Un Select ALL</a>
                                                                <a class="btn btn-xs btn-primary chk-all pull-right" style="margin-right: 5px;">Select ALL</a>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if(count($modules_roles) > 0):
                                                        ?>
                                                        <?php foreach($modules_roles as $key => $row):?>
                                                        <tr>
                                                            <td><?php echo $row['title'];?></td>
                                                            <td>
                                                                <?php foreach($row['roles'] as $role):?>
                                                                <label class="col-sm-2">
                                                                    <?php if(($role['website_module_id'] == HALLMARK_MODULE_ID && PRO_MODULE_HALLMARK == 'no') || ($role['website_module_id'] == HALLMARK_ITEM_MASTER_MODULE_ID && PRO_MODULE_HALLMARK == 'no') || ($role['website_module_id'] == HALLMARK_RECEIPT_MODULE_ID && PRO_MODULE_HALLMARK == 'no') || ($role['website_module_id'] == HALLMARK_XRF_MODULE_ID && PRO_MODULE_HALLMARK == 'no')
                                                                         || ($role['website_module_id'] == DEMO_MODULE_ID && PRO_MODULE_DEMOMENU == 'no')
                                                                         || ($role['website_module_id'] == TRADING_PL_MODULE_ID && PRO_MODULE_TRADING_PL == 'no')
                                                                         || ($role['website_module_id'] == CALCULATIONS_MODULE_ID && PRO_MODULE_CALCULATIONS_REPORT == 'no')
                                                                         || ($role['website_module_id'] == MACHINE_CHAIN_OPERATION_MODULE_ID && PRO_MODULE_MANUFACTURE_MACHINE_CHAIN == 'no')|| ($role['website_module_id'] == MACHINE_CHAIN_MODULE_ID && PRO_MODULE_MANUFACTURE_MACHINE_CHAIN == 'no')
                                                                         || ($role['website_module_id'] == CASTING_MODULE_ID && PRO_MODULE_MANUFACTURE_CASTING == 'no')
                                                                         || ($role['website_module_id'] == ORDER_MENU_ID && PRO_MODULE_ORDER == 'no') || ($role['website_module_id'] == ORDER_MODULE_ID && PRO_MODULE_ORDER == 'no') || ($role['website_module_id'] == ORDER_SLIDER_MODULE_ID && PRO_MODULE_ORDER == 'no')
                                                                         || ($role['website_module_id'] == SELL_PURCHASE_MENU_ID && PRO_MODULE_SELL_PURCHASE_MENU == 'no')
                                                                         || ($role['website_module_id'] == SELL_PURCHASE_MODULE_ID && PRO_MODULE_SELL_PURCHASE == 'no')
                                                                         || ($role['website_module_id'] == OTHER_ENTRY_MODULE_ID && PRO_MODULE_SELL_PURCHASE_OTHER == 'no')
                                                                         || ($role['website_module_id'] == STOCK_TRANSFER_MODULE_ID && PRO_MODULE_STOCK_TRANSFER == 'no')
                                                                         || ($role['website_module_id'] == JOURNAL_MODULE_ID && PRO_MODULE_JOURNAL == 'no')
                                                                         || ($role['website_module_id'] == CASHBOOK_MODULE_ID && PRO_MODULE_CASHBOOK == 'no')
                                                                         || ($role['website_module_id'] == IMPORT_DATA_MODULE_ID && PRO_MODULE_IMPORT_DATA == 'no')
                                                                         || ($role['website_module_id'] == HR_MODULE_ID && PRO_MODULE_HR == 'no') || ($role['website_module_id'] == HR_ATTENDANCE_MODULE_ID && PRO_MODULE_HR == 'no') || ($role['website_module_id'] == YEARLY_LEAVES_ID && PRO_MODULE_HR == 'no') || ($role['website_module_id'] == APPLY_LEAVE_ID && PRO_MODULE_HR == 'no') || ($role['website_module_id'] == PRESENT_HOURS_MODULE_ID && PRO_MODULE_HR == 'no') || ($role['website_module_id'] == EMPLOYEE_SALARY_MODULE_ID && PRO_MODULE_HR == 'no')
                                                                         || ($role['website_module_id'] == MANUFACTURE_MODULE_ID && PRO_MODULE_MANUFACTURE_MENU == 'no')
                                                                         || ($role['website_module_id'] == ISSUE_RECEIVE_MODULE_ID && PRO_MODULE_MANUFACTURE_ISSUE_RECEIVE == 'no')
                                                                         || ($role['website_module_id'] == ISSUE_RECEIVE_SILVER_MODULE_ID && PRO_MODULE_MANUFACTURE_ISSUE_RECEIVE_SILVER == 'no')
                                                                         || ($role['website_module_id'] == OPERATION_MODULE_ID && PRO_MODULE_OPERATION == 'no')
                                                                         || ($role['website_module_id'] == MANU_HAND_MADE_MODULE_ID && PRO_MODULE_MANU_HAND_MADE == 'no')
                                                                         || ($role['website_module_id'] == BACKUP_MODULE_ID && PRO_MODULE_BACKUP == 'no')
                                                                         || ($role['website_module_id'] == SERVER_SHUTDOWN_MODULE_ID && ALLOW_SERVER_SHUTDOWN == '0')
                                                                            ){ ?>
                                                                            <label class="label label-success"><i class="fa fa-rupee"></i></label>
                                                                    <?php } else { ?>
                                                                        <input type="checkbox" <?php echo in_array($role['module_role_id'], $user_roles) ? 'checked="checked"':''; ?> class="chkids <?php echo $row['main_module']; ?>" value="<?php echo $role['module_role_id'];?>" name="roles[<?php echo $role['module_role_id'];?>_<?php echo $role['website_module_id'];?>]" /> 
                                                                    <?php } ?>
                                                                    <?php echo str_replace("_", " ", ucwords($role['title']));?>
                                                                </label>
                                                                <?php endforeach;?>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                        <?php endif;?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <a class="btn btn-success btn-update-roles pull-right">Update [ Ctrl + S ]</a>
                                    </form>
                                </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#user_type').select2();
        $(".chk-all").click(function(){
            $(".chkids").prop("checked",true);
        });

        $(".un-chk-all").click(function(){
            $(".chkids").prop("checked",false);
        });
        
        $(document).on("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                update_rights();
                return false;
            }
        });

        $(".btn-update-roles").click(function(){
            update_rights();
            return false;
        });
    });
    
    function update_rights(){
        $.ajax({
            type: 'post',
            url: '<?=base_url("master/update_roles/")?>',
            data: $('.main-frm').serialize(),
            success: function(data) {
                var data = JSON.parse(data);
                $msg = data.msg;
                if(data.status == 1)
                {
                    show_notify($msg,true);
                }
                else
                {
                    show_notify($msg,false);
                }

            },
        });
    }
</script>
