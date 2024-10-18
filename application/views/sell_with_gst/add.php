<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" method="post" id="save_sell_entry" novalidate enctype="multipart/form-data">
        <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
            <input type="hidden" name="sell_id" class="sell_id" value="<?= $sell_data->sell_id ?>">
        <?php } ?>
        <input type="hidden" name="grwt_total" id="grwt_total" class="grwt_total" value="0">
        <input type="hidden" name="gold_fine_total" id="gold_fine_total" class="gold_fine_total" value="0">
        <input type="hidden" name="silver_fine_total" id="silver_fine_total" class="silver_fine_total" value="0">
        <input type="hidden" name="amount_total" id="amount_total" class="amount_total" value="0">
        <input type="hidden" name="payment_receipt_id" id="payment_receipt_id" class="payment_receipt_id" value="0">
        <input type="hidden" id="total_grwt_sell" value=""/>
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <h1>
                Add Entry with GST
                <?php
                $isEdit = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "add");
                $allow_change_date = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow_change_date");
                ?>
                <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { } else {
                    if ($isAdd) {
                        $btn_disable = null;
                    } else {
                        $btn_disable = 'disabled';
                    }
                } ?> 
                <?php if(!isset($sell_data->account_id) || (isset($sell_data->account_id) && $sell_data->account_id != ADJUST_EXPENSE_ACCOUNT_ID)){ ?>
                    <?php if(!isset($sell_data->audit_status) || (isset($sell_data->audit_status) && $sell_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
                        <button type="submit" class="btn btn-primary pull-right module_save_btn btn-sm" ><?= isset($sell_data->sell_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                    <?php } ?>
                <?php } ?>
                <a href="<?= base_url('sell_with_gst/sell_with_gst_list') ?>" class="btn btn-primary pull-right btn-sm" style="margin: 5px;" <?php echo isset($sell_data->sell_id) ? '' : $btn_disable; ?>>Entry with GST List</a>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <?php if ($isAdd || $isEdit) { ?>
                        <!-- Horizontal Form -->
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="clearfix"></div>
                                        <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
                                        <div class="col-md-1">
                                            <label for="sell_no">No.</label>
                                            <input type="text" name="sell_no" id="sell_no" class="form-control" readonly value="<?= (isset($sell_data->sell_no) && !empty($sell_data->sell_no)) ? $sell_data->sell_no : ''; ?>">
                                        </div>
                                        <?php } ?>
                                        <div class="col-md-3 pr0">
                                            <label for="account_id">Account Name <span class="required-sign">&nbsp;*</span></label>
                                            <select name="account_id" id="account_id" class="form-control select2" ></select>
                                        </div> 
                                        <div class="col-md-2 pr0">
                                            <label for="department_id">Department <span class="required-sign">&nbsp;*</span></label>
                                            <select name="department_id" id="department_id" class="form-control select2" ></select>
                                        </div>
                                        <div class="col-md-2 pr0">
                                            <label for="date">Date<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="sell_date" id="datepicker2" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control input-datepicker" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?php if (isset($sell_data->sell_date) && !empty($sell_data->sell_date)) { echo date('d-m-Y', strtotime($sell_data->sell_date)); } else { echo date('d-m-Y'); } ?>">
                                        </div>
                                        <div class="col-md-3 pr0">
                                            <label for="sell_remark">Remark</label>
                                            <input type="text" name="sell_remark" id="sell_remark" class="form-control" value="<?php if (isset($sell_data->sell_remark) && !empty($sell_data->sell_remark)) { echo $sell_data->sell_remark;  } else { echo ''; } ?>">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-2 pr0">
                                            <label for="ship_to_name">Ship To Name</label>
                                            <input type="text" name="ship_to_name" id="ship_to_name" class="form-control" value="<?php if (isset($sell_data->ship_to_name) && !empty($sell_data->ship_to_name)) { echo $sell_data->ship_to_name; } ?>">
                                        </div>
                                        <div class="col-md-5 pr0 mb10">
                                            <label for="ship_to_address">Ship To Address</label>
                                            <input type="text" name="ship_to_address" id="ship_to_address" class="form-control" value="<?php if (isset($sell_data->ship_to_address) && !empty($sell_data->ship_to_address)) { echo $sell_data->ship_to_address;  } ?>">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="line_item_form item_fields_div" style="height: 160px;">
                                            <input type="hidden" name="line_items_index" id="line_items_index" />
                                            <input type="hidden" name="line_items_data[sell_item_delete]" id="sell_item_delete" value="allow" />
                                                <?php if (isset($sell_items_with_gst_data) && !empty($sell_items_with_gst_data)) { ?>
                                                    <input type="hidden" name="line_items_data[sell_item_id]" id="sell_item_id" value="0"/>
                                                <?php } ?>
                                            <h4 class="col-md-12">
                                                Line Item &nbsp;&nbsp;&nbsp;
                                            </h4>
                                            <div class="col-md-1 pr0">
                                                <label for="sell_type_id">Type<span class="required-sign">&nbsp;*</span></label>
                                                <select name="line_items_data[type]" class="form-control sell_type_id select2" id="sell_type_id"></select>
                                            </div>
                                            <div class="col-md-2 pr0">
                                                <label for="item_id">Category<span class="required-sign">&nbsp;*</span></label>
                                                <select name="line_items_data[category_id]" class="form-control category_id" id="category_id"></select>
                                            </div>
                                            <div class="col-md-2 pr0">
                                                <label for="item_id">Item<span class="required-sign">&nbsp;*</span></label>
                                                <select name="line_items_data[item_id]" class="form-control item_id select2" id="item_id"></select>
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="hsn_code">HSN Code</label>
                                                <input type="text" name="line_items_data[hsn_code]" class="form-control" id="hsn_code" readonly="">
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="grwt">Gr.Wt.<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="line_items_data[grwt]" class="form-control grwt num_only" id="grwt">
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="spi_rate">Rate<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="line_items_data[spi_rate]" class="form-control less num_only" id="spi_rate">
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="rate_per_1_gram">Rate On</label>
                                                <input type="text" name="line_items_data[rate_per_1_gram]" class="form-control num_only" id="rate_per_1_gram" value="1" readonly="">
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="gst_rate">GST Rate</label>
                                                <input type="text" name="line_items_data[gst_rate]" class="form-control num_only" id="gst_rate" readonly="">
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="tax">TAX</label>
                                                <input type="text" name="line_items_data[tax]" class="form-control num_only" id="tax" readonly="">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="unit">Amount<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="line_items_data[amount]" class="form-control amount num_only" readonly="" id="amount">
                                            </div>
                                            <div class="col-md-1">
                                                <br /><input type="button" id="add_lineitem" class="btn btn-info btn-sm add_lineitem pull-right" value="Add Item" style="margin:7px; margin-left: 30%"/>
                                            </div>
                                            <div class="clearfix"></div>
                                            <?php /*<div class="col-md-7">
                                                <button type="button" id="payment_receipt" class="btn btn-instagram module_save_btn pull-right" style="margin:7px;">Payment Receipt</button>
                                            </div> */ ?>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-12">
                                            <table style="" class="table custom-table border item-table">
                                                <thead>
                                                    <tr>
                                                        <th width="4%">Action</th>
                                                        <th width="4%">Type</th>
                                                        <th width="12%">Category</th>
                                                        <th width="9%">Description</th>
                                                        <th width="9%" class="text-right">Gr.Wt.</th>
                                                        <th width="9%" class="text-right">Rate / On</th>
                                                        <th width="9%" class="text-right">TAX</th>
                                                        <th width="9%" class="text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lineitem_list"></tbody>
                                                <tfoot id="lineitem_foot_list">
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th class="text-right">Total</th>
                                                        <th class="text-right"><span id="total_grwt"></span></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th class="text-right"><span id="total_amount"></span></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <table style="" class="table custom-table pay_rec_table" hidden="">
                                                <tbody id="pop_up_pay_rec_list"></tbody>
                                            </table>
                                            <table style="" class="table custom-table item-table">
                                                <tbody id="pop_up_total_list">
                                                    <tr>
                                                        <th width="4%"></th>
                                                        <th width="4%"></th>
                                                        <th width="12%"></th>
                                                        <th width="9%" class="text-right">TCS % : </th>
                                                        <th width="9%" class="text-right"><span id=""></span></th>
                                                        <th width="9%"></th>
                                                        <th width="9%"></th>
                                                        <th width="9%" class="text-right">
                                                            <input type="text" name="tcs_per" id="tcs_per" class="form-control input-sm pull-right num_only" value="<?php if (isset($sell_data->tcs_per) && !empty($sell_data->tcs_per)) { echo $sell_data->tcs_per; } else { echo '0';} ?>" style="width: 100px;">
                                                            <input type="text" name="tcs_amount" id="tcs_amount" class="form-control input-sm pull-right num_only" value="<?php if (isset($sell_data->tcs_amount) && !empty($sell_data->tcs_amount)) { echo $sell_data->tcs_amount; } else { echo '0';} ?>" readonly="" style="width: 100px;">
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th width="4%"></th>
                                                        <th width="4%"></th>
                                                        <th width="12%"></th>
                                                        <th width="9%" class="text-right">Bill Balance : </th>
                                                        <th width="9%" class="text-right"><span id="bill_grwt"></span></th>
                                                        <th width="9%"></th>
                                                        <th width="9%"></th>
                                                        <th width="9%" class="text-right"><span id="bill_amount"></span></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th class="text-right"><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></th>
                                                        <th class="text-right"><?php echo $gold_rate; ?></th>
                                                        <th class="text-right"><?php echo $silver_rate; ?></th>
                                                        <th class="text-right"></th>
                                                        <th></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
                                            <div class="created_updated_info" style="margin-left: 10px;">
                                                Created by : <?php echo isset($sell_data->created_by_name) ? $sell_data->created_by_name : ''; ?>
                                                @ <?php echo isset($sell_data->created_at) ? date('d-m-Y h:i A', strtotime($sell_data->created_at)) : ''; ?><br/>
                                                Updated by : <?php echo isset($sell_data->updated_by_name) ? $sell_data->updated_by_name : ''; ?>
                                                @ <?php echo isset($sell_data->updated_at) ? date('d-m-Y h:i A', strtotime($sell_data->updated_at)) : ''; ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="payment_receipt_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color:#f1e8e1;">
                    <div class="modal-header">
                        <div class="col-md-6">
                            <h4 class="modal-title" id="myModalLabel">Payment Receipt</h4>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <label><input type="radio" name="payment_receipt" class="iradio_minimal-blue" value="1"> Payment</label> &nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="payment_receipt" class="iradio_minimal-blue" value="2"> Receipt</label>
                        </div>
                    </div>
                    <div class="modal-body edit-content">
                        <div class="col-md-12">
                            <div class="pay_rec_form pay_rec_div">
                                <?php if (isset($pay_rec_data)) { ?>
                                    <input type="hidden" name="pay_rec_data[pay_rec_id]" id="pay_rec_id" />
                                <?php } ?>
                                <input type="hidden" name="pay_rec_index" id="pay_rec_index" />
                                <div class="col-md-4">
                                    <select name="pay_rec_data[cash_cheque]" class="form-control select2 cash_cheque" id="cash_cheque">
                                        <option value="1" selected="">Cash</option>
                                        <option value="2">Cheque</option>
                                    </select>
                                </div>
                                <div class="col-md-8 banks">
                                    <label class="col-md-2">Bank<span class="required-sign">&nbsp;*</span></label>
                                    <div class="col-md-10">
                                        <select name="pay_rec_data[bank_id]" id="bank_id" class="form-control select2"></select>
                                    </div>
                                </div>
                                <div class="clearfix"></div><br />
                                <div class="col-md-4">
                                    <label>Amount<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="pay_rec_data[amount]" id="pr_amount" class="form-control num_only" value="">
                                </div>
                                <div class="col-md-7 pull-right">
                                    <label>Narration</label>
                                    <textarea id="narration" name="pay_rec_data[narration]" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br />
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="payment_receipt_button">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var module_submit_flag = 0;
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    var zero_value = 0;
    var total_amount = 0;
    var gold_amount_count = 0;
    var gold_count = 0;
    var silver_count = 0;
    var silver_amount_count = 0;
    var old_amount_val = 0;
    var old_gold_fine_val = 0;
    var old_silver_fine_val = 0;
    var credit_limit = 0;
    var app_net_amt = 0;
    var tcs_per = 0;
    var tcs_amount = 0;
    var total_grwt = 0;
    var total_li_amount = 0;

    var edit_pay_rec_inc = 0;
    var sell_index = '';

    var lineitem_objectdata = [];
    var pay_rec_objectdata = [];
    var gold_array_for_edit = [];
    var silver_array_for_edit = [];
    var items = [];
<?php if (isset($sell_items_with_gst_data)) { ?>
        var li_lineitem_objectdata = [<?php echo $sell_items_with_gst_data; ?>];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
<?php }
if (isset($pay_rec_data) && !empty($pay_rec_data)) {
    ?>
        var pay_lineitem_objectdata = [<?php echo $pay_rec_data; ?>];
        if (pay_lineitem_objectdata != '') {
            $.each(pay_lineitem_objectdata, function (index, value) {
                pay_rec_objectdata.push(value);
            });
        }
        $('.pay_rec_table').show();
<?php } ?>
    var pts_lineitem_objectdata = [];
    display_pay_rec_html(pay_rec_objectdata);

    $(document).ready(function () {
        initAjaxSelect2($("#account_id"), "<?= base_url('app/party_name_with_number_select2_source/1') ?>");
<?php if (isset($sell_data->account_id) && !empty($sell_data->account_id)) { ?>
            get_bill_balance(<?php echo $sell_data->account_id; ?>);
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . $sell_data->account_id) ?>");
<?php } else { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . CASE_CUSTOMER_ACCOUNT_ID) ?>");
<?php } ?>
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
<?php if (isset($sell_data->department_id) && !empty($sell_data->department_id)) { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $sell_data->department_id) ?>");
<?php } else { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['default_department_id']) ?>");
<?php } ?>

        initAjaxSelect2($("#category_id"), "<?= base_url('app/category_for_gold_and_silver_select2_source') ?>");
        initAjaxSelect2($("#sell_type_id"), "<?= base_url('app/sell_type_select2_source') ?>");

        $('.banks').hide();
        $(".cash_cheque").select2();
        $('#payment_receipt_data').hide();
        
        if ($('#datepicker2').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker2').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: "today",
                maxDate: 0,
            })
        }
        
        var account_id = $('#account_id').val();
        get_bill_balance(account_id);

        $(document).on('change', '#account_id', function () {
            var account_id = $('#account_id').val();
            if (account_id != '' && account_id != null) {
                get_bill_balance(account_id);
            }
        });

        $(document).on('click', '#payment_receipt', function () {
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                return false;
            }
            $('#payment_receipt_model').modal('show');
        });

        $(document).on('change', '#department_id', function () {
            $('#sell_type_id').val(null).trigger("change");
        });

        $(document).on('change', '#sell_type_id', function () {
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                $('#sell_type_id').val(null).trigger('change.select2');
                return false;
            }
            var sell_type_id = $('#sell_type_id').val();
            if (sell_type_id == '1') {
                $(".item_fields_div").css("background-color", "#c4ecc4");
            } else if (sell_type_id == '2') {
                $(".item_fields_div").css("background-color", "#fc949b");
            } else {
                $(".item_fields_div").css("background-color", "#ffffff");
            }

            $('#category_id').val(null).trigger('change');
            $('#item_id').val(null).trigger('change');
            $("#grwt").val('');
            $("#spi_rate").val('');
            $("#rate_per_1_gram").val('1');
            $("#tax").val('');
            $("#amount").val('');
        });

        $(document).on('change', '#category_id', function () {
            $('#item_id').val(null).trigger('change');
            $('#hsn_code').val('');
            $("#grwt").val('');
            $("#spi_rate").val('');
            $("#rate_per_1_gram").val('1');
            $('#gst_rate').val('');
            $("#tax").val('');
            $("#amount").val('');
            var category_id = $('#category_id').val();
            var sell_type_id = $('#sell_type_id').val();
            if (category_id != '' && category_id != null && sell_type_id != '' && sell_type_id != null) {
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_from_select_category_for_sell_select2_source') ?>/" + category_id + '/' + sell_type_id);
            }
            if (category_id != '' && category_id != null) {
                $.ajax({
                    url: "<?php echo base_url('sell_with_gst/get_category_data'); ?>/" + category_id,
                    type: "GET",
                    async: false,
                    data: "",
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $('#hsn_code').val(json.hsn_code);
                        $('#gst_rate').val(json.gst_rate);
                    }
                });
            }
        });

        $(document).on('change', '#item_id', function () {
            $("#grwt").val('');
            $("#spi_rate").val('');
            $("#rate_per_1_gram").val('1');
            $("#tax").val('');
            $("#amount").val('');
            var item_id = $('#item_id').val();
            if (item_id != '' && item_id != null) {
                $.ajax({
                    url: "<?php echo base_url('sell/get_item_data'); ?>/" + item_id,
                    type: "GET",
                    async: false,
                    data: "",
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $('#rate_per_1_gram').val(json.rate_on);
                    }
                });
            }
        });

        $(document).on('keyup change', '#grwt, #spi_rate', function () {
            var grwt = parseFloat($('#grwt').val()) || 0;
            var spi_rate = parseFloat($('#spi_rate').val()) || 0;
            var rate_per_1_gram = parseFloat($('#rate_per_1_gram').val()) || 1;
            var gst_rate = parseFloat($('#gst_rate').val()) || 0;
            var amount = parseFloat(grwt) * parseFloat(spi_rate) / parseFloat(rate_per_1_gram);
            var tax = parseFloat(amount) * parseFloat(gst_rate) / 100;
            tax = tax.toFixed(2);
            $('#tax').val(tax);
            var amount = parseFloat(amount) + parseFloat(tax);
            $('#amount').val(amount.toFixed(2));
        });

//        $(document).on('keyup change', '#grwt, #amount', function () {
//            var grwt = parseFloat($('#grwt').val()) || 0;
//            var amount = parseFloat($('#amount').val()) || 0;
//            var rate_per_1_gram = parseFloat($('#rate_per_1_gram').val()) || 1;
//            var spi_rate = (parseFloat(amount) * parseFloat(rate_per_1_gram) / parseFloat(grwt)) || 0;
//            $('#spi_rate').val(spi_rate.toFixed(2));
//        });

        <?php if (isset($sell_data->tcs_per) && !empty($sell_data->tcs_per)) { ?>
            tcs_per = $('#tcs_per').val() || 0;
            get_bill_total_amount();
        <?php } ?>
        $(document).bind('keyup change', '#tcs_per', function () {
            tcs_per = $('#tcs_per').val() || 0;
            get_bill_total_amount();
        });

        $(document).on('change', '.cash_cheque', function () {
            var cash_cheque = $('.cash_cheque').val();
            if (cash_cheque == 2) {
                $('.banks').show();
                initAjaxSelect2($("#bank_id"), "<?= base_url('app/account_bank_select2_source') ?>");
            } else {
                $("#bank_id").val(null).trigger("change");
                $('.banks').hide();
            }
        });

        $(document).on('click', '#payment_receipt_button', function () {
            if (!$('input[name=payment_receipt]:checked').val()) {
                show_notify('Please Select Payment or Receipt.', false);
                $("#payment_receipt").focus();
                return false;
            }
            if ($.trim($("#cash_cheque").val()) == '') {
                show_notify('Please Select Cash or Cheque.', false);
                $("#cash_cheque").focus();
                return false;
            }
            if ($.trim($("#cash_cheque").val()) == '2') {
                if ($.trim($("#bank_id").val()) == '') {
                    show_notify('Please Select Bank.', false);
                    $("#bank_id").focus();
                    return false;
                }
            }
            if ($.trim($("#pr_amount").val()) == '') {
                show_notify('Please Enter Amount.', false);
                $("#pr_amount").focus();
                return false;
            }

            var key = '';
            var value = '';
            var lineitem = {};
            $('select[name^="pay_rec_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("pay_rec_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('input[name^="pay_rec_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("pay_rec_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('textarea[name^="pay_rec_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("pay_rec_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });

            var payment_receipt = $('input[name=payment_receipt]:checked').val();
            lineitem['payment_receipt'] = payment_receipt;
            if ($.trim($("#cash_cheque").val()) == '2') {
                var bank_data = $('#bank_id').select2('data');
                lineitem['bank_name'] = bank_data[0].text;
            } else {
                lineitem['bank_name'] = '';
            }
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));

            var payment_receipt = $('input[name=payment_receipt]:checked').val();
            var pay_rec_amount = parseFloat($('#pr_amount').val()) || 0;

            var pay_rec_index = $("#pay_rec_index").val();
            if (pay_rec_index != '') {

                var pay_rec_amount_prev = pay_rec_objectdata[pay_rec_index].amount;
                if (pay_rec_objectdata[pay_rec_index].payment_receipt == '1' && payment_receipt == '1') {
                    total_amount = total_amount + parseFloat(pay_rec_amount) - parseFloat(pay_rec_amount_prev);
                } else if (pay_rec_objectdata[pay_rec_index].payment_receipt == '1' && payment_receipt == '2') {
                    total_amount = total_amount - parseFloat(pay_rec_amount) - parseFloat(pay_rec_amount_prev);
                } else if (pay_rec_objectdata[pay_rec_index].payment_receipt == '2' && payment_receipt == '1') {
                    total_amount = total_amount + parseFloat(pay_rec_amount) + parseFloat(pay_rec_amount_prev);
                } else if (pay_rec_objectdata[pay_rec_index].payment_receipt == '2' && payment_receipt == '2') {
                    total_amount = total_amount - parseFloat(pay_rec_amount) + parseFloat(pay_rec_amount_prev);
                }
                pay_rec_objectdata.splice(pay_rec_index, 1, new_lineitem);

            } else {

                if (payment_receipt == '1') {
                    total_amount = total_amount + parseFloat(pay_rec_amount);
                } else if (payment_receipt == '2') {
                    total_amount = total_amount - parseFloat(pay_rec_amount);
                }
                pay_rec_objectdata.push(new_lineitem);
            }

            display_pay_rec_html(pay_rec_objectdata);
            $('#pay_rec_id').val('');
            $("input[name=payment_receipt]").prop("checked", false);
            $('#cash_cheque').val('1').trigger("change");
            $('#bank_id').val(null).trigger("change");
            $("#pr_amount").val('');
            $("#narration").val('');
            $("#pay_rec_index").val('');
            $('#payment_receipt_id').val('1');
            $('#payment_receipt_model').modal('hide');
            $('.pay_rec_table').show();
        });
        
        $('#payment_receipt_model').on('hidden.bs.modal', function () {
            $('#pay_rec_id').val('');
            $("input[name=payment_receipt]").prop("checked", false);
            $('#cash_cheque').val('1').trigger("change");
            $('#bank_id').val(null).trigger("change");
            $("#pr_amount").val('');
            $("#narration").val('');
            $("#pay_rec_index").val('');
            $('#payment_receipt_id').val('1');
        });

        <?php if(!isset($sell_data->audit_status) || (isset($sell_data->audit_status) && $sell_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
            <?php if(!isset($sell_data->account_id) || (isset($sell_data->account_id) && $sell_data->account_id != ADJUST_EXPENSE_ACCOUNT_ID)){ ?>
            $(document).bind("keydown", function (e) {
                if (e.ctrlKey && e.which == 83) {
                    e.preventDefault();
                    if(module_submit_flag == 0 ){
                        $("#save_sell_entry").submit();
                        return false;
                    }
                }
            });
            <?php } ?>
        <?php } ?>

        $(document).on('submit', '#save_sell_entry', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Account Name.', false);
                $("#account_id").select2('open');
                return false;
            }
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                return false;
            }
            var datepicker2 = $("#datepicker2").val();
            if(datepicker2 == '' ){
                show_notify('Please Select Date.', false);
                $("#datepicker2").focus();
                return false;
            }
            if (lineitem_objectdata == '' && pay_rec_objectdata == '') {
                show_notify("Please Add Atlist One Line Item.", false);
                return false;
            }
            var account_id = $('#account_id').val();
            if (account_id == <?php echo CASE_CUSTOMER_ACCOUNT_ID; ?>) {
                var grwt_total = $('#grwt_total').val();
                var amount_total = $('#amount_total').val();
                if (amount_total != 0) {
                    show_notify('Total Must Be 0.', false);
                    return false;
                }
            }
            var is_grater = 0;

            if (app_net_amt > credit_limit) {
                is_grater = 1;
            }
            var postData = new FormData(this);
            if (is_grater == 1) {
//                swal({
//                    title: 'Credit Limit : ' + credit_limit,
//                    type: "warning",
//                    buttons: ["Cancel", "Ok"],
//                    className: "danger_alert"
//                }).then((willSave) => {
//                    if (willSave) {
//                        save_form(postData);
//                    }
//                });
                if (confirm('Credit Limit : ' + credit_limit)) {
                    save_form(postData);
                }
            } else {
                save_form(postData);
            }
            module_submit_flag = 1;
            return false;
        });

        $('#add_lineitem').on('click', function () {
            var sell_type_id = $("#sell_type_id").val();
            if (sell_type_id == '' || sell_type_id == null) {
                $("#sell_type_id").select2('open');
                show_notify("Please select Type!", false);
                return false;
            }
            var category_id = $("#category_id").val();
            if (category_id == '' || category_id == null) {
                $("#category_id").select2('open');
                show_notify("Please select Category!", false);
                return false;
            }
            var item_id = $("#item_id").val();
            if (item_id == '' || item_id == null) {
                $("#item_id").select2('open');
                show_notify("Please select Item!", false);
                return false;
            }
            var grwt = $("#grwt").val();
            if (grwt == '' || grwt == null) {
                $("#grwt").focus();
                show_notify("Please Enter Gr.Wt.!", false);
                return false;
            } else {
                var total_grwt_sell = $('#total_grwt_sell').val();
                <?php if ($without_purchase_sell_allow == '1') { ?>
                    if (total_grwt_sell != '' && total_grwt_sell != null) {
                        var grwt = parseFloat($('#grwt').val()) || 0;
                        grwt = round(grwt, 2).toFixed(3);
                        if (parseFloat(grwt) < parseFloat(total_grwt_sell)) {
                            show_notify("GrWt Should Be Grater Than " + total_grwt_sell, false);
                            $('#grwt').val('');
                            $("#grwt").focus();
                            return false;
                        }
                    }
                <?php } ?>
            }
            var spi_rate = $("#spi_rate").val();
            if (spi_rate == '' || spi_rate == null || spi_rate == '0' || spi_rate == '0.00') {
                $("#spi_rate").focus();
                show_notify("Please Enter rate!", false);
                return false;
            }
            var amount = $("#amount").val();
            if (amount == '' || amount == null || amount == '0' || amount == '0.00') {
                $("#amount").focus();
                show_notify("Please Enter amount!", false);
                return false;
            }

            $("#add_lineitem").attr('disabled', 'disabled');

            var key = '';
            var value = '';
            var lineitem = {};
            var is_validate = '0';
            $('select[name^="line_items_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('input[name^="line_items_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });

            var item_id = $('#item_id').val();
            var category_id = $('#category_id').val();
//            $('select[name^="line_items_data"]').each(function (index) {
//                key = $(this).attr('name');
//                key = key.replace("line_items_data[", "");
//                key = key.replace("]", "");
////                console.log(lineitem_objectdata);
//                $.each(lineitem_objectdata, function (index, value) {
//                    if (value.category_id == category_id && value.item_id == item_id && typeof (value.id) != "undefined" && value.id !== null) {
//                        $('input[name^="line_items_data"]').each(function (index) {
//                            keys = $(this).attr('name');
//                            keys = keys.replace("line_items_data[", "");
//                            keys = keys.replace("]", "");
//                            if (keys == 'id') {
//                                if (value.id != $(this).val()) {
//                                    is_validate = '1';
//                                    show_notify("You cannot Add this Item. This Item has been used!", false);
//                                    return false;
//                                }
//                            }
//                        });
//                    } else if (value.category_id == category_id && value.item_id == item_id) {
//                        if (sell_index !== index) {
//                            is_validate = '1';
//                            show_notify("You cannot Add this Item. This Item has been used!", false);
//                            return false;
//                        }
//                    }
//                });
//                if (is_validate == '1') {
//                    return false;
//                }
//            });
            if (is_validate != '1') {
                var type_data = $('#sell_type_id').select2('data');
                lineitem['type_name'] = type_data[0].text;

                var item_data = $('#category_id').select2('data');
                lineitem['category_name'] = item_data[0].text;
                var item_data = $('#item_id').select2('data');
                lineitem['item_name'] = item_data[0].text;
                lineitem['grwt'] = round(lineitem['grwt'], 2).toFixed(3);
                lineitem['total_grwt_sell'] = $('#total_grwt_sell').val();
                
                if(sell_type_id == 1){
                    var sell_item_id = $("#sell_item_id").val();
                    var department_id = $("#department_id").val();
                    $.ajax({
                        url: "<?php echo base_url('sell/get_item_stock'); ?>/",
                        type: 'POST',
                        async: false,
                        data: {process_id : department_id, category_id : category_id, item_id : item_id, touch_id : '0', sell_item_id : sell_item_id},
                        success: function (response) {
                            var json = $.parseJSON(response);
                            <?php if($without_purchase_sell_allow == '1'){ ?>
                                if(parseFloat(grwt) > parseFloat(json.grwt)){
                                    show_notify('Please Enter GrWt Less than of ' + json.grwt, false);
                                    $("#grwt").focus();
                                    $("#add_lineitem").removeAttr('disabled', 'disabled');
                                    return false;
                                }
                            <?php }  ?>

                            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
                            line_items_index = $("#line_items_index").val();
                            if (line_items_index != '') {
                                lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
                            } else {
                                lineitem_objectdata.push(new_lineitem);
                            }
                            display_lineitem_html(lineitem_objectdata);

                            $('#sell_item_id').val('');
                            $('#sell_type_id').val(null).trigger("change");
                            $("#category_id").val(null).trigger("change");
                            $("#item_id").val(null).trigger("change");
                            $("#grwt").val('');
                            $("#spi_rate").val('');
                            $("#rate_per_1_gram").val('1');
                            $("#tax").val('');
                            $("#amount").val('');
                            $("#line_items_index").val('');
                            $('#sell_type_id').removeAttr('disabled', 'disabled');
                            $('#category_id').removeAttr('disabled', 'disabled');
                            $('#item_id').removeAttr('disabled', 'disabled');
                            sell_index = '';
                        }
                    });
                } else {
                    var new_lineitem = JSON.parse(JSON.stringify(lineitem));
                    line_items_index = $("#line_items_index").val();
                    if (line_items_index != '') {
                        lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
                    } else {
                        lineitem_objectdata.push(new_lineitem);
                    }
                    display_lineitem_html(lineitem_objectdata);

                    $('#sell_item_id').val('');
                    $('#sell_type_id').val(null).trigger("change");
                    $("#category_id").val(null).trigger("change");
                    $("#item_id").val(null).trigger("change");
                    $("#grwt").val('');
                    $("#spi_rate").val('');
                    $("#rate_per_1_gram").val('1');
                    $("#tax").val('');
                    $("#amount").val('');
                    $("#line_items_index").val('');
                    $('#sell_type_id').removeAttr('disabled', 'disabled');
                    $('#category_id').removeAttr('disabled', 'disabled');
                    $('#item_id').removeAttr('disabled', 'disabled');
                }
                $('#total_grwt_sell').val('');
            }
            $(".item_fields_div").css("background-color", "#ffffff");
            $("#add_lineitem").removeAttr('disabled', 'disabled');
        });
    });

    function get_bill_balance(account_id) {
        if (account_id != '' && account_id != null) {
            $.ajax({
                url: "<?= base_url('sell/get_account_old_balance') ?>/" + account_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    <?php if (isset($sell_data) && !empty($sell_data)) { ?>
                        var sell_data_total_grwt = '<?php // echo $sell_data->total_grwt; ?>';
                        var sell_data_total_amount = '<?php echo $sell_data->total_amount; ?>';
                        var gold_fine = parseFloat(json.gold_fine) - 0;
                        var silver_fine = parseFloat(json.silver_fine) - 0;
                        var amount = json.amount - sell_data_total_amount;
                    <?php } else { ?>
                        var gold_fine = json.gold_fine;
                        var silver_fine = json.silver_fine;
                        var amount = parseInt(json.amount);
                    <?php } ?>
                    old_gold_fine_val = gold_fine = round(gold_fine, 2).toFixed(3);
                    old_silver_fine_val = silver_fine = round(silver_fine, 1).toFixed(3);
                    old_amount_val = amount = amount.toFixed(0);
                    $('#old_amount').html(amount);
                    $('#old_gold_fine').html('Gold Fine : ' + gold_fine);
                    $('#old_silver_fine').html('Silver Fine : ' + silver_fine);
                    credit_limit = json.credit_limit;
                    display_lineitem_html(lineitem_objectdata);
                }
            });
        }
    }

    function get_bill_total_amount(){
        var nwt_gold_count = parseFloat(old_gold_fine_val);
        var nwt_gold_count = round(nwt_gold_count, 2).toFixed(3);
//        alert(nwt_gold_count);
        var nwt_silver_count = parseFloat(old_silver_fine_val);
        var nwt_silver_count = round(nwt_silver_count, 1).toFixed(3);

        var bill_amount_count = parseFloat(total_amount) + parseFloat(total_li_amount);
        tcs_amount = parseFloat(bill_amount_count) * parseFloat(tcs_per) / 100;
        tcs_amount = tcs_amount.toFixed(2);
        var bill_amount_count = parseFloat(bill_amount_count) + parseFloat(tcs_amount);
        bill_amount_count = bill_amount_count.toFixed(2);
        var nwt_amount_count = parseFloat(old_amount_val) + parseFloat(bill_amount_count);

        $('#total_grwt').html(round(total_grwt, 2).toFixed(3));
        $('#grwt_total').html(round(total_grwt, 2).toFixed(3));
        $('#total_amount').html(round(total_li_amount, 2));
        $('#bill_grwt').html(round(total_grwt, 2).toFixed(3));
        $('#bill_silver_fine').html('0.000');
        $('#tcs_amount').val(tcs_amount);
        $('#bill_amount').html(bill_amount_count);
        $('#gold_fine_total').val(nwt_gold_count);
        $('#silver_fine_total').val(nwt_silver_count);
        $('#amount_total').val(nwt_amount_count);

        $('#save_sell_entry').append('<input type="hidden" name="sell_grwt" id="sell_grwt" value="' + total_grwt + '" />');
        $('#save_sell_entry').append('<input type="hidden" name="sell_amount" id="sell_amount" value="' + bill_amount_count + '" />');
    }

    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        total_grwt = 0;
        total_li_amount = 0;

        // If any one Lineitem is added then Department not allow to change
        if ($.isEmptyObject(lineitem_objectdata) && $.isEmptyObject(pay_rec_objectdata)) {
            $('#department_id').removeAttr('disabled', 'disabled');
            $('#after_disabled_department_id').remove();
        } else {
            var department_id = $('#department_id').val();
            $('#department_id').attr('disabled', 'disabled');
            $('#department_id').closest('div').append('<input type="hidden" name="department_id" id="after_disabled_department_id" value="' + department_id + '" />');
        }
        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            if (value.type_name == 'S') {
                total_grwt = total_grwt + parseFloat(value.grwt || 0);
                total_li_amount = total_li_amount + parseFloat(value.amount || 0);
                var grwt = value.grwt;
                var spi_rate = value.spi_rate;
                var tax = value.tax;
                var amount = value.amount;
            } else if (value.type_name == 'P') {
                total_grwt = total_grwt - parseFloat(value.grwt || 0);
                total_li_amount = total_li_amount - parseFloat(value.amount || 0);
                var grwt = zero_value - parseFloat(value.grwt);
                var spi_rate = zero_value - parseFloat(value.spi_rate);
                var tax = zero_value - parseFloat(value.tax);
                var amount = zero_value - parseFloat(value.amount);
            }
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.sell_item_delete == 'allow'){
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_oe_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td>' + value.type_name + '</td>' +
                    '<td>' + value.category_name + '</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right">' + parseFloat(grwt).toFixed(3) + '</td>' +
                    '<td class="text-right">' + spi_rate + ' / ' + value.rate_per_1_gram + '</td>' +
                    '<td class="text-right">' + tax + ' <br><small class="text-gray">( ' + value.gst_rate + ' )</small></td>' +
                    '<td class="text-right">' + amount + '</td>' +
                    '</td></td>';
            new_lineitem_html += row_html;

        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        get_bill_total_amount();
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_oe_item").addClass('hide');
        sell_index = index;
        var value = lineitem_objectdata[index];
        console.log(value);
        $("#sell_type_id").val(value.type).trigger("change");
        setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/') ?>" + value.type);
        $("#category_id").val(value.category_id).trigger("change");
        setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/') ?>" + value.category_id);
        $("#item_id").val(value.item_id).trigger("change");
        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + value.item_id);
        $("#line_items_index").val(index);
        if (typeof (value.sell_item_id) != "undefined" && value.sell_item_id !== null) {
            $("#sell_item_id").val(value.sell_item_id);
        }
        $("#grwt").val(value.grwt);
        $("#spi_rate").val(value.spi_rate);
        $("#tax").val(value.tax);
        $("#amount").val(value.amount);
        $('#total_grwt_sell').val(value.total_grwt_sell);
        if (value.sell_item_delete == 'not_allow') {
            $('#sell_type_id').removeAttr('disabled', 'disabled');
            $('#category_id').removeAttr('disabled', 'disabled');
            $('#item_id').removeAttr('disabled', 'disabled');
        }
        $("#sell_item_delete").val(value.sell_item_delete);
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        value = lineitem_objectdata[index];
        if (confirm('Are you sure ?')) {
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }

    function display_pay_rec_html(pay_rec_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
//        console.log(pay_rec_objectdata);
        $.each(pay_rec_objectdata, function (index, value) {
            <?php if (isset($pay_rec_data)) { ?>
                if (edit_pay_rec_inc == '0') {
                    if (value.payment_receipt == '1') {
                        total_amount = total_amount + parseInt(value.amount);
                    } else if (value.payment_receipt == '2') {
                        total_amount = total_amount - parseInt(value.amount);
                    }
                }
            <?php } ?>
            if (value.payment_receipt == '1') {
                var payment_receipt = 'Payment';
                var value_amount = value.amount;
            } else {
                var payment_receipt = 'Receipt';
                var value_amount = zero_value - value.amount;
            }
            if (value.cash_cheque == '1') {
                var cash_cheque = 'Cash';
            } else {
                var cash_cheque = 'Cheque';
            }
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_pay_rec' + index + '" href="javascript:void(0);" onclick="edit_pay_rec(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item" href="javascript:void(0);" onclick="remove_pay_rec(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="pay_rec_index_' + index + '"><td class=""  width="4%">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td width="4%"></td>' +
                    '<td width="12%"> Payment Receipt </td>' +
                    '<td width="9%">' + payment_receipt + ' @ ' + cash_cheque + '</td>' +
                    '<td width="9%">' + value.bank_name + '</td>' +
                    '<td width="9%"></td>' +
                    '<td width="9%"></td>' +
                    '<td width="9%" class="text-right">' + value_amount + '</td>';
            new_lineitem_html += row_html;
//            console.log(new_lineitem_html);
        });
        <?php if (isset($pay_rec_data)) { ?>
            edit_pay_rec_inc = 1;
        <?php } ?>
        $('tbody#pop_up_pay_rec_list').html(new_lineitem_html);
//        $('tfoot#lineitem_foot_list').append(new_lineitem_html);
        $('#ajax-loader').hide();
        display_lineitem_html(lineitem_objectdata);
    }

    function edit_pay_rec(index) {
        $('#payment_receipt_model').modal('show');
        $('#ajax-loader').show();
        var value = pay_rec_objectdata[index];

        $("#pay_rec_index").val(index);
        if (typeof (value.pay_rec_id) != "undefined" && value.pay_rec_id !== null) {
            $("#pay_rec_id").val(value.pay_rec_id);
        }
        $("input[name=payment_receipt][value='" + value.payment_receipt + "']").prop("checked", true);
        $("#cash_cheque").val(value.cash_cheque).trigger("change");
        $("#bank_id").val(value.bank_id).trigger("change");
        setSelect2Value($("#bank_id"), "<?= base_url('app/set_account_bank_select2_val_by_id/') ?>" + value.bank_id);
        $("#pr_amount").val(value.amount);
        $("#narration").val(value.narration);
        $('#ajax-loader').hide();
    }

    function remove_pay_rec(index) {
        if (confirm('Are you sure ?')) {
            var value = pay_rec_objectdata[index];
            if (typeof (value.pay_rec_id) != "undefined" && value.pay_rec_id !== null) {
                $('.pay_rec_form').append('<input type="hidden" name="deleted_pay_rec_id[]" id="deleted_pay_rec_id" value="' + value.pay_rec_id + '" />');
            }
            if (value.payment_receipt == '1') {
                total_amount = total_amount - parseFloat(value.amount);
            } else if (value.payment_receipt == '2') {
                total_amount = total_amount + parseFloat(value.amount);
            }
            $('#payment_receipt_id').val('1');
            pay_rec_objectdata.splice(index, 1);
            display_pay_rec_html(pay_rec_objectdata);
        }
    }

    function round(value, exp) {
        if (typeof exp === 'undefined' || +exp === 0)
            return Math.round(value);

        value = +value;
        exp = +exp;

        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
            return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    }

    function round10(x) {
        return Math.round(x / 10) * 10;
    }

    function save_form(postData) {
        $("#ajax-loader").show();
        $('.module_save_btn').attr('disabled', 'disabled');
        var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
        postData.append('line_items_data', lineitem_objectdata_stringify);
        var pay_rec_objectdata_stringify = JSON.stringify(pay_rec_objectdata);
        postData.append('pay_rec_data', pay_rec_objectdata_stringify);
        $.ajax({
            url: "<?= base_url('sell_with_gst/save_sell') ?>",
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            data: postData,
            datatype: 'json',
            async: false,
            success: function (response) {
                $('.module_save_btn').removeAttr('disabled', 'disabled');
                var json = $.parseJSON(response);
                if (json['error'] == 'Exist') {
                    $("#ajax-loader").hide();
                    show_notify(json['error_exist'], false);
                } else if (json['error'] == 'Something went Wrong') {
                    $("#ajax-loader").hide();
                    show_notify('Something went Wrong! Please Refresh page and Go ahead.', false);
                } else if (json['success'] == 'Added') {
                    window.location.href = "<?php echo base_url('sell_with_gst/sell_with_gst_list') ?>";
                } else if (json['success'] == 'Updated') {
                    window.location.href = "<?php echo base_url('sell_with_gst/sell_with_gst_list') ?>";
                }
                return false;
            },
        });
    }
</script>
