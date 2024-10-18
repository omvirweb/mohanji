<?php // $this->load->view('success_false_notify');       ?>
<div class="content-wrapper">   
    <section class="content-header">
        <h1>
            Balance Sheet
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
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label>From Date</label>
                                        <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date("d-m-Y",strtotime($from_date));?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y');?>">
                                    </div>
                                    <div class="col-md-3">
                                            <label>Balance Sheet Format</label>
                                            <select name="balance_sheet_format" class="form-control Format select2" id="balance_sheet_format">
                                                <option value="group" selected="selected">Group</option>
                                                <option value="account">Account</option>
                                            </select>
                                        </div>
                                    <div class="col-md-1">
                                        <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                    </div>
                                    <div class="col-md-2">
                                        <label><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></label><br />
                                        <label>Gold Rate : <?php echo $gold_rate; ?></label><br />
                                        <label>Silver Rate : <?php echo $silver_rate; ?></label><br />
                                    </div>
                                </div>
                                <div class="clearfix"></div><br />
                                <div class="col-md-12">
                                    <table id="balance_sheet_table" class="table table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="35%"><h4>Credit</h4>Particulars</th>
                                                <th width="150">Amount</th>
                                                <th width="15"></th>
                                                <th width="35%"><h4>Debit</h4>Particulars</th>
                                                <th width="150">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total : </th>
                                                <th></th>
                                                <th></th>
                                                <th>Total : </th>
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
<input type="hidden" id="total_net_amount">
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('.select2').select2();

        table = $('#balance_sheet_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "searching": false,
            "ajax": {
                "beforeSend": function () {
                    $('#ajax-loader').show();
                },
                "url": "<?php echo site_url('reports_new_sp3/balance_sheet_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.balance_sheet_format = $('#balance_sheet_format').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
                "dataSrc": function ( jsondata ) {
                    if(jsondata.total_net_amount){
                        $('#total_net_amount').val(jsondata.total_net_amount);
                    } else {
                        $('#total_net_amount').val('');
                    }

                    /*
                    if(jsondata.foot_total_silver_fine){
                        $('#foot_total_silver_fine1').val(jsondata.foot_total_silver_fine);
                    } else {
                        $('#foot_total_silver_fine1').val('');
                    }
                    if(jsondata.foot_total_amount){
                        $('#foot_total_amount1').val(jsondata.foot_total_amount);
                    } else {
                        $('#foot_total_amount1').val('');
                    }
                    if(jsondata.total_net_amount){
                        $('#total_net_amount1').val(jsondata.total_net_amount);
                    } else {
                        $('#total_net_amount1').val('');
                    }*/
                    return jsondata.data;
                },
            },
            "columnDefs": [
                {"className": "dt-right", "targets": []},
                {"className": "text-right", "targets": [1,4]},
                {"orderable": false, "targets": [0,1,2,3,4]},
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                $( api.column( 1 ).footer() ).html('');
                $( api.column( 1 ).footer() ).html($('#total_net_amount').val());
                $( api.column( 4 ).footer() ).html('');
                $( api.column( 4 ).footer() ).html($('#total_net_amount').val());
            }
        });
        
        $(document).on('click', '#search', function (){
            $('#ajax-loader').show();
            table.draw();
        });
    });
</script>


