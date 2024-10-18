<?php // $this->load->view('success_false_notify');         ?>
<div class="content-wrapper">   
    <form class="form-horizontal" action="" method="post" id="save_case" novalidate enctype="multipart/form-data">
        <?php if (isset($case_data->id) && !empty($case_data->id)) { ?>
            <input type="hidden" name="case_id" value="<?= $case_data->case_id ?>">
        <?php } ?>
        <section class="content-header">
            <h1>
                Transaction Book
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
                                        <html>
                                            <head>
                                                <meta name="viewport" content="width=device-width, initial-scale=1">
                                                <style>
                                                    * {
                                                        box-sizing: border-box;
                                                    }
                                                    #myTable {
                                                        border-collapse: collapse;
                                                        width: 100%;
                                                        border: 1px solid black;
                                                        font-size: 15px;
                                                        line-height: 1px;
                                                    }

                                                    #myTable th, #myTable td {
                                                        border: 1px solid black;
                                                        padding: 12px;
                                                    }

                                                    #myTable tr {
                                                        border-bottom: 1px solid black;
                                                    }

                                                    #myTable tr.header, #myTable tr:hover {
                                                        background-color: #f1f1f1;
                                                    }
                                                </style>
                                            </head>
                                            <body>
                                                <div>
                                                   <div class="col-md-3">
                                                        <label>Account</label>
                                                        <select name="account_id" id="account_id" class="form-control">
                                                            <option value="0">All</option>
                                                            <?php if(!empty($account)){
                                                            foreach ($account as $acc){ ?>
                                                                <option value="<?php echo $acc->account_id?>"><?php echo $acc->account_name; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>From Date</label>
                                                        <input type="text" name="from_date" id="datepicker1" class="from_date form-control" value="<?php echo date('d-m-Y');?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>To Date</label>
                                                        <input type="text" name="to_date" id="datepicker2" class="to_date form-control" value="<?php echo date('d-m-Y');?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Transaction</label>
                                                        <select name="transaction_id" id="transaction_id" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="1">Only Gold</option>
                                                            <option value="2">Only Silver</option>
                                                            <option value="3">Only Amount</option>
                                                        </select>
                                                    </div>
                                                    <br /><a href="javascript:void(0);" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</a><br /><br />
                                                    <div class="col-md-12">
                                                        <table id="transaction_table" align="center" class="table row-border table-bordered table-striped" style="width:100%">
                                                            <thead>
                                                            <tr>
                                                                <th>Narration</th>
                                                                <th>Ref.No</th>
                                                                <!--<th>Gross Wt</th>
                                                                <th>Wt</th>-->
                                                                <th>Gold(Dr)</th>
                                                                <th>Gold(Cr)</th>
                                                                <th>CSilver(Dr)</th>
                                                                <th>CSilver(Cr)</th>
                                                                <th>Amount(Dr)</th>
                                                                <th>Amount(Cr)</th>
                                                                <!--<th>Gold Gold Gr.Wt</th>
                                                                <th>Sil.Sil Gr.Wt</th>-->
                                                            </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                            </body>
                                        </html>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</form>       
</div>
<script>
    $(document).ready(function () {
       $('#account_id').select2();
       $('#transaction_id').select2();
       table = $('#transaction_table').DataTable({
           "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('transaction/transaction_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.account_id = $('#account_id').val();
                    d.transaction_id = $('#transaction_id').val();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [2,3,4,5,6,7],
            }],
        });
        $(document).on('click', '#search', function (){
            table.draw();
            var transaction_id = $('#transaction_id').val();
            if(transaction_id == '1'){
                table.column( 4 ).visible( false );
                table.column( 5 ).visible( false );
                table.column( 6 ).visible( false );
                table.column( 7 ).visible( false );
                table.column( 2 ).visible( true );
                table.column( 3 ).visible( true );
            } if(transaction_id == '2') {
                table.column( 2 ).visible( false );
                table.column( 3 ).visible( false );
                table.column( 6 ).visible( false );
                table.column( 7 ).visible( false );
                table.column( 4 ).visible( true );
                table.column( 5 ).visible( true );
            } if(transaction_id == '3') {
                table.column( 2 ).visible( false );
                table.column( 3 ).visible( false );
                table.column( 4 ).visible( false );
                table.column( 5 ).visible( false );
                table.column( 6 ).visible( true );
                table.column( 7 ).visible( true );
            } if(transaction_id == '') {
                table.column( 2 ).visible( true );
                table.column( 3 ).visible( true );
                table.column( 4 ).visible( true );
                table.column( 5 ).visible( true );
                table.column( 6 ).visible( true );
                table.column( 7 ).visible( true );
            }
        });
    });
</script>

