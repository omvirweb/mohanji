<?php $this->load->view('success_false_notify'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Ledger
            <div class="col-md-3 pull-right">
                <small>
                    <span id="daterange_data" class="text-aqua pull-right" data-toggle="tooltip" title="Date Range Data" data-html="true" data-placement="left" >Daterange Total</span> <br/>
                    <span id="average_daterange_data" class="text-aqua pull-right" data-toggle="tooltip" title="Average Date Range Data" data-html="true" data-placement="left" >Average Daterange Total</span>
                </small>
            </div>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-3 pr0">
                            <label>Account</label>
                            <select name="account_id" id="account_id" class="form-control select2"></select>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-6">
                            <label>From Date</label>
                            <input type="text" name="from_date" id="datepicker1" class="form-control" value="<?php echo date("d-m-Y");?>">
                            </div>
                            <div class="col-md-6">
                            <label>To Date</label>
                            <input type="text" name="to_date" id="datepicker2" class="form-control" value="<?php echo date('d-m-Y');?>">
                            </div>
                        </div>
                        <input type="hidden" id="rowCount" value="0">
                        <div class="col-md-2">
                            <label>Type(Sort)</label>
                            <select id="type_sort" class="form-control select2">
                                <option value=""> All </option>
                                <option value="P">P</option>
                                <option value="S">S</option>
                                <option value="E">E</option>
                                <option value="SP Discount">SP Discount</option>
                                <option value="M R">M R</option>
                                <option value="M P">M P</option>
                                <option value="Payment">Payment</option>
                                <option value="Receipt">Receipt</option>
                                <option value="GB S">GB S</option>
                                <option value="GB P">GB P</option>
                                <option value="SB S">SB S</option>
                                <option value="SB P">SB P</option>
                                <option value="TR Naam">TR Naam</option>
                                <option value="TR Jama">TR Jama</option>
                                <option value="Ad Charges">Ad Charges</option>
                                <option value="Adjust CR">Adjust CR</option>
                                <option value="J Naam">J Naam</option>
                                <option value="J Jama">J Jama</option>
                                <option value="C R">C R</option>
                                <option value="C P">C P</option>
                                <option value="ST F">ST F</option>
                                <option value="ST T">ST T</option>
                                <option value="MFI">MFI</option>
                                <option value="MFR">MFR</option>
                                <option value="IRKW">IRKW</option>
                                <option value="MFIS">MFI S</option>
                                <option value="MFRS">MFR S</option>
                                <option value="MHMIFW">MHMIFW</option>
                                <option value="MHMIS">MHMIS</option>
                                <option value="MHMRFW">MHMRFW</option>
                                <option value="MHMRS">MHMRS</option>
                                <option value="CASTINGIFW">CASTINGIFW</option>
                                <option value="CASTINGIS">CASTINGIS</option>
                                <option value="CASTINGRFW">CASTINGRFW</option>
                                <option value="CASTINGRS">CASTINGRS</option>
                                <option value="MCHAINIFW">MCHAIN IFW</option>
                                <option value="MCHAINIS">MCHAIN IS</option>
                                <option value="MCHAINRFW">MCHAIN RFW</option>
                                <option value="MCHAINRS">MCHAIN RS</option>
                                <option value="O P">O P</option>
                                <option value="O S">O S</option>
                                <option value="O Payment">O Payment</option>
                                <option value="O Receipt">O Receipt</option>
                                <option value="Hisab Fine">Hisab Fine</option>
                                <option value="HD-I">HD-I</option>
                                <option value="HD-R">HD-R</option>
                                <option value="Hisab Fine S">Hisab Fine S</option>
                                <option value="HD-I S">HD-I S</option>
                                <option value="HD-R S">HD-R S</option>
                                <option value="MHM Hisab Fine">MHM Hisab Fine</option>
                                <option value="MHM HD-I">MHM HD-I</option>
                                <option value="MHM HD-R">MHM HD-R</option>
                                <option value="CASTING Hisab Fine">CASTING Hisab Fine</option>
                                <option value="CASTING HD-I">CASTING HD-I</option>
                                <option value="CASTING HD-R">CASTING HD-R</option>
                                <option value="MCHAIN Hisab Fine">MCHAIN Hisab Fine</option>
                                <option value="MCHAIN HD-I">MCHAIN HD-I</option>
                                <option value="MCHAIN HD-R">MCHAIN HD-R</option>
                                <option value="XRF">XRF</option>
                            </select>
                        </div>
                        <div class="col-md-1" style="margin: 0px; padding: 0px;">
                            <input type="checkbox" name="start_from_zero" id="start_from_zero" value="">
                            <label for="start_from_zero">From Zero &nbsp;</label>
                            <hr style="margin: 0px;">
                            <input type="checkbox" name="view_only_xrf_entries" id="view_only_xrf_entries" value="">
                            <label for="view_only_xrf_entries"><small>View Only <br>XRF Entries</small></label>
                        </div>
                        <div class="col-md-1" style="margin: 0px; padding: 0px;">
                            <input type="checkbox" name="view_only_hisab" id="view_only_hisab" value="">
                            <label for="view_only_hisab"><small>View Only Hisab</small></label>
                        </div>
                        <div class="col-md-1">
                            <br/>
                            <button name="search" id="search" class="btn btn-primary btn-sm pull-left"><span class="fa fa-search-plus"></span> Search</button>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12"><span id="account_remarks"></span></div>
                        <div class="clearfix"></div><br />
                        <!---content start --->
                        <table id="ledger_table" class="table row-border table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th class="text-nowrap" width="60px">Date</th>
                                    <th>Particular</th>
                                    <th>Type</th>
                                    <th>Gr.Wt.</th>
                                    <th>Stone Wt.</th>
                                    <th>Sijat</th>
                                    <th>Less</th>
                                    <th>Net.Wt.</th>
                                    <th>Tunch</th>
                                    <th>Wastage / Labour</th>
                                    <th>Wastage / Labour Value</th>
                                    <th>Labour Amount</th>
                                    <th>Stone Qty</th>
                                    <th>Stone Rs.</th>
                                    <th>Gold Fine</th>
                                    <th>Silver Fine</th>
                                    <th>Amount</th>
                                    <?php // if($c_r_amount_separate == '1') { ?>
                                        <th>C Amt</th>
                                        <th>R Amt</th>
                                    <?php // } ?>
                                    <th></th>
                                    <th>Reference No</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!---content end--->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var selected_rows = [];
    var table;
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('.select2').select2();
        
        account = $("#account_id option:selected").text();
//        $("#account_id").select2();
        $(document).on('change','#account_id', function(e){
            account = $("#account_id").select2('data')[0].text;
            var account_id = $("#account_id").val();
            $.ajax({
                url: "<?= base_url('sell/get_account_old_balance') ?>/" + account_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    if(json.account_remarks != '' && json.account_remarks != null){
                        $('#account_remarks').html('Account Remarks : ' + json.account_remarks);
                    } else {
                        $('#account_remarks').html('');
                    }
                }
            });
        });
        type_sort = $("#type_sort option:selected").text();
//        $("#type_sort").select2();
        $(document).on('change','#type_sort', function(e){
            type_sort = $("#type_sort").select2('data')[0].text;
        });
        
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_with_number_select2_source') ?>");
        <?php if (isset($account_id) && !empty($account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' .$account_id) ?>");
        <?php } else if($allow_cash_customer == 1){ ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . CASE_CUSTOMER_ACCOUNT_ID) ?>");
        <?php } ?>
            
        var ledger_table = $('#ledger_table').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'pdfHtml5',
                download: 'open',
                text: 'Ledger Print',
                orientation: 'landscape',
                title: function () { return ('Ledger') },
                header: true,
                customize: function(doc) {
                    var objLayout = {};
                        var tblBody = doc.content[1].table.body;
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        doc.content[1].layout = objLayout;
                        doc.defaultStyle.fontSize = 12;
                        var iColumns = $('#ledger_table thead th').length;
 
                    var rowCount = document.getElementById("ledger_table").rows.length;
                    var groupByValue = $('#GroupByFilter option:selected').data('index');
                    
                    $('#ledger_table').find('tr').each(function (ix, row) {
                        var index = ix;
                        $(row).find('td').each(function (ind, elt) {
                            if (tblBody[index][1].text == 'Date Range Gold Total' || tblBody[index][1].text == 'Date Range Silver Total') {
                                tblBody[index][3].text = '';
                                tblBody[index][4].text = '';
                                tblBody[index][5].text = '';
                            }
                        });
                    });
                
                    for (i = 0; i < rowCount; i++) {
                        doc.content[1].table.body[i][3].alignment = 'right';
                        doc.content[1].table.body[i][4].alignment = 'right';
                        doc.content[1].table.body[i][5].alignment = 'right';
                        doc.content[1].table.body[i][6].alignment = 'right';
                        doc.content[1].table.body[i][7].alignment = 'right';
                        doc.content[1].table.body[i][8].alignment = 'right';
                        doc.content[1].table.body[i][9].alignment = 'right';
                        doc.content[1].table.body[i][10].alignment = 'right';
                         
                    };
                  doc.content.splice(0, 1, {
                    text: [{
                      text: 'Ledger \n',
                      bold: true,
                      fontSize: 25
                    }, {
                      text: 'Account : '+ account +' \n',
                      bold: true,
                      fontSize: 11
                    }, {
                      text: 'From Date : ' + $('#datepicker1').val() + '           To Date : ' + $('#datepicker2').val() + '\n',
                      bold: true,
                      fontSize: 11
                    }, {
                      text: 'Type(Sort) : ' + type_sort,
                      bold: true,
                      fontSize: 11
                    }],
                    margin: [0, 0, 0, 12],
                    alignment: 'center'
                  });
                },
                exportOptions: {
                    <?php if($c_r_amount_separate == '1') { ?>
                        columns: [ 1, 2, 3, 4, 7, 8, 9, 11, 15, 16, 17, 21],
                    <?php } else { ?>
                        columns: [ 1, 2, 3, 4, 7, 8, 9, 11, 15, 16, 17, 21],
                    <?php } ?>
                },
                },                
            ],
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
//            "paging": false,
//            "ordering": false,
            "order": [],
            "searching": false,
            "paging": false,
            "ajax": {
                "beforeSend": function () {
                    $('#ajax-loader').show();
                },
                "url": "<?php echo site_url('reports_new/customer_ledger_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.account_id = $('#account_id').val();
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.type_sort = $('#type_sort').val();
                    d.offset = $('#rowCount').val();
                    if($('#start_from_zero').prop("checked") == true){
                        d.from_zero = '1';
                    } else {
                        d.from_zero = '0';
                    }
                    if($('#view_only_hisab').prop("checked") == true){
                        d.view_only_hisab = '1';
                    } else {
                        d.view_only_hisab = '0';
                    }
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
                "dataSrc": function ( jsondata ) {
                    var daterange_data = '';
                    if(jsondata.drt_gold_total_gold_fine != '0.000' || jsondata.drt_silver_total_silver_fine != '0.000' || jsondata.drt_daterange_total_amount != '0.00'){
                        daterange_data += ' Gold Grwr : ' + jsondata.drt_gold_total_grwt + ' <br>';
                        daterange_data += ' Gold Less : ' + jsondata.drt_gold_total_less + ' <br>';
                        daterange_data += ' Gold Netwt : ' + jsondata.drt_gold_total_net_wt + ' <br>';
                        daterange_data += ' Gold Fine : ' + jsondata.drt_gold_total_gold_fine + ' <br>';
                        daterange_data += ' Silver Grwr : ' + jsondata.drt_silver_total_grwt + ' <br>';
                        daterange_data += ' Silver Less : ' + jsondata.drt_silver_total_less + ' <br>';
                        daterange_data += ' Silver Netwt : ' + jsondata.drt_silver_total_net_wt + ' <br>';
                        daterange_data += ' Silver Fine : ' + jsondata.drt_silver_total_silver_fine + ' <br>';
                        daterange_data += ' Date Range Amount : ' + jsondata.drt_daterange_total_amount + ' <br>';
                    } else {
                        daterange_data += ' - ';
                    }
                    $('#daterange_data').attr('title', daterange_data).tooltip('fixTitle');

                    var average_daterange_data = '';
                    if(jsondata.average_gold_fine != '0.000' || jsondata.average_silver_fine != '0.000' || jsondata.average_amount != '0.00'){
                        average_daterange_data += ' Average Gold Fine : ' + jsondata.average_gold_fine + ' <br>';
                        average_daterange_data += ' Average Silver Fine : ' + jsondata.average_silver_fine + ' <br>';
                        average_daterange_data += ' Average Amount : ' + jsondata.average_amount + ' <br>';
                    } else {
                        average_daterange_data += ' - ';
                    }
                    $('#average_daterange_data').attr('title', average_daterange_data).tooltip('fixTitle');
                    return jsondata.data;
                },
            },
            "columnDefs": [
                { "orderable": false, "targets": 0 },
                <?php if($c_r_amount_separate == '1') { ?>
                    { "visible": false, "targets": 20 },
                    { "className": "dt-right", "targets": [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 21] },
                <?php } else { ?>
                    { "visible": false, "targets": 18 },
                    { "visible": false, "targets": 19 },
                    { "visible": false, "targets": 20 },
                    { "className": "dt-right", "targets": [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 21] },
                <?php } ?>
                { "className": "text-nowrap", "targets": [1] }
            ],
            "fnRowCallback": function (nRow, aData) {
                var api = this.api(), data;
                var $nRow = $(nRow);
                var date_text = '';
                if(aData[1] != ''){
                    date_text = aData[1].replace(/(<([^>]+)>)/ig,"");
                }
                var particular_text = '';
                if(aData[2] != ''){
                    particular_text = aData[2].replace(/(<([^>]+)>)/ig,"");
                }
                var type_text = '';
                if(aData[3] != ''){
                    type_text = aData[3].replace(/(<([^>]+)>)/ig,"");
                }
                var gr_wt_text = '';
                if(aData[4] != ''){
                    gr_wt_text = aData[4].replace(/(<([^>]+)>)/ig,"");
                }
                var gold_fine_text = '';
                if(aData[9] != ''){
                    gold_fine_text = aData[9].replace(/(<([^>]+)>)/ig,"");
                }
                var silver_fine_text = '';
                if(aData[10] != ''){
                    silver_fine_text = aData[10].replace(/(<([^>]+)>)/ig,"");
                }
                var amount_text = '';
                if(aData[11] != ''){
                    amount_text = aData[11].replace(/(<([^>]+)>)/ig,"");
                }
                var row_unique_text = date_text + particular_text + type_text + gold_fine_text + silver_fine_text + amount_text;
                $nRow.attr("data-row_particular",row_unique_text);
                if(jQuery.inArray(row_unique_text,selected_rows) !== -1) {
                    $nRow.addClass('selected');
                }
                return nRow;
            },
        });
        
        $('#ledger_table tbody').on( 'click', 'tr', function () {
            if($(this).hasClass('selected') == false) {
                console.log($(this).attr('data-row_particular'));
                selected_rows.push($(this).attr('data-row_particular'));
            } else {
                remove_selected_rows(selected_rows,$(this).attr('data-row_particular'));
            }
            $(this).toggleClass('selected');
        } );
    
        $(document).on('click', '#search', function () {
            $('#ajax-loader').show();
            ledger_table.draw();
        });
        
        $('.buttons-pdf.buttons-html5').attr('style', 'background: #d9edf7 !important; color: #3c8dbc; border: 1px solid #3c8dbc;');

    });
    
    function remove_selected_rows(array, value) {
        var i = 0;
        while (i < array.length) {
            if(array[i] === value) {
                array.splice(i, 1);
            } else {
                ++i;
            }
        }
        return array;
    }
    
</script>
