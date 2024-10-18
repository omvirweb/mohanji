<?php // $this->load->view('success_false_notify');       ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Department Attendance</h1>
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
                                                        <label for="account_id">Department</label>
                                                        <select class="select form-control" name="department_id" id="department_id">
                                                            <option value="">Select Department</option>
                                                            <?php
                                                                if(!empty($department)){
                                                                    foreach($department as $row){
                                                            ?>
                                                                        <option value="<?= $row->account_id;?>"><?= $row->account_name; ?></option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="account_id">Date</label>
                                                        <input type="text" class="form-control input-datepicker" name="date" id="datepicker1" value="<?=date('d-m-Y')?>">
                                                        <div class="clearfix"></div><br />
                                                    </div>
                                                    <div class="col-md-2">
                                                        <br/>
                                                        <button type="submit" class="btn btn-primary btn-sm pull-left">Filter</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="table-responsive">
                                                        <table class="row-border table-bordered table-striped" id="present_datatable" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr No.</th>
                                                                    <th>Present</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="table-responsive">
                                                        <table class="table row-border table-bordered table-striped" id="absent_datatable" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr No.</th>
                                                                    <th>Absent</th>
                                                                </tr>
                                                            </thead>
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
<input type="hidden" id="clicked_date" value="" >
<input type="hidden" id="list_account_id" value="-1" >
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
<script type="text/javascript">
    var present_table = absent_datatable;
    $(document).ready(function(){

        $('#department_id').select2();

        var department_id;
        var date;

        $(document).on('submit','#filter_form',function(){
            department_id = $('#department_id').val();
            date = $('#datepicker1').val();
            present_table.draw();
            absent_datatable.draw();
        });

        present_table = $('#present_datatable').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('department_attendance/present_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.department_id = department_id;
                    d.date = date;
                }
            },
            "initComplete":function(){
                if(present_table.data().count()>0){
                    $('#present_datatable tbody tr').css('background','green');
                    $('#present_datatable tbody tr').css('color','#ffffff');
                }
            },
            "drawCallback":function(){
                if(present_table.data().count()>0){
                    $('#present_datatable tbody tr').css('background','green');
                    $('#present_datatable tbody tr').css('color','#ffffff');
                }
            }
        });

        absent_datatable = $('#absent_datatable').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('department_attendance/absent_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.department_id = department_id;
                    d.date = date;
                }
            },
            "initComplete":function(){
                if(absent_datatable.data().count()>0){
                    $('#absent_datatable tbody tr').css('background','red');
                    $('#absent_datatable tbody tr').css('color','#ffffff');
                }
            },
            "drawCallback":function(){
                if(absent_datatable.data().count()>0){
                    $('#absent_datatable tbody tr').css('background','red');
                    $('#absent_datatable tbody tr').css('color','#ffffff');
                }
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
            $('#clicked_date').val($('#datepicker1').val());
            $('#list_account_id').val($(this).data('list_account_id'));
            day_attendance_table.draw();
            $('#day_attendance_detail').modal('show');
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
                        present_table.draw();
                        absent_datatable.draw();
                        show_notify('Deleted Successfully!', true);
                    }
                });
            }
        });


    });
</script>