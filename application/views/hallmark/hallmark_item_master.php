<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Item Master
            <?php $isEdit = $this->app_model->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID, "add"); ?>

            <?php if (isset($item_data->item_id) && !empty($item_data->item_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">


                <?php if($isAdd || $isEdit) { ?>
                <!-- Horizontal Form -->
                <div class="col-md-6 col-md-push-6">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form class="form-horizontal" id="item_form"  action="<?= base_url('hallmark/save_item_master') ?>" method="post" novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($item_data->item_id) && !empty($item_data->item_id)) { ?>
                                                <input type="hidden" name="item_id" class="item_id" value="<?= $item_data->item_id ?>">
                                            <?php } ?>
                                            <label for="item_name">Item Name<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="item_name" id="item_name" class="form-control" autofocus value="<?= (isset($item_data->item_name)) ? $item_data->item_name : ''; ?>"><br />
                                            <div class="clrearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($item_data->item_id) ? '' : $btn_disable;?>><?= isset($item_data->item_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button><br/>
                                            <?php if (isset($item_data->item_id) && !empty($item_data->item_id)) { ?>
                                            <div class="created_updated_info">
                                                    Created by : <?php echo (isset($item_data->created_by_name)) ? $item_data->created_by_name : ''; ?>
                                                    @ <?php echo (isset($item_data->created_at)) ? date('d-m-Y h:i A', strtotime($item_data->created_at)) : ''; ?> <br/>
                                                    Updated by : <?php echo (isset($item_data->updated_by_name)) ? $item_data->updated_by_name : ''; ?>
                                                    @ <?php echo (isset($item_data->updated_at)) ?date('d-m-Y h:i A', strtotime($item_data->updated_at)) : '' ?>
                                            </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <div class="col-md-6 col-md-pull-6">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <?php if($isView){ ?>
                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 80px;">Action</th>
                                                        <th>Item Name</th>
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
</div>
<script>
    $(document).ready(function () {    
        table = $('#item_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('hallmark/item_master_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },

        });
    
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#item_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#item_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#item_name").val()) == '') {
                show_notify('Please Enter item Name.', false);
                $("#item_name").focus();
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('hallmark/save_item_master') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Exist') {
                        show_notify(json['error_exist'], false);
                    } else if (json['success'] == 'Added') {
                        $('input').val('');
                        table.draw();
                        show_notify('item Added Successfully!', true);
                    } else if (json['success'] == 'Updated') {
                        $('.changed-input').removeClass('changed-input');
                        window.location.href = "<?php echo base_url('hallmark/item_master') ?>";
                    }
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    return false;
                },
            });
            return false;
        });

        $(document).on("click", ".delete_button", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=item_id&table_name=item',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this item. This item has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('item Deleted Successfully!', true);
                        }
                    }
                });
            }
        });

    });
</script>
