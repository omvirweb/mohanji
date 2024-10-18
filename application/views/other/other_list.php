<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <style>
        .dataTables_scroll {
            overflow:auto;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Other Entry List
            <?php
            $isView = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "add");
            ?>
            <?php if ($isAdd) { ?>
                <a href="<?= base_url('other/add') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Other Entry</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <?php if ($isView) { ?>
                        <!-- Horizontal Form -->
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <?php if (isset($view_not) && !empty($view_not)) { ?> 
                                                <div class="filter" hidden=""> 
                                            <?php } else { ?>
                                                <div class="filter">
                                            <?php } ?>
                                                    <div class="col-md-3">
                                                        <label>From Date</label>
                                                        <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y", strtotime("first day of this month")); ?>">
                                                        <!--<input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="">-->
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>To Date</label>
                                                        <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y'); ?>">
                                                        <!--<input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="">-->
                                                    </div>
                                                    <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                                </div>
                                                <table id="other_entry_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 20%;">Action</th>
                                                            <th>Account Name</th>
                                                            <th>Department</th>
                                                            <th>Other No</th>
                                                            <th  width="80px" class="text-nowrap">Other Date</th>
                                                            <th>Remark</th>
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
                    <?php } ?>
                </div>
            </div>
        </div>
        <input type="hidden" id="clicked_item_id" value="-1" >
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                Modal content
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Sell Item List</h4>
                    </div>
                    <div class="modal-body">
                        <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Other Item No</th>
                                    <th>Type</th>
                                    <th>Category Name</th>
                                    <th>Item Name</th>
                                    <th>Gross.Wt</th>
                                    <th>Rate</th>
                                    <th>Rate On</th>
                                    <th>Amount</th>
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
        <input type="hidden" id="clicked_pay_rec_id" value="-1" >
        <div id="pay_rec_modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                Modal content
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Payment Receipt List</h4>
                    </div>
                    <div class="modal-body">
                        <table id="pay_rec_table" class="table row-border table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Payment Receipt</th>
                                    <th>Cash/Cheque</th>
                                    <th>Bank Name</th>
                                    <th>Amount</th>
                                    <th>Narration</th>
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
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        
        table = $('#other_entry_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            //            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('other/other_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [],
            }]
        });

        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('other/other_item_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.other_id = $('#clicked_item_id').val()
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [4,5,6,7],
            }]
        });

        pay_rec_table = $('#pay_rec_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('other/pay_rec_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.pay_rec_id = $('#clicked_pay_rec_id').val()
                },
            },
            "columnDefs": [{
                    "className": "dt-right",
                    "targets": [3],
                }]
        });

        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');

        $(document).on('click', '#search', function () {
            $("#ajax-loader").show();
            table.draw();
        });

        $(document).on('click', '.item_row', function () {
            $('#clicked_item_id').val($(this).attr('data-other_id'));
            item_table.draw();
            $('#myModal').modal('show');
        });

        $(document).on('click', '.pay_rec_id', function () {
            $('#clicked_pay_rec_id').val($(this).attr('data-pay_rec_id'));
            pay_rec_table.draw();
            $('#pay_rec_modal').modal('show');
        });
        
        $(document).on("click", ".delete_other", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Other Entry. This Other Entry has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Other Entry Deleted Successfully!', true);
                        }
                    }
                });
            }
        });
    });
</script>
