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
            Cashbook Receipt Log
            <?php
            $isView = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "add"); ?>
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
                                            <label>Account</label>
                                            <select name="account_filter" class="form-control account_filter" id="account_filter"></select>
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
                                        <br/><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                        <div class="clearfix"></div>
                                        <br/>
                                        <table id="lot_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Update(count)</th>
                                                    <th>Is Delete?</th>
                                                    <th>Received?</th>
                                                    <th>A/C.Name</th>
                                                    <th>Department</th>
                                                    <th>Amount(Cr)</th>
                                                    <th>Vno</th>
                                                    <th>Narration</th>
                                                    <th width="80px" class="text-nowrap">Date</th>
                                                    <th>Is Received</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
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
//        $('#ajax-loader').show();
        $('#audit_status_filter').select2();
        $('#account_filter').select2();
        initAjaxSelect2($("#account_filter"), "<?= base_url('app/account_name_with_number_without_department_without_case_customer_select2_source/')?>");

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
                "url": "<?php echo site_url('reports/cash_receipt_log_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.payment_receipt = '1';
                    d.account_filter = $('#account_filter').val();
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                   "className": "dt-right",
                   "targets": [3],
            }],
        });
        
//        
//        item_table = $('#item_table').DataTable({
//            "serverSide": true,
//            "paging": false,
//            "ordering": false,
//            "ajax": {
//                "url": "<?php echo site_url('journal/journal_detail_datatable') ?>",
//                "type": "POST",
//                "data": function (d) {
//                    d.journal_id = $('#clicked_item_id').val();
//                },
//            },
//            "columnDefs": [{
//                   "className": "dt-right",
//                   "targets": [1,2],
//            }],
//            
//        });
        
//        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');

        $(document).on('click', '.item_row', function () {
            $('#clicked_item_id').val($(this).attr('data-journal_id'));
            item_table.draw();
            $('#myModal').modal('show');
        });

        $(document).on('click', '#search', function (){
            $('#ajax-loader').show();
            table.draw();
        });
        
        
        
        
    });
</script>
