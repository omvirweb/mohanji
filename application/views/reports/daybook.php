<?php // $this->load->view('success_false_notify');       ?>
<div class="content-wrapper">   
    <section class="content-header">
        <h1>
            Day Book
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
                                        <label>Party</label>
                                        <select name="account_id" id="account_id" class="form-control">
                                            <option value="0">All</option>
                                            <?php if(!empty($account)){
                                            foreach ($account as $acc){ ?>
                                                <option value="<?php echo $acc->account_id?>"><?php echo $acc->account_name.' - '.$acc->account_mobile; ?></option>
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
                                    <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                    <table id="day_book_table" class="table row-border table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Narration</th>
                                                <th>Ref</th>
                                                <th>Gross Wt</th>
                                                <th>Less</th>
                                                <th>Nt Wt</th>
                                                <th>Gold(Dr)</th>
                                                <th>Gold(Cr)</th>
                                                <th>Silver(Dr)</th>
                                                <th>Silver(Cr)</th>
                                                <th>Debit(Dr)</th>
                                                <th>Credit(Cr)</th>
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
        </div>
    </div>
</div>
<script>
    var col1 = 10;
    $(document).ready(function () {
        $('#account_id').select2();
        table = $('#day_book_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports/daybook_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.account_id = $('#account_id').val();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [1, 2, 3, 4, 5, 6, 7, 8, 9,col1],
            }]
        });
        
        $(document).on('click', '#search', function (){
            var account_id = $('#account_id').val();
            if(account_id == '0'){
                col1 = 10;
                table.column( 0 ).visible( true );
            } else {
                col1 = '';
                table.column( 0 ).visible( false );
            }
            table.draw();
        });
    });
</script>


