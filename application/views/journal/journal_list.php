<?php $this->load->view('success_false_notify'); ?>
<?php $this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type']; ?>
<div class="content-wrapper">
    <style>
        .dataTables_scroll {
            overflow:auto;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Journal List
            <?php
            $isView = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?=base_url('journal/add') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Journal Entry</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <?php if($isView) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <div class="col-md-3">
                                            <label>Department</label>
                                            <select name="department_id" class="form-control department_id" id="department_id"></select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>From Date</label>
                                            <label style="font-size: 10px">Everything From Start <input type="checkbox" name="everything_from_start" id="everything_from_start" ></label>
                                            <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y", strtotime("first day of this month"));?>">
                                            <!--<input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="">-->
                                        </div>
                                        <div class="col-md-3">
                                            <label>To Date</label>
                                            <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y');?>">
                                            <!--<input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="">-->
                                        </div>
                                        <div class="col-md-2">
                                            <label>Audit Status</label>
                                            <select name="audit_status_filter" id="audit_status_filter" class="form-control select2">
                                                <option value="all">All</option>
                                                <option value="<?php echo AUDIT_STATUS_PENDING; ?>">Pending</option>
                                                <option value="<?php echo AUDIT_STATUS_AUDITED; ?>">Audited</option>
                                                <option value="<?php echo AUDIT_STATUS_SUSPECTED; ?>">Suspected</option>
                                            </select>
                                        </div>
                                        <br/><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                        <div class="clearfix"></div>
                                        <br/>
                                        <table id="lot_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th style="width: 100px;">Reference No</th>
                                                    <th>Department Name</th>
                                                    <th>Journal Date</th>
                                                    <th style="width: 250px;">Accounts</th>
                                                    <th style="width: 250px;">Particular</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total : </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th style="align: right"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
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
<input type="hidden" id="clicked_item_id" value="-1" >
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:80% !important;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Journal Details</h4>
            </div>
            <div class="modal-body">
                <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Naam (Dr)</th>
                            <th>Jama (Cr)</th>
                            <th>Narration</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>Total : </th>
                            <th style="align: right"></th>
                            <th style="align: right"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="audit_status_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Audit Status</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="audit_status_pay_rec_id" id="audit_status_pay_rec_id">
                <input type="hidden" name="audit_status_value" id="audit_status_value">
                <input type="radio" name="audit_status" id="audit_status_<?php echo AUDIT_STATUS_PENDING; ?>" value="<?php echo AUDIT_STATUS_PENDING; ?>" >
                <label for="audit_status_<?php echo AUDIT_STATUS_PENDING; ?>">Pending</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="audit_status" id="audit_status_<?php echo AUDIT_STATUS_AUDITED; ?>" value="<?php echo AUDIT_STATUS_AUDITED; ?>" >
                <label for="audit_status_<?php echo AUDIT_STATUS_AUDITED; ?>">Audited</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="audit_status" id="audit_status_<?php echo AUDIT_STATUS_SUSPECTED; ?>" value="<?php echo AUDIT_STATUS_SUSPECTED; ?>" >
                <label for="audit_status_<?php echo AUDIT_STATUS_SUSPECTED; ?>">Suspected</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="change_audit_status">Change Status</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('#department_id').select2();
        $('#audit_status_filter').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");

        table = $('#lot_master_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
//            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('journal/journal_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.department_id = $('#department_id').val();
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                    d.audit_status_filter = $('#audit_status_filter').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                   "className": "dt-right",
                   "targets": [6],
            }],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                total = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 2 ).footer() ).html(total);

                // Total over all pages
                total = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 6 ).footer() ).html(total.toFixed(2));
            }
        });
        
        $(document).on('change', '#department_id', function(){
            var department_id = $('#department_id').val();
            if(department_id == '' || department_id === null){
                $('#select2-department_id-container .select2-selection__placeholder').html(' All ');
            }
        });
        
        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ordering": false,
            "ajax": {
                "url": "<?php echo site_url('journal/journal_detail_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.journal_id = $('#clicked_item_id').val();
                },
            },
            "columnDefs": [{
                   "className": "dt-right",
                   "targets": [1,2],
            }],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                total = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 1 ).footer() ).html(total);

                // Total over all pages
                total = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 2 ).footer() ).html(total);
            }
        });
        
        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');

        $(document).on('click', '.item_row', function () {
            $('#clicked_item_id').val($(this).attr('data-journal_id'));
            item_table.draw();
            $('#myModal').modal('show');
        });

        $(document).on("click", ".delete_journal", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (data) {
                        table.draw();
                        show_notify('Journal Entry Deleted Successfully!', true);
                    }
                });
            }
        });

        $(document).on('click', '#search', function (){
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on("click", ".audit_status_button", function () {
            var audit_status_pay_rec_id = $(this).attr('data-audit_status_pay_rec_id');
            var audit_status = $(this).attr('data-audit_status');
            $('#audit_status_pay_rec_id').val(audit_status_pay_rec_id);
            $('#audit_status_value').val(audit_status);
            $('input:radio[name=audit_status][id=audit_status_'+ audit_status +']').prop('checked', true);
            $('#audit_status_modal').modal('show');
        });
        
        $("#audit_status_modal").on("hidden.bs.modal", function () {
            $('#audit_status_pay_rec_id').val('');
            $('#audit_status_value').val('');
        });
        
        $(document).on("change", "input[type=radio][name=audit_status]", function () {
            var allow_to_audit = 0;
            <?php if($this->app_model->have_access_role(JOURNAL_MODULE_ID, "allow to audit / suspect")){ ?>
                allow_to_audit = 1;
            <?php } ?>
            var allow_audit_to_pending = 0;
            <?php if($this->app_model->have_access_role(JOURNAL_MODULE_ID, "allow audit / suspect to pending")){ ?>
                allow_audit_to_pending = 1;
            <?php } ?>
            if ((this.value == '2' || this.value == '3') && allow_to_audit != 1) {
                var audit_status_value = $('#audit_status_value').val();
                $('input:radio[name=audit_status][id=audit_status_'+ audit_status_value +']').prop('checked', true);
                show_notify('Not Allow to Audit / Suspect!', false);
                return false;
            }
            if (this.value == '1' && allow_audit_to_pending != 1) {
                var audit_status_value = $('#audit_status_value').val();
                $('input:radio[name=audit_status][id=audit_status_'+ audit_status_value +']').prop('checked', true);
                show_notify('Not Allow Audit / Suspect to Pending!', false);
                return false;
            }
        });
        
        $(document).on("click", "#change_audit_status", function () {
            var audit_status_pay_rec_id = $('#audit_status_pay_rec_id').val();
            var audit_status = $("input[name='audit_status']:checked").val();
            if(audit_status_pay_rec_id != '' && audit_status != ''){
                var value = confirm('Are you sure to Change Audit Status?');
                if (value) {
                    $("#ajax-loader").show();
                    $.ajax({
                        url: "<?php echo site_url('journal/audit_status_journal') ?>",
                        type: "POST",
                        async: false,
                        data: {audit_status_pay_rec_id : audit_status_pay_rec_id, audit_status : audit_status},
                        success: function (response) {
                            var json = $.parseJSON(response);
                            $("#ajax-loader").hide();
                            if (json['success'] == 'Changed') {
                                table.draw();
                                show_notify('Audit Status Changed Successfully!', true);
                                $('#audit_status_modal').modal('hide');
                            } else {
                                show_notify('Somthing was Wrong!', true);
                            }
                        }
                    });
                }
            }
        });
        
    });
</script>
