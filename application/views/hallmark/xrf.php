<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="" method="post" id="xrf_form" novalidate enctype="multipart/form-data">
        <input type="hidden" id="print_xrf" value="0">
        <?php if (isset($xrf_data->xrf_id) && !empty($xrf_data->xrf_id)) { ?>
            <input type="hidden" name="xrf_id" value="<?= $xrf_data->xrf_id ?>">
        <?php } ?>
        <section class="content-header">
            <h1>
                XRF / HM / Laser Entry
                <?php
                $isEdit = $this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "add");
                $allow_price_change = $this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "allow to change price / per pcs");
                ?>

                <?php if ($isAdd) { ?>
                    <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn_print" style="margin: 5px;"><?= isset($xrf_data->xrf_id) ? 'Update & Print' : 'Save & Print' ?></button>

                    <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" style="margin: 5px;"><?= isset($xrf_data->xrf_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                <?php } ?>

                <?php if ($isView) { ?>
                    <a href="<?= base_url('hallmark/xrf_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">XRF / HM / Laser List</a>
                <?php } ?>                
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <div class="col-md-12">
                        <?php if ($isAdd || $isEdit) { ?>
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-2 pr0">
                                            <label for="posting_date">Posting Date<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="posting_date" id="posting_date" readonly="" class="form-control" value="<?= isset($xrf_data->posting_date) && strtotime($xrf_data->posting_date) > 0 ? date('d-m-Y', strtotime($xrf_data->posting_date)) : date('d-m-Y') ?>">
                                        </div>
                                        <div class="col-md-2 pr0">
                                            <label for="receipt_no">Receipt No<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="receipt_no" id="receipt_no" class="form-control" readonly="" value="<?= isset($xrf_data->receipt_no) ? $xrf_data->receipt_no : ''; ?>">
                                        </div>
                                        <div class="col-md-2 pr0">
                                            <label for="receipt_date">Receipt Date<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="receipt_date" id="receipt_date" class="form-control datepicker" value="<?= isset($xrf_data->receipt_date) && strtotime($xrf_data->receipt_date) > 0 ? date('d-m-Y', strtotime($xrf_data->receipt_date)) : date('d-m-Y') ?>">
                                        </div>
                                        <div class="col-md-2 pr0">
                                            <label for="account_id">Party<span class="required-sign">&nbsp;*</span></label>
                                            <?php if ($this->app_model->have_access_role(ACCOUNT_MODULE_ID, "add")) { ?>
                                                <a href="javascript:void(0);" data-href="<?= base_url('account/account'); ?>" class="btn btn-xs btn-primary pull-right open_popup"><i class="fa fa-plus"></i></a>
                                            <?php } ?>
                                            <select name="account_id" id="account_id" class="form-control"></select>
                                        </div>
                                        <div class="col-md-2 pr0">
                                            <label for="status">Status<span class="required-sign">&nbsp;*</span></label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="<?= HALLMARK_XRF_STATUS_ACTIVE ?>">Active</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="receipt_time">Receipt Time<span class="required-sign">&nbsp;*</span></label>
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <input type="text" name="receipt_time" id="receipt_time" class="form-control out_time input-small" value="<?= isset($xrf_data->receipt_time) && strtotime($xrf_data->receipt_time) > 0 ? date('h:i A', strtotime($xrf_data->receipt_time)) : date('h:i A') ?>" />
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-2">
                                            <br/>
                                            <label for="taken_by_same">
                                                <input type="checkbox" name="taken_by_same" id="taken_by_same" <?= isset($xrf_data->taken_by_same) && $xrf_data->taken_by_same == 1 ? 'checked' : '' ?>> Taken By Same
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="taken_by_name">Taken By Name <?= isset($xrf_data->taken_by_same) && $xrf_data->taken_by_same == 1 ? '' : '<span class="required-sign">&nbsp;*</span>' ?></label>
                                            <input type="text" name="taken_by_name" id="taken_by_name" class="form-control" required="" value="<?= isset($xrf_data->taken_by_name) ? $xrf_data->taken_by_name : ''; ?>" <?= isset($xrf_data->taken_by_same) && $xrf_data->taken_by_same == 1 ? 'readonly' : '' ?>>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="gst_no">Gst No</label>
                                            <input type="text" name="gst_no" id="gst_no" value="<?= isset($xrf_data->gst_no) ? $xrf_data->gst_no : ''; ?>" readonly="" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="box_no">Box No <?php echo (isset($xrf_box_no_mandatory) && $xrf_box_no_mandatory == '1') ? '<span class="required-sign">&nbsp;*</span>' : ''; ?></label>
                                            <input type="text" name="box_no" id="box_no" value="<?= isset($xrf_data->box_no) ? $xrf_data->box_no : ''; ?>" class="form-control num_only">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="remark">Remarks</label>
                                            <textarea name="remark" class="form-control" id="remark" rows="1" ><?= isset($xrf_data->remark) ? $xrf_data->remark : ''; ?></textarea>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="line_item_form item_fields_div">
                                            <h4 class="col-md-12">Line Item</h4>
                                            <input type="hidden" name="line_items_index" id="line_items_index" />
                                            <div class="col-md-2 pr0">
                                                <label for="item_id">Article<span class="required-sign">&nbsp;*</span></label>
                                                <?php if ($this->app_model->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID, "add")) { ?>
                                                    <a href="javascript:void(0);" data-href="<?= base_url('hallmark/item_master'); ?>" class="btn btn-xs btn-primary pull-right open_popup"><i class="fa fa-plus"></i></a>
                                                <?php } ?>
                                                <select name="line_items_data[item_id]" class="form-control" id="item_id"></select>
                                                <br/>
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="purity">Purity<span class="required-sign">&nbsp;*</span></label>
                                                <?php if ($this->app_model->have_access_role(TUNCH_MODULE_ID, "add")) { ?>
                                                    <a href="javascript:void(0);" data-href="<?= base_url('master/tunch'); ?>" class="btn btn-xs btn-primary pull-right open_popup"><i class="fa fa-plus"></i></a>
                                                <?php } ?>
                                                <select name="line_items_data[purity]" id="purity" class="form-control select2"></select><br/>
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="rec_qty">Rec. Qty<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="line_items_data[rec_qty]" class="form-control num_only" id="rec_qty" placeholder="" value=""><br/>
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="rec_weight">Rec. Weight<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="line_items_data[rec_weight]" class="form-control num_only" id="rec_weight" placeholder="" value=""><br/>
                                            </div>
                                            <div class="col-md-2 pr0">
                                                <label for="hm_ls_option">XRF / LASER / TUNCH</label>
                                                <select name="line_items_data[hm_ls_option]" id="hm_ls_option" class="form-control">
                                                    <option value="1" <?php echo ($hm_ls_option == '1') ? ' Selected ' : ''; ?> >XRF</option>
                                                    <option value="2" <?php echo ($hm_ls_option == '2') ? ' Selected ' : ''; ?> >LASER</option>
                                                    <option value="3" <?php echo ($hm_ls_option == '3') ? ' Selected ' : ''; ?> >TUNCH</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 pr0">
                                                <label for="price_per_pcs">Price / Per Pcs<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="line_items_data[price_per_pcs]" id="price_per_pcs" value="" class="form-control num_only" <?php echo isset($allow_price_change) && !empty($allow_price_change) ? '' : 'readonly' ?>>
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="item_amount">Amount</label>
                                                <input type="text" name="line_items_data[item_amount]" id="item_amount" value="" class="form-control num_only" readonly >
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp;</label>
                                                <input type="button" id="add_lineitem" class="btn btn-info btn-sm pull-right add_lineitem" value="Add Item" style="margin-top: 21px;"/>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-12">
                                            <table style="" class="table custom-table item-table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="100px">Action</th>
                                                        <th class="text-right" width="100px">Sr. No</th>
                                                        <th width="100px">HM / L / T</th>
                                                        <th width="200px">Article</th>
                                                        <th class="text-right" width="100px">Rec. Weight</th>
                                                        <th class="text-right" width="100px">Purity</th>
                                                        <th class="text-right" width="100px">Rec. Qty</th>
                                                        <th class="text-right" width="100px">Price / Per Pcs</th>
                                                        <th class="text-right" width="100px">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lineitem_list"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Total:</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th id="total_rec_weight" class="text-right"></th>
                                                        <th></th>
                                                        <th id="total_rec_qty" class="text-right"></th>
                                                        <th></th>
                                                        <th id="total_item_amount_label" class="text-right"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-3">
                                            <div class="account_balance" id="account_balance"></div>
                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-2">
                                            <input type="hidden" name="total_item_amount" id="total_item_amount">
                                            <label for="other_charges">Other Charges</label>
                                            <input type="text" name="other_charges" id="other_charges" class="form-control text-right num_only" value="<?= isset($xrf_data->other_charges) && $xrf_data->other_charges != 0 ? $xrf_data->other_charges : ''; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="total_amount">Total Amount</label>
                                            <input type="text" name="total_amount" id="total_amount" class="form-control text-right num_only" readonly="" value="<?php echo isset($xrf_data->total_amount) && $xrf_data->total_amount != 0 ? $xrf_data->total_amount : ''; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="advance_rec_amount">Advance Rec. Amount</label>
                                            <input type="text" name="advance_rec_amount" id="advance_rec_amount" class="form-control text-right num_only" value="<?= isset($xrf_data->advance_rec_amount) && $xrf_data->advance_rec_amount != 0 ? $xrf_data->advance_rec_amount : ''; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="pending_amount">Pending Amount</label>
                                            <input type="text" name="pending_amount" id="pending_amount" class="form-control text-right" readonly="" value="<?= isset($xrf_data->pending_amount) ? $xrf_data->pending_amount : ''; ?>">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-12">
                                            <?php if ($isAdd) { ?>
                                                <button type="submit" class="btn btn-primary btn-sm  module_save_btn" style="margin: 5px;"><?= isset($xrf_data->xrf_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                                                <button type="submit" class="btn btn-primary btn-sm  module_save_btn_print" style="margin: 5px;"><?= isset($xrf_data->xrf_id) ? 'Update & Print' : 'Save & Print' ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
</div>
</div>
<div id="iframeDiv" style="display:none"></div>
<script>
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    var acc_price_per_pcs = 0;
    var lineitem_objectdata = [];
    var edit_line_item = 0;
    var xrf_item_index = '';
    <?php if (isset($xrf_detail_arr)) { ?>
        var li_lineitem_objectdata = [<?php echo $xrf_detail_arr; ?>];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    var pts_lineitem_objectdata = [];
    display_lineitem_html(lineitem_objectdata);
    $(document).ready(function () {
        
//        $('#xrf_form').on('keyup keypress', function(e) {
//            var keyCode = e.keyCode || e.which;
//            if (keyCode === 13) {
//                var rfid_number = $('#rfid_number').val();
//                if(rfid_number == ''){
//                    e.preventDefault();
//                    return false;
//                }
//            }
//        });
        
        $('#receipt_time').timepicker();
        $("#status").select2();
        $("#hm_ls_option").select2();
        initAjaxSelect2($("#account_id"), "<?= base_url('app/party_name_with_number_select2_source') ?>");
        <?php if (isset($xrf_data->account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . $xrf_data->account_id) ?>");
            setTimeout(function () {
                $('#account_id').change();
            }, 500);
        <?php } ?>

        initAjaxSelect2($("#item_id"), "<?= base_url('app/hallmark_item_name_select2_source') ?>");
        initAjaxSelect2($("#purity"), "<?= base_url('app/touch_xrf_select2_source') ?>");

        $(document).on('focusout', '#receipt_time', function () {
            $(this).timepicker('hideWidget');
        });

        $(document).on('change', '#taken_by_same', function () {
            if ($("#taken_by_same").is(":checked")) {
                $("#taken_by_name").attr("readonly", "readonly");
                $("#taken_by_name").prop("required", false);
                $("label[for='taken_by_name']").html('Taken By Name');
            } else {
                $("#taken_by_name").removeAttr("readonly");
                $("#taken_by_name").prop("required", true);
                $("label[for='taken_by_name']").html('Taken By Name <span class="required-sign">&nbsp;*</span>');
            }
        });
        $(document).on('change', '#account_id', function () {
            var account_id = $('#account_id').val();
            $("#account_balance").html('');
            if (account_id != '' && account_id != null) {
                $('#ajax-loader').show();
                $.ajax({
                    url: "<?= base_url('app/get_account_detail') ?>/" + account_id,
                    type: 'GET',
                    data: '',
                    dataType: 'json',
                    success: function (res) {
                        $('#ajax-loader').hide();
                        if (res.success == "true") {
                            var account_data = res.account_data;
                            $("#gst_no").val(account_data.account_gst_no);
                            acc_price_per_pcs = account_data.price_per_pcs;
                            $("#price_per_pcs").val(acc_price_per_pcs);
                            $("#account_balance").html('<div class="row"><div class="col-md-6"><label>Old Balance</label><br /><span><b>Gold Fine : ' + account_data.gold_fine + '</b><br /></span><span><b>Silver Fine : ' + account_data.silver_fine + '</b></span></div><div class="col-md-6"><br /><span><b>Amount : ' + account_data.amount + '</b></span><br /><span><b>Credit Limit : <span id="credit_limit">' +account_data.credit_limit+'</span></b></span></div></div>');
                            calculate_totals();
                        }
                    }
                });
            }
        });

        $(document).on('input', '#advance_rec_amount,#other_charges', function () {
            calculate_totals();
        });

        $(document).on('input', '#rec_qty, #price_per_pcs', function () {
            var rec_qty = $("#rec_qty").val() || 0;
            var price_per_pcs = $("#price_per_pcs").val() || 0;
            var item_amount = parseFloat(price_per_pcs) * parseFloat(rec_qty);
            item_amount = round(item_amount, 2).toFixed(2);
            $('#item_amount').val(item_amount);
        });

        $(document).bind("keydown", function (e) {
            if (e.ctrlKey && e.which == 83) {
                $("#xrf_form").submit();
                return false;
            }
        });

        $(document).on('click', '.module_save_btn_print', function () {
            $("#print_xrf").val('1');
        });

        $(document).on('click', '.module_save_btn', function () {
            $("#print_xrf").val('0');
        });

        $(document).on('submit', '#xrf_form', function () {
            if ($.trim($("#receipt_date").val()) == '') {
                show_notify('Please Select Receipt Date.', false);
                $("#receipt_date").focus();
                return false;
            }

            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Party.', false);
                $("#account_id").select2('open');
                return false;
            }

            if ($.trim($("#status").val()) == '') {
                show_notify('Please Select Status.', false);
                $("#status").select2('open');
                return false;
            }

            if ($.trim($("#receipt_time").val()) == '') {
                show_notify('Please Select Receipt Time.', false);
                $("#receipt_time").focus();
                return false;
            }

            if (!($("#taken_by_same").is(":checked"))) {
                if ($.trim($("#taken_by_name").val()) == '') {
                    show_notify('Please Enter Taken By Name.', false);
                    $("#taken_by_name").focus();
                    return false;
                }
            }

            <?php if (isset($xrf_box_no_mandatory) && $xrf_box_no_mandatory == '1') { ?>
                if ($.trim($("#box_no").val()) == '') {
                    show_notify('Please Enter Box No.', false);
                    $("#box_no").focus();
                    return false;
                }
            <?php } ?>

            if (lineitem_objectdata == '') {
                show_notify("Please Add Article.", false);
                return false;
            }
            
            var account_id = $('#account_id').val();
            if(account_id == <?php echo CASE_CUSTOMER_ACCOUNT_ID; ?>){
                var pending_amount = $('#pending_amount').val();
                if(pending_amount != 0){
                    show_notify('Total Must Be 0.', false);
                    return false;
                }
            }

            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            $('.module_save_btn_print').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);

            $.ajax({
                url: "<?= base_url('hallmark/save_xrf') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    $('.module_save_btn_print').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Exist') {
                        $("#ajax-loader").hide();
                        show_notify(json['error_exist'], false);

                    } else if (json['success'] == 'Added' || json['success'] == 'Updated') {
                        if ($("#print_xrf").val() == "1") {
                            $("#print_xrf").val("0");

                            document.title = "XRF RECEIPT";

                            var xrf_id = json['xrf_id'];
                            $("#iframeDiv").html('');
                            $('#iframeDiv').append("<iframe src='<?= base_url('hallmark/print_xrf') ?>/" + xrf_id + "'></iframe>");
                            <?php /* window.open("<?=base_url('hallmark/print_xrf')?>/"+xrf_id, '_blank'); */ ?>
                            setTimeout(function () {
                                $('.changed-input').removeClass('changed-input');
                                window.location.href = "<?php echo base_url('hallmark/xrf_list') ?>";
                            }, 2000);
                        } else {
                            $('.changed-input').removeClass('changed-input');
                            window.location.href = "<?php echo base_url('hallmark/xrf_list') ?>";
                        }

                    }
                    return false;
                },
            });
            return false;
        });

        $('#add_lineitem').on('click', function () {

            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Party.', false);
                $("#account_id").select2('open');
                return false;
            }

            var item_id = $("#item_id").val();
            if (item_id == '' || item_id == null) {
                $("#item_id").select2('open');
                show_notify("Please Select Article!", false);
                return false;
            }

            var purity = $("#purity").val();
            if (purity == '' || purity == null) {
                $("#purity").focus();
                show_notify("Please Enter Purity!", false);
                return false;
            }

            var rec_qty = $("#rec_qty").val();
            if (rec_qty == '' || rec_qty == null) {
                $("#rec_qty").focus();
                show_notify("Please Enter Rec. Qty!", false);
                return false;
            }

            var rec_weight = $("#rec_weight").val();
            if (rec_weight == '' || rec_weight == null) {
                $("#rec_weight").focus();
                show_notify("Please Enter Rec. Weight!", false);
                return false;
            }

            var price_per_pcs = $("#price_per_pcs").val();
            if (price_per_pcs == '' || price_per_pcs == null) {
                show_notify('Please Enter Price / Per Pcs.', false);
                $("#price_per_pcs").focus();
                return false;
            }

            $("#add_lineitem").attr('disabled', 'disabled');
            var xrf_item_id = $("#xrf_item_id").val();
            if (typeof (xrf_item_id) != "undefined" && xrf_item_id !== null) {
                $('.line_item_form #deleted_xrf_item_id[value="' + xrf_item_id + '"]').remove();
            }

            var key = '';
            var value = '';
            var lineitem = {};
            var is_validate = '0';

            $('input[name^="line_items_data"],textarea[name^="line_items_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('select[name^="line_items_data"]').each(function (index) {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });

            var item_data = $('#item_id').select2('data');

            if (is_validate != '1') {
                var category_data = $('#category_id').select2('data');
                lineitem['item_name'] = item_data[0].text;
                var hm_ls_option_data = $('#hm_ls_option').select2('data');
                lineitem['hm_ls_option_text'] = hm_ls_option_data[0].text;
                
                lineitem['purity'] = $('#purity').val();
                lineitem['purity_name'] = round($('#purity option:selected').html(), 2).toFixed(3);

                var new_lineitem = JSON.parse(JSON.stringify(lineitem));
                line_items_index = $("#line_items_index").val();
                if (line_items_index != '') {
                    lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
                } else {
                    lineitem_objectdata.push(new_lineitem);
                }

                display_lineitem_html(lineitem_objectdata);
                $('#xrf_item_id').val('');
                $("#item_id").val(null).trigger("change");
                $("#purity").val(null).trigger("change");
                $('#rec_qty').val('');
                $('#rec_weight').val('');
                $('#price_per_pcs').val(acc_price_per_pcs);
                $('#item_amount').val('');
                xrf_item_index = '';
                $("#line_items_index").val('');
            }
            $("#add_lineitem").removeAttr('disabled', 'disabled');
        });
    });

    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        var total_rec_qty = 0;
        var total_rec_weight = 0;
        var total_item_amount = 0;
        $.each(lineitem_objectdata, function (index, value) {
            var sr_no = index + 1;

            var rec_qty_value = round(value.rec_qty, 2).toFixed(3);
            var rec_weight_value = round(value.rec_weight, 2).toFixed(3);
            var item_amount = round(value.item_amount, 2).toFixed(2);

            total_rec_qty = parseFloat(total_rec_qty) + parseFloat(rec_qty_value);
            total_rec_weight = parseFloat(total_rec_weight) + parseFloat(rec_weight_value);
            total_item_amount = parseFloat(total_item_amount) + parseFloat(item_amount);

            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';

            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_st_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';

            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td class="text-right">' + sr_no + '</td>' +
                    '<td class="">' + value.hm_ls_option_text + '</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right">' + rec_weight_value + '</td>' +
                    '<td class="text-right">' + value.purity_name + '</td>' +
                    '<td class="text-right">' + rec_qty_value + '</td>' +
                    '<td class="text-right">' + value.price_per_pcs + '</td>' +
                    '<td class="text-right">' + item_amount + '</td>';
            new_lineitem_html += row_html;
        });

        total_rec_qty = round(total_rec_qty, 2).toFixed(3);
        total_rec_weight = round(total_rec_weight, 2).toFixed(3);
        total_item_amount = round(total_item_amount, 2).toFixed(2);

        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#total_rec_qty').html(total_rec_qty);
        $('#total_rec_weight').html(total_rec_weight);
        $('#total_item_amount_label').html(total_item_amount);
        $('#total_item_amount').val(total_item_amount);
        calculate_totals();
        $('#ajax-loader').hide();
    }

    function calculate_totals()
    {
        var total_item_amount = parseFloat($('#total_item_amount').val()) || 0;
        ;
        var other_charges = parseFloat($('#other_charges').val()) || 0;
        var advance_rec_amount = parseFloat($("#advance_rec_amount").val()) || 0;

        var total_item_amount_with_charges = total_item_amount + other_charges;
        $('#total_amount').val(round(total_item_amount_with_charges, 2).toFixed(2));

        var pending_amount = parseFloat(total_item_amount_with_charges) - parseFloat(advance_rec_amount);
        $('#pending_amount').val(round(pending_amount, 2).toFixed(2));
    }

    function edit_lineitem(index) {
        $('#ajax-loader').show();
        $(".delete_st_item").addClass('hide');
        xrf_item_index = index;
        $("#line_items_index").val(xrf_item_index);

        var value = lineitem_objectdata[index];
        edit_line_item = 1;
        setSelect2Value($("#item_id"), "<?= base_url('app/set_hallmark_item_name_select2_val_by_id') ?>/" + value.item_id);
        $("#item_id").select2('open');
        setSelect2Value($("#purity"), "<?= base_url('app/set_touch_select2_val_by_id/') ?>" + value.purity);
        $("#rec_qty").val(value.rec_qty);
        $("#rec_weight").val(value.rec_weight);
        $("#hm_ls_option").val(value.hm_ls_option).trigger("change");
        $('#price_per_pcs').val(value.price_per_pcs);
        $('#item_amount').val(value.item_amount);
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        if (confirm('Are you sure ?')) {
            value = lineitem_objectdata[index];
            if (typeof (value.transfer_detail_id) != "undefined" && value.transfer_detail_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_xrf_item_id[]" id="deleted_xrf_item_id" value="' + value.transfer_detail_id + '" />');
            }
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
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

</script>

<?php 
$enter_key_to_next = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'enter_key_to_next'));
if($enter_key_to_next == 1 ) {
    ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#account_id").select2('open');

//        $(document).on('shown.bs.modal', function(e) {
//            $('input:visible:enabled:first', e.target).focus();
//        });

        $('body').on('keydown', 'input,select,.select2-search__field, textarea', function(e) {
            var self = $(this)
              , form = self.parents('form:eq(0)')
              , focusable
              , next
              , prev
              ;

            if($('.modal.in').length > 0) { 
                form = $('.modal.in');
            }
            
            var id = $(this).attr('id');
            if(id == 'add_lineitem'){
                $('#add_lineitem').click();
            } else if (e.shiftKey) {
                if (e.keyCode == 13 && $(this).is("textarea") == false) {
                    focusable =   form.find('input,a,select,.select2-search__field,button,textarea').filter(':visible:not([readonly])');
                    prev = focusable.eq(focusable.index(this)-1); 

                    if (prev.length) {
                       prev.focus();
                    } else {
                        form.submit();
                    }
                }
            } else if (e.keyCode == 13 && $(this).is("textarea") == false) {
                focusable = form.find('input,a,select,.select2-search__field,button,textarea').filter(':visible:not([readonly])');
                next = focusable.eq(focusable.index(this)+1);
                if (next.length) {
                    next.focus();
                } else {
                    form.submit();
                }
                return false;

            } else if (e.ctrlKey) {
                if (e.keyCode == 13) {
                    focusable =   form.find('input,a,select,.select2-search__field,button,textarea').filter(':visible:not([readonly])');
                    prev = focusable.eq(focusable.index(this)+1); 

                    if (prev.length) {
                       prev.focus();
                    } else {
                        form.submit();
                    }
                }
            }
        });

        /**
            * WARNING: untested using Select2's option ['selectOnClose'=>true]
            *
            * This code was written because the Select2 widget does not handle
            * tabbing from one form field to another.  The desired behavior is that
            * the user can use [Enter] to select a value from Select2 and [Tab] to move
            * to the next field on the form.
            *
            * The following code moves focus to the next form field when a Select2 'close'
            * event is triggered.  If the next form field is a Select2 widget, the widget
            * is opened automatically.
            *
            * Users that click elsewhere on the document will cause the active Select2
            * widget to close.  To prevent the code from overriding the user's focus choice
            * a flag is added to each element that the users clicks on.  If the flag is
            * active, then the automatic focus script does not happen.
            *
            * To prevent conflicts with multiple Select2 widgets opening at once, a second
            * flag is used to indicate the open status of a Select2 widget.  It was
            * necessary to use a flag instead of reading the class '--open' because using the
            * class '--open' as an indicator flag caused timing/bubbling issues.
            *
            * To simulate a Shift+Tab event, a flag is recorded every time the shift key
            * is pressed.
            */
        var docBody = $(document.body);
        var shiftPressed = false;
        var clickedOutside = false;
        //var keyPressed = 0;

        docBody.on('keydown', function(e) {
            var keyCaptured = (e.keyCode ? e.keyCode : e.which);
            //shiftPressed = keyCaptured == 16 ? true : false;
            if (keyCaptured == 16) { shiftPressed = true; }
        });
        docBody.on('keyup', function(e) {
            var keyCaptured = (e.keyCode ? e.keyCode : e.which);
            //shiftPressed = keyCaptured == 16 ? true : false;
            if (keyCaptured == 16) { shiftPressed = false; }
        });

        docBody.on('mousedown', function(e){
            // remove other focused references
            clickedOutside = false;
            // record focus
            if ($(e.target).is('[class*="select2"]')!=true) {
                clickedOutside = true;
            }
        });

        docBody.on('select2:opening', function(e) {
            // this element has focus, remove other flags
            clickedOutside = false;
            // flag this Select2 as open
            $(e.target).attr('data-s2open', 1);
        });
        docBody.on('select2:closing', function(e) {
            // remove flag as Select2 is now closed
            $(e.target).removeAttr('data-s2open');
        });

        docBody.on('select2:close', function(e) {
            var elSelect = $(e.target);
            elSelect.removeAttr('data-s2open');
            var currentForm = elSelect.closest('form');
            var othersOpen = currentForm.has('[data-s2open]').length;
            if (othersOpen == 0 && clickedOutside==false) {
                /* Find all inputs on the current form that would normally not be focus`able:
                 *  - includes hidden <select> elements whose parents are visible (Select2)
                 *  - EXCLUDES hidden <input>, hidden <button>, and hidden <textarea> elements
                 *  - EXCLUDES disabled inputs
                 *  - EXCLUDES read-only inputs
                 */
                var inputs = currentForm.find(':input:enabled:not([readonly], input:hidden, button:hidden, textarea:hidden)')
                    .not(function () {   // do not include inputs with hidden parents
                        return $(this).parent().is(':hidden');
                    });
                var elFocus = null;
                $.each(inputs, function (index) {
                    var elInput = $(this);
                    if (elInput.attr('id') == elSelect.attr('id')) {
                        if ( shiftPressed) { // Shift+Tab
                            elFocus = inputs.eq(index - 1);
                        } else {
                            elFocus = inputs.eq(index + 1);
                        }
                        return false;
                    }
                });
                if (elFocus !== null) {
                    // automatically move focus to the next field on the form
                    var isSelect2 = elFocus.siblings('.select2').length > 0;
                    if (isSelect2) {
                        elFocus.select2('open');
                    } else {
                        elFocus.focus();
                    }
                }
            }
        });

        /**
         * Capture event where the user entered a Select2 control using the keyboard.
         * http://stackoverflow.com/questions/20989458
         * http://stackoverflow.com/questions/1318076
         */
        docBody.on('focus', '.select2', function(e) {
            var elSelect = $(this).siblings('select');
            var test1 = elSelect.is('[disabled]');
            var test2 = elSelect.is('[data-s2open]');
            var test3 = $(this).has('.select2-selection--single').length;
            if (elSelect.is('[disabled]')==false && elSelect.is('[data-s2open]')==false
                && $(this).has('.select2-selection--single').length>0) {
                elSelect.attr('data-s2open', 1);
                elSelect.select2('open');
            }
        });
    });        
</script>
<?php
}
?>