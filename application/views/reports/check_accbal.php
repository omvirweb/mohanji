<?php // $this->load->view('success_false_notify');       ?>
<div class="content-wrapper">   
    <section class="content-header">
        <h1>Check Account Balance</h1>
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
                                    <div class="col-md-2">
                                        <label>Account Group</label>
                                        <select name="account_group_id" id="account_group_id" class="form-control select2">
                                            <option value="0">All</option>
                                            <?php if(!empty($account_groups)){
                                            foreach ($account_groups as $grp){?>
                                                <option value="<?php echo $grp['account_group_id']?>" 
                                                    <?=!empty($account_group_id) && $account_group_id == $grp['account_group_id']?'selected':''?>>
                                                    <?php echo $grp['account_group_name']; ?>
                                                </option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Account</label>
                                        <select name="account_id" id="account_id" class="form-control select2"></select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Date</label>
                                        <input type="text" name="upto_balance_date" id="datepicker1" class="from_date form-control" value="<?php echo date('d-m-Y');?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Credit Limit</label>
                                        <select name="credit_limit" id="credit_limit" class="form-control select2">
                                            <option value="1">All</option>
                                            <option value="2">In Limit</option>
                                            <option value="3">Out Of Credit Limit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                    </div>
                                    <div class="col-md-2">
                                        <label><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></label><br />
                                        <label>Gold Rate : <?php echo $gold_rate; ?></label><br />
                                        <label>Silver Rate : <?php echo $silver_rate; ?></label><br />
                                    </div><div class="clearfix"></div><br />
                                    <table id="outstanding_table" class="table row-border table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">No.</th>
                                                <th style="text-align: center;">Checked Status</th>
                                                <th>Party Name</th>
                                                <th>Mobile</th>
                                                <th>Mobile</th>
                                                <th>Address</th>
                                                <th>Bal.Date</th>
                                                <th class="text-right ">Gold Fine</th>
                                                <th class="text-right">Silver Fine</th>
                                                <th class="text-right">Amount</th>
                                                <th class="text-right">Net Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id="" style="font-weight: bold; font-size: 16px;">Cr Total</th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id="foot_total_credit_gold_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="foot_total_credit_silver_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="foot_total_credit_amount" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="total_credit_net_amount" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                            </tr>
                                            <tr>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id="" style="font-weight: bold; font-size: 16px;">Dr Total</th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id="foot_total_debit_gold_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="foot_total_debit_silver_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="foot_total_debit_amount" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="total_debit_net_amount" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                            </tr>
                                            <tr>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id="" style="font-weight: bold; font-size: 16px;">Total</th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id=""></th>
                                                <th id="foot_total_gold_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="foot_total_silver_fine" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="foot_total_amount" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
                                                <th id="total_net_amount" style="font-weight: bold; font-size: 16px; text-align: right;"></th>
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
<input type="hidden" id="foot_total_gold_fine1">
<input type="hidden" id="foot_total_silver_fine1">
<input type="hidden" id="foot_total_amount1">
<input type="hidden" id="total_net_amount1">
<input type="hidden" id="foot_total_credit_gold_fine1">
<input type="hidden" id="foot_total_credit_silver_fine1">
<input type="hidden" id="foot_total_credit_amount1">
<input type="hidden" id="total_credit_net_amount1">
<input type="hidden" id="foot_total_debit_gold_fine1">
<input type="hidden" id="foot_total_debit_silver_fine1">
<input type="hidden" id="foot_total_debit_amount1">
<input type="hidden" id="total_debit_net_amount1">
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('.select2').select2();
        
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_with_number_select2_source') ?>");
        <?php if (!empty($account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' .$account_id) ?>");
        <?php } ?>

        $(document).on('change', '#account_group_id', function (){
            var account_group_id = $('#account_group_id').val();
            initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_with_number_select2_source') ?>/" + account_group_id);
        });
        
        table = $('#outstanding_table').DataTable({
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
                "url": "<?php echo site_url('reports/outstanding_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.upto_balance_date = $('#datepicker1').val();
                    d.credit_limit = $('#credit_limit').val();
                    d.account_group_id = $('#account_group_id').val();
                    d.account_id = $('#account_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
                "dataSrc": function ( jsondata ) {
                    $.each(jsondata.data, function (index, value) {
                        var account_name = value[2];
                        var account_name_href = account_name.split('=');
                        account_name_href = account_name_href[2].split('"');
                        var row_account_id = account_name_href[0];
                        console.log(row_account_id);
                        jsondata.data[index][0] = row_account_id;
                        jsondata.data[index][2] = jsondata.data[index][2];
                        
                        $.ajax({
                            url: "<?php echo base_url('check_accbal/get_account_balance'); ?>/" + row_account_id,
                            type: "GET",
                            async: false,
                            data: "",
                            success: function (response) {
                                var json = $.parseJSON(response);
                                var gold_fine = jsondata.data[index][7];
                                gold_fine = gold_fine.split(' ');
                                var silver_fine = jsondata.data[index][8];
                                silver_fine = silver_fine.split(' ');
                                var amount = jsondata.data[index][9];
                                amount = amount.split(' ');
                                var checked_icon = '';
                                if(parseFloat(json.gold_fine) == parseFloat(gold_fine)){
                                    checked_icon += '<i class="fa fa-check text-success"></i> ';
                                } else {
                                    checked_icon += '<i class="fa fa-close text-danger"></i> danger ';
                                }
                                if(parseFloat(json.silver_fine) == parseFloat(silver_fine)){
                                    checked_icon += '<i class="fa fa-check text-success"></i> ';
                                } else {
                                    checked_icon += '<i class="fa fa-close text-danger"></i> danger ';
                                }
                                if(parseFloat(json.amount) == parseFloat(amount)){
                                    checked_icon += '<i class="fa fa-check text-success"></i> ';
                                } else {
                                    checked_icon += '<i class="fa fa-close text-danger"></i> danger ';
                                }
                                jsondata.data[index][1] = checked_icon;
                            },
                        });
                        
//                        jsondata.data[index] = jsondata.data[index] 
                    });
                    if(jsondata.foot_total_gold_fine){
                        $('#foot_total_gold_fine1').val(jsondata.foot_total_gold_fine);
                    } else {
                        $('#foot_total_gold_fine1').val('');
                    }
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
                    }
                    
                    if(jsondata.foot_total_credit_gold_fine){
                        $('#foot_total_credit_gold_fine1').val(jsondata.foot_total_credit_gold_fine);
                    } else {
                        $('#foot_total_credit_gold_fine1').val('');
                    }
                    if(jsondata.foot_total_credit_silver_fine){
                        $('#foot_total_credit_silver_fine1').val(jsondata.foot_total_credit_silver_fine);
                    } else {
                        $('#foot_total_credit_silver_fine1').val('');
                    }
                    if(jsondata.foot_total_credit_amount){
                        $('#foot_total_credit_amount1').val(jsondata.foot_total_credit_amount);
                    } else {
                        $('#foot_total_credit_amount1').val('');
                    }
                    if(jsondata.total_credit_net_amount){
                        $('#total_credit_net_amount1').val(jsondata.total_credit_net_amount);
                    } else {
                        $('#total_credit_net_amount1').val('');
                    }
                    
                    if(jsondata.foot_total_debit_gold_fine){
                        $('#foot_total_debit_gold_fine1').val(jsondata.foot_total_debit_gold_fine);
                    } else {
                        $('#foot_total_debit_gold_fine1').val('');
                    }
                    if(jsondata.foot_total_debit_silver_fine){
                        $('#foot_total_debit_silver_fine1').val(jsondata.foot_total_debit_silver_fine);
                    } else {
                        $('#foot_total_debit_silver_fine1').val('');
                    }
                    if(jsondata.foot_total_debit_amount){
                        $('#foot_total_debit_amount1').val(jsondata.foot_total_debit_amount);
                    } else {
                        $('#foot_total_debit_amount1').val('');
                    }
                    if(jsondata.total_debit_net_amount){
                        $('#total_debit_net_amount1').val(jsondata.total_debit_net_amount);
                    } else {
                        $('#total_debit_net_amount1').val('');
                    }
                    return jsondata.data;
                },
            },
            "columnDefs": [
                {"className": "dt-right", "targets": []},
                {"className": "text-right", "targets": [7,8,9,10]},
                {"orderable": false, "targets": [0,1,2,3]},
                {"className": "text-nowrap", "targets": [6]},
            ],
            "drawCallback": function( settings ) {
                $('input[type="checkbox"].send_sms').css("height", "20px");
                $('input[type="checkbox"].send_sms').css("width", "20px");
                $('input[type="checkbox"].send_whatsapp_sms').css("height", "20px");
                $('input[type="checkbox"].send_whatsapp_sms').css("width", "20px");
            },
            "footerCallback": function ( row, data, start, end, display ) {
                $('tfoot tr th#foot_total_gold_fine').html($('#foot_total_gold_fine1').val());
                $('tfoot tr th#foot_total_silver_fine').html($('#foot_total_silver_fine1').val());
                $('tfoot tr th#foot_total_amount').html($('#foot_total_amount1').val());
                $('tfoot tr th#total_net_amount').html($('#total_net_amount1').val());
                $('tfoot tr th#foot_total_credit_gold_fine').html($('#foot_total_credit_gold_fine1').val());
                $('tfoot tr th#foot_total_credit_silver_fine').html($('#foot_total_credit_silver_fine1').val());
                $('tfoot tr th#foot_total_credit_amount').html($('#foot_total_credit_amount1').val());
                $('tfoot tr th#total_credit_net_amount').html($('#total_credit_net_amount1').val());
                $('tfoot tr th#foot_total_debit_gold_fine').html($('#foot_total_debit_gold_fine1').val());
                $('tfoot tr th#foot_total_debit_silver_fine').html($('#foot_total_debit_silver_fine1').val());
                $('tfoot tr th#foot_total_debit_amount').html($('#foot_total_debit_amount1').val());
                $('tfoot tr th#total_debit_net_amount').html($('#total_debit_net_amount1').val());
            }
        });
        table.columns( [4] ).visible( false );
        table.columns( [5] ).visible( false );
        $(document).on('click', '#search', function (){
            $('#ajax-loader').show();
            table.draw();
        });
        
        $('#checkbox_all_sms').click(function () {
            $('.send_sms').not(this).prop('checked', this.checked);
        });
        $('#checkbox_all_whatsapp_sms').click(function () {
            $('.send_whatsapp_sms').not(this).prop('checked', this.checked);
        });
    });
</script>


