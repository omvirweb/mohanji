<?php // $this->load->view('success_false_notify');       ?>
<style>
    .reminder_class > a{
        color: red !important;
    }
</style>
<div class="content-wrapper">   
    <section class="content-header">
        <h1>
            Outstanding
            <button class="btn btn-primary btn-sm pull-right" id="send">Send</button>
            <?php /*<span class="pull-right" style="margin-right: 20px;">
                <div class="form-group">
                    <label for="checkbox_all_whatsapp_sms" class="col-sm-12 input-sm text-green" style="font-size: 18px; line-height: 25px;">
                        <input type="checkbox" name="" id="checkbox_all_whatsapp_sms" class="" checked="" style="width: 20px; height: 20px;">  &nbsp; Send<img src="<?php echo base_url(); ?>assets/dist/img/whatsapp_icon.png" style="width:25px;" >
                    </label>
                </div>
            </span>*/ ?>
            <span class="pull-right" style="margin-right: 20px;">
                <div class="form-group">
                    <label for="checkbox_all_sms" class="col-sm-12 input-sm" style="font-size: 18px; line-height: 25px;">
                        <input type="checkbox" name="" id="checkbox_all_sms" class="" checked="" style="width: 20px; height: 20px;">  &nbsp; Send SMS
                    </label>
                </div>
            </span>
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
                                    <div class="col-md-2">
                                        <label>Account Group</label>
                                        <select name="account_group_id" id="account_group_id" class="form-control select2">
                                            <option value="0">All</option>
                                            <?php if(!empty($account_groups)){
                                            foreach ($account_groups as $grp){?>
                                                <option value="<?php echo $grp->account_group_id?>" 
                                                    <?=!empty($account_group_id) && $account_group_id == $grp->account_group_id?'selected':''?>>
                                                    <?php echo $grp->account_group_name; ?>
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
                                                <th style="text-align: center;">SMS</th>
                                                <th style="text-align: center;">WhatsApp</th>
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
<div id="myModal" class="modal fade" role="dialog" style="width:100%">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Reminder</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="r_account_id" id="r_account_id">
                <div class="col-md-6">
                    <label>Reminder Date<span class="required-sign">&nbsp;*</span></label>
                    <input type="text" name="reminder_date" id="datepicker2" class="form-control" value="<?php echo date('d-m-Y', strtotime("+1 day"));?>">
                </div>
                <div class="col-md-6">
                    <label>Debit/Credit<span class="required-sign">&nbsp;*</span></label>
                    <select name="debit_credit" id="debit_credit" class="form-control">
                        <option value="1">Debit</option>
                        <option value="2">Credit</option>
                    </select>
                </div>
                <div class="clearfix"></div><br/>
                <div class="col-md-4">
                    <label>Gold</label>
                    <input type="text" name="rem_gold" id="rem_gold" class="form-control num_only">
                </div>
                <div class="col-md-4">
                    <label>Silver</label>
                    <input type="text" name="rem_silver" id="rem_silver" class="form-control num_only">
                </div>
                <div class="col-md-4">
                    <label>Amount</label>
                    <input type="text" name="rem_amount" id="rem_amount" class="form-control num_only">
                </div>
                <div class="col-md-6">
                    <label>Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control"></textarea>
                </div>
                <div class="clearfix"></div><br/>
                <div class="col-md-2">
                    <button name="" id="save_reminder" class="btn btn-primary btn-sm"> Save</button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right: 15px;">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var table = '';
    $(document).ready(function () {
        var selected_rows = [];
        $('#ajax-loader').show();
        $('.select2').select2();
        $('#debit_credit').select2({width:'100%'});
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_with_number_select2_source') ?>");
        <?php if (!empty($account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' .$account_id) ?>");
        <?php } ?>

        $(document).on('change', '#account_group_id', function (){
            var account_group_id = $('#account_group_id').val();
            initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_with_number_select2_source') ?>/" + account_group_id);
        });

        $('.account_address').tooltip('fixTitle');

        table = $('#outstanding_table').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                download: 'open',
                text: 'Outstanding',
                orientation: 'landscape',
//                footer: true,
                title: function () { return ('Outstanding_<?php echo date('Y_m_d_H_i_s'); ?>') },
                exportOptions: {
//                    stripNewlines: false,
//                    format : {
//                        footer : function (data, column, row){
//                            console.log(data.replace(/<br\s*[\/]?>/gi,'\n'));
//                            return data.replace(/<br\s*[\/]?>/gi,'\n');
//                        }
//                    },
                    <?php if(isset($display_net_amount_in_outstanding) && $display_net_amount_in_outstanding == '1'){ ?>
                        columns: [ 2, 4, 5, 6, 7, 8, 9, 10],
                    <?php } else { ?>
                        columns: [ 2, 4, 5, 6, 7, 8, 9],
                    <?php } ?>
                },
            }],
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
                "url": "<?php echo site_url('reports_new_sp3/outstanding_datatable') ?>",
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
                {"orderable": false, "targets": [0,1]},
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
            },
            "fnRowCallback": function (nRow, aData) {
                var api = this.api(), data;
                var $nRow = $(nRow);
                var account_text = '';
                if(aData[2] != ''){
                    account_text = aData[2].replace(/(<([^>]+)>)/ig,"");
                }
                var date_text = '';
                if(aData[6] != ''){
                    date_text = aData[6].replace(/(<([^>]+)>)/ig,"");
                }
                var gold_fine_text = '';
                if(aData[7] != ''){
                    gold_fine_text = aData[7].replace(/(<([^>]+)>)/ig,"");
                }
                var row_unique_text = account_text + date_text + gold_fine_text;
                $nRow.attr("data-row_particular",row_unique_text);
                if(jQuery.inArray(row_unique_text,selected_rows) !== -1) {
                    $nRow.addClass('selected');
                }
                return nRow;
            },
        });
        table.columns( [4] ).visible( false );
        table.columns( [5] ).visible( false );
        <?php if(isset($display_net_amount_in_outstanding) && $display_net_amount_in_outstanding == '1'){ } else { ?>
            table.columns( [10] ).visible( false );
        <?php } ?>
        $('.dt-button.buttons-excel').css('border', 'none');
        $('.dt-button.buttons-excel').html('<img src="<?php echo base_url(); ?>assets/dist/img/excel_icon.png" style="width:25px;" alt="Excel" title="Excel" >');

        $('#outstanding_table tbody').on( 'click', 'tr', function () {
            if($(this).hasClass('selected') == false) {
                console.log($(this).attr('data-row_particular'));
                selected_rows.push($(this).attr('data-row_particular'));
            } else {
                remove_selected_rows(selected_rows,$(this).attr('data-row_particular'));
            }
            $(this).toggleClass('selected');
        } );
        
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

        account = [];
        $(document).on("click", "#send", function () {
            var pre_transaction = '';
            var pre_our_bank = '';
            $('input[name="send_sms[]"]').map(function () {
                if ($(this).prop('checked')) {
                    account.push({
                        id: $(this).data('acc_id'),
                        bal_date: $(this).data('bal_date'),
                        gold: $(this).data('gold'),
                        silver: $(this).data('silver'),
                        amount: $(this).data('amount'),
                    });
                }
            }).get();
            if(account != ''){
                var accounts = JSON.stringify(account);
                $("#ajax-loader").show();
                $.ajax({
                    url: '<?php echo base_url('reports/send_sms'); ?>',
                    type: 'POST',
                    data : {accounts :accounts, message_type : 'sms'},
                    async: false,
                    success : function(response){
                        var json = $.parseJSON(response);
                        account = [];
                        $("#ajax-loader").hide();
                        if (json['success'] == 'sent') {
                            show_notify('Sms Sent Successfully!', true);
                        }
                    }
                });
            }
            else {
                show_notify('Please Select At Least One Account To Send SMS!', false);
            }
        });
        
        $(document).on("click", ".reminder", function () {
            $('#myModal').modal('show');
            $('#r_account_id').val($(this).data('account_id'));
            var n_amt = $(this).data('amount');
            if(n_amt > 0){
                $("#debit_credit").val('2').trigger("change");
            } else {
                $("#debit_credit").val('1').trigger("change");
            }
        });
        
        $(document).on("click", "#save_reminder", function () {
            if ($.trim($("#datepicker2").val()) == '') {
                show_notify('Please enter Reminder Date.', false);
                $("#datepicker2").focus();
                return false;
            }
            if ($.trim($("#rem_amount").val()) == '' && $.trim($("#rem_gold").val()) == '' && $.trim($("#rem_silver").val()) == '') {
                show_notify('Please enter any one Gold/Silver/Amount !', false);
                $("#rem_amount").focus();
                return false;
            }
            $.ajax({
                url: '<?php echo base_url('reports/save_reminder'); ?>',
                type: 'POST',
                data : {
                    account_id :$("#r_account_id").val(),
                    date : $("#datepicker2").val(),
                    debit_credit : $("#debit_credit").val(),
                    amount : $("#rem_amount").val(),
                    gold : $("#rem_gold").val(),
                    silver : $("#rem_silver").val(),
                    remarks : $("#remarks").val()
                },
                async: false,
                success : function(response){
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        $("#r_account_id").val('');
                        $("#datepicker2").val(json['date']);
                        $("#rem_amount").val('');
                        $("#rem_gold").val('');
                        $("#rem_silver").val('');
                        $("#remarks").val('');
                        $('#myModal').modal('hide');
                        show_notify('Reminder Added Successfully!', true);
                        table.draw();
                    } else {
                        show_notify('Something went wrong !', false);
                    }
                }
            });
        });
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


