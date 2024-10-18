<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            State
            <?php $isEdit = $this->app_model->have_access_role(STATE_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(STATE_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(STATE_MODULE_ID, "add"); ?>
            <?php if (isset($state_data->state_id) && !empty($state_data->state_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/state') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add State </a>
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
                                        <table id="state_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>State</th>                                                    
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

                <!-- Horizontal Form -->
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-body">
                            <?php if($isAdd || $isEdit) { ?>
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form class="form-horizontal" id="state_form" action="<?= base_url('master/save_state') ?>" method="post" novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($state_data->state_id) && !empty($state_data->state_id)) { ?>
                                                <input type="hidden" name="state_id" class="state_id" value="<?= $state_data->state_id ?>">
                                            <?php } ?>
                                            <label for="state">State<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="state_name" id="state" class="form-control" autofocus value="<?= (isset($state_data->state_name)) ? $state_data->state_name : ''; ?>"><br />
                                            <div class="clrearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($state_data->state_id) ? '' : $btn_disable;?>><?= isset($state_data->state_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                                            <?php if (isset($state_data->state_id) && !empty($state_data->state_id)) { ?>
                                            <div class="created_updated_info">
                                                    Created by : <?php echo (isset($state_data->created_by_name)) ? $state_data->created_by_name : ''; ?>
                                                    @ <?php echo (isset($state_data->created_at)) ? date('d-m-Y h:i A', strtotime($state_data->created_at)) : ''; ?> <br/>
                                                    Updated by : <?php echo (isset($state_data->updated_by_name)) ? $state_data->updated_by_name : ''; ?>
                                                    @ <?php echo (isset($state_data->updated_at)) ?date('d-m-Y h:i A', strtotime($state_data->updated_at)) : '' ?>
                                            </div>
                                            <?php } ?>
                                        </form>
                                    </div>
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
<script>
    $(document).ready(function () {
        table = $('#state_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('master/state_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },

        });
    
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#state_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#state_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#state").val()) == '') {
                show_notify('Please Enter State. ', false);
                $("#state").focus();
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_state') ?>",
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
                        $('#state_id').find('option').remove().end().val('whatever');
                        table.draw();
                        show_notify('State Added Successfully!', true);
                    }else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/State') ?>";
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
                    data: 'id_name=state_id&table_name=state',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this State. This State has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('State Deleted Successfully!', true);
                        }
                        
                    }
                });
            }
        });

    });
</script>
