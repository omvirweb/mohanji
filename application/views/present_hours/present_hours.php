<?php  $this->load->view('success_false_notify');       ?>
<style>
    #prsent_hour_table td:nth-child(1)
    {
        font-weight: bold;
    }
</style>
<div class="content-wrapper">  
    <form class="" action="" method="post" id="present_hour_form" novalidate enctype="multipart/form-data">
    <section class="content-header">
        <h1>
            Present Hours 
        </h1>
        <?php
            $isAdd = $this->app_model->have_access_role(PRESENT_HOURS_MODULE_ID, "add");
            $isView = $this->app_model->have_access_role(PRESENT_HOURS_MODULE_ID, "view");
        ?>
    </section>
    <?php if($isAdd) { ?>
        <div class="clearfix">
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
                                                <label for="department_id" class="col-sm-4 col-form-label">Select Department<span class="required-sign">&nbsp;*</span></label>
                                                <div class="col-sm-7">
                                                  <select name="department_id" id="department_id" class="form-control select2"></select><br/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <div class="form-group row">
                                                <label for="present_date" class="col-sm-4 col-form-label">Present Date<span class="required-sign">&nbsp;*</span></label>
                                                <div class="col-sm-7">
                                                  <input type="text" name="present_date" id="datepicker1" class="form-control input-datepicker" value="<?= isset($pr_hr_data->present_date) ? date('d-m-Y', strtotime($pr_hr_data->present_date)) : date('d-m-Y'); ?>"><br />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="users_from_department">
                                            <!--<div class="col-md-3 form-group">
                                                <div class="form-group row">
                                                    <input type="checkbox" name="">
                                                    <label></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <div class="form-group row">
                                                    <label for="in_time" class="col-sm-6 col-form-label">In Time<span class="required-sign">&nbsp;*</span></label>
                                                    <div class="col-sm-6">
                                                        <div class="input-group bootstrap-timepicker timepicker">
                                                            <input id="in_time" type="text" class="form-control input-small timepicker1" value="" required="">
                                                            <input type="hidden" name="in_time" type="text" value="" >
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <div class="form-group row">
                                                    <label for="out_time" class="col-sm-4 col-form-label">Out Time<span class="required-sign">&nbsp;*</span></label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group bootstrap-timepicker timepicker">
                                                            <input id="out_time" type="text" class="form-control input-small timepicker1" value="" required="">
                                                            <input type="hidden" name="out_time" type="text" value="" >
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <div class="form-group row">
                                                    <label for="out_time" class="col-sm-4 col-form-label">Present Hours</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" name="no_of_hours" id="hours" class="form-control" value="<?= (isset($pr_hr_data->no_of_hours)) ? $pr_hr_data->no_of_hours : '0'; ?>" readonly=""><br />
                                                    </div>
                                                </div>
                                            </div>-->
                                        </div>
                                        <!--<img src="<?php echo base_url();?>assets/image/present_hours.png" class="img-thumbnail" width="100%">-->

                                        <button type="submit" class="btn btn-primary pull-right module_save_btn btn-sm" ><?= isset($pr_hr_data->present_hour_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
    </form>
    <?php if($isView) { ?>
        <div class="clearfix">
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
                                                <label for="user_id" class="col-sm-4 col-form-label">Select Employee</label>
                                                <div class="col-sm-7">
                                                  <select id="user_id_select" class="form-control select2"></select><br/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <div class="form-group row">
                                                <label for="year" class="col-sm-4 col-form-label">Select Year</label>
                                                <div class="col-sm-7">
                                                    <input type="text" id="datepicker2" class="form-control" value="<?php echo date("Y"); ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="box-body table-responsive">
                                                <table id="prsent_hour_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Month</th>
                                                            <?php for($i=1; $i<=31; $i++){
                                                                $i = $i <= 9 ? '0'.$i : $i;
                                                                echo '<th>'.$i.'</th>';
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
        </div>
    <?php } ?>
</div>

<input type="hidden" id="clicked_date" value="" >
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Entry Details</h4>
            </div>
            <div class="modal-body">
                <table id="entry_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Hours</th>
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
<script>

    $(document).ready(function () {
        $(".select2").select2({
            width: "100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
        $("#datepicker2").datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        });

        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        <?php if (isset($pr_hr_data->department_id)) { ?>
                setSelect2Value($("#department_id"), "<?= base_url('app/set_user_worker_select2_val_by_id/' . $pr_hr_data->user_id) ?>");
        <?php } ?>
        initAjaxSelect2($("#user_id_select"), "<?= base_url('app/user_worker_select2_source') ?>");
        <?php if (isset($last_user_id)) { ?>
                setSelect2Value($("#user_id_select"), "<?= base_url('app/set_user_worker_select2_val_by_id/' . $last_user_id) ?>");
        <?php } ?>
            $(document).on('change','#department_id', function(){
               var department_id = $(this).val();
               if(department_id != '' || department_id != null) {
                   $.ajax({
                        url: "<?= base_url('present_hours/get_users_from_department') ?>",
                        type: "POST",
                        data: {'department_id' : department_id},
                        success: function (response) {
                            var json = $.parseJSON(response);
                            if (json['error'] == 'error') {
                                show_notify('No user available for this department!!.', false);
                                $('.users_from_department').html('');
                            } else if (json['success'] == 'success') {
                                $('.users_from_department').html(json['html']);
                                $('.timepicker1').timepicker({});
                            }
                        },
                    });
               }
            });
        //timeConversion();

        table = $('#prsent_hour_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": "100%",
            "bFilter": false,
            "bInfo": false,
            "paging": false,
            "ordering":false,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('present_hours/present_hour_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.year = $('#datepicker2').val();
                    d.user_id = $('#user_id_select').val();
                },
            },
        });

        $(document).on('change', '.in_time, .out_time', function(){
            var id = $(this).data('id');
            var in_time = $('#in_time_'+id).val();
            var out_time = $('#out_time_'+id).val();
            timeConversion(in_time, out_time, id);
            
            var startTime = moment(in_time, 'hh:mm a');
            var endTime = moment(out_time, 'hh:mm a');

            var totalHours = (endTime.diff(startTime, 'hours'));
            var totalMinutes = endTime.diff(startTime, 'minutes');
            var clearMinutes = totalMinutes % 60;
            //totalHours = Math.abs(totalHours);
            //clearMinutes = Math.abs(clearMinutes);
            if(parseFloat(totalHours) < 0 || parseFloat(clearMinutes) < 0){
                $("#out_time_"+id).val('');
                show_notify('Out time must be grater than in time.', false);
                $("#out_time_"+id).focus();
                return false;
            }
            
            if(clearMinutes == 0){
                $('#hours_'+id).val(totalHours);
            } else {
                $('#hours_'+id).val(totalHours+'.'+clearMinutes);
            }
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#present_hour_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#present_hour_form', function () {
            $(window).unbind('beforeunload');
                var department_id = $("#department_id").val();
                if ($.trim($("#department_id").val()) == '') {
                    show_notify('Please Select Employee.', false);
                    $("#user_id").select2('open');
                    return false;
                }

                if ($.trim($("#datepicker1").val()) == '') {
                    show_notify('Please Select Present Date.', false);
                    $("#datepicker1").focus();
                    return false;
                }

                if ($("input.selected_users:checked").length == 0) {
                    show_notify('Please select at least one User.', false);
                    return false;
                }
                /**if ($.trim($("#in_time").val()) == '') {
                    show_notify('Please Enter In Time.', false);
                    $("#in_time").focus();
                    return false;
                }

                if ($.trim($("#out_time").val()) == '') {
                    show_notify('Please Enter Out Time.', false);
                    $("#out_time").focus();
                    return false;
                }**/
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('present_hours/save_present_hours') ?>",
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
                        window.location.href = "<?php echo base_url('present_hours/present_hours') ?>";
                        show_notify('Present Hours Added Successfully!', true);
                    }else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('present_hours/present_hours') ?>";
                    }
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    return false;
                },
            });
            return false;
        });

        $(document).on('change', '#datepicker2, #user_id_select', function(){
            table.draw();
        });

        entry_table = $('#entry_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ordering": false,
            "ajax": {
                "url": "<?php echo site_url('present_hours/entry_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.date = $('#clicked_date').val();
                    d.user_id = $('#user_id_select').val();
                },
            },
            "columnDefs": [{
                   "className": "dt-right",
                   "targets": [2],
            }],
        });

        $(document).on("click", ".show_time", function () {
            $('#clicked_date').val($(this).data('date'));
            entry_table.draw();
            $('#myModal').modal('show');
        });

        $(document).on('click','#all', function(){
            $('.selected_users').not(this).prop('checked', this.checked);
        });
    });

    function timeConversion(in_time, out_time, id) {
        var it = moment(in_time, ["h:mm A"]).format("HH:mm");
        $('input[name="in_time_'+id+'"]').val(it);
        var ot = moment(out_time, ["h:mm A"]).format("HH:mm");
        $('input[name="out_time_'+id+'"]').val(ot);
    }
</script>