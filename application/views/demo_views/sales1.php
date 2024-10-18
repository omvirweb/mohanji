<?php $this->load->view('success_false_notify'); ?>
<style>
    .sell_net_balance_data tr th, .sell_net_balance_data tr td{
        padding: 2px 10px;
    }
    .pr0{
        padding-right: 0px;
    }
</style>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" method="post" id="save_sell_purchase" novalidate enctype="multipart/form-data">
        <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
            <input type="hidden" name="sell_id" class="sell_id" value="<?= $sell_data->sell_id ?>">
        <?php } ?>
            <input type="hidden" name="gold_fine_total" id="gold_fine_total" class="gold_fine_total" value="0">
            <input type="hidden" name="silver_fine_total" id="silver_fine_total" class="silver_fine_total" value="0">
            <input type="hidden" name="amount_total" id="amount_total" class="amount_total" value="0">
            <input type="hidden" name="payment_receipt_id" id="payment_receipt_id" class="payment_receipt_id" value="0">
            <input type="hidden" name="metal_payment_receipt_id" id="metal_payment_receipt_id" class="metal_payment_receipt_id" value="0">
            <input type="hidden" name="gold_bhav_id" id="gold_bhav_id" class="gold_bhav_id" value="0">
            <input type="hidden" name="silver_bhav_id" id="silver_bhav_id" class="silver_bhav_id" value="0">
            <input type="hidden" name="transfer_id" id="transfer_id" class="transfer_id" value="0">
            <input type="hidden" name="order_id" id="order_id" value=""/>
            <input type="hidden" id="total_grwt_sell" value=""/>
            <input type="hidden" id="total_grwt_metal" value=""/>
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <h1>
                Add <?=$page_label?> <?=$page_shortcut?>
                <?php $isEdit = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "add");
                $allow_change_date = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow_change_date"); ?>
                <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <?php /*<?php if(!isset($sell_data->account_id) || (isset($sell_data->account_id) && $sell_data->account_id != ADJUST_EXPENSE_ACCOUNT_ID)){ ?>
                    <?php if(!isset($sell_data->audit_status) || (isset($sell_data->audit_status) && $sell_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
                        <button type="submit" class="btn btn-primary pull-right module_save_btn btn-sm" ><?= isset($sell_data->sell_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                    <?php } ?>
                <?php } ?>
                <a href="<?= $list_page_url ?>" class="btn btn-primary pull-right btn-sm" style="margin: 5px;" <?php echo isset($sell_data->sell_id) ? '' : $btn_disable;?>><?=$page_label?> List</a>
                <span class="pull-right" style="margin-right: 20px;">
                    <div class="form-group">
                        <label for="send_sms" class="col-sm-12 input-sm" style="font-size: 18px; line-height: 25px;">
                            <input type="checkbox" name="send_sms" id="send_sms" class="send_sms" >  &nbsp; Send SMS
                        </label>
                    </div>
                </span>*/ ?>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <?php if($isAdd || $isEdit) { ?>
                    <!-- Horizontal Form -->
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
                                    <div class="col-md-1 pr0">
                                        <label for="sell_no">No.</label>
                                        <input type="text" name="sell_no" id="sell_no" class="form-control" readonly value="<?= (isset($sell_data->sell_no) && !empty($sell_data->sell_no)) ? $sell_data->sell_no : ''; ?>">
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-3 pr0">
                                        <label for="account_id">Account Name <span class="required-sign">&nbsp;*</span></label>
                                        <select name="account_id" id="account_id" class="form-control select2" ></select>
                                    </div> 
                                    <?php $department_2 = 0; if($department_2 == '1') { ?>
                                        <div class="col-md-2 pr0">
                                            <label for="process_id">Department <span class="required-sign">&nbsp;*</span></label>
                                            <select name="process_id" id="process_id" class="form-control select2" ></select>
                                        </div>
                                    <?php } else { ?>
                                        <input type="hidden" name="process_id" id="process_id" value="<?php echo (isset($sell_data->process_id) && !empty ($sell_data->process_id)) ? $sell_data->process_id : $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']; ?>" >
                                    <?php } ?>
                                    <div class="col-md-2 pr0">
                                        <label for="date">Date <span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" name="sell_date" id="datepicker2" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control input-datepicker" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?php if (isset($sell_data->sell_date) && !empty($sell_data->sell_date)) { echo date('d-m-Y', strtotime($sell_data->sell_date)); } else { echo date('d-m-Y'); } ?>" style="padding: 5px;">
                                    </div>
                                    <?php if($remark_2 == '1') { ?>
                                        <div class="col-md-3">
                                            <label for="sell_remark">Remark</label>
                                            <input type="text" name="sell_remark" id="sell_remark" class="form-control" value="<?php if (isset($sell_data->sell_remark) && !empty($sell_data->sell_remark)) { echo $sell_data->sell_remark; } else { echo '';} ?>">
                                        </div>
                                    <?php } ?>
                                    <?php /*if($delivered_not_2 == '1') { ?>
                                    <div class="col-md-2">
                                        <label for="delivery_type">Delivered / Not</label>
                                        <select name="delivery_type" id="delivery_type" class="form-control select2" >
                                            <option value="1" <?= (isset($sell_data->delivery_type) && !empty($sell_data->delivery_type)) ? ($sell_data->delivery_type == '1') ? 'Selected' : '' : 'Selected';?> >Delivered</option>
                                            <option value="2" <?= (isset($sell_data->delivery_type) && !empty($sell_data->delivery_type)) ? ($sell_data->delivery_type == '2') ? 'Selected' : '' : '';?>>Not Delivered</option>
                                        </select>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <?php } */?>
                                    <div class="clearfix"></div>
                                    <div class="line_item_form item_fields_div">
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <input type="hidden" name="line_items_data[sell_item_delete]" id="sell_item_delete" value="allow" />
                                        <?php if (isset($sell_item) && !empty($sell_item)) { ?>
                                            <input type="hidden" name="line_items_data[sell_purchase_id]" id="sell_purchase_id" value="0"/>
                                            <input type="hidden" name="line_items_data[order_lot_item_id]" id="order_lot_item_id"/>
                                            <input type="hidden" name="line_items_data[purchase_sell_item_id]" id="purchase_sell_item_id"/>
                                            <input type="hidden" name="line_items_data[stock_type]" id="stock_type" />
                                            <input type="hidden" name="line_items_data[sell_item_id]" id="sell_item_id" />
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <h4>
                                                Line Item [F6]&nbsp;&nbsp;&nbsp;
                                                <?php /*if($tunch_textbox_2 == '1') { ?>
                                                    <span><label style="margin-bottom: 0px;"><input type="checkbox" name="line_items_data[tunch_textbox]" id="tunch_textbox"> <small>Tunch Textbox</small></label></span>
                                                <?php } else { ?>
                                                    <input type="hidden" name="line_items_data[tunch_textbox]" id="tunch_textbox" value="1">
                                                <?php } */?>
                                            </h4>
                                        </div>
                                        <?php /*
                                            $use_rfid = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'use_rfid'));
                                            $use_barcode = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'use_barcode'));
                                            if($use_rfid == 1 || $use_barcode == 1) {
                                        ?>
                                            <div class="col-md-3">
                                                <label for="rfid_number">Enter RFID</label>
                                                <input type="hidden" name="line_items_data[item_stock_rfid_id]" id="item_stock_rfid_id">
                                                <input type="text" name="line_items_data[rfid_number]" id="rfid_number" class="form-control">
                                            </div>
                                        <?php } ?>
                                        <?php if($charges_2 == '1') { ?>
                                            <div class="col-md-1 pr0">
                                                <label for="charges_amt">Charges</label>
                                                <input type="text" name="line_items_data[charges_amt]" id="charges_amt" class="form-control">
                                            </div>
                                        <?php } else { ?>
                                                <input type="hidden" name="line_items_data[charges_amt]" id="charges_amt" value="0">
                                        <?php } */ ?>
                                        <div class="clearfix"></div>
                                        <?php /*<div class="col-md-1 pr0">
                                            <label for="sell_type_id">Type<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[type]" class="form-control sell_type_id select2" id="sell_type_id"></select>
                                        </div>
                                        <?php if($use_category == '1') { ?>
                                            <div class="col-md-2">
                                                <label for="item_id">Category<span class="required-sign">&nbsp;*</span></label>
                                                <select name="line_items_data[category_id]" class="form-control category_id" id="category_id"></select>
                                            </div>
                                        <?php } else { ?>
                                            <input type="hidden" name="line_items_data[category_id]" id="category_id" class="category_id">
                                        <?php } */ ?>
                                        <div class="col-md-2 pr0">
                                            <label for="item_id">Item<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[item_id]" class="form-control item_id select2" id="item_id">
                                                <option> - Select Item - </option>
                                                <option>Item 1</option>
                                                <option>Item 2</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <label for="hsn_code">HSN Code<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[hsn_code]" class="form-control hsn_code num_only" id="hsn_code" placeholder="" value="">
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <label for="grwt">Gr.Wt.<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[grwt]" class="form-control grwt num_only" id="grwt" placeholder="" value="">
                                        </div>
                                        <?php if($less_netwt_2 == '1') { ?>
                                            <div class="col-md-1 pr0">
                                                <label for="less" style="font-size: 12px;">
                                                    Less
                                                    <?php if($ask_less_ad_details_in_sell_purchase == '1' ) { ?>
                                                        <a href="javascript:void(0)" id="less_ad_details" class="module_save_btn" style="margin: 0; font-size: 9px;">Details[F8]</a>
                                                    <?php } ?>
                                                </label>
                                                <input type="text" name="line_items_data[less]" class="form-control less num_only" id="less"  placeholder="" value="">
                                            </div>
                                            <div class="col-md-1 pr0">
                                                <label for="unit">Net.Wt<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="line_items_data[net_wt]" class="form-control net_wt num_only" id="net_wt" placeholder="" value="" readonly="">
                                            </div>
                                        <?php } else { ?>
                                                <input type="hidden" name="line_items_data[less]" id="less" class="less" value="0">
                                                <input type="hidden" name="line_items_data[net_wt]" id="net_wt" class="net_wt">
                                        <?php } ?>
                                        <?php /*<div class="col-md-1 pr0">
                                            <label for="touch_id">Tunch<span class="required-sign">&nbsp;*</span></label>
                                            <div class="touch_select">
                                                <select name="line_items_data[touch_id]" id="touch_id" class="form-control select2 touch_id">
                                                    <option value=""> - Select - </option>
                                                    <?php if(isset($touch) && !empty($touch)){ foreach ($touch as $value) { ?>
                                                        <option value="<?= $value->purity; ?>"<?= isset($carat_id) && $value->purity == $carat_id ? 'selected="selected"' : ''; ?>><?= $value->purity; ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                            <div class="touch_input">
                                                <input type="text" name="line_items_data[touch_id]" id="touch_data_id" class="form-control touch_id num_only" value="">
                                            </div>
                                        </div>
                                        <?php if($wstg_2 == '1') { ?>
                                            <div class="col-md-1 pr0">
                                                <label for="wstg">Wstg<span class="required-sign field_required">&nbsp;*</span></label>
                                                <input type="text" name="line_items_data[wstg]" class="form-control wstg num_only" id="wstg" placeholder="" value="" >
                                                <input type="hidden" name="line_items_data[default_wstg]" id="default_wstg" placeholder="" value="">
                                            </div>
                                        <?php } else { ?>
                                                <input type="hidden" name="line_items_data[wstg]" id="wstg" value="0" class="wstg">
                                                <input type="hidden" name="line_items_data[default_wstg]" id="default_wstg">
                                        <?php } ?>
                                        <div class="col-md-1 pr0">
                                            <label for="fine">Fine</label>
                                            <input type="text" name="line_items_data[fine]" class="form-control fine" id="fine" placeholder="" value="" readonly>
                                        </div>
                                        <?php if($lineitem_image_2 == '1') { ?>
                                            <div class="col-md-1 pr0">
                                                <label for="file_upload">Image</label>
                                                <input type="file" name="line_items_data[file_upload]" id="file_upload" class="from-control" onchange="readURL(this);" accept="image/*" value="" style="width: 90px;">
                                                <input type="hidden" name="line_items_data[image]" id="image" class="from-control">
                                            </div>
                                        <?php } else { ?>
                                                <input type="hidden" name="line_items_data[file_upload]" id="image">
                                                <input type="hidden" name="line_items_data[image]" id="image">
                                        <?php } */ ?>
                                        <div class="col-md-1 pr0">
                                            <label for="rate_per_1_gram"><small>Rate Per 1 Gram</small><span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[rate_per_1_gram]" class="form-control num_only" id="rate_per_1_gram" placeholder="" value="">
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <label for="gross_amount">Gr Amount<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[gross_amount]" class="form-control num_only" id="gross_amount" placeholder="" value="">
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <label for="gst_rate_in">GST Rate in<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[gst_rate_in]" class="form-control" id="gst_rate_in">
                                                <option value="1">Including</option>
                                                <option value="2" Selected >Excluding</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <label for="gst_per">GST %<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[gst_per]" class="form-control num_only" id="gst_rate" placeholder="" value="">
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <label for="labout_other_charges" class="text-info" data-toggle="tooltip" title="Labout + Other Changes" data-html="true" data-placement="top">Charges<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[labout_other_charges]" class="form-control num_only" id="labout_other_charges" placeholder="" value="">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="amount">Amount<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[amount]" class="form-control num_only" id="amount" placeholder="" value="">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-1 pr0">
                                            <label for="gst_amount">GST Amount<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[gst_amount]" class="form-control num_only" id="gst_amount" placeholder="" value="">
                                        </div>
                                        <div class="col-md-3 pr0">
                                            <label for="li_narration">Narration<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[li_narration]" class="form-control num_only" id="li_narration" placeholder="" value="">
                                        </div>
                                        <div class="col-md-8">
                                            <label>&nbsp;</label><br>
                                            <input type="button" id="add_lineitem" class="btn btn-info btn-sm add_lineitem pull-right" value="Add Item" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <div class="col-md-12">
                                        <!--<label>&nbsp;</label>-->
                                        <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
                                            <div class="created_updated_info pull-right" style="margin-left: 10px;">
                                               Created by : <?php echo isset($sell_data ->created_by_name) ? $sell_data ->created_by_name :'' ; ?>
                                               @ <?php echo isset($sell_data ->created_at) ? date('d-m-Y h:i A',strtotime($sell_data ->created_at)) :'' ; ?><br/>
                                               Updated by : <?php echo isset($sell_data ->updated_by_name) ? $sell_data ->updated_by_name :'' ;?>
                                               @ <?php echo isset($sell_data ->updated_at) ? date('d-m-Y h:i A',strtotime($sell_data ->updated_at)) : '' ;?>
                                            </div>
                                        <?php } ?>
                                        <button type="button" id="payment_receipt" class="btn btn-instagram module_save_btn" style="margin:1px;">Payment Receipt [F1]</button>
                                        <button type="button" id="metal_receipt_payment" class="btn btn-info module_save_btn" style="margin:1px;">Metal Receipt Payment [F2]</button>
                                        <button type="button" id="gold_bhav" class="btn btn-success module_save_btn" style="margin:1px;">Gold Bhav [F3]</button>
                                        <button type="button" id="silver_bhav" class="btn btn-danger module_save_btn" style="margin:1px;">Silver Bhav [F4]</button>
                                        <button type="button" id="transfer" class="btn btn-warning module_save_btn" style="margin:1px;">Transfer [F5]</button>
                                        <?php if($ask_ad_charges_in_sell_purchase == '1' ) { ?>
                                            <button type="button" id="ad_charges" class="btn btn-dropbox module_save_btn" style="margin:1px;">Charges [F7]</button>
                                        <?php } ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-12">
                                        <table style="" class="table custom-table border item-table">
                                            <thead>
                                                <tr>
                                                    <th width="5%">Action</th>
                                                    <?php /*<th width="4%">Type</th>
                                                    <?php if($use_category == '1') { ?>
                                                        <th width="10%">Category</th>
                                                    <?php } ?>*/ ?>
                                                    <th width="8%">Description</th>
                                                    <th width="5%" class="text-right">HSN Code</th>
                                                    <th width="5%" class="text-right">Gr.Wt.</th>
                                                    <?php if($less_netwt_2 == '1') { ?>
                                                        <th width="5%" class="text-right">Less</th>
                                                        <th width="5%" class="text-right">Net.Wt</th>
                                                    <?php } ?>
                                                    <?php /*<th width="5%" class="text-right">Tunch</th>
                                                    <?php if($wstg_2 == '1') { ?>
                                                        <th width="4%" class="text-right">Wstg</th>
                                                    <?php } ?>
                                                    <th width="7%" class="text-right">Gold Fine</th>
                                                    <th width="7%" class="text-right">Silver Fine</th>
                                                    <th width="6%" class="text-right">Amount</th>
                                                    <?php if($lineitem_image_2 == '1') { ?>
                                                        <th width="5%">Image</th>
                                                    <?php } */?>
                                                    <th width="5%" class="text-right">Rate Per 1 Gram</th> 
                                                    <th width="5%" class="text-right">Gross Amount</th> 
                                                    <th width="5%" class="text-right">GST Rate</th> 
                                                    <th width="5%" class="text-right"><span class="text-info" data-toggle="tooltip" title="Labout + Other Changes" data-html="true" data-placement="top">Charges</span></th> 
                                                    <th width="5%" class="text-right">Amount</th> 
                                                </tr>
                                            </thead>
                                            <tbody id="lineitem_list"></tbody>
                                            <tfoot id="lineitem_foot_list">
                                                <tr>
                                                    <th></th>
                                                    <?php /*<th></th>
                                                    <?php if($use_category == '1') { ?>
                                                        <th></th>
                                                    <?php } ?>*/ ?>
                                                    <th>Total</th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right"><span id="total_grwt"></span></th>
                                                    <?php if($less_netwt_2 == '1') { ?>
                                                        <th class="text-right"><span id="total_less"></span></th>
                                                        <th class="text-right"><span id="total_ntwt"></span></th>
                                                    <?php } ?>
                                                    <?php /*<th></th>
                                                    <?php if($wstg_2 == '1') { ?>
                                                        <th></th>
                                                    <?php } ?>
                                                    <th class="text-right"><span id="total_gold_fine"></span></th>
                                                    <th class="text-right"><span id="total_silver_fine"></span></th>
                                                    <th class="text-right"><span id="total_other_fine"></span></th>
                                                    <?php if($lineitem_image_2 == '1') { ?>
                                                        <th></th>
                                                    <?php } */?>
                                                    <th class="text-right"></th>
                                                    <th class="text-right">590941.16</th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right">654691.56</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <?php /*<table style="" class="table custom-table pay_rec_table" hidden="">
                                            <tbody id="pop_up_pay_rec_list"></tbody>
                                        </table>
                                        <table style="" class="table custom-table metal_table" hidden="">
                                            <tbody id="pop_up_metal_list"></tbody>
                                        </table>
                                        <table style="" class="table custom-table gold_table" hidden="">
                                            <tbody id="pop_up_gold_list"></tbody>
                                        </table>
                                        <table style="" class="table custom-table silver_table" hidden="">
                                            <tbody id="pop_up_silver_list"></tbody>
                                        </table>
                                        <table style="" class="table custom-table transfer_table" hidden="">
                                            <tbody id="pop_up_transfer_list"></tbody>
                                        </table>
                                        <table style="" class="table custom-table ad_charges_table" hidden="">
                                            <tbody id="pop_up_ad_charges_list"></tbody>
                                        </table>
                                        <table style="" class="table custom-table item-table">
                                            <tbody id="pop_up_total_list">
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="4%"></th>
                                                    <th width="10%"></th>
                                                    <th width="8%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="9%" colspan="2" class="text-right">Bill Balance : </th>
                                                    <!--<th width="4%"></th>-->
                                                    <th width="7%" class="text-right"><span id="bill_gold_fine"></span></th>
                                                    <th width="7%" class="text-right"><span id="bill_silver_fine"></span></th>
                                                    <th width="6%" class="text-right"><span id="bill_amount"></span></th>
                                                    <?php if($lineitem_image_2 == '1') { ?>
                                                        <th width="5%"></th>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Bal.Date : </th>
                                                    <th><span id="balance_date"></span></th>
                                                    <th colspan="2" class="text-right">Old Balance : </th>
                                                    <!--<th></th>-->
                                                    <th class="text-right"><span id="old_gold_fine"></span></th>
                                                    <th class="text-right"><span id="old_silver_fine"></span></th>
                                                    <th class="text-right"><span id="old_amount"></span></th>
                                                    <?php if($lineitem_image_2 == '1') { ?>
                                                        <th></th>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>RFID Pcs</th>
                                                    <th class="text-right"><span id="total_rfid_pcs"></span></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th colspan="2" class="text-right">Net Balance : </th>
                                                    <!--<th></th>-->
                                                    <th class="text-right"><span id="net_gold_fine" class="net_gold_fine label label-success" style="font-size: 14px;"></span></th>
                                                    <th class="text-right"><span id="net_silver_fine" class="net_silver_fine label label-success" style="font-size: 14px;"></span></th>
                                                    <th class="text-right"><span id="net_amount" class="net_amount label label-success" style="font-size: 14px;"></span></th>
                                                    <?php if($lineitem_image_2 == '1') { ?>
                                                        <th></th>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Loose Pcs</th>
                                                    <th class="text-right"><span id="total_loose_pcs"></span></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th colspan="2" class="text-right">Approx Amount : </th>
                                                    <!--<th></th>-->
                                                    <th class="text-right"><span id="app_gold_fine"></span></th>
                                                    <th class="text-right"><span id="app_silver_fine"></span></th>
                                                    <th class="text-right"><span id="app_amount"></span></th>
                                                    <?php if($lineitem_image_2 == '1') { ?>
                                                        <th></th>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>RFID Wt</th>
                                                    <th class="text-right"><span id="total_rfid_wt"></span></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th colspan="2" class="text-right">Approx Net Balance : </th>
                                                    <!--<th></th>-->
                                                    <th class="text-right"><span id="app_net_amt"></span></th>
                                                    <th></th>
                                                    <th></th>
                                                    <?php if($lineitem_image_2 == '1') { ?>
                                                        <th></th>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Loose Wt</th>
                                                    <th class="text-right"><span id="total_loose_wt"></span></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th colspan="2" class="text-right"><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></th>
                                                    <!--<th></th>-->
                                                    <th class="text-right"><?php echo $gold_rate; ?></th>
                                                    <th class="text-right"><?php echo $silver_rate; ?></th>
                                                    <th></th>
                                                    <?php if($lineitem_image_2 == '1') { ?>
                                                        <th></th>
                                                    <?php } ?>
                                                </tr>
                                            </tbody>
                                        </table>*/ ?>
                                        <table style="" class="table custom-table item-table">
                                            <tbody id="pop_up_total_list">
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="10%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="9%" colspan="2" class="text-right">Discount</th>
                                                    <th width="6%" class="text-right">2</th>
                                                </tr>
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="10%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="9%" colspan="2" class="text-right">Gross</th>
                                                    <th width="6%" class="text-right">654689.56</th>
                                                </tr>
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="10%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="9%" colspan="2" class="text-right">CGST</th>
                                                    <th width="6%" class="text-right">9820.34</th>
                                                </tr>
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="10%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="9%" colspan="2" class="text-right">SGST</th>
                                                    <th width="6%" class="text-right">9820.34</th>
                                                </tr>
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="10%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="5%"></th>
                                                    <th width="9%" colspan="2" class="text-right">Bill Total</th>
                                                    <th width="6%" class="text-right">674330.24</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                            <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
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
                                    <input type="text" name="pay_rec_data[amount]" id="amount" class="form-control num_only" value="">
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
                        <table class="pull-left table-bordered sell_net_balance_data" >
                            <tr>
                                <th>Net Gold Fine</th>
                                <th>Net Silver Fine</th>
                                <th>Net Amount</th>
                            </tr>
                            <tr>
                                <td><span class="net_gold_fine"></span></td>
                                <td><span class="net_silver_fine"></span></td>
                                <td><span class="net_amount"></span></td>
                            </tr>
                        </table>
                        <button type="button" class="btn btn-primary" id="payment_receipt_button">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="metal_receipt_payment_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content" style="background-color:#f1e8e1;">
                    <div class="modal-header">
                        <div class="col-md-6">
                            <h4 class="modal-title" id="myModalLabel">Metal Receipt Payment</h4>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <label><input type="radio" name="metal_payment_receipt" class="iradio_minimal-blue metal_payment_receipt" id="metal_payment_receipt" value="1"> Payment</label> &nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="metal_payment_receipt" class="iradio_minimal-blue metal_payment_receipt" id="metal_payment_receipt" value="2"> Receipt</label>
                        </div>
                    </div>
                    <div class="modal-body edit-content">
                        <div class="col-md-12">
                            <div class="metal_form metal_div">
                                <input type="hidden" name="metal_index" id="metal_index" />
                                <input type="hidden" name="metal_data[metal_item_delete]" id="metal_item_delete" value="allow" />
                                <?php if (isset($metal_data) && !empty($metal_data)) { ?>
                                <input type="hidden" name="metal_data[metal_pr_id]" id="metal_pr_id" value="0" />
                                <?php } ?>
                                <div class="col-md-4">
                                    <label for="item_id">Item<span class="required-sign">&nbsp;*</span></label>
                                    <select name="metal_data[metal_item_id]" class="form-control metal_item_id select2" id="metal_item_id"></select>
                                </div>
                                <div class="col-md-3">
                                    <label for="metal_grwt">Gr.Wt.<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="metal_data[metal_grwt]" class="form-control metal_grwt num_only" id="metal_grwt" placeholder="" value="">
                                </div>
                                <div class="col-md-2">
                                    <label for="metal_tunch">Tunch</label>
                                    <input type="text" name="metal_data[metal_tunch]" class="form-control metal_tunch num_only" id="metal_tunch" placeholder="" value="100">
                                </div>
                                <div class="col-md-3">
                                    <label for="metal_fine">Fine</label>
                                    <input type="text" name="metal_data[metal_fine]" class="form-control metal_fine num_only" id="metal_fine" placeholder="" value="" readonly="">
                                </div>
                                <div class="clearfix"></div>
                                <label>&nbsp;</label><br />
                            </div>
                            <div class="clearfix"></div><br />
                            <label class="col-md-2">Narration</label>
                            <div class="col-md-8">
                                <input type="text" name="metal_data[metal_narration]" id="metal_narration" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br />
                    <div class="modal-footer">
                        <table class="pull-left table-bordered sell_net_balance_data" >
                            <tr>
                                <th>Net Gold Fine</th>
                                <th>Net Silver Fine</th>
                                <th>Net Amount</th>
                            </tr>
                            <tr>
                                <td><span class="net_gold_fine"></span></td>
                                <td><span class="net_silver_fine"></span></td>
                                <td><span class="net_amount"></span></td>
                            </tr>
                        </table>
                        <button type="button" class="btn btn-primary metal_button" id="metal_button">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="gold_bhav_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color:#f1e8e1;">
                    <div class="modal-header">
                        <div class="col-md-6">
                            <h4 class="modal-title" id="myModalLabel">Gold Bhav</h4>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <label><input type="radio" name="gold_sale_purchase" class="iradio_minimal-blue" id="gold_sale_purchase" class="gold_sale_purchase" value="1"> Sell </label>&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="gold_sale_purchase" class="iradio_minimal-blue" id="gold_sale_purchase" class="gold_sale_purchase" value="2"> Purchase </label>
                        </div>
                    </div>
                    <div class="modal-body edit-content">
                        <div class="col-md-12">
                            <div class="gold_form metal_div">
                                <?php if (isset($gold_data)) { ?>
                                    <input type="hidden" name="gold_data[gold_id]" id="gold_id" />
                                <?php } ?>
                                <input type="hidden" name="gold_index" id="gold_index" />
                                <div class="col-md-4">
                                    <label><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></label>
                                </div>
                                <div class="col-md-4">
                                    
                                    <label>Min Gold Rate : <?php echo $gold_min; ?></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Max Gold Rate : <?php echo $gold_max; ?></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Weight<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="gold_data[gold_weight]" id="gold_weight" class="form-control num_only" value="">
                                </div>
                                <div class="col-md-4">
                                    <label>Rate<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="gold_data[gold_rate]" id="gold_rate" class="form-control num_only" value="">
                                </div>
                                <div class="col-md-4">
                                    <label>Value</label>
                                    <input type="text" name="gold_data[gold_value]" id="gold_value" class="form-control num_only" value="" readonly="">
                                </div>
                                <div class="clearfix"></div><br />
                                <div class="col-md-10">
                                    <label class="col-md-3">Narration</label>
                                    <div class="col-md-9">
                                        <input type="text" name="gold_data[gold_narration]" id="gold_narration" class="form-control" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br />
                    <div class="modal-footer">
                        <table class="pull-left table-bordered sell_net_balance_data" >
                            <tr>
                                <th>Net Gold Fine</th>
                                <th>Net Silver Fine</th>
                                <th>Net Amount</th>
                            </tr>
                            <tr>
                                <td><span class="net_gold_fine"></span></td>
                                <td><span class="net_silver_fine"></span></td>
                                <td><span class="net_amount"></span></td>
                            </tr>
                        </table>
                        <button type="button" class="btn btn-primary gold_button" id="gold_button">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="silver_bhav_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color:#f1e8e1;">
                    <div class="modal-header">
                        <div class="col-md-6">
                            <h4 class="modal-title" id="myModalLabel">Silver Bhav</h4>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <label><input type="radio" name="silver_sale_purchase" class="iradio_minimal-blue" id="silver_sale_purchase" value="1"> Sell </label>&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="silver_sale_purchase" class="iradio_minimal-blue" id="silver_sale_purchase" value="2"> Purchase </label>
                        </div>
                    </div>
                    <div class="modal-body edit-content">
                        <div class="col-md-12">
                            <div class="silver_form metal_div">
                                <input type="hidden" name="silver_index" id="silver_index" />
                                <?php if (isset($silver_data)) { ?>
                                    <input type="hidden" name="silver_data[silver_id]" id="silver_id" />
                                <?php } ?>
                                <div class="col-md-4">
                                    <label><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Min Silver Rate : <?php echo $silver_min; ?></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Max Silver Rate : <?php echo $silver_max; ?></label>
                                </div>
                            <div class="col-md-4">
                                <label>Weight<span class="required-sign">&nbsp;*</span></label>
                                <input type="text" name="silver_data[silver_weight]" id="silver_weight" class="form-control num_only" value="">
                            </div>
                            <div class="col-md-4">
                                <label>Rate<span class="required-sign">&nbsp;*</span></label>
                                <input type="text" name="silver_data[silver_rate]" id="silver_rate" class="form-control num_only" value="">
                            </div>
                            <div class="col-md-4">
                                <label>Value</label>
                                <input type="text" name="silver_data[silver_value]" id="silver_value" class="form-control num_only" value="" readonly="">
                            </div>
                            <div class="clearfix"></div><br />
                            <div class="col-md-10">
                                <label class="col-md-3">Narration</label>
                                <div class="col-md-9">
                                    <input type="text" name="silver_data[silver_narration]" id="silver_narration" class="form-control" value="">
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br />
                    <div class="modal-footer">
                        <table class="pull-left table-bordered sell_net_balance_data" >
                            <tr>
                                <th>Net Gold Fine</th>
                                <th>Net Silver Fine</th>
                                <th>Net Amount</th>
                            </tr>
                            <tr>
                                <td><span class="net_gold_fine"></span></td>
                                <td><span class="net_silver_fine"></span></td>
                                <td><span class="net_amount"></span></td>
                            </tr>
                        </table>
                        <button type="button" class="btn btn-primary silver_button" id="silver_button">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="transfer_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color:#f1e8e1;">
                    <div class="modal-header">
                        <div class="col-md-6">
                            <h4 class="modal-title" id="myModalLabel">Transfer</h4>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body edit-content">
                        <div class="col-md-12">
                            <div class="transfer_form transfer_div">
                                <input type="hidden" name="transfer_index" id="transfer_index" />
                                <?php if (isset($transfer_data) && !empty($transfer_data)) { ?>
                                    <input type="hidden" name="transfer_data[transfer_entry_id]" id="transfer_entry_id" />
                                <?php } ?>
                                <div class="col-md-5">
                                    <label><input type="radio" name="naam_jama" class="iradio_minimal-blue" id="naam_jama" value="1">&nbsp;Naam(Dr)</label>&nbsp;
                                    <label><input type="radio" name="naam_jama" class="iradio_minimal-blue" id="naam_jama" value="2">&nbsp;Jama(Cr)</label>&nbsp;
                                </div>
                                <div class="col-md-7">
                                    <select name="transfer_data[transfer_account_id]" id="transfer_account_id" class="form-control select2" ></select>
                                </div><div class="clearfix"></div>
                                <div class="col-md-5">
                                    <label>Gold</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="transfer_data[transfer_gold]" id="transfer_gold" class="form-control num_only" value=""> 
                                </div><div class="clearfix"></div>
                                <div class="col-md-5">
                                    <label>Silver</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="transfer_data[transfer_silver]" id="transfer_silver" class="form-control num_only" value=""> 
                                </div><div class="clearfix"></div>
                                <div class="col-md-5">
                                    <label>Amount</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="transfer_data[transfer_amount]" id="transfer_amount" class="form-control num_only" value=""> 
                                </div><div class="clearfix"></div>
                                <label class="col-md-5">Narration</label>
                                <div class="col-md-7">
                                    <input type="text" name="transfer_data[transfer_narration]" id="transfer_narration" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br />
                    <div class="modal-footer">
                        <table class="pull-left table-bordered sell_net_balance_data" >
                            <tr>
                                <th>Sel. Acc. Gold Fine</th>
                                <th>Sel. Acc. Silver Fine</th>
                                <th>Sel. Acc. Amount</th>
                            </tr>
                            <tr>
                                <td><span class="selected_account_gold_fine"></span></td>
                                <td><span class="selected_account_silver_fine"></span></td>
                                <td><span class="selected_account_amount"></span></td>
                            </tr>
                        </table>
                        <button type="button" class="btn btn-primary transfer_button" id="transfer_button">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="ad_charges_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color:#f1e8e1;">
                    <div class="modal-header">
                        <div class="col-md-6">
                            <h4 class="modal-title" id="myModalLabel">Charges</h4>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body edit-content">
                        <div class="col-md-12">
                            <div class="ad_lineitem_form">
                                <input type="hidden" name="ad_lineitem_index" id="ad_lineitem_index" />
                                <?php if(isset($ad_charges_data) && !empty($ad_charges_data)){ ?>
                                    <input type="hidden" name="ad_lineitem_data[sell_ad_charges_id]" id="sell_ad_charges_id" />
                                <?php } ?>
                                <div class="col-md-6">
                                    <label for="ad_id">Ad<span class="required-sign">&nbsp;*</span></label>
                                    <select name="ad_lineitem_data[ad_id]" class="form-control ad_id" id="ad_id"></select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <label for="ad_pcs">Pcs<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="ad_lineitem_data[ad_pcs]" class="form-control num_only ad_pcs" id="ad_pcs" >
                                </div>
                                <div class="col-md-4">
                                    <label for="ad_rate">Rate<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="ad_lineitem_data[ad_rate]" class="form-control num_only ad_rate" id="ad_rate" >
                                </div>
                                <div class="col-md-4">
                                    <label for="ad_amount">Amount<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="ad_lineitem_data[ad_amount]" class="form-control num_only ad_amount" id="ad_amount" readonly="" ><br />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br />
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary ad_charges_button" id="ad_charges_button">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="less_ad_details_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color:#f1e8e1;">
                    <div class="modal-header">
                        <div class="col-md-6">
                            <h4 class="modal-title" id="myModalLabel">Less Ad Details</h4>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body edit-content">
                        <div class="col-md-12">
                            <div class="less_ad_details_form">
                                <input type="hidden" name="less_ad_details_index" id="less_ad_details_index" />
                                <input type="hidden" name="less_ad_details_data[less_ad_details_delete]" id="less_ad_details_delete" value="allow" />
                                <?php if(isset($less_ad_details_data) && !empty($less_ad_details_data)){ ?>
                                    <input type="hidden" name="less_ad_details_data[sell_less_ad_details_id]" id="sell_less_ad_details_id" />
                                <?php } ?>
                                <div class="col-md-4">
                                    <label for="less_ad_details_ad_id">Ad<span class="required-sign">&nbsp;*</span></label>
                                    <select name="less_ad_details_data[less_ad_details_ad_id]" class="form-control less_ad_details_ad_id" id="less_ad_details_ad_id"></select>
                                </div>
                                <div class="col-md-3">
                                    <label for="less_ad_details_ad_pcs">Pcs</label>
                                    <input type="text" name="less_ad_details_data[less_ad_details_ad_pcs]" class="form-control num_only less_ad_details_ad_pcs" id="less_ad_details_ad_pcs" >
                                </div>
                                <div class="col-md-3">
                                    <label for="less_ad_details_ad_weight">Weight<span class="required-sign">&nbsp;*</span></label>
                                    <input type="text" name="less_ad_details_data[less_ad_details_ad_weight]" class="form-control num_only less_ad_details_ad_weight" id="less_ad_details_ad_weight" >
                                </div>
                                <div class="col-md-2">
                                    <input type="button" id="add_less_ad_details" class="btn btn-info btn-sm add_less_ad_details" value="Add Ad Line" style="margin-top: 21px;"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="" style="overflow-x: scroll;">
                                <table style="" class="table custom-table item-table">
                                    <thead>
                                        <tr>
                                            <th width="80px">Action</th>
                                            <th>Ad</th>
                                            <th class="text-right">Pcs</th>
                                            <th class="text-right">Weight</th>
                                        </tr>
                                    </thead>
                                    <tbody id="less_ad_details_list"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total:</th>
                                            <th id="auto_count_chhijat"></th>
                                            <th class="text-right" id="total_less_ad_details_pcs"></th>
                                            <th class="text-right" id="total_less_ad_details_weight"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br />
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 99999;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Image</h4>
                </div>
                <div class="modal-body edit-content">
                    <img id="doc_img_src" alt="No Image Found" class="img-responsive" height='300px' width='600px'>
                    </div>
            </div>
        </div>
    </div>
</div>
<div id="item_selection_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="myModalLabel">Item Details</h4>
                </div>
            </div>
            <div class="modal-body edit-content">
                <div class="col-md-12">
                    <div class="popup_div">
                        <table style="" class="table custom-table border item-table">
                            <thead>
                                <tr>
                                    <th><label><input type="checkbox" id="select_all_order_to_sell" /> Select All</label></th>
                                    <th>Item</th>
                                    <th class="text-right">Gr.Wt.</th>
                                    <th class="text-right">Less</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tbody id="item_selection_list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <button type="button" class="btn btn-primary order_to_sell_button" id="order_to_sell_button">Save</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div id="purchase_item_selection_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="myModalLabel">Purchase/Exchange Item Details</h4>
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

<script type="text/javascript">
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    var sell_purchase = "<?=$sell_purchase?>";
    var save_sell_purchase_submit_flag = 0;
    var zero_value = 0;
    var metal_gold_total = 0;
    var metal_silver_total = 0;
    var transfer_gold_total = 0;
    var transfer_silver_total = 0;
    var transfer_amount_total = 0;
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
    
    var edit_lineitem_inc = 0;
    var edit_pay_rec_inc = 0;
    var edit_metal_inc = 0;
    var edit_gold_inc = 0;
    var edit_silver_inc = 0;
    var edit_transfer_inc = 0;
    var edit_ad_charges_inc = 0;
    var edit_less_ad_details_inc = 0;
    var sell_index = '';
    var metal_index = '';
    
    var lineitem_objectdata = [];
    var metal_objectdata = [];
    var gold_objectdata = [];
    var silver_objectdata = [];
    var pay_rec_objectdata = [];
    var transfer_objectdata = [];
    var ad_charges_objectdata = [];
    var less_ad_details_objectdata = [];
    var gold_array_for_edit = [];
    var silver_array_for_edit = [];
    var items = [];
<?php if (isset($sell_item)) { ?>
    var li_lineitem_objectdata = [<?php echo $sell_item; ?>];
    if (li_lineitem_objectdata != '') {
        $.each(li_lineitem_objectdata, function (index, value) {
            lineitem_objectdata.push(value);
        });
    }
<?php } 
if (isset($pay_rec_data) && !empty($pay_rec_data)) { ?>
    var pay_lineitem_objectdata = [<?php echo $pay_rec_data; ?>];
    if (pay_lineitem_objectdata != '') {
        $.each(pay_lineitem_objectdata, function (index, value) {
            pay_rec_objectdata.push(value);
        });
    }
    $('.pay_rec_table').show();
<?php } 
if (isset($metal_data)) { ?>
    var metal_lineitem_objectdata = [<?php echo $metal_data; ?>];
    if (metal_lineitem_objectdata != '') {
        $.each(metal_lineitem_objectdata, function (index, value) {
//            $('#metal_pr_id').val(value.metal_pr_id);
//            $('#save_sell_purchase').append('<input type="hidden" name="metal_pr_id" id="metal_pr_id" value="' + value.metal_pr_id + '" />');
            metal_objectdata.push(value);
        });
    }
    $('.metal_table').show();
<?php } 
if (isset($gold_data)) { ?>
    var gold_lineitem_objectdata = [<?php echo $gold_data; ?>];
    if (gold_lineitem_objectdata != '') {
        $.each(gold_lineitem_objectdata, function (index, value) {
            gold_objectdata.push(value);
        });
    }
    $('.gold_table').show();
<?php } 
if (isset($silver_data)) { ?>
    var silver_lineitem_objectdata = [<?php echo $silver_data; ?>];
    if (silver_lineitem_objectdata != '') {
        $.each(silver_lineitem_objectdata, function (index, value) {
            silver_objectdata.push(value);
        });
    }
    $('.silver_table').show();
<?php } 
if (isset($transfer_data)) { ?>
    var transfer_lineitem_objectdata = [<?php echo $transfer_data; ?>];
    if (transfer_lineitem_objectdata != '') {
        $.each(transfer_lineitem_objectdata, function (index, value) {
            transfer_objectdata.push(value);
        });
    }
    $('.transfer_table').show();
<?php } 
if (isset($ad_charges_data)) { ?>
    var ad_charges_lineitem_objectdata = [<?php echo $ad_charges_data; ?>];
    if (ad_charges_lineitem_objectdata != '') {
        $.each(ad_charges_lineitem_objectdata, function (index, value) {
            ad_charges_objectdata.push(value);
        });
    }
    $('.ad_charges_table').show();
<?php } 
if (isset($order_lot_item)) { ?>
    var ots_li_lineitem_objectdata = [<?php echo $order_lot_item; ?>];
    var ots_lineitem_objectdata = [];
    if (ots_li_lineitem_objectdata != '') {
        $.each(ots_li_lineitem_objectdata, function (index, value) {
            ots_lineitem_objectdata.push(value);
        });
    }
<?php } ?>
    
    var pts_lineitem_objectdata = [];
    display_metal_html(metal_objectdata);
    display_gold_html(gold_objectdata);
    display_silver_html(silver_objectdata);
    display_pay_rec_html(pay_rec_objectdata);
    display_transfer_html(transfer_objectdata);
    display_ad_charges_html(ad_charges_objectdata);
    
    $(document).ready(function () {
        
        $('#save_sell_purchase').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                var rfid_number = $('#rfid_number').val();
                if(rfid_number == ''){
                    e.preventDefault();
                    return false;
                }
            }
        });

        <?php if (isset($new_order_data->order_id)) { ?>
            $('#order_id').val(<?php echo $new_order_data->order_id; ?>);
        <?php } ?>
        initAjaxSelect2($("#account_id"), "<?= base_url('app/party_name_with_number_select2_source/1') ?>");
        <?php if (isset($new_order_data->party_id)) { ?>
            get_bill_balance(<?php echo $new_order_data->party_id; ?>);
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . $new_order_data->party_id) ?>");
        <?php } elseif (isset($sell_data->account_id) && !empty ($sell_data->account_id)) { ?>
            get_bill_balance(<?php echo $sell_data->account_id; ?>);
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . $sell_data->account_id) ?>");
            initAjaxSelect2($("#transfer_account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . $sell_data->account_id) ?>");
        <?php } else { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . CASE_CUSTOMER_ACCOUNT_ID) ?>");
        <?php } ?>
        <?php if (isset($sell_data->account_id) && !empty ($sell_data->account_id)) { ?>  
            initAjaxSelect2($("#transfer_account_id"), "<?= base_url('app/account_name_from_main_party_select2_source/' . $sell_data->account_id.'/1') ?>");
        <?php } else { ?>
            initAjaxSelect2($("#transfer_account_id"), "<?= base_url('app/account_name_from_main_party_select2_source/' . CASE_CUSTOMER_ACCOUNT_ID.'/1')?>");
        <?php } ?>
        <?php if($department_2 == '1') { ?>
            initAjaxSelect2($("#process_id"), "<?= base_url('app/department_select2_source') ?>");
            <?php if (isset($new_order_data->process_id)) { ?>
                setSelect2Value($("#process_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $new_order_data->process_id) ?>");
            <?php } elseif (isset($sell_data->process_id) && !empty ($sell_data->process_id)) { ?>
                setSelect2Value($("#process_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $sell_data->process_id) ?>");
            <?php } else { ?>
                setSelect2Value($("#process_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
            <?php } ?>
        <?php } ?>
            
        <?php if(isset($order_to_sell_purchase) && !empty($order_to_sell_purchase)){ ?>
            $('#item_selection_popup').modal('show');
            var order_lineitem_html = '';
            $.each(ots_lineitem_objectdata, function (index, value) {
//                console.log(value);
                if(value.less == 1){
                    var input_less = '<input type="text" name="order_less[]" value="0">';
                } else {
                    var input_less = '<input type="text" name="order_less[]" value="0" disabled="">';
                }
                
                var row_html_order = '<tr class="lineitem_index_' + index + '">' +
                    '<td class="text-center">' +
                    '<input type="checkbox" data-item_id="' + value.item_id + '" data-selected_index="' + index + '" class="selected_index">' +
                    '</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right"><input type="text" name="order_grwt[]" id="order_grwt" value="'+ value.grwt + '"></td>' +
                    '<td class="text-right">' + input_less + '</td>';
                    if (value.image !== null && value.image !== '') {
                        var img_url = '<?php echo base_url(); ?>' + '/' + value.image;
                        row_html_order += '<td><a href="javascript:void(0)" class="btn btn-xs btn-primary image_model" data-img_src="' + img_url + '" ><i class="fa fa-image"></i></a></td>';
                    }
                    row_html_order += '</tr>';
                order_lineitem_html += row_html_order;
            });
        
            $('tbody#item_selection_list').html(order_lineitem_html);
        <?php } ?>
    
        $(document).on('click', '#select_all_order_to_sell', function () {
            if($("#select_all_order_to_sell").is(':checked')){
                    $('.selected_index').prop('checked', true);
            } else {
                    $('.selected_index').prop('checked', false);
            }
        });

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
        
        <?php if($use_category == '1') { ?>
            initAjaxSelect2($("#category_id"), "<?= base_url('app/category_for_gold_and_silver_select2_source') ?>");
        <?php } ?>
        initAjaxSelect2($("#metal_item_id"), "<?= base_url('app/item_name_from_select_category_for_metal_select2_source') ?>");
        initAjaxSelect2($("#sell_type_id"), "<?= base_url('app/sell_type_select2_source/') ?>" + sell_purchase );
        
        <?php /* initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_from_category_select2_source') ?>"); */ ?>
        
        $('input[type="checkbox"].send_sms').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
        });
        
        $(".myModelClose").on("hidden.bs.modal", function () {
            gold_array_for_edit = [];
        });
            
        $('.touch_select').show();
        $('.touch_input').hide();
        $('.banks').hide();
        $("#party_id").select2('open');
        $(".cash_cheque").select2();
        $("#delivery_type").select2();
        $('#payment_receipt_data').hide();
        
        if ($('#datepicker2').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker2').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: "today",
                maxDate: 0,
                constrainInput: false
            });
        }
        
        $(document).on('focus',"#datepicker2",function () {
           $(this).select();
        });
        $('#datepicker2').on('show', function(e){
            if ( e.date ) {
                 $(this).data('stickyDate', e.date);
            }
            else {
                 $(this).data('stickyDate', null);
            }
        });
        $('#datepicker2').on('hide', function(e){
            var stickyDate = $(this).data('stickyDate');
            if ( !e.date && stickyDate ) {
                $(this).datepicker('setDate', stickyDate);
                $(this).data('stickyDate', null);
            }
        });
        
//        $("#datepicker2").datepicker("hide");
//        $("#datepicker2").datepicker("destroy");
//        $('#datepicker2').datepicker('remove');
//        $('#datepicker2').datepicker('update');

        
        var account_id = $('#account_id').val();
        get_bill_balance(account_id);
        
        $(document).on('change', '#account_id', function (){
            $('#transfer_account_id').val(null).trigger('change');
            var account_id = $('#account_id').val();
            if (account_id != '' && account_id != null) {
                initAjaxSelect2($("#transfer_account_id"), "<?= base_url('app/account_name_from_main_party_select2_source') ?>/" + account_id + "/1");
                get_bill_balance(account_id);
            } else {
                $('#transfer_account_id').val(null).trigger('change');
            }
            var sell_type_id = $('#sell_type_id').val();
            if(sell_type_id == <?php echo SELL_TYPE_SELL_ID; ?>){
                get_wstg_from_account();
            }
        });

        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");
            setTimeout(function () {
                $("#doc_img_src").attr('src', src);
            }, 0);
            $('#edit-modal').modal('show');
        });

        $(document).on('click', '#payment_receipt', function () {
            if ($.trim($("#process_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#process_id").select2('open');
                return false;
            }
            $('#payment_receipt_model').modal('show');
        });

        $(document).on('click', '#metal_receipt_payment', function () {
            setSelect2Value($("#metal_item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + <?php echo METAL_PAYMENT_RECEIPT_DEFAULT_ITEM_ID; ?>);
            if ($.trim($("#process_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#process_id").select2('open');
                return false;
            }
            $('#metal_receipt_payment_model').modal('show');
        });

        $(document).on('click', '#gold_bhav', function () {
            if ($.trim($("#process_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#process_id").select2('open');
                return false;
            }
            $('#gold_bhav_model').modal('show');
            var net_gold_fine = $('.net_gold_fine').html();
            if(net_gold_fine < 0){
                $('input:radio[name=gold_sale_purchase]').filter('[value=2]').prop('checked', true);
                net_gold_fine = Math.abs(net_gold_fine);
            } else {
                $('input:radio[name=gold_sale_purchase]').filter('[value=1]').prop('checked', true);
            }
            $('#gold_weight').val(net_gold_fine);
        });

        $(document).on('click', '#silver_bhav', function () {
            if ($.trim($("#process_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#process_id").select2('open');
                return false;
            }
            $('#silver_bhav_model').modal('show');
            var net_silver_fine = $('.net_silver_fine').html();
            if(net_silver_fine < 0){
                $('input:radio[name=silver_sale_purchase]').filter('[value=2]').prop('checked', true);
                net_silver_fine = Math.abs(net_silver_fine);
            } else {
                $('input:radio[name=silver_sale_purchase]').filter('[value=1]').prop('checked', true);
            }
            $('#silver_weight').val(net_silver_fine);
        });

        $(document).on('click', '#transfer', function () {
            if ($.trim($("#process_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#process_id").select2('open');
                return false;
            }
            $('#transfer_model').modal('show');
        });
        
        $(document).on('click', '#ad_charges', function () {
            $('#ad_charges_model').modal('show');
        });
        
        $(document).on('keyup change', '#ad_pcs, #ad_rate', function () {
            var ad_pcs = parseFloat($('#ad_pcs').val()) || 0;
            ad_pcs = round(ad_pcs, 2).toFixed(2);
            var ad_rate = parseFloat($('#ad_rate').val()) || 0;
            ad_rate = round(ad_rate, 2).toFixed(2);
            var ad_amount = parseFloat(ad_pcs) * parseFloat(ad_rate);
            ad_amount = round(ad_amount, 2).toFixed(2);
            $('#ad_amount').val(ad_amount);
        });
        
        $(document).on('click', '#less_ad_details', function () {
            initAjaxSelect2($("#less_ad_details_ad_id"), "<?= base_url('app/ad_name_select2_source/is_sell_purchase_less_ad_details') ?>");
            $('#less_ad_details_model').modal('show');
        });
        $('#less_ad_details_model').on('hidden.bs.modal', function () {
            var total_less_ad_details_weight = $('#total_less_ad_details_weight').html();
            $('#less').val(total_less_ad_details_weight).trigger("change");;
        });
        
        $('#add_less_ad_details').on('click', function () {
            var less_ad_details_ad_id = $("#less_ad_details_ad_id").val();
            if (less_ad_details_ad_id == '' || less_ad_details_ad_id == null) {
                $("#less_ad_details_ad_id").select2('open');
                show_notify("Please select Ad!", false);
                return false;
            }
            var less_ad_details_ad_pcs = $("#less_ad_details_ad_pcs").val();
            var less_ad_details_ad_weight = $("#less_ad_details_ad_weight").val();
            if (less_ad_details_ad_weight == '' || less_ad_details_ad_weight == null) {
                show_notify("Ad Weight is required!", false);
                $("#less_ad_details_ad_weight").focus();
                return false;
            }

            $("#add_less_ad_details").attr('disabled', 'disabled');
            var sell_less_ad_details_id = $("#sell_less_ad_details_id").val();
            if (typeof (sell_less_ad_details_id) !== "undefined" && sell_less_ad_details_id !== null) {
                $('.less_ad_details_form #deleted_less_ad_details_id[value="' + sell_less_ad_details_id + '"]').remove();
            }
            var key = '';
            var value = '';
            var less_ad_details = {};
            $('input[name^="less_ad_details_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("less_ad_details_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                less_ad_details[key] = value;
            });
            less_ad_details['less_ad_details_ad_id'] = less_ad_details_ad_id;
            var ad_data = $('#less_ad_details_ad_id option:selected').html();
            less_ad_details['less_ad_details_ad_name'] = ad_data;
            less_ad_details['less_ad_details_ad_pcs'] = less_ad_details_ad_pcs;
            less_ad_details['less_ad_details_ad_weight'] = less_ad_details_ad_weight;
            
            var new_less_ad_details = JSON.parse(JSON.stringify(less_ad_details));
            less_ad_details_index = $("#less_ad_details_index").val();
            if (less_ad_details_index != '') {
                less_ad_details_objectdata.splice(less_ad_details_index, 1, new_less_ad_details);
            } else {
                less_ad_details_objectdata.push(new_less_ad_details);
            }
            display_less_ad_details_html(less_ad_details_objectdata);
            $("#less_ad_details_index").val('');
            less_ad_details_index = '';
            $('#less_ad_details_delete').val('allow');
            $('#sell_less_ad_details_id').val('');
            $("#less_ad_details_ad_id").val(null).trigger("change");
            $("#less_ad_details_ad_pcs").val('');
            $("#less_ad_details_ad_weight").val('');
            edit_less_ad_details_inc = 0;
            $("#add_less_ad_details").removeAttr('disabled', 'disabled');
        });

        $(document).on('change', '#process_id', function () {
            if(sell_purchase == "sell") {
                setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_SELL_ID) ?>");
            } else if(sell_purchase == "purchase") {
                setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_PURCHASE_ID) ?>");
            } else {
                $('#sell_type_id').val(null).trigger("change");    
            }
            pts_lineitem_objectdata = [];
            display_pts_lineitem_html(pts_lineitem_objectdata);
        });
        
        $(document).on('change', '#tunch_textbox', function(){
            if($('#tunch_textbox').prop("checked") == true){
                $('.touch_select').hide();
                $('.touch_input').show();
            } else {
                $('.touch_select').show();
                $('.touch_input').hide();
            }
            $("#touch_id").val(null).trigger("change");
            $("#touch_data_id").val('');
        });
        
        $("#tunch_textbox").prop("checked", true).trigger('change');
        
        $(document).on('change', '#sell_type_id', function () {
            $('#wstg').removeAttr('readonly', 'readonly');
            if ($.trim($("#process_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#process_id").select2('open');
                if(sell_purchase == "sell") {
                    setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_SELL_ID) ?>");
                } else if(sell_purchase == "purchase") {
                    setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_PURCHASE_ID) ?>");
                } else {
                    $('#sell_type_id').val(null).trigger("change");    
                }
                return false;
            }
            var sell_type_id = $('#sell_type_id').val();
            if (sell_type_id == '1') {
                $('.field_required').removeAttr('hidden', 'hidden');
                $(".item_fields_div").css("background-color", "#c4ecc4");
                if($('#tunch_textbox').prop("checked") == true){
                    $('.touch_select').hide();
                    $('.touch_input').show();
                } else {
                    $('.touch_select').show();
                    $('.touch_input').hide();
                }
                $("#tunch_textbox").removeAttr('disabled', 'disabled');
                <?php if($this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow change wastage")){ } else { ?>
                    $('#wstg').attr('readonly', 'readonly');
                <?php } ?>
            } else if (sell_type_id == '2') {
                $('.field_required').removeAttr('hidden', 'hidden');
                $(".item_fields_div").css("background-color", "#fc949b");
                if($('#tunch_textbox').prop("checked") == true){
                    $('.touch_select').hide();
                    $('.touch_input').show();
                } else {
                    $('.touch_select').show();
                    $('.touch_input').hide();
                }
                $("#tunch_textbox").removeAttr('disabled', 'disabled');
            } else if (sell_type_id == '3') {
                $('.field_required').attr('hidden', 'hidden');
                $(".item_fields_div").css("background-color", "#efbacc");
                $('.touch_select').hide();
                $('.touch_input').show();
                $("#tunch_textbox").attr('disabled', 'disabled');
            } else {
                $(".item_fields_div").css("background-color", "#ffffff");
            }
            
            <?php if($use_category == '1') { ?>
                $('#category_id').val(null).trigger('change');
                $('#item_id').val(null).trigger('change');
            <?php } else { ?>
                $('#item_id').val(null).trigger('change');
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_from_select_category_for_sell_select2_source') ?>/0/" + sell_type_id);
            <?php } ?>
            
            if (sell_type_id == '<?php echo SELL_TYPE_EXCHANGE_ID; ?>') {
                var exchange_default_category_id = '<?php echo EXCHANGE_DEFAULT_CATEGORY_ID; ?>';
                var exchange_default_item_id = '<?php echo EXCHANGE_DEFAULT_ITEM_ID; ?>';
                <?php if($use_category == '1') { ?>
                    $("#category_id").val(exchange_default_category_id).trigger("change");
                    setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/') ?>" + exchange_default_category_id);
                <?php } else { ?>
                    $("#category_id").val(exchange_default_category_id);
                <?php } ?>
                $("#item_id").val(exchange_default_item_id).trigger("change");
                setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + exchange_default_item_id);
            }
            $("#grwt").val('');
            $("#less").val('');
            $("#net_wt").val('');
            $("#touch_id").val(null).trigger("change");
            $("#touch_data_id").val('');
            <?php if($wstg_2 == '1') { ?>
                $("#wstg").val('');
            <?php } else { ?>
                $("#wstg").val('0');
            <?php } ?>
            $("#fine").val('');
        });

        $(document).on('change', '#category_id', function () {
            var category_id = $('#category_id').val();
            var sell_type_id = $('#sell_type_id').val();
            if (category_id != '' && category_id != null && sell_type_id != '' && sell_type_id != null) {
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_from_select_category_for_sell_select2_source') ?>/" + category_id + '/' + sell_type_id);
            } else {
                $('#item_id').val(null).trigger('change');
                $("#grwt").val('');
                $("#less").val('');
                $("#net_wt").val('');
                $("#touch_id").val(null).trigger("change");
                $("#touch_data_id").val('');
                <?php if($wstg_2 == '1') { ?>
                    $("#wstg").val('');
                <?php } else { ?>
                    $("#wstg").val('0');
                <?php } ?>
                $("#fine").val('');
            }
            $('#item_id').val(null).trigger('change');
            $("#grwt").val('');
            $("#less").val('');
            $("#net_wt").val('');
            $("#touch_id").val(null).trigger("change");
            $("#touch_data_id").val('');
            <?php if($wstg_2 == '1') { ?>
                $("#wstg").val('');
            <?php } else { ?>
                $("#wstg").val('0');
            <?php } ?>
            $("#fine").val('');
        });

        $(document).on('change', '#item_id', function () {
            $("#grwt").val('');
            $("#less").val('');
            $("#net_wt").val('');
            $("#touch_id").val(null).trigger("change");
            $("#touch_data_id").val('');
            <?php if($wstg_2 == '1') { ?>
                $("#wstg").val('');
            <?php } else { ?>
                $("#wstg").val('0');
            <?php } ?>
            $("#fine").val('');
            var item_id = $('#item_id').val();
            var account_id = $('#account_id').val();
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
                            $('#less_ad_details').addClass('hide');
                            $("#grwt").focus();
                            $(document).on('change', '#grwt', function () {
                                $('.touch_id').focus();
                            });
                        } else {
                            $('#less').removeAttr('readonly', 'readonly');
                            $('#less_ad_details').removeClass('hide');
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
                            var sell_type_id = $('#sell_type_id').val();
                            if(sell_type_id == <?php echo SELL_TYPE_SELL_ID; ?>){
                                var process_id = $('#process_id').val();
                                var sell_id = '';
                                <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
                                    sell_id = '<?php echo $sell_data->sell_id; ?>';
                                <?php } ?>
                                $.ajax({
                                    url: "<?php echo base_url('sell/get_purchase_to_sell_pending_item'); ?>/",
                                    type: 'POST',
                                    async: false,
                                    data: {department_id : process_id, item_id : item_id, sell_id : sell_id, account_id : account_id},
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
                        var sell_type_id = $('#sell_type_id').val();
                        if(sell_type_id == <?php echo SELL_TYPE_SELL_ID; ?>){
                            get_wstg_from_account();
                        }
                    }
                });
            }
        });

        $('#purchase_item_selection_popup').on('hidden.bs.modal', function () {
            $("#item_id").val(null).trigger("change");
            $(".delete_sp_item").removeClass('hide');
        });

        $(document).on('keyup change', '.pts_grwt, .pts_less, .pts_wstg', function () {
            var pts_selected_index = $(this).data('pts_selected_index');
            var pts_grwt = $('#pts_grwt_' + pts_selected_index).val();
            var pts_less = $('#pts_less_' + pts_selected_index).val();
            var pts_wstg = $('#pts_wstg_' + pts_selected_index).val();
//            pts_lineitem_objectdata[pts_selected_index].grwt = pts_grwt;
            var pe_stock_grwt = pts_lineitem_objectdata[pts_selected_index].grwt;
//            console.log(pts_grwt + ' ' + pe_stock_grwt);
            <?php // if($without_purchase_sell_allow == '1'){ ?>
            if(parseFloat(pts_grwt) > parseFloat(pe_stock_grwt)){
                show_notify('Please Enter GrWt Less than of ' + pe_stock_grwt, false);
                $(this).val(pe_stock_grwt).keyup();
                return false;
            }
            <?php // } ?>
            pts_lineitem_objectdata[pts_selected_index].net_wt = parseFloat(pts_grwt || 0) - parseFloat(pts_less || 0);
            pts_lineitem_objectdata[pts_selected_index].net_wt = round(pts_lineitem_objectdata[pts_selected_index].net_wt, 2).toFixed(3);
            pts_lineitem_objectdata[pts_selected_index].fine = parseFloat(pts_lineitem_objectdata[pts_selected_index].net_wt) * (parseFloat(pts_lineitem_objectdata[pts_selected_index].touch_id) + parseFloat(pts_wstg || 0)) / 100;
            pts_lineitem_objectdata[pts_selected_index].fine = round(pts_lineitem_objectdata[pts_selected_index].fine, 2).toFixed(3);
            $('#pts_net_wt_' + pts_selected_index).html(pts_lineitem_objectdata[pts_selected_index].net_wt);
            $('#pts_fine_' + pts_selected_index).html(pts_lineitem_objectdata[pts_selected_index].fine);
            checked_average_value();
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

        $(document).bind('keyup change', '#net_wt, .touch_id, #wstg', function () {
            var net_wt = parseFloat($('#net_wt').val()) || 0;
            net_wt = round(net_wt, 2).toFixed(3);
            var wstg = parseFloat($('#wstg').val()) || 0;
            var sell_type_id = $('#sell_type_id').val();
            if (sell_type_id == '3') {
                var touch = parseFloat($('#touch_data_id').val()) || 0;
            } else {
                if($('#tunch_textbox').prop("checked") == true){
                    var touch = parseFloat($('#touch_data_id').val()) || 0;
                } else {
                    var touch = parseFloat($('#touch_id').val()) || 0;
                }
            }
            var fine = 0;
            fine = parseFloat(net_wt) * (parseFloat(touch) + parseFloat(wstg)) / 100;
            fine = round(fine, 2).toFixed(3);
            $('#fine').val(fine);
        });
        
        $(document).on('change', '.cash_cheque', function () {
            var cash_cheque = $('.cash_cheque').val();
            if(cash_cheque == 2){
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
            var cash_cheque = $('.cash_cheque').val();
            if(cash_cheque == 2){
                if ($.trim($("#bank_id").val()) == '') {
                    show_notify('Please Select Bank.', false);
                    $("#bank_id").focus();
                    return false;
                }
            }
            if ($.trim($("#amount").val()) == '') {
                show_notify('Please Enter Amount.', false);
                $("#amount").focus();
                return false;
            }
            
            $('#payment_receipt_button').attr('disabled', 'disabled');
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
            if(cash_cheque == 2){
                var bank_data = $('#bank_id').select2('data');
                lineitem['bank_name'] = bank_data[0].text;
            } else { lineitem['bank_name'] = ''; }
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
            
            var payment_receipt = $('input[name=payment_receipt]:checked').val();
            var pay_rec_amount = parseFloat($('#amount').val()) || 0;
            
            var pay_rec_index = $("#pay_rec_index").val();
            if (pay_rec_index != '') {
                
                var pay_rec_amount_prev = pay_rec_objectdata[pay_rec_index].amount;
                if(pay_rec_objectdata[pay_rec_index].payment_receipt == '1' && payment_receipt == '1'){
                    total_amount = total_amount + parseFloat(pay_rec_amount) - parseFloat(pay_rec_amount_prev);
                } else if(pay_rec_objectdata[pay_rec_index].payment_receipt == '1' && payment_receipt == '2'){
                    total_amount = total_amount - parseFloat(pay_rec_amount) - parseFloat(pay_rec_amount_prev);
                } else if(pay_rec_objectdata[pay_rec_index].payment_receipt == '2' && payment_receipt == '1'){
                    total_amount = total_amount + parseFloat(pay_rec_amount) + parseFloat(pay_rec_amount_prev);
                } else if(pay_rec_objectdata[pay_rec_index].payment_receipt == '2' && payment_receipt == '2'){
                    total_amount = total_amount - parseFloat(pay_rec_amount) + parseFloat(pay_rec_amount_prev);
                }
                pay_rec_objectdata.splice(pay_rec_index, 1, new_lineitem);
                
            } else {
                
                if(payment_receipt == '1'){
                    total_amount = total_amount + parseFloat(pay_rec_amount);
                } else if(payment_receipt == '2') {
                    total_amount = total_amount - parseFloat(pay_rec_amount);
                }
                pay_rec_objectdata.push(new_lineitem);
            }
            
            display_pay_rec_html(pay_rec_objectdata);
            $('#pay_rec_id').val('');
            $("input[name=payment_receipt]").prop("checked",false);
            $('#cash_cheque').val('1').trigger("change");
            $('#bank_id').val(null).trigger("change");
            $("#amount").val('');
            $("#narration").val('');
            $("#pay_rec_index").val('');
            $('#payment_receipt_id').val('1');
            $('#payment_receipt_model').modal('hide');
            $('.pay_rec_table').show();
            $('#payment_receipt_button').removeAttr('disabled', 'disabled');
        });

        $('#payment_receipt_model').on('shown.bs.modal', function () {
            if ($.trim($("#amount").val()) == '') {
                $('#cash_cheque').select2('open');
            }
        });
        
        $('#payment_receipt_model').on('hidden.bs.modal', function () {
            $('#pay_rec_id').val('');
            $("input[name=payment_receipt]").prop("checked",false);
            $('#cash_cheque').val('1').trigger("change");
            $('#bank_id').val(null).trigger("change");
            $("#amount").val('');
            $("#narration").val('');
            $("#pay_rec_index").val('');
            $('#payment_receipt_id').val('1');
            $(".delete_pr_item").removeClass('hide');
        });
        
        $(document).bind('keyup change', '#metal_grwt, #metal_tunch', function () {
            var metal_grwt = parseFloat($('#metal_grwt').val()) || 0;
            metal_grwt = round(metal_grwt, 2).toFixed(3);
            var metal_tunch = parseFloat($('#metal_tunch').val()) || 0;
            var fine = 0;
            fine = parseFloat(metal_grwt) * parseFloat(metal_tunch) / 100;
            fine = round(fine, 2).toFixed(3);
            $('#metal_fine').val(fine);
        });
        
        $(document).on('click', '#metal_button', function () {
            if (!$('input[name=metal_payment_receipt]:checked').val()) {
                show_notify('Please Select Payment or Receipt.', false);
                $("#metal_payment_receipt").focus();
                return false;
            }
            var metal_payment_receipt = $('input[name=metal_payment_receipt]:checked').val();
            var metal_item_id = $('#metal_item_id').val();
            var metal_tunch = $('#metal_tunch').val();
            var metal_grwt = $('#metal_grwt').val();
            var department_id = $('#process_id').val();
            if (metal_item_id == '' || metal_item_id == null) {
                $("#metal_item_id").select2('open');
                show_notify("Please select Item!", false);
                return false;
            }
            if (metal_grwt == '' || metal_grwt == null) {
                $("#metal_grwt").focus();
                show_notify("Please Enter Gr.Wt.!", false);
                return false;
            }else {
                var total_grwt_metal = $('#total_grwt_metal').val();
                <?php if($without_purchase_sell_allow == '1'){ ?>
                    if(total_grwt_metal != '' && total_grwt_metal != null){
                        var metal_grwt = parseFloat($('#metal_grwt').val()) || 0;
                        metal_grwt = round(metal_grwt, 2).toFixed(3);
                        if(parseFloat(metal_grwt) < parseFloat(total_grwt_metal)){
                            show_notify("Gr.Wt. Should Be Grater Than " + total_grwt_metal , false);
                            $('#metal_grwt').val(total_grwt_metal);
                            $("#metal_grwt").focus();
                            return false;
                        }
                    }
                <?php } ?>
            }
            <?php if($without_purchase_sell_allow == '1'){ ?>
                if(metal_payment_receipt == 1){
                    var metal_pr_id = $("#metal_pr_id").val();
                    var grwt_stock = get_stock_for_metal_receipt(department_id,metal_item_id,metal_tunch,metal_pr_id);
                    if(parseFloat(grwt_stock) < parseFloat(metal_grwt)){
                        show_notify('Please Enter GrWt Less than of ' + grwt_stock, false);
                        return false;
                    }
                }
            <?php } ?>
            $('#metal_button').attr('disabled', 'disabled');
            var key = '';
            var value = '';
            var lineitem = {};
            var is_validate = '0';
            $('select[name^="metal_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("metal_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('input[name^="metal_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("metal_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
//            $('select[name^="line_items_data"]').each(function (index) {
//                key = $(this).attr('name');
//                key = key.replace("line_items_data[", "");
//                key = key.replace("]", "");
////                console.log(lineitem_objectdata);
//                $.each(metal_objectdata, function (index, value) {
//                    if (value.metal_payment_receipt == metal_payment_receipt && value.metal_item_id == metal_item_id && value.metal_tunch == metal_tunch && typeof (value.id) != "undefined" && value.id !== null) {
//                        $('input[name^="line_items_data"]').each(function (index) {
//                            keys = $(this).attr('name');
//                            keys = keys.replace("line_items_data[", "");
//                            keys = keys.replace("]", "");
//                            if (keys == 'id') {
//                                if (value.id != $(this).val()) {
//                                    is_validate = '1';
//                                    
//                                }
//                            }
//                        });
//                    } else if (value.metal_payment_receipt == metal_payment_receipt && value.metal_item_id == metal_item_id && value.metal_tunch == metal_tunch) {
//                        if(metal_index !== index){
//                            is_validate = '1';
//                        }
//                    }
//                });
//                
//            });
            if (is_validate == '1') {
                show_notify("You cannot Add this Item. This Item has been used!", false);
                $('#metal_button').removeAttr('disabled', 'disabled');
                return false;
            } else {
            
            var metal_item_id = $('#metal_item_id').val();
            var item_data = $('#metal_item_id').select2('data');
            lineitem['metal_item_name'] = item_data[0].text;
            
            $.ajax({
                url: "<?php echo base_url('sell/get_category_group'); ?>/" + metal_item_id,
                type: "GET",
                async: false,
                data: "",
                success: function(response){
                    var json = $.parseJSON(response);
                    lineitem['group_name'] = json;
                    lineitem['metal_payment_receipt'] = metal_payment_receipt;
                    if(lineitem['group_name'] == 1){
                        lineitem['metal_grwt'] = round(lineitem['metal_grwt'], 2).toFixed(3);
                        var metal_fine = lineitem['metal_fine'] = round(lineitem['metal_fine'], 2).toFixed(3);
                    } else {
                        lineitem['metal_grwt'] = round(lineitem['metal_grwt'], 1).toFixed(3);
                        var metal_fine = lineitem['metal_fine'] = round(lineitem['metal_fine'], 1).toFixed(3);
                    }
                    lineitem['total_grwt_metal'] = $('#total_grwt_metal').val();
                    var new_lineitem = JSON.parse(JSON.stringify(lineitem));
                    metal_index = $("#metal_index").val();
                    if (metal_index != '') {
                        
                        var metal_fine_prev = metal_objectdata[metal_index].metal_fine;
                        $.ajax({
                            url: "<?php echo base_url('sell/get_category_group'); ?>/" + metal_objectdata[metal_index].metal_item_id,
                            type: "GET",
                            async: false,
                            data: "",
                            success: function(response_perv){
                                var json_perv = $.parseJSON(response_perv);
                                
                                if(metal_objectdata[metal_index].metal_payment_receipt == '1' && metal_payment_receipt == '1'){
                                    
                                    if(json_perv == 1 && json == 1){
                                        metal_gold_total = metal_gold_total + parseFloat(metal_fine) - parseFloat(metal_fine_prev);
                                    } else if(json_perv == 1 && json == 2){
                                        metal_gold_total = metal_gold_total - parseFloat(metal_fine_prev);
                                        metal_silver_total = metal_silver_total + parseFloat(metal_fine);
                                    } else if(json_perv == 2 && json == 1){
                                        metal_gold_total = metal_gold_total + parseFloat(metal_fine);
                                        metal_silver_total = metal_silver_total - parseFloat(metal_fine_prev);
                                    } else if(json_perv == 2 && json == 2){
                                        metal_silver_total = metal_silver_total + parseFloat(metal_fine) - parseFloat(metal_fine_prev);
                                    }
                                    
                                } else if(metal_objectdata[metal_index].metal_payment_receipt == '1' && metal_payment_receipt == '2'){
                                    
                                    if(json_perv == 1 && json == 1){
                                        metal_gold_total = metal_gold_total - parseFloat(metal_fine) - parseFloat(metal_fine_prev);
                                    } else if(json_perv == 1 && json == 2){
                                        metal_gold_total = metal_gold_total - parseFloat(metal_fine_prev);
                                        metal_silver_total = metal_silver_total - parseFloat(metal_fine);
                                    } else if(json_perv == 2 && json == 1){
                                        metal_gold_total = metal_gold_total - parseFloat(metal_fine);
                                        metal_silver_total = metal_silver_total - parseFloat(metal_fine_prev);
                                    } else if(json_perv == 2 && json == 2){
                                        metal_silver_total = metal_silver_total - parseFloat(metal_fine) - parseFloat(metal_fine_prev);
                                    }
                                    
                                } else if(metal_objectdata[metal_index].metal_payment_receipt == '2' && metal_payment_receipt == '1'){
                                    
                                    if(json_perv == 1 && json == 1){
                                        metal_gold_total = metal_gold_total + parseFloat(metal_fine) + parseFloat(metal_fine_prev);
                                    } else if(json_perv == 1 && json == 2){
                                        metal_gold_total = metal_gold_total + parseFloat(metal_fine_prev);
                                        metal_silver_total = metal_silver_total + parseFloat(metal_fine);
                                    } else if(json_perv == 2 && json == 1){
                                        metal_gold_total = metal_gold_total + parseFloat(metal_fine);
                                        metal_silver_total = metal_silver_total + parseFloat(metal_fine_prev);
                                    } else if(json_perv == 2 && json == 2){
                                        metal_silver_total = metal_silver_total + parseFloat(metal_fine) + parseFloat(metal_fine_prev);
                                    }
                                    
                                } else if(metal_objectdata[metal_index].metal_payment_receipt == '2' && metal_payment_receipt == '2'){
                                    
                                    if(json_perv == 1 && json == 1){
                                        metal_gold_total = metal_gold_total - parseFloat(metal_fine) + parseFloat(metal_fine_prev);
                                    } else if(json_perv == 1 && json == 2){
                                        metal_gold_total = metal_gold_total + parseFloat(metal_fine_prev);
                                        metal_silver_total = metal_silver_total - parseFloat(metal_fine);
                                    } else if(json_perv == 2 && json == 1){
                                        metal_gold_total = metal_gold_total - parseFloat(metal_fine);
                                        metal_silver_total = metal_silver_total + parseFloat(metal_fine_prev);
                                    } else if(json_perv == 2 && json == 2){
                                        metal_silver_total = metal_silver_total - parseFloat(metal_fine) + parseFloat(metal_fine_prev);
                                    }
                                    
                                }
                            }
                        });
                        metal_objectdata.splice(metal_index, 1, new_lineitem);
                        
                    } else {
                        
                        if(metal_payment_receipt == '1'){
                            if(json == 1){
                                metal_gold_total = metal_gold_total + parseFloat(metal_fine);
                            } else if(json == 2){
                                metal_silver_total = metal_silver_total + parseFloat(metal_fine);
                            }  
                        } else if(metal_payment_receipt == '2'){
                            if(json == 1){
                                metal_gold_total = metal_gold_total - parseFloat(metal_fine);
                            } else if(json == 2){
                                metal_silver_total = metal_silver_total - parseFloat(metal_fine);
                            }
                        }
                        metal_objectdata.push(new_lineitem);
                    }
                    
                    display_metal_html(metal_objectdata);
                    $('#metal_pr_id').val('');
                    $("input[name=metal_payment_receipt]").prop("checked",false);
                    $("input[name=metal_payment_receipt]").removeAttr("disabled",false);
                    $('#metal_item_id').removeAttr('disabled', true);
                    $('#metal_tunch').removeAttr('readonly', true);
                    $("#metal_item_id").val(null).trigger("change");
                    $("#metal_grwt").val('');
                    $("#metal_tunch").val(100);
                    $("#metal_fine").val('');
                    $("#metal_narration").val('');
                    $("#metal_index").val('');
                    $('#total_grwt_metal').val('');
                    metal_index = '';
                }
            });
            }
            $('#metal_payment_receipt_id').val('1');
            $('#metal_receipt_payment_model').modal('hide');
            $('.metal_table').show();
            $('#metal_button').removeAttr('disabled', 'disabled');
        });
        
        $('#metal_receipt_payment_model').on('shown.bs.modal', function () {
            if ($.trim($("#metal_grwt").val()) == '') {
                $('#metal_item_id').select2('open');
            }
        });
        
        $('#metal_receipt_payment_model').on('hidden.bs.modal', function () {
            $('#metal_pr_id').val('');
            $("input[name=metal_payment_receipt]").prop("checked",false);
            $("input[name=metal_payment_receipt]").removeAttr("disabled",false);
            $('#metal_item_id').removeAttr('disabled', true);
            $('#metal_tunch').removeAttr('readonly', true);
            $("#metal_item_id").val(null).trigger("change");
            $("#metal_grwt").val('');
            $("#metal_tunch").val(100);
            $("#metal_fine").val('');
            $("#metal_narration").val('');
            $("#metal_index").val('');
            $('#total_grwt_metal').val('');
            $('#metal_payment_receipt_id').val('1');
            $(".delete_mpr_item").removeClass('hide');
        });
        
        $(document).on('keyup change', '#gold_weight, #gold_rate', function () {
            var gold_weight = parseFloat($('#gold_weight').val()) || 0;
            gold_weight = round(gold_weight, 2).toFixed(3);
            var gold_rate = parseFloat($('#gold_rate').val()) || 0;
            gold_rate = round(gold_rate, 2).toFixed(3);
            var value = 0;
            var value = parseFloat(gold_weight) * (parseFloat(gold_rate) / 10);
            $('#gold_value').val(round10(value.toFixed(0)));
        });
        
        $(document).on('change', '#gold_rate', function() {
            var gold_rate = $('#gold_rate').val();
            var gold_min = <?php echo $gold_min; ?>;
            var gold_max = <?php echo $gold_max; ?>;
            if((gold_rate < gold_min) || (gold_rate > gold_max)){
                if (confirm('Gold Rate is Out of Range, Are You Sure?')) {
                    var allow_to_save_gs_bhav_out = <?php echo $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow to save gold / silver bhav out of range"); ?>;
                    if(allow_to_save_gs_bhav_out != 1){
                        show_notify('You have Not Allow to Save Gold / Silver Bhav Out of Range.', false);
                        return false;
                    }
                } else {
                    $('#gold_rate').val('');
                    $('#gold_rate').focus();
                }
            }
        });
        
        $(document).on('click', '#gold_button', function ()  {
            if (!$('input[name=gold_sale_purchase]:checked').val()) {
                show_notify('Please Select Sale or Purchase.', false);
                $("#gold_sale_purchase").focus();
                return false;
            }
            
            if ($.trim($("#gold_weight").val()) == '') {
                show_notify('Please Enter Weight.', false);
                $("#gold_weight").focus();
                return false;
            }
            
            if ($.trim($("#gold_rate").val()) == '') {
                show_notify('Please Enter Rate.', false);
                $("#gold_rate").focus();
                return false;
            }
            
            var gold_rate = $('#gold_rate').val();
            var gold_min = <?php echo $gold_min; ?>;
            var gold_max = <?php echo $gold_max; ?>;
            if((gold_rate < gold_min) || (gold_rate > gold_max)){
                var allow_to_save_gs_bhav_out = <?php echo $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow to save gold / silver bhav out of range"); ?>;
                if(allow_to_save_gs_bhav_out != 1){
                    show_notify('You have Not Allow to Save Gold / Silver Bhav Out of Range.', false);
                    return false;
                }
            }
            
            $('#gold_button').attr('disabled', 'disabled');
            var key = '';
            var value = '';
            var lineitem = {};
            $('select[name^="gold_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("gold_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('input[name^="gold_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("gold_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            var gold_sale_purchase = $('input[name=gold_sale_purchase]:checked').val();
            lineitem['gold_sale_purchase'] = gold_sale_purchase;
            var gold_weight = lineitem['gold_weight'] = round(lineitem['gold_weight'], 2).toFixed(3);
            var gold_value = lineitem['gold_value'];
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));

            var gold_index = $("#gold_index").val();
            if (gold_index != '') {
                
                var gold_weight_prev = gold_objectdata[gold_index].gold_weight;
                var gold_value_prev = gold_objectdata[gold_index].gold_value;
                if(gold_objectdata[gold_index].gold_sale_purchase == '1' && gold_sale_purchase == '1'){
                    
                    gold_count = gold_count - parseFloat(gold_weight) + parseFloat(gold_weight_prev);
                    total_amount = total_amount + parseFloat(gold_value) - parseFloat(gold_value_prev);
                    
                } else if(gold_objectdata[gold_index].gold_sale_purchase == '1' && gold_sale_purchase == '2'){
                    
                    gold_count = gold_count + parseFloat(gold_weight) + parseFloat(gold_weight_prev);
                    total_amount = total_amount - parseFloat(gold_value) - parseFloat(gold_value_prev);
                    
                } else if(gold_objectdata[gold_index].gold_sale_purchase == '2' && gold_sale_purchase == '1'){
                    
                    gold_count = gold_count - parseFloat(gold_weight) - parseFloat(gold_weight_prev);
                    total_amount = total_amount + parseFloat(gold_value) + parseFloat(gold_value_prev);
                    
                } else if(gold_objectdata[gold_index].gold_sale_purchase == '2' && gold_sale_purchase == '2'){
                    
                    gold_count = gold_count + parseFloat(gold_weight) - parseFloat(gold_weight_prev);
                    total_amount = total_amount - parseFloat(gold_value) + parseFloat(gold_value_prev);
                    
                }
                gold_objectdata.splice(gold_index, 1, new_lineitem);
                
            } else {
                
                if(gold_sale_purchase == '1'){
                    gold_count = gold_count - parseFloat(gold_weight);
                    total_amount = total_amount + parseFloat(gold_value);
                } else if(gold_sale_purchase == '2') {
                    gold_count = gold_count + parseFloat(gold_weight);
                    total_amount = total_amount - parseFloat(gold_value);
                }
                
                gold_objectdata.push(new_lineitem);
            }

            display_gold_html(gold_objectdata);
            $("#gold_id").val('');
            $("input[name=gold_sale_purchase]").prop("checked",false);
            $("#gold_weight").val('');
            $("#gold_rate").val('');
            $("#gold_value").val('');
            $("#gold_narration").val('');
            $("#gold_index").val('');
            $('#gold_bhav_id').val('1');
            $('#gold_bhav_model').modal('hide');
            $('.gold_table').show();
            $('#gold_button').removeAttr('disabled', 'disabled');
        });
        
        $('#gold_bhav_model').on('shown.bs.modal', function () {
            $('#gold_weight').focus();
        });
        
        $('#gold_bhav_model').on('hidden.bs.modal', function () {
            $("#gold_id").val('');
            $("input[name=gold_sale_purchase]").prop("checked",false);
            $("#gold_weight").val('');
            $("#gold_rate").val('');
            $("#gold_value").val('');
            $("#gold_narration").val('');
            $("#gold_index").val('');
            $('#gold_bhav_id').val('1');
            $(".delete_g_item").removeClass('hide');
        });
        
        $(document).on('keyup change', '#silver_weight, #silver_rate', function () {
            var silver_weight = parseFloat($('#silver_weight').val()) || 0;
            silver_weight = round(silver_weight, 1).toFixed(3);
            var silver_rate = parseFloat($('#silver_rate').val()) || 0;
            silver_rate = round(silver_rate, 1).toFixed(3);
            var value = 0;
            var value = parseFloat(silver_weight) * (parseFloat(silver_rate) / 10);
            $('#silver_value').val(round10(value.toFixed(0)));
        });
        
        $(document).on('change', '#silver_rate', function() {
            var silver_rate = $('#silver_rate').val();
            var silver_min = <?php echo $silver_min; ?>;
            var silver_max = <?php echo $silver_max; ?>;
            if((silver_rate < silver_min) || (silver_rate > silver_max)){
                if (confirm('Silver Rate is Out of Range, Are You Sure?')) {
                    var allow_to_save_gs_bhav_out = <?php echo $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow to save gold / silver bhav out of range"); ?>;
                    if(allow_to_save_gs_bhav_out != 1){
                        show_notify('You have Not Allow to Save Gold / Silver Bhav Out of Range.', false);
                        return false;
                    }
                } else {
                    $('#silver_rate').val('');
                    $('#silver_rate').focus();
                }
            }
        });
        
        $(document).on('click', '#silver_button', function () {
            if (!$('input[name=silver_sale_purchase]:checked').val()) {
                show_notify('Please Select Sale or Purchase.', false);
                $(".silver_sale_purchase").focus();
                return false;
            }
            if ($.trim($("#silver_weight").val()) == '') {
                show_notify('Please Enter Weight.', false);
                $("#silver_weight").focus();
                return false;
            }
            if ($.trim($("#silver_rate").val()) == '') {
                show_notify('Please Enter Rate.', false);
                $("#silver_rate").focus();
                return false;
            }
            
            var silver_rate = $('#silver_rate').val();
            var silver_min = <?php echo $silver_min; ?>;
            var silver_max = <?php echo $silver_max; ?>;
            if((silver_rate < silver_min) || (silver_rate > silver_max)){
                var allow_to_save_gs_bhav_out = <?php echo $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow to save gold / silver bhav out of range"); ?>;
                if(allow_to_save_gs_bhav_out != 1){
                    show_notify('You have Not Allow to Save Gold / Silver Bhav Out of Range.', false);
                    return false;
                }
            }
            
            $('#silver_button').attr('disabled', 'disabled');
            var key = '';
            var value = '';
            var lineitem = {};
            $('select[name^="silver_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("silver_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('input[name^="silver_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("silver_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            var silver_sale_purchase = $('input[name=silver_sale_purchase]:checked').val();
            lineitem['silver_sale_purchase'] = silver_sale_purchase;
            var silver_weight = lineitem['silver_weight'] = round(lineitem['silver_weight'], 1).toFixed(3);
            var silver_value = lineitem['silver_value'];
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
            
            var silver_index = $("#silver_index").val();
            if (silver_index != '') {
                
                var silver_weight_prev = silver_objectdata[silver_index].silver_weight;
                var silver_value_prev = silver_objectdata[silver_index].silver_value;
                if(silver_objectdata[silver_index].silver_sale_purchase == '1' && silver_sale_purchase == '1'){
                    
                    silver_count = silver_count - parseFloat(silver_weight) + parseFloat(silver_weight_prev);
                    total_amount = total_amount + parseFloat(silver_value) - parseFloat(silver_value_prev);
                    
                } else if(silver_objectdata[silver_index].silver_sale_purchase == '1' && silver_sale_purchase == '2'){
                    
                    silver_count = silver_count + parseFloat(silver_weight) + parseFloat(silver_weight_prev);
                    total_amount = total_amount - parseFloat(silver_value) - parseFloat(silver_value_prev);
                    
                } else if(silver_objectdata[silver_index].silver_sale_purchase == '2' && silver_sale_purchase == '1'){
                    
                    silver_count = silver_count - parseFloat(silver_weight) - parseFloat(silver_weight_prev);
                    total_amount = total_amount + parseFloat(silver_value) + parseFloat(silver_value_prev);
                    
                } else if(silver_objectdata[silver_index].silver_sale_purchase == '2' && silver_sale_purchase == '2'){
                    
                    silver_count = silver_count + parseFloat(silver_weight) - parseFloat(silver_weight_prev);
                    total_amount = total_amount - parseFloat(silver_value) + parseFloat(silver_value_prev);
                    
                }
                silver_objectdata.splice(silver_index, 1, new_lineitem);
                
            } else {
                if(silver_sale_purchase == '1'){
                    silver_count = silver_count - parseFloat(silver_weight);
                    total_amount = total_amount + parseFloat(silver_value);
                } else if(silver_sale_purchase == '2') {
                    silver_count = silver_count + parseFloat(silver_weight);
                    total_amount = total_amount - parseFloat(silver_value);
                }
                
                silver_objectdata.push(new_lineitem);
            }
            
            display_silver_html(silver_objectdata);
            $('#silver_id').val('');
            $("input[name=silver_sale_purchase]").prop("checked",false);
            $("#silver_weight").val('');
            $("#silver_rate").val('');
            $("#silver_value").val('');
            $("#silver_narration").val('');
            $("#silver_index").val('');
            $('#silver_bhav_id').val('1');
            $('#silver_bhav_model').modal('hide');
            $('.silver_table').show();
            $('#silver_button').removeAttr('disabled', 'disabled');
        });
        
        $('#silver_bhav_model').on('shown.bs.modal', function () {
            $('#silver_weight').focus();
        });
        
        $('#silver_bhav_model').on('hidden.bs.modal', function () {
            $('#silver_id').val('');
            $("input[name=silver_sale_purchase]").prop("checked",false);
            $("#silver_weight").val('');
            $("#silver_rate").val('');
            $("#silver_value").val('');
            $("#silver_narration").val('');
            $("#silver_index").val('');
            $('#silver_bhav_id').val('1');
            $(".delete_s_item").removeClass('hide');
        });
        
        $(document).on('change', '#transfer_account_id', function (){
            var transfer_account_id = $('#transfer_account_id').val();
            if(transfer_account_id != '' && transfer_account_id != null){
                $.ajax({
                    url: "<?= base_url('sell/get_account_old_balance') ?>/" + transfer_account_id,
                    type: 'GET',
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        var gold_fine = parseFloat(json.gold_fine);
                        var silver_fine = parseFloat(json.silver_fine);
                        var amount = parseFloat(json.amount);
                        $('.selected_account_gold_fine').html(gold_fine);
                        $('.selected_account_silver_fine').html(silver_fine);
                        $('.selected_account_amount').html(amount);
                    }
                });
            } else {
                $('.selected_account_gold_fine').html('');
                $('.selected_account_silver_fine').html('');
                $('.selected_account_amount').html('');
            }
        });
        
        $(document).on('click', '#transfer_button', function () {
            if (!$('input[name=naam_jama]:checked').val()) {
                show_notify('Please Select Naam or Jama.', false);
                $("#naam_jama").focus();
                return false;
            }
            if (!$('#transfer_account_id').val()) {
                show_notify('Please Select Party Name.', false);
                $("#transfer_account_id").focus();
                return false;
            }
            var transfer_gold = $('#transfer_gold').val() || 0;
            var transfer_silver = $('#transfer_silver').val() || 0;
            var transfer_amount = $('#transfer_amount').val() || 0;
            if (transfer_gold <= 0 && transfer_silver <= 0 && transfer_amount <= 0) {
                show_notify('Please Enter Gold, Silver or Amount.', false);
                return false;
            }
            
            $('#transfer_button').attr('disabled', 'disabled');
            var key = '';
            var value = '';
            var lineitem = {};
            $('select[name^="transfer_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("transfer_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('input[name^="transfer_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("transfer_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            var naam_jama = $('input[name=naam_jama]:checked').val();
            lineitem['naam_jama'] = naam_jama;
            var party_name = $('#transfer_account_id').select2('data');
            lineitem['party_name'] = party_name[0].text;
            var transfer_gold = lineitem['transfer_gold'] = round(lineitem['transfer_gold'], 2).toFixed(3);
            var transfer_silver = lineitem['transfer_silver'] = round(lineitem['transfer_silver'], 2).toFixed(3);
            var transfer_amount = lineitem['transfer_amount'] = parseFloat($('#transfer_amount').val()) || 0;
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
            
            var transfer_naam_jama = $('input[name=naam_jama]:checked').val();
            var transfer_index = $("#transfer_index").val();
            if (transfer_index != '') {
                var transfer_gold_prev = transfer_objectdata[transfer_index].transfer_gold;
                var transfer_silver_prev = transfer_objectdata[transfer_index].transfer_silver;
                var transfer_amount_prev = transfer_objectdata[transfer_index].transfer_amount;
                
                if(transfer_objectdata[transfer_index].naam_jama == '1' && transfer_naam_jama == '1'){
                    
                    transfer_gold_total = transfer_gold_total - parseFloat(transfer_gold) + parseFloat(transfer_gold_prev);
                    transfer_silver_total = transfer_silver_total - parseFloat(transfer_silver) + parseFloat(transfer_silver_prev);
                    total_amount = total_amount - parseFloat(transfer_amount) + parseFloat(transfer_amount_prev);
                    
                } else if(transfer_objectdata[transfer_index].naam_jama == '1' && transfer_naam_jama == '2'){
                    
                    transfer_gold_total = transfer_gold_total + parseFloat(transfer_gold) + parseFloat(transfer_gold_prev);
                    transfer_silver_total = transfer_silver_total + parseFloat(transfer_silver) + parseFloat(transfer_silver_prev);
                    total_amount = total_amount + parseFloat(transfer_amount) + parseFloat(transfer_amount_prev);
                    
                } else if(transfer_objectdata[transfer_index].naam_jama == '2' && transfer_naam_jama == '1'){
                    
                    transfer_gold_total = transfer_gold_total - parseFloat(transfer_gold) - parseFloat(transfer_gold_prev);
                    transfer_silver_total = transfer_silver_total - parseFloat(transfer_silver) - parseFloat(transfer_silver_prev);
                    total_amount = total_amount - parseFloat(transfer_amount) - parseFloat(transfer_amount_prev);
                    
                } else if(transfer_objectdata[transfer_index].naam_jama == '2' && transfer_naam_jama == '2'){

                    transfer_gold_total = transfer_gold_total + parseFloat(transfer_gold) - parseFloat(transfer_gold_prev);
                    transfer_silver_total = transfer_silver_total + parseFloat(transfer_silver) - parseFloat(transfer_silver_prev);
                    total_amount = total_amount + parseFloat(transfer_amount) - parseFloat(transfer_amount_prev);
                    
                }
                
                transfer_objectdata.splice(transfer_index, 1, new_lineitem);
                
            } else {
                
                if(transfer_naam_jama == '1') {
                    transfer_gold_total = transfer_gold_total - parseFloat(transfer_gold);
                    transfer_silver_total = transfer_silver_total - parseFloat(transfer_silver);
                    total_amount = total_amount - parseFloat(transfer_amount);
                } else if(transfer_naam_jama == '2'){
                    transfer_gold_total = transfer_gold_total + parseFloat(transfer_gold);
                    transfer_silver_total = transfer_silver_total + parseFloat(transfer_silver);
                    total_amount = total_amount + parseFloat(transfer_amount);
                }                
                transfer_objectdata.push(new_lineitem);
            }
            
            display_transfer_html(transfer_objectdata);
            $('#transfer_id').val('1');
            $('#transfer_entry_id').val('');
            $("input[name=naam_jama]").prop("checked",false);
            $('#transfer_account_id').val(null).trigger("change");
            $("#transfer_gold").val('');
            $("#transfer_silver").val('');
            $("#transfer_amount").val('');
            $("#transfer_narration").val('');
            $("#transfer_index").val('');
            $('#transfer_model').modal('hide');
            $('.transfer_table').show();
            $('.selected_account_gold_fine').html('');
            $('.selected_account_silver_fine').html('');
            $('.selected_account_amount').html('');
            $('#transfer_button').removeAttr('disabled', 'disabled');
        });
        
        $('#transfer_model').on('shown.bs.modal', function () {
            if ($.trim($("#transfer_account_id").val()) == '') {
                $('#transfer_account_id').select2('open');
            }
        });
        
        $('#transfer_model').on('hidden.bs.modal', function () {
            $('#transfer_id').val('1');
            $('#transfer_entry_id').val('');
            $("input[name=naam_jama]").prop("checked",false);
            $('#transfer_account_id').val(null).trigger("change");
            $("#transfer_gold").val('');
            $("#transfer_silver").val('');
            $("#transfer_amount").val('');
            $("#transfer_narration").val('');
            $("#transfer_index").val('');
            $('.selected_account_gold_fine').html('');
            $('.selected_account_silver_fine').html('');
            $('.selected_account_amount').html('');
            $(".delete_t_item").removeClass('hide');
        });
        
        $(document).on('click', '#ad_charges_button', function () {
            var ad_id = $("#ad_id").val();
            if (ad_id == '' || ad_id == null) {
                $("#ad_id").select2('open');
                show_notify("Please select Ad!", false);
                return false;
            }
            var ad_pcs = $("#ad_pcs").val();
            if (ad_pcs == '' || ad_pcs == null) {
                show_notify("Ad Pcs is required!", false);
                $("#ad_pcs").focus();
                return false;
            }
            var ad_rate = $("#ad_rate").val();
            if (ad_rate == '' || ad_rate == null) {
                show_notify("Ad Rate is required!", false);
                $("#ad_rate").focus();
                return false;
            }
            $('#ad_charges_button').attr('disabled', 'disabled');
            var key = '';
            var value = '';
            var lineitem = {};
            $('select[name^="ad_lineitem_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("ad_lineitem_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            $('input[name^="ad_lineitem_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("ad_lineitem_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            var ad_name = $('#ad_id').select2('data');
            lineitem['ad_name'] = ad_name[0].text;
            var ad_amount = lineitem['ad_amount'] = parseFloat($('#ad_amount').val()) || 0;
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
            
            var ad_lineitem_index = $("#ad_lineitem_index").val();
            if (ad_lineitem_index != '') {
                var ad_amount_prev = ad_charges_objectdata[ad_lineitem_index].ad_amount;
                total_amount = total_amount + parseFloat(ad_amount) - parseFloat(ad_amount_prev);
                ad_charges_objectdata.splice(ad_lineitem_index, 1, new_lineitem);
            } else {
                total_amount = total_amount + parseFloat(ad_amount);
                ad_charges_objectdata.push(new_lineitem);
            }
            
            display_ad_charges_html(ad_charges_objectdata);
            $('#sell_ad_charges_id').val('');
            $('#ad_id').val(null).trigger("change");
            $("#ad_pcs").val('');
            $("#ad_rate").val('');
            $("#ad_amount").val('');
            $("#ad_lineitem_index").val('');
            $('#ad_charges_model').modal('hide');
            $('.ad_charges_table').show();
            $('#ad_charges_button').removeAttr('disabled', 'disabled');
        });
        
        $('#ad_charges_model').on('shown.bs.modal', function () {
            initAjaxSelect2($("#ad_id"), "<?= base_url('app/ad_name_select2_source/is_sell_purchase_ad_charges') ?>");
            if ($.trim($("#ad_id").val()) == '') {
                $('#ad_id').select2('open');
            }
        });
        
        $('#ad_charges_model').on('hidden.bs.modal', function () {
            $('#sell_ad_charges_id').val('');
            $('#ad_id').val(null).trigger("change");
            $("#ad_pcs").val('');
            $("#ad_rate").val('');
            $("#ad_amount").val('');
            $("#ad_lineitem_index").val('');
            $('#ad_charges_model').modal('hide');
            $(".delete_ad_item").removeClass('hide');
        });
        
        $(document).on('click', '#order_to_sell_button', function () {
            var order_grwt= new Array();
            $("input[name='order_grwt[]']").each(function(){
                order_grwt.push($(this).val());
            });
            var order_less= new Array();
            $("input[name='order_less[]']").each(function(){
                order_less.push($(this).val());
            });
            if ($("input.selected_index:checked").length == 0) {
                show_notify('Please select at least one item.', false);
                return false;
            }
            
            var selected_index_lineitems = [];
            var order_gold_bhav = 0;
            var order_silver_bhav = 0;
            $.each($("input.selected_index:checked"), function() {
                var selected_index = $(this).data('selected_index');
                ots_lineitem_objectdata[selected_index].sell_item_delete = 'allow';
                ots_lineitem_objectdata[selected_index].grwt = order_grwt[selected_index];
                ots_lineitem_objectdata[selected_index].less = order_less[selected_index];
                ots_lineitem_objectdata[selected_index].net_wt = parseFloat(ots_lineitem_objectdata[selected_index].grwt) - parseFloat(ots_lineitem_objectdata[selected_index].less);
                ots_lineitem_objectdata[selected_index].net_wt = round(ots_lineitem_objectdata[selected_index].net_wt, 2).toFixed(3);
                ots_lineitem_objectdata[selected_index].fine = parseFloat(ots_lineitem_objectdata[selected_index].net_wt) * (parseFloat(ots_lineitem_objectdata[selected_index].touch_id) + parseFloat(ots_lineitem_objectdata[selected_index].wstg)) / 100;
                
                selected_index_lineitems.push(ots_lineitem_objectdata[selected_index]);
                
                if(ots_lineitem_objectdata[selected_index].group_name == '<?php echo CATEGORY_GROUP_GOLD_ID; ?>'){
                    order_gold_bhav = order_gold_bhav + parseFloat(ots_lineitem_objectdata[selected_index].fine);
                }
                if(ots_lineitem_objectdata[selected_index].group_name == '<?php echo CATEGORY_GROUP_SILVER_ID; ?>'){
                    order_silver_bhav = order_silver_bhav + parseFloat(ots_lineitem_objectdata[selected_index].fine);
                }
			});
            <?php if (isset($new_order_data->gold_price) && !empty($new_order_data->gold_price)) { ?>
                if(order_gold_bhav != 0){
                    $('input:radio[name=gold_sale_purchase]').filter('[value=1]').prop('checked', true);
                    var gold_weight = order_gold_bhav;
                    var gold_rate = <?php echo $new_order_data->gold_price; ?>;
                    $('#gold_weight').val(gold_weight);
                    $('#gold_rate').val(gold_rate);
                    var value = gold_weight * (gold_rate / 10);
                    $('#gold_value').val(value.toFixed(0));
                    $('#gold_button').click();
                }
            <?php } ?>
            <?php if (isset($new_order_data->silver_price) && !empty($new_order_data->silver_price)) { ?>
                if(order_silver_bhav != 0){
                    $('input:radio[name=silver_sale_purchase]').filter('[value=1]').prop('checked', true);
                    var silver_weight = order_silver_bhav;
                    var silver_rate = <?php echo $new_order_data->silver_price; ?>;
                    $('#silver_weight').val(silver_weight);
                    $('#silver_rate').val(silver_rate);
                    var value = silver_weight * (silver_rate / 10);
                    $('#silver_value').val(value.toFixed(0));
                    $('#silver_button').click();
                }
            <?php } ?>
            $('#item_selection_popup').modal('hide');
            lineitem_objectdata = selected_index_lineitems;
            display_lineitem_html(lineitem_objectdata);
        });
        
        $(document).on('click', '#purchase_to_sell_button', function () {
//            console.log(lineitem_objectdata);
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
            var sell_allow = 1;
            $.each($("input.pts_selected_index:checked"), function() {
                pts_item_id = $(this).data('item_id');
                var pts_selected_index = $(this).data('pts_selected_index');
                pts_lineitem_objectdata[pts_selected_index].sell_item_delete = 'allow';
                pts_lineitem_objectdata[pts_selected_index].grwt = pts_grwt[pts_selected_index] || 0;
                pts_lineitem_objectdata[pts_selected_index].less = pts_less[pts_selected_index] || 0;
                pts_lineitem_objectdata[pts_selected_index].wstg = pts_wstg[pts_selected_index];
                pts_lineitem_objectdata[pts_selected_index].net_wt = parseFloat(pts_lineitem_objectdata[pts_selected_index].grwt || 0) - parseFloat(pts_lineitem_objectdata[pts_selected_index].less || 0);
                pts_lineitem_objectdata[pts_selected_index].net_wt = round(pts_lineitem_objectdata[pts_selected_index].net_wt, 2).toFixed(3);
                pts_lineitem_objectdata[pts_selected_index].fine = parseFloat(pts_lineitem_objectdata[pts_selected_index].net_wt) * (parseFloat(pts_lineitem_objectdata[pts_selected_index].touch_id) + parseFloat(pts_lineitem_objectdata[pts_selected_index].wstg || 0)) / 100;
                pts_lineitem_objectdata[pts_selected_index].fine = round(pts_lineitem_objectdata[pts_selected_index].fine, 2).toFixed(3);
                
                /******* You not allow to Sell, The Parchse of same Entry! Start *******/
                $.each(lineitem_objectdata, function(index, value) {
                    if(typeof(pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id) !== "undefined" && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id !== null && 
                            typeof(value.sell_item_id) !== "undefined" && value.sell_item_id !== null && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id == value.sell_item_id && 
                            (pts_lineitem_objectdata[pts_selected_index].stock_type == '1' ||  pts_lineitem_objectdata[pts_selected_index].stock_type == '2')) {
                        show_notify('You not allow to Sell, The Parchse of same Entry!', false);
                        sell_allow = 0;
                        return false;
                    }
                });
                if(sell_allow == 1){
                    pts_selected_index_lineitems.push(pts_lineitem_objectdata[pts_selected_index]);
                }
                /******* You not allow to Sell, The Parchse of same Entry! End *******/
            });
//            lineitem_delete = [];
//            jQuery.each(lineitem_objectdata, function(obj, values) {
//                lineitem_delete.push(values.sell_item_id);
//            });
//            pts_delete = [];
//            jQuery.each(pts_selected_index_lineitems, function(obj, values) {
//                pts_delete.push(values.sell_item_id);
//            });
//            var uncheck_sell_item = $(lineitem_delete).not(pts_delete).get();
//
//            $('.line_item_form').append('<input type="hidden" name="deleted_sell_item_id[]" id="deleted_sell_item_id" value="' + uncheck_sell_item + '" />');
                
            $('#purchase_item_selection_popup').modal('hide');
            
            /******* Remove Same Item Sell Entry! Start *******/
            var remove_arr = [];
            $.each(lineitem_objectdata, function(index, value) {
                if(typeof(value.item_id) !== "undefined" && value.item_id !== null && value.item_id == pts_item_id && value.type == '1') {
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
            /******* Remove Same Item Sell Entry! End *******/
            
//            lineitem_objectdata = pts_selected_index_lineitems;
            lineitem_objectdata = $.merge(pts_selected_index_lineitems, lineitem_objectdata);
            display_lineitem_html(lineitem_objectdata);
            $('#select_all_purchase_to_sell').prop('checked', false);
            if(sell_purchase == "sell") {
                setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_SELL_ID) ?>");
            } else if(sell_purchase == "purchase") {
                setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_PURCHASE_ID) ?>");
            } else {
                $('#sell_type_id').val(null).trigger("change");    
            }
            $("#category_id").val(null).trigger("change");
            $("#item_id").val(null).trigger("change");
            $("#grwt").val('');
            $("#less").val('');
            $('#less').removeAttr('readonly', 'readonly');
            $('#less_ad_details').removeClass('hide');
            $("#net_wt").val('');
            $("#touch_id").val(null).trigger("change");
            $("#touch_data_id").val('');
            <?php if($wstg_2 == '1') { ?>
                $("#wstg").val('');
            <?php } else { ?>
                $("#wstg").val('0');
            <?php } ?>
            $("#fine").val('');
            $("#image").val('');
            $("#file_upload").val('');
            $("#line_items_index").val('');
        });
        
        <?php if(!isset($sell_data->audit_status) || (isset($sell_data->audit_status) && $sell_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
            <?php if(!isset($sell_data->account_id) || (isset($sell_data->account_id) && $sell_data->account_id != ADJUST_EXPENSE_ACCOUNT_ID)){ ?>
            $(document).bind("keydown", function(e){
                if(e.ctrlKey && e.which == 83){
                    if($('#payment_receipt_model').hasClass('in')){
                        $("#payment_receipt_button").click();
                        return false;
                    }
                    if($('#metal_receipt_payment_model').hasClass('in')){
                        $("#metal_button").click();
                        return false;
                    }
                    if($('#gold_bhav_model').hasClass('in')){
                        $("#gold_button").click();
                        return false;
                    }
                    if($('#silver_bhav_model').hasClass('in')){
                        $("#silver_button").click();
                        return false;
                    }
                    if($('#transfer_model').hasClass('in')){
                        $("#transfer_button").click();
                        return false;
                    }
                    if(save_sell_purchase_submit_flag == 0 ){
                        $("#save_sell_purchase").submit();
                        return false;
                    }
                }
            });
            <?php } ?>
        <?php } ?>
        
        $(document).on('submit', '#save_sell_purchase', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Account Name.', false);
                $("#account_id").select2('open');
                return false;
            }
            if ($.trim($("#process_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#process_id").select2('open');
                return false;
            }
            var datepicker2 = $("#datepicker2").val();
            if(datepicker2 == '' ){
                show_notify('Please Select Date.', false);
                $("#datepicker2").focus();
                return false;
            }
            if (lineitem_objectdata == '' && pay_rec_objectdata == '' && metal_objectdata == '' && gold_objectdata == '' && silver_objectdata == '' && transfer_objectdata == '' && ad_charges_objectdata == '') {
                show_notify("Please Add Sell Item.", false);
                return false;
            }
            var account_id = $('#account_id').val();
            if(account_id == <?php echo CASE_CUSTOMER_ACCOUNT_ID; ?>){
                var gold_fine_total = $('#gold_fine_total').val();
                var silver_fine_total = $('#silver_fine_total').val();
                if(gold_fine_total != 0 || silver_fine_total != 0 || total_amount != 0){
                    show_notify('Total Must Be 0.', false);
                    return false;
                } 
            }
            var is_grater = 0;
            
            if(app_net_amt > credit_limit){
                is_grater = 1;
            }
            var postData = new FormData(this);
            var href_url = '<?= base_url('sell/save_sell') ?>';
            if(is_grater == 1){
                if (confirm('Credit Limit : ' + credit_limit)) {
                    var allow_to_save_cl_out = <?php echo $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "allow to save out of credit limit"); ?>;
                    if(allow_to_save_cl_out != 1){
                        show_notify('You have Not Allow to Save Out of Credit Limit.', false);
                        return false;
                    }
                    <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
                        if (($("#deleted_sell_item_id").length > 0)){
                            var sell_id = '<?php echo $sell_data->sell_id;?>';
                            $.ajax({
                                url: "<?php echo site_url('sell/check_rfid_in_lineitems_or_not') ?>" + '/' + sell_id,
                                type: "POST",
                                data: '',
                                async: false,
                                success: function (response) {
                                    var json = $.parseJSON(response);
                                    if (json['have_rfid'] == '1') {
                                        if (confirm('If you have RFID, Then Click on Ok Button to open RIFD, Otherwise Click on Cancel Button to Inc. Loose Stock.')) {
                                            href_url = href_url + '/1';
                                            save_form(postData, href_url);
                                        } else {
                                            save_form(postData, href_url);
                                        }
                                    } else {
                                        save_form(postData, href_url);
                                    }
                                }
                            });
                        } else {
                            save_form(postData, href_url);
                        }
                    <?php } else { ?>
                        save_form(postData, href_url);
                    <?php } ?>
                }
            } else {
                <?php if (isset($sell_data->sell_id) && !empty($sell_data->sell_id)) { ?>
                    if (($("#deleted_sell_item_id").length > 0)){
                        var sell_id = '<?php echo $sell_data->sell_id;?>';
                        $.ajax({
                            url: "<?php echo site_url('sell/check_rfid_in_lineitems_or_not') ?>" + '/' + sell_id,
                            type: "POST",
                            data: '',
                            async: false,
                            success: function (response) {
                                var json = $.parseJSON(response);
                                if (json['have_rfid'] == '1') {
                                    if (confirm('If you have RFID, Then Click on Ok Button to open RIFD, Otherwise Click on Cancel Button to Inc. Loose Stock.')) {
                                        href_url = href_url + '/1';
                                        save_form(postData, href_url);
                                    } else {
                                        save_form(postData, href_url);
                                    }
                                } else {
                                    save_form(postData, href_url);
                                }
                            }
                        });
                    } else {
                        save_form(postData, href_url);
                    }
                <?php } else { ?>
                    save_form(postData, href_url);
                <?php } ?>
            }
            save_sell_purchase_submit_flag = 1;
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
            var less = $("#less").val();
            if(parseFloat(less) > parseFloat(grwt)){
                show_notify('Less Can not be > grwt.', false);
                $("#less").focus();
                return false;
            }
            
            if (sell_type_id == '3') {
                if ($.trim($("#touch_data_id").val()) == '') {
                    $("#touch_data_id").focus();
                    show_notify("Please Enter Touch!", false);
                    return false;
                }
            } else {
                if($('#tunch_textbox').prop("checked") == true){
                    if ($.trim($("#touch_data_id").val()) == '') {
                        $("#touch_data_id").focus();
                        show_notify("Please Enter Touch!", false);
                        return false;
                    }  
                } else {
                    if ($.trim($("#touch_id").val()) == '') {
                        $("#touch_id").focus();
                        show_notify("Please select Tunch!", false);
                        return false;
                    }
                }
                var wstg = $("#wstg").val();
                if (wstg == '' || wstg == null) {
                    $("#wstg").focus();
                    show_notify("Please Enter Wstg.!", false);
                    return false;
                }
            }
            if (sell_type_id == '3') {
                var tunch = $("#touch_data_id").val();
                var wstg = $("#wstg").val();
                var tunch_wstg = parseFloat(tunch) + parseFloat(wstg);
                if (tunch_wstg > 100) {
                    $("#wstg").focus();
                    show_notify("Please Enter Tunch + wstg less than 100!", false);
                    return false;
                }
            }
            
            var hasMatch =false;
            var line_items_index = $("#line_items_index").val();
            var rfid_number = $("#rfid_number").val();
            for (var line_i = 0; line_i < lineitem_objectdata.length; ++line_i) {
                var itemrow = lineitem_objectdata[line_i];
                if(rfid_number != '' && rfid_number != null && itemrow.rfid_number == rfid_number && line_items_index == ''){
                    show_notify("RFID Used in this Entry!", false);
                    $("#sell_type_id").val(null).trigger("change");
                    $("#category_id").val(null).trigger("change");
                    $("#item_id").val(null).trigger("change");
                    $("#grwt").val('');
                    $("#less").val('');
                    $("#net_wt").val('');
                    $(".touch_id").val(null).trigger("change");
                    $(".wstg").val('');
                    $("#fine").val('');
                    $("#charges_amt").val(0);
                    $("#item_stock_rfid_id").val('');
                    $("#rfid_number").val('');
                    $('#rfid_number').focus();
                    return false;
                }
            }

            $("#add_lineitem").attr('disabled', 'disabled');
            var sell_item_id = $("#sell_item_id").val();
            if (typeof (sell_item_id) != "undefined" && sell_item_id !== null) {
                $('.line_item_form #deleted_sell_item_id[value="' + sell_item_id + '"]').remove();
            }
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
            lineitem['sell_less_ad_details'] = JSON.stringify(less_ad_details_objectdata);
            less_ad_details_objectdata.length = 0;
            display_less_ad_details_html(less_ad_details_objectdata);
            
            var charges_amt = lineitem['charges_amt'] = parseFloat($('#charges_amt').val()) || 0;
            
            var line_items_index = $("#line_items_index").val();
            if (line_items_index != '') {
                var ad_amount_prev = lineitem_objectdata[line_items_index].charges_amt || 0;
                total_amount = parseFloat(total_amount) + parseFloat(charges_amt) - parseFloat(ad_amount_prev);
            } else {
                total_amount = parseFloat(total_amount) + parseFloat(charges_amt);
            }
            
            var item_id = $('#item_id').val();
            var category_id = $('#category_id').val();
            if($('#tunch_textbox').prop("checked") == true){
                var touch_id = $('#touch_data_id').val();
            } else {
                var touch_id = $('#touch_id').val();
            }
//            $('select[name^="line_items_data"]').each(function (index) {
//                key = $(this).attr('name');
//                key = key.replace("line_items_data[", "");
//                key = key.replace("]", "");
////                console.log(lineitem_objectdata);
//                $.each(lineitem_objectdata, function (index, value) {
//                    if (value.category_id == category_id && value.item_id == item_id && value.touch_id == touch_id && typeof (value.id) != "undefined" && value.id !== null) {
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
//                    } else if (value.category_id == category_id && value.item_id == item_id && value.touch_id == touch_id) {
//                        if(sell_index !== index){
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
                var type_data = $('#sell_type_id').select2('data');
                lineitem['type_name'] = type_data[0].text;
                
                <?php if($use_category == '1') { ?>
                    var item_data = $('#category_id').select2('data');
                    lineitem['category_name'] = item_data[0].text;
                <?php } ?>
                var item_data = $('#item_id').select2('data');
                lineitem['item_name'] = item_data[0].text;
                lineitem['total_grwt_sell'] = $('#total_grwt_sell').val();
                if (sell_type_id != '3') {
                    if($('#tunch_textbox').prop("checked") == true){
                        lineitem['touch_id'] = $('#touch_data_id').val();
                    } else {
        //                var touch_data = $('#touch_id').val();
                        lineitem['touch_id'] = $('#touch_id').val();
                    }
                }
                $.ajax({
                    url: "<?php echo base_url('sell/get_category_group'); ?>/" + item_id,
                    type: "GET",
                    contentType: "application/json",
                    data: "",
                    success: function(response){
                        var json = $.parseJSON(response);
    //                    console.log(json);

                        lineitem['group_name'] = json;
                        if(lineitem['group_name'] == 1){
                            lineitem['grwt'] = round(lineitem['grwt'], 2).toFixed(3);
                            lineitem['less'] = round(lineitem['less'], 2).toFixed(3);
                            lineitem['net_wt'] = round(lineitem['net_wt'], 2).toFixed(3);
                            lineitem['fine'] = round(lineitem['fine'], 2).toFixed(3);
                        } else {
                            lineitem['grwt'] = round(lineitem['grwt'], 1).toFixed(3);
                            lineitem['less'] = round(lineitem['less'], 1).toFixed(3);
                            lineitem['net_wt'] = round(lineitem['net_wt'], 1).toFixed(3);
                            lineitem['fine'] = round(lineitem['fine'], 1).toFixed(3);
                        }

                        if (sell_type_id == '1') {
                            var sell_item_id = $("#sell_item_id").val();
                            var process_id = $("#process_id").val();
                            if($('#tunch_textbox').prop("checked") == true){
                                var touch_id = $("#touch_data_id").val();
                            } else {
                                var touch_id = $("#touch_id").val();
                            }
                            $.ajax({
                                url: "<?php echo base_url('sell/get_item_stock'); ?>/",
                                type: 'POST',
                                async: false,
                                data: {process_id : process_id, category_id : category_id, item_id : item_id, touch_id : touch_id, sell_item_id : sell_item_id},
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
                                    $("#tunch_textbox").prop("checked", true).trigger('change');
                                    if(sell_purchase == "sell") {
                                        setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_SELL_ID) ?>");
                                    } else if(sell_purchase == "purchase") {
                                        setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_PURCHASE_ID) ?>");
                                    } else {
                                        $('#sell_type_id').val(null).trigger("change");    
                                    }
                                    $("#category_id").val(null).trigger("change");
                                    $("#item_id").val(null).trigger("change");
                                    $("#grwt").val('');
                                    $("#less").val('');
                                    $('#less').removeAttr('readonly', 'readonly');
                                    $('#less_ad_details').removeClass('hide');
                                    $("#net_wt").val('');
                                    $("#touch_id").val(null).trigger("change");
                                    $("#touch_data_id").val('');
                                    <?php if($wstg_2 == '1') { ?>
                                        $("#wstg").val('');
                                    <?php } else { ?>
                                        $("#wstg").val('0');
                                    <?php } ?>
                                    $("#defult_wstg").val('');
                                    $("#fine").val('');
                                    $("#charges_amt").val('');
                                    $("#image").val('');
                                    $("#file_upload").val('');
                                    $("#line_items_index").val('');
                                    $('#sell_type_id').removeAttr('disabled','disabled');
                                    $('#category_id').removeAttr('disabled','disabled');
                                    $('#item_id').removeAttr('disabled','disabled');
                                    $('.touch_id').removeAttr('disabled','disabled');
                                    $("#tunch_textbox").removeAttr('disabled', 'disabled');
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
                            sell_index = '';
                            $("#tunch_textbox").prop("checked", true).trigger('change');
                            if(sell_purchase == "sell") {
                                setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_SELL_ID) ?>");
                            } else if(sell_purchase == "purchase") {
                                setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_PURCHASE_ID) ?>");
                            } else {
                                $('#sell_type_id').val(null).trigger("change");    
                            }
                            
                            $("#category_id").val(null).trigger("change");
                            $("#item_id").val(null).trigger("change");
                            $("#grwt").val('');
                            $("#less").val('');
                            $('#less').removeAttr('readonly', 'readonly');
                            $('#less_ad_details').removeClass('hide');
                            $("#net_wt").val('');
                            $("#touch_id").val(null).trigger("change");
                            $("#touch_data_id").val('');
                            <?php if($wstg_2 == '1') { ?>
                                $("#wstg").val('');
                            <?php } else { ?>
                                $("#wstg").val('0');
                            <?php } ?>
                            $("#fine").val('');
                            $("#charges_amt").val('');
                            $("#image").val('');
                            $("#file_upload").val('');
                            $("#line_items_index").val('');
                            $('#sell_type_id').removeAttr('disabled','disabled');
                            $("#tunch_textbox").removeAttr('disabled', 'disabled');
                            $('#category_id').removeAttr('disabled','disabled');
                            $('#item_id').removeAttr('disabled','disabled');
                            $('.touch_id').removeAttr('disabled','disabled');
                        }
                        $('#total_grwt_sell').val('');
                    }
                });
            }
            $(".item_fields_div").css("background-color", "#ffffff");
            $("#add_lineitem").removeAttr('disabled', 'disabled');
            if($.trim($('#rfid_number').val()) == ''){
                $('#sell_type_id').select2('open');
            }
            $("#item_stock_rfid_id").val('');
            $("#rfid_number").val('');
            $('#grwt').removeAttr('disabled','disabled');
            $('#rfid_number').removeAttr('disabled','disabled');
        });
    
        if(sell_purchase == "sell") {
            setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_SELL_ID) ?>");
        } else if(sell_purchase == "purchase") {
            setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/'.SELL_TYPE_PURCHASE_ID) ?>");
        }
        
        $("input.pts_selected_index:checked").click(function(){
            $("input.pts_selected_index").prop("checked", false);
        });

        $(document).on('keypress', '#rfid_number', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                var process_id = $.trim($("#process_id").val());
                if ( process_id == '') {
                    show_notify('Please Select Department.', false);
                    $("#process_id").select2('open');
                    return false;
                }
                $('#ajax-loader').show();
                var item_stock_rfid_id = $('#item_stock_rfid_id').val();
                var rfid_number = $('#rfid_number').val();
                var account_id = $('#account_id').val();
                $.ajax({
                    url: "<?php echo base_url('sell/get_lineitem_based_on_rfid'); ?>/",
                    type: 'POST',
                    async: false,
                    data: {department_id: process_id, item_stock_rfid_id : item_stock_rfid_id, rfid_number : rfid_number, account_id : account_id},
                    success: function (response) {
                        $('#ajax-loader').hide();
                        var json = $.parseJSON(response);
                        $('#rfid_number').val('');
                        if(json.rfid_used == '1'){
                            show_notify(json.rfid_used_msg, false);
                            return false;
                        }
                        if(json.type){
                            $("#sell_type_id").val(json.type).trigger("change");
                            setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/') ?>" + json.type);
                            <?php if($use_category == '1') { ?>
                                $("#category_id").val(json.category_id).trigger("change");
                                setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/') ?>" + json.category_id);
                            <?php } else { ?>
                                $("#category_id").val(json.category_id);
                            <?php } ?>
                            $("#item_id").val(json.item_id).trigger("change");
                            setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + json.item_id);
                            $("#grwt").val(json.grwt);
                            $("#less").val(json.less);
                            $("#net_wt").val(json.net_wt);
                            $(".touch_id").val(json.touch_id).trigger("change");
                            $(".wstg").val(json.wstg);
                            $("#fine").val(json.fine);
                            $("#charges_amt").val(json.charges_amt);
                            $("#item_stock_rfid_id").val(json.item_stock_rfid_id);
                            $("#rfid_number").val(json.rfid_number);
                            $("#image").val('');
                            $("#file_upload").val('');
                            $('#add_lineitem').click();
                            $('#item_stock_rfid_id').val('');
                        } else {
                            var process_data = 'Default';
                            <?php if($department_2 == '1') { ?>
                                var process_data = $('#process_id').select2('data');
                            <?php } ?>
                            show_notify('RFID Not Found In '+ process_data[0].text +' Department!', false);
                        }
                        $('#rfid_number').focus();
                    }
                });
                e.preventDefault();
                return false;
            }
        });

        shortcut.add("f1",function(event) {
            event.preventDefault();
            $('#payment_receipt').click();
        });
        shortcut.add("f2",function(event) {
            event.preventDefault();
            $('#metal_receipt_payment').click();
        });
        shortcut.add("f3",function(event) {
            event.preventDefault();
            $('#gold_bhav').click();
        });
        shortcut.add("f4",function(event) {
            event.preventDefault();
            $('#silver_bhav').click();
        });
        shortcut.add("f5",function(event) {
            event.preventDefault();
            $('#transfer').click();
        });
        shortcut.add("f6",function(event) {
            event.preventDefault();
            $("#sell_type_id").select2('open');
        });
        shortcut.add("f7",function(event) {
            event.preventDefault();
            $("#ad_charges").click();
        });
        shortcut.add("f8",function(event) {
            event.preventDefault();
            $("#less_ad_details").click();
        });
    });
    
    function display_pts_lineitem_html(pts_lineitem_objectdata){
        var pts_lineitem_html = '';
        var pts_item_delete = 'allow';
        var pts_total_grwt = 0;
        var pts_total_less = 0;
        var pts_total_ntwt = 0;
        var pts_total_average = 0;
        var pts_total_fine = 0;
        //console.log(lineitem_objectdata);
        $.each(pts_lineitem_objectdata, function (index, value) {
            var row_html_order = '<tr class="lineitem_index_' + index + ' _' + index + '">';
                if(lineitem_objectdata.length !== 0){
                    var row_added = 0;
                    $.each(lineitem_objectdata, function (li_index, li_value) {
                        if(li_value.purchase_sell_item_id == value.purchase_sell_item_id && li_value.stock_type == value.stock_type){
                            if(row_added == 0){ 
                                row_added = 1;
                                pts_lineitem_objectdata[index].sell_item_id = li_value.sell_item_id;
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
                                pts_item_delete = pts_item_delete + parseFloat(li_value.pts_item_delete);
                                pts_total_grwt = pts_total_grwt + parseFloat(li_value.grwt);
                                pts_total_less = pts_total_less + parseFloat(li_value.less);
                                pts_total_ntwt = pts_total_ntwt + parseFloat(li_value.net_wt);
                                pts_total_fine = pts_total_fine + parseFloat(li_value.fine);
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
                        pts_item_delete = pts_item_delete + parseFloat(value.pts_item_delete);
                        pts_total_grwt = pts_total_grwt + parseFloat(value.grwt);
                        pts_total_less = pts_total_less + parseFloat(value.less);
                        pts_total_ntwt = pts_total_ntwt + parseFloat(value.net_wt);
                        pts_total_fine = pts_total_fine + parseFloat(value.fine);
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
                    pts_item_delete = pts_item_delete + parseFloat(value.pts_item_delete);
                    pts_total_grwt = pts_total_grwt + parseFloat(value.grwt);
                    pts_total_less = pts_total_less + parseFloat(value.less);
                    pts_total_ntwt = pts_total_ntwt + parseFloat(value.net_wt);
                    pts_total_fine = pts_total_fine + parseFloat(value.fine);
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
    
    function get_bill_balance(account_id){
        if(account_id != '' && account_id != null){
            $.ajax({
                url: "<?= base_url('sell/get_account_old_balance') ?>/" + account_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    <?php if(isset($sell_data) && !empty($sell_data)){ ?>
                        var sell_data_total_gold_fine = <?php echo $sell_data->total_gold_fine; ?>;
                        var sell_data_total_silver_fine = <?php echo $sell_data->total_silver_fine; ?>;
                        var sell_data_total_amount = <?php echo $sell_data->total_amount; ?>;
                        var gold_fine = parseFloat(json.gold_fine) - parseFloat(sell_data_total_gold_fine);
                        var silver_fine = parseFloat(json.silver_fine) - parseFloat(sell_data_total_silver_fine);
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
                    $('#old_gold_fine').html(gold_fine);
                    $('#old_silver_fine').html(silver_fine);
                    $('#balance_date').html('<a href="<?php echo base_url(); ?>reports/customer_ledger?account_id=' + account_id + '" target="_blank">' + json.balance_date + '</a>');
                    credit_limit = json.credit_limit;
                    display_lineitem_html(lineitem_objectdata);
                }
            });
        }
    }

    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        var total_gold_fine = 0;
        var total_silver_fine = 0;
        var total_grwt = 0;
        var total_less = 0;
        var total_ntwt = 0;
        
        var total_rfid_pcs = 0;
        var total_loose_pcs = 0;
        var total_rfid_wt = 0;
        var total_loose_wt = 0;
        
        // If any one Lineitem is added then Department not allow to change
        if($.isEmptyObject(lineitem_objectdata) && $.isEmptyObject(pay_rec_objectdata)
            && $.isEmptyObject(metal_objectdata) && $.isEmptyObject(gold_objectdata)
            && $.isEmptyObject(silver_objectdata) && $.isEmptyObject(transfer_objectdata) 
            && $.isEmptyObject(ad_charges_objectdata)){
                $('#process_id').removeAttr('disabled','disabled');
                $('#after_disabled_process_id').remove();
        } else {
            var process_id = $('#process_id').val();
            $('#process_id').attr('disabled','disabled');
            $('#process_id').closest('div').append('<input type="hidden" name="process_id" id="after_disabled_process_id" value="' + process_id + '" />');
        }
        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            if(value.type_name == 'S'){
                if(value.group_name == 1){
                    total_gold_fine = total_gold_fine + parseFloat(value.fine);
                    var gold_value = parseFloat(value.fine);
                    var silver_value = '';
                    var amount = '';
                } else if(value.group_name == 2){
                    total_silver_fine = total_silver_fine + parseFloat(value.fine);
                    var gold_value = '';
                    var silver_value = parseFloat(value.fine);
                    var amount = '';
                } 
                total_grwt = total_grwt + parseFloat(value.grwt || 0);
                total_less = total_less + parseFloat(value.less || 0);
                total_ntwt = total_ntwt + parseFloat(value.net_wt || 0);
                var less = value.less;
                var grwt = value.grwt;
                var net_wt = value.net_wt;
                var touch_id = value.touch_id;
                var wstg = value.wstg;
//                var operater = '';
            } else if(value.type_name == 'P'){
                if(value.group_name == 1){
                    total_gold_fine = total_gold_fine - parseFloat(value.fine);
                    var gold_value = zero_value - parseFloat(value.fine);
                    var silver_value = '';
                    var amount = '';
                } else if(value.group_name == 2){
                    total_silver_fine = total_silver_fine - parseFloat(value.fine);
                    var gold_value = '';
                    var silver_value = zero_value - parseFloat(value.fine);
                    var amount = '';
                } 
                total_grwt = total_grwt - parseFloat(value.grwt || 0);
                total_less = total_less - parseFloat(value.less || 0);
                total_ntwt = total_ntwt - parseFloat(value.net_wt || 0);
                var less = (value.less != '') ? 0 - parseFloat(value.less) : value.less;
                var grwt = zero_value - parseFloat(value.grwt);
                var net_wt = zero_value - parseFloat(value.net_wt);
                var touch_id = zero_value - parseFloat(value.touch_id);
                var wstg = zero_value - parseFloat(value.wstg);
//                var operater = '-';
            } else {
                if(value.group_name == 1){
                    total_gold_fine = total_gold_fine - parseFloat(value.fine);
                    var gold_value = zero_value - parseFloat(value.fine);
                    var silver_value = '';
                    var amount = '';
                } else if(value.group_name == 2){
                    total_silver_fine = total_silver_fine - parseFloat(value.fine);
                    var gold_value = '';
                    var silver_value = zero_value - parseFloat(value.fine);
                    var amount = '';
                } 
                total_grwt = total_grwt - parseFloat(value.grwt || 0);
                total_less = total_less - parseFloat(value.less || 0);
                total_ntwt = total_ntwt - parseFloat(value.net_wt || 0);
                var less = (value.less != '') ? 0 - parseFloat(value.less) : value.less;
                var grwt = zero_value - parseFloat(value.grwt);
                var net_wt = zero_value - parseFloat(value.net_wt);
                var touch_id = zero_value - parseFloat(value.touch_id);
                var wstg = zero_value - parseFloat(value.wstg || 0);
//                var operater = '-';
            }
            if(value.charges_amt){
                amount = parseFloat(value.charges_amt);
            } else {
                var amount = '';
            }
            <?php if (isset($sell_item)) { ?>
                if(edit_lineitem_inc == '0'){
                    total_amount = total_amount + parseFloat(value.charges_amt);
                }
            <?php } ?>
            lineitem_edit_btn = ''; //'<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.sell_item_delete == 'allow'){
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_sp_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>';
            <?php /*row_html += '<td>' + value.type_name + '</td>';
            <?php if($use_category == '1') { ?>
                row_html += '<td>' + value.category_name + '</td>';
            <?php } */?>
            row_html += '<td>' + value.item_name + '</td>' +
                    '<td class="text-right">' + value.hsn_code + '</td>' +
                    '<td class="text-right">' + parseFloat(grwt).toFixed(3) + '</td>';
            <?php if($less_netwt_2 == '1') { ?>
                <?php if (isset($order_lot_item)) { ?>
                    row_html += '<td class="text-right">' + parseFloat(value.less).toFixed(3) + '</td>';
                <?php } else { ?>
                    row_html += '<td class="text-right">' + parseFloat(less).toFixed(3) + '</td>';
                <?php } ?>
                row_html += '<td class="text-right">' + parseFloat(net_wt).toFixed(3) + '</td>';
            <?php } ?>
            <?php /*row_html += '<td class="text-right">' + touch_id + '</td>';
            <?php if($wstg_2 == '1') { ?>
                row_html += '<td class="text-right">' + wstg + '</td>';
            <?php } ?>
            row_html += '<td class="text-right">' + parseFloat(gold_value || 0).toFixed(3) + '</td>'+
                    '<td class="text-right">' + parseFloat(silver_value || 0).toFixed(3) + '</td>'+
                    '<td class="text-right">' + amount + '</td>'+
                    '</td></td>' ;
            <?php if($lineitem_image_2 == '1') { ?>
                if (value.image !== null && value.image !== '') {
                    var value_image = value.image;
                    if (value.order_lot_item_id && value_image.indexOf('uploads/order_item_photo') != -1) {
                        var img_url = '<?php echo base_url(); ?>' + '/' + value.image;
                    } else {
                        var img_url = '<?php echo base_url(); ?>' + 'uploads/sell_item_photo/' + value.image;
                    }
                    row_html += '<td><a href="javascript:void(0)" class="btn btn-xs btn-primary image_model" data-img_src="' + img_url + '" ><i class="fa fa-image"></i></a></td>';
                }
            <?php } */?>
            row_html += '<td class="text-right">' + value.rate_per_1_gram + '</td>';
            row_html += '<td class="text-right">' + value.gross_amount + '</td>';
            row_html += '<td class="text-right">' + value.gst_rate + '</td>';
            row_html += '<td class="text-right">' + value.labout_other_charges + '</td>';
            row_html += '<td class="text-right">' + value.amount + '</td>';
            new_lineitem_html += row_html;
            
            if(value.rfid_number != '' && value.rfid_number != null){
                total_rfid_pcs++;
                total_rfid_wt = parseFloat(total_rfid_wt) + parseFloat(grwt);
            } else {
                total_loose_pcs++;
                total_loose_wt = parseFloat(total_loose_wt) + parseFloat(grwt);
            }
            
        });
        <?php if (isset($sell_item)) { ?>
            edit_lineitem_inc = 1;
        <?php } ?>
        $('tbody#lineitem_list').html(new_lineitem_html);
        var total_gold_count = parseFloat(total_gold_fine) + parseFloat(gold_count) + parseFloat(transfer_gold_total) + parseFloat(metal_gold_total);
        var total_gold_count = round(total_gold_count, 2).toFixed(3);
        var nwt_gold_count = parseFloat(old_gold_fine_val) + parseFloat(total_gold_count);
        var nwt_gold_count = round(nwt_gold_count, 2).toFixed(3);
//        alert(nwt_gold_count);
        var total_silver_count = parseFloat(total_silver_fine) + parseFloat(silver_count) + parseFloat(transfer_silver_total) + parseFloat(metal_silver_total);
        var total_silver_count = round(total_silver_count, 1).toFixed(3);
        var nwt_silver_count = parseFloat(old_silver_fine_val) + parseFloat(total_silver_count);
        var nwt_silver_count = round(nwt_silver_count, 1).toFixed(3);
        
        var nwt_amount_count = parseInt(old_amount_val) + parseInt(total_amount);
        
        var depart_gold_fine = parseFloat(total_gold_fine) + parseFloat(metal_gold_total);
        var depart_silver_fine = parseFloat(total_silver_fine) + parseFloat(metal_silver_total);
        
        $('#total_gold_fine').html(round(total_gold_fine, 2).toFixed(3));
        $('#total_silver_fine').html(round(total_silver_fine, 1).toFixed(3));
        $('#total_grwt').html(round(total_grwt, 2).toFixed(3));
        $('#total_less').html(round(total_less, 2).toFixed(3));
        $('#total_ntwt').html(round(total_ntwt, 2).toFixed(3));
        $('#bill_gold_fine').html(total_gold_count);
        $('#bill_silver_fine').html(total_silver_count);
        $('#bill_amount').html(total_amount);
        $('.net_gold_fine').html(nwt_gold_count);
        $('.net_silver_fine').html(nwt_silver_count);
        $('.net_amount').html(nwt_amount_count);
        $('#gold_fine_total').val(nwt_gold_count);
        $('#silver_fine_total').val(nwt_silver_count);
        $('#amount_total').val(nwt_amount_count);
        
        $('#total_rfid_pcs').html(total_rfid_pcs);
        $('#total_rfid_wt').html(round(total_rfid_wt, 2).toFixed(3));
        $('#total_loose_pcs').html(total_loose_pcs);
        $('#total_loose_wt').html(round(total_loose_wt, 2).toFixed(3));
        
        var app_gold_fine = (<?php echo $gold_rate; ?> / 10 ) * (nwt_gold_count || 0);
        var app_silver_fine = (<?php echo $silver_rate; ?> / 10 ) * (nwt_silver_count || 0);
        app_net_amt = app_gold_fine + app_silver_fine + (nwt_amount_count || 0);
        $('#app_gold_fine').html(app_gold_fine.toFixed(0));
        $('#app_silver_fine').html(app_silver_fine.toFixed(0));
        $('#app_amount').html(nwt_amount_count);
        $('#app_net_amt').html(app_net_amt.toFixed(0));
        if(app_net_amt > credit_limit){
            $("#app_net_amt").css("color", "#fa0812");
        } else {
            $("#app_net_amt").css("color", "#000000");
        }
        $('#save_sell_purchase').append('<input type="hidden" name="app_net_amt" id="app_net_amt" value="' + app_net_amt + '" />');
        $('#save_sell_purchase').append('<input type="hidden" name="sell_gold_fine" id="sell_gold_fine" value="' + total_gold_count + '" />');
        $('#save_sell_purchase').append('<input type="hidden" name="sell_silver_fine" id="sell_silver_fine" value="' + total_silver_count + '" />');
        $('#save_sell_purchase').append('<input type="hidden" name="sell_amount" id="sell_amount" value="' + total_amount + '" />');
        $('#save_sell_purchase').append('<input type="hidden" name="depart_gold_fine" id="depart_gold_fine" value="' + depart_gold_fine + '" />');
        $('#save_sell_purchase').append('<input type="hidden" name="depart_silver_fine" id="depart_silver_fine" value="' + depart_silver_fine + '" />');
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_sp_item").addClass('hide');
        sell_index = index;
        var value = lineitem_objectdata[index];
//        console.log(value);
        if(value.tunch_textbox == 1){
            $("#tunch_textbox").prop("checked", true);
        } else {
            $("#tunch_textbox").prop("checked", false);
        }
        $("#sell_type_id").val(value.type).trigger("change");
        setSelect2Value($("#sell_type_id"), "<?= base_url('app/set_sell_type_select2_val_by_id/') ?>" + value.type);
        <?php if($use_category == '1') { ?>
            $("#category_id").val(value.category_id).trigger("change");
            setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/') ?>" + value.category_id);
        <?php } else { ?>
            $("#category_id").val(value.category_id);
        <?php } ?>
        $("#item_id").val(value.item_id).trigger("change");
        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + value.item_id);
        if(value.purchase_sell_item_id == '' || value.purchase_sell_item_id == null){
            $("#line_items_index").val(index);
            if (typeof (value.sell_item_id) != "undefined" && value.sell_item_id !== null) {
                $("#sell_item_id").val(value.sell_item_id);
            }
            $("#sell_purchase_id").val(value.sell_item_id);
            $("#order_lot_item_id").val(value.order_lot_item_id);
            $("#purchase_sell_item_id").val(value.purchase_sell_item_id);
            $("#stock_type").val(value.stock_type);
            $("#grwt").val(value.grwt);
            $("#less").val(value.less);
            $("#net_wt").val(value.net_wt);
            $(".touch_id").val(value.touch_id).trigger("change");
            $(".wstg").val(value.wstg);
            $("#fine").val(value.fine);
            $("#item_stock_rfid_id").val(value.item_stock_rfid_id);
            $("#rfid_number").val(value.rfid_number);
            $("#charges_amt").val(value.charges_amt);
            $("#image").val(value.image);
            $("#file_upload").val('');
            $('#total_grwt_sell').val(value.total_grwt_sell);
        } else {
            $("#item_id").val(null).trigger("change");
        }
        if(value.sell_item_delete == 'not_allow'){
            $('#sell_type_id').attr('disabled','disabled');
            $('#category_id').attr('disabled','disabled');
            $('#item_id').attr('disabled','disabled');
            $('.touch_id').attr('disabled','disabled');
            $('#tunch_textbox').attr('disabled','disabled');
        }
        
        if(value.rfid_number != '' && value.rfid_number != null){
            $('#sell_type_id').attr('disabled','disabled');
            $('#category_id').attr('disabled','disabled');
            $('#item_id').attr('disabled','disabled');
            $('#grwt').attr('disabled','disabled');
            $('.touch_id').attr('disabled','disabled');
            $('#tunch_textbox').attr('disabled','disabled');
            $('#rfid_number').attr('disabled','disabled');
        }
        
        $("#sell_item_delete").val(value.sell_item_delete);
        
        if (typeof (value.sell_less_ad_details) != "undefined" && value.sell_less_ad_details !== null) {
            value_sell_less_ad_details = value.sell_less_ad_details;
            if(value_sell_less_ad_details != ''){
                less_ad_details_objectdata = JSON.parse(value_sell_less_ad_details);
            }
            display_less_ad_details_html(less_ad_details_objectdata);
        }
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        value = lineitem_objectdata[index];
        if (confirm('Are you sure ?')) {
            if (typeof (value.sell_item_id) != "undefined" && value.sell_item_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_sell_item_id[]" id="deleted_sell_item_id" value="' + value.sell_item_id + '" />');
            }
            total_amount = parseFloat(total_amount) - parseFloat(value.charges_amt);
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
            if(edit_pay_rec_inc == '0'){
                if(value.payment_receipt == '1'){
                    total_amount = total_amount + parseInt(value.amount);
                } else if(value.payment_receipt == '2') {
                    total_amount = total_amount - parseInt(value.amount);
                }
            }
            <?php } ?>
            if(value.payment_receipt == '1'){
                var payment_receipt = 'Payment';
                var value_amount = value.amount;
            } else { 
                var payment_receipt = 'Receipt';
                var value_amount = zero_value - value.amount;
            }
            if(value.cash_cheque == '1'){
                var cash_cheque = 'Cash';
            } else { 
                var cash_cheque = 'Cheque';
            }
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_pay_rec' + index + '" href="javascript:void(0);" onclick="edit_pay_rec(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_pr_item" href="javascript:void(0);" onclick="remove_pay_rec(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="pay_rec_index_' + index + '"><td class=""  width="5%">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td width="4%"></td>' +
                    '<td width="10%"> Payment Receipt </td>' +
                    '<td width="8%">' + payment_receipt + ' @ '+ cash_cheque +'</td>' +
                    '<td width="5%">' + value.bank_name + '</td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="4%"></td>' +
                    '<td width="7%"></td>' +
                    '<td width="7%" class="text-right"></td>' +
                    '<td width="6%" class="text-right">' + value_amount + '</td>' + 
                    '<td width="5%"></td></tr>';
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
        $(".delete_pr_item").addClass('hide');
        var value = pay_rec_objectdata[index];
        
        $("#pay_rec_index").val(index);
        if (typeof (value.pay_rec_id) != "undefined" && value.pay_rec_id !== null) {
            $("#pay_rec_id").val(value.pay_rec_id);
        }
        $("input[name=payment_receipt][value='" + value.payment_receipt + "']").prop("checked",true);
        $("#cash_cheque").val(value.cash_cheque).trigger("change");
        $("#bank_id").val(value.bank_id).trigger("change");
        setSelect2Value($("#bank_id"), "<?= base_url('app/set_account_bank_select2_val_by_id/') ?>" + value.bank_id);
        $("#amount").val(value.amount);
        $("#narration").val(value.narration);
        $('#ajax-loader').hide();
    }

    function remove_pay_rec(index) {
        if (confirm('Are you sure ?')) {
            var value = pay_rec_objectdata[index];
            if (typeof (value.pay_rec_id) != "undefined" && value.pay_rec_id !== null) {
                $('.pay_rec_form').append('<input type="hidden" name="deleted_pay_rec_id[]" id="deleted_pay_rec_id" value="' + value.pay_rec_id + '" />');
            }
            if(value.payment_receipt == '1'){
                total_amount = total_amount - parseFloat(value.amount);
            } else if(value.payment_receipt == '2') {
                total_amount = total_amount + parseFloat(value.amount);
            }
            $('#payment_receipt_id').val('1');
            pay_rec_objectdata.splice(index, 1);
            display_pay_rec_html(pay_rec_objectdata);
        }
    }
    
    function display_metal_html(metal_objectdata) {
        $('#ajax-loader').show();
        var new_metal_html = '';
        var metal_gold_total_fine = 0;
        var metal_silver_total_fine = 0;
        var metal_other_total_fine = 0;
        console.log(metal_objectdata);
        $.each(metal_objectdata, function (index, value) {
            <?php if (isset($metal_data)) { ?>
                if(edit_metal_inc == '0'){
                    if(value.metal_payment_receipt == '1'){
                        if(value.group_name == 1){
                            metal_gold_total = metal_gold_total + parseFloat(value.metal_fine);
                        } else if(value.group_name == 2){
                            metal_silver_total = metal_silver_total + parseFloat(value.metal_fine);
                        }
                        var metal_grwt = value.metal_grwt;
                        var metal_tunch = value.metal_tunch;
//                        var operater = '';
                    } else if(value.metal_payment_receipt == '2'){
                        if(value.group_name == 1){
                            metal_gold_total = metal_gold_total - parseFloat(value.metal_fine);
                        } else if(value.group_name == 2){
                            metal_silver_total = metal_silver_total - parseFloat(value.metal_fine);
                        }
                        var metal_grwt = zero_value - parseFloat(value.metal_grwt);
                        var metal_tunch = zero_value - parseFloat(value.metal_tunch);
//                        var operater = '-';
                    }
                }
            <?php } ?>
            if(value.metal_payment_receipt == '1'){
                if(value.group_name == 1){
                    metal_gold_total_fine = metal_gold_total_fine + parseFloat(value.metal_fine);
                } else if(value.group_name == 2){
                    metal_silver_total_fine = metal_silver_total_fine + parseFloat(value.metal_fine);
                } else {
                    metal_other_total_fine = metal_other_total_fine + parseFloat(value.metal_fine);
                }
                var metal_grwt = value.metal_grwt;
                var metal_tunch = value.metal_tunch;
//                var operater = '';
            } else if(value.metal_payment_receipt == '2'){
                if(value.group_name == 1){
                    metal_gold_total_fine = metal_gold_total_fine - parseFloat(value.metal_fine);
                } else if(value.group_name == 2){
                    metal_silver_total_fine = metal_silver_total_fine - parseFloat(value.metal_fine);
                } else {
                    metal_other_total_fine = metal_other_total_fine - parseFloat(value.metal_fine);
                }
                var metal_grwt = zero_value - parseFloat(value.metal_grwt);
                var metal_tunch = zero_value - parseFloat(value.metal_tunch);
//                var operater = '-';
            }
            if(value.metal_payment_receipt == '1'){
                var metal_pay_type = 'Payment';
            } else { 
                var metal_pay_type = 'Receipt';
            }
            if(value.group_name == 1){
                if(value.metal_payment_receipt == '2'){
                    var gold_operater =  zero_value - parseFloat(value.metal_fine);
//                    var gold_operater = '-';
                    var silver_operater = value.metal_fine;
                } else {
                    var gold_operater = value.metal_fine;
                    var silver_operater = value.metal_fine;
                }
                var gold_value = round(parseFloat(gold_operater), 2).toFixed(3);
                var silver_value = '';
            } else if(value.group_name == 2){
//                var gold_value = '';
//                var silver_value = round(parseFloat(value.metal_fine), 1).toFixed(3);
                if(value.metal_payment_receipt == '2'){
                    var gold_operater = value.metal_fine;
                    var silver_operater = zero_value - parseFloat(value.metal_fine);
                } else {
                    var gold_operater = value.metal_fine;
                    var silver_operater = value.metal_fine;
                }
                var gold_value = '';
                var silver_value = round(parseFloat(silver_operater), 1).toFixed(3);
            }
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
//            var metal_grwt = parseFloat(value.metal_grwt);
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_metal' + index + '" href="javascript:void(0);" onclick="edit_metal(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.metal_item_delete == 'allow'){
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_mpr_item" href="javascript:void(0);" onclick="remove_metal(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var metal_html = '<tr class="metal_index_' + index + '"><td class=""  width="5%">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td width="4%"></td>' +
                    '<td width="10%"> Metal Payment Receipt </td>' +
                    '<td width="8%">' + metal_pay_type + ' @ ' + value.metal_item_name + '</td>' +
                    '<td width="5%" class="text-right">' + parseFloat(metal_grwt).toFixed(3) + '</td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%" class="text-right">' + metal_tunch + '</td>' +
                    '<td width="4%"></td>' +
                    '<td width="7%" class="text-right">' + parseFloat(gold_value || 0).toFixed(3) + '</td>' +  
                    '<td width="7%" class="text-right">' + parseFloat(silver_value || 0).toFixed(3) + '</td>' + 
                    '<td width="6%"></td>' +
                    '<td width="5%"></td></tr>';
            new_metal_html += metal_html;
        });
        <?php if (isset($metal_data)) { ?>
            edit_metal_inc = 1;
        <?php } ?>
        $('tbody#pop_up_metal_list').html(new_metal_html);
        metal_gold_total_fine = round(metal_gold_total_fine, 2).toFixed(3);
        metal_silver_total_fine = round(metal_silver_total_fine, 1).toFixed(3);
        metal_other_total_fine = round(metal_other_total_fine, 2).toFixed(3);
        $('#save_sell_purchase').append('<input type="hidden" name="metal_gold_total_fine" id="metal_gold_total_fine" value="' + metal_gold_total_fine + '" />');
        $('#save_sell_purchase').append('<input type="hidden" name="metal_silver_total_fine" id="metal_silver_total_fine" value="' + metal_silver_total_fine + '" />');
        $('#save_sell_purchase').append('<input type="hidden" name="metal_other_total_fine" id="metal_other_total_fine" value="' + metal_other_total_fine + '" />');
        $('#ajax-loader').hide();
        display_lineitem_html(lineitem_objectdata);
    }

    function edit_metal(index) {
        $('#metal_receipt_payment_model').modal('show');
        $('#ajax-loader').show();
        $(".delete_mpr_item").addClass('hide');
        var value = metal_objectdata[index];
        metal_index = index;
        $("#metal_index").val(index);
        if (typeof (value.id) != "undefined" && value.id !== null) {
            $("#metal_pr_id").val(value.id);
        }
        $("input[name=metal_payment_receipt][value='" + value.metal_payment_receipt + "']").prop("checked",true);
        if(value.metal_item_delete != 'allow'){
            $('input[name=metal_payment_receipt]').attr('disabled', true);
            $('#metal_item_id').attr('disabled', true);
            $('#metal_tunch').attr('readonly', true);
        }
//        $(".metal_payment_receipt").val(value.metal_payment_receipt).attr('checked', 'checked');
        $("#metal_item_id").val(value.metal_item_id).trigger("change");
        setSelect2Value($("#metal_item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + value.metal_item_id);
        $("#metal_grwt").val(value.metal_grwt);
        $("#metal_tunch").val(value.metal_tunch);
        $("#metal_fine").val(value.metal_fine);
        $('#metal_pr_id').val(value.metal_pr_id);
        $('#metal_narration').val(value.metal_narration);
        $('#total_grwt_metal').val(value.total_grwt_metal);    
        $('#metal_item_delete').val(value.metal_item_delete);    
        $('#ajax-loader').hide();
    }

    function remove_metal(index) {
        if (confirm('Are you sure ?')) {
            value = metal_objectdata[index];
//            console.log(value);
            if (typeof (value.metal_pr_id) != "undefined" && value.metal_pr_id !== null) {
                $('.metal_form').append('<input type="hidden" name="deleted_metal_id[]" id="deleted_metal_id" value="' + value.metal_pr_id + '" />');
            }
            if(value.metal_payment_receipt == '1'){
                if(value.group_name == 1){
                    metal_gold_total = metal_gold_total - parseFloat(value.metal_fine);
                } else if(value.group_name == 2){
                    metal_silver_total = metal_silver_total - parseFloat(value.metal_fine);
                }  
            } else if(value.metal_payment_receipt == '2'){
                if(value.group_name == 1){
                    metal_gold_total = metal_gold_total + parseFloat(value.metal_fine);
                } else if(value.group_name == 2){
                    metal_silver_total = metal_silver_total + parseFloat(value.metal_fine);
                } 
            }
            $('#metal_payment_receipt_id').val('1');
            
            metal_objectdata.splice(index, 1);
            display_metal_html(metal_objectdata);
        }
    }
    
    function display_gold_html(gold_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        $.each(gold_objectdata, function (index, value) {
            var gold_weight = parseFloat(value.gold_weight);
            
            <?php if (isset($gold_data)) { ?>
                if(edit_gold_inc == '0'){
                    if(value.gold_sale_purchase == '1') {
                        gold_count = gold_count - gold_weight;
                        total_amount = total_amount + parseInt(value.gold_value);
                    } else if(value.gold_sale_purchase == '2'){
                        gold_count = gold_count + gold_weight;
                        total_amount = total_amount - parseInt(value.gold_value);
                    }
                }
            <?php } ?>
            
            if(value.gold_sale_purchase == '1'){
                var gold_sale_purchase = 'Sell';
                var value_gold_weight = zero_value - parseFloat(gold_weight);
                var value_gold_value = value.gold_value;
//                var gold_weight = '-';
//                var amt_operator = '';
            } else { 
                var gold_sale_purchase = 'Purchase';
                var value_gold_weight = gold_weight;
                var value_gold_value = zero_value - parseInt(value.gold_value);
//                var operator = '';
//                var amt_operator = '-';
            }
            
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_gold' + index + '" class="gold_open_popup" href="javascript:void(0);" onclick="edit_gold(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_g_item" href="javascript:void(0);" onclick="remove_gold(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="gold_index_' + index + '"><td class="" width="5%">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +   
                    '</td>' +
                    '<td width="4%"></td>' +
                    '<td width="10%"> Gold Bhav </td>' +
                    '<td width="8%">' + gold_sale_purchase + ' @ '+ value.gold_rate +'</td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="4%"></td>' +
                    '<td width="7%" class="text-right">' + parseFloat(value_gold_weight).toFixed(3) + '</td>' +
                    '<td width="7%"></td>' +
                    '<td width="6%" class="text-right">' + value_gold_value + '</td>' +
                    '<td width="5%"></td></tr>';
            new_lineitem_html += row_html;
//            console.log(new_lineitem_html);
        });
        <?php if (isset($gold_data)) { ?>
            edit_gold_inc = 1;
        <?php } ?>
        $('tbody#pop_up_gold_list').html(new_lineitem_html);
//        $('tfoot#lineitem_foot_list').append(new_lineitem_html);
        $('#ajax-loader').hide();
        display_lineitem_html(lineitem_objectdata);
    }

    function edit_gold(index) {
        $('#gold_bhav_model').modal('show');
        $('#ajax-loader').show();
        $(".delete_g_item").addClass('hide');
        gold_array_for_edit = [];
        var value = gold_objectdata[index];
//        console.log(gold_array_for_edit);
        gold_array_for_edit.push(value);

        $("#gold_index").val(index);
        if (typeof (value.gold_id) != "undefined" && value.gold_id !== null) {
            $("#gold_id").val(value.gold_id);
        }
        $("input[name=gold_sale_purchase][value='" + value.gold_sale_purchase + "']").prop("checked",true);
        $("#gold_weight").val(value.gold_weight);
        $("#gold_rate").val(value.gold_rate);
        $("#gold_value").val(value.gold_value);
        $("#gold_narration").val(value.gold_narration);
        $('#ajax-loader').hide();
    }
    
    var deletd_gold_id = 0;
    function remove_gold(index) {
        if (confirm('Are you sure ?')) {
            var value = gold_objectdata[index];
            if (typeof (value.gold_id) != "undefined" && value.gold_id !== null) {
                $('.gold_form').append('<input type="hidden" name="deleted_gold_id[]" id="deleted_gold_id" value="' + value.gold_id + '" />');
            }
            <?php if(isset($gold_data)){ ?>
                deletd_gold_id = 1;
            <?php } ?>
            if(value.gold_sale_purchase == '1'){
                gold_count = gold_count + parseFloat(value.gold_weight);
                total_amount = total_amount - parseFloat(value.gold_value);
            } else if(value.gold_sale_purchase == '2') {
                gold_count = gold_count - parseFloat(value.gold_weight);
                total_amount = total_amount + parseFloat(value.gold_value);
            }
            $('#gold_bhav_id').val('1');
            
            gold_objectdata.splice(index, 1);
            display_gold_html(gold_objectdata);
        }
    }
    
    function display_silver_html(silver_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        $.each(silver_objectdata, function (index, value) {
            var silver_weight = parseFloat(value.silver_weight);
            <?php if (isset($silver_data)) { ?>
                if(edit_silver_inc == '0'){
                    if(value.silver_sale_purchase == '2'){
                        silver_count = silver_count + silver_weight;
                        total_amount = total_amount - parseInt(value.silver_value);
                    } else if(value.silver_sale_purchase == '1') {
                        silver_count = silver_count - silver_weight;
                        total_amount = total_amount + parseInt(value.silver_value);
                    }
                }
            <?php } ?>
            if(value.silver_sale_purchase == '1'){
                var silver_sale_purchase = 'Sell';
                var value_silver_weight = zero_value - parseFloat(silver_weight);
                var value_silver_value = value.silver_value;
//                var operator = '-';
//                var amt_operator = '';
            } else { 
                var silver_sale_purchase = 'Purchase';
                var value_silver_weight = silver_weight;
                var value_silver_value = zero_value - parseInt(value.silver_value);
//                var operator = '';
//                var amt_operator = '-';
            }
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_silver' + index + '" href="javascript:void(0);" onclick="edit_silver(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_s_item" href="javascript:void(0);" onclick="remove_silver(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="silver_index_' + index + '"><td class="" width="5%">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td width="4%"></td>' +
                    '<td width="10%"> Silver Bhav </td>' +
                    '<td width="8%">' + silver_sale_purchase + ' @ '+ value.silver_rate +'</td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="4%"></td>' +
                    '<td width="7%"></td>' +
                    '<td width="7%" class="text-right">' + parseFloat(value_silver_weight).toFixed(3) + '</td>' +
                    '<td width="6%" class="text-right">' + value_silver_value + '</td>' +
                    '<td width="5%"></td></tr>';
            new_lineitem_html += row_html;
//            console.log(new_lineitem_html);
        });
        <?php if (isset($silver_data)) { ?>
            edit_silver_inc = 1;
        <?php } ?>
        $('tbody#pop_up_silver_list').html(new_lineitem_html);
//        $('tfoot#lineitem_foot_list').append(new_lineitem_html);
        $('#ajax-loader').hide();
        display_lineitem_html(lineitem_objectdata);
    }

    function edit_silver(index) {
        $('#silver_bhav_model').modal('show');
        $('#ajax-loader').show();
        $(".delete_s_item").addClass('hide');
        var value = silver_objectdata[index];
        silver_array_for_edit.push(value);
        
        $("#silver_index").val(index);
        if (typeof (value.silver_id) != "undefined" && value.silver_id !== null) {
            $("#silver_id").val(value.silver_id);
        }
        $("input[name=silver_sale_purchase][value='" + value.silver_sale_purchase + "']").prop("checked",true);
        $("#silver_weight").val(value.silver_weight);
        $("#silver_rate").val(value.silver_rate);
        $("#silver_value").val(value.silver_value);
        $("#silver_narration").val(value.silver_narration);
        $('#ajax-loader').hide();
    }

    function remove_silver(index) {
        if (confirm('Are you sure ?')) {
            var value = silver_objectdata[index];
            if (typeof (value.silver_id) != "undefined" && value.silver_id !== null) {
                $('.silver_form').append('<input type="hidden" name="deleted_silver_id[]" id="deleted_silver_id" value="' + value.silver_id + '" />');
            }
            if(value.silver_sale_purchase == '1'){
                silver_count = silver_count + parseFloat(value.silver_weight);
                total_amount = total_amount - parseFloat(value.silver_value);
            } else if(value.silver_sale_purchase == '2') {
                silver_count = silver_count - parseFloat(value.silver_weight);
                total_amount = total_amount + parseFloat(value.silver_value);
            }
            $('#silver_bhav_id').val('1');
              
            silver_objectdata.splice(index, 1);
            display_silver_html(silver_objectdata);
        }
    }
    
    function display_transfer_html(transfer_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        $.each(transfer_objectdata, function (index, value) {
            var transfer_gold = parseFloat(value.transfer_gold || 0);
            var transfer_silver = parseFloat(value.transfer_silver  || 0);
            <?php if (isset($transfer_data)) { ?>
                if(edit_transfer_inc == '0'){
                    if(value.naam_jama == '2'){
                        transfer_gold_total = transfer_gold_total + transfer_gold;
                        transfer_silver_total = transfer_silver_total + transfer_silver;
                        total_amount = total_amount + parseInt(value.transfer_amount);
                    } else if(value.naam_jama == '1') {
                        transfer_gold_total = transfer_gold_total - transfer_gold;
                        transfer_silver_total = transfer_silver_total - transfer_silver;
                        total_amount = total_amount - parseInt(value.transfer_amount);
                    }
                }
            <?php } ?>
            if(value.naam_jama == '1'){
                var naam_jama = 'Naam (Dr)';
                var value_transfer_gold = zero_value - transfer_gold;
                var value_transfer_silver = zero_value - transfer_silver;
                var value_transfer_amount = zero_value - value.transfer_amount;
//                var operator = '-';
            } else { 
                var naam_jama = 'Jama (Cr)';
                var value_transfer_gold = transfer_gold;
                var value_transfer_silver = transfer_silver;
                var value_transfer_amount = value.transfer_amount;
//                var operator = '';
            }
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_transfer' + index + '" href="javascript:void(0);" onclick="edit_transfer(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_t_item" href="javascript:void(0);" onclick="remove_transfer(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="transfer_index_' + index + '"><td class="" width="5%">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td width="4%"></td>' +
                    '<td width="10%"> Transfer </td>' +
                    '<td width="8%">' + naam_jama + ' @ '+ value.party_name +'</td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="4%"></td>' +
                    '<td width="7%" class="text-right"> ' + parseFloat(value_transfer_gold || 0).toFixed(3) + '</td>' +
                    '<td width="7%" class="text-right">' + parseFloat(value_transfer_silver || 0).toFixed(3) + '</td>' +
                    '<td width="6%" class="text-right">' + value_transfer_amount + '</td>' +
                    '<td width="5%"></td></tr>';
            new_lineitem_html += row_html;
//            console.log(new_lineitem_html);
        });
        <?php if (isset($transfer_data)) { ?>
            edit_transfer_inc = 1;
        <?php } ?>
        $('tbody#pop_up_transfer_list').html(new_lineitem_html);
        $('#ajax-loader').hide();
        display_lineitem_html(lineitem_objectdata);
    }

    function edit_transfer(index) {
        $('#transfer_model').modal('show');
        $('#ajax-loader').show();
        $(".delete_t_item").addClass('hide');
        var value = transfer_objectdata[index];
        
        $("#transfer_index").val(index);
//        console.log(value.transfer_id);
        if (typeof (value.transfer_entry_id) != "undefined" && value.transfer_entry_id !== null) {
            $("#transfer_entry_id").val(value.transfer_entry_id);
        }
        $("input[name=naam_jama][value='" + value.naam_jama + "']").prop("checked",true);
        $("#transfer_account_id").val(value.transfer_account_id).trigger("change");
        setSelect2Value($("#transfer_account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/') ?>" + value.transfer_account_id);
        $("#transfer_gold").val(value.transfer_gold);
        $("#transfer_silver").val(value.transfer_silver);
        $("#transfer_amount").val(value.transfer_amount);
        $("#transfer_narration").val(value.transfer_narration);
//        $("#transfer_entry_id").val(value.transfer_id);
        $('#ajax-loader').hide();
    }

    function remove_transfer(index) {
        if (confirm('Are you sure ?')) {
            var value = transfer_objectdata[index];
            if (typeof (value.transfer_entry_id) != "undefined" && value.transfer_entry_id !== null) {
                $('.transfer_form').append('<input type="hidden" name="deleted_transfer_id[]" id="deleted_transfer_id" value="' + value.transfer_entry_id + '" />');
            }
//            console.log(value);
            if(value.naam_jama == '1'){
                transfer_gold_total = transfer_gold_total + parseFloat(value.transfer_gold);
                transfer_silver_total = transfer_silver_total + parseFloat(value.transfer_silver);
                total_amount = total_amount + parseFloat(value.transfer_amount);
            } else if(value.naam_jama == '2') {
                transfer_gold_total = transfer_gold_total - parseFloat(value.transfer_gold);
                transfer_silver_total = transfer_silver_total - parseFloat(value.transfer_silver);
                total_amount = total_amount - parseFloat(value.transfer_amount);
            }
            $('#transfer_id').val('1');
              
            transfer_objectdata.splice(index, 1);
            display_transfer_html(transfer_objectdata);
        }
    }
    
    function display_ad_charges_html(ad_charges_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        $.each(ad_charges_objectdata, function (index, value) {
            <?php if (isset($ad_charges_data)) { ?>
                if(edit_ad_charges_inc == '0'){
                    total_amount = total_amount + parseInt(value.ad_amount);
                }
            <?php } ?>
            var value_ad_charges_amount = value.ad_amount;
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_ad_charges' + index + '" href="javascript:void(0);" onclick="edit_ad_charges(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_ad_item" href="javascript:void(0);" onclick="remove_ad_charges(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="ad_charges_index_' + index + '"><td class="" width="5%">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td width="4%"></td>' +
                    '<td width="10%"> Charges : ' + value.ad_name + '</td>' +
                    '<td width="8%">' + value.ad_pcs + ' @ '+ value.ad_rate +'</td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="5%"></td>' +
                    '<td width="4%"></td>' +
                    '<td width="7%" class="text-right"></td>' +
                    '<td width="7%" class="text-right"></td>' +
                    '<td width="6%" class="text-right">' + value_ad_charges_amount + '</td>' +
                    '<td width="5%"></td></tr>';
            new_lineitem_html += row_html;
//            console.log(new_lineitem_html);
        });
        <?php if (isset($ad_charges_data)) { ?>
            edit_ad_charges_inc = 1;
        <?php } ?>
        $('tbody#pop_up_ad_charges_list').html(new_lineitem_html);
        $('#ajax-loader').hide();
        display_lineitem_html(lineitem_objectdata);
    }

    function edit_ad_charges(index) {
        $('#ad_charges_model').modal('show');
        $('#ajax-loader').show();
        $(".delete_ad_item").addClass('hide');
        var value = ad_charges_objectdata[index];
        
        $("#ad_lineitem_index").val(index);
//        console.log(value.ad_charges_id);
        if (typeof (value.sell_ad_charges_id) != "undefined" && value.sell_ad_charges_id !== null) {
            $("#sell_ad_charges_id").val(value.sell_ad_charges_id);
        }
        $("input[name=naam_jama][value='" + value.naam_jama + "']").prop("checked",true);
        $("#ad_charges_account_id").val(value.ad_charges_account_id).trigger("change");
        setSelect2Value($("#ad_id"), "<?= base_url('app/set_ad_name_select2_val_by_id/') ?>" + value.ad_id);
        $("#ad_pcs").val(value.ad_pcs);
        $("#ad_rate").val(value.ad_rate);
        $("#ad_amount").val(value.ad_amount);
        $('#ajax-loader').hide();
    }

    function remove_ad_charges(index) {
        if (confirm('Are you sure ?')) {
            var value = ad_charges_objectdata[index];
            if (typeof (value.sell_ad_charges_id) != "undefined" && value.sell_ad_charges_id !== null) {
                $('.ad_lineitem_form').append('<input type="hidden" name="deleted_ad_charges_id[]" id="deleted_ad_charges_id" value="' + value.sell_ad_charges_id + '" />');
            }
//            console.log(value);
            total_amount = total_amount - parseFloat(value.ad_amount);
            ad_charges_objectdata.splice(index, 1);
            display_ad_charges_html(ad_charges_objectdata);
        }
    }
    
    function display_less_ad_details_html(less_ad_details_objectdata) {
        $('#ajax-loader').show();
        var less_ad_details_html = '';
        var total_less_ad_details_pcs = 0;
        var total_less_ad_details_weight = 0;
        
        $.each(less_ad_details_objectdata, function (index, value) {

            var less_ad_details_edit_btn = '';
            var less_ad_details_delete_btn = '';
            var less_ad_details_ad_pcs = parseFloat(value.less_ad_details_ad_pcs) || 0;
            var less_ad_details_ad_weight = parseFloat(value.less_ad_details_ad_weight) || 0;
            less_ad_details_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_mhm_ad_item edit_less_ad_details_' + index + '" href="javascript:void(0);" onclick="edit_less_ad_details(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.less_ad_details_delete == 'allow'){
                less_ad_details_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_mhm_ad_item" href="javascript:void(0);" onclick="remove_less_ad_details(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    less_ad_details_edit_btn +
                    less_ad_details_delete_btn +
                    '</td>' +
                    '<td>' + value.less_ad_details_ad_name + '</td>' +
                    '<td class="text-right">' + less_ad_details_ad_pcs + '</td>'+
                    '<td class="text-right">' + less_ad_details_ad_weight.toFixed(2) + '</td>';
            total_less_ad_details_pcs = parseFloat(total_less_ad_details_pcs) + parseFloat(less_ad_details_ad_pcs);
            total_less_ad_details_weight = parseFloat(total_less_ad_details_weight) + parseFloat(less_ad_details_ad_weight);
            less_ad_details_html += row_html;
        });
        $('#less_ad_details_list').html(less_ad_details_html);
        $('#total_less_ad_details_pcs').html(total_less_ad_details_pcs);
        $('#total_less_ad_details_weight').html(total_less_ad_details_weight.toFixed(2));
        $('#ajax-loader').hide();
    }
    
    function edit_less_ad_details(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_mhm_ad_item").addClass('hide');
        less_ad_details_index = index;
        if (edit_less_ad_details_inc == 0) {
            edit_less_ad_details_inc = 1;
            $(".add_less_ad_details").removeAttr("disabled");
        }
        var value = less_ad_details_objectdata[index];
        $("#less_ad_details_index").val(index);
        $("#less_ad_details_delete").val(value.less_ad_details_delete);
        if(typeof(value.sell_less_ad_details_id) !== "undefined" && value.sell_less_ad_details_id !== null) {
            $("#sell_less_ad_details_id").val(value.sell_less_ad_details_id);
        }
        $("#less_ad_details_ad_id").val(null).trigger("change");
        setSelect2Value($("#less_ad_details_ad_id"), "<?= base_url('app/set_ad_name_select2_val_by_id/') ?>" + value.less_ad_details_ad_id);
        $("#less_ad_details_ad_pcs").val(value.less_ad_details_ad_pcs);
        $("#less_ad_details_ad_weight").val(value.less_ad_details_ad_weight);
        $('#ajax-loader').hide();
    }

    function remove_less_ad_details(index) {
        value = less_ad_details_objectdata[index];
        if (confirm('Are you sure ?')) {
            if (typeof (value.sell_less_ad_details_id) !== "undefined" && value.sell_less_ad_details_id !== null) {
                $('.less_ad_details_form').append('<input type="hidden" name="deleted_less_ad_details_id[]" id="deleted_less_ad_details_id" value="' + value.sell_less_ad_details_id + '" />');
            }
            less_ad_details_objectdata.splice(index, 1);
            display_less_ad_details_html(less_ad_details_objectdata);
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
    
    function get_wstg_from_account(){
        <?php //if (isset($order_lot_item) || isset($sell_item)){ } else { ?>
        <?php if($wstg_2 == '1') { ?>
            var account_id = $('#account_id').val();
            var item_id = $('#item_id').val();
            if (account_id != '' && account_id != null && item_id != '' && item_id != null) {
                $.ajax({
                    url: "<?= base_url('sell/get_wstg_from_account') ?>/" + account_id + '/' + item_id,
                    type: 'GET',
                    async: false,
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $('#wstg').val(json.account_data).change();
                        $('#default_wstg').val(json.account_data).change();
                    }
                });
            }
        <?php } else { ?>
            $('#wstg').val('0');
            $('#default_wstg').val('0');
        <?php } ?>
        <?php //} ?>
    }
    
    function readURL(input) {
        if (input.files && input.files[0]) {
//            console.log(input.files);
            $("#ajax-loader").show();
            var form = new FormData();
            var myFormData = document.getElementById('file_upload').files[0];
            form.append('file_upload', myFormData);
            form.append('action', 'get_temp_path');
            $.ajax({
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                url: "<?= base_url('sell/get_temp_path_image') ?>",
                success: function (html) {
                    $('#image').val(html);
                    $("#ajax-loader").hide();
                }
            });
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
    
    function get_stock_for_metal_receipt(department_id,metal_item_id,metal_tunch,metal_pr_id){
        var json_grwt = 0;
        $.ajax({
            url: "<?php echo base_url('sell/get_stock_for_metal_receipt'); ?>/" + department_id +"/"+ metal_item_id +"/"+ metal_tunch +"/"+metal_pr_id,
            type: "GET",
            async: false,
            data: "",
            success: function(response_grwt){
                json_grwt = $.parseJSON(response_grwt);
                
            }
        });
        return json_grwt;
    }
    
    function round10(x){
        return Math.round(x / 10) * 10;
    }
    
    function save_form(postData, href_url){
        alert('Test'); return false;
        $("#ajax-loader").show();
        $('.module_save_btn').attr('disabled', 'disabled');
        var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
        postData.append('line_items_data', lineitem_objectdata_stringify);
        var metal_objectdata_stringify = JSON.stringify(metal_objectdata);
        postData.append('metal_data', metal_objectdata_stringify);
        var pay_rec_objectdata_stringify = JSON.stringify(pay_rec_objectdata);
        postData.append('pay_rec_data', pay_rec_objectdata_stringify);
        var gold_objectdata_stringify = JSON.stringify(gold_objectdata);
        postData.append('gold_data', gold_objectdata_stringify);
        var silver_objectdata_stringify = JSON.stringify(silver_objectdata);
        postData.append('silver_data', silver_objectdata_stringify);
        var transfer_objectdata_stringify = JSON.stringify(transfer_objectdata);
        postData.append('transfer_data', transfer_objectdata_stringify);
        var ad_charges_objectdata_stringify = JSON.stringify(ad_charges_objectdata);
        postData.append('ad_lineitem_data', ad_charges_objectdata_stringify);
        $.ajax({
            url: href_url,
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
                }else if (json['success'] == 'Added') {
                    window.location.href = "<?php echo $list_page_url; ?>";
                } else if (json['success'] == 'Updated') {
                    window.location.href = "<?php echo $list_page_url; ?>";
                }
                return false;
            },
        });
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