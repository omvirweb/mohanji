<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('worker/save_worker_entry') ?>"  method="post" id="save_worker_entry" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($worker_data->worker_entry_id) && !empty($worker_data->worker_entry_id)) { ?>
            <input type="hidden" name="worker_entry_id" class="worker_entry_id" value="<?= $worker_data->worker_entry_id ?>">
        <?php } ?>
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <h1>
                Add Worker
                <?php $isEdit = $this->app_model->have_access_role(WORKER_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(WORKER_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(WORKER_MODULE_ID, "add"); ?>
                <?php if (isset($worker_data->worker_entry_id) && !empty($worker_data->worker_entry_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($worker_data->worker_entry_id) ? '' : $btn_disable;?>><?= isset($worker_data->worker_entry_id) ? 'Update' : 'Save' ?></button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('worker/worker_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Worker List</a>
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
                                    <div class="col-md-6">
                                        <label for="person_name">Name</label>
                                        <input type="text" name="person_name" id="person_name" class="form-control" value="<?= (isset($worker_data->person_name)) ? $worker_data->person_name : ''; ?>"><br />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="process_id">Department<span class="required-sign">&nbsp;*</span></label>
                                        <select name="process_id" id="process_id" class="form-control select2" ></select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <label for="salary">Salary</label>
                                        <input type="text" name="salary" id="salary" class="form-control num_only" value="<?= (isset($worker_data->salary)) ? $worker_data->salary : ''; ?>"><br />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="worker_type_id">Worker Type</label>
                                        <select name="worker_type_id" id="worker_type_id" class="form-control select2" >
                                            <option value="1">Worker</option>
                                            <option value="2">Sales</option>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6"  id="filediv">
                                        <label for="">Upload Document</label>
                                        <input type="file" name="image_1" id="image_upload" class="from-control"><br/>
                                        <input type="button" id="add_more" class="upload" value="Add More Files"/>
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
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    var abc = 0;
    $(document).ready(function () {
        initAjaxSelect2($("#process_id"), "<?= base_url('app/process_master_select2_source') ?>");
<?php if (isset($worker_data->process_id)) { ?>
            setSelect2Value($("#process_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $worker_data->process_id) ?>");
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
        

        $(document).on('submit', '#save_worker_entry', function () {
        $(window).unbind('beforeunload');
            if ($.trim($("#process_id").val()) == '') {
                show_notify('Please Select Department Name.', false);
                $("#process_id").select2('open');
                return false;
            }

            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('worker/save_worker_entry') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('worker/worker_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('worker/worker_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });



    });

</script>
