<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">   
    <section class="content-header">
        <h1> Reminder List </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">                        
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <label>Account</label>
                                    <select name="account_id" id="account_id" class="form-control select2">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Upto Date</label>
                                    <input type="text" name="reminder_date" id="datepicker2" class="form-control" value="<?php echo date('d-m-Y', strtotime("+1 month"));?>">
                                </div>
                                <div class="col-md-1">
                                    <br/>
                                    <button name="search" id="search" class="btn btn-primary pull-right">Search</button>
                                </div>
                                <div class="clearfix"></div><br />
                                <div class="col-md-1">
                                    <button name="" id="delete_multiple_rem" class="btn btn-primary pull-left">Delete Checked</button>
                                </div>
                                <div class="col-md-12">
                                    <table id="reminder_table" class="table row-border table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" name="" id="checkbox_all" class="" style="width: 20px; height: 20px;">  &nbsp;Action</th>
                                                <th>Date</th>
                                                <th class="">Party name</th>
                                                <th class="text-right">Credit Gold</th>
                                                <th class="text-right">Debit Gold</th>
                                                <th class="text-right">Credit Silver</th>
                                                <th class="text-right">Debit Silver</th>
                                                <th class="text-right">Credit Amt</th>
                                                <th class="text-right">Debit Amt</th>
                                                <th class="">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
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
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('.select2').select2();
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_with_number_select2_source/')?>");
        
        
        
        table = $('#reminder_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports/reminder_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.reminder_date = $('#datepicker2').val();
                    d.account_id = $('#account_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {"className": "text-right", "targets": [3,4,5,6,7,8]},
                { "targets": 0, "orderable": false } 
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };
                amount_total = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                $(api.column(3).footer()).html(amount_total.toFixed(2));
                
                amount_total2 = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                $(api.column(4).footer()).html(amount_total2.toFixed(2));
                
                amount_total3 = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                $(api.column(5).footer()).html(amount_total3.toFixed(2));
                
                amount_total4 = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                $(api.column(6).footer()).html(amount_total4.toFixed(2));
                
                amount_total5 = api
                        .column(7)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                $(api.column(7).footer()).html(amount_total5.toFixed(2));
                
                amount_total6 = api
                        .column(8)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                $(api.column(8).footer()).html(amount_total6.toFixed(2));
            },
        });
        
        $(document).on('click', '#search', function (){
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on("click", ".delete_button", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (data) {
                        table.draw();
                        show_notify('Reminder Deleted Successfully!', true);
                    }
                });
            }
        });
        
        $('#checkbox_all').click(function () {
            $('.delete_multiple').not(this).prop('checked', this.checked);
        });
        
        reminder_ids = [];
        $(document).on("click", "#delete_multiple_rem", function () {
            $('input[name="delete_multiple[]"]').map(function () {
                if ($(this).prop('checked')) {
                    reminder_ids.push($(this).data('reminder_id'));
                }
            }).get();
            if(reminder_ids != ''){
                var reminder_ids1 = JSON.stringify(reminder_ids);
                $("#ajax-loader").show();
                $.ajax({
                    url: '<?php echo base_url('reports/delete_multiple_reminder'); ?>',
                    type: 'POST',
                    data : {reminder_ids :reminder_ids1},
                    async: false,
                    success : function(response){
                        var json = $.parseJSON(response);
                        reminder_ids = [];
                        $("#ajax-loader").hide();
                        table.draw();
                        if (json['success'] == 'deleted') {
                            show_notify('Reminder  Deleted Successfully!', true);
                        }
                    }
                });
            }
            else {
                show_notify('Please Select At Least One Reminder To Delete !', false);
            }
        });
        
    });
    
</script>


