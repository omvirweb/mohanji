<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">   
    <section class="content-header">
        <h1> Interest </h1>
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
                                <div class="col-md-3">
                                    <label>Account</label>
                                    <select name="account_id" id="account_id" class="form-control select2" onchange="get_interest_rate();">
                                        <?php /*if(!empty($account)){
                                        foreach ($account as $acc){ ?>
                                        <option value="<?php echo $acc->account_id; ?>" <?php if(isset($account_id) && $account_id == $acc->account_id) { echo ' Selected '; } ?> ><?php echo $acc->account_name.' - '.$acc->account_mobile; ?></option>
                                        <?php } } */ ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label>Date</label>
                                    <input type="text" name="datepicker_month" id="datepicker_month" class="form-control" value="<?php if(isset($journal_date)) { echo date('m-Y', strtotime('-1 month', strtotime($journal_date))); } else { echo date('m-Y'); } ?>">
                                </div>
                                <div class="col-md-1">
                                    <label>Gold Rate</label>
                                    <input type="text" name="" id="gold_rate" class="form-control num_only" value="<?php echo $gold_rate; ?>" disabled="">
                                </div>
                                <div class="col-md-1">
                                    <label>Silver Rate</label>
                                    <input type="text" name="" id="silver_rate" class="form-control num_only" value="<?php echo $silver_rate; ?>" disabled="">
                                </div>
                                <div class="col-md-1">
                                    <label>Interest Rate</label>
                                    <input type="text" name="" id="int_rate" class="form-control" value="<?php if(isset($interest_rate)) { echo $interest_rate; } ?>" disabled="">
                                </div>
                                <input type="hidden" id="rowCount" value="0">
                                <div class="col-md-1">
                                    <br />
                                    <button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                </div>
                                <div class="col-md-2">
                                    <label><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></label><br />
                                    <label>Gold Rate : <?php echo $gold_rate; ?></label><br />
                                    <label>Silver Rate : <?php echo $silver_rate; ?></label><br />
                                </div>
                                <div class="col-md-2">
                                    <button name="generate_interest" id="generate_interest" class="btn btn-primary pull-right">Generate</button>
                                </div>
                                <div class="clearfix"></div><br />
                                <div class="col-md-12">
                                    <table id="interest_table" class="table row-border table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th class="text-right">Gold</th>
                                                <th class="text-right">Silver</th>
                                                <th class="text-right">Amount</th>
                                                <th class="text-right">Net Amount</th>
                                                <th class="text-right">Interest Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Month Interest </th>
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
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_from_main_party_select2_source/' . CASE_CUSTOMER_ACCOUNT_ID)?>");
        get_interest_rate();
        $('#datepicker_month').datepicker({
            format: "mm-yyyy",
            startView: "months",
            minViewMode: "months",
            showButtonPanel: true,
            autoclose: true,
            
        });
        $('#generate_interest').hide();
        $(document).on('change', '#datepicker_month', function(e) {
           var selected_date = $(this).val();
           selected_date = '01-' +selected_date;
           var current_date = new Date();

           if(new Date(current_date.getFullYear(), current_date.getMonth(), 1) > new Date(selected_date.split("-").reverse().join("-"))) {
               $('#generate_interest').show();
           } else {
               $('#generate_interest').hide();
           }
        });
        account = $("#account_id option:selected").text();
        $(document).on('change','#account_id', function(e){
            account = $("#account_id").select2('data')[0].text;
        });
        
        $(document).on('click','#generate_interest', function(e){
            var month = $('#datepicker_month').val();
            if(month == ''){
                show_notify('Please Select Month!', false);
                $("#datepicker_month").focus();
                return false;
            }
            var gold_rate = $('#gold_rate').val();
            if(gold_rate == '' || gold_rate == 0){
                show_notify('Please enter Gold Rate in Setting!', false);
                return false;
            }
            var silver_rate = $('#silver_rate').val();
            if(silver_rate == '' || silver_rate == 0){
                show_notify('Please enter Silver Rate in Setting!', false);
                return false;
            }
            $('#ajax-loader').show();
            window.location.href = "<?php echo base_url('reports_new_sp3/generate_interest'); ?>/" + month;
            return false;
        });
        
        table = $('#interest_table').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'pdfHtml5',
                download: 'open',
                text: 'Print',
//                orientation: 'landscape',
                title: function () { return ('Customer Ledger') },
                header: true,
                footer: true,
                customize: function(doc) {
                    var objLayout = {};
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        doc.content[1].layout = objLayout;
                        doc.defaultStyle.fontSize = 12;
                        var iColumns = $('#interest_table thead th').length;
 
                    var rowCount = document.getElementById("interest_table").rows.length;
                    var groupByValue = $('#GroupByFilter option:selected').data('index');
                    for (i = 0; i < rowCount; i++) {
                        doc.content[1].table.body[i][1].alignment = 'right';
                        doc.content[1].table.body[i][2].alignment = 'right';
                        doc.content[1].table.body[i][3].alignment = 'right';
                        doc.content[1].table.body[i][4].alignment = 'right';
                        doc.content[1].table.body[i][5].alignment = 'right';
                    };
                    doc.content[1].margin = [ 10, 0, 10, 00 ];
                    doc.content.splice(0, 1, {
                      text: [{
                        text: 'Interest \n',
                        bold: true,
                        fontSize: 25
                      }, {
                        text: 'Account : '+ account +' \n',
                        bold: true,
                        fontSize: 11
                      }, {
                        text: 'Date : ' + $('#datepicker_month').val()+ '\n',
                        bold: true,
                        fontSize: 11
                      }, {
                        text: 'Gold Rate : '+ $('#gold_rate').val() +'           Silver Rate : ' + $('#silver_rate').val() + '             Interest Rate : ' + $('#int_rate').val() + '\n\n',
                        bold: true,
                        fontSize: 11
                      }],
  //                    margin: [12, 0, 0, 12],
                      alignment: 'center'
                    });
//                    doc.styles.tableHeader.alignment = 'left'; //giustifica a sinistra titoli colonne
//                    doc.content[1].table.widths = [90,90,90,90,90,90];
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                },
                exportOptions: {
                    columns: [0,1,2,3,4,5],
                },
                },
            ],
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": false,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports_new_sp3/interest_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.month = $('#datepicker_month').val();
                    d.account_id = $('#account_id').val();
                    d.offset = $('#rowCount').val();
                    d.gold_rate = $('#gold_rate').val();
                    d.silver_rate = $('#silver_rate').val();
                    d.int_rate = $('#int_rate').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {"className": "text-right", "targets": [1,2,3,4,5]}
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

                // Total over all pages
                amount_total = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                if(amount_total < 0){
                    amount_total = 0;
                }
                $(api.column(5).footer()).html(amount_total.toFixed(2));
                //Sell rate & Buy Rate Avg
            },
        });
        
        $(document).on('click', '#search', function (){
            $('#ajax-loader').show();
            table.draw();
        });
        
//        $(document).on('change', '#account_id', function (){
//            table.draw();
//        });
        $('.buttons-pdf.buttons-html5').attr('style', 'background: #d9edf7 !important; color: #3c8dbc; border: 1px solid #3c8dbc;');
    });
    
    function get_interest_rate(){
        var account_id = $("#account_id").val();
        <?php if(isset($interest_rate)) { ?>
            $("#int_rate").val(<?php echo $interest_rate; ?>);
        <?php } else { ?>
            $.ajax({
                url: '<?php echo base_url('reports/get_interest_rate'); ?>',
                type: 'POST',
                data : {account_id :account_id},
                async: false,
                success : function(response){
                    var json = $.parseJSON(response);
                    if (json['interest_rate']) {
                            $("#int_rate").val(json['interest_rate']);
                    } else {
                        $("#int_rate").val('0');
                    }
                }
            });
        <?php } ?>
    }
    
</script>


