<?php $this->load->view('success_false_notify'); ?>
<style>
    .direct-chat-text {
        border-radius: 6px !important;
        position: relative;
        padding: 5px 10px !important;
        background: #00a65a !important;
        border: 1px solid #00a65a !important;
        margin: 5px 0 0 50px !important;
        color: #ffffff !important;
        font-weight: 900 !important;
    }
    .direct-chat-primary .right>.direct-chat-text {
        background: #00a65a !important;
        border-color: #00a65a !important;
        color: #fff !important;
    }
    .direct-chat-primary .right>.direct-chat-text:before {
        border-left-color: #00a65a !important;

    }
    .right .direct-chat-text:after, .right .direct-chat-text:before {
        left: auto;
        right: 100% !important;
        border-left-color: transparent !important;
        border-right-color: #00a65a !important;
    }
    .direct-chat-text:before {
        border-width: 6px !important;
        margin-top: -6px !important;
    }
    .direct-chat-text:after, .direct-chat-text:before {
        position: absolute;
        left: 100% !important;
        top: 14px !important;
        border: solid 7px transparent !important;
        border-left-color: #00a65a !important;
        content: ' ';
        height: 0;
        width: 0;
        pointer-events: none;
    }
    #costing_report_model .label{
        font-size: 14px;
    }
</style>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('manu_hand_made/save_manu_hand_made') ?>" method="post" id="save_manu_hand_made" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($mhm_data->mhm_id) && !empty($mhm_data->mhm_id)) { ?>
            <input type="hidden" name="mhm_id" class="mhm_id" value="<?= $mhm_data->mhm_id ?>">
        <?php } ?>
            <input type="hidden" id="total_grwt_sell" value=""/>
        <input type="hidden" name="total_ad_amount_for_journal" id="total_ad_amount_for_journal" value=""/>
        <input type="hidden" name="total_meena_charges_for_journal" id="total_meena_charges_for_journal" value=""/>

        <!-- Content Header (Page header) -->
     
        <section class="content-header">
            <h1>
                Add Manufacture Hand Made
                <?php $isEdit = $this->app_model->have_access_role(MANU_HAND_MADE_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(MANU_HAND_MADE_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(MANU_HAND_MADE_MODULE_ID, "add");
                $allow_change_date = $this->app_model->have_access_role(MANU_HAND_MADE_MODULE_ID, "allow_change_date"); ?>
                <?php if(isset($mhm_data->mhm_id) && !empty($mhm_data->mhm_id)) { } else { if(isset($isAdd) && $isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($mhm_data->mhm_id) ? '' : $btn_disable;?>><?= isset($mhm_data->mhm_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('manu_hand_made/manu_hand_made_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Manufacture Hand Made List</a>
                <?php } ?>
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
                                    <div class="col-md-4">
                                        <label for="department_id">Department<span class="required-sign">&nbsp;*</span></label>
                                        <select name="department_id" id="department_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="operation_id">Operation<span class="required-sign">&nbsp;*</span></label>
                                        <select name="operation_id" id="operation_id" class="form-control select2"></select>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="worker_id">Worker/Supplier<span class="required-sign">&nbsp;*</span></label>
                                        <select name="worker_id" id="worker_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                            <?php if (isset($mhm_data->mhm_id) && !empty($mhm_data->mhm_id)) { ?>
                                                <label for="reference_no">Reference No</label>
                                                <input type="text" name="reference_no" id="reference_no" class="form-control" readonly="" value="<?= (isset($mhm_data->reference_no)) ? $mhm_data->reference_no : ''; ?>">
                                            <?php } ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="date">Date<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="mhm_date" id="datepicker2" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control input-datepicker" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?= (isset($mhm_data->mhm_date)) ? date('d-m-Y', strtotime($mhm_data->mhm_date)) : date('d-m-Y'); ?>">
                                            </div>
                                            <div class="clearfix"></div><br />
                                            <?php if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"allow to audit mhm")) { ?>
                                                <?php if(isset($mhm_data->lott_complete) && $mhm_data->lott_complete == '1'){ ?>
                                                    <div class="col-md-4 audit_status_div">
                                                        <label for="audit_status">Audit Status</label>
                                                        <input type="checkbox" name="audit_status" id="audit_status" <?php echo (isset($mhm_data->audit_status) && $mhm_data->audit_status == '2') ? ' Checked ' : ''; ?> style="height: 20px; width: 20px;">
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mhm_remark">Remark</label>
                                        <textarea name="mhm_remark" id="mhm_remark" class="form-control"><?php echo (isset($mhm_data->mhm_remark)) ? $mhm_data->mhm_remark : ''; ?></textarea><br />
                                    </div>
                                    <div class="col-md-4">
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="lott_complete">Lott Complete ?</label><br>
                                                <?php if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"allow to lott complete mhm")) { ?>
                                                    <label><input type="radio" name="lott_complete" class="iradio_minimal-blue" value="1" <?= (isset($mhm_data->lott_complete)) && $mhm_data->lott_complete == 1 ? 'checked' : ''; ?>> Yes</label> &nbsp;&nbsp;&nbsp;
                                                    <label><input type="radio" name="lott_complete" class="iradio_minimal-blue" value="0" <?= (isset($mhm_data->lott_complete)) ? $mhm_data->lott_complete == 0 ? 'checked' : '' : 'checked'; ?>> No</label>
                                                <?php } else { ?>
                                                    <?= (isset($mhm_data->lott_complete)) && $mhm_data->lott_complete == 1 ? 'Yes' : 'No'; ?>
                                                    <input type="hidden" name="lott_complete" value="<?= (isset($mhm_data->lott_complete)) && $mhm_data->lott_complete == 1 ? '1' : '0'; ?>">
                                                <?php } ?>
                                            </div>
                                            <div class="col-md-8">
                                                <div id="mhm_diffrence_calculation"></div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <?php $lott_complete_hide =  (isset($mhm_data->lott_complete)) ? $mhm_data->lott_complete == 0 ? 'hide' : '' : 'hide'; ?>
                                        <div class="lott_complete_div <?php echo $lott_complete_hide; ?> ">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="mhm_diffrence">Difference : </label><br />
                                                    <span id="mhm_diffrence_value"></span>
                                                    <?php if($manufacture_lott_complete_in == '1'){ ?>
                                                    <span id="mhm_diffrence_in" class="text-bold">Gr.Wt.</span>
                                                    <?php } else { ?>
                                                        <span id="mhm_diffrence_in" class="text-bold">Fine</span>
                                                    <?php } ?>
                                                    <input type="hidden" name="mhm_diffrence" id="mhm_diffrence" class="num_only form-control" value="<?= (isset($mhm_data->mhm_diffrence)) ? $mhm_data->mhm_diffrence  : ''; ?>">
                                                </div>
                                                <?php if($manufacture_lott_complete_in == '3'){ ?>
                                                    <div class="col-md-4">
                                                        <label for="worker_gold_rate">Worker Gold Rate</label>
                                                        <input type="text" name="worker_gold_rate" id="worker_gold_rate" class="num_only form-control" value="<?= (isset($mhm_data->worker_gold_rate)) ? $mhm_data->worker_gold_rate  : ''; ?>">
                                                    </div>
                                                    <div class="col-md-4"><br/>
                                                        Amount : <span id="mhm_diffrence_amount"></span><br />
                                                        <input type="checkbox" name="lott_complete_sms" id="lott_complete_sms">
                                                        <label for="lott_complete_sms">Send SMS</label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm" id="order_items_btn" ><b> Order Items</b></button>
                                    </div>
                                    <div class="col-md-8 ad_lineitem_div" style="display: none;">
                                        <div style="border: 1px solid #cccccc;">
                                            <h5 style="text-align: center;">
                                                <div class="pull-left">
                                                    <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#costing_report_model" style="padding: 0px 5px; margin-top: -10px; margin-left: 4px; border-bottom: 1px solid #0086ca; color: #0086ca; background: none;"><strong>See Costing Report</strong></button>
                                                </div>
                                                <strong>Ad Details</strong>
                                                <div class="pull-right">
                                                    <label for="chhijjat_per_100_ad">Chhijjat per 100 Ad</label>
                                                    <input type="text" name="chhijjat_per_100_ad" class="num_only chhijjat_per_100_ad" id="chhijjat_per_100_ad" style="width: 100px; padding: 2px 5px;">&nbsp;
                                                </div>
                                            </h5>
                                            <div class="row">
                                                <div class="clearfix"></div>
                                                <div class="col-md-6">
                                                    <div class="ad_lineitem_form">
                                                        <input type="hidden" name="ad_lineitem_index" id="ad_lineitem_index" />
                                                        <input type="hidden" name="ad_lineitem_data[ad_lineitem_delete]" id="ad_lineitem_delete" value="allow" />
                                                        <?php if(isset($manu_hand_made_ads) && !empty($manu_hand_made_ads)){ ?>
                                                            <input type="hidden" name="ad_lineitem_data[mhm_ad_id]" id="mhm_ad_id" />
                                                        <?php } ?>
                                                        <div class="col-md-4">
                                                            <label for="ad_id">Ad<span class="required-sign">&nbsp;*</span></label>
                                                            <select name="ad_lineitem_data[ad_id]" class="form-control ad_id" id="ad_id"></select>
                                                        </div>
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
                                                        <div class="col-md-4">
                                                            <input type="button" id="add_ad_lineitem" class="btn btn-info btn-sm add_ad_lineitem" value="Add Ad Line" style="margin-top: 21px;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="" style="overflow-x: scroll;">
                                                        <table style="" class="table custom-table item-table">
                                                            <thead>
                                                                <tr>
                                                                    <th width="80px">Action</th>
                                                                    <th>Ad</th>
                                                                    <th class="text-right">Pcs</th>
                                                                    <th class="text-right">Rate</th>
                                                                    <th class="text-right">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="ad_lineitem_list"></tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>Total:</th>
                                                                    <th id="auto_count_chhijat"></th>
                                                                    <th class="text-right" id="total_ad_pcs"></th>
                                                                    <th></th>
                                                                    <th class="text-right" id="total_ad_amount"></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 meena_charges_div" style="display: none;">
                                        <h5 style="text-align: center;">
                                            <div class="pull-right">
                                                <label for="meena_charges">Meena Charges per 1 gm</label>
                                                <input type="text" name="meena_charges" class="num_only meena_charges" id="meena_charges" style="width: 100px; padding: 2px 5px;">&nbsp;<br />
                                                <label class="label label-success meena_charges_amount pull-right" style="font-size: 14px"></label>
                                            </div>
                                        </h5>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="line_item_form item_fields_div">
                                        <h4 class="col-md-12">
                                            Line Item &nbsp;&nbsp;&nbsp;
                                            <span><label style="margin-bottom: 0px;"><input type="checkbox" name="line_items_data[tunch_textbox]" id="tunch_textbox" > <small>Tunch Textbox</small></label></span>
                                        </h4>
                                        <div class="clearfix"></div><br />
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <input type="hidden" name="line_items_data[mhm_item_delete]" id="mhm_item_delete" value="allow" />
                                        <?php if(isset($manu_hand_made_detail) && !empty($manu_hand_made_detail)){ ?>
                                            <input type="hidden" name="line_items_data[mhm_detail_id]" id="mhm_detail_id" />
                                            <input type="hidden" name="line_items_data[purchase_sell_item_id]" id="purchase_sell_item_id"/>
                                            <input type="hidden" name="line_items_data[stock_type]" id="stock_type" />
                                        <?php } ?>
                                        <div class="col-md-2">
                                            <label for="type">Type<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[type_id]" class="form-control type_id" id="type_id">
                                                <option value=""> - Select - </option>
                                                <option value="<?php echo MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID; ?>">Issue Finish Work</option>
                                                <option value="<?php echo MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID; ?>">Issue Scrap</option>
                                                <option value="<?php echo MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID; ?>">Receive Finish Work</option>
                                                <option value="<?php echo MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID; ?>">Receive Scrap</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="item_id">Item<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[item_id]" class="form-control item_id select2" id="item_id">
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="weight">Weight<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[weight]" class="form-control num_only weight" id="weight"  placeholder="" value="">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="stamp">Less</label>
                                            <input type="text" name="line_items_data[less]" class="form-control less num_only" id="less"  placeholder="" value="">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="unit">Net.Wt<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[net_wt]" class="form-control net_wt num_only" id="net_wt" placeholder="" value="" readonly="">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="touch_id">Tunch<span class="required-sign">&nbsp;*</span></label>
                                            <div class="touch_select">
                                                <select name="line_items_data[touch_id]" class="form-control touch_id" id="touch_id">
                                                    <option value=""> - Select - </option>
                                                    <?php if(isset($touch) && !empty($touch)){ foreach ($touch as $value) { ?>
                                                        <option value="<?= $value->purity; ?>"<?= isset($touch_id) && $value->purity == $touch_id ? 'selected="selected"' : ''; ?>><?= $value->purity; ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                            <div class="touch_input" hidden="">
                                                <input type="text" name="line_items_data[touch_id]" id="touch_data_id" class="form-control touch_id num_only" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="actual_tunch">Actual Tunch</label>
                                            <input type="text" name="line_items_data[actual_tunch]" class="form-control num_only actual_tunch" id="actual_tunch"  placeholder="" value=""><br />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="fine">Fine<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[fine]" class="form-control num_only fine" id="fine"  placeholder="" value=""><br />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="pcs">Pcs</label>
                                            <input type="text" name="line_items_data[pcs]" class="form-control num_only pcs" id="pcs"  placeholder="" value=""><br />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="mhm_detail_date">Date<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[mhm_detail_date]" class="form-control datepicker" id="mhm_detail_date" placeholder="" ><br />
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-1 ad_weight_div" style="display: none;">
                                            <label for="ad_weight">Ad Weight</label>
                                            <input type="text" name="line_items_data[ad_weight]" class="form-control num_only ad_weight" id="ad_weight" >
                                        </div>
                                        <div class="col-md-3">
                                            <label for="mhm_detail_remark">Remark</label>
                                            <textarea name="line_items_data[mhm_detail_remark]" class="form-control" id="mhm_detail_remark" placeholder=""></textarea><br />
                                        </div>
                                        <div class="col-md-1 including_ad_wt_div" style="display: none;">
                                            <label style="margin-bottom: 0px;"><input type="checkbox" name="line_items_data[including_ad_wt]" id="including_ad_wt" > Weight Including <br> Ad Weight</label>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp;</label>
                                            <input type="button" id="add_lineitem" class="btn btn-info btn-sm pull-right add_lineitem" value="Add Row" style="margin-top: 21px;"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-6">
                                        <div class="" style="overflow-x: scroll;">
                                        <h4 style="text-align: center">Receive Table</h4>
                                        <table style="" class="table custom-table item-table">
                                            <thead>
                                                <tr>
                                                    <th width="80px">Action</th>
                                                    <th>Type</th>
                                                    <th>Item Name</th>
                                                    <th class="text-right">Weight</th>
                                                    <th class="text-right">Less</th>
                                                    <th class="text-right">Net.Wt</th>
                                                    <th class="text-right">Tunch</th>
                                                    <th class="text-right">A. Tunch</th>
                                                    <th class="text-right">Fine</th>
                                                    <th class="text-right">Pcs</th>
                                                    <th class="text-right">Ad Weight</th>
                                                    <th class="text-nowrap">Date</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="receive_lineitem_list"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total RFW:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_receive_fw_weight"></th>
                                                    <th class="text-right" id="total_receive_fw_less"></th>
                                                    <th class="text-right" id="total_receive_fw_net_wt"></th>
                                                    <th class="text-right" id="total_receive_fw_tunch"></th>
                                                    <th class="text-right" id="total_receive_fw_actual_tunch"></th>
                                                    <th class="text-right" id="total_receive_fw_fine"></th>
                                                    <th class="text-right" id="total_receive_fw_pcs"></th>
                                                    <th class="text-right" id="total_receive_fw_ad_weight"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>Total RS:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_receive_s_weight"></th>
                                                    <th class="text-right" id="total_receive_s_less"></th>
                                                    <th class="text-right" id="total_receive_s_net_wt"></th>
                                                    <th class="text-right" id="total_receive_s_tunch"></th>
                                                    <th class="text-right" id="total_receive_s_actual_tunch"></th>
                                                    <th class="text-right" id="total_receive_s_fine"></th>
                                                    <th class="text-right" id="total_receive_s_pcs"></th>
                                                    <th class="text-right" id="total_receive_s_ad_weight"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>Total:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_receive_weight"></th>
                                                    <th class="text-right" id="total_receive_less"></th>
                                                    <th class="text-right" id="total_receive_net_wt"></th>
                                                    <th class="text-right" id="total_receive_tunch"></th>
                                                    <th class="text-right" id="total_receive_actual_tunch"></th>
                                                    <th class="text-right" id="total_receive_fine"></th>
                                                    <th class="text-right" id="total_receive_pcs"></th>
                                                    <th class="text-right" id="total_receive_ad_weight"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr class="ad_weight_tr" style="display: none;">
                                                    <th>Total With AD:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_receive_with_ad_weight"></th>
                                                    <th class="text-right" id="total_receive_with_ad_less"></th>
                                                    <th class="text-right" id="total_receive_with_ad_net_wt"></th>
                                                    <th class="text-right" id="total_receive_with_ad_tunch"></th>
                                                    <th class="text-right" id="total_receive_with_ad_actual_tunch"></th>
                                                    <th class="text-right" id="total_receive_with_ad_fine"></th>
                                                    <th class="text-right" id="total_receive_with_ad_pcs"></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="" style="overflow-x: scroll;">
                                        <h4 style="text-align: center">Issue Table</h4>
                                        <table style="" class="table custom-table item-table">
                                            <thead>
                                                <tr>
                                                    <th width="80px">Action</th>
                                                    <th>Type</th>
                                                    <th>Item Name</th>
                                                    <th class="text-right">Weight</th>
                                                    <th class="text-right">Less</th>
                                                    <th class="text-right">Net.Wt</th>
                                                    <th class="text-right">Tunch</th>
                                                    <th class="text-right">A. Tunch</th>
                                                    <th class="text-right">Fine</th>
                                                    <th class="text-right">Pcs</th>
                                                    <th class="text-right">Ad Weight</th>
                                                    <th class="text-nowrap">Date</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="issue_lineitem_list"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right">Total IFW: </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_issue_fw_weight"></th>
                                                    <th class="text-right" id="total_issue_fw_less"></th>
                                                    <th class="text-right" id="total_issue_fw_net_wt"></th>
                                                    <th class="text-right" id="total_issue_fw_tunch"></th>
                                                    <th class="text-right" id="total_issue_fw_actual_tunch"></th>
                                                    <th class="text-right" id="total_issue_fw_fine"></th>
                                                    <th class="text-right" id="total_issue_fw_pcs"></th>
                                                    <th class="text-right" id="total_issue_fw_ad_weight"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>Total IS:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_issue_s_weight"></th>
                                                    <th class="text-right" id="total_issue_s_less"></th>
                                                    <th class="text-right" id="total_issue_s_net_wt"></th>
                                                    <th class="text-right" id="total_issue_s_tunch"></th>
                                                    <th class="text-right" id="total_issue_s_actual_tunch"></th>
                                                    <th class="text-right" id="total_issue_s_fine"></th>
                                                    <th class="text-right" id="total_issue_s_pcs"></th>
                                                    <th class="text-right" id="total_issue_s_ad_weight"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>Total:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_issue_weight"></th>
                                                    <th class="text-right" id="total_issue_less"></th>
                                                    <th class="text-right" id="total_issue_net_wt"></th>
                                                    <th class="text-right" id="total_issue_tunch"></th>
                                                    <th class="text-right" id="total_issue_actual_tunch"></th>
                                                    <th class="text-right" id="total_issue_fine"></th>
                                                    <th class="text-right" id="total_issue_pcs"></th>
                                                    <th class="text-right" id="total_issue_ad_weight"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr class="ad_weight_tr" style="display: none;">
                                                    <th>Total With AD:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_issue_with_ad_weight"></th>
                                                    <th class="text-right" id="total_issue_with_ad_less"></th>
                                                    <th class="text-right" id="total_issue_with_ad_net_wt"></th>
                                                    <th class="text-right" id="total_issue_with_ad_tunch"></th>
                                                    <th class="text-right" id="total_issue_with_ad_actual_tunch"></th>
                                                    <th class="text-right" id="total_issue_with_ad_fine"></th>
                                                    <th class="text-right" id="total_issue_with_ad_pcs"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <span><b>Balance Weight : </b> <span id="total_weight"></span></span><br />
                                        <span><b>Balance Net.Wt : </b> <span id="total_net_wt"></span></span><br />
                                        <span><b>Balance Fine : </b> <span id="total_fine"></span></span><br />
                                        <span><b>Receive Tunch : </b> <span id="balance_receive_tunch"></span></span>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if (isset($mhm_data->mhm_id) && !empty($mhm_data->mhm_id)) { ?>
                                    <div class="created_updated_info" style="margin-left: 10px;">
                                       Created by : <?php echo isset($mhm_data->created_by_name) ? $mhm_data->created_by_name :'' ; ?>
                                       @ <?php echo isset($mhm_data->created_at) ? date('d-m-Y h:i A',strtotime($mhm_data->created_at)) :'' ; ?><br/>
                                       Updated by : <?php echo isset($mhm_data->updated_by_name) ? $mhm_data->updated_by_name :'' ;?>
                                       @ <?php echo isset($mhm_data->updated_at) ? date('d-m-Y h:i A',strtotime($mhm_data->updated_at)) : '' ;?>
                                    </div>
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
<div id="order_items_selection_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="myModalLabel">Order Items Selection</h4>
                </div>
            </div>
            <div class="modal-body edit-content">
                <div class="col-md-12">
                    <div class="popup_div">
                        <table style="" class="table custom-table border item-table">
                            <thead>
                                <tr>
                                    <th class="text-center"><label><input type="checkbox" id="select_all_items" style="height:20px; width:20px" /> Select All</label></th>
                                    <th class="text-right">Item Name</th>
                                    <th class="text-right">Category Name</th>
                                    <th>Party Name</th>
                                    <th>Order No</th>
                                    <th class="text-right">Order Date</th>
                                    <th class="text-right">Delivery Date</th>
                                    <th class="text-right">Tunch</th>
                                    <th class="text-right">Weight</th>
                                    <th class="text-right">Pcs</th>
                                </tr>
                            </thead>
                            <tbody id="order_items_selection_list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <button type="button" class="btn btn-primary items_save_btn" id="items_save_btn">Select</button>
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
                                    <th class="text-center" id="pts_checked_total_average">0</th>
                                    <th class="text-right" id="pts_checked_total_fine">0</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Total</th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right" id="pts_total_grwt"></th>
                                    <th class="text-right" id="pts_total_less"></th>
                                    <th class="text-right" id="pts_total_ntwt"></th>
                                    <th class="text-center" id="pts_total_average"></th>
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
<div id="costing_report_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="myModalLabel">Costing Report</h4>
                </div>
            </div>
            <div class="modal-body edit-content">
                <div class="row">
                    <div class="col-md-2">
                        <label for="costing_total_issue_tunch">Total Issue Tunch</label>
                        <input type="text" id="costing_total_issue_tunch" class="num_only form-control" readonly="">
                    </div>
                    <div class="col-md-1">
                        <label for="costing_wastage">Wastage</label>
                        <input type="text" id="costing_wastage" value="" class="num_only form-control" >
                    </div>
                    <div class="col-md-9">
                        <table style="width:100%;">
                            <tr>
                                <td class="text-right"><label for="stone_charge_in_amount">Stone charge in Amount:</label></td>
                                <td><span id="stone_charge_in_amount" class="label bg-purple"></span><br></td>
                                <td class="text-right"><label for="costing_total_rfw_wt">Total RFW wt :</label></td>
                                <td><span id="costing_total_rfw_wt" class="label" style="background-color: #8d908e"></span></td>
                                <td class="text-right"><label for="costing_total_rfw_net_wt">Total RFW net wt :</label></td>
                                <td><span id="costing_total_rfw_net_wt" class="label" style="background-color: #79a78f"></span></td>
                            </tr>
                            <tr>
                                <td class="text-right"><a href="<?php echo base_url('master/setting') ?>" target="_blanck">Gold Rate</a> :</td>
                                <td><span class="label bg-maroon"><?php echo $gold_rate; ?></span></td>
                                <td class="text-right"><label for="costing_total_ad_wt">Total Ad Wt :</label></td>
                                <td><span id="costing_total_ad_wt" class="label" style="background-color: #616362"></span></td>
                                <td class="text-right"><label for="costing_balance_net_wt">Balance Net wt :</label></td>
                                <td><span id="costing_balance_net_wt" class="label" style="background-color: #8c718b"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <table class="table">
                        <tr>
                            <th></th>
                            <th class="text-right">Net Wt</th>
                            <th class="text-right">Fine</th>
                            <th>Fourmula</th>
                        </tr>
                        <tr>
                            <td class="text-right direct-chat-text">Total IFW</td>
                            <td class="text-right"><label id="costing_total_issue_fw_net_wt" class="label label-success" readonly="" style="font-size: 14px;"></label></td>
                            <td class="text-right"><label id="costing_total_issue_fw_fine" class="label bg-blue" readonly="" style="font-size: 14px;"></label></td>
                            <td><span class="label label-success">Total IFW Net wt</span>&nbsp;<span class="label" style="background-color: #852266">* ( Total Issue Tunch + Wastage ) / 100</span></td>
                        </tr>
                        <tr>
                            <td class="text-right direct-chat-text">Stone charge in Gold</td>
                            <td></td>
                            <td class="text-right"><label id="costing_stone_charge_in_gold" class="label bg-yellow" readonly="" style="font-size: 14px;"></label></td>
                            <td><span class="label bg-purple">Stone charge in Amount</span>&nbsp;<span class="label bg-maroon">/ Gold Rate From Setting * </span>&nbsp;<span class="label" style="background-color: #852266">10</span></td>
                        </tr>
                        <tr>
                            <td class="text-right direct-chat-text">Total RS</td>
                            <td class="text-right"><label id="costing_total_receive_s_net_wt" class="label bg-aqua" readonly="" style="font-size: 14px;"></label></td>
                            <td class="text-right"><label id="costing_total_receive_s_fine" class="label bg-teal" readonly="" style="font-size: 14px;"></label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-right direct-chat-text">Total</td>
                            <td></td>
                            <td class="text-right"><label id="costing_total" class="label bg-red" readonly="" style="font-size: 14px;"></label></td>
                            <td class="label"><span class="label bg-blue">Total IFW Fine</span>&nbsp;<span class="label bg-yellow"> + Stone charge in Gold -</span>&nbsp;<span class="label bg-teal">Total RS Fine</span></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="text-right direct-chat-text">Cost including Stone</td>
                            <td></td>
                            <td class="text-right"><label id="cost_including_stone" class="label" readonly="" style="background-color: #001ad0; font-size: 14px;"></label></td>
                            <td class="label"><span class="label bg-red">Total</span>&nbsp;<span class="label" style="background-color: #8d908e">/ (Total RFW wt</span>&nbsp;<span class="label" style="background-color: #616362">+ Total Ad Wt)</span>&nbsp;<span class="label" style="background-color: #852266">* 100</span></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="text-right direct-chat-text">Cost without Stone without Chhijjat</td>
                            <td></td>
                            <td class="text-right"><label id="cost_without_stone_without_chhijjat" class="label" readonly="" style="background-color: #a733ff; font-size: 14px;"></label></td>
                            <td><span class="label bg-red">Total</span>&nbsp;<span class="label" style="background-color: #79a78f">/ Total RFW net wt</span>&nbsp;<span class="label" style="background-color: #852266">* 100</span></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="text-right direct-chat-text">Cost without Stone with Chhijjat</td>
                            <td></td>
                            <td class="text-right"><label id="cost_without_stone_with_chhijjat" class="label bg-purple" readonly="" style="font-size: 14px;"></td>
                            <td><span class="label bg-red">Total</span>&nbsp;<span class="label" style="background-color: #79a78f">/ (Total RFW net wt +</span>&nbsp;<span class="label" style="background-color: #8c718b">Balance net wt)</span>&nbsp;<span class="label" style="background-color: #852266">* 100</span></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var lott_complete_yes_no = '';
    var module_submit_flag = 0;
    var checked_order_items = [];
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    var line_items_index = '';
    var edit_lineitem_inc = 0;
    var lineitem_objectdata = [];
    var pts_lineitem_objectdata = [];
    <?php if (isset($checked_order_items)) { ?>
        var li_checked_order_items_objectdata = [<?php echo $checked_order_items; ?>];
        var checked_order_items = [];
        if (li_checked_order_items_objectdata != '') {
            $.each(li_checked_order_items_objectdata, function (index, value) {
                checked_order_items.push(value);
            });
            console.log(checked_order_items);
        }
    <?php } ?>
    <?php if (isset($manu_hand_made_detail)) { ?>
        var li_lineitem_objectdata = [<?php echo $manu_hand_made_detail; ?>];
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    display_lineitem_html(lineitem_objectdata);
    var ad_lineitem_index = '';
    var edit_ad_lineitem_inc = 0;
    var ad_lineitem_objectdata = [];
    <?php if (isset($manu_hand_made_ads)) { ?>
        var li_ad_lineitem_objectdata = [<?php echo $manu_hand_made_ads; ?>];
        var ad_lineitem_objectdata = [];
        if (li_ad_lineitem_objectdata != '') {
            $.each(li_ad_lineitem_objectdata, function (index, value) {
                ad_lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    display_ad_lineitem_html(ad_lineitem_objectdata);
    $(document).ready(function () {
        $('.type_id, #touch_id').select2();

        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_select2_source') ?>");
        <?php if (isset($mhm_data->worker_id)) { ?>
            setSelect2Value($("#worker_id"), "<?= base_url('app/set_worker_select2_val_by_id/' . $mhm_data->worker_id) ?>");
        <?php }?>
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        <?php if (isset($mhm_data->department_id)) { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $mhm_data->department_id) ?>");
            initAjaxSelect2($("#operation_id"), "<?= base_url('app/operation_from_department_select2_source/' . $mhm_data->department_id) ?>");
        <?php } else { ?>
        <?php } ?>
        <?php if (isset($mhm_data->operation_id)) { ?>
            if(<?php echo $mhm_data->operation_id; ?> == <?php echo MANUFACTURE_HM_OPERATION_NANG_SETTING_ID; ?>){
                $('.ad_lineitem_div').show();
                $('.ad_weight_div').show();
                $('.including_ad_wt_div').show();
                $('.ad_weight_tr').show();
            }
            if(<?php echo $mhm_data->operation_id; ?> == <?php echo MANUFACTURE_HM_OPERATION_MEENA_ID; ?>){
                $('.meena_charges_div').show();
            }
            setSelect2Value($("#operation_id"), "<?= base_url('app/set_operation_select2_val_by_id/' . $mhm_data->operation_id) ?>");
            initAjaxSelect2($("#worker_id"), "<?= base_url('app/worker_from_operation_select2_source/' . $mhm_data->operation_id) ?>");
        <?php }?>
            
        initAjaxSelect2($("#ad_id"), "<?= base_url('app/ad_name_select2_source/is_nang_setting') ?>");
            
        $(document).on('change', '#department_id', function(){
            checked_order_items = [];
            $('#operation_id').val(null).empty().select2();
            $('#worker_id').val(null).empty().select2();
//            $('#operation_id').val(null).trigger('change');
            var department_id = $("#department_id").val();
            if(department_id != '' && department_id != null){
                initAjaxSelect2($("#operation_id"), "<?= base_url('app/operation_from_department_select2_source/') ?>/" + department_id);
            }
        });
        
        $(document).on('change', '#operation_id', function(){
            $('#worker_id').val(null).empty().select2();
//            $("#worker_id").val(null).trigger("change");
            var operation_id = $("#operation_id").val();
            if($(this).val() != '' || $(this).val() != null){
                initAjaxSelect2($("#worker_id"), "<?= base_url('app/worker_from_operation_select2_source/') ?>/" + operation_id);
            }
            if(operation_id == <?php echo MANUFACTURE_HM_OPERATION_NANG_SETTING_ID; ?>){
                $('.ad_lineitem_div').show();
                $('.ad_weight_div').show();
                $('.including_ad_wt_div').show();
                $('.ad_weight_tr').show();
            } else {
                $('.ad_lineitem_div').hide();
                $('.ad_weight_div').hide();
                $('.including_ad_wt_div').hide();
                $('.ad_weight_tr').hide();
            }
            if(operation_id == <?php echo MANUFACTURE_HM_OPERATION_MEENA_ID; ?>){
                $('.meena_charges_div').show();
            } else {
                $('.meena_charges_div').hide();
            }
        });
        
        get_worker_chhijjat_per_100_ad();
        get_worker_meena_charges();
        $(document).on('change', '#worker_id', function(){
            get_worker_chhijjat_per_100_ad();
            get_worker_meena_charges();
        });
        
        $(document).on('keyup change', '#chhijjat_per_100_ad', function(){
            display_ad_lineitem_html(ad_lineitem_objectdata);
        });
        
        $(document).on('keyup change', '#meena_charges', function(){
            var lott_complete_val = $('input[type=radio][name=lott_complete]:checked').val();
            if(lott_complete_val == 1){
                set_total_meena_charges_for_journal();
            } else {
                $('#total_meena_charges_for_journal').val('');
                $('.meena_charges_amount').html('');
            }
        });
        
        $(document).on('keyup change', '#ad_pcs, #ad_rate', function () {
            var ad_pcs = parseFloat($('#ad_pcs').val()) || 0;
            ad_pcs = round(ad_pcs, 2).toFixed(2);
            var ad_rate = parseFloat($('#ad_rate').val()) || 0;
            ad_rate = round(ad_rate, 2).toFixed(2);
            var ad_amount = parseFloat(ad_pcs) * parseFloat(ad_rate);
            ad_amount = round(ad_amount, 0).toFixed(2);
            $('#ad_amount').val(ad_amount);
        });
        
        $('input[type=radio][name=lott_complete]').change(function() {
            var line_items_index = $('#line_items_index').val();
            if(line_items_index != ''){
                show_notify('First Please Click on Add Row Button to edit curruent Lineitem.', false);
                if($(this).val() == 1){
                    $('input[type=radio][name=lott_complete][value=0]').prop('checked', true);
                } else {
                    $('input[type=radio][name=lott_complete][value=1]').prop('checked', true);
                }
                return false
            } else {
                if (lineitem_objectdata == '') {
                    show_notify("Please Add Item.", false);
                    if($(this).val() == 1){
                        $('input[type=radio][name=lott_complete][value=0]').prop('checked', true);
                    } else {
                        $('input[type=radio][name=lott_complete][value=1]').prop('checked', true);
                    }
                    return false;
                }
                var total_receive_fw_weight = $('#total_receive_fw_weight').html();
                if (total_receive_fw_weight == '0.000') {
                    show_notify("Please Add RFW Item.", false);
                    if($(this).val() == 1){
                        $('input[type=radio][name=lott_complete][value=0]').prop('checked', true);
                    } else {
                        $('input[type=radio][name=lott_complete][value=1]').prop('checked', true);
                    }
                    return false;
                }
                if($(this).val() == 0){
                    
                    if(lott_complete_yes_no == 'Yes'){
                        <?php if(isset($mhm_data->lott_complete) && $mhm_data->lott_complete == '1' && $mhm_data->audit_status == '2'){ ?>
                            show_notify("This MHM is Audited.", false);
                            if($(this).val() == 1){
                                $('input[type=radio][name=lott_complete][value=0]').prop('checked', true);
                            } else {
                                $('input[type=radio][name=lott_complete][value=1]').prop('checked', true);
                            }
                            return false;
                        <?php } ?>
                    }
                    
                    $('#operation_id').removeAttr('disabled','disabled');
                    $('#after_disabled_operation_id').remove();
                    $('#worker_id').removeAttr('disabled','disabled');
                    $('#after_disabled_worker_id').remove();
                    
                    var operation_id = $('#operation_id').val();
                    if(operation_id == <?php echo MANUFACTURE_HM_OPERATION_NANG_SETTING_ID; ?>){
                        
                    } else if(operation_id == <?php echo MANUFACTURE_HM_OPERATION_MEENA_ID; ?>){
                        $('#total_meena_charges_for_journal').val('');
                        $('.meena_charges_amount').html('');
                    } else {
                        $('#mhm_diffrence').val('');
                        $('#mhm_diffrence_value').html('');
                        $('#mhm_diffrence_calculation').html('');
                        $('#mhm_diffrence_amount').html('');
                        $('#worker_gold_rate').val('');
                        $('.lott_complete_div').addClass('hide');
                    }
                    $(".add_lineitem").removeClass('hide');
                    $(".edit_mhm_item").removeClass('hide');
                    $(".delete_mhm_item").removeClass('hide');
                    
                    $('.audit_status_div').addClass('hide');
                    
                } else {
                    
                    var operation_id = $('#operation_id').val();
                    $('#operation_id').attr('disabled','disabled');
                    $('#operation_id').closest('div').append('<input type="hidden" name="operation_id" id="after_disabled_operation_id" value="' + operation_id + '" />');
                    var worker_id = $('#worker_id').val();
                    $('#worker_id').attr('disabled','disabled');
                    $('#worker_id').closest('div').append('<input type="hidden" name="worker_id" id="after_disabled_worker_id" value="' + worker_id + '" />');
                    
                    $(".add_lineitem").addClass('hide');
                    $(".edit_mhm_item").addClass('hide');
                    $(".delete_mhm_item").addClass('hide');
                    
                    if(operation_id == <?php echo MANUFACTURE_HM_OPERATION_NANG_SETTING_ID; ?>){
                        
                    } else if(operation_id == <?php echo MANUFACTURE_HM_OPERATION_MEENA_ID; ?>){
                        var total_weight = $('#total_weight').html() || 0;
                        if(parseFloat(total_weight) >= 0){
                            show_notify('Only Receive Weight >= Issue Weight Allowed', false);
                            return false;
                        } else {
                            set_total_meena_charges_for_journal();
                        }
                    } else {
                        
                        var worker_gold_rate = '<?php echo $worker_gold_rate; ?>';
                        $('#worker_gold_rate').val(worker_gold_rate);
                        <?php if (isset($mhm_data->operation_id)) { if( $mhm_data->operation_id == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID || $mhm_data->operation_id == MANUFACTURE_HM_OPERATION_MEENA_ID){ ?>
                            $('.lott_complete_div').addClass('hide');
                        <?php } } else { ?>
                            $('.lott_complete_div').removeClass('hide');
                        <?php } ?>
                        
                        var total_issue_finish_work_weight = 0;
                        var total_issue_finish_work_less = 0;
                        var total_issue_finish_work_net_wt = 0;
                        var total_issue_finish_work_fine = 0;
                        var total_issue_finish_work_tunch = 0;
                        var total_issue_finish_work_pcs = 0;
                        var total_receive_finish_work_weight = 0;
                        var total_receive_finish_work_less = 0;
                        var total_receive_finish_work_net_wt = 0;
                        var total_receive_finish_work_fine = 0;
                        var total_receive_finish_work_tunch = 0;
                        var total_receive_finish_work_pcs = 0;
                        $.each(lineitem_objectdata, function (index, value) {
                            var weight = parseFloat(value.weight) || 0;
                            var less = parseFloat(value.less) || 0;
                            var net_wt = parseFloat(value.net_wt) || 0;
                            var fine = parseFloat(value.fine) || 0;
                            var pcs = parseFloat(value.pcs) || 0;
                            if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID; ?>'){
                                total_issue_finish_work_weight = parseFloat(total_issue_finish_work_weight) + parseFloat(weight);
                                total_issue_finish_work_less = parseFloat(total_issue_finish_work_less) + parseFloat(less);
                                total_issue_finish_work_net_wt = parseFloat(total_issue_finish_work_net_wt) + parseFloat(net_wt);
                                total_issue_finish_work_fine = parseFloat(total_issue_finish_work_fine) + parseFloat(fine);
                                total_issue_finish_work_pcs = parseFloat(total_issue_finish_work_pcs) + parseFloat(pcs);
                            }
                            if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID; ?>'){
                                total_receive_finish_work_weight = parseFloat(total_receive_finish_work_weight) + parseFloat(weight);
                                total_receive_finish_work_less = parseFloat(total_receive_finish_work_less) + parseFloat(less);
                                total_receive_finish_work_net_wt = parseFloat(total_receive_finish_work_net_wt) + parseFloat(net_wt);
                                total_receive_finish_work_fine = parseFloat(total_receive_finish_work_fine) + parseFloat(fine);
                                total_receive_finish_work_pcs = parseFloat(total_receive_finish_work_pcs) + parseFloat(pcs);
                            }
                        });
                        total_issue_finish_work_tunch = (parseFloat(total_issue_finish_work_fine) / parseFloat(total_issue_finish_work_net_wt)) * 100;
                        total_issue_finish_work_tunch = total_issue_finish_work_tunch || 0;
                        total_issue_finish_work_tunch = total_issue_finish_work_tunch.toFixed(2);

                        total_receive_finish_work_tunch = (parseFloat(total_receive_finish_work_fine) / parseFloat(total_receive_finish_work_net_wt)) * 100;
                        total_receive_finish_work_tunch = total_receive_finish_work_tunch || 0;
                        total_receive_finish_work_tunch = total_receive_finish_work_tunch.toFixed(2);

                        var total_finish_work_weight = parseFloat(total_receive_finish_work_weight) - parseFloat(total_issue_finish_work_weight);
                        total_finish_work_weight = total_finish_work_weight.toFixed(3);
                        total_issue_finish_work_weight = total_issue_finish_work_weight.toFixed(3);

                        var balance_weight_total_weight = $('#total_weight').html() || 0;
                        var total_issue = $('#total_issue_fine').html() || 0;
                        var total_receive = $('#total_receive_fine').html() || 0;
                        var balance_fine = parseFloat(total_issue) - parseFloat(total_receive);
                        balance_fine = balance_fine.toFixed(3);

                        var operation_id = $("#operation_id").val();
                        $.ajax({
                            url: "<?php echo base_url('manu_hand_made/get_operation_detail'); ?>/" + operation_id,
                            type: "GET",
                            async: false,
                            data: "",
                            success: function (response) {
                                var json = $.parseJSON(response);
                                var mhm_diffrence = 0;
                                var mhm_diffrence_calculation = '';
                                if(json.fix_loss == '1'){ // Fix loss No

                                    var receive_finish_work_weight_loss = parseFloat(total_finish_work_weight) * parseFloat(json.fix_loss_per) / 100;
                                    receive_finish_work_weight_loss = round(receive_finish_work_weight_loss, 2).toFixed(3);
                                    mhm_diffrence_calculation += 'Fix Loss % = ' + json.fix_loss_per + ', ';
                                    mhm_diffrence_calculation += parseFloat(total_finish_work_weight);
                                    mhm_diffrence_calculation += ' *';
                                    mhm_diffrence_calculation += ' ' + parseFloat(json.fix_loss_per);
                                    mhm_diffrence_calculation += ' / 100';
                                    mhm_diffrence_calculation += ' = ' + receive_finish_work_weight_loss + '<br>';

                                    json.issue_finish_fix_loss_per = json.issue_finish_fix_loss_per || 0;
                                    var issue_finish_work_weight_loss = parseFloat(total_issue_finish_work_weight) * parseFloat(json.issue_finish_fix_loss_per) / 100;
                                    issue_finish_work_weight_loss = round(issue_finish_work_weight_loss, 2).toFixed(3);
                                    mhm_diffrence_calculation += 'Issue Finish Fix Loss % = ' + json.issue_finish_fix_loss_per + ', ';
                                    mhm_diffrence_calculation += parseFloat(total_issue_finish_work_weight);
                                    mhm_diffrence_calculation += ' *';
                                    mhm_diffrence_calculation += ' ' + parseFloat(json.issue_finish_fix_loss_per);
                                    mhm_diffrence_calculation += ' / 100';
                                    mhm_diffrence_calculation += ' = ' + issue_finish_work_weight_loss + '<br>';

                                    receive_finish_work_weight_loss = parseFloat(receive_finish_work_weight_loss) + parseFloat(issue_finish_work_weight_loss);
                                    receive_finish_work_weight_loss = round(receive_finish_work_weight_loss, 2).toFixed(3);

                                    if(json.fix_loss == '1' && json.max_loss_allow != '1'){ // Fix loss Yes and Max loss Allow No
        //                                No Need to write here any code
                                    } else if(json.fix_loss == '1' && json.max_loss_allow == '1'){ // Fix loss Yes and Max loss Allow Yes
                                        var json_max_loss_wt = parseFloat(json.max_loss_wt) * parseFloat(total_receive_finish_work_pcs); // 1 pcs max allow loss * total receive finish work pcs
                                        json_max_loss_wt = round(json_max_loss_wt, 2).toFixed(3);
                                        mhm_diffrence_calculation += ' ' + parseFloat(json.max_loss_wt) + ' (Max Loss wt) * ' + parseFloat(total_receive_finish_work_pcs) + ' (Pcs) = ' + json_max_loss_wt + '<br>';
                                        if(parseFloat(json_max_loss_wt) < parseFloat(receive_finish_work_weight_loss)){
                                            receive_finish_work_weight_loss = parseFloat(json_max_loss_wt);
                                        }
                                    }

                                    var rfw_bw = parseFloat(receive_finish_work_weight_loss) - parseFloat(balance_weight_total_weight);
                                    rfw_bw = round(rfw_bw, 2).toFixed(3);
                                    <?php if($manufacture_lott_complete_in == '1'){ ?>
                                        mhm_diffrence_calculation += ' ' + parseFloat(receive_finish_work_weight_loss) +' - '+ parseFloat(balance_weight_total_weight) +' = ' + rfw_bw + ', ';
                                        mhm_diffrence = round(rfw_bw, 2).toFixed(3);
                                    <?php } else { ?>
                                        var receive_finish_work_weight_loss_fine = parseFloat(rfw_bw) * parseFloat(total_receive_finish_work_tunch) / 100;
                                        receive_finish_work_weight_loss_fine = round(receive_finish_work_weight_loss_fine, 2).toFixed(3);
                                        mhm_diffrence_calculation += ' ' + parseFloat(receive_finish_work_weight_loss) +' - '+ parseFloat(balance_weight_total_weight) +' = ' + rfw_bw + ', ' + rfw_bw;
                                        mhm_diffrence_calculation += ' *';
                                        mhm_diffrence_calculation += ' ' + parseFloat(total_receive_finish_work_tunch);
                                        mhm_diffrence_calculation += ' / 100';
                                        mhm_diffrence_calculation += ' = ' + receive_finish_work_weight_loss_fine;

                                        var mhm_diffrence = parseFloat(receive_finish_work_weight_loss_fine);
    //                                    var mhm_diffrence = parseFloat(receive_finish_work_weight_loss_fine) - parseFloat(balance_fine);
                                        mhm_diffrence = round(mhm_diffrence, 2).toFixed(3);
                                    <?php } ?>
//                                    mhm_diffrence_calculation += ' ' + parseFloat(receive_finish_work_weight_loss_fine);
//                                    mhm_diffrence_calculation += ' -';
//                                    mhm_diffrence_calculation += ' ' + parseFloat(balance_fine);
//                                    mhm_diffrence_calculation += ' = ' + mhm_diffrence;

                                } else {
                                    mhm_diffrence_calculation += 'Fix Loss = No';
                                }
                                $("#mhm_diffrence").val(mhm_diffrence);
                                $("#mhm_diffrence_value").html(mhm_diffrence);
                                $("#mhm_diffrence_calculation").html(mhm_diffrence_calculation);
                                var mhm_diffrence_amount = parseFloat(mhm_diffrence) * parseFloat(worker_gold_rate) / 10;
                                mhm_diffrence_amount = round(mhm_diffrence_amount, 0).toFixed(2);
                                $("#mhm_diffrence_amount").html(mhm_diffrence_amount);
                                $('.lott_complete_div').removeClass('hide');
                            },
                        });
                    }
                    $('.audit_status_div').removeClass('hide');
                }
            }
        });
        
        <?php if((isset($mhm_data->lott_complete)) && $mhm_data->lott_complete == 1){ ?>
            $('#worker_gold_rate').val('<?php echo $worker_gold_rate; ?>');
            <?php if (isset($mhm_data->operation_id)) { if( $mhm_data->operation_id == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID || $mhm_data->operation_id == MANUFACTURE_HM_OPERATION_MEENA_ID){ ?>
                $('.lott_complete_div').addClass('hide');
            <?php } } else { ?>
                $('.lott_complete_div').removeClass('hide');
            <?php } ?>
            $(".add_lineitem").addClass('hide');
            $(".edit_mhm_item").addClass('hide');
            $(".delete_mhm_item").addClass('hide');
        <?php } ?>
        
        $(document).on('keyup change', '#worker_gold_rate', function(){
            var mhm_diffrence = $("#mhm_diffrence").val();
            var worker_gold_rate = $("#worker_gold_rate").val();
            var mhm_diffrence_amount = parseFloat(mhm_diffrence) * parseFloat(worker_gold_rate) / 10;
            mhm_diffrence_amount = round(mhm_diffrence_amount, 2).toFixed(3);
            $("#mhm_diffrence_amount").html(mhm_diffrence_amount);
        });
        
        $(document).on('click', '#order_items_btn', function(){
            var department_id = $('#department_id').val();
            var mhm_id = '';
            <?php if (isset($mhm_data->mhm_id) && !empty($mhm_data->mhm_id)) { ?>
                mhm_id = '<?php echo $mhm_data->mhm_id; ?>';
            <?php } ?>
            if(department_id == '' || department_id == null){
                show_notify('Please Select Department.', false);
//                $("#department_id").select2('open');
                return false
            } else {
                $.ajax({
                    url: "<?php echo base_url('manu_hand_made/new_order_item_datatable'); ?>/" + department_id + '/' + mhm_id,
                    type: "GET",
                    async: false,
                    data: "",
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $('#order_items_selection_popup').modal('show');
                        if (json['order_items'] != '') {
                            var order_items_objectdata = json['order_items'];
                            var row_html_order = '';
                            $.each(order_items_objectdata, function (li_index, li_value) {
                                var is_checked = '';
                                var value_mhm_oi_id = '';
                                var value_mhm_id = '';
                                $.each(checked_order_items, function (it_index, it_value) {
                                    if(it_value.order_lot_item_id == li_value.order_lot_item_id){
                                        is_checked = 'checked';
                                        if(typeof(it_value.mhm_oi_id) !== "undefined" && it_value.mhm_oi_id !== null) {
                                            value_mhm_oi_id = it_value.mhm_oi_id;
                                        }
                                        if(typeof(it_value.mhm_id) !== "undefined" && it_value.mhm_id !== null) {
                                            value_mhm_id = it_value.mhm_id;
                                        }
                                        return false; // breaks
                                    }
                                });
                                row_html_order += '<tr class="items_row">';
                                row_html_order += '<td class="text-center">' +
                                '<input type="checkbox" data-mhm_oi_id="' + value_mhm_oi_id + '" data-mhm_id="' + value_mhm_id + '" data-order_id="' + li_value.order_id + '" data-order_lot_item_id="' + li_value.order_lot_item_id + '"  class="checkbox_ch" '+is_checked+' style="height:20px; width:20px"></td>';
                                row_html_order += '<td class="">' + li_value.item_name + '</td>';
                                row_html_order += '<td class="">' + li_value.category_name + '</td>';
                                row_html_order += '<td class="">' + li_value.account_name + '</td>';
                                row_html_order += '<td class="">' + li_value.order_no + '</td>';
                                row_html_order += '<td class="">' + li_value.order_date + '</td>';
                                row_html_order += '<td class="">' + li_value.delivery_date + '</td>';
                                row_html_order += '<td class="text-right">' + li_value.purity + '</td>';
                                row_html_order += '<td class="text-right">' + li_value.weight + '</td>';
                                row_html_order += '<td class="text-right">' + li_value.pcs + '</td>';
                                row_html_order += '</tr>';
                            });
                            row_html_order += '<tr class="items_row">';
                                row_html_order += '<td class=""><b>Total</b></td>';
                                row_html_order += '<td class="">&nbsp;</td>';
                                row_html_order += '<td class="">&nbsp;</td>';
                                row_html_order += '<td class="">&nbsp;</td>';
                                row_html_order += '<td class="">&nbsp;</td>';
                                row_html_order += '<td class="">&nbsp;</td>';
                                row_html_order += '<td class="">&nbsp;</td>';
                                row_html_order += '<td class="text-right">&nbsp;</td>';
                                row_html_order += '<td class="text-right"><b>' + json['total_weight'] + '</b></td>';
                                row_html_order += '<td class="text-right"><b>' + json['total_pcs'] + '</b></td>';
                                row_html_order += '</tr>';
                            $("#order_items_selection_list").html(row_html_order);
                        } else {
                            $("#order_items_selection_list").html('');
                        }
                    }
                });
            }
        });
        
        $(document).on('click', '#select_all_items', function () {
            if($(this).prop('checked') == true){
                $(".checkbox_ch").each(function(){
                    $(this).prop("checked", true);
                });
            } else {
                $(".checkbox_ch").each(function(){
                    $(this).prop("checked", false);

                });
            }
        });
        
        $(document).on('click', '#items_save_btn', function () {
            checked_order_items = [];
            $(".checkbox_ch").each(function(){
                if($(this).prop('checked') == true){
                    var ch_arr = {};
                    ch_arr['mhm_oi_id'] = $(this).attr('data-mhm_oi_id');
                    ch_arr['mhm_id'] = $(this).attr('data-mhm_id');
                    ch_arr['order_id'] = $(this).attr('data-order_id');
                    ch_arr['order_lot_item_id'] = $(this).attr('data-order_lot_item_id');
                    checked_order_items.push(ch_arr);
                }
            });
            console.log(checked_order_items);
            $('#order_items_selection_popup').modal('hide');
        });
        
        $('#purchase_item_selection_popup').on('hidden.bs.modal', function () {
            $("#item_id").val(null).trigger("change");
            $(".delete_mhm_item").removeClass('hide');
        });
        
        if ($('#datepicker2').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker2').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: "today",
                maxDate: 0,
            });
        }
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            endDate: "today",
            maxDate: 0,
        });
        
        $(document).on('change', '#department_id', function () {
            pts_lineitem_objectdata = [];
            display_pts_lineitem_html(pts_lineitem_objectdata);
        });
        
        $(document).on('change', '#type_id', function () {
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                $('#type_id').val(null).trigger('change.select2');
                return false;
            }
            if ($.trim($("#worker_id").val()) == '') {
                show_notify('Please Select Worker Name.', false);
                $("#worker_id").select2('open');
                $('#type_id').val(null).trigger('change.select2');
                return false;
            }
            var type_id = $("#type_id").val()
            if(type_id == <?php echo MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID; ?>){
                $("#pcs").val('1');
            } else {
                $("#pcs").val(0);
            }
        });
        
        $(document).on('change', '#item_id', function () {
            $("#weight").val('');
            $("#less").val('');
            $("#net_wt").val('');
            $("#touch_data_id").val('');
            $("#touch_id").val(null).trigger("change");
            $("#fine").val('');
            $("#ad_weight").val('');
            $('#including_ad_wt').prop('checked', false);
            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();
            var todays_date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
//            $('#mhm_detail_date').val(todays_date).trigger('change');
            
            var item_id = $('#item_id').val();
//            alert(item_id);
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
                            $("#weight").focus();
                            $(document).on('change', '#weight', function () {
                                $('#touch_id').focus();
                            });
                        } else {
                            $('#less').removeAttr('readonly', 'readonly');
                            $("#weight").focus();
                            $(document).on('change', '#weight', function () {
                                $('#less').focus();
                            });
                        }
                        
                        if (json.stock_method == <?php echo STOCK_METHOD_ITEM_WISE; ?>) {
//                            alert();
                            var type_id = $('#type_id').val();
                            
                            if(type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID; ?>' || type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID; ?>'){
                                var department_id = $('#department_id').val();
                                var mhm_id = '';
                                <?php if (isset($mhm_data->mhm_id) && !empty($mhm_data->mhm_id)) { ?>
                                    mhm_id = '<?php echo $mhm_data->mhm_id; ?>';
                                <?php } ?>
                                $.ajax({
                                    url: "<?php echo base_url('sell/get_purchase_to_sell_pending_item'); ?>/",
                                    type: 'POST',
                                    async: false,
                                    data: {department_id : department_id, item_id : item_id, mhm_id : mhm_id, do_not_count_wstg : 1},
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
                    }
                });
            }
        });
        
        $(document).on('keyup change', '#weight, #less', function () {
            var weight = parseFloat($('#weight').val()) || 0;
            weight = round(weight, 2).toFixed(3);
            var less = parseFloat($('#less').val()) || 0;
            less = round(less, 2).toFixed(3);
            var net_wt = 0;
            net_wt = parseFloat(weight) - parseFloat(less);
            net_wt = round(net_wt, 2).toFixed(3);
            $('#net_wt').val(net_wt);
        });

        $(document).bind('keyup change', '#net_wt, .touch_id', function () {
            var net_wt = parseFloat($('#net_wt').val()) || 0;
            net_wt = round(net_wt, 2).toFixed(3);
            if($('#tunch_textbox').prop("checked") == true){
                var touch = parseFloat($('#touch_data_id').val()) || 0;
            } else {
                var touch = parseFloat($('#touch_id').val()) || 0;
            }
            var fine = 0;
            fine = parseFloat(net_wt) * (parseFloat(touch)) / 100;
            fine = round(fine, 2).toFixed(3);
            $('#fine').val(fine);
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
//            var pts_wstg= new Array();
//            $("input[name='pts_wstg[]']").each(function(){
//                pts_wstg.push($(this).val());
//            });
            if ($("input.pts_selected_index:checked").length == 0) {
                show_notify('Please select at least one item.', false);
                return false;
            }
            var pts_selected_index_lineitems = [];
            var pts_item_id = '';
            var sell_allow = 1;
            var type_id = $('#type_id').val();

            $.each($("input.pts_selected_index:checked"), function() {
                pts_item_id = $(this).data('item_id');
                var pts_selected_index = $(this).data('pts_selected_index');
                pts_lineitem_objectdata[pts_selected_index].mhm_item_delete = 'allow';
                pts_lineitem_objectdata[pts_selected_index].grwt = pts_grwt[pts_selected_index] || 0;
                pts_lineitem_objectdata[pts_selected_index].weight = pts_grwt[pts_selected_index] || 0;
                pts_lineitem_objectdata[pts_selected_index].less = pts_less[pts_selected_index] || 0;
//                pts_lineitem_objectdata[pts_selected_index].wstg = pts_wstg[pts_selected_index];
                pts_lineitem_objectdata[pts_selected_index].purity = pts_lineitem_objectdata[pts_selected_index].touch_id;
                pts_lineitem_objectdata[pts_selected_index].net_wt = parseFloat(pts_lineitem_objectdata[pts_selected_index].grwt || 0) - parseFloat(pts_lineitem_objectdata[pts_selected_index].less || 0);
                pts_lineitem_objectdata[pts_selected_index].net_wt = round(pts_lineitem_objectdata[pts_selected_index].net_wt, 2).toFixed(3);
                pts_lineitem_objectdata[pts_selected_index].fine = parseFloat(pts_lineitem_objectdata[pts_selected_index].net_wt) * (parseFloat(pts_lineitem_objectdata[pts_selected_index].touch_id)) / 100;
                pts_lineitem_objectdata[pts_selected_index].fine = round(pts_lineitem_objectdata[pts_selected_index].fine, 2).toFixed(3);
                pts_lineitem_objectdata[pts_selected_index].actual_tunch = 0;
                pts_lineitem_objectdata[pts_selected_index].pcs = 0;
                pts_lineitem_objectdata[pts_selected_index].ad_weight = 0;
                var d = new Date();

                var month = d.getMonth()+1;
                var day = d.getDate();

                var todays_date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
            
                pts_lineitem_objectdata[pts_selected_index].mhm_detail_date = todays_date;
                pts_lineitem_objectdata[pts_selected_index].mhm_detail_remark = '';
                pts_lineitem_objectdata[pts_selected_index].type_id = type_id;
                
                /******* You not allow to Issue, The Receive of same Entry! Start *******/
                $.each(lineitem_objectdata, function(index, value) {
                    if(typeof(pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id) !== "undefined" && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id !== null && 
                            typeof(value.sell_item_id) !== "undefined" && value.sell_item_id !== null && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id == value.sell_item_id &&
                            (pts_lineitem_objectdata[pts_selected_index].stock_type == <?php echo STOCK_TYPE_MHM_RECEIVE_FINISH_ID; ?> || pts_lineitem_objectdata[pts_selected_index].stock_type == <?php echo STOCK_TYPE_MHM_RECEIVE_SCRAP_ID; ?>)) {
                        show_notify('You not allow to Issue, The Receive of same Entry!', false);
                        sell_allow = 0;
                        return false;
                    }
                });
                if(sell_allow == 1){
                    pts_selected_index_lineitems.push(pts_lineitem_objectdata[pts_selected_index]);
                }
                /******* You not allow to Issue, The Receive of same Entry! End *******/
                
            });
//            lineitem_delete = [];
//            jQuery.each(lineitem_objectdata, function(obj, values) {
//                lineitem_delete.push(values.mhm_detail_id);
//            });
//            pts_delete = [];
//            jQuery.each(pts_selected_index_lineitems, function(obj, values) {
//                pts_delete.push(values.mhm_detail_id);
//            });
//            var uncheck_sell_item = $(lineitem_delete).not(pts_delete).get();
//            
//            $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + uncheck_sell_item + '" />');
                
            $('#purchase_item_selection_popup').modal('hide');
            
            var remove_arr = [];
            $.each(lineitem_objectdata, function(index, value) {
                if(typeof(value.item_id) !== "undefined" && value.item_id !== null && value.item_id == pts_item_id && (value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID; ?>')) {
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
//            lineitem_objectdata = pts_selected_index_lineitems;
            lineitem_objectdata = $.merge(pts_selected_index_lineitems, lineitem_objectdata);
            display_lineitem_html(lineitem_objectdata);
            $('#select_all_purchase_to_sell').prop('checked', false);
            $('#type_id').val(null).trigger("change");
            $("#item_id").val(null).trigger("change");
            $("#weight").val('');
            $("#less").val('');
            $('#less').removeAttr('readonly', 'readonly');
            $("#net_wt").val('');
            $("#touch_id").val(null).trigger("change");
            $("#touch_data_id").val('');
            $("#fine").val('');
            $("#ad_weight").val('');
            $('#including_ad_wt').prop('checked', false);
            $("#line_items_index").val('');
        });
        
        $("input.pts_selected_index:checked").click(function(){
            $("input.pts_selected_index").prop("checked", false);
        });
        
        $(document).on('keyup change', '.pts_grwt, .pts_less', function () {
            var pts_selected_index = $(this).data('pts_selected_index');
            var pts_grwt = $('#pts_grwt_' + pts_selected_index).val();
            var pts_less = $('#pts_less_' + pts_selected_index).val();
//            var pts_wstg = $('#pts_wstg_' + pts_selected_index).val();
//            pts_lineitem_objectdata[pts_selected_index].grwt = pts_grwt;
            var pe_stock_grwt = pts_lineitem_objectdata[pts_selected_index].grwt;
//            console.log(pts_grwt + ' ' + pe_stock_grwt);
            <?php // if($without_purchase_sell_allow == '1'){ ?>
            if(parseFloat(pts_grwt) > parseFloat(pe_stock_grwt)){
                show_notify('Please Enter Weight Less than of ' + pe_stock_grwt, false);
                $(this).val(pe_stock_grwt).keyup();
                return false;
            }
            <?php // } ?>
            pts_lineitem_objectdata[pts_selected_index].net_wt = parseFloat(pts_grwt || 0) - parseFloat(pts_less || 0);
            pts_lineitem_objectdata[pts_selected_index].net_wt = round(pts_lineitem_objectdata[pts_selected_index].net_wt, 2).toFixed(3);
            pts_lineitem_objectdata[pts_selected_index].fine = parseFloat(pts_lineitem_objectdata[pts_selected_index].net_wt) * (parseFloat(pts_lineitem_objectdata[pts_selected_index].touch_id)) / 100;
            pts_lineitem_objectdata[pts_selected_index].fine = round(pts_lineitem_objectdata[pts_selected_index].fine, 2).toFixed(3);
            $('#pts_net_wt_' + pts_selected_index).html(pts_lineitem_objectdata[pts_selected_index].net_wt);
            $('#pts_fine_' + pts_selected_index).html(pts_lineitem_objectdata[pts_selected_index].fine);
            checked_average_value();
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
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                e.preventDefault();
                if(module_submit_flag == 0 ){
                    $("#save_manu_hand_made").submit();
                    return false;
                }
            }
        });

        $(document).on('submit', '#save_manu_hand_made', function () {
            $(window).unbind('beforeunload');
            var department_id = $('#department_id').val();
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                return false;
            }
            if ($.trim($("#operation_id").val()) == '') {
                show_notify('Please Select Operation.', false);
                $("#operation_id").select2('open');
                return false;
            }
            if ($.trim($("#worker_id").val()) == '') {
                show_notify('Please Select Worker Name.', false);
                $("#worker_id").select2('open');
                return false;
            }
            var datepicker2 = $("#datepicker2").val();
            if(datepicker2 == '' ){
                show_notify('Please Select Manufacture Date.', false);
                $("#datepicker2").focus();
                return false;
            }

            if (lineitem_objectdata == '') {
                show_notify("Please Add Item.", false);
                return false;
            }
            
            var lott_complete_val = $('input[type=radio][name=lott_complete]:checked').val();
            if(lott_complete_val == 1){
                var operation_id = $('#operation_id').val();
                if(operation_id == <?php echo MANUFACTURE_HM_OPERATION_NANG_SETTING_ID; ?>){
                    var total_net_wt = $('#total_net_wt').html();
                    var auto_count_chhijat_value = $('#auto_count_chhijat_value').val();
                    if(parseFloat(total_net_wt) != parseFloat(auto_count_chhijat_value)){
                        if (confirm('Balance Net Wt != Chhijjat !!!')) {
                        } else {
                            return false;
                        }
                    }
                } else if(operation_id == <?php echo MANUFACTURE_HM_OPERATION_MEENA_ID; ?>){
                    var total_weight = $('#total_weight').html();
                    if(parseFloat(total_weight) >= 0){
                        show_notify('Only Receive Weight >= Issue Weight Allowed', false);
                        return false;
                    }
                }
            }
            
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var checked_order_items_objectdata_stringify = JSON.stringify(checked_order_items);
            postData.append('checked_order_items', checked_order_items_objectdata_stringify);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            var ad_lineitem_objectdata_stringify = JSON.stringify(ad_lineitem_objectdata);
            postData.append('ad_lineitem_data', ad_lineitem_objectdata_stringify);
            $.ajax({
                url: "<?= base_url('manu_hand_made/save_manu_hand_made') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                async: false,
                success: function (response) {
                    $('.changed-input').removeClass('changed-input');
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Something went Wrong') {
                        $("#ajax-loader").hide();
                        show_notify('Something went Wrong! Please Refresh page and Go ahead.', false);
                    } else if (json['success'] == 'Added') {
                        <?php //if($manufacture_lott_complete_in == '3'){ ?>
                            window.location.href = "<?php echo base_url('manu_hand_made/manu_hand_made_list') ?>";
                        <?php /* } else { ?>
                            var checked_items = new Array();
                            checked_items.push(json['mhm_id']);
                            var worker_id = $('#worker_id').val();
                            var department_id = $('#department_id').val();
                            var hisab_date = $("#datepicker2").val();
                            var fine = $("#mhm_diffrence").val();
                            var is_module = '<?php echo HISAB_DONE_IS_MODULE_MHM; ?>';
                            $.ajax({
                                url: "<?php echo base_url('manufacture/save_worker_hisab_details') ?>/",
                                type: "POST",
                                data: {is_module: is_module, checked_items: checked_items, hisab_date: hisab_date, fine: fine, worker_id: worker_id, department_id: department_id},
                                success: function (response) {
                                    $('#ajax-loader').hide();
                                    var json = $.parseJSON(response);
                                    if (json['success'] == 'Added') {
                                        window.location.href = "<?php echo base_url('manu_hand_made/manu_hand_made_list') ?>";
                                    } 
                                    return false;
                                }
                            });
                        <?php } */ ?>
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('manu_hand_made/manu_hand_made_list') ?>";
                    }
                    return false;
                },
            });
            module_submit_flag = 1;
            return false;
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
        
        $('#add_lineitem').on('click', function () {
            var type_id = $("#type_id").val();
            if (type_id == '' || type_id == null) {
                $("#type_id").select2('open');
                show_notify("Please select Type!", false);
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
            var weight = $("#weight").val();
            if (weight == '' || weight == null) {
                show_notify("Weight is required!", false);
                $("#weight").focus();
                return false;
            } else {
                var total_grwt_sell = $('#total_grwt_sell').val();
                <?php if($without_purchase_sell_allow == '1'){ ?>
                if(total_grwt_sell != '' && total_grwt_sell != null){
                    var grwt = parseFloat($('#weight').val()) || 0;
                    grwt = round(grwt, 2).toFixed(3);
                    if(parseFloat(grwt) < parseFloat(total_grwt_sell)){
                        show_notify("Weight Should Be Grater Than " + total_grwt_sell , false);
                        $('#weight').val('');
                        $("#weight").focus();
                        return false;
                    }
                }
                <?php } ?>
            }
            var fine = $("#fine").val();
            if (fine == '' || fine == null) {
                show_notify("Fine is required!", false);
                $("#fine").focus();
                return false;
            }
            
            if($("#mhm_detail_date").val() == '') {
                show_notify('Please Select Manufacture Hand Made Lineitem Date.', false);
                $("#mhm_detail_date").focus();
                return false;
            }

            $("#add_lineitem").attr('disabled', 'disabled');
            var mhm_detail_id = $("#mhm_detail_id").val();
            if (typeof (mhm_detail_id) !== "undefined" && mhm_detail_id !== null) {
                $('.line_item_form #deleted_lineitem_id[value="' + mhm_detail_id + '"]').remove();
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

            $('textarea[name^="line_items_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            
            if($('#including_ad_wt').prop("checked") == true){
                lineitem['including_ad_wt'] = '1';
            } else {
                lineitem['including_ad_wt'] = '0';
            }
            var item_id = $('#item_id').val();
            if($('#tunch_textbox').prop("checked") == true){
                var touch_id = $("#touch_data_id").val();
            } else {
                var touch_id = $('#touch_id').val();
            }
            var type_id = $('#type_id').val();
//            $('select[name^="line_items_data"]').each(function (index) {
//                key = $(this).attr('name');
//                key = key.replace("line_items_data[", "");
//                key = key.replace("]", "");
//                
//                $.each(lineitem_objectdata, function (index, value) {
//                    if (value.type_id == type_id && value.item_id == item_id && value.purity == touch_id && typeof (value.id) != "undefined" && value.id !== null) {
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
//                    } else if (value.type_id == type_id && value.item_id == item_id && value.purity == touch_id) {
//                        if(line_items_index !== index){
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
            if (is_validate != '1') {
                var type_data = $('#type_id option:selected').html();
                var item_data = $('#item_id option:selected').html();

                lineitem['type_name'] = type_data;
                lineitem['item_name'] = item_data;
                lineitem['total_grwt_sell'] = $('#total_grwt_sell').val();
                if($('#tunch_textbox').prop("checked") == true){
                    lineitem['purity'] = $('#touch_data_id').val();
                } else {
                    lineitem['purity'] = $('#touch_id').val();
                }
                if($('#tunch_textbox').prop("checked") == true){
                    lineitem['tunch_textbox'] = '1';
                } else {
                    lineitem['tunch_textbox'] = '0';
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
                        if(lineitem['group_name'] == '<?php echo CATEGORY_GROUP_GOLD_ID; ?>' || lineitem['group_name'] == '<?php echo CATEGORY_GROUP_SILVER_ID; ?>'){
                            lineitem['weight'] = round(lineitem['weight'], 2).toFixed(3);
                            lineitem['less'] = round(lineitem['less'], 2).toFixed(3);
                            lineitem['net_wt'] = round(lineitem['net_wt'], 2).toFixed(3);
                            lineitem['fine'] = round(lineitem['fine'], 2).toFixed(3);
                        } else if(lineitem['group_name'] == '<?php echo CATEGORY_GROUP_OTHER_ID; ?>'){
                            lineitem['weight'] = round(lineitem['weight'], 2).toFixed(3);
                            lineitem['less'] = 0;
                            lineitem['net_wt'] = round(lineitem['weight'], 2).toFixed(3);
                            lineitem['fine'] = 0;
                        }
                        
                        if(lineitem['group_name'] == '<?php echo CATEGORY_GROUP_OTHER_ID; ?>'){ // Tunch Zero for Cagetory Group : Other
                            $("#touch_data_id").val(0);
                            $("#touch_id").val(0);
                            lineitem['touch_id'] = 0;
                            lineitem['purity'] = 0;
                            lineitem['tunch_textbox'] = 1;
                        }
                
                        var type_id = $('#type_id').val();
                        if (type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID; ?>' || type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID; ?>'){
                            var mhm_detail_id = $("#mhm_detail_id").val();
                            var process_id = $("#department_id").val();
                            if($('#tunch_textbox').prop("checked") == true){
                                var touch_id = $("#touch_data_id").val();
                            } else {
                                var touch_id = $("#touch_id").val();
                            }
                            var category_id = get_category_from_item(item_id);
                            $.ajax({
                                url: "<?php echo base_url('sell/get_item_stock'); ?>/",
                                type: 'POST',
                                async: false,
                                data: {process_id : process_id, category_id : category_id, item_id : item_id, touch_id : touch_id, mhm_detail_id : mhm_detail_id},
                                success: function (response) {
                                    var json = $.parseJSON(response);
                                    <?php if($without_purchase_sell_allow == '1'){ ?>
                                        var grwt = parseFloat($('#weight').val()) || 0;
                                        if(parseFloat(grwt) > parseFloat(json.grwt)){
                                            show_notify('Please Enter Weight Less than of ' + json.grwt, false);
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
                                    $('#type_id').removeAttr('disabled','disabled');
                                    $('#item_id').removeAttr('disabled','disabled');
                                    $('#tunch_textbox').removeAttr('disabled','disabled');
                                    $('.touch_id').removeAttr('disabled','disabled');

                                    $("#tunch_textbox").prop("checked", true).trigger('change');
                    //                $("#tunch_textbox").prop("checked", false).trigger('change');
                                    $("#line_items_index").val('');
                                    line_items_index = '';
                                    $("#mhm_item_delete").val('allow');
                                    $('#mhm_detail_id').val('');
                                    $("#purchase_sell_item_id").val('');
                                    $("#stock_type").val('');
                                    $("#type_id").val(null).trigger("change");
                                    $("#item_id").val(null).trigger("change");
                                    $("#weight").val('');
                                    $("#less").val('');
                                    $("#net_wt").val('');
                                    $("#touch_id").val(null).trigger("change");
                                    $("#touch_data_id").val('');
                                    $("#actual_tunch").val('');
                                    $("#fine").val('');
                                    $("#pcs").val('');
                                    $("#ad_weight").val('');
                                    $('#including_ad_wt').prop('checked', false);
//                                    var d = new Date();
//                                    var curr_date = strpad00(d.getDate())+'-'+strpad00(d.getMonth()+1)+'-'+d.getFullYear();
                                    $("#mhm_detail_date").val('');
                                    $("#mhm_detail_date").datepicker('setDate', null);
                                    $("#mhm_detail_remark").val('');
                                    edit_lineitem_inc = 0;
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
                            $('#type_id').removeAttr('disabled','disabled');
                            $('#item_id').removeAttr('disabled','disabled');
                            $('#tunch_textbox').removeAttr('disabled','disabled');
                            $('.touch_id').removeAttr('disabled','disabled');

                            $("#tunch_textbox").prop("checked", true).trigger('change');
        //                    $("#tunch_textbox").prop("checked", false).trigger('change');
                            $("#line_items_index").val('');
                            line_items_index = '';
                            $("#mhm_item_delete").val('allow');
                            $('#mhm_detail_id').val('');
                            $("#purchase_sell_item_id").val('');
                            $("#stock_type").val('');
                            $("#type_id").val(null).trigger("change");
                            $("#item_id").val(null).trigger("change");
                            $("#weight").val('');
                            $("#less").val('');
                            $("#net_wt").val('');
                            $("#touch_id").val(null).trigger("change");
                            $("#touch_data_id").val('');
                            $("#actual_tunch").val('');
                            $("#fine").val('');
                            $("#pcs").val('');
                            $("#ad_weight").val('');
                            $('#including_ad_wt').prop('checked', false);
//                            var d = new Date();
//                            var curr_date = strpad00(d.getDate())+'-'+strpad00(d.getMonth()+1)+'-'+d.getFullYear();
                            $("#mhm_detail_date").val('');
                            $("#mhm_detail_date").datepicker('setDate', null);
                            $("#mhm_detail_remark").val('');
                            edit_lineitem_inc = 0;
                        }
                    }
                });
                $('#total_grwt_sell').val('');
            }
            $("#add_lineitem").removeAttr('disabled', 'disabled');
        });
        
        $('#add_ad_lineitem').on('click', function () {
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
            var ad_amount = $("#ad_amount").val();
            if (ad_amount == '' || ad_amount == null) {
                show_notify("Ad Amount is required!", false);
                $("#ad_amount").focus();
                return false;
            }

            $("#add_ad_lineitem").attr('disabled', 'disabled');
            var mhm_ad_id = $("#mhm_ad_id").val();
            if (typeof (mhm_ad_id) !== "undefined" && mhm_ad_id !== null) {
                $('.ad_lineitem_form #deleted_ad_lineitem_id[value="' + mhm_ad_id + '"]').remove();
            }
            var key = '';
            var value = '';
            var ad_lineitem = {};
            $('select[name^="ad_lineitem_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("ad_lineitem_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                ad_lineitem[key] = value;
            });

            $('input[name^="ad_lineitem_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("ad_lineitem_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                ad_lineitem[key] = value;
            });

            $('textarea[name^="line_items_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                ad_lineitem[key] = value;
            });
            
            var ad_data = $('#ad_id option:selected').html();
            ad_lineitem['ad_name'] = ad_data;
            var new_ad_lineitem = JSON.parse(JSON.stringify(ad_lineitem));
            ad_lineitem_index = $("#ad_lineitem_index").val();
            if (ad_lineitem_index != '') {
                ad_lineitem_objectdata.splice(ad_lineitem_index, 1, new_ad_lineitem);
            } else {
                ad_lineitem_objectdata.push(new_ad_lineitem);
            }
            display_ad_lineitem_html(ad_lineitem_objectdata);
            $("#ad_lineitem_index").val('');
            ad_lineitem_index = '';
            $('#ad_lineitem_delete').val('allow');
            $('#mhm_ad_id').val('');
            $("#ad_id").val(null).trigger("change");
            $("#ad_pcs").val('');
            $("#ad_rate").val('');
            $("#ad_amount").val('');
            edit_ad_lineitem_inc = 0;
            $("#add_ad_lineitem").removeAttr('disabled', 'disabled');
        });
        
        <?php if(isset($mhm_data->lott_complete) && $mhm_data->lott_complete == 1){ ?>
            $("input[type=radio][name=lott_complete][value=0]").click();
            $("input[type=radio][name=lott_complete][value=1]").click();
            lott_complete_yes_no = 'Yes';
        <?php } ?>
    
        $('#costing_report_model').on('shown.bs.modal', function () {
            var total_issue_tunch = $('#total_issue_tunch').html();
            $('#costing_total_issue_tunch').val(total_issue_tunch);
            
            var stone_charge_in_amount = $('#total_ad_amount').html();
            $('#stone_charge_in_amount').html(stone_charge_in_amount);
            var costing_total_rfw_wt = $('#total_receive_fw_weight').html();
            $('#costing_total_rfw_wt').html(costing_total_rfw_wt);
            var costing_total_ad_wt = $('#total_receive_ad_weight').html();
            $('#costing_total_ad_wt').html(costing_total_ad_wt);
            var costing_total_rfw_net_wt = $('#total_receive_fw_net_wt').html();
            $('#costing_total_rfw_net_wt').html(costing_total_rfw_net_wt);
            var costing_balance_net_wt = $('#total_net_wt').html();
            $('#costing_balance_net_wt').html(costing_balance_net_wt);
            
            var total_issue_fw_net_wt = $('#total_issue_fw_net_wt').html();
            $('#costing_total_issue_fw_net_wt').html(total_issue_fw_net_wt);
            var total_receive_s_net_wt = $('#total_receive_s_net_wt').html();
            $('#costing_total_receive_s_net_wt').html(total_receive_s_net_wt);
            var total_receive_s_fine = $('#total_receive_s_fine').html();
            $('#costing_total_receive_s_fine').html(total_receive_s_fine);
            $('#costing_wastage').focus();
            $('#costing_wastage').val('0');
            $('#costing_wastage').select();
            $('#costing_wastage').keyup();
        });
        $('#costing_report_model').on('hidden.bs.modal', function () {
            $('#costing_total_issue_tunch').val('');
            $('#costing_wastage').val('');
            $('#costing_total_issue_fw_fine').html('');
            $('#costing_stone_charge_in_gold').html('');
            $('#costing_total').html('');
            $('#cost_including_stone').html('');
            $('#cost_without_stone_without_chhijjat').html('');
            $('#cost_without_stone_with_chhijjat').html('');
        });
        $(document).on('keyup change', '#costing_wastage', function () {
            var costing_total_issue_tunch = parseFloat($('#costing_total_issue_tunch').val()) || 0;
            var costing_wastage = parseFloat($('#costing_wastage').val()) || 0;
            var costing_total_issue_fw_net_wt = parseFloat($('#costing_total_issue_fw_net_wt').html()) || 0;
            var costing_total_issue_fw_fine = parseFloat(costing_total_issue_fw_net_wt) * (parseFloat(costing_total_issue_tunch) + parseFloat(costing_wastage)) / 100;
            $('#costing_total_issue_fw_fine').html(costing_total_issue_fw_fine.toFixed(3));
            
            var total_ad_amount = parseFloat($('#total_ad_amount').html()) || 0;
            var gold_rate = <?php echo $gold_rate; ?> || 0;
            var costing_stone_charge_in_gold = parseFloat(total_ad_amount) / parseFloat(gold_rate) * 10;
            $('#costing_stone_charge_in_gold').html(costing_stone_charge_in_gold.toFixed(3));
            
            var costing_total_receive_s_fine = parseFloat($('#costing_total_receive_s_fine').html()) || 0;
            var costing_total = parseFloat(costing_total_issue_fw_fine) + parseFloat(costing_stone_charge_in_gold) - parseFloat(costing_total_receive_s_fine)
            $('#costing_total').html(costing_total.toFixed(3));
            
            var total_receive_fw_weight = parseFloat($('#total_receive_fw_weight').html()) || 0;
            var total_receive_ad_weight = parseFloat($('#total_receive_ad_weight').html()) || 0;
            var cost_including_stone = parseFloat(costing_total) / (parseFloat(total_receive_fw_weight) + parseFloat(total_receive_ad_weight)) * 100;
            $('#cost_including_stone').html(cost_including_stone.toFixed(3));
            
            
            var total_receive_fw_net_wt = parseFloat($('#total_receive_fw_net_wt').html()) || 0;
            var cost_without_stone_without_chhijjat = parseFloat(costing_total) / parseFloat(total_receive_fw_net_wt) * 100;
            $('#cost_without_stone_without_chhijjat').html(cost_without_stone_without_chhijjat.toFixed(3));
            
            var total_net_wt = parseFloat($('#total_net_wt').html()) || 0;
            var cost_without_stone_with_chhijjat = parseFloat(costing_total) / (parseFloat(total_receive_fw_net_wt) + parseFloat(total_net_wt)) * 100;
            $('#cost_without_stone_with_chhijjat').html(cost_without_stone_with_chhijjat.toFixed(3));
        });
    
    });
    
    function get_category_from_item(item_id){
        if(item_id != '' && item_id != null){
            var category_id = '';
            $.ajax({
                url: "<?php echo base_url('manu_hand_made/get_category_from_item'); ?>/",
                type: 'POST',
                async: false,
                data: {item_id : item_id},
                success: function (response) {
                    var json = $.parseJSON(response);
                    category_id = json.category_id;
                }
            });
            return category_id;
        }
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
//                        alert(li_value.purchase_sell_item_id);
//                        alert(value.purchase_sell_item_id);
                        if(li_value.purchase_sell_item_id == value.purchase_sell_item_id){
                            if(row_added == 0){
                                row_added = 1;
                                pts_lineitem_objectdata[index].mhm_detail_id = li_value.mhm_detail_id;
                                if(value.less_allow == 1){
                                    var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ li_value.less + '" style="width:150px;"> ' + value.less;
                                } else {
                                    var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ li_value.less + '" disabled="" style="width:150px;"> ' + value.less;
                                }
                                row_html_order += '<td class="text-center">' +
                                '<input type="checkbox" data-item_id="' + value.item_id + '" data-pts_selected_index="' + index + '" class="pts_selected_index" checked disabled>' +
                                '</td>' +
                                '<td>' + value.account_name + ' ['+ value.sell_date +']</td>' +
                                '<td>' + value.item_name + '</td>' +
                                '<td class="text-right"><input type="text" name="pts_grwt[]" id="pts_grwt_' + index + '" data-pts_selected_index="' + index + '" class="pts_grwt num_only" value="'+ li_value.grwt + '" style="width:150px;"> ' + value.grwt + ' </td>' +
                                '<td class="text-right">' + input_less + '</td>' +
                                '<td class="text-right" id="pts_net_wt_' + index + '">' + li_value.net_wt + '</td>' +
                                '<td class="text-right">' + value.touch_id + '</td>' +
//                                '<td class="text-right"><input type="text" name="pts_wstg[]" id="pts_wstg_' + index + '" data-pts_selected_index="' + index + '" class="pts_wstg num_only" value="'+ li_value.wstg + '" style="width:100px;"> ' + value.wstg + ' </td>' +
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
                            var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ value.less + '" style="width:150px;">';
                        } else {
                            var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ value.less + '" disabled="" style="width:150px;">';
                        }
                        row_html_order += '<td class="text-center">' +
                        '<input type="checkbox" data-item_id="' + value.item_id + '" data-pts_selected_index="' + index + '" class="pts_selected_index">' +
                        '</td>' +
                        '<td>' + value.account_name + ' ['+ value.sell_date +']</td>' +
                        '<td>' + value.item_name + '</td>' +
                        '<td class="text-right"><input type="text" name="pts_grwt[]" id="pts_grwt_' + index + '" data-pts_selected_index="' + index + '" class="pts_grwt num_only" value="'+ value.grwt + '" style="width:150px;"></td>' +
                        '<td class="text-right">' + input_less + '</td>' +
                        '<td class="text-right" id="pts_net_wt_' + index + '">' + value.net_wt + '</td>' +
                        '<td class="text-right">' + value.touch_id + '</td>' +
//                        '<td class="text-right"><input type="text" name="pts_wstg[]" id="pts_wstg_' + index + '" data-pts_selected_index="' + index + '" class="pts_wstg num_only" value="'+ value.wstg + '" style="width:100px;"></td>' +
                        '<td class="text-right" id="pts_fine_' + index + '">' + value.fine + '</td>';
                        pts_total_grwt = parseFloat(pts_total_grwt) + parseFloat(value.grwt);
                        pts_total_less = parseFloat(pts_total_less) + parseFloat(value.less);
                        pts_total_ntwt = parseFloat(pts_total_ntwt) + parseFloat(value.net_wt);
                        pts_total_fine = parseFloat(pts_total_fine) + parseFloat(value.fine);
                    }
                    
                } else {
                    if(value.less_allow == 1){
                        var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ value.less + '" style="width:150px;">';
                    } else {
                        var input_less = '<input type="text" name="pts_less[]" id="pts_less_' + index + '" data-pts_selected_index="' + index + '" class="pts_less num_only" value="'+ value.less + '" disabled="" style="width:150px;">';
                    }
                    row_html_order += '<td class="text-center">' +
                    '<input type="checkbox" data-item_id="' + value.item_id + '" data-pts_selected_index="' + index + '" class="pts_selected_index">' +
                    '</td>' +
                    '<td>' + value.account_name + ' ['+ value.sell_date +']</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right"><input type="text" name="pts_grwt[]" id="pts_grwt_' + index + '" data-pts_selected_index="' + index + '" class="pts_grwt num_only" value="'+ value.grwt + '" style="width:150px;"></td>' +
                    '<td class="text-right">' + input_less + '</td>' +
                    '<td class="text-right" id="pts_net_wt_' + index + '">' + value.net_wt + '</td>' +
                    '<td class="text-right">' + value.touch_id + '</td>' +
//                    '<td class="text-right"><input type="text" name="pts_wstg[]" id="pts_wstg_' + index + '" data-pts_selected_index="' + index + '" class="pts_wstg num_only" value="'+ value.wstg + '" style="width:100px;"></td>' +
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
        var new_issue_lineitem_html = '';
        var new_receive_lineitem_html = '';
        var total_issue_fw_weight = 0;
        var total_issue_fw_less = 0;
        var total_issue_fw_net_wt = 0;
        var total_issue_fw_fine = 0;
        var total_issue_fw_tunch = 0;
        var total_issue_fw_actual_fine = 0;
        var total_issue_fw_pcs = 0;
        var total_issue_fw_ad_weight = 0;
        var total_issue_s_weight = 0;
        var total_issue_s_less = 0;
        var total_issue_s_net_wt = 0;
        var total_issue_s_fine = 0;
        var total_issue_s_tunch = 0;
        var total_issue_s_actual_fine = 0;
        var total_issue_s_pcs = 0;
        var total_issue_s_ad_weight = 0;
        var total_issue_weight = 0;
        var total_issue_less = 0;
        var total_issue_net_wt = 0;
        var total_issue_fine = 0;
        var total_issue_tunch = 0;
        var total_issue_actual_fine = 0;
        var total_issue_pcs = 0;
        var total_issue_ad_weight = 0;
        var total_issue_with_ad_weight = 0;
        var total_issue_with_ad_less = 0;
        var total_issue_with_ad_net_wt = 0;
        var total_issue_with_ad_fine = 0;
        var total_issue_with_ad_tunch = 0;
        var total_issue_with_ad_actual_fine = 0;
        var total_issue_with_ad_pcs = 0;
        
        var total_receive_fw_weight = 0;
        var total_receive_fw_less = 0;
        var total_receive_fw_net_wt = 0;
        var total_receive_fw_fine = 0;
        var total_receive_fw_tunch = 0;
        var total_receive_fw_actual_fine = 0;
        var total_receive_fw_pcs = 0;
        var total_receive_fw_ad_weight = 0;
        var total_receive_s_weight = 0;
        var total_receive_s_less = 0;
        var total_receive_s_net_wt = 0;
        var total_receive_s_fine = 0;
        var total_receive_s_tunch = 0;
        var total_receive_s_actual_fine = 0;
        var total_receive_s_pcs = 0;
        var total_receive_s_ad_weight = 0;
        var total_receive_weight = 0;
        var total_receive_less = 0;
        var total_receive_net_wt = 0;
        var total_receive_fine = 0;
        var total_receive_tunch = 0;
        var total_receive_actual_fine = 0;
        var total_receive_pcs = 0;
        var total_receive_ad_weight = 0;
        var total_receive_with_ad_weight = 0;
        var total_receive_with_ad_less = 0;
        var total_receive_with_ad_net_wt = 0;
        var total_receive_with_ad_fine = 0;
        var total_receive_with_ad_tunch = 0;
        var total_receive_with_ad_actual_fine = 0;
        var total_receive_with_ad_pcs = 0;
        
        if($.isEmptyObject(lineitem_objectdata)){
            $('#department_id').removeAttr('disabled','disabled');
            $('#after_disabled_department_id').remove();
        } else {
            var department_id = $('#department_id').val();
            $('#department_id').attr('disabled','disabled');
            $('#department_id').closest('div').append('<input type="hidden" name="department_id" id="after_disabled_department_id" value="' + department_id + '" />');
        }

        $.each(lineitem_objectdata, function (index, value) {

            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';  
            var weight = parseFloat(value.weight) || 0;
            var less = parseFloat(value.less) || 0;
            var net_wt = parseFloat(value.net_wt) || 0;
            var fine = parseFloat(value.fine) || 0;
            var pcs = parseFloat(value.pcs) || 0;
            var ad_weight = parseFloat(value.ad_weight) || 0;
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_mhm_item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.mhm_item_delete == 'allow'){
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_mhm_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var type_value = '';
            if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID; ?>'){
                type_value = 'IFW';
            } else if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID; ?>'){
                type_value = 'IS';
            } else if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID; ?>'){
                type_value = 'RFW';
            } else if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID; ?>'){
                type_value = 'RS';
            }
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td>' + type_value + '</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right">' + weight.toFixed(3) + '</td>' +
                    '<td class="text-right">' + less.toFixed(3) + '</td>' +
                    '<td class="text-right">' + net_wt.toFixed(3) + '</td>' +
                    '<td class="text-right">' + value.purity + '</td>' +
                    '<td class="text-right">' + value.actual_tunch + '</td>' +
                    '<td class="text-right">' + fine.toFixed(3) + '</td>'+
                    '<td class="text-right">' + value.pcs + '</td>'+
                    '<td class="text-right">' + value.ad_weight + '</td>'+
                    '<td class="text-nowrap">' + value.mhm_detail_date + '</td>'+
                    '<td>' + value.mhm_detail_remark + '</td>';
            if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID; ?>'){
                total_receive_weight = parseFloat(total_receive_weight) + parseFloat(weight);
                total_receive_less = parseFloat(total_receive_less) + parseFloat(less);
                total_receive_net_wt = parseFloat(total_receive_net_wt) + parseFloat(net_wt);
                total_receive_fine = parseFloat(total_receive_fine) + parseFloat(fine);
                total_receive_pcs = parseFloat(total_receive_pcs) + parseFloat(pcs);
                total_receive_ad_weight = parseFloat(total_receive_ad_weight) + parseFloat(ad_weight);
                new_receive_lineitem_html += row_html;
                total_receive_tunch = (parseFloat(total_receive_fine) / parseFloat(total_receive_net_wt)) * 100;
                total_receive_tunch = total_receive_tunch || 0;
                
                var receive_actual_fine = parseFloat(net_wt) * parseFloat(value.actual_tunch) / 100;
                total_receive_actual_fine = parseFloat(total_receive_actual_fine) + parseFloat(receive_actual_fine);
                
                if(value.including_ad_wt == 1){
                    total_receive_with_ad_weight = parseFloat(total_receive_with_ad_weight) + parseFloat(weight);
                } else {
                    total_receive_with_ad_weight = parseFloat(total_receive_with_ad_weight) + parseFloat(weight) + parseFloat(ad_weight);
                }
                total_receive_with_ad_less = parseFloat(total_receive_with_ad_less) + parseFloat(less);
                if(value.including_ad_wt == 1){
                    var with_ad_net_wt = parseFloat(weight);
                } else {
                    var with_ad_net_wt = parseFloat(weight) + parseFloat(ad_weight);
                }
                total_receive_with_ad_net_wt = parseFloat(total_receive_with_ad_net_wt) + parseFloat(with_ad_net_wt);
                total_receive_with_ad_pcs = parseFloat(total_receive_with_ad_pcs) + parseFloat(pcs);
                
                var receive_with_ad_actual_fine = parseFloat(with_ad_net_wt) * parseFloat(value.actual_tunch) / 100;
                total_receive_with_ad_actual_fine = parseFloat(total_receive_with_ad_actual_fine) + parseFloat(receive_with_ad_actual_fine);
                
                if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID; ?>'){
                    total_receive_fw_weight = parseFloat(total_receive_fw_weight) + parseFloat(weight);
                    total_receive_fw_less = parseFloat(total_receive_fw_less) + parseFloat(less);
                    total_receive_fw_net_wt = parseFloat(total_receive_fw_net_wt) + parseFloat(net_wt);
                    total_receive_fw_fine = parseFloat(total_receive_fw_fine) + parseFloat(fine);
                    total_receive_fw_pcs = parseFloat(total_receive_fw_pcs) + parseFloat(pcs);
                    total_receive_fw_ad_weight = parseFloat(total_receive_fw_ad_weight) + parseFloat(ad_weight);
                    total_receive_fw_tunch = (parseFloat(total_receive_fw_fine) / parseFloat(total_receive_fw_net_wt)) * 100;
                    total_receive_fw_tunch = total_receive_fw_tunch || 0;
                    total_receive_fw_actual_fine = parseFloat(total_receive_fw_actual_fine) + parseFloat(receive_actual_fine);
                }
                if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID; ?>'){
                    total_receive_s_weight = parseFloat(total_receive_s_weight) + parseFloat(weight);
                    total_receive_s_less = parseFloat(total_receive_s_less) + parseFloat(less);
                    total_receive_s_net_wt = parseFloat(total_receive_s_net_wt) + parseFloat(net_wt);
                    total_receive_s_fine = parseFloat(total_receive_s_fine) + parseFloat(fine);
                    total_receive_s_pcs = parseFloat(total_receive_s_pcs) + parseFloat(pcs);
                    total_receive_s_ad_weight = parseFloat(total_receive_s_ad_weight) + parseFloat(ad_weight);
                    total_receive_s_tunch = (parseFloat(total_receive_s_fine) / parseFloat(total_receive_s_net_wt)) * 100;
                    total_receive_s_tunch = total_receive_s_tunch || 0;
                    total_receive_s_actual_fine = parseFloat(total_receive_s_actual_fine) + parseFloat(receive_actual_fine);
                }
                
            } else if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID; ?>'){
                total_issue_weight = parseFloat(total_issue_weight) + parseFloat(weight);
                total_issue_less = parseFloat(total_issue_less) + parseFloat(less);
                total_issue_net_wt = parseFloat(total_issue_net_wt) + parseFloat(net_wt);
                total_issue_fine = parseFloat(total_issue_fine) + parseFloat(fine);
                total_issue_pcs = parseFloat(total_issue_pcs) + parseFloat(pcs);
                total_issue_ad_weight = parseFloat(total_issue_ad_weight) + parseFloat(ad_weight);
                new_issue_lineitem_html += row_html;
                total_issue_tunch = (parseFloat(total_issue_fine) / parseFloat(total_issue_net_wt)) * 100;
                total_issue_tunch = total_issue_tunch || 0;
                
                var issue_actual_fine = parseFloat(net_wt) * parseFloat(value.actual_tunch) / 100;
                total_issue_actual_fine = parseFloat(total_issue_actual_fine) + parseFloat(issue_actual_fine);
                
                if(value.including_ad_wt == 1){
                    total_issue_with_ad_weight = parseFloat(total_issue_with_ad_weight) + parseFloat(weight);
                } else {
                    total_issue_with_ad_weight = parseFloat(total_issue_with_ad_weight) + parseFloat(weight) + parseFloat(ad_weight);
                }
                total_issue_with_ad_less = parseFloat(total_issue_with_ad_less) + parseFloat(less);
                if(value.including_ad_wt == 1){
                    var with_ad_net_wt = parseFloat(weight);
                } else {
                    var with_ad_net_wt = parseFloat(weight) + parseFloat(ad_weight);
                }
                total_issue_with_ad_net_wt = parseFloat(total_issue_with_ad_net_wt) + parseFloat(with_ad_net_wt);
                total_issue_with_ad_pcs = parseFloat(total_issue_with_ad_pcs) + parseFloat(pcs);
                
                var issue_with_ad_actual_fine = parseFloat(with_ad_net_wt) * parseFloat(value.actual_tunch) / 100;
                total_issue_with_ad_actual_fine = parseFloat(total_issue_with_ad_actual_fine) + parseFloat(issue_with_ad_actual_fine);
                
                if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID; ?>'){
                    total_issue_fw_weight = parseFloat(total_issue_fw_weight) + parseFloat(weight);
                    total_issue_fw_less = parseFloat(total_issue_fw_less) + parseFloat(less);
                    total_issue_fw_net_wt = parseFloat(total_issue_fw_net_wt) + parseFloat(net_wt);
                    total_issue_fw_fine = parseFloat(total_issue_fw_fine) + parseFloat(fine);
                    total_issue_fw_pcs = parseFloat(total_issue_fw_pcs) + parseFloat(pcs);
                    total_issue_fw_ad_weight = parseFloat(total_issue_fw_ad_weight) + parseFloat(ad_weight);
                    total_issue_fw_tunch = (parseFloat(total_issue_fw_fine) / parseFloat(total_issue_fw_net_wt)) * 100;
                    total_issue_fw_tunch = total_issue_fw_tunch || 0;
                    total_issue_fw_actual_fine = parseFloat(total_issue_fw_actual_fine) + parseFloat(issue_actual_fine);
                }
                if(value.type_id == '<?php echo MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID; ?>'){
                    total_issue_s_weight = parseFloat(total_issue_s_weight) + parseFloat(weight);
                    total_issue_s_less = parseFloat(total_issue_s_less) + parseFloat(less);
                    total_issue_s_net_wt = parseFloat(total_issue_s_net_wt) + parseFloat(net_wt);
                    total_issue_s_fine = parseFloat(total_issue_s_fine) + parseFloat(fine);
                    total_issue_s_pcs = parseFloat(total_issue_s_pcs) + parseFloat(pcs);
                    total_issue_s_ad_weight = parseFloat(total_issue_s_ad_weight) + parseFloat(ad_weight);
                    total_issue_s_tunch = (parseFloat(total_issue_s_fine) / parseFloat(total_issue_s_net_wt)) * 100;
                    total_issue_s_tunch = total_issue_s_tunch || 0;
                    total_issue_s_actual_fine = parseFloat(total_issue_s_actual_fine) + parseFloat(issue_actual_fine);
                }
                
            }

        });
        $('#issue_lineitem_list').html(new_issue_lineitem_html);
        $('#total_issue_weight').html(total_issue_weight.toFixed(3));
        $('#total_issue_less').html(total_issue_less.toFixed(3));
        $('#total_issue_net_wt').html(total_issue_net_wt.toFixed(3));
        $('#total_issue_fine').html(total_issue_fine.toFixed(3));
        $('#total_issue_pcs').html(total_issue_pcs.toFixed(2));
        $('#total_issue_ad_weight').html(total_issue_ad_weight.toFixed(2));
        $('#total_issue_tunch').html(total_issue_tunch.toFixed(2));
        var total_issue_actual_tunch = ((parseFloat(total_issue_actual_fine) / parseFloat(total_issue_net_wt)) * 100) || 0;
        $('#total_issue_actual_tunch').html(total_issue_actual_tunch.toFixed(2));
        $('#total_issue_with_ad_weight').html(total_issue_with_ad_weight.toFixed(3));
        $('#total_issue_with_ad_less').html(total_issue_with_ad_less.toFixed(3));
        $('#total_issue_with_ad_net_wt').html(total_issue_with_ad_net_wt.toFixed(3));
        total_issue_with_ad_tunch = (parseFloat(total_issue_fine) / parseFloat(total_issue_with_ad_weight)) * 100;
        total_issue_with_ad_tunch = total_issue_with_ad_tunch || 0;
        var with_ad_fine = parseFloat(total_issue_with_ad_net_wt) * parseFloat(total_issue_with_ad_tunch) / 100;
        total_issue_with_ad_fine = parseFloat(total_issue_with_ad_fine) + parseFloat(with_ad_fine);
        $('#total_issue_with_ad_fine').html(total_issue_with_ad_fine.toFixed(3));
        $('#total_issue_with_ad_pcs').html(total_issue_with_ad_pcs.toFixed(2));
        $('#total_issue_with_ad_tunch').html(total_issue_with_ad_tunch.toFixed(2));
        var total_issue_with_ad_actual_tunch = ((parseFloat(total_issue_with_ad_actual_fine) / parseFloat(total_issue_with_ad_net_wt)) * 100) || 0;
        $('#total_issue_with_ad_actual_tunch').html(total_issue_with_ad_actual_tunch.toFixed(2));
        
        $('#total_issue_fw_weight').html(total_issue_fw_weight.toFixed(3));
        $('#total_issue_fw_less').html(total_issue_fw_less.toFixed(3));
        $('#total_issue_fw_net_wt').html(total_issue_fw_net_wt.toFixed(3));
        $('#total_issue_fw_fine').html(total_issue_fw_fine.toFixed(3));
        $('#total_issue_fw_actual_fine').html(total_issue_fw_actual_fine.toFixed(3));
        $('#total_issue_fw_pcs').html(total_issue_fw_pcs.toFixed(3));
        $('#total_issue_fw_ad_weight').html(total_issue_fw_ad_weight.toFixed(3));
        $('#total_issue_fw_tunch').html(total_issue_fw_tunch.toFixed(2));
        var total_issue_fw_actual_tunch = ((parseFloat(total_issue_fw_actual_fine) / parseFloat(total_issue_fw_net_wt)) * 100) || 0;
        $('#total_issue_fw_actual_tunch').html(total_issue_fw_actual_tunch.toFixed(2));
        
        $('#total_issue_s_weight').html(total_issue_s_weight.toFixed(3));
        $('#total_issue_s_less').html(total_issue_s_less.toFixed(3));
        $('#total_issue_s_net_wt').html(total_issue_s_net_wt.toFixed(3));
        $('#total_issue_s_fine').html(total_issue_s_fine.toFixed(3));
        $('#total_issue_s_actual_fine').html(total_issue_s_actual_fine.toFixed(3));
        $('#total_issue_s_pcs').html(total_issue_s_pcs.toFixed(3));
        $('#total_issue_s_ad_weight').html(total_issue_s_ad_weight.toFixed(3));
        $('#total_issue_s_tunch').html(total_issue_s_tunch.toFixed(2));
        var total_issue_s_actual_tunch = ((parseFloat(total_issue_s_actual_fine) / parseFloat(total_issue_s_net_wt)) * 100) || 0;
        $('#total_issue_s_actual_tunch').html(total_issue_s_actual_tunch.toFixed(2));
        
        $('#receive_lineitem_list').html(new_receive_lineitem_html);
        $('#total_receive_weight').html(total_receive_weight.toFixed(3));
        $('#total_receive_less').html(total_receive_less.toFixed(3));
        $('#total_receive_net_wt').html(total_receive_net_wt.toFixed(3));
        $('#total_receive_fine').html(total_receive_fine.toFixed(3));
        $('#total_receive_pcs').html(total_receive_pcs.toFixed(2));
        $('#total_receive_ad_weight').html(total_receive_ad_weight.toFixed(2));
        $('#total_receive_tunch').html(total_receive_tunch.toFixed(2));
        var total_receive_actual_tunch = ((parseFloat(total_receive_actual_fine) / parseFloat(total_receive_net_wt)) * 100) || 0;
        $('#total_receive_actual_tunch').html(total_receive_actual_tunch.toFixed(2));
        $('#total_receive_with_ad_weight').html(total_receive_with_ad_weight.toFixed(3));
        $('#total_receive_with_ad_less').html(total_receive_with_ad_less.toFixed(3));
        $('#total_receive_with_ad_net_wt').html(total_receive_with_ad_net_wt.toFixed(3));
        total_receive_with_ad_tunch = (parseFloat(total_receive_fine) / parseFloat(total_receive_with_ad_weight)) * 100;
        total_receive_with_ad_tunch = total_receive_with_ad_tunch || 0;
        var with_ad_fine = parseFloat(total_receive_with_ad_net_wt) * parseFloat(total_receive_with_ad_tunch) / 100;
        total_receive_with_ad_fine = parseFloat(total_receive_with_ad_fine) + parseFloat(with_ad_fine);
        $('#total_receive_with_ad_fine').html(total_receive_with_ad_fine.toFixed(3));
        $('#total_receive_with_ad_pcs').html(total_receive_with_ad_pcs.toFixed(2));
        $('#total_receive_with_ad_tunch').html(total_receive_with_ad_tunch.toFixed(2));
        var total_receive_with_ad_actual_tunch = ((parseFloat(total_receive_with_ad_actual_fine) / parseFloat(total_receive_with_ad_net_wt)) * 100) || 0;
        $('#total_receive_with_ad_actual_tunch').html(total_receive_with_ad_actual_tunch.toFixed(2));
        
        $('#total_receive_fw_weight').html(total_receive_fw_weight.toFixed(3));
        $('#total_receive_fw_less').html(total_receive_fw_less.toFixed(3));
        $('#total_receive_fw_net_wt').html(total_receive_fw_net_wt.toFixed(3));
        $('#total_receive_fw_fine').html(total_receive_fw_fine.toFixed(3));
        $('#total_receive_fw_actual_fine').html(total_receive_fw_actual_fine.toFixed(3));
        $('#total_receive_fw_pcs').html(total_receive_fw_pcs.toFixed(3));
        $('#total_receive_fw_ad_weight').html(total_receive_fw_ad_weight.toFixed(3));
        $('#total_receive_fw_tunch').html(total_receive_fw_tunch.toFixed(2));
        var total_receive_fw_actual_tunch = ((parseFloat(total_receive_fw_actual_fine) / parseFloat(total_receive_fw_net_wt)) * 100) || 0;
        $('#total_receive_fw_actual_tunch').html(total_receive_fw_actual_tunch.toFixed(2));
        
        $('#total_receive_s_weight').html(total_receive_s_weight.toFixed(3));
        $('#total_receive_s_less').html(total_receive_s_less.toFixed(3));
        $('#total_receive_s_net_wt').html(total_receive_s_net_wt.toFixed(3));
        $('#total_receive_s_fine').html(total_receive_s_fine.toFixed(3));
        $('#total_receive_s_actual_fine').html(total_receive_s_actual_fine.toFixed(3));
        $('#total_receive_s_pcs').html(total_receive_s_pcs.toFixed(3));
        $('#total_receive_s_ad_weight').html(total_receive_s_ad_weight.toFixed(3));
        $('#total_receive_s_tunch').html(total_receive_s_tunch.toFixed(2));
        var total_receive_s_actual_tunch = ((parseFloat(total_receive_s_actual_fine) / parseFloat(total_receive_s_net_wt)) * 100) || 0;
        $('#total_receive_s_actual_tunch').html(total_receive_s_actual_tunch.toFixed(2));
        
        $('#save_manu_hand_made').append('<input type="hidden" name="total_issue_net_wt" id="total_issue_net_wt" value="' + total_issue_net_wt + '" />');
        $('#save_manu_hand_made').append('<input type="hidden" name="total_receive_net_wt" id="total_receive_net_wt" value="' + total_receive_net_wt + '" />');
        $('#save_manu_hand_made').append('<input type="hidden" name="total_issue_fine" id="total_issue_fine" value="' + total_issue_fine + '" />');
        $('#save_manu_hand_made').append('<input type="hidden" name="total_receive_fine" id="total_receive_fine" value="' + total_receive_fine + '" />');
        $('#ajax-loader').hide();
        set_total_weight();
        set_total_net_wt();
        set_total_fine();
        set_balance_receive_tunch();
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

    function edit_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_mhm_item").addClass('hide');
        line_items_index = index;
        if (edit_lineitem_inc == 0) {
            edit_lineitem_inc = 1;
            $(".add_lineitem").removeAttr("disabled");
        }
        var value = lineitem_objectdata[index];
        if(value.tunch_textbox == 1){
            $("#tunch_textbox").prop("checked", true).trigger('change');
        } else {
            $("#tunch_textbox").prop("checked", false).trigger('change');
        }
        $("#line_items_index").val(index);
        $("#mhm_item_delete").val(value.mhm_item_delete);
        if(typeof(value.mhm_detail_id) !== "undefined" && value.mhm_detail_id !== null) {
            $("#mhm_detail_id").val(value.mhm_detail_id);
        }
        $("#purchase_sell_item_id").val(value.purchase_sell_item_id);
        $("#stock_type").val(value.stock_type);
        $("#type_id").val(value.type_id).trigger("change");
        $("#item_id").val(null).trigger("change");
        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + value.item_id);
        $("#weight").val(value.weight);
        $("#less").val(value.less);
        $("#net_wt").val(value.net_wt);
        $(".touch_id").val(value.purity).trigger("change");
        $("#actual_tunch").val(value.actual_tunch);
        $("#fine").val(value.fine);
        $("#pcs").val(value.pcs);
        $("#ad_weight").val(value.ad_weight);
        if(value.including_ad_wt == 1){
            $("#including_ad_wt").prop("checked", true).trigger('change');
        } else {
            $("#including_ad_wt").prop("checked", false).trigger('change');
        }
        $("#mhm_detail_date").val(value.mhm_detail_date);
        $("#mhm_detail_date").datepicker('setDate', value.mhm_detail_date);
        $("#mhm_detail_remark").val(value.mhm_detail_remark);
        $('#total_grwt_sell').val(value.total_grwt_sell);
        if(value.mhm_item_delete == 'not_allow'){
            $('#type_id').attr('disabled','disabled');
            $('#item_id').attr('disabled','disabled');
            $('.touch_id').attr('disabled','disabled');
            $('#tunch_textbox').attr('disabled','disabled');
        }
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        value = lineitem_objectdata[index];
        if (confirm('Are you sure ?')) {
            if (typeof (value.mhm_detail_id) !== "undefined" && value.mhm_detail_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.mhm_detail_id + '" />');
            }
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }
    
    function display_ad_lineitem_html(ad_lineitem_objectdata) {
        $('#ajax-loader').show();
        var ad_lineitem_html = '';
        var total_ad_pcs = 0;
        var total_ad_amount = 0;
        
        $.each(ad_lineitem_objectdata, function (index, value) {

            var ad_lineitem_edit_btn = '';
            var ad_lineitem_delete_btn = '';
            var ad_pcs = parseFloat(value.ad_pcs) || 0;
            var ad_rate = parseFloat(value.ad_rate) || 0;
            var ad_amount = parseFloat(value.ad_amount) || 0;
            ad_lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_mhm_ad_item edit_ad_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_ad_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.ad_lineitem_delete == 'allow'){
                ad_lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_mhm_ad_item" href="javascript:void(0);" onclick="remove_ad_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    ad_lineitem_edit_btn +
                    ad_lineitem_delete_btn +
                    '</td>' +
                    '<td>' + value.ad_name + '</td>' +
                    '<td class="text-right">' + ad_pcs.toFixed(2) + '</td>'+
                    '<td class="text-right">' + ad_rate.toFixed(2) + '</td>'+
                    '<td class="text-right">' + ad_amount.toFixed(2) + '</td>';
            total_ad_pcs = parseFloat(total_ad_pcs) + parseFloat(ad_pcs);
            total_ad_amount = parseFloat(total_ad_amount) + parseFloat(ad_amount);
            ad_lineitem_html += row_html;
        });
        $('#ad_lineitem_list').html(ad_lineitem_html);
        var chhijjat_per_100_ad = $('#chhijjat_per_100_ad').val();
        var auto_count_chhijat = parseFloat(total_ad_pcs) * parseFloat(chhijjat_per_100_ad) / 100;
        $('#auto_count_chhijat').html('Chhijjat : ' + auto_count_chhijat.toFixed(2) + ' <input type="hidden" name="auto_count_chhijat_value" id="auto_count_chhijat_value" value="'+ auto_count_chhijat.toFixed(2) +'">');
        $('#total_ad_pcs').html(total_ad_pcs.toFixed(2));
        $('#total_ad_amount').html(total_ad_amount.toFixed(2));
        $('#total_ad_amount_for_journal').val(total_ad_amount.toFixed(2));
        
        $('#ajax-loader').hide();
    }
    
    function edit_ad_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_mhm_ad_item").addClass('hide');
        ad_lineitem_index = index;
        if (edit_ad_lineitem_inc == 0) {
            edit_ad_lineitem_inc = 1;
            $(".add_ad_lineitem").removeAttr("disabled");
        }
        var value = ad_lineitem_objectdata[index];
        $("#ad_lineitem_index").val(index);
        $("#ad_lineitem_delete").val(value.ad_lineitem_delete);
        if(typeof(value.mhm_ad_id) !== "undefined" && value.mhm_ad_id !== null) {
            $("#mhm_ad_id").val(value.mhm_ad_id);
        }
        $("#ad_id").val(null).trigger("change");
        setSelect2Value($("#ad_id"), "<?= base_url('app/set_ad_name_select2_val_by_id/') ?>" + value.ad_id);
        $("#ad_pcs").val(value.ad_pcs);
        $("#ad_rate").val(value.ad_rate);
        $("#ad_amount").val(value.ad_amount);
        $('#ajax-loader').hide();
    }

    function remove_ad_lineitem(index) {
        value = ad_lineitem_objectdata[index];
        if (confirm('Are you sure ?')) {
            if (typeof (value.mhm_ad_id) !== "undefined" && value.mhm_ad_id !== null) {
                $('.ad_lineitem_form').append('<input type="hidden" name="deleted_ad_lineitem_id[]" id="deleted_ad_lineitem_id" value="' + value.mhm_ad_id + '" />');
            }
            ad_lineitem_objectdata.splice(index, 1);
            display_ad_lineitem_html(ad_lineitem_objectdata);
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

    function strpad00(s){
        s = s + '';
        if (s.length === 1) s = '0'+s;
        return s;
    }
    
    function set_total_net_wt(){
        var total_issue = $('#total_issue_net_wt').html() || 0;
        var total_receive = $('#total_receive_net_wt').html() || 0;
        var total_weight = parseFloat(total_issue) - parseFloat(total_receive);
        $('#total_net_wt').text(total_weight.toFixed(3));
    }
    
    function set_total_weight(){
        var total_issue = $('#total_issue_weight').html() || 0;
        var total_receive = $('#total_receive_weight').html() || 0;
        var total_weight = parseFloat(total_issue) - parseFloat(total_receive);
        $('#total_weight').text(total_weight.toFixed(3));
    }
    
    function set_total_fine(){
        var total_issue = $('#total_issue_fine').html() || 0;
        var total_receive = $('#total_receive_fine').html() || 0;
        var total_fine = parseFloat(total_issue) - parseFloat(total_receive);
        $('#total_fine').text(total_fine.toFixed(3));
    }
    
    function set_balance_receive_tunch(){
        var total_receive_fw_fine = $('#total_receive_fw_fine').html() || 0;
        var total_receive_with_ad_weight = $('#total_receive_with_ad_weight').html() || 0;
        if(total_receive_with_ad_weight == '0'){
            total_receive_with_ad_weight = $('#total_receive_weight').html() || 0;
        }
        var balance_receive_tunch = (parseFloat(total_receive_fw_fine) / (parseFloat(total_receive_with_ad_weight) * 100)) || 0;
        $('#balance_receive_tunch').text(balance_receive_tunch.toFixed(2));
    }
    
    function get_worker_chhijjat_per_100_ad(){
        var worker_id = $("#worker_id").val();
        if(worker_id != '' || worker_id != null){
            $.ajax({
                url: "<?php echo base_url('manu_hand_made/get_worker_chhijjat_per_100_ad'); ?>/",
                type: 'POST',
                async: false,
                data: {worker_id : worker_id},
                success: function (response) {
                    var json = $.parseJSON(response);
                    var chhijjat_per_100_ad = json.chhijjat_per_100_ad || 0;
                    $('#chhijjat_per_100_ad').val(chhijjat_per_100_ad);
                    display_ad_lineitem_html(ad_lineitem_objectdata);
                }
            });
        }
    }
    
    function get_worker_meena_charges(){
        var worker_id = $("#worker_id").val();
        if(worker_id != '' || worker_id != null){
            $.ajax({
                url: "<?php echo base_url('manu_hand_made/get_worker_meena_charges'); ?>/",
                type: 'POST',
                async: false,
                data: {worker_id : worker_id},
                success: function (response) {
                    var json = $.parseJSON(response);
                    var meena_charges = json.meena_charges || 0;
                    $('#meena_charges').val(meena_charges);
                }
            });
        }
    }
    
    function set_total_meena_charges_for_journal(){
        var total_weight = $('#total_weight').html() || 0;
        var meena_charges = $('#meena_charges').val() || 0;
        var total_meena_charges_for_journal = parseFloat(total_weight) * parseFloat(meena_charges);
        total_meena_charges_for_journal = Math.abs(total_meena_charges_for_journal);
        total_meena_charges_for_journal = round(total_meena_charges_for_journal, 0).toFixed(2);
        $('#total_meena_charges_for_journal').val(total_meena_charges_for_journal);
        $('.meena_charges_amount').html('Meena Charges Amount : ' + total_meena_charges_for_journal + ' <input type="hidden" name="meena_charges_amount_value" id="meena_charges_amount_value" value="'+ total_meena_charges_for_journal +'">');
    }
    
</script>
