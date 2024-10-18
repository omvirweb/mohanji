<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Design Master
            <?php $isEdit = $this->app_model->have_access_role(DESIGN_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(DESIGN_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(DESIGN_MODULE_ID, "add"); ?>
            <?php if (isset($design_data->design_id) && !empty($design_data->design_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/design_master') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Design Master</a>
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
                                            <table id="design_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 80px;">Action</th>
                                                        <th>Design No</th>                                                    
                                                        <th>File No</th>
                                                        <th>Stl/3dm No</th>                                                    
                                                        <th>Die Making</th>
                                                        <th>Die No</th>
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
                                        <form class="form-horizontal" id="design_form"  action="<?= base_url('master/save_lot_master') ?>" method="post" novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($design_data->design_id) && !empty($design_data->design_id)) { ?>
                                                <input type="hidden" name="design_id" class="design_id" value="<?= $design_data->design_id ?>">
                                            <?php } ?>
                                            <label for="design_no">Design No</label>
                                            <input type="text" name="design_no" id="design_no" class="form-control" autofocus value="<?= (isset($design_data->design_no)) ? $design_data->design_no : ''; ?>"><br />
                                            <label for="file_no">File No</label>
                                            <input type="text" name="file_no" id="file_no" class="form-control" value="<?= (isset($design_data->file_no)) ? $design_data->file_no : ''; ?>"><br />
                                            <label for="stl_3dm_no">STL/3DM No</label>
                                            <input type="text" name="stl_3dm_no" id="stl_3dm_no" class="form-control" value="<?= (isset($design_data->stl_3dm_no)) ? $design_data->stl_3dm_no : ''; ?>"><br />
                                            <label for="die_making">Die Making<span class="required-sign">&nbsp;*</span></label>
                                            <select name="die_making" id="die_making" class="form-control ">
                                                <option value="">--Select--</option>
                                                <option value="Yes" <?php echo isset($design_data->die_making) && $design_data->die_making == 'Yes' ? 'Selected' : ''; ?>>Yes</option>
                                                <option value="No" <?php echo isset($design_data->die_making) && $design_data->die_making == 'No' ? 'Selected' : ''; ?>>No</option>
                                            </select><br />
                                            <div class="clrearfix"></div>
                                            <label for="die_no" style="margin-top: 10px;">Die No</label>
                                            <input type="text" name="die_no" id="die_no" class="form-control" value="<?= (isset($design_data->die_no)) ? $design_data->die_no : ''; ?>"><br />
                                            <div class="clrearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($design_data->design_id) ? '' : $btn_disable;?>><?= isset($design_data->design_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                                            <?php if (isset($design_data->design_id) && !empty($design_data->design_id)) { ?>
                                            <div class="created_updated_info">
                                                    Created by : <?php echo (isset($design_data->created_by_name)) ? $design_data->created_by_name : ''; ?> 
                                                    @ <?php echo (isset($design_data->created_at)) ? date('d-m-Y h:i A', strtotime($design_data->created_at)) : ''; ?><br/> 
                                                    Updated by : <?php echo (isset($design_data->updated_by_name)) ? $design_data->updated_by_name : ''; ?>
                                                    @ <?php echo (isset($design_data->updated_at)) ? date('d-m-Y h:i A', strtotime($design_data->updated_at)) : ''; ?>
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
        $('#die_making').select2({width: '100%'});
    
        table = $('#design_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('master/design_master_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },

        });
    
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#design_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#design_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#die_making").val()) == '') {
                show_notify('Please Select Die Making.', false);
                $("#die_making").select2('open');
                return false;
            }
            
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_design_master') ?>",
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
                        $('#die_making').val(null).trigger('change');
                        table.draw();
                        show_notify('Design Master Added Successfully!', true);
                    }else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/design_master') ?>";
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
                    data: '',
                    success: function (data) {
                        table.draw();
                        show_notify('Design Deleted Successfully!', true);
                    }
                });
            }
        });

    });
</script>
