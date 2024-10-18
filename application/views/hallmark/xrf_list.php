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
            XRF / HM / Laser List
            <?php
            $isView = $this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?=base_url('hallmark/xrf') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add XRF / HM / Laser</a>
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
                            <div class="row">
                                <div class="col-md-2">
                                    <label>From Date</label> <label style="font-size: 10px"> Everything From Start <input type="checkbox" name="everything_from_start" id="everything_from_start" ></label>
                                    <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y", strtotime("first day of this month")); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label>To Date</label>
                                    <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                                <br />
                                <button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>

                                <div class="clearfix"></div>
                                <?php if($isView) { ?>
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="xrf_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px;">Action</th>
                                                    <th style="width: 90px;">Receipt No.</th>
                                                    <th>Party</th>
                                                    <th>Box No.</th>
                                                    <th>Posting Date</th>
                                                    <th>Receipt Date</th>
                                                    <th>No of Pcs</th>
                                                    <th>Total Amount</th>
                                                    <th>Other Charges</th>
                                                    <th>Advance Rec.</th>
                                                    <th>Pending Amount</th>
                                                    <th>Taken By Name</th>
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
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clicked_xrf_id" value="-1" >
<div id="xrfItemModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:80% !important;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">XRF / HM / Laser Articles</h4>
            </div>
            <div class="modal-body">
                <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>HM / L / T</th>
                            <th>Article</th>
                            <th>Rec. Weight</th>
                            <th>Purity</th>
                            <th>Rec. Qty</th>
                            <th>Price / Per Pcs</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>Total : </th>
                            <th></th>
                            <th style="align: right"></th>
                            <th style="align: right"></th>
                            <th style="align: right"></th>
                            <th style="align: right"></th>
                            <th style="align: right"></th>
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
<div id="updatetemModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:80% !important;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-md-3">
                    <label for="">Paid Weight</label>
                    <input type="text" name="" id="" class="form-control text-right" value="">
                </div>
                <div class="col-md-3">
                    <label for="">Pending Amount</label>
                    <input type="text" name="" id="" class="form-control text-right" value="">
                </div>
                <div class="col-md-3">
                    <label for=""> Person Name</label>
                    <input type="text" name="" id="" class="form-control text-right" value="">
                </div>
                <div class="col-md-3">
                    <br/>
                    <button type="submit" class="btn btn-primary btn-sm  module_save_btn" disabled="" style="margin: 5px;">Save</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="iframeDiv" style="display:none"></div>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('#ajax-loader').show();
        table = $('#xrf_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "ordering":[1, "desc"],
            "order": [],
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('hallmark/xrf_datatable') ?>",
                "type": "POST",
                "data": function (d) {
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
                "targets": [1,3,6,7,8,9,10],
            },{
                orderable: false,
                "targets": [0],
            }]
        });
        
        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ordering": false,
            "ajax": {
                "url": "<?php echo site_url('hallmark/xrf_items_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.xrf_id = $('#clicked_xrf_id').val();
                },
            },
            "columnDefs": [{
                   "className": "dt-right",
                   "targets": [2,3,4,5,6],
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

                total2 = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 2 ).footer() ).html(total2.toFixed(3));

                
                total5 = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                total5 = parseFloat(total5);
                $( api.column( 4 ).footer() ).html(total5);

                total7 = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                total7 = parseFloat(total7);
                $( api.column( 6 ).footer() ).html(total7.toFixed(2));
            }
        });

        $(document).on('click', '.print_xrf_receipt', function () {
            var xrf_id = $(this).attr('data-xrf_id');
            $("#iframeDiv").html('');
            $('#iframeDiv').append("<iframe src='<?=base_url('hallmark/print_xrf')?>/"+xrf_id+"'></iframe>");
        });

        $(document).on('click', '.item_row', function () {
            $('#clicked_xrf_id').val($(this).attr('data-xrf_id'));
            item_table.draw();
            $('#xrfItemModal').modal('show');
        });
        
        $(document).on('click', '.update_row', function () {
//            $('#clicked_xrf_id').val($(this).attr('data-xrf_id'));
            $('#updatetemModal').modal('show');
        });

        $(document).on('click', '#search', function () {
            $("#ajax-loader").show();
            table.draw();
        });
        
        $(document).on("click", ".delete_xrf", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this XRF / HM / Laser. This XRF / HM / Laser has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('XRF / HM / Laser Deleted Successfully!', true);
                        }
                    }
                });
            }
        });

        table.draw();
    });
</script>
