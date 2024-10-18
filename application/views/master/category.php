<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Category
            <?php $isEdit = $this->app_model->have_access_role(CATEGORY_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(CATEGORY_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(CATEGORY_MODULE_ID, "add"); ?>
            <?php if (isset($category_data->category_id) && !empty($category_data->category_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/category') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Category</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-6">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <?php if($isView){ ?>
                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <table id="category_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 80px;">Action</th>
                                                        <th>Category Name</th>
                                                        <th>Category Group Name</th>
                                                        <th>HSN Code</th>
                                                        <th>GST Rate</th>
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
                <?php if($isAdd || $isEdit) { ?>
                <!-- Horizontal Form -->
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form class="form-horizontal" id="category_form"  action="<?= base_url('master/save_lot_master') ?>" method="post" novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($category_data->category_id) && !empty($category_data->category_id)) { ?>
                                                <input type="hidden" name="category_id" class="category_id" value="<?= $category_data->category_id ?>">
                                            <?php } ?>
                                            <label for="category_name">Category Name<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="category_name" id="category_name" class="form-control" autofocus value="<?= (isset($category_data->category_name)) ? $category_data->category_name : ''; ?>"><br />
                                            <label for="category_group_id">Category Group Name<span class="required-sign">&nbsp;*</span></label>
                                            <select name="category_group_id" id="category_group_id" class="form-control select2"></select>
                                            <div class="clrearfix"></div><br>
                                            <label for="hsn_code">HSN Code<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="hsn_code" id="hsn_code" class="form-control" value="<?= (isset($category_data->hsn_code)) ? $category_data->hsn_code : ''; ?>"><br />
                                            <label for="gst_rate">GST Rate<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="gst_rate" id="gst_rate" class="form-control num_only" value="<?= (isset($category_data->gst_rate)) ? $category_data->gst_rate : ''; ?>"><br />
                                            <div class="clrearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($category_data->category_id) ? '' : $btn_disable;?>><?= isset($category_data->category_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button><br/>
                                            <?php if (isset($category_data->category_id) && !empty($category_data->category_id)) { ?>
                                            <div class="created_updated_info">
                                                    Created by : <?php echo (isset($category_data->created_by_name)) ? $category_data->created_by_name : ''; ?>
                                                    @ <?php echo (isset($category_data->created_at)) ? date('d-m-Y h:i A', strtotime($category_data->created_at)) : ''; ?> <br/>
                                                    Updated by : <?php echo (isset($category_data->updated_by_name)) ? $category_data->updated_by_name : ''; ?>
                                                    @ <?php echo (isset($category_data->updated_at)) ?date('d-m-Y h:i A', strtotime($category_data->updated_at)) : '' ?>
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
            </div>
        </div>
    </div>
</div>
<script>
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    $(document).ready(function () {
        initAjaxSelect2($("#category_group_id"), "<?= base_url('app/category_group_select2_source') ?>");
    <?php if (isset($category_data->category_group_id)) { ?>
            setSelect2Value($("#category_group_id"), "<?= base_url('app/set_category_group_select2_val_by_id/' . $category_data->category_group_id) ?>");
    <?php } ?>
    
        table = $('#category_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('master/category_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },

        });
    
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#category_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#category_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#category_name").val()) == '') {
                show_notify('Please Enter Category Name.', false);
                $("#category_name").focus();
                return false;
            }
            if ($.trim($("#category_group_id").val()) == '') {
                show_notify('Please Select Category Group Name.', false);
                $("#category_group_id").select2('open');
                return false;
            }
            if ($.trim($("#hsn_code").val()) == '') {
                show_notify('Please Enter HSN Code.', false);
                $("#hsn_code").focus();
                return false;
            }
            if ($.trim($("#gst_rate").val()) == '') {
                show_notify('Please Enter GST Rate.', false);
                $("#gst_rate").focus();
                return false;
            }
            
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_category') ?>",
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
                        $('#category_group_id').find('option').remove().end().val('whatever');
                        table.draw();
                        show_notify('Category Added Successfully!', true);
                    }else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/category') ?>";
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
                    data: 'id_name=category_id&table_name=category',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Category. This Category has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Category Deleted Successfully!', true);
                        }
                    }
                });
            }
        });

    });
</script>
