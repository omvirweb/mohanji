<?php $this->load->view('success_false_notify'); 
    $user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type'];
?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Apply Leave
            <?php $isEdit = $this->app_model->have_access_role(APPLY_LEAVE_ID, "edit");
            $isView = $this->app_model->have_access_role(APPLY_LEAVE_ID, "view");
            $isAdd = $this->app_model->have_access_role(APPLY_LEAVE_ID, "add"); ?>
            <?php if (isset($apply_leave_data->apply_leave_id) && !empty($apply_leave_data->apply_leave_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
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
                                        <table id="apply_leave_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 100px;">Action</th>                                              
                                                    <th>Name</th>                                                    
                                                    <th>From Date</th>                                                    
                                                    <th>To Date</th>                                                    
                                                    <th>Days</th>                                                                                                        
                                                    <th>Reason</th>                                                    
                                                    <th>Status</th>
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
                <?php if($isAdd || $isEdit) { ?>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form class="form-horizontal" id="apply_leave_form"  action="<?= base_url('master/save_apply_leave') ?>" method="post" novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($apply_leave_data->apply_leave_id) && !empty($apply_leave_data->apply_leave_id)) { ?>
                                                <input type="hidden" name="apply_leave_id" class="apply_leave_id" value="<?= $apply_leave_data->apply_leave_id ?>">
                                            <?php } ?>
                                            <label for="user_id">Name<span class="required-sign">&nbsp;*</span></label>
                                            <?php if($user_type == USER_TYPE_ADMIN) {?>
                                                <select name="user_id" id="user_id" class="form-control select2"></select><br/>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" value="<?= (isset($user_name)) ? $user_name : ''; ?>" readonly=""><br />
                                                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>"/>
                                            <?php } ?>
                                            <!--<label for="email">Email</label>
                                            <input type="text" name="email" id="email" class="form-control" value="<?= (isset($category_data->category_name)) ? $apply_leave_data->category_name : ''; ?>"><br />-->
                                            <label for="from_date" style="margin-top: 15px;">From Date</label>
                                            <input type="text" name="from_date" id="datepicker1" class="form-control input-datepicker" value="<?= (isset($apply_leave_data->from_date)) ? date('d-m-Y', strtotime($apply_leave_data->from_date)) : date('d-m-Y'); ?>"><br/>
                                            <label for="to_date">To Date</label>
                                            <input type="text" name="to_date" id="datepicker2" class="form-control input-datepicker" value="<?= (isset($apply_leave_data->to_date)) ? date('d-m-Y', strtotime($apply_leave_data->to_date)) : date('d-m-Y'); ?>"><br/>
                                            <label for="day">Days</label>
                                            <input type="text" name="no_of_days" id="days" class="form-control" value="<?= (isset($apply_leave_data->no_of_days)) ? $apply_leave_data->no_of_days : '1'; ?>" readonly=""><br />
                                            <label for="reason">Reason</label>
                                            <textarea name="reason" id="reason" class="form-control"><?= (isset($apply_leave_data->reason)) ? $apply_leave_data->reason : ''; ?></textarea><br />
                                            <div class="clrearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($apply_leave_data->apply_leave_id) ? '' : $btn_disable;?>><?= isset($apply_leave_data->apply_leave_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button><br/>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($apply_leave_data->apply_leave_id) && !empty($apply_leave_data->apply_leave_id)) { ?>
                                    <div class="created_updated_info" style="margin: 10px;">
                                        Created by : <?= isset($apply_leave_data->created_by_name) ? $apply_leave_data->created_by_name : '' ?>
                                        @ <?= isset($apply_leave_data->created_at) ? date ('d-m-Y h:i:s', strtotime($apply_leave_data->created_at)) : '' ?><br/>
                                        <?php if(isset($apply_leave_data->updated_by_name) && $apply_leave_data->updated_by_name) { ?>
                                            Updated by : <?= isset($apply_leave_data->updated_by_name) ? $apply_leave_data->updated_by_name : '' ?>
                                            @ <?= isset($apply_leave_data->updated_at) ? date('d-m-Y h:i:s', strtotime($apply_leave_data->updated_at)) :'' ; ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
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

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(".select2").select2({
            width: "100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
        initAjaxSelect2($("#user_id"), "<?= base_url('app/user_worker_select2_source') ?>");
    <?php if (isset($apply_leave_data->user_id)) { ?>
            setSelect2Value($("#user_id"), "<?= base_url('app/set_user_worker_select2_val_by_id/' . $apply_leave_data->user_id) ?>");
    <?php } ?>
    
        table = $('#apply_leave_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('apply_leave/apply_leave_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [4],
            }],
        });

        var dateToday = new Date(); 

        $("#datepicker1").datepicker({
            format: 'dd-mm-yyyy',
            //startDate: dateToday,
            autoclose: true
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#datepicker2').datepicker('setStartDate', minDate);
            $('#datepicker2').datepicker('setDate', minDate);
            calculate();
        });

        $("#datepicker2").datepicker({
            format: 'dd-mm-yyyy',
            //startDate: dateToday,
            autoclose: true
        }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#datepicker1').datepicker('setEndDate', minDate);
                calculate();
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#apply_leave_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#apply_leave_form', function () {
            $(window).unbind('beforeunload');
            <?php if($user_type == USER_TYPE_ADMIN) {?>
                if ($.trim($("#user_id").val()) == '') {
                    show_notify('Please Select Name.', false);
                    $("#user_id").select2('open');
                    return false;
                }
            <?php } ?>
                
          
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('apply_leave/save_apply_leave') ?>",
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
                        $('textarea').val('');
                        table.draw();
                        show_notify('Leave Applied Successfully!', true);
                    }else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('apply_leave/apply_leave') ?>";
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
                    data: 'id_name=apply_leave_id&table_name=hr_apply_leave',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('Something Went Wrong!!.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Leave Deleted Successfully!', true);
                        }
                    }
                });
            }
        });

        $(document).on("click", ".approve_leave_btn", function () {
            var status = $(this).data('status');
            if(status == '1'){
                var msg = 'Are you sure you want to approve this leave?';
                var notify_msg = 'Leave Approved Successfully';
            }
            if(status == '2') {
                var msg = 'Are you sure you want to disapprove this leave?';
                var notify_msg = 'Leave Disapproved Successfully';
            }
            if (confirm(msg)) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: {status : status},
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('Something Went Wrong!!.', false);
                        } else if (json['success'] == 'Updated') {
                            table.draw();
                            show_notify(notify_msg, true);
                        }
                    }
                });
            }
        });
    });
    function calculate() {
        var d1 = $('#datepicker1').datepicker('getDate');
        var d2 = $('#datepicker2').datepicker('getDate');
        var oneDay = 24*60*60*1000;
        var diff = 0;
        if (d1 && d2) {
          diff = Math.round(Math.abs((d2.getTime() - d1.getTime())/(oneDay)));
        }
        diff = parseInt(diff) + parseInt(1);
        $('#days').val(diff);
    }
</script>
