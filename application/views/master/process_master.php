<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Department Master
          <?php $isEdit = $this->app_model->have_access_role(DEPARTMENT_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(DEPARTMENT_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(DEPARTMENT_MODULE_ID, "add"); ?>
            <?php if (isset($process_master_data->process_id) && !empty($process_master_data->process_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>  
                <a href="<?= base_url('master/process_master') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Department</a>
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
                                <?php if($isView) { ?>
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="process_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Department Name</th>
                                                </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horizontal Form -->
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <?php if($isAdd || $isEdit) { ?>
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form class="form-horizontal" id="process_master_form" action="<?= base_url('master/save_process_master') ?>" method="post"  novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($process_master_data->process_id) && !empty($process_master_data->process_id)) { ?>
                                                <input type="hidden" name="process_id" class="process_id" value="<?= $process_master_data->process_id ?>">
                                            <?php } ?>
                                            <label for="process_name">Department Name<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="process_name" id="process_name" autofocus class="form-control" value="<?= (isset($process_master_data->process_name)) ? $process_master_data->process_name : ''; ?>"><br />

                                            <div class="clrearfix"></div>
                                            <button class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($process_master_data->process_id) ? '' : $btn_disable;?>><?= isset($process_master_data->process_id) ? 'Update' : 'Save' ?></button>
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

        table = $('#process_master_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('master/process_master_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },

        });

        $(document).on('submit', '#process_master_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#process_name").val()) == '') {
                show_notify('Please Enter Process Name.', false);
                $("#process_name").focus();
                return false;
            }

            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_process_master') ?>",
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
                        show_notify('Process Added Successfully!', true);
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/process_master') ?>";
                    }
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    return false;
                },
            });
            return false;
        });
        $(document).on("click", ".delete_button", function () {
            var value = confirm('Are you sure delete this records?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=process_id&table_name=process_master',
                    success: function (data) {
                        table.draw();
                        show_notify('Process Deleted Successfully!', true);
                    }
                });
            }
        });

    });
</script>
