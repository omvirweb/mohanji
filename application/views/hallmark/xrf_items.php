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
            XRF / HM / Laser Items
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
                                    <input type="text" name="filter_from_date" id="datepicker1" class="from_date form-control" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label>To Date</label>
                                    <input type="text" name="filter_to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label>Deliver Status</label>
                                    <select name="filter_deliver_status" id="filter_deliver_status" class="form-control select2">
                                        <option value="all">All</option>
                                        <option value="1">Delivered</option>
                                        <option value="2" selected="">Not Delivered</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>HM / L / T</label>
                                    <select name="filter_hm_ls_option" id="filter_hm_ls_option" class="form-control select2">
                                        <option value="all">All</option>
                                        <option value="1" selected="">XRF</option>
                                        <option value="2" >LASER</option>
                                        <option value="3">TUNCH</option>
                                    </select>
                                </div>
                                <br />
                                <button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>

                                <div class="clearfix"></div>
                                <?php if($isView) { ?>
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="60px">Deliver Status</th>
                                                    <th>Party Name</th>
                                                    <th>Posting Date</th>
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
                                                    <th></th>
                                                    <th></th>
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
<script>
    var item_table;
    $(document).ready(function () {
        $('.select2').select2();
        $('#ajax-loader').show();
        
        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ordering": false,
            "ajax": {
                "url": "<?php echo site_url('hallmark/xrf_items_page_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.filter_deliver_status = $('#filter_deliver_status').val();
                    d.filter_hm_ls_option = $('#filter_hm_ls_option').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                   "className": "dt-right",
                   "targets": [5,6,7,8,9],
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

                total4 = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 5 ).footer() ).html(total4.toFixed(3));


                total6 = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                total6 = parseFloat(total6);
                $( api.column( 7 ).footer() ).html(total6);

                total8 = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                total8 = parseFloat(total8);
                $( api.column( 9 ).footer() ).html(total8.toFixed(2));

            }
        });

        $(document).on('click', '#search', function () {
            $("#ajax-loader").show();
            item_table.draw();
        });
        
        item_table.draw();
        
        $(document).on("click", ".set_deliver_status", function () {
            var deliver_status = $(this).data('deliver_status');
            if(deliver_status == '1'){
                var msg = 'Are you sure you want to Set to Deliver?';
            }
            if(deliver_status == '0') {
                var msg = 'Are you sure you want to Set to Not Deliver?';
            }
            if (confirm(msg)) {
                $('#ajax-loader').show();
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: {deliver_status : deliver_status},
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $('#ajax-loader').hide();
                        if (json['error'] == 'Error') {
                            show_notify('Something Went Wrong!!.', false);
                        } else if (json['success'] == 'Updated') {
                            item_table.draw();
                            show_notify('Status changed Successfully!', true);
                        }
                    }
                });
            } else {
                if(deliver_status == '1'){
                    $(this).prop('checked', false);
                } else {
                    $(this).prop('checked', true);
                }
            }
        });
        
    });
</script>
