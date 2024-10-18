<?php // $this->load->view('success_false_notify');       ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Attendance Report
            <?php
            $isAdd = $this->app_model->have_access_role(HR_ATTENDANCE_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?= base_url('hr_attendance'); ?>" class="btn btn-primary pull-right btn-sm">Add Attendance</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="box box-primary">
                                        <div class="box-body">
                                            <div class="row">
                                                <form action="javascript:void(0);" method="post" id="filter_form">
                                                    <div class="col-md-2">
                                                        <label for="account_id">Select User</label>
                                                        <select class="select2 form-control" name="account_id" id="account_id"></select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Date</label>
                                                        <input type="text" class="form-control input-datepicker" name="date" id="datepicker1" value="<?=date('d-m-Y')?>">
                                                        <div class="clearfix"></div><br />
                                                    </div>
                                                    <div class="col-md-2">
                                                        <br/>
                                                        <label for="">Only Entry by System ?</label>
                                                        <input type="checkbox" name="is_cron_entry" id="is_cron_entry" style="width: 20px; height: 20px;">
                                                        <div class="clearfix"></div><br />
                                                    </div>
                                                    <div class="col-md-2">
                                                        <br/>
                                                        <button type="submit" class="btn btn-primary btn-sm pull-left">Search</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="day_attendance_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>User Name</th>
                                                                    <th>User No.</th>
                                                                    <th>Date</th>
                                                                    <th>Time</th>
                                                                    <th>In/Out</th>
                                                                    <th>Is Out For Office?</th>
                                                                    <th>Entry by System</th>
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
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.select2').select2();
        initAjaxSelect2($("#account_id"), "<?= base_url('app/active_accounts_select2_source') ?>");

        $(document).on('submit','#filter_form',function(){
            if(($('#datepicker1').val() != '' || $('#datepicker1').val() != null) &&  ($('#account_id').val() != '' || $('#account_id').val() != null)){
                day_attendance_table.draw();
            }
        });

        day_attendance_table = $('#day_attendance_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ordering": false,
            "bFilter": false,
            "ajax": {
                "url": "<?php echo site_url('hr_attendance/attendance_report_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.date = $('#datepicker1').val();
                    d.user_id = $('#account_id').val();
                    if ($('#is_cron_entry').prop('checked')==true){
                        d.is_cron_entry = '1';
                    } else {
                        d.is_cron_entry = '0';
                    }
                },
            },
            "columnDefs": [{
                   "className": "dt-center",
                   "targets": [1,2,3,4,5,6],
            }],
        });

    });
</script>