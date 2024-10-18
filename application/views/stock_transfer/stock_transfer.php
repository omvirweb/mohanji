<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="" method="post" id="stock_transfer_form" novalidate enctype="multipart/form-data">
        <?php if (isset($stock_transfer_data->stock_transfer_id) && !empty($stock_transfer_data->stock_transfer_id)) { ?>
            <input type="hidden" name="stock_transfer_id" value="<?= $stock_transfer_data->stock_transfer_id ?>">
        <?php } ?>
            <input type="hidden" id="total_grwt_sell" value=""/>
        <section class="content-header">
            <h1>
                Stock Transfer
                <?php 
                    $isEdit = $this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "edit");
                    $isView = $this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "view");
                    $isAdd = $this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "add"); 
                    $allow_change_date = $this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "allow_change_date"); 
                ?>
            <?php if(!isset($stock_transfer_data->audit_status) || (isset($stock_transfer_data->audit_status) && $stock_transfer_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
                <?php if($isAdd){ ?>
                    <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" style="margin: 5px;"><?= isset($stock_transfer_data->stock_transfer_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                <?php } ?>
            <?php } ?>
            <?php if($isView){ ?>
                <a href="<?= base_url('stock_transfer/stock_transfer_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Stock Transfer List</a>
            <?php } ?>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <div class="col-md-12">
                        <?php if($isAdd || $isEdit) { ?>
                        <!-- Horizontal Form -->
                        <div class="box box-primary">                        
                            <div class="box-body">
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <label for="from_department">From Department<span class="required-sign">&nbsp;*</span></label>
                                            <select name="from_department" id="from_department" class="form-control select2" onchange="set_to_department()"></select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="to_department">To Department<span class="required-sign">&nbsp;*</span></label>
                                            <select name="to_department" id="to_department" class="form-control select2"></select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date">Transfer Date</label>
                                            <input type="text" name="transfer_date" id="datepicker1" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?php if (isset($stock_transfer_data->transfer_date) && !empty($stock_transfer_data->transfer_date)) { echo date('d-m-Y', strtotime($stock_transfer_data->transfer_date)); } else { echo date('d-m-Y'); } ?>"><br />
                                        </div>
                                        <div class="col-md-3">
                                            <label for="narration">Narration</label>
                                            <input type="narration" name="narration" id="narration" class="form-control" value="<?php if (isset($stock_transfer_data->narration) && !empty($stock_transfer_data->narration)) { echo $stock_transfer_data->narration; } else { echo '';} ?>"><br     />
                                        </div>
                                        <div class="line_item_form item_fields_div" style="height: 160px;">
                                            <input type="hidden" name="line_items_index" id="line_items_index" />
                                            <input type="hidden" name="line_items_data[stock_item_delete]" id="stock_item_delete" value="allow" />
                                            <?php if (isset($stock_transfer_detail_arr)) { ?>
                                                <input type="hidden" name="line_items_data[purchase_sell_item_id]" id="purchase_sell_item_id"/>
                                                <input type="hidden" name="line_items_data[transfer_detail_id]" id="transfer_detail_id" />
                                                <input type="hidden" name="line_items_data[stock_type]" id="stock_type" />
                                            <?php } ?>
                                            <div class="col-md-3">
                                                <h4>
                                                    Line Item &nbsp;&nbsp;&nbsp;
                                                    <span><label style="margin-bottom: 0px;"><input type="checkbox" name="line_items_data[tunch_textbox]" id="tunch_textbox"> <small>Tunch Textbox</small></label></span>
                                                </h4>
                                            </div>
                                            <?php
                                                $use_rfid = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'use_rfid'));
                                                $use_barcode = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'use_barcode'));
                                                if($use_rfid == 1 || $use_barcode == 1) {
                                            ?>
                                                <div class="col-md-3">
                                                    <label for="rfid_number">Enter RFID</label>
                                                    <input type="hidden" name="line_items_data[from_item_stock_rfid_id]" id="from_item_stock_rfid_id">
                                                    <input type="hidden" name="line_items_data[to_item_stock_rfid_id]" id="to_item_stock_rfid_id">
                                                    <input type="text" name="line_items_data[rfid_number]" id="rfid_number" class="form-control">
                                                </div>
                                            <?php } ?>
                                            <div class="clearfix"></div>
                                            <?php if($use_category == '1') { ?>
                                                <div class="col-md-2">
                                                    <label for="item_id">Category<span class="required-sign">&nbsp;*</span></label>
                                                    <select name="line_items_data[category_id]" class="form-control category_id" id="category_id"></select>
                                                </div>
                                            <?php } else { ?>
                                                <input type="hidden" name="line_items_data[category_id]" id="category_id" class="category_id">
                                            <?php } ?>
                                            <div class="col-md-2">
                                                <label for="item_id">Item<span class="required-sign">&nbsp;*</span></label>
                                                <select name="line_items_data[item_id]" class="form-control item_id select2" id="item_id"></select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tunch">Tunch<span class="required-sign">&nbsp;*</span></label>
                                                <div class="touch_select">
                                                    <select name="line_items_data[tunch]" id="tunch" class="form-control select2 tunch"></select>
                                                </div>
                                                <div class="touch_input">
                                                    <input type="text" name="line_items_data[tunch]" id="touch_data_id" class="form-control tunch num_only" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="grwt">Gr.Wt.</label>
                                                <input type="text" name="line_items_data[grwt]" class="form-control grwt num_only" id="grwt" placeholder="" value=""><br />
                                            </div>
                                            <div class="col-md-1">
                                                <label for="stamp">Less</label>
                                                <input type="text" name="line_items_data[less]" class="form-control less num_only" id="less"  placeholder="" value=""><br />
                                            </div>
                                            <div class="col-md-1">
                                                <label for="unit">Net.Wt<span class="required-sign">&nbsp;*</span></label><span id="stock_ntwt_data"></span>
                                                <input type="text" name="line_items_data[net_wt]" class="form-control net_wt num_only" id="net_wt" placeholder="" value="" ><br />
                                            </div>
                                            <div class="col-md-1">
                                                <?php
                                                    $allow_change_wastage = 'readonly';
                                                    if($this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "allow change wastage")){
                                                        $allow_change_wastage = '';
                                                    }
                                                ?>
                                                <label for="wstg">Wstg</label>
                                                <input type="text" name="line_items_data[wstg]" class="form-control wstg num_only" id="wstg" placeholder="" value="" <?php echo $allow_change_wastage; ?> ><br />
                                            </div>
                                            <div class="col-md-1">
                                                <label for="fine">Fine</label><span id="stock_fine_data"></span>
                                                <input type="text" name="line_items_data[fine]" class="form-control fine" id="fine" placeholder="" value="" ><br />
                                            </div>
                                            <div class="col-md-1">
                                                <label></label>
                                                <br /><input type="button" id="add_lineitem" class="btn btn-info btn-sm add_lineitem pull-right" value="Add Item" style="margin-left: -15px;"/>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-12">
                                            <table style="" class="table custom-table item-table">
                                                <thead>
                                                    <tr>
                                                        <th width="100px">Action</th>
                                                        <?php if($use_category == '1') { ?>
                                                            <th>Category</th>
                                                        <?php } ?>
                                                        <th>Item Name</th>
                                                        <th class="text-right">Tunch</th>
                                                        <th class="text-right">Gr.Wt.</th>
                                                        <th class="text-right">Less</th>
                                                        <th class="text-right">Net.Wt</th>
                                                        <th class="text-right">Wstg</th>
                                                        <th class="text-right">Gold Fine</th>
                                                        <th class="text-right">Silver Fine</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lineitem_list"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <?php if($use_category == '1') { ?>
                                                            <th></th>
                                                        <?php } ?>
                                                        <th class="text-right">Total : </td>
                                                        <th></th>
                                                        <th id="total_gold_grwt" class="text-right"></th>
                                                        <th id="total_gold_less" class="text-right"></th>
                                                        <th id="total_gold_ntwt" class="text-right"></th>
                                                        <th></th>
                                                        <th id="total_gold_fine" class="text-right"></th>
                                                        <th id="total_silver_fine" class="text-right"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <?php if (isset($stock_transfer_data->stock_transfer_id) && !empty($stock_transfer_data->stock_transfer_id)) { ?>
                                        <div class="created_updated_info" style="margin-left: 10px;">
                                            Created by : <?= isset($stock_transfer_data->created_by_name) ? $stock_transfer_data->created_by_name : '' ?>
                                            @ <?= isset($stock_transfer_data->created_at) ? date ('d-m-Y h:i A', strtotime($stock_transfer_data->created_at)) : '' ?><br/>
                                            Updated by : <?= isset($stock_transfer_data->updated_by_name) ? $stock_transfer_data->updated_by_name : '' ?>
                                            @ <?= isset($stock_transfer_data->updated_at) ? date('d-m-Y h:i A', strtotime($stock_transfer_data->updated_at)) :'' ; ?>
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
        </div>
    </form>
</div>
<div id="purchase_item_selection_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="myModalLabel">Available Item Details</h4>
                </div>
            </div>
            <div class="modal-body edit-content">
                <div class="col-md-12">
                    <div class="popup_div">
                        <table style="" class="table custom-table border item-table">
                            <thead>
                                <tr>
                                    <th class="text-center"><label><input type="checkbox" id="select_all_purchase_to_sell" /> Select All</label></th>
                                    <th>Particulars</th>
                                    <th>Item</th>
                                    <th class="text-right">Gr.Wt.</th>
                                    <th class="text-right">Less</th>
                                    <th class="text-right">Net.Wt.</th>
                                    <th class="text-right">Tunch</th>
                                    <th class="text-right">Wstg</th>
                                    <th class="text-right">Fine</th>
                                </tr>
                            </thead>
                            <tbody id="purchase_item_selection_list"></tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Checked Total</th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right" id="pts_checked_total_grwt">0</th>
                                    <th class="text-right" id="pts_checked_total_less">0</th>
                                    <th class="text-right" id="pts_checked_total_ntwt">0</th>
                                    <th class="text-center" id="pts_checked_total_average" colspan="2">0</th>
                                    <th class="text-right" id="pts_checked_total_fine">0</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Total</th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right" id="pts_total_grwt"></th>
                                    <th class="text-right" id="pts_total_less"></th>
                                    <th class="text-right" id="pts_total_ntwt"></th>
                                    <th class="text-center" id="pts_total_average" colspan="2"></th>
                                    <th class="text-right" id="pts_total_fine"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <button type="button" class="btn btn-primary purchase_to_sell_button" id="purchase_to_sell_button">Save</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    var module_submit_flag = 0;
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    var lineitem_objectdata = [];
    var edit_line_item = 0;
    var transfer_index = '';
    <?php if (isset($stock_transfer_detail_arr)) { ?>
        var li_lineitem_objectdata = [<?php echo $stock_transfer_detail_arr; ?>];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    var pts_lineitem_objectdata = [];
    display_lineitem_html(lineitem_objectdata);
    $(document).ready(function () {
        
        $('#stock_transfer_form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                var rfid_number = $('#rfid_number').val();
                if(rfid_number == ''){
                    e.preventDefault();
                    return false;
                }
            }
        });
        
        if ($('#datepicker1').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker1').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: "today",
                maxDate: 0,
            })
        }
        <?php if($without_purchase_sell_allow == '1'){ ?>
            initAjaxSelect2($("#from_department"), "<?= base_url('app/department_from_stock_select2_source') ?>");
        <?php } else { ?>
            initAjaxSelect2($("#from_department"), "<?= base_url('app/process_master_select2_source') ?>");
            initAjaxSelect2($("#tunch"), "<?= base_url('app/touch_purity_select2_source') ?>");
        <?php } ?>
        <?php if (isset($stock_transfer_data->from_department)) { ?>
            setSelect2Value($("#from_department"), "<?= base_url('app/set_account_name_select2_val_by_id/' . $stock_transfer_data->from_department) ?>");
//            $("#from_department").change();
            $('#from_department').val(<?php echo $stock_transfer_data->from_department; ?>).trigger('change');
            initAjaxSelect2($("#to_department"), "<?= base_url('app/process_master_from_process_select2_source/' . $stock_transfer_data->from_department) ?>");
            setSelect2Value($("#to_department"), "<?= base_url('app/set_account_name_select2_val_by_id/' . $stock_transfer_data->to_department) ?>");
            <?php if($use_category == '1') { ?>
                initAjaxSelect2($("#category_id"), "<?= base_url('app/category_from_stock_department_select2_source/' . $stock_transfer_data->from_department) ?>");
            <?php } ?>
        <?php } else { ?>
            setSelect2Value($("#from_department"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
            $('#from_department').val(<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']; ?>).trigger('change');
//            initAjaxSelect2($("#to_department"), "<?= base_url('app/process_master_from_process_select2_source/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
            <?php if($use_category == '1') { ?>
                <?php if($without_purchase_sell_allow == '1'){ ?>
                    initAjaxSelect2($("#category_id"), "<?= base_url('app/category_from_stock_department_select2_source/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
                <?php } else { ?>
                    initAjaxSelect2($("#category_id"), "<?= base_url('app/category_select2_source') ?>");
                <?php } ?>
            <?php } ?>
        <?php } ?>
        
        $(document).on('change', '#category_id', function (e) {
            $('#item_id').val(null).trigger('change');
            var category_id = $('#category_id').val();
            if (category_id != '' && category_id != null) {
                <?php if($without_purchase_sell_allow == '1'){ ?>
                    initAjaxSelect2($("#item_id"), "<?= base_url('app/item_from_stock_category_select2_source') ?>/" + category_id);
                <?php } else { ?>
                    initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_from_category_select2_source') ?>/" + category_id);
                <?php } ?>
            } else {
                $('#item_id').val(null).trigger('change');
            }
        });
        <?php if($use_category == '1') { ?>
        <?php } else { ?>
            <?php if($without_purchase_sell_allow == '1'){ ?>
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_from_stock_category_select2_source') ?>/");
            <?php } else { ?>
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_from_category_select2_source') ?>/");
            <?php } ?>
        <?php } ?>

        $(document).on('click', '#select_all_purchase_to_sell', function () {
            if($("#select_all_purchase_to_sell").is(':checked')){
                    $('.pts_selected_index').prop('checked', true);
            } else {
                    $('.pts_selected_index').prop('checked', false);
            }
            checked_average_value();
        });
        
        $(document).on('click', '.pts_selected_index', function () {
            checked_average_value();
		});

        $(document).on('change', '#item_id', function () {
            $("#grwt").val('');
            $("#less").val('');
            $("#net_wt").val('');
            if(edit_line_item == 0){
                $("#tunch").val(null).trigger("change");
                $("#touch_data_id").val('');
            }
            $("#wstg").val('');
            $("#fine").val('');
            
            var item_id = $('#item_id').val();
            var from_department = $('#from_department').val();
            var category_id = $('.category_id').val();
            <?php if($without_purchase_sell_allow == '1'){ ?>
            if (item_id != '' && item_id != null || from_department != '' && from_department != null) {
                initAjaxSelect2($("#tunch"), "<?= base_url('app/tunch_from_stock_item_select2_source') ?>/" + item_id + '/' + from_department + '/' + category_id);
            }
            <?php } else { ?>
                initAjaxSelect2($("#tunch"), "<?= base_url('app/touch_purity_select2_source') ?>");
            <?php } ?>
            if (item_id != '' && item_id != null) {
                $.ajax({
                    url: "<?php echo base_url('sell/get_item_data'); ?>/" + item_id,
                    type: "GET",
                    async: false,
                    data: "",
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json.less == 0) {
                            $('#less').attr('readonly', 'readonly');
                            $("#grwt").focus();
                            $(document).on('change', '#grwt', function () {
                                $('#wstg').focus();
                            });
                        } else {
                            $('#less').removeAttr('readonly', 'readonly');
                            $("#grwt").focus();
                            $(document).on('change', '#grwt', function () {
                                $('#less').focus();
                            });
                        }
                        <?php if($use_category == '1') { ?>
                        <?php } else { ?>
                            $('#category_id').val(json.category_id);
                        <?php } ?>

                        if (json.stock_method == '2') {
                            var from_department = $('#from_department').val();
                            var stock_transfer_id = '';
                            <?php if (isset($stock_transfer_data->stock_transfer_id) && !empty($stock_transfer_data->stock_transfer_id)) { ?>
                                stock_transfer_id = '<?php echo $stock_transfer_data->stock_transfer_id; ?>';
                            <?php } ?>
                            $.ajax({
                                url: "<?php echo base_url('sell/get_purchase_to_sell_pending_item'); ?>/",
                                type: 'POST',
                                async: false,
                                data: {department_id : from_department, item_id : item_id, stock_transfer_id : stock_transfer_id},
                                success: function (response) {
                                    var json = $.parseJSON(response);
                                    $('#purchase_item_selection_popup').modal('show');
                                    if (json['sell_lineitems'] != '') {
                                        pts_lineitem_objectdata = json['sell_lineitems'];
                                        display_pts_lineitem_html(pts_lineitem_objectdata);
                                    } else {
                                        pts_lineitem_objectdata = [];
                                        display_pts_lineitem_html(pts_lineitem_objectdata);
                                    }
                                }
                            });
                        }
                    }
                });
            }
        });

        $('#purchase_item_selection_popup').on('hidden.bs.modal', function () {
            $("#item_id").val(null).trigger("change");
            $(".delete_st_item").removeClass('hide');
        });

        $(document).on('keyup change', '.pts_grwt, .pts_less, .pts_wstg', function () {
            var pts_selected_index = $(this).data('pts_selected_index');
//            console.log(pts_selected_index);
            var pts_grwt = $('#pts_grwt_' + pts_selected_index).val();
            var pts_less = $('#pts_less_' + pts_selected_index).val();
            var pts_wstg = $('#pts_wstg_' + pts_selected_index).val();
            
            var pe_stock_grwt = pts_lineitem_objectdata[pts_selected_index].grwt;
            <?php // if($without_purchase_sell_allow == '1'){ ?>
                if(parseFloat(pts_grwt) > parseFloat(pe_stock_grwt)){
                    show_notify('Please Enter GrWt Less than of ' + pe_stock_grwt, false);
                    $(this).val(pe_stock_grwt).keyup();
                    return false;
                }
            <?php // } ?>
//                console.log(lineitem_objectdata);
//            var stock_grwt = lineitem_objectdata[pts_selected_index].grwt;
            var total_grwt_sell = $('#total_grwt_sell').val();
            var total_grwt_sell = parseFloat(total_grwt_sell).toFixed(3);
            if(total_grwt_sell != '' && total_grwt_sell != null){
//                if (lineitem_objectdata != '') {
//                    var stock_grwt = lineitem_objectdata[pts_selected_index].grwt;
//                }
                <?php // if($without_purchase_sell_allow == '1'){ ?>
                    if(parseFloat(pts_grwt) < parseFloat(total_grwt_sell)){
                        show_notify('GrWt Should Be Grater Than of ' + total_grwt_sell, false);
                        $(this).val(total_grwt_sell).keyup();
                        return false;
                    }
                <?php // } ?>
            }
            pts_lineitem_objectdata[pts_selected_index].net_wt = parseFloat(pts_grwt || 0) - parseFloat(pts_less || 0);
            pts_lineitem_objectdata[pts_selected_index].net_wt = round(pts_lineitem_objectdata[pts_selected_index].net_wt, 2).toFixed(3);
            pts_lineitem_objectdata[pts_selected_index].fine = parseFloat(pts_lineitem_objectdata[pts_selected_index].net_wt) * (parseFloat(pts_lineitem_objectdata[pts_selected_index].touch_id) + parseFloat(pts_wstg || 0)) / 100;
            pts_lineitem_objectdata[pts_selected_index].fine = round(pts_lineitem_objectdata[pts_selected_index].fine, 2).toFixed(3);
            $('#pts_net_wt_' + pts_selected_index).html(pts_lineitem_objectdata[pts_selected_index].net_wt);
            $('#pts_fine_' + pts_selected_index).html(pts_lineitem_objectdata[pts_selected_index].fine);
            checked_average_value();
        });
        
        $(document).on('click', '#purchase_to_sell_button', function () {
            var pts_grwt= new Array();
            $("input[name='pts_grwt[]']").each(function(){
                pts_grwt.push($(this).val());
            });
            var pts_less= new Array();
            $("input[name='pts_less[]']").each(function(){
                pts_less.push($(this).val());
            });
            var pts_wstg= new Array();
            $("input[name='pts_wstg[]']").each(function(){
                pts_wstg.push($(this).val());
            });
            if ($("input.pts_selected_index:checked").length == 0) {
                show_notify('Please select at least one item.', false);
                return false;
            }
            var pts_selected_index_lineitems = [];
            var pts_item_id = '';
            $.each($("input.pts_selected_index:checked"), function() {
                pts_item_id = $(this).data('item_id');
                var pts_selected_index = $(this).data('pts_selected_index');
                pts_lineitem_objectdata[pts_selected_index].stock_item_delete = 'allow';
                pts_lineitem_objectdata[pts_selected_index].grwt = pts_grwt[pts_selected_index] || 0;
                pts_lineitem_objectdata[pts_selected_index].less = pts_less[pts_selected_index] || 0;
                pts_lineitem_objectdata[pts_selected_index].wstg = pts_wstg[pts_selected_index];
                pts_lineitem_objectdata[pts_selected_index].tunch = pts_lineitem_objectdata[pts_selected_index].touch_id;
                pts_lineitem_objectdata[pts_selected_index].tunch_name = pts_lineitem_objectdata[pts_selected_index].touch_id;
                pts_lineitem_objectdata[pts_selected_index].net_wt = parseFloat(pts_lineitem_objectdata[pts_selected_index].grwt || 0) - parseFloat(pts_lineitem_objectdata[pts_selected_index].less || 0);
                pts_lineitem_objectdata[pts_selected_index].net_wt = round(pts_lineitem_objectdata[pts_selected_index].net_wt, 2).toFixed(3);
                pts_lineitem_objectdata[pts_selected_index].fine = parseFloat(pts_lineitem_objectdata[pts_selected_index].net_wt) * (parseFloat(pts_lineitem_objectdata[pts_selected_index].touch_id) + parseFloat(pts_lineitem_objectdata[pts_selected_index].wstg || 0)) / 100;
                pts_lineitem_objectdata[pts_selected_index].fine = round(pts_lineitem_objectdata[pts_selected_index].fine, 2).toFixed(3);
                
                pts_selected_index_lineitems.push(pts_lineitem_objectdata[pts_selected_index]);
            });
//            lineitem_delete = [];
//            jQuery.each(lineitem_objectdata, function(obj, values) {
//                lineitem_delete.push(values.transfer_detail_id);
//            });
//            pts_delete = [];
//            jQuery.each(pts_selected_index_lineitems, function(obj, values) {
//                pts_delete.push(values.transfer_detail_id);
//            });
//            var uncheck_transafer_item = $(lineitem_delete).not(pts_delete).get();
//            
//            $('.line_item_form').append('<input type="hidden" name="deleted_transfer_detail_id[]" id="deleted_transfer_detail_id" value="' + uncheck_transafer_item + '" />');
            $('#purchase_item_selection_popup').modal('hide');
            /******* Remove Same Item Stock Transfer Entry! Start *******/
            var remove_arr = [];
            $.each(lineitem_objectdata, function(index, value) {
                if(typeof(value.item_id) !== "undefined" && value.item_id !== null && value.item_id == pts_item_id) {
//                if(value.item_id == pts_item_id){
                    remove_arr.push(index);
                }
            });
            if (remove_arr.length != 0) {
               var remove_arr_inc = 0;
               $.each(remove_arr, function (index, value) {
                   var remove_index = value - remove_arr_inc;
                   lineitem_objectdata.splice(remove_index, 1);
                   remove_arr_inc++;
               });
            }
            /******* Remove Same Item Stock Transfer Entry! End *******/
//            lineitem_objectdata = pts_selected_index_lineitems;
            lineitem_objectdata = $.merge(pts_selected_index_lineitems, lineitem_objectdata);
            display_lineitem_html(lineitem_objectdata);
            $('#select_all_purchase_to_sell').prop('checked', false);
            $('#transfer_detail_id').val('');
            $("#category_id").val(null).trigger("change");
            $("#item_id").val(null).trigger("change");
            $("#grwt").val('');
            $("#less").val('');
            $('#less').removeAttr('readonly', 'readonly');
            $("#net_wt").val('');
            $("#tunch").val(null).trigger("change");
            $("#touch_data_id").val('');
            $("#wstg").val('');
            $("#fine").val('');
            $("#image").val('');
            $("#file_upload").val('');
            $("#line_items_index").val('');
        });
        
        $(document).on('change', '#tunch_textbox', function(){
            if($('#tunch_textbox').prop("checked") == true){
                $('.touch_select').hide();
                $('.touch_input').show();
            } else {
                $('.touch_select').show();
                $('.touch_input').hide();
            }
            $("#tunch").val(null).trigger("change");
            $("#touch_data_id").val('');
        });
        $("#tunch_textbox").prop("checked", true).trigger('change');
        
        $(document).on('change', '#tunch', function () {
            if(edit_line_item == 0){
                var tunch = $('#tunch').val();
                var from_department = $('#from_department').val();
                var category_id = $('#category_id').val();
                var item_id = $('#item_id').val();
                if (tunch != '' && tunch != null) {
                    $.ajax({
                        url: "<?php echo base_url('stock_transfer/get_ntwt_and_fine_from_stock'); ?>",
                        type: "POST",
                        data: {from_department: from_department, category_id: category_id, item_id: item_id, tunch: tunch},
                        success: function(response){
                            var json = $.parseJSON(response);
                            if(json != ''){
                                $('#grwt').val(round(json.grwt, 2).toFixed(3));
                                $('#less').val(round(json.less, 2).toFixed(3));
                                $('#net_wt').val(round(json.net_weight, 2).toFixed(3));
                                $('#wstg').val(json.st_default_wastage);
                                $('#net_wt').trigger('change');
//                                $('#fine').val(round(json.fine, 2).toFixed(3));
                            } else {
                                $('#grwt').val('');
                                $('#less').val('');
                                $('#net_wt').val('');
                                $('#wstg').val('');
                                $('#fine').val('');
                            }
                        }
                    });
                } else {
                    $('#grwt').val('');
                    $('#less').val('');
                    $('#net_wt').val('');
                    $('#wstg').val('');
                    $('#fine').val('');
                }
            } else {
                edit_line_item = 0;
            }
        });
        
        $(document).on('focusout', '#touch_data_id', function () {
            if(edit_line_item == 0){
                var tunch = $('#touch_data_id').val();
                var from_department = $('#from_department').val();
                var category_id = $('#category_id').val();
                var item_id = $('#item_id').val();
                if (tunch != '' && tunch != null) {
                    $.ajax({
                        url: "<?php echo base_url('stock_transfer/get_ntwt_and_fine_from_stock'); ?>",
                        type: "POST",
                        data: {from_department: from_department, category_id: category_id, item_id: item_id, tunch: tunch},
                        success: function(response){
                            var json = $.parseJSON(response);
                            if(json != ''){
                                $('#grwt').val(round(json.grwt, 2).toFixed(3));
                                $('#less').val(round(json.less, 2).toFixed(3));
                                $('#net_wt').val(round(json.net_weight, 2).toFixed(3));
                                $('#wstg').val(json.st_default_wastage);
                                $('#net_wt').trigger('change');
//                                $('#fine').val(round(json.fine, 2).toFixed(3));
                            } else {
                                $('#grwt').val('');
                                $('#less').val('');
                                $('#net_wt').val('');
                                $('#wstg').val('');
                                $('#fine').val('');
                            }
                        }
                    });
                } else {
                    $('#grwt').val('');
                    $('#less').val('');
                    $('#net_wt').val('');
                    $('#wstg').val('');
                    $('#fine').val('');
                }
            } else {
                edit_line_item = 0;
            }
        });

        $(document).on('keyup change', '#grwt, #less', function () {
            var grwt = parseFloat($('#grwt').val()) || 0;
            grwt = round(grwt, 2).toFixed(3);
            var less = parseFloat($('#less').val()) || 0;
            less = round(less, 2).toFixed(3);
            var net_wt = 0;
            net_wt = parseFloat(grwt) - parseFloat(less);
            net_wt = round(net_wt, 2).toFixed(3);
            $('#net_wt').val(net_wt);
        });

        $(document).bind('keyup change', '#net_wt, #tunch, #wstg', function () {
            var net_wt = parseFloat($('#net_wt').val()) || 0;
            net_wt = round(net_wt, 2).toFixed(3);
            var wstg = parseFloat($('#wstg').val()) || 0;
            if($('#tunch_textbox').prop("checked") == true){
                var tunch = parseFloat($('#touch_data_id').val()) || 0;
            } else {
                var tunch = parseFloat($('#tunch').val()) || 0;
            }
            var fine = 0;
            fine = parseFloat(net_wt) * (parseFloat(tunch) + parseFloat(wstg)) / 100;
            fine = round(fine, 2).toFixed(3);
            $('#fine').val(fine);
        });
        
        <?php if(!isset($stock_transfer_data->audit_status) || (isset($stock_transfer_data->audit_status) && $stock_transfer_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
            $(document).bind("keydown", function(e){
                if(e.ctrlKey && e.which == 83){
                    e.preventDefault();
                    if(module_submit_flag == 0 ){
                        $("#stock_transfer_form").submit();
                        return false;
                    }
                }
            });
        <?php } ?>
        
        
        $(document).on('submit', '#stock_transfer_form', function () {
            if ($.trim($("#datepicker1").val()) == '') {
                show_notify('Please Select Transfer Date.', false);
                $("#datepicker1").focus();
                return false;
            }
            if ($.trim($("#from_department").val()) == '') {
                show_notify('Please Select From Department Name.', false);
                $("#from_department").select2('open');
                return false;
            }
            if ($.trim($("#to_department").val()) == '') {
                show_notify('Please Select To Department Name.', false);
                $("#to_department").select2('open');
                return false;
            }
            if (lineitem_objectdata == '') {
                show_notify("Please Add Stock Item.", false);
                return false;
            }
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            <?php if (isset($stock_transfer_data->stock_transfer_id) && !empty($stock_transfer_data->stock_transfer_id)) { ?>
                postData.append('from_department', <?= $stock_transfer_data->from_department ?>);
            <?php } ?>
            $.ajax({
                url: "<?= base_url('stock_transfer/save_stock_transfer') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
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
                        $('.changed-input').removeClass('changed-input');
                        window.location.href = "<?php echo base_url('stock_transfer/stock_transfer_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        $('.changed-input').removeClass('changed-input');
                        window.location.href = "<?php echo base_url('stock_transfer/stock_transfer_list') ?>";
                    }
                    return false;
                },
            });
            module_submit_flag = 1;
            return false;
        });

        $('#add_lineitem').on('click', function () {
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
            
            if($('#tunch_textbox').prop("checked") == true){
                if ($.trim($("#touch_data_id").val()) == '') {
                    $("#touch_data_id").focus();
                    show_notify("Please Enter Tunch!", false);
                    return false;
                }
            } else {
                if ($.trim($("#tunch").val()) == '') {
                    $("#tunch").select2('open');
                    show_notify("Please select Tunch!", false);
                    return false;
                }
            }
            var grwt = $("#grwt").val();
            if (grwt == '' || grwt == null) {
                $("#grwt").focus();
                show_notify("Please Enter Gr.Wt.!", false);
                return false;
            } else {
                var total_grwt_sell = $('#total_grwt_sell').val();
                <?php if($without_purchase_sell_allow == '1'){ ?>
                    if(total_grwt_sell != '' && total_grwt_sell != null){
                        var grwt = parseFloat($('#grwt').val()) || 0;
                        grwt = round(grwt, 2).toFixed(3);
                        if(parseFloat(grwt) < parseFloat(total_grwt_sell)){
                            show_notify("GrWt Should Be Grater Than " + total_grwt_sell , false);
                            $('#grwt').val('');
                            $("#grwt").focus();
                            return false;
                        }
                    }
                <?php } ?>
            }
//            if (grwt < 0) {
//                $("#grwt").focus();
//                show_notify("Not allow to enter grwt < 0!", false);
//                return false;
//            }
//            var less = $("#less").val();
//            if (less < 0) {
//                $("#less").focus();
//                show_notify("Not allow to enter less < 0!", false);
//                return false;
//            }
//            var net_wt = $("#net_wt").val();
//            if (net_wt < 0) {
//                $("#net_wt").focus();
//                show_notify("Not allow to enter net_wt < 0!", false);
//                return false;
//            }
//            var wstg = $("#wstg").val();
//            if (wstg < 0) {
//                $("#wstg").focus();
//                show_notify("Not allow to enter wstg < 0!", false);
//                return false;
//            }
            
            var hasMatch =false;
            var line_items_index = $("#line_items_index").val();
            var rfid_number = $("#rfid_number").val();
            for (var line_i = 0; line_i < lineitem_objectdata.length; ++line_i) {
                var itemrow = lineitem_objectdata[line_i];
                if(rfid_number != '' && rfid_number != null && itemrow.rfid_number == rfid_number && line_items_index == ''){
                    show_notify("RFID Used in this Entry!", false);
                    $("#category_id").val(null).trigger("change");
                    $("#item_id").val(null).trigger("change");
                    $("#grwt").val('');
                    $("#less").val('');
                    $('#less').removeAttr('readonly', 'readonly');
                    $("#net_wt").val('');
                    $("#tunch").val(null).trigger("change");
                    $("#touch_data_id").val('');
                    $("#wstg").val('');
                    $("#fine").val('');
                    $("#from_item_stock_rfid_id").val('');
                    $("#to_item_stock_rfid_id").val('');
                    $("#rfid_number").val('');
                    $('#rfid_number').focus();
                    return false;
                }
            }

            $("#add_lineitem").attr('disabled', 'disabled');
            var transfer_detail_id = $("#transfer_detail_id").val();
            if (typeof (transfer_detail_id) != "undefined" && transfer_detail_id !== null) {
                $('.line_item_form #deleted_transfer_detail_id[value="' + transfer_detail_id + '"]').remove();
            }
            var key = '';
            var value = '';
            var lineitem = {};
            var is_validate = '0';
            
            $('input[name^="line_items_data"]').each(function () {
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
            <?php if($use_category == '1') { ?>
                var cate_data = $('#category_id').select2('data');
                var category_name = cate_data[0].text;
            <?php } ?>
            var item_data = $('#item_id').select2('data');
            var item_name = item_data[0].text;
            if($('#tunch_textbox').prop("checked") == true){
                var tunch_name = $('#touch_data_id').val();
            } else {
                var tunch_data = $('#tunch').select2('data');
                var tunch_name = tunch_data[0].text;
            }
//            $('select[name^="line_items_data"]').each(function (index) {
//                key = $(this).attr('name');
//                key = key.replace("line_items_data[", "");
//                key = key.replace("]", "");
//                $.each(lineitem_objectdata, function (index, value) {
//                    if (value.category_name == category_name && value.item_name == item_name && value.tunch_name == tunch_name && typeof (value.id) != "undefined" && value.id !== null) {
//                        $('input[name^="line_items_data"]').each(function (index) {
//                            keys = $(this).attr('name');
//                            keys = keys.replace("line_items_data[", "");
//                            keys = keys.replace("]", "");
//                            if (keys == 'id') {
//                                if (value.id != $(this).val()) {
//                                    is_validate = '1';
//                                    show_notify("You cannot Add this Item. This Item has been used!", false);
//                                    $("#add_lineitem").removeAttr('disabled', 'disabled');
//                                    return false;
//                                }
//                            }
//                        });
//                    } else if (value.category_name == category_name && value.item_name == item_name && value.tunch_name == tunch_name) {
//                        if(transfer_index !== index){
//                            is_validate = '1';
//                            show_notify("You cannot Add this Item. This Item has been used!", false);
//                            $("#add_lineitem").removeAttr('disabled', 'disabled');
//                            return false;
//                        }
//                    }
//                });
//                if (is_validate == '1') {
//                    return false;
//                }
//            });
            if($('#tunch_textbox').prop("checked") == true){
                lineitem['tunch_textbox'] = '1';
            } else {
                lineitem['tunch_textbox'] = '0';
            }
            if (is_validate != '1') {
                <?php if($use_category == '1') { ?>
                    var category_data = $('#category_id').select2('data');
                    lineitem['category_name'] = category_data[0].text;
                <?php } ?>
                var item_data = $('#item_id').select2('data');
                lineitem['item_name'] = item_data[0].text;
                lineitem['tunch_name'] = tunch_name;
                if($('#tunch_textbox').prop("checked") == true){
                    lineitem['tunch'] = $('#touch_data_id').val();
                } else {
                    lineitem['tunch'] = $('#tunch').val();
                }
                lineitem['total_grwt_sell'] = $('#total_grwt_sell').val();
                $.ajax({
                    url: "<?php echo base_url('sell/get_category_group'); ?>/" + item_id,
                    type: "GET",
                    contentType: "application/json",
                    data: "",
                    success: function(response){
                        var json = $.parseJSON(response);
    //                    console.log(json);
                        lineitem['group_name'] = json;
                        if(lineitem['group_name'] == '<?php echo CATEGORY_GROUP_GOLD_ID; ?>'){
                            lineitem['grwt'] = round(lineitem['grwt'], 2).toFixed(3);
                            lineitem['less'] = round(lineitem['less'], 2).toFixed(3);
                            lineitem['net_wt'] = round(lineitem['net_wt'], 2).toFixed(3);
                            lineitem['fine'] = round(lineitem['fine'], 2).toFixed(3);
                        } else if(lineitem['group_name'] == '<?php echo CATEGORY_GROUP_SILVER_ID; ?>'){
                            lineitem['grwt'] = round(lineitem['grwt'], 1).toFixed(3);
                            lineitem['less'] = round(lineitem['less'], 1).toFixed(3);
                            lineitem['net_wt'] = round(lineitem['net_wt'], 1).toFixed(3);
                            lineitem['fine'] = round(lineitem['fine'], 1).toFixed(3);
                        } else if(lineitem['group_name'] == '<?php echo CATEGORY_GROUP_OTHER_ID; ?>'){
                            lineitem['grwt'] = round(lineitem['grwt'], 2).toFixed(3);
                            lineitem['less'] = 0;
                            lineitem['net_wt'] = round(lineitem['grwt'], 2).toFixed(3);
                            lineitem['wstg'] = 0;
                            lineitem['fine'] = 0;
                        }
                        
                        if(lineitem['group_name'] == '<?php echo CATEGORY_GROUP_OTHER_ID; ?>'){ // Tunch Zero for Cagetory Group : Other
                            $("#touch_data_id").val(0);
                            $("#touch_id").val(0);
                            lineitem['tunch'] = 0;
                            lineitem['tunch_textbox'] = 1;
                        }

                        var transfer_detail_id = $("#transfer_detail_id").val();
                        var from_department = $("#from_department").val();
                        if($('#tunch_textbox').prop("checked") == true){
                            var tunch = $("#touch_data_id").val();
                        } else {
                            var tunch =  $("#tunch").val();
                        }
                        var grwt = $("#grwt").val();
                        $.ajax({
                            url: "<?php echo base_url('sell/get_item_stock'); ?>/",
                            type: 'POST',
                            async: false,
                            data: {process_id : from_department, category_id : category_id, item_id : item_id, touch_id : tunch, transfer_detail_id : transfer_detail_id},
                            success: function (response) {
                                var json = $.parseJSON(response);
                                <?php if($without_purchase_sell_allow == '1'){ ?>
                                if(parseFloat(grwt) > parseFloat(json.grwt)){
                                    show_notify('Please Enter GrWt Less than of ' + json.grwt, false);
                                    $("#grwt").focus();
                                    $("#add_lineitem").removeAttr('disabled', 'disabled');
                                    return false;
                                }
                                <?php } ?>
                                var new_lineitem = JSON.parse(JSON.stringify(lineitem));
//                                console.log(new_lineitem);
                                line_items_index = $("#line_items_index").val();
                                if (line_items_index != '') {
                                    lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
                                } else {
                                    lineitem_objectdata.push(new_lineitem);
                                }
                                display_lineitem_html(lineitem_objectdata);
                                $('#transfer_detail_id').val('');
                                $("#tunch_textbox").prop("checked", true).trigger('change');
                                $("#category_id").val(null).trigger("change");
                                $("#item_id").val(null).trigger("change");
                                $("#grwt").val('');
                                $("#less").val('');
                                $('#less').removeAttr('readonly', 'readonly');
                                $("#net_wt").val('');
                                $("#tunch").val(null).trigger("change");
                                $("#touch_data_id").val('');
                                $("#wstg").val('');
                                $("#fine").val('');
                                $('#category_id').removeAttr('disabled','disabled');
                                $('#item_id').removeAttr('disabled','disabled');
                                $('.tunch').removeAttr('disabled','disabled');
                                $("#tunch_textbox").removeAttr('disabled', 'disabled');
                                transfer_index= '';
                                $("#line_items_index").val('');
//                                $('#total_grwt_sell').val('');
                            }
                        });
                    }
                });
            }
            $("#add_lineitem").removeAttr('disabled', 'disabled');
            $("#from_item_stock_rfid_id").val('');
            $("#to_item_stock_rfid_id").val('');
            $("#rfid_number").val('');
            $('#grwt').removeAttr('disabled','disabled');
            $('#net_wt').removeAttr('disabled','disabled');
            $('#fine').removeAttr('disabled','disabled');
            $('#rfid_number').removeAttr('disabled','disabled');
        });
        
        $(document).on('keypress', '#rfid_number', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                var from_department = $.trim($("#from_department").val());
                if ( from_department == '') {
                    show_notify('Please Select From Department.', false);
                    $("#from_department").select2('open');
                    return false;
                }
                $('#ajax-loader').show();
                var from_item_stock_rfid_id = $('#from_item_stock_rfid_id').val();
                var to_item_stock_rfid_id = $('#to_item_stock_rfid_id').val();
                var rfid_number = $('#rfid_number').val();
                $.ajax({
                    url: "<?php echo base_url('stock_transfer/get_lineitem_based_on_rfid'); ?>/",
                    type: 'POST',
                    async: false,
                    data: {from_department: from_department, from_item_stock_rfid_id : from_item_stock_rfid_id, to_item_stock_rfid_id: to_item_stock_rfid_id, rfid_number : rfid_number},
                    success: function (response) {
                        $('#ajax-loader').hide();
                        var json = $.parseJSON(response);
                        $('#rfid_number').val('');
                        if(json.rfid_used == '1'){
                            show_notify(json.rfid_used_msg, false);
                            return false;
                        }
                        if(json.category_id){
                            <?php if($use_category == '1') { ?>
                                $("#category_id").val(json.category_id).trigger("change");
                                setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/') ?>" + json.category_id);
                            <?php } else { ?>
                                $("#category_id").val(json.category_id);
                            <?php } ?>
                            setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + json.item_id);
//                            e.preventDefault();
                            $("#grwt").val(json.grwt);
                            $("#less").val(json.less);
                            $("#net_wt").val(json.net_wt);
                            $("#touch_data_id").val(json.touch_id);
                            $(".touch").val(json.touch_id).trigger("change");
                            $("#wstg").val(json.wstg);
                            $("#fine").val(json.fine);
                            $("#charges_amt").val(json.charges_amt);
                            $("#from_item_stock_rfid_id").val(json.from_item_stock_rfid_id);
                            $("#to_item_stock_rfid_id").val(json.to_item_stock_rfid_id);
                            $("#rfid_number").val(json.rfid_number);
                            $('#add_lineitem').click();
                            $('#from_item_stock_rfid_id').val('');
                            $('#to_item_stock_rfid_id').val('');
                        } else {
                            var from_department_data = $('#from_department').select2('data');
                            show_notify('RFID Not Found In '+ from_department_data[0].text +' Department!', false);
                        }
                        $('#rfid_number').focus();
                    }
                });
                e.preventDefault();
                return false;
            }
        });
    });
    
    function set_to_department(){
        var from_department = $('#from_department').val();
        <?php if($without_purchase_sell_allow == '1'){ ?>
            $('#category_id').val(null);
            pts_lineitem_objectdata = [];
            display_pts_lineitem_html(pts_lineitem_objectdata);
            $('#to_department').val(null).trigger('change');
            if (from_department != '' && from_department != null) {
                <?php if($use_category == '1') { ?>
                    initAjaxSelect2($("#category_id"), "<?= base_url('app/category_from_stock_department_select2_source') ?>/" + from_department);
                <?php } ?>
                initAjaxSelect2($("#to_department"), "<?= base_url('app/process_master_from_process_select2_source') ?>/" + from_department);
            }
            $('#to_department').val(null).trigger('change');
        <?php } else { ?>
            <?php if($use_category == '1') { ?>
                initAjaxSelect2($("#category_id"), "<?= base_url('app/category_select2_source') ?>");
            <?php } ?>
            initAjaxSelect2($("#to_department"), "<?= base_url('app/process_master_from_process_select2_source') ?>/" + from_department);
        <?php } ?>
    }

    function display_pts_lineitem_html(pts_lineitem_objectdata){
        var pts_lineitem_html = '';
        var pts_total_grwt = 0;
        var pts_total_less = 0;
        var pts_total_ntwt = 0;
        var pts_total_average = 0;
        var pts_total_fine = 0;
        $.each(pts_lineitem_objectdata, function (index, value) {
            var row_html_order = '<tr class="lineitem_index_' + index + ' _' + index + '">';
                if(lineitem_objectdata.length !== 0){
                    var row_added = 0;
                    $.each(lineitem_objectdata, function (li_index, li_value) {
                        if(li_value.purchase_sell_item_id == value.purchase_sell_item_id && li_value.stock_type == value.stock_type){
                            if(row_added == 0){
                                row_added = 1;
                                pts_lineitem_objectdata[index].transfer_detail_id = li_value.transfer_detail_id;
                                if(value.less_allow == 1){
                                    var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ li_value.less + '" style="width:100px;"> ' + value.less;
                                } else {
                                    var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ li_value.less + '" disabled="" style="width:100px;"> ' + value.less;
                                }
                                row_html_order += '<td class="text-center">' +
                                '<input type="checkbox" data-item_id="' + value.item_id + '" data-pts_selected_index="' + index + '" class="pts_selected_index" checked disabled>' +
                                '</td>' +
                                '<td>' + value.account_name + ' ['+ value.sell_date +']</td>' +
                                '<td>' + value.item_name + '</td>' +
                                '<td class="text-right"><input type="text" name="pts_grwt[]" id="pts_grwt_' + index + '" data-pts_selected_index="' + index + '" class="pts_grwt num_only" value="'+ li_value.grwt + '" style="width:100px;"> ' + value.grwt + ' </td>' +
                                '<td class="text-right">' + input_less + '</td>' +
                                '<td class="text-right" id="pts_net_wt_' + index + '">' + li_value.net_wt + '</td>' +
                                '<td class="text-right">' + value.touch_id + '</td>' +
                                '<td class="text-right"><input type="text" name="pts_wstg[]" id="pts_wstg_' + index + '" data-pts_selected_index="' + index + '" class="pts_wstg num_only" value="'+ li_value.wstg + '" style="width:100px;"> ' + value.wstg + ' </td>' +
                                '<td class="text-right" id="pts_fine_' + index + '">' + li_value.fine + '</td>';
                                pts_total_grwt = parseFloat(pts_total_grwt) + parseFloat(li_value.grwt);
                                pts_total_less = parseFloat(pts_total_less) + parseFloat(li_value.less);
                                pts_total_ntwt = parseFloat(pts_total_ntwt) + parseFloat(li_value.net_wt);
                                pts_total_fine = parseFloat(pts_total_fine) + parseFloat(li_value.fine);
                            }
                        }
                    });
                    if(row_added == 0){
                        if(value.less_allow == 1){
                            var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ value.less + '" style="width:100px;">';
                        } else {
                            var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ value.less + '" disabled="" style="width:100px;">';
                        }
                        row_html_order += '<td class="text-center">' +
                        '<input type="checkbox" data-item_id="' + value.item_id + '" data-pts_selected_index="' + index + '" class="pts_selected_index">' +
                        '</td>' +
                        '<td>' + value.account_name + ' ['+ value.sell_date +']</td>' +
                        '<td>' + value.item_name + '</td>' +
                        '<td class="text-right"><input type="text" name="pts_grwt[]" id="pts_grwt_' + index + '" data-pts_selected_index="' + index + '" class="pts_grwt num_only" value="'+ value.grwt + '" style="width:100px;"></td>' +
                        '<td class="text-right">' + input_less + '</td>' +
                        '<td class="text-right" id="pts_net_wt_' + index + '">' + value.net_wt + '</td>' +
                        '<td class="text-right">' + value.touch_id + '</td>' +
                        '<td class="text-right"><input type="text" name="pts_wstg[]" id="pts_wstg_' + index + '" data-pts_selected_index="' + index + '" class="pts_wstg num_only" value="'+ value.wstg + '" style="width:100px;"></td>' +
                        '<td class="text-right" id="pts_fine_' + index + '">' + value.fine + '</td>';
                        pts_total_grwt = parseFloat(pts_total_grwt) + parseFloat(value.grwt);
                        pts_total_less = parseFloat(pts_total_less) + parseFloat(value.less);
                        pts_total_ntwt = parseFloat(pts_total_ntwt) + parseFloat(value.net_wt);
                        pts_total_fine = parseFloat(pts_total_fine) + parseFloat(value.fine);
                    }
                    
                } else {
                    if(value.less_allow == 1){
                        var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ value.less + '" style="width:100px;">';
                    } else {
                        var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ value.less + '" disabled="" style="width:100px;">';
                    }
                    row_html_order += '<td class="text-center">' +
                    '<input type="checkbox" data-item_id="' + value.item_id + '" data-pts_selected_index="' + index + '" class="pts_selected_index">' +
                    '</td>' +
                    '<td>' + value.account_name + ' ['+ value.sell_date +']</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right"><input type="text" name="pts_grwt[]" id="pts_grwt_' + index + '" data-pts_selected_index="' + index + '" class="pts_grwt num_only" value="'+ value.grwt + '" style="width:100px;"></td>' +
                    '<td class="text-right">' + input_less + '</td>' +
                    '<td class="text-right" id="pts_net_wt_' + index + '">' + value.net_wt + '</td>' +
                    '<td class="text-right">' + value.touch_id + '</td>' +
                    '<td class="text-right"><input type="text" name="pts_wstg[]" id="pts_wstg_' + index + '" data-pts_selected_index="' + index + '" class="pts_wstg num_only" value="'+ value.wstg + '" style="width:100px;"></td>' +
                    '<td class="text-right" id="pts_fine_' + index + '">' + value.fine + '</td>';
                    pts_total_grwt = parseFloat(pts_total_grwt) + parseFloat(value.grwt);
                    pts_total_less = parseFloat(pts_total_less) + parseFloat(value.less);
                    pts_total_ntwt = parseFloat(pts_total_ntwt) + parseFloat(value.net_wt);
                    pts_total_fine = parseFloat(pts_total_fine) + parseFloat(value.fine);
                }
                row_html_order += '</tr>';
            pts_lineitem_html += row_html_order;
        });
        $('tbody#purchase_item_selection_list').html(pts_lineitem_html);
        $('#pts_total_grwt').html(pts_total_grwt.toFixed(3));
        $('#pts_total_less').html(pts_total_less.toFixed(3));
        $('#pts_total_ntwt').html(pts_total_ntwt.toFixed(3));
        if(pts_total_ntwt != 0 && pts_total_fine != 0){
            pts_total_average = pts_total_fine / pts_total_ntwt * 100;
        }
        $('#pts_total_average').html(pts_total_average.toFixed(3));
        $('#pts_total_fine').html(round(pts_total_fine, 2).toFixed(3));
        checked_average_value();
    }
    
    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        var total_gold_grwt = 0;
        var total_gold_less = 0;
        var total_gold_ntwt = 0;
        var total_gold_fine = 0;
        var total_silver_fine = 0;
        var department_disabled_or_not = 0;
        
        if($.isEmptyObject(lineitem_objectdata)){
            $('#from_department').removeAttr('disabled','disabled');
            $('#after_disabled_from_department').remove();
        } else {
            var from_department = $('#from_department').val();
            $('#from_department').attr('disabled','disabled');
            $('#from_department').closest('div').append('<input type="hidden" name="from_department" id="after_disabled_from_department" value="' + from_department + '" />');
        }
        
        $.each(lineitem_objectdata, function (index, value) {
            total_gold_grwt = parseFloat(total_gold_grwt) + parseFloat(value.grwt);
            total_gold_less = parseFloat(total_gold_less) + parseFloat(value.less);
            total_gold_ntwt = parseFloat(total_gold_ntwt) + parseFloat(value.net_wt);
            if(value.group_name == '<?php echo CATEGORY_GROUP_GOLD_ID; ?>'){
                total_gold_fine = total_gold_fine + parseFloat(value.fine);
                var gold_value = round(value.fine, 2).toFixed(3);
                var silver_value = '';
            } else if(value.group_name == '<?php echo CATEGORY_GROUP_SILVER_ID; ?>'){
                total_silver_fine = total_silver_fine + parseFloat(value.fine);
                var gold_value = '';
                var silver_value = round(value.fine, 2).toFixed(3);
            } else if(value.group_name == '<?php echo CATEGORY_GROUP_OTHER_ID; ?>'){
                var gold_value = 0;
                var silver_value = 0;
            }
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.stock_item_delete == 'allow'){
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_st_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            } else {
                department_disabled_or_not = 1;
            }
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>';
            <?php if($use_category == '1') { ?>
                row_html += '<td>' + value.category_name + '</td>';
            <?php } ?>
            row_html += '<td>' + value.item_name + '</td>' +
                    '<td class="text-right">' + value.tunch_name + '</td>' +
                    '<td class="text-right">' + value.grwt + '</td>' +
                    '<td class="text-right">' + value.less + '</td>' +
                    '<td class="text-right">' + value.net_wt + '</td>' +
                    '<td class="text-right">' + value.wstg + '</td>' +
                    '<td class="text-right">' + gold_value + '</td>' +
                    '<td class="text-right">' + silver_value + '</td>';
            new_lineitem_html += row_html;
        });
        if(department_disabled_or_not == 1){
            var from_department = $('#from_department').val();
            var to_department = $('#to_department').val();
            $('#stock_transfer_form').append('<input type="hidden" name="from_department" value="' + from_department + '" />');
            $('#stock_transfer_form').append('<input type="hidden" name="to_department" value="' + to_department + '" />');
            $('#from_department').attr('disabled','disabled');
            $('#to_department').attr('disabled','disabled');
        }
        total_gold_grwt = round(total_gold_grwt, 2).toFixed(3);
        total_gold_less = round(total_gold_less, 2).toFixed(3);
        total_gold_ntwt = round(total_gold_ntwt, 2).toFixed(3);
        total_gold_fine = round(total_gold_fine, 2).toFixed(3);
        total_silver_fine = round(total_silver_fine, 1).toFixed(3);
        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#total_gold_grwt').html(total_gold_grwt);
        $('#total_gold_less').html(total_gold_less);
        $('#total_gold_ntwt').html(total_gold_ntwt);
        $('#total_gold_fine').html(total_gold_fine);
        $('#total_silver_fine').html(total_silver_fine);
        $('#stock_transfer_form').append('<input type="hidden" name="total_gold_fine" value="' + total_gold_fine + '" />');
        $('#stock_transfer_form').append('<input type="hidden" name="total_silver_fine" value="' + total_silver_fine + '" />');
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_st_item").addClass('hide');
        transfer_index = index;
        var value = lineitem_objectdata[index];
        edit_line_item = 1;
//        alert(value.total_grwt_sell);
        if(value.tunch_textbox == 1){
            $("#tunch_textbox").prop("checked", true);
        } else {
            $("#tunch_textbox").prop("checked", false);
        }
        var from_department = $('#from_department').val();
        <?php if($use_category == '1') { ?>
            <?php if($without_purchase_sell_allow == '1'){ ?>
                initAjaxSelect2($("#category_id"), "<?= base_url('app/category_from_stock_department_select2_source') ?>/" + from_department);
            <?php } else { ?>
                initAjaxSelect2($("#category_id"), "<?= base_url('app/category_select2_source') ?>");
            <?php } ?>
            setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/') ?>" + value.category_id);
        <?php } else { ?>
            $("#category_id").val(value.category_id);
        <?php } ?>
        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id') ?>/" + value.item_id);
        $('#total_grwt_sell').val(value.total_grwt_sell);
        if(value.purchase_sell_item_id == '' || value.purchase_sell_item_id == null){
            $("#line_items_index").val(index);
            if (typeof (value.transfer_detail_id) != "undefined" && value.transfer_detail_id !== null) {
                $("#transfer_detail_id").val(value.transfer_detail_id);
            }
            $("#purchase_sell_item_id").val(value.purchase_sell_item_id);
            $("#stock_type").val(value.stock_type);
            $("#grwt").val(value.grwt);
            $("#less").val(value.less);
            $("#net_wt").val(value.net_wt);
            $("#wstg").val(value.wstg);
            $("#fine").val(value.fine);
            $("#from_item_stock_rfid_id").val(value.from_item_stock_rfid_id);
            $("#to_item_stock_rfid_id").val(value.to_item_stock_rfid_id);
            $("#rfid_number").val(value.rfid_number);
            
            if($('#tunch_textbox').prop("checked") == true){
                $('.touch_select').hide();
                $('.touch_input').show();
                $("#touch_data_id").val(value.tunch);
            } else {
                $('.touch_select').show();
                $('.touch_input').hide();
                <?php if($without_purchase_sell_allow == '1'){ ?>
                    initAjaxSelect2($("#tunch"), "<?= base_url('app/tunch_from_stock_item_select2_source') ?>/" + value.item_id + '/' + from_department + '/' + value.category_id);
                <?php } else { ?>
                    initAjaxSelect2($("#tunch"), "<?= base_url('app/touch_purity_select2_source') ?>");
                <?php } ?>
                setSelect2Value($("#tunch"), "<?= base_url('app/set_touch_exchange_select2_val_by_id') ?>/" + value.tunch);
            }
            $("#stock_item_delete").val(value.stock_item_delete);
        } else {
            $("#item_id").val(null).trigger("change");
        }
        if(value.stock_item_delete == 'not_allow'){
            $('#category_id').attr('disabled','disabled');
            $('#item_id').attr('disabled','disabled');
            $('.tunch').attr('disabled','disabled');
            $('#tunch_textbox').attr('disabled','disabled');
        }
        
        if(value.rfid_number != '' && value.rfid_number != null){
            $('#category_id').attr('disabled','disabled');
            $('#item_id').attr('disabled','disabled');
            $('#grwt').attr('disabled','disabled');
            $('.tunch').attr('disabled','disabled');
            $('#net_wt').attr('disabled','disabled');
            $('#fine').attr('disabled','disabled');
            $('#tunch_textbox').attr('disabled','disabled');;
            $('#rfid_number').attr('disabled','disabled');
        }
        
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        if (confirm('Are you sure ?')) {
            value = lineitem_objectdata[index];
            if (typeof (value.transfer_detail_id) != "undefined" && value.transfer_detail_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_transfer_detail_id[]" id="deleted_transfer_detail_id" value="' + value.transfer_detail_id + '" />');
            }
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }
    
    function checked_average_value() {
        var total_grwt = 0;
        var total_fine = 0;
        var average_value = 0;
        var pts_checked_total_grwt = 0;
        var pts_checked_total_less = 0;
        var pts_checked_total_ntwt = 0;
        var pts_checked_total_average = 0;
        var pts_checked_total_fine = 0;
        
        $.each($(".pts_selected_index:checked"), function(){
            var pts_selected_index = $(this).data('pts_selected_index');
//            console.log(pts_selected_index);
            pts_checked_total_grwt = pts_checked_total_grwt + parseFloat($('#pts_grwt_' +pts_selected_index).val() || 0);
            pts_checked_total_less = pts_checked_total_less + parseFloat($('#pts_less_' +pts_selected_index).val() || 0);
            pts_checked_total_ntwt = pts_checked_total_ntwt + parseFloat($('#pts_net_wt_' +pts_selected_index).text());
            pts_checked_total_fine = pts_checked_total_fine + parseFloat($('#pts_fine_' +pts_selected_index).text() || 0);
        });
         
        $('#pts_checked_total_grwt').html(pts_checked_total_grwt.toFixed(3));
        $('#pts_checked_total_less').html(pts_checked_total_less.toFixed(3));
        $('#pts_checked_total_ntwt').html(pts_checked_total_ntwt.toFixed(3));
        if(pts_checked_total_ntwt != 0 && pts_checked_total_fine != 0){
            pts_checked_total_average = pts_checked_total_fine / pts_checked_total_ntwt * 100;
        }
        $('#pts_checked_total_average').html(pts_checked_total_average.toFixed(3));
        $('#pts_checked_total_fine').html(round(pts_checked_total_fine, 2).toFixed(3));
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


