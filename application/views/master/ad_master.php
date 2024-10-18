<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            AD Master
            <?php 
                $isEdit = $this->app_model->have_access_role(AD_MASTER_ID, "edit");
                $isView = $this->app_model->have_access_role(AD_MASTER_ID, "view");
                $isAdd = $this->app_model->have_access_role(AD_MASTER_ID, "add"); 
            ?>
            <?php if (isset($ad_id) && !empty($ad_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/ad_master') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add New</a>
            <?php } ?>
        </h1>
    </section>
	<!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-8">
                <?php if($isView){ ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">List</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!---content start --->
                            <table class="table table-striped table-bordered ad_table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Nang Setting</th>
                                        <th>Sell/Purchase Ad Charges</th>
                                        <th>Sell/Purchase Less Ad Details</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <!---content end--->
                        </div>
                        <!-- /.box-body -->
                    </div>
                <?php } ?>
                <!-- /.box -->
            </div>
            <?php if($isAdd || $isEdit) { ?>
            <!-- /.col -->
            <div class="col-md-4">
                <form id="form_ad_master" action="" enctype="multipart/form-data" data-parsley-validate="">
                    <?php if (isset($ad_id) && !empty($ad_id)) { ?>
                        <input type="hidden" id="ad_id" name="ad_id" value="<?= $ad_id; ?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title"><?= isset($ad_id) ? 'Edit' : 'Add' ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="ad_name">AD Name<span class="required-sign">&nbsp;*</span></label>
                                <input type="text" name="ad_name" autofocus="" class="form-control" id="ad_name" value="<?= isset($ad_name) ? $ad_name : '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="ad_description">AD Description</label>
                                <textarea name="ad_description" class="form-control" id="ad_description"><?= isset($ad_description) ? $ad_description : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="is_nang_setting">Nang Setting</label> &nbsp;
                                <input type="checkbox" name="is_nang_setting" id="is_nang_setting" <?= isset($is_nang_setting) && !empty($is_nang_setting) ? 'checked' : '' ?> >
                            </div>
                            <div class="form-group">
                                <label for="is_sell_purchase_ad_charges">Sell Purchase AD Charges</label> &nbsp;
                                <input type="checkbox" name="is_sell_purchase_ad_charges" id="is_sell_purchase_ad_charges" <?= isset($is_sell_purchase_ad_charges) && !empty($is_sell_purchase_ad_charges) ? 'checked' : '' ?> >
                            </div>
                            <div class="form-group">
                                <label for="is_sell_purchase_less_ad_details">Sell Purchase Less AD Details</label> &nbsp;
                                <input type="checkbox" name="is_sell_purchase_less_ad_details" id="is_sell_purchase_less_ad_details" <?= isset($is_sell_purchase_less_ad_details) && !empty($is_sell_purchase_less_ad_details) ? 'checked' : '' ?> >
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($ad_id) ? '' : $btn_disable;?>><?= isset($ad_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button><br/>
                        </div>
                    </div>
                </form>
                <!-- /.box -->
            </div>
            <?php } ?>
            <!-- /.col -->
        </div>
        <!-- /.row -->
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

        table = $('.ad_table').DataTable({
                "serverSide": true,
                "ordering": true,
                "searching": true,
                "aaSorting": [],
                "ajax": {
                        "url": "<?php echo base_url('master/ad_datatable')?>",
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
                    $("#form_ad_master").submit();
                    return false;
                }
            }
        });

        $(document).on('submit', '#form_ad_master', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#ad_name").val()) == '') {
                show_notify('Please Enter AD Name.', false);
                $("#ad_name").focus();
                return false;
            }
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_ad') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                async: false,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Exist') {
                        show_notify("AD Name Already Exist", false);
                    }
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('master/ad_master') ?>";
                    }
                    if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/ad_master') ?>";
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
                    data: 'id_name=ad_id&table_name=ad',
                    success: function(response){
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this AD. This AD has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('AD Deleted Successfully!', true);
                        }
                    }
                });
            }
        });
    });
</script>
