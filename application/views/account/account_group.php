<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Account Group
            <?php $isEdit = $this->app_model->have_access_role(ACCOUNT_GROUP_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(ACCOUNT_GROUP_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(ACCOUNT_GROUP_MODULE_ID, "add"); ?>
            <?php if (isset($id) && !empty($id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
                <a href="<?=base_url('account/account_group');?>" class="btn btn-primary pull-right btn-sm">Add New</a>
            <?php } ?>
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
            <?php if($isView) { ?>
			<div class="col-md-8">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered account_group-table">
							<thead>
								<tr>
                                    <th>Action</th>
                                    <th>Parent Account Group</th>
                                    <th>Account Group</th>
                                    <th>Sequence</th>
                                    <th>Balance Sheet</th>
                                    <th>Use in profit loss</th>
                                    <th>On MoveData Set Opening 0</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<!---content end--->
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
            <?php } ?>
			<!-- /.col -->

            <?php if($isAdd || $isEdit) { ?>
                <div class="col-md-4">
                    <form id="form_account_group" action="" enctype="multipart/form-data" data-parsley-validate="">
                        <?php if(isset($id) && !empty($id)){ ?>
                        <input type="hidden" id="id" name="id" value="<?=$id;?>">
                        <?php } ?>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title form_title"><?=isset($id) ? 'Edit' : 'Add' ?></h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="parent_group_id">Parent Account Group</label><br />
                                    <select name="parent_group_id" id="parent_group_id" class="parent_group_id"></select>
                                </div>
                                <div class="form-group">
                                    <label for="account_group_name">Account Group Name<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="account_group_name" class="form-control" id="account_group_name" placeholder="Enter Account Group" value="<?=isset($account_group_name) ? $account_group_name : '' ?>" pattern="[^'\x22]+" title="Invalid input" required>
                                </div>
                                <div class="form-group">
                                    <label for="sequence">Sequence</label>
                                    <input type="text" name="sequence" class="form-control" id="sequence" placeholder="Enter Sequence" value="<?=isset($sequence) ? $sequence : '' ?>" pattern="[^'\x22]+" title="Invalid input" >
                                </div>
                                <div class="form-group">
                                    <?php
                                        $display_in_balance_sheet_checked_status = 1;
                                        if(isset($is_display_in_balance_sheet) && $is_display_in_balance_sheet == 0) {
                                            $display_in_balance_sheet_checked_status = 0;
                                        }
                                    ?>
                                    <label for="is_display_in_balance_sheet">Display in Balance Sheet</label> &nbsp;
                                    <input type="checkbox" name="is_display_in_balance_sheet" id="is_display_in_balance_sheet" value="1" <?=$display_in_balance_sheet_checked_status == 1 ? 'checked' : '' ?>>
                                </div>
                                <div class="form-group">
                                    <label for="use_in_profit_loss">Use In Profit Loss</label> &nbsp;
                                    <input type="checkbox" name="use_in_profit_loss" id="use_in_profit_loss" <?= isset($use_in_profit_loss) && !empty($use_in_profit_loss) ? 'checked' : '' ?> >
                                </div>
                                <div class="form-group">
                                    <label for="move_data_opening_zero">On MoveData Set Opening 0</label> &nbsp;
                                    <input type="checkbox" name="move_data_opening_zero" id="move_data_opening_zero" <?= isset($move_data_opening_zero) && !empty($move_data_opening_zero) ? 'checked' : '' ?> >
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary form_btn module_save_btn btn-sm" <?php echo isset($id) ? '' : $btn_disable;?>><?=isset($id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                            </div>
                        </div>
                    </form>
                    <!-- /.box -->
                </div>
            <?php } ?>
			<!-- /.col -->
		</div>
		<!-- /.row -->
		<!-- END ALERTS AND CALLOUTS -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var module_submit_flag = 0;
    var table;
    $(document).ready(function(){
        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
        });

        initAjaxSelect2($("#parent_group_id"),"<?=base_url('app/account_group_select2_source_for_account')?>");
        <?php if(isset($parent_group_id) && !empty($parent_group_id)){ ?>
        setSelect2Value($("#parent_group_id"),"<?=base_url('app/set_account_group_select2_val_by_id/'.$parent_group_id)?>");
        <?php } ?>
        table = $('.account_group-table').DataTable({
                "serverSide": true,
                "ordering": true,
                "searching": true,
                "aaSorting": [[1, 'asc']],
                "ajax": {
                        "url": "<?php echo base_url('account/account_group_datatable')?>",
                        "type": "POST"
                },
                "scrollY": '<?php echo ACCOUNT_LIST_TABLE_HEIGHT;?>',
                "scroller": {
                        "loadingIndicator": true
                },
                "columnDefs": [
                        {"targets": 0, "orderable": false }
                ]
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                e.preventDefault();
                if(module_submit_flag == 0 ){
                    $("#form_account_group").submit();
                    return false;
                }
            }
        });

        $(document).on('submit', '#form_account_group', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#account_group_name").val()) == '') {
                show_notify('Please Enter Account Group Name.', false);
                $("#account_group_name").focus();
                return false;
            }
            var parent_group_id = $('#parent_group_id').val();
            var account_group_name = $('#account_group_name').val();
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('account/save_account_group') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                async: false,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Exist') {
                        show_notify("Account Group Already Exist", false);
                    }
                    if (json['success'] == 'Added') {
                        $('#form_account_group').find('input:text, select, textarea').val('');
                        $("#parent_group_id").val(null).trigger("change");
//                        $('#use_in_profit_loss').removeAttr('checked', 'checked');
                         $('#use_in_profit_loss').iCheck('uncheck');
                         $('#move_data_opening_zero').iCheck('uncheck');
                        table.draw();
                        show_notify('Account Group Added Successfully!', true);
                    }
                    if (json['success'] == 'Updated') {
                        $('#form_account_group').find('input:text, select, textarea').val('');
                        $("#parent_group_id").val(null).trigger("change");
                        $('#use_in_profit_loss').iCheck('uncheck');
                        $('#move_data_opening_zero').iCheck('uncheck');
                        $(".form_btn").html('Save');
                        $(".form_title").html('Add');
                        $('input[name="id"]').val("");
                        table.draw();
                        show_notify('Account Group Successfully Updated.', true);
                    }
                    return false;
                },
            });
            module_submit_flag = 1;
            return false;
        });

        $(document).on("click",".delete_button",function(){
            var value = confirm('Are you sure delete this records?');
            var tr = $(this).closest("tr");
            if(value){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=account_group_id&table_name=account_group',
                    success: function(data){
                        var json = $.parseJSON(data);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Account Group. This Account Group has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Account Group Deleted Successfully!', true);
                        }
                    }
                });
            }
        });
    });
</script>
