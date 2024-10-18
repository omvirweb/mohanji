<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="" method="post" id="save_order" novalidate enctype="multipart/form-data">
     
        <section class="content-header">
            <h1>
                Receipt List
                <?php
                $isAdd = $this->app_model->have_access_role(HALLMARK_RECEIPT_MODULE_ID, "add"); ?>
                <?php if($isAdd){ ?>
                    <a href="<?= base_url('hallmark/receipt') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Receipt</a>
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
                                    <div class="col-md-3">
                                        <label for="party_id">Party<span class="required-sign">&nbsp;*</span></label>
                                        <select name="" id="party_id" class="form-control select2"></select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>From Date</label>
                                        <label style="font-size: 10px">Everything From Start <input type="checkbox" name="everything_from_start" id="everything_from_start" ></label>
                                        <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y");?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y');?>">
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <button type="button" name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <table id="receipt_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th class="text-center">
                                                            <input type="checkbox" id="chk_all" class="icheckbox_flat-blue" autocomplete="off">
                                                        </th>
                                                        <th>Receipt No</th>
                                                        <th>Party Name</th>
                                                        <th>Receipt Date</th>
                                                        <th>Received Time</th>
                                                        <th>Metal</th>
                                                        <th>Delivery Date</th>
                                                        <th>Delivery Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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
<input type="hidden" id="clicked_item_id" value="-1" >
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:80% !important;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Receipt Details</h4>
            </div>
            <div class="modal-body">
                <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Receipt Wt.</th>
                            <th>Purity</th>
                            <th>Box No</th>
                            <th>Pcs</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>Total : </th>
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
<script type="text/javascript">
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(document).ready(function () {
        initAjaxSelect2($("#party_id"), "<?= base_url('app/party_name_with_number_select2_source') ?>");
        initAjaxSelect2($("#article_id"), "<?= base_url('app/item_name_select2_source') ?>");
        
        table = $('#receipt_table').DataTable({
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
                "url": "<?php echo site_url('hallmark/receipt_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.party_id = $('#party_id').val();
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [ 
                {
                "targets": 1,
                "orderable": false
                },
                {"className": "dt-right",
                "targets": [2]
                }
            ]
        }); 
    
        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ordering": false,
            "ajax": {
                "url": "<?php echo site_url('hallmark/receipt_detail_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.receipt_id = $('#clicked_item_id').val();
                },
            },
            "columnDefs": [{
                   "className": "dt-right",
                   "targets": [1,2,3,4],
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
                total1 = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 1 ).footer() ).html(total1.toFixed(3));
                total3 = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 3 ).footer() ).html(total3);
                total4 = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 4 ).footer() ).html(total4);
            }
        });
    
        $(document).on('click', '#search', function (){
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on("click", ".delete_receipt", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (data) {
                        table.draw();
                        show_notify('Receipt Entry Deleted Successfully!', true);
                    }
                });
            }
        });
        
        $(document).on('click', '.item_row', function () {
            $('#clicked_item_id').val($(this).attr('data-receipt_id'));
            item_table.draw();
            $('#myModal').modal('show');
        });
    
    });    
</script>
