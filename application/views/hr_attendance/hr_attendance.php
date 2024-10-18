<?php $this->load->view('success_false_notify'); ?>
<style>
    #hr_attendance_table td:nth-child(1)
    {
        font-weight: bold;
    }
</style>
<div class="content-wrapper" id="body-content">
    <form class="" action="" method="post" id="attendance_form" novalidate enctype="multipart/form-data">
        <section class="content-header">
            <h1>
                Attendance 
                <?php
                $isAdd = $this->app_model->have_access_role(HR_ATTENDANCE_MODULE_ID, "add");
                $isView = $this->app_model->have_access_role(HR_ATTENDANCE_MODULE_ID, "view");
                $isDateChange = $this->app_model->have_access_role(HR_ATTENDANCE_MODULE_ID, "allow to update date");
                $isTimeChange = $this->app_model->have_access_role(HR_ATTENDANCE_MODULE_ID, "allow to update time");
                ?>
                <?php if($isAdd){ ?>
                    <a href="<?= base_url('hr_attendance/attendance_report'); ?>" class="btn btn-primary pull-right btn-sm">Attendance Report</a>
                <?php } ?>
            </h1>
            
        </section>
        <?php if($isAdd) { ?>
            <div class="row">
                <div style="margin: 15px;">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_id" class="col-sm-4 col-form-label">Select User<span class="required-sign">&nbsp;*</span></label>
                                            <div class="col-sm-5">
                                                <select id="account_id" name="account_id" class="form-control select2"></select>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <div class="form-group">
                                            <label for="attendance_date" class="col-sm-4 col-form-label">Select Date<span class="required-sign">&nbsp;*</span></label>
                                            <div class="col-sm-5">
                                                <?php if($isDateChange) { ?>
                                                    <input type="text" name="attendance_date" id="attendance_date" class="form-control" value="<?=date('d-m-Y')?>" />
                                                <?php } else { ?> 
                                                    <input type="text" class="form-control attendance_date" value="<?=date('d-m-Y')?>" disabled />
                                                    <input type="hidden" name="attendance_date" id="attendance_date" class="form-control" value="<?=date('d-m-Y')?>" />
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <br/><br/>
                                        
                                        <div class="form-group">
                                            <label for="attendance_time" class="col-sm-4 col-form-label">Select Time<span class="required-sign">&nbsp;*</span></label>
                                            <div class="col-sm-5">
                                                <?php if($isTimeChange) { ?>
                                                    <div class="input-group bootstrap-timepicker timepicker">
                                                        <input type="text" name="attendance_time" id="attendance_time" class="form-control out_time input-small" value="<?=date('h:i A')?>" />
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                    </div>
                                                <?php } else { ?> 
                                                    <input type="text" class="form-control attendance_time" value="<?=date('h:i A')?>" disabled />
                                                    <input type="hidden" name="attendance_time" id="attendance_time" class="form-control" value="<?=date('h:i A')?>" />
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <br/><br/>
                                        
                                        <div class="form-group">
                                            <label for="is_in_out" class="col-sm-4 col-form-label">In/Out<span class="required-sign">&nbsp;*</span></label>
                                            <div class="col-sm-5">
                                                <label for="is_in_out_in">
                                                <input type="radio" name="is_in_out" id="is_in_out_in" value="1" class="is_in_out" /> In
                                                </label>
                                                &nbsp; &nbsp;
                                                
                                                <label for="is_in_out_out">
                                                <input type="radio" name="is_in_out" id="is_in_out_out" value="2" class="is_in_out" />Out
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <br/><br/>

                                        <div class="form-group">
                                            <label for="is_out_for_office" class="col-sm-4 col-form-label">
                                                Out For Office? &nbsp; &nbsp;
                                                <input type="checkbox" name="is_out_for_office" id="is_out_for_office">
                                            </label>
                                            <div class="col-sm-5">
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <img id="user_default_image" src="<?=base_url("/assets/image/profile-pic.jpg")?>" height="150" width="150">
                                    </div>
                                    <div class="clearfix"></div>
                                    
                                    <div class="col-md-12 form-group">
                                        <button type="submit" class="btn btn-primary pull-right module_save_btn btn-sm" ><?= isset($pr_hr_data->present_hour_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if($isView) { ?>
            <div class="row">
                <div style="margin: 15px;">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <div class="col-md-12 form-group">
                                        <div class="col-md-6 form-group">
                                            <div class="form-group row">
                                                <label for="list_account_id" class="col-sm-4 col-form-label">Select User<span class="required-sign">&nbsp;*</span></label>
                                                <div class="col-sm-5">
                                                  <select id="list_account_id" class="form-control select2"></select>
                                                  <br/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <div class="form-group row">
                                                <label for="year" class="col-sm-4 col-form-label">Select Year<span class="required-sign">&nbsp;*</span></label>
                                                <div class="col-sm-5">
                                                    <input type="text" id="year_datepicker" class="form-control" value="<?php echo date("Y"); ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="box-body table-responsive">
                                                <table id="hr_attendance_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Month</th>
                                                            <?php for($i=1; $i<=32; $i++){
                                                                $i = $i <= 9 ? '0'.$i : $i;
                                                                if($i==32){
                                                                    echo '<th>Total Hours</th>';
                                                                } else {
                                                                    echo '<th>'.$i.'</th>';
                                                                }
                                                            } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </form>
</div>

<input type="hidden" id="clicked_date" value="" >
<div id="day_attendance_detail" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Entry Details</h4>
            </div>
            <div class="modal-body">
                <table id="day_attendance_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>In/Out</th>
                            <th>Is Out For Office?</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/dist/js/jquery.validate.min.js') ?>"></script>
<script>
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    $(document).ready(function(){
        initAjaxSelect2($("#account_id"), "<?= base_url('app/active_accounts_select2_source') ?>");
        initAjaxSelect2($("#list_account_id"), "<?= base_url('app/active_accounts_select2_source') ?>");

        <?php if($isDateChange) { ?>
        $('#attendance_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            endDate: "today",
            maxDate: 0,
        })
        <?php } ?>

        <?php if($isTimeChange) { ?>
        $('#attendance_time').timepicker();
        <?php } ?>
        

        setInterval(function(){
            $.ajax({
                url: "<?= base_url('hr_attendance/get_date_time') ?>",
                type: "GET",
                dataType:'json',
                success: function (res) {
                    <?php if($isDateChange) { ?>
                        $('#attendance_date').datepicker("setDate",res.attendance_date);
                    <?php } else { ?>
                        $('#attendance_date').val(res.attendance_date);
                        $('.attendance_date').val(res.attendance_date);    
                    <?php } ?>

                    <?php if($isTimeChange) { ?>
                        $('#attendance_time').timepicker("setTime",res.attendance_time);
                    <?php } else { ?>
                        $('#attendance_time').val(res.attendance_time);
                        $('.attendance_time').val(res.attendance_time);
                    <?php } ?>
                },
            });
        },60000);

        $("#year_datepicker").datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        });

        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#attendance_form").submit();
                return false;
            }
        });

        hr_attendance_table = $('#hr_attendance_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": "100%",
            "bFilter": false,
            "bInfo": false,
            "paging": false,
            "ordering":false,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('hr_attendance/attendance_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.year = $('#year_datepicker').val();
                    d.account_id = $('#list_account_id').val();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [32],
            }],
        });

        $(document).on('change', '#year_datepicker, #list_account_id', function(){
            if(($('#year_datepicker').val() != '' || $('#year_datepicker').val() != null) &&  ($('#list_account_id').val() != '' || $('#list_account_id').val() != null)){
                hr_attendance_table.draw();
            }
        });

        $(document).on('change', '#account_id', function (e) {
            var account_id = $(this).val();
            if(account_id != '') {
                $.ajax({
                    url: "<?= base_url('hr_attendance/get_user_default_image_url/') ?>" + account_id,
                    type: "GET",
                    dataType:'json',
                    success: function (res) {
                        console.log(res);
                        if(res.status == 'success') {
                            $("#user_default_image").attr('src',res.default_image_url);
                        }
                    },
                });
                
            }
        });

        day_attendance_table = $('#day_attendance_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ordering": false,
            "bFilter": false,
            "ajax": {
                "url": "<?php echo site_url('hr_attendance/day_attendance_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.date = $('#clicked_date').val();
                    d.user_id = $('#list_account_id').val();
                },
            },
            "columnDefs": [{
                   "className": "dt-center",
                   "targets": [0,1,2,3,4],
            }],
        });

        $(document).on("click", ".show_time", function () {
            $('#clicked_date').val($(this).data('date'));
            day_attendance_table.draw();
            $('#day_attendance_detail').modal('show');
        });

        $(document).on('submit', '#attendance_form', function (e) {
            e.preventDefault();
            dateString = $('#attendance_date').val();
            var month = dateString.substring(3,5);
            var date = dateString.substring(0,2);
            var year = dateString.substr(dateString.length - 4);
            var startDate = new Date(year, month-1, date);
            var endDate = new Date();
            if (startDate > endDate) {
              show_notify('Date can not greater than today !', false);
               return false;
            } 
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select User', false);
                return false;
            }
            if ($.trim($("#attendance_date").val()) == '') {
                show_notify('Please Enter Date', false);
                return false;
            }

            if ($("input.is_in_out:checked").length == 0) {
                show_notify('Please Select In/Out.', false);
                return false;
            }

            $('.module_save_btn').attr('disabled', 'disabled');

            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('hr_attendance/save_hr_attendance') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                dataType: 'json',
                success: function (response) {
                    if(response.status == "error") {
                        show_notify(response.error_message, false);
                    } else {
                        $('#attendance_form')[0].reset();
                        $("#account_id").val(null).trigger("change");
                        $('#attendance_date').datepicker("setDate","<?=date('d-m-Y')?>");
                        hr_attendance_table.draw();
                        show_notify('Attendance Added Successfully!', true);
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
                    data: 'id_name=attendance_id&table_name=hr_attendance',
                    success: function (data) {
                        day_attendance_table.draw();
                        hr_attendance_table.draw();
                        show_notify('Deleted Successfully!', true);
                    }
                });
            }
        });
    });
    
    $(function () {
        $('#attendance_form').validate({
           rules: {
                attendance_date: {
                    date: true
                }
            }
        });
    });
    
</script>