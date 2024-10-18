<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('machine_chain/save_machine_chain') ?>" method="post" id="save_machine_chain" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
            <input type="hidden" name="machine_chain_id" class="machine_chain_id" value="<?= $machine_chain_data->machine_chain_id ?>">
        <?php } ?>
        <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'forward') { ?>
            <input type="hidden" name="forwarded_from_mc_id" class="forwarded_from_mc_id" value="<?= $machine_chain_data->machine_chain_id ?>">
        <?php } ?>
            <input type="hidden" id="total_grwt_sell" value=""/>
            <input type="hidden" id="issue_change_actual_tunch_allow" value="<?=isset($machine_chain_operation_data->issue_change_actual_tunch_allow)?$machine_chain_operation_data->issue_change_actual_tunch_allow:'1'?>"/>
            <input type="hidden" id="receive_change_actual_tunch_allow" value="<?=isset($machine_chain_operation_data->receive_change_actual_tunch_allow)?$machine_chain_operation_data->receive_change_actual_tunch_allow:'1'?>"/>
            <input type="hidden" id="use_selected_tunch" value="<?=isset($machine_chain_operation_data->use_selected_tunch)?$machine_chain_operation_data->use_selected_tunch:'0'?>"/>

        <!-- Content Header (Page header) -->
     
        <section class="content-header">
            <h1>
                Add Machine Chain
                <?php $isEdit = $this->app_model->have_access_role(MACHINE_CHAIN_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(MACHINE_CHAIN_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(MACHINE_CHAIN_MODULE_ID, "add");
                $allow_change_date = $this->app_model->have_access_role(MACHINE_CHAIN_MODULE_ID, "allow_change_date"); ?>
                <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { } else { if(isset($isAdd) && $isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo (isset($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') ? '' : $btn_disable;?>><?php echo (isset($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('machine_chain/machine_chain_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Machine Chain List</a>
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
                                        <label for="operation_id">Machine Chain Operation<span class="required-sign">&nbsp;*</span></label>
                                        <select name="operation_id" id="operation_id" class="form-control select2"></select>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="worker_id">Worker<span class="required-sign">&nbsp;*</span></label>
                                        <select name="worker_id" id="worker_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                            <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
                                                <label for="reference_no">Reference No</label>
                                                <input type="text" name="reference_no" id="reference_no" class="form-control" readonly="" value="<?= (isset($machine_chain_data->reference_no)) ? $machine_chain_data->reference_no : ''; ?>">
                                            <?php } ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="date">Date<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="machine_chain_date" id="datepicker2" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control input-datepicker" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?= (isset($machine_chain_data->machine_chain_date)) ? date('d-m-Y', strtotime($machine_chain_data->machine_chain_date)) : date('d-m-Y'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="machine_chain_remark">Remark</label>
                                        <textarea name="machine_chain_remark" id="machine_chain_remark" class="form-control"><?php echo (isset($machine_chain_data->machine_chain_remark)) ? $machine_chain_data->machine_chain_remark : ''; ?></textarea><br />
                                    </div>
                                    <div class="col-md-2">
                                        <label for="lott_complete">Lott Complete ?</label><br>
                                        <?php if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"allow to lott complete")) { ?>
                                            <label><input type="radio" name="lott_complete" class="iradio_minimal-blue" value="1" <?= (isset($machine_chain_data->lott_complete)) && $machine_chain_data->lott_complete == 1 ? 'checked' : ''; ?>> Yes</label> &nbsp;&nbsp;&nbsp;
                                            <label><input type="radio" name="lott_complete" class="iradio_minimal-blue" value="0" <?= (isset($machine_chain_data->lott_complete)) ? $machine_chain_data->lott_complete == 0 ? 'checked' : '' : 'checked'; ?>> No</label>
                                        <?php } else { ?>
                                            <?= (isset($machine_chain_data->lott_complete)) && $machine_chain_data->lott_complete == 1 ? 'Yes' : 'No'; ?>
                                            <input type="hidden" name="lott_complete" value="<?= (isset($machine_chain_data->lott_complete)) && $machine_chain_data->lott_complete == 1 ? '1' : '0'; ?>">
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-2 curb_box_div hidden">
                                        <label for="curb_box">Curb/Box<span class="required-sign">&nbsp;*</span></label>
                                        <select name="curb_box" id="curb_box" class="form-control select2">
                                            <option value=""> - Select - </option>
                                            <option value="<?php echo MCO_SOLDING_CURB_ID; ?>" <?= (isset($machine_chain_data->curb_box)) && $machine_chain_data->curb_box == MCO_SOLDING_CURB_ID ? 'selected' : '0'; ?>>Curb</option>
                                            <option value="<?php echo MCO_SOLDING_BOX_ID; ?>" <?= (isset($machine_chain_data->curb_box)) && $machine_chain_data->curb_box == MCO_SOLDING_BOX_ID ? 'selected' : '0'; ?>>Box</option>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-primary btn-sm" id="order_items_btn" ><b> Order Items</b></button>
                                    </div>
                                    <div class="col-sm-10" style="border: 1px solid #cccccc;">
                                        <div class="" style="overflow-x: scroll;">
                                        <h4 style="text-align: center">Selected Order Items</h4>
                                        <table style="" class="table custom-table item-table">
                                            <thead>
                                                <tr>
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
                                            <tbody id="selected_order_items_list"></tbody>
                                        </table>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="line_item_form item_fields_div">
                                        <h4 class="col-md-12">
                                            Line Item &nbsp;&nbsp;&nbsp;
                                            <span><label style="margin-bottom: 0px;"><input type="checkbox" name="line_items_data[tunch_textbox]" id="tunch_textbox" > <small>Tunch Textbox</small></label></span>
                                        </h4>
                                        <div class="clearfix"></div><br />
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <input type="hidden" name="line_items_data[machine_chain_item_delete]" id="machine_chain_item_delete" value="allow" />
                                        <input type="hidden" name="line_items_data[forwarded_from_mcd_id]" id="forwarded_from_mcd_id" />
                                        <input type="hidden" name="line_items_data[added_from_ifw_mcd_id]" id="added_from_ifw_mcd_id" />
                                        <input type="hidden" name="line_items_data[added_from_ifw_mcd_index]" id="added_from_ifw_mcd_index" />
                                        <?php if(isset($machine_chain_detail) && !empty($machine_chain_detail) && $machine_chain_data->entry_mode == 'edit'){ ?>
                                            <input type="hidden" name="line_items_data[machine_chain_detail_id]" id="machine_chain_detail_id" />
                                            <input type="hidden" name="line_items_data[weight_limit]" id="weight_limit" />
                                            <input type="hidden" name="line_items_data[purchase_sell_item_id]" id="purchase_sell_item_id"/>
                                            <input type="hidden" name="line_items_data[stock_type]" id="stock_type" />
                                        <?php } ?>
                                        <div class="col-md-2">
                                            <label for="type">Type<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[type_id]" class="form-control type_id" id="type_id">
                                                <option value=""> - Select - </option>
                                                <option value="<?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?>">Issue Finish Work</option>
                                                <option value="<?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>">Issue Scrap</option>
                                                <option value="<?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>">Receive Finish Work</option>
                                                <option value="<?php echo MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID; ?>">Receive Scrap</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="item_id">Item<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[item_id]" class="form-control item_id select2" id="item_id">
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="weight">Weight<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[weight]" class="form-control num_only weight" id="weight"  placeholder="" value=""><br />
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
                                                    <?php foreach ($touch as $value) { ?>
                                                        <option value="<?= $value->purity; ?>"<?= isset($touch_id) && $value->purity == $touch_id ? 'selected="selected"' : ''; ?>><?= $value->purity; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="touch_input" hidden="">
                                                <input type="text" name="line_items_data[touch_id]" id="touch_data_id" class="form-control touch_id num_only" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="actual_tunch">Actual Tunch<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[actual_tunch]" class="form-control num_only actual_tunch" id="actual_tunch"  placeholder="" value=""><br />
                                            <input type="hidden" name="line_items_data[real_actual_tunch]" class="form-control num_only real_actual_tunch" id="real_actual_tunch">
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
                                            <label for="machine_chain_detail_date">Date<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[machine_chain_detail_date]" class="form-control datepicker" id="machine_chain_detail_date" placeholder="" value="<?=date('d-m-Y');?>" ><br />
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-3">
                                            <label for="machine_chain_detail_remark">Remark</label>
                                            <textarea name="line_items_data[machine_chain_detail_remark]" class="form-control" id="machine_chain_detail_remark" placeholder=""></textarea><br />
                                        </div>

                                        <div class="col-md-1">
                                            <label>&nbsp;</label>
                                            <input type="button" id="add_lineitem" class="btn btn-info btn-sm pull-right add_lineitem" value="Add Row" style="margin-top: 21px;"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-6">
                                        <div class="" style="overflow-x: scroll;">
                                        <h4 style="text-align: center">
                                            Receive Table
                                            <span id="calculate_btn_div"></span>
                                            <span id="btn_forward_selected_rfw" class="btn btn-warning btn-xs pull-left hidden" >Forward</span>
                                            <span id="btn_forward_selected_rfw_to_old" class="btn btn-warning btn-xs pull-left hidden" style="margin-left: 5px;" >Forward into old</span>
                                        </h4>
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
                                                    <th class="text-right">A. Fine</th>
                                                    <th class="text-nowrap">Date</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="receive_lineitem_list"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>
                                                        Total RFW:
                                                        <input type="hidden" name="total_receive_fw_real_actual_tunch" id="total_receive_fw_real_actual_tunch">
                                                        <input type="hidden" name="total_receive_fw_real_actual_fine" id="total_receive_fw_real_actual_fine">
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_receive_fw_weight"></th>
                                                    <th class="text-right" id="total_receive_fw_less"></th>
                                                    <th class="text-right" id="total_receive_fw_net_wt"></th>
                                                    <th class="text-right" id="total_receive_fw_tunch"></th>
                                                    <th class="text-right" id="total_receive_fw_actual_tunch"></th>
                                                    <th class="text-right" id="total_receive_fw_fine"></th>
                                                    <th class="text-right" id="total_receive_fw_actual_fine"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Total RS:
                                                        <input type="hidden" name="total_receive_s_real_actual_tunch" id="total_receive_s_real_actual_tunch">
                                                        <input type="hidden" name="total_receive_s_real_actual_fine" id="total_receive_s_real_actual_fine">
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_receive_s_weight"></th>
                                                    <th class="text-right" id="total_receive_s_less"></th>
                                                    <th class="text-right" id="total_receive_s_net_wt"></th>
                                                    <th class="text-right" id="total_receive_s_tunch"></th>
                                                    <th class="text-right" id="total_receive_s_actual_tunch"></th>
                                                    <th class="text-right" id="total_receive_s_fine"></th>
                                                    <th class="text-right" id="total_receive_s_actual_fine"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Total:
                                                        <input type="hidden" name="total_receive_real_actual_tunch" id="total_receive_real_actual_tunch">
                                                        <input type="hidden" name="total_receive_real_actual_fine" id="total_receive_real_actual_fine">
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_receive_weight"></th>
                                                    <th class="text-right" id="total_receive_less"></th>
                                                    <th class="text-right" id="total_receive_net_wt"></th>
                                                    <th class="text-right" id="total_receive_tunch"></th>
                                                    <th class="text-right" id="total_receive_actual_tunch"></th>
                                                    <th class="text-right" id="total_receive_fine"></th>
                                                    <th class="text-right" id="total_receive_actual_fine"></th>
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
                                                    <th class="text-right">A. Fine</th>
                                                    <th class="text-nowrap">Date</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="issue_lineitem_list"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>
                                                        Total IFW:
                                                        <input type="hidden" name="total_issue_fw_real_actual_tunch" id="total_issue_fw_real_actual_tunch">
                                                        <input type="hidden" name="total_issue_fw_real_actual_fine" id="total_issue_fw_real_actual_fine">
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_issue_fw_weight"></th>
                                                    <th class="text-right" id="total_issue_fw_less"></th>
                                                    <th class="text-right" id="total_issue_fw_net_wt"></th>
                                                    <th class="text-right" id="total_issue_fw_tunch"></th>
                                                    <th class="text-right" id="total_issue_fw_actual_tunch"></th>
                                                    <th class="text-right" id="total_issue_fw_fine"></th>
                                                    <th class="text-right" id="total_issue_fw_actual_fine"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Total IS:
                                                        <input type="hidden" name="total_issue_s_real_actual_tunch" id="total_issue_s_real_actual_tunch">
                                                        <input type="hidden" name="total_issue_s_real_actual_fine" id="total_issue_s_real_actual_fine">
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_issue_s_weight"></th>
                                                    <th class="text-right" id="total_issue_s_less"></th>
                                                    <th class="text-right" id="total_issue_s_net_wt"></th>
                                                    <th class="text-right" id="total_issue_s_tunch"></th>
                                                    <th class="text-right" id="total_issue_s_actual_tunch"></th>
                                                    <th class="text-right" id="total_issue_s_fine"></th>
                                                    <th class="text-right" id="total_issue_s_actual_fine"></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Total:
                                                        <input type="hidden" name="total_issue_real_actual_tunch" id="total_issue_real_actual_tunch">
                                                        <input type="hidden" name="total_issue_real_actual_fine" id="total_issue_real_actual_fine">
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_issue_weight"></th>
                                                    <th class="text-right" id="total_issue_less"></th>
                                                    <th class="text-right" id="total_issue_net_wt"></th>
                                                    <th class="text-right" id="total_issue_tunch"></th>
                                                    <th class="text-right" id="total_issue_actual_tunch"></th>
                                                    <th class="text-right" id="total_issue_fine"></th>
                                                    <th class="text-right" id="total_issue_actual_fine"></th>
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
                                        <span><b>Balance Fine : </b> <span id="total_fine"></span></span>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
                                    <div class="created_updated_info" style="margin-left: 10px;">
                                       Created by : <?php echo isset($machine_chain_data->created_by_name) ? $machine_chain_data->created_by_name :'' ; ?>
                                       @ <?php echo isset($machine_chain_data->created_at) ? date('d-m-Y h:i A',strtotime($machine_chain_data->created_at)) :'' ; ?><br/>
                                       Updated by : <?php echo isset($machine_chain_data->updated_by_name) ? $machine_chain_data->updated_by_name :'' ;?>
                                       @ <?php echo isset($machine_chain_data->updated_at) ? date('d-m-Y h:i A',strtotime($machine_chain_data->updated_at)) : '' ;?>
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
    </form>
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
<div id="forward_lineitem_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="myModalLabel">Forward Wt</h4>
                </div>
            </div>
            <div class="modal-body edit-content">
                <div class="col-md-12">
                    <div class="popup_div">
                        <input type="text" name="forward_wt" id="forward_wt" class="form-control num_only" style="width: 100px;" >
                        <input type="hidden" name="forward_wt_hidden" id="forward_wt_hidden">
                        <input type="hidden" name="machine_chain_id_hidden" id="machine_chain_id_hidden">
                        <input type="hidden" name="machine_chain_detail_id_hidden" id="machine_chain_detail_id_hidden">
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <a href="#" class="btn btn-default" id="forward_btn" title="Forward for Next Operation" ><i class="fa fa-mail-forward" style="color : #279B8D;">&nbsp;</i>Forward</a>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div id="mcd_order_items_selection_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <th class="text-center"><label><input type="checkbox" id="mcd_select_all_items" style="height:20px; width:20px" /> Select All</label></th>
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
                            <tbody id="mcd_order_items_selection_list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><br/>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary mcd_items_save_btn" id="mcd_items_save_btn">Select</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div id="forward_multiple_lineitem_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="myModalLabel">Forward Wt</h4>
                </div>
            </div>
            <form class="form-horizontal" action="<?= base_url('machine_chain/machine_chain_forward_multiple') ?>" method="post" id="form_forward_multiple" novalidate enctype="multipart/form-data"> 
                <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
                    <input type="hidden" name="machine_chain_id" class="machine_chain_id" value="<?= $machine_chain_data->machine_chain_id ?>">
                <?php } ?>
            <div class="modal-body edit-content">
                <div class="col-md-12">
                    <div class="popup_div table-responsive">
                        <table style="" class="table custom-table border item-table">
                            <thead>
                                <tr>
                                    <th width="80px">Forward Wt</th>
                                    <th>Type</th>
                                    <th>Item Name</th>
                                    <th class="text-right">Weight</th>
                                    <th class="text-right">Less</th>
                                    <th class="text-right">Net.Wt</th>
                                    <th class="text-right">Tunch</th>
                                    <th class="text-right">A. Tunch</th>
                                    <th class="text-right">Fine</th>
                                    <th class="text-right">A. Fine</th>
                                    <th class="text-nowrap">Date</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody id="forward_multiple_lineitem_list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            </form>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="multiple_forward_btn" title="Forward for Next Operation"><i class="fa fa-mail-forward" style="color : #279B8D;">&nbsp;</i>Forward</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div id="forward_multiple_lineitem_to_old_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="myModalLabel">Forward Wt</h4>
                </div>
            </div>
            <form class="form-horizontal" method="post" id="form_forward_multiple_to_old" novalidate enctype="multipart/form-data"> 
                <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
                    <input type="hidden" name="machine_chain_id" class="machine_chain_id" value="<?= $machine_chain_data->machine_chain_id ?>">
                    <input type="hidden" name="forwarded_to_mc_id" id="forwarded_to_mc_id">
                <?php } ?>
            <div class="modal-body edit-content">
                <div class="col-md-12">
                    <div class="popup_div table-responsive">
                        <table id="machine_chain_table" class="table custom-table border item-table">
                            <thead>
                                <tr>
                                    <th width="80px">Select Row</th>
                                    <th>Department</th>
                                    <th>Operation</th>
                                    <th nowrap>Worker</th>
                                    <th>Date</th>
                                    <th>Ref.</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            </form>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="multiple_forward_to_old_btn" title="Forward for Next Operation"><i class="fa fa-mail-forward" style="color : #279B8D;">&nbsp;</i>Forward</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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

    <?php if(isset($machine_chain_data->operation_id) && $machine_chain_data->operation_id == MACHINE_CHAIN_OPERATION_SOLDING_ID){ ?>
        $('.curb_box_div').removeClass('hidden');
        $('#curb_box').select2();
    <?php } ?>

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
            get_selected_order_items(checked_order_items);
        }
    <?php } ?>
    <?php if (isset($machine_chain_detail)) { ?>
        var li_lineitem_objectdata = [<?php echo $machine_chain_detail; ?>];
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    var is_calculated = '0';
    <?php if (isset($machine_chain_data->is_calculated) && $machine_chain_data->is_calculated == '1') { ?>
        var is_calculated = '1';
    <?php } ?>
    var display_calculate_button = '0';
    <?php if (isset($machine_chain_operation_data->calculate_button) && $machine_chain_operation_data->calculate_button == '1') { ?>
        var display_calculate_button = '1';
    <?php } ?>
    var allow_only_1_order_item = '0';
    var open_edit_mode = '0';
    var machine_chain_table;
    $(document).ready(function () {
        $('.type_id, #touch_id').select2();

        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_select2_source') ?>");
        <?php if (isset($machine_chain_data->worker_id)) { ?>
            setSelect2Value($("#worker_id"), "<?= base_url('app/set_worker_select2_val_by_id/' . $machine_chain_data->worker_id) ?>");
        <?php }?>
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        <?php if (isset($machine_chain_data->department_id)) { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $machine_chain_data->department_id) ?>");
            initAjaxSelect2($("#operation_id"), "<?= base_url('app/machine_chain_operation_from_department_select2_source/' . $machine_chain_data->department_id) ?>");
        <?php } else { ?>
        <?php } ?>
        <?php if (isset($machine_chain_data->operation_id)) { ?>
            setSelect2Value($("#operation_id"), "<?= base_url('app/set_machine_chain_operation_select2_val_by_id/' . $machine_chain_data->operation_id) ?>");
            display_calculate_btn(<?php echo $machine_chain_data->operation_id; ?>);
            initAjaxSelect2($("#worker_id"), "<?= base_url('app/worker_from_machine_chain_operation_select2_source/' . $machine_chain_data->operation_id) ?>");
            $.ajax({
                url: "<?= base_url('app/worker_from_machine_chain_operation_select2_source/' . $machine_chain_data->operation_id) ?>",
                type: "GET",
                data: "",
                dataType: "json",
                success: function (res) {
                    if(res.total_count == 1) {
                        var data = res.results[0];
                        $("#worker_id").empty().append($('<option/>').val(data.id).text(data.text)).val(data.id).trigger("change");
                    }
                }
            });
        <?php }?>
        
        display_lineitem_html(lineitem_objectdata);
        

        machine_chain_table = $('#machine_chain_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('machine_chain/machine_chain_selection_list') ?>",
                "type": "POST",
                "data": function (d) {
                    d.machine_chain_id = $('.machine_chain_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {
                   "className": "text-nowrap",
                   "targets": [0,3,4],
                },
                {
                    "targets": 0,
                    "orderable": false
                }
            ],
        });
        $(document).on('change', '#department_id', function(){
            checked_order_items = [];
            $('#operation_id').val(null).empty().select2();
            $('#worker_id').val(null).empty().select2();
//            $('#operation_id').val(null).trigger('change');
            var department_id = $("#department_id").val();
            if(department_id != '' && department_id != null){
                initAjaxSelect2($("#operation_id"), "<?= base_url('app/machine_chain_operation_from_department_select2_source/') ?>/" + department_id);
            }
        });
        
        $(document).on('change', '#operation_id', function(){
            $('#worker_id').val(null).empty().select2();
//            $("#worker_id").val(null).trigger("change");
            var operation_id = $("#operation_id").val();
            if($(this).val() != '' && $(this).val() != null){
                initAjaxSelect2($("#worker_id"), "<?= base_url('app/worker_from_machine_chain_operation_select2_source/') ?>/" + operation_id);
                $.ajax({
                    url: "<?= base_url('app/worker_from_machine_chain_operation_select2_source/') ?>/" + operation_id,
                    type: "GET",
                    data: "",
                    dataType: "json",
                    success: function (res) {
                        if(res.total_count == 1) {
                            var data = res.results[0];
                            $("#worker_id").empty().append($('<option/>').val(data.id).text(data.text)).val(data.id).trigger("change");
                        }
                    }
                });
                display_calculate_btn(operation_id);
            } else {
                $('#calculate_btn_div').html('');
            }
            
            if(operation_id == '<?php echo MACHINE_CHAIN_OPERATION_SOLDING_ID; ?>'){
                $('.curb_box_div').removeClass('hidden');
                $('#curb_box').select2();
            } else {
                $('.curb_box_div').addClass('hidden');
                $('#curb_box').val(null).select2();
            }
        });
        
        $('input[type=radio][name=lott_complete]').change(function() {
            if(is_calculated == '1' || display_calculate_button == '0'){
            } else {
                show_notify('First Please Calculate Actual Tunch, Then After allow to Lott Complete!', false);
                if($(this).val() == 1){
                    $('input[type=radio][name=lott_complete][value=0]').prop('checked', true);
                } else {
                    $('input[type=radio][name=lott_complete][value=1]').prop('checked', true);
                }
                return false
            }
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
                if($(this).val() == 0){
                    $(".add_lineitem").removeClass('hide');
                    $(".edit_machine_chain_item").removeClass('hide');
                    $(".delete_machine_chain_item").removeClass('hide');
                } else {
                    $(".add_lineitem").addClass('hide');
                    $(".edit_machine_chain_item").addClass('hide');
                    $(".delete_machine_chain_item").addClass('hide');
                }
            }
        });
        <?php if((isset($machine_chain_data->lott_complete)) && $machine_chain_data->lott_complete == 1){ ?>
            $(".add_lineitem").addClass('hide');
            $(".edit_machine_chain_item").addClass('hide');
            $(".delete_machine_chain_item").addClass('hide');
        <?php } ?>
        
        $(document).on('click', '#order_items_btn', function(){
            var department_id = $('#department_id').val();
            var machine_chain_id = '0';
            <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
                machine_chain_id = '<?php echo $machine_chain_data->machine_chain_id; ?>';
            <?php } ?>
            var forwarded_from_mc_id = '0'
            <?php if (isset($machine_chain_data->forwarded_from_mc_id) && !empty($machine_chain_data->forwarded_from_mc_id)) { ?>
                forwarded_from_mc_id = '<?php echo $machine_chain_data->forwarded_from_mc_id; ?>';
            <?php } else if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'forward') { ?>
                forwarded_from_mc_id = '<?php echo $machine_chain_data->machine_chain_id; ?>';
            <?php } ?>
            if(department_id == '' || department_id == null){
                show_notify('Please Select Department.', false);
//                $("#department_id").select2('open');
                return false
            } else {
                $.ajax({
                    url: "<?php echo base_url('machine_chain/new_order_item_datatable'); ?>/" + department_id + '/' + machine_chain_id + '/' + forwarded_from_mc_id,
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
                                var value_machine_chain_oi_id = '';
                                var value_machine_chain_id = '';
                                $.each(checked_order_items, function (it_index, it_value) {
                                    if(it_value.order_lot_item_id == li_value.order_lot_item_id){
                                        is_checked = 'checked';
                                        if(typeof(it_value.machine_chain_oi_id) !== "undefined" && it_value.machine_chain_oi_id !== null) {
                                            value_machine_chain_oi_id = it_value.machine_chain_oi_id;
                                        }
                                        if(typeof(it_value.machine_chain_id) !== "undefined" && it_value.machine_chain_id !== null) {
                                            value_machine_chain_id = it_value.machine_chain_id;
                                        }
                                        return false; // breaks
                                    }
                                });
                                row_html_order += '<tr class="items_row">';
                                row_html_order += '<td class="text-center">' +
                                '<input type="checkbox" data-machine_chain_oi_id="' + value_machine_chain_oi_id + '" data-machine_chain_id="' + value_machine_chain_id + '" data-order_id="' + li_value.order_id + '" data-order_lot_item_id="' + li_value.order_lot_item_id + '"  class="checkbox_ch" '+is_checked+' style="height:20px; width:20px"></td>';
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
        
        $(document).on('click', '.btn_show_mcd_order_items', function(){
            var lineitem_index = $(this).data('lineitem_index');

            var department_id = $('#department_id').val();
            var machine_chain_id = '0';
            <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
                machine_chain_id = '<?php echo $machine_chain_data->machine_chain_id; ?>';
            <?php } ?>
            var forwarded_from_mc_id = '0'
            <?php if (isset($machine_chain_data->forwarded_from_mc_id) && !empty($machine_chain_data->forwarded_from_mc_id)) { ?>
                forwarded_from_mc_id = '<?php echo $machine_chain_data->forwarded_from_mc_id; ?>';
            <?php } else if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'forward') { ?>
                forwarded_from_mc_id = '<?php echo $machine_chain_data->machine_chain_id; ?>';
            <?php } ?>

            if(department_id == '' || department_id == null){
                show_notify('Please Select Department.', false);
                return false
            } else {
                $.ajax({
                    url: "<?php echo base_url('machine_chain/new_order_item_datatable'); ?>/" + department_id + '/' + machine_chain_id + '/' + forwarded_from_mc_id,
                    type: "GET",
                    async: false,
                    data: "",
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $('#mcd_order_items_selection_popup').modal('show');
                        $('#mcd_items_save_btn').attr('data-lineitem_index',lineitem_index);

                        if (json['order_items'] != '') {
                            var order_items_objectdata = json['order_items'];
                            var row_html_order = '';
                            var total_weight = 0;
                            var total_pcs = 0;
                            $.each(order_items_objectdata, function (li_index, li_value) {
                                var is_checked = '';
                                var value_machine_chain_oi_id = '';
                                var value_machine_chain_id = '';
                                $.each(checked_order_items, function (it_index, it_value) {
                                    if(it_value.order_lot_item_id == li_value.order_lot_item_id){
                                        is_checked = 'checked';
                                        if(typeof(it_value.machine_chain_oi_id) !== "undefined" && it_value.machine_chain_oi_id !== null) {
                                            value_machine_chain_oi_id = it_value.machine_chain_oi_id;
                                        }
                                        if(typeof(it_value.machine_chain_id) !== "undefined" && it_value.machine_chain_id !== null) {
                                            value_machine_chain_id = it_value.machine_chain_id;
                                        }
                                        return false; // breaks
                                    }
                                });

                                if(is_checked == 'checked') {
                                    var machine_chain_detail_oi_id = 0;
                                    var is_checked = '';
                                    if(typeof(lineitem_objectdata[lineitem_index].mcd_checked_order_items) !== "undefined") {
                                        var mcd_checked_order_items = lineitem_objectdata[lineitem_index].mcd_checked_order_items
                                        $.each(mcd_checked_order_items, function (it_index, it_value) {
                                            if(it_value.order_lot_item_id == li_value.order_lot_item_id){
                                                is_checked = 'checked';
                                                if(typeof(it_value.machine_chain_detail_oi_id) !== "undefined" && it_value.machine_chain_detail_oi_id !== null) {
                                                    machine_chain_detail_oi_id = it_value.machine_chain_detail_oi_id;
                                                }
                                                return false;
                                            }
                                        });    
                                    }

                                    row_html_order += '<tr class="items_row">';
                                    row_html_order += '<td class="text-center">' +
                                    '<input type="checkbox" data-lineitem_index="' + lineitem_index + '" data-machine_chain_id="' + value_machine_chain_id + '" data-machine_chain_detail_oi_id="' + machine_chain_detail_oi_id + '" data-order_id="' + li_value.order_id + '" data-order_lot_item_id="' + li_value.order_lot_item_id + '"  '+is_checked+' class="mcd_checkbox_ch"  style="height:20px; width:20px"></td>';
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

                                    total_weight += parseFloat(li_value.weight) || 0;
                                    total_pcs += parseInt(li_value.pcs) || 0;
                                }
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
                                row_html_order += '<td class="text-right"><b>' + (round(total_weight,2).toFixed(3)) + '</b></td>';
                                row_html_order += '<td class="text-right"><b>' + total_pcs + '</b></td>';
                            row_html_order += '</tr>';
                            $("#mcd_order_items_selection_list").html(row_html_order);
                        } else {
                            $("#mcd_order_items_selection_list").html('');
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
        
        $(document).on('click', '#mcd_select_all_items', function () {
            if($(this).prop('checked') == true){
                $(".mcd_checkbox_ch").each(function(){
                    $(this).prop("checked", true);
                });
            } else {
                $(".mcd_checkbox_ch").each(function(){
                    $(this).prop("checked", false);

                });
            }
        });
        
        $(document).on('click', '#mcd_items_save_btn', function () {
            var lineitem_index = $(this).attr('data-lineitem_index');
            
            var mcd_checked_order_items = [];
            $(".mcd_checkbox_ch").each(function(){
                if($(this).prop('checked') == true){
                    var ch_arr = {};
                    ch_arr['machine_chain_detail_oi_id'] = $(this).attr('data-machine_chain_detail_oi_id');
                    ch_arr['machine_chain_id'] = $(this).attr('data-machine_chain_id');
                    ch_arr['order_id'] = $(this).attr('data-order_id');
                    ch_arr['order_lot_item_id'] = $(this).attr('data-order_lot_item_id');
                    mcd_checked_order_items.push(ch_arr);
                }
            });
            lineitem_objectdata[lineitem_index]["mcd_checked_order_items"] = null;
            lineitem_objectdata[lineitem_index]["mcd_checked_order_items"] = mcd_checked_order_items;
            $('#mcd_order_items_selection_popup').modal('hide');
        });

        $('#mcd_order_items_selection_popup').on('hidden.bs.modal', function () {
            $("#mcd_items_save_btn").attr('data-lineitem_index',-1);
            $("#mcd_select_all_items").prop("checked", false);
        });
        
        $(document).on('click', '#items_save_btn', function () {
            checked_order_items = [];
            $(".checkbox_ch").each(function(){
                if($(this).prop('checked') == true){
                    var ch_arr = {};
                    ch_arr['machine_chain_oi_id'] = $(this).attr('data-machine_chain_oi_id');
                    ch_arr['machine_chain_id'] = $(this).attr('data-machine_chain_id');
                    ch_arr['order_id'] = $(this).attr('data-order_id');
                    ch_arr['order_lot_item_id'] = $(this).attr('data-order_lot_item_id');
                    checked_order_items.push(ch_arr);
                }
            });
            console.log(checked_order_items);
            get_selected_order_items(checked_order_items);
            $('#order_items_selection_popup').modal('hide');
        });
        
        $('#purchase_item_selection_popup').on('hidden.bs.modal', function () {
            $("#item_id").val(null).trigger("change");
            $(".delete_machine_chain_item").removeClass('hide');
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
        
        $(document).on('click', '#calculate_btn', function(){
            var total_weight = $("#total_weight").html();
            if(total_weight < 0){
//                if($("#receive_change_actual_tunch_allow").val() == "1") {
                    var total_receive_fw_weight = $("#total_receive_fw_weight").html();
                    var total_receive_fw_real_actual_tunch = $("#total_receive_fw_real_actual_tunch").val();
                    var rfw_real_actual_fine = parseFloat(total_receive_fw_weight) * parseFloat(total_receive_fw_real_actual_tunch) / 100;
                    var rfw_actual_tunch = parseFloat(rfw_real_actual_fine) / (parseFloat(total_receive_fw_weight) - parseFloat(total_weight)) * 100;
                    $.each(lineitem_objectdata, function (index, value) {
                        if(value.type_id == <?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>){
                            lineitem_objectdata[index].actual_tunch = rfw_actual_tunch.toFixed(2);
                        }
                    });
//                }
                $('#calculate_btn_div').html('');
                is_calculated = '1';
                open_edit_mode = '1';
                $('#save_machine_chain').submit();
                show_notify('Actual Tunch Calculated!', true);
            } else {
                show_notify('Balance Weight >= 0 Not Allowed', false);
            }
        });
        
        $(document).on('change', '#type_id', function () {
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                $('#type_id').val(null).trigger('change.select2');
                return false;
            }
            var operation_id = $.trim($("#operation_id").val());
            if (operation_id == '') {
                show_notify('Please Select Machine Chain Operation.', false);
                $("#operation_id").select2('open');
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
            var forwarded_from_mcd_id = $("#forwarded_from_mcd_id").val();
            if(forwarded_from_mcd_id == '' || forwarded_from_mcd_id == null) {
                $.ajax({
                    url: "<?php echo base_url('machine_chain/get_machine_chain_operation_detail'); ?>/" + operation_id,
                    type: "GET",
                    async: false,
                    data: "",
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if(json.direct_issue_allow == '0' && type_id == <?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?> || 
                            json.direct_issue_allow == '0' && type_id == <?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>){
                                show_notify('In '+ json.operation_name +' Operation, Direct Issue Not Allow!', false);
                                $('#type_id').val(null).trigger('change.select2');
                                return false;
                        }
                    }
                });
            }
            if(type_id == <?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>){
                $("#pcs").val('1');
            } else {
                $("#pcs").val(0);
            }

            var added_from_ifw_mcd_id = $("#added_from_ifw_mcd_id").val();
            var added_from_ifw_mcd_index = $("#added_from_ifw_mcd_index").val();

            if((added_from_ifw_mcd_id != '' && added_from_ifw_mcd_id != null) || (added_from_ifw_mcd_index != '' && added_from_ifw_mcd_index != null)){
                $("#actual_tunch").attr('readonly','readonly');

            } else if(type_id == <?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?> || type_id == <?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>) {
                if($("#issue_change_actual_tunch_allow").val() == "0") {
                    $("#actual_tunch").attr('readonly','readonly');
                } else {
                    $("#actual_tunch").removeAttr('readonly','readonly');
                }
            } else if(type_id == <?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?> || type_id == <?php echo MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID; ?>) {
                if($("#receive_change_actual_tunch_allow").val() == "0") {
                    $("#actual_tunch").val($("#total_issue_actual_tunch").html());
                    $("#real_actual_tunch").val($("#total_issue_actual_tunch").html());
                    $("#actual_tunch").attr('readonly','readonly');
                } else {
                    $("#actual_tunch").removeAttr('readonly','readonly');
                }
            } else {
                $("#actual_tunch").removeAttr('readonly','readonly');
            }
        });
        
        $(document).on('change', '#item_id', function () {
            $("#weight").val('');
            $("#less").val('');
            $("#net_wt").val('');
            $("#touch_data_id").val('');
            $("#touch_id").val(null).trigger("change");
            $("#fine").val('');
            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();
            var todays_date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
//            $('#machine_chain_detail_date').val(todays_date).trigger('change');
            
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
                            
                            if(type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?>' || type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>'){
                                var department_id = $('#department_id').val();
                                var machine_chain_id = '';
                                <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
                                    machine_chain_id = '<?php echo $machine_chain_data->machine_chain_id; ?>';
                                <?php } ?>
                                $.ajax({
                                    url: "<?php echo base_url('sell/get_purchase_to_sell_pending_item'); ?>/",
                                    type: 'POST',
                                    async: false,
                                    data: {department_id : department_id, item_id : item_id, machine_chain_id : machine_chain_id, do_not_count_wstg : 1},
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
                pts_lineitem_objectdata[pts_selected_index].machine_chain_item_delete = 'allow';
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
                pts_lineitem_objectdata[pts_selected_index].real_actual_tunch = 0;
                pts_lineitem_objectdata[pts_selected_index].pcs = 0;
                var d = new Date();

                var month = d.getMonth()+1;
                var day = d.getDate();

                var todays_date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
            
                pts_lineitem_objectdata[pts_selected_index].machine_chain_detail_date = todays_date;
                pts_lineitem_objectdata[pts_selected_index].machine_chain_detail_remark = '';
                pts_lineitem_objectdata[pts_selected_index].type_id = type_id;
                
                /******* You not allow to Issue, The Receive of same Entry! Start *******/
                $.each(lineitem_objectdata, function(index, value) {
                    if(typeof(pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id) !== "undefined" && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id !== null && 
                            typeof(value.sell_item_id) !== "undefined" && value.sell_item_id !== null && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id == value.sell_item_id &&
                            (pts_lineitem_objectdata[pts_selected_index].stock_type == <?php echo STOCK_TYPE_MC_RECEIVE_FINISH_ID; ?> || pts_lineitem_objectdata[pts_selected_index].stock_type == <?php echo STOCK_TYPE_MC_RECEIVE_SCRAP_ID; ?>)) {
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
//                lineitem_delete.push(values.machine_chain_detail_id);
//            });
//            pts_delete = [];
//            jQuery.each(pts_selected_index_lineitems, function(obj, values) {
//                pts_delete.push(values.machine_chain_detail_id);
//            });
//            var uncheck_sell_item = $(lineitem_delete).not(pts_delete).get();
//            
//            $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + uncheck_sell_item + '" />');
                
            $('#purchase_item_selection_popup').modal('hide');
            
            var remove_arr = [];
            $.each(lineitem_objectdata, function(index, value) {
                if(typeof(value.item_id) !== "undefined" && value.item_id !== null && value.item_id == pts_item_id && (value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>')) {
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
                    $("#save_machine_chain").submit();
                    return false;
                }
            }
        });

        $(document).on('submit', '#save_machine_chain', function () {
            $(window).unbind('beforeunload');
            var department_id = $('#department_id').val();
            if (department_id == '' || department_id == null) {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                return false;
            }
            var operation_id = $.trim($("#operation_id").val());
            if (operation_id == '' || operation_id == null) {
                show_notify('Please Select Machine Chain Operation.', false);
                $("#operation_id").select2('open');
                return false;
            }

            if(operation_id == <?=MACHINE_CHAIN_OPERATION_SOLDING_ID?> && ($("#curb_box").val() == '' || $("#curb_box").val() == null)) {
                show_notify('Please Select Curb/Box.', false);
                $("#curb_box").select2('open');
                return false;
            }

            if ($.trim($("#worker_id").val()) == '' || $.trim($("#worker_id").val()) == null) {
                show_notify('Please Select Worker Name.', false);
                $("#worker_id").select2('open');
                return false;
            }
            var datepicker2 = $("#datepicker2").val();
            if(datepicker2 == '' || datepicker2 == null){
                show_notify('Please Select Machine Chain Date.', false);
                $("#datepicker2").focus();
                return false;
            }

            if (lineitem_objectdata == '' || lineitem_objectdata == null) {
                show_notify("Please Add Item.", false);
                return false;
            }
            
            $.ajax({
                url: "<?php echo base_url('machine_chain/get_machine_chain_operation_detail'); ?>/" + operation_id,
                type: "GET",
                async: false,
                data: "",
                success: function (response) {
                    var json = $.parseJSON(response);
                    if(json.allow_only_1_order_item == '1' && checked_order_items.length > '1'){
                        allow_only_1_order_item = '1';
                    } else {
                        allow_only_1_order_item = '0';
                    }
                }
            });
            if(allow_only_1_order_item == '1'){
                show_notify('For Selected Operation, Allow Only 1 Order Item!', false);
                return false;
            }
            
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var checked_order_items_objectdata_stringify = JSON.stringify(checked_order_items);
            postData.append('is_calculated', is_calculated);
            postData.append('checked_order_items', checked_order_items_objectdata_stringify);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            $.ajax({
                url: "<?= base_url('machine_chain/save_machine_chain') ?>",
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
                    if(open_edit_mode == '1'){
                        window.location.href = "<?php echo base_url('machine_chain/machine_chain/') ?>" + json['machine_chain_id'];
                        return false;
                    }
                    if (json['error'] == 'Something went Wrong') {
                        $("#ajax-loader").hide();
                        show_notify('Something went Wrong! Please Refresh page and Go ahead.', false);
                    } else if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('machine_chain/machine_chain_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('machine_chain/machine_chain/') ?>" + json['machine_chain_id'];
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
            var actual_tunch_readonly = $('#actual_tunch').attr('readonly');
            if(actual_tunch_readonly && actual_tunch_readonly.toLowerCase()!=='false') { } else {
                var actual_tunch = $("#actual_tunch").val();
                if (actual_tunch == '' || actual_tunch == null) {
                    $("#actual_tunch").focus();
                    show_notify("Please Enter Actual Tunch!", false);
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
            
            var weight_limit = parseFloat($('#weight_limit').val()) || 0;
            var weight = parseFloat($('#weight').val()) || 0;
            if (weight_limit != '' && weight_limit > 0 && weight > weight_limit) {
                show_notify("Not Allow Greater than "+ weight_limit +" Weight!", false);
                $("#weight").focus();
                return false;
            }
            
            if($("#machine_chain_detail_date").val() == '') {
                show_notify('Please Select Machine Chain Lineitem Date.', false);
                $("#machine_chain_detail_date").focus();
                return false;
            }

            $("#add_lineitem").attr('disabled', 'disabled');
            var machine_chain_detail_id = $("#machine_chain_detail_id").val();
            if (typeof (machine_chain_detail_id) !== "undefined" && machine_chain_detail_id !== null) {
                $('.line_item_form #deleted_lineitem_id[value="' + machine_chain_detail_id + '"]').remove();
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
                var machine_chain_detail_id = $("#machine_chain_detail_id").val();
                if (typeof (machine_chain_detail_id) !== "undefined" && machine_chain_detail_id !== null) {
                } else {
                    lineitem['real_actual_tunch'] = lineitem['actual_tunch'];
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
                        if (type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?>' || type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>'){
                            var machine_chain_detail_id = $("#machine_chain_detail_id").val();
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
                                data: {process_id : process_id, category_id : category_id, item_id : item_id, touch_id : touch_id, machine_chain_detail_id : machine_chain_detail_id},
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
                                    $("#machine_chain_item_delete").val('allow');
                                    $('#machine_chain_detail_id').val('');
                                    $('#forwarded_from_mcd_id').val('');
                                    $('#added_from_ifw_mcd_id').val('');
                                    $('#added_from_ifw_mcd_index').val('');
                                    $('#weight_limit').val('');
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
                                    $("#real_actual_tunch").val('');
                                    $("#fine").val('');
                                    $("#pcs").val('');
//                                    var d = new Date();
//                                    var curr_date = strpad00(d.getDate())+'-'+strpad00(d.getMonth()+1)+'-'+d.getFullYear();
                                    $("#machine_chain_detail_date").val('<?=date('d-m-Y')?>');
                                    $("#machine_chain_detail_date").datepicker('setDate','<?=date('d-m-Y')?>');
                                    $("#machine_chain_detail_remark").val('');
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
                            $("#machine_chain_item_delete").val('allow');
                            $('#machine_chain_detail_id').val('');
                            $('#forwarded_from_mcd_id').val('');
                            $('#added_from_ifw_mcd_id').val('');
                            $('#added_from_ifw_mcd_index').val('');
                            $('#weight_limit').val('');
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
                            $("#real_actual_tunch").val('');
                            $("#fine").val('');
                            $("#pcs").val('');
//                            var d = new Date();
//                            var curr_date = strpad00(d.getDate())+'-'+strpad00(d.getMonth()+1)+'-'+d.getFullYear();
                            $("#machine_chain_detail_date").val('<?=date('d-m-Y')?>');
                            $("#machine_chain_detail_date").datepicker('setDate','<?=date('d-m-Y')?>');
                            $("#machine_chain_detail_remark").val('');
                            edit_lineitem_inc = 0;
                        }
                    }
                });
                $('#total_grwt_sell').val('');
            }
            $("#add_lineitem").removeAttr('disabled', 'disabled');
            var operation_id = $("#operation_id").val();
            display_calculate_btn(operation_id);
        }); 
        
        <?php if(isset($machine_chain_data->lott_complete) && $machine_chain_data->lott_complete == 1){ ?>
            $("input[type=radio][name=lott_complete][value=0]").click();
            $("input[type=radio][name=lott_complete][value=1]").click();
        <?php } ?>
    
        $(document).on('click', '#btn_forward_selected_rfw', function(){
            $('#forward_multiple_lineitem_popup').modal('show');
            var selected_rfw_mcd_ids = [];
            $.each($("input[name='forward_rfw[]']:checked"), function(){
                selected_rfw_mcd_ids.push($(this).val());
            });
            
            var lineitem_list = '';
            if(selected_rfw_mcd_ids.length > 0) {
               $(selected_rfw_mcd_ids).each(function(index,lineitem_value){
                    var value = lineitem_objectdata[lineitem_value];

                    var available_weight_for_forward = parseFloat(value.available_weight_for_forward) || 0;
                    var weight = parseFloat(value.weight) || 0;
                    var less = parseFloat(value.less) || 0;
                    var net_wt = parseFloat(value.net_wt) || 0;
                    var fine = parseFloat(value.fine) || 0;
                    var pcs = parseFloat(value.pcs) || 0;
                    var machine_chain_detail_id = value.machine_chain_detail_id;
                    var type_value = '';
                    if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?>'){
                        type_value = 'IFW';                       
                    } else if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>'){
                        type_value = 'IS';
                    } else if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>'){
                        type_value = 'RFW';
                    } else if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID; ?>'){
                        type_value = 'RS';
                    }
                    var actual_fine = parseFloat(net_wt) * parseFloat(value.actual_tunch) / 100;

                    var row_html = '<tr class="lineitem_index_' + index + '">' +
                            '<td class=""><input type="text" name="forward_wt_arr['+machine_chain_detail_id+']" id="forward_wt_arr" class="form-control num_only" value="'+available_weight_for_forward.toFixed(3) +'" data-available_weight_for_forward="'+available_weight_for_forward+'" data-machine_chain_detail_id="'+machine_chain_detail_id+'" style="width: 100px;" autocomplete="off"></td>' +
                            '<td>' + type_value + '</td>' +
                            '<td>' + value.item_name + '</td>' +
                            '<td class="text-right">' + weight.toFixed(3) + '</td>' +
                            '<td class="text-right">' + less.toFixed(3) + '</td>' +
                            '<td class="text-right">' + net_wt.toFixed(3) + '</td>' +
                            '<td class="text-right">' + value.purity + '</td>' +
                            '<td class="text-right">' + value.actual_tunch + '</td>' +
                            '<td class="text-right">' + fine.toFixed(3) + '</td>'+
                            '<td class="text-right">' + actual_fine.toFixed(3) + '</td>'+
                            '<td class="text-nowrap">' + value.machine_chain_detail_date + '</td>'+
                            '<td>' + value.machine_chain_detail_remark + '</td></tr>';
                    lineitem_list += row_html;
               }) ;
            }
            $("#forward_multiple_lineitem_list").html(lineitem_list);
        });

        $(document).on('click', '#multiple_forward_btn', function(){
            var selected_rfw_mcd_wt = 0;
            var is_error = false;
            $.each($("input[name^='forward_wt_arr']"),function(){
                var available_weight_for_forward = $(this).data('available_weight_for_forward');
                var forward_wt = $(this).val();
                if(parseFloat(forward_wt) > parseFloat(available_weight_for_forward)){
                    if($(this).closest('td').find('em').length > 0) {
                        $(this).closest('td').find('em').html("Not Allow Greater than " + available_weight_for_forward.toFixed(3) + " Weight!");
                    } else {
                        $(this).closest('td').append("<em class='error forward_wt_error'>Not Allow Greater than " + available_weight_for_forward.toFixed(3) + " Weight!</em>");    
                    }
                    is_error = true;
                } else {
                    selected_rfw_mcd_wt++;
                }
            });

            if(is_error == false && selected_rfw_mcd_wt > 0) {
                $("#form_forward_multiple").submit();
                $('#forward_multiple_lineitem_popup').modal('hide');
            }
        });
    
        $(document).on('click', '#btn_forward_selected_rfw_to_old', function(){
            if($("input[name='forward_rfw[]']:checked").length > 0) {
                $('#forward_multiple_lineitem_to_old_popup').modal('show');
            } else {
                show_notify("Please Select Receive Finish Work To Forward.",false);
            }
            

        });

        $('#forward_multiple_lineitem_to_old_popup').on('shown.bs.modal', function (e) {
            machine_chain_table.draw();
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        $(document).on('change','.forwarded_to_mc', function(){
            if($(this).is(":checked")) {
                $("#forwarded_to_mc_id").val($(this).val());
                $(".forwarded_to_mc:not([value='"+ $(this).val() +"'])").prop('checked',false);
            } else {
                $("#forwarded_to_mc_id").val('');
            }
        });

        $(document).on('click', '#multiple_forward_to_old_btn', function(){
            var forwarded_to_mc_id = $("#forwarded_to_mc_id").val();
            if(forwarded_to_mc_id == '' || forwarded_to_mc_id == null) {
                show_notify("Please Select Machine Chain.",false);
            } else {
                var selected_rfw_mcd_ids = [];
                $.each($("input[name='forward_rfw[]']:checked"), function(){
                    selected_rfw_mcd_ids.push($(this).val());
                });
                
                if(selected_rfw_mcd_ids.length > 0) {
                   $(selected_rfw_mcd_ids).each(function(index,lineitem_value){
                        var value = lineitem_objectdata[lineitem_value];

                        var available_weight_for_forward = parseFloat(value.available_weight_for_forward) || 0;
                        var machine_chain_detail_id = value.machine_chain_detail_id;

                        $("#form_forward_multiple_to_old").append(
                            $('<input>',{
                                type: 'hidden',
                                val: available_weight_for_forward,
                                name: "forward_wt_arr["+machine_chain_detail_id+"]",
                                class: 'forward_wt_arr',
                            })
                        );
                   }) ;
                   $("#form_forward_multiple_to_old").attr("action","<?=base_url("machine_chain/machine_chain/")?>" + forwarded_to_mc_id);
                   $("#form_forward_multiple_to_old").submit();
                   $('#forward_multiple_lineitem_to_old_popup').modal('hide'); 
                }                
            }            
        });

        $('#forward_multiple_lineitem_to_old_popup').on('hidden.bs.modal', function () {
            $("#form_forward_multiple_to_old .forward_wt_arr").remove();
            $.each($("input[name='forward_rfw[]']:checked"), function(){
                $(this).prop('checked',false);
            });
        });

        $(document).on('input',"input[name^='forward_wt_arr']", function(){
            $(this).closest('td').find('em').remove();
        });

        $('#forward_multiple_lineitem_popup').on('hidden.bs.modal', function () {
            $.each($("input[name='forward_rfw[]']:checked"), function(){
                $(this).prop('checked',false);
            });
        });

        $(document).on('click', '.machine_chain_forward', function(){
            $('#forward_lineitem_popup').modal('show');
            var weight = $(this).data('available_weight_for_forward');
            var weight = $(this).data('available_weight_for_forward');
            $("#forward_wt").val(weight.toFixed(3));
            $("#forward_wt_hidden").val(weight);
            var machine_chain_id = $(this).data('machine_chain_id');
            var machine_chain_detail_id = $(this).data('machine_chain_detail_id');
            $("#machine_chain_id_hidden").val(machine_chain_id);
            $("#machine_chain_detail_id_hidden").val(machine_chain_detail_id);
        });
        
        $(document).on('click', '#forward_btn', function(){
            var forward_wt = $("#forward_wt").val();
            var forward_wt_hidden = parseFloat($("#forward_wt_hidden").val()) || 0;
            if(parseFloat(forward_wt) > parseFloat(forward_wt_hidden)){
                show_notify("Not Allow Greater than " + forward_wt_hidden.toFixed(3) + " Weight!", false);
                return false;
            } else {
                var machine_chain_id_hidden = $("#machine_chain_id_hidden").val();
                var machine_chain_detail_id_hidden = $("#machine_chain_detail_id_hidden").val();
                $('#forward_btn').attr('href', '<?php echo base_url(); ?>machine_chain/machine_chain_forward/'+ machine_chain_id_hidden +'/'+ machine_chain_detail_id_hidden +'/'+ forward_wt +'/');
            }
            $('#forward_lineitem_popup').modal('hide');
        });

        $('#forward_lineitem_popup').on('hidden.bs.modal', function () {
            $("#forward_wt").val('');
            $("#forward_wt_hidden").val('');
            
        });
    
    });
    
    function get_category_from_item(item_id){
        if(item_id != '' && item_id != null){
            var category_id = '';
            $.ajax({
                url: "<?php echo base_url('machine_chain/get_category_from_item'); ?>/",
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
                                pts_lineitem_objectdata[index].machine_chain_detail_id = li_value.machine_chain_detail_id;
                                pts_lineitem_objectdata[index].forwarded_from_mcd_id = li_value.forwarded_from_mcd_id;
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
        var total_issue_weight = 0;
        var total_issue_less = 0;
        var total_issue_net_wt = 0;
        var total_issue_fine = 0;
        var total_issue_tunch = 0;
        var total_issue_actual_fine = 0;
        var total_issue_real_actual_fine = 0;
        var total_issue_pcs = 0;
        var total_issue_fw_weight = 0;
        var total_issue_fw_less = 0;
        var total_issue_fw_net_wt = 0;
        var total_issue_fw_fine = 0;
        var total_issue_fw_tunch = 0;
        var total_issue_fw_actual_fine = 0;
        var total_issue_fw_real_actual_fine = 0;
        var total_issue_fw_pcs = 0;
        var total_issue_s_weight = 0;
        var total_issue_s_less = 0;
        var total_issue_s_net_wt = 0;
        var total_issue_s_fine = 0;
        var total_issue_s_tunch = 0;
        var total_issue_s_actual_fine = 0;
        var total_issue_s_real_actual_fine = 0;
        var total_issue_s_pcs = 0;
        var total_receive_weight = 0;
        var total_receive_less = 0;
        var total_receive_net_wt = 0;
        var total_receive_fine = 0;
        var total_receive_tunch = 0;
        var total_receive_actual_fine = 0;
        var total_receive_real_actual_fine = 0;
        var total_receive_pcs = 0;
        var total_receive_fw_weight = 0;
        var total_receive_fw_less = 0;
        var total_receive_fw_net_wt = 0;
        var total_receive_fw_fine = 0;
        var total_receive_fw_tunch = 0;
        var total_receive_fw_actual_fine = 0;
        var total_receive_fw_real_actual_fine = 0;
        var total_receive_fw_pcs = 0;
        var total_receive_s_weight = 0;
        var total_receive_s_less = 0;
        var total_receive_s_net_wt = 0;
        var total_receive_s_fine = 0;
        var total_receive_s_tunch = 0;
        var total_receive_s_actual_fine = 0;
        var total_receive_s_real_actual_fine = 0;
        var total_receive_s_pcs = 0;
        
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
            var lineitem_order_item_btn = '';
            var lineitem_delete_btn = '';
            var weight = parseFloat(value.weight) || 0;
            var forwarded_weight = parseFloat(value.forwarded_weight) || 0;
            var available_weight_for_forward = parseFloat(value.available_weight_for_forward) || 0;
            var less = parseFloat(value.less) || 0;
            var net_wt = parseFloat(value.net_wt) || 0;
            var fine = parseFloat(value.fine) || 0;
            var pcs = parseFloat(value.pcs) || 0;

            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_machine_chain_item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';

            lineitem_order_item_btn = '<a class="btn btn-xs btn-primary btn_show_mcd_order_items" data-lineitem_index=' + index + ' href="javascript:void(0);"><i class="fa fa-list"></i></a> ';

            if(value.machine_chain_item_delete == 'allow'){
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_machine_chain_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a> ';
            }
            var lineitem_forward_btn = '';
            var lineitem_forward_chk = '';
            <?php if (isset($machine_chain_data->machine_chain_id) && !empty($machine_chain_data->machine_chain_id) && $machine_chain_data->entry_mode == 'edit') { ?>
                var machine_chain_id = '<?php echo $machine_chain_data->machine_chain_id; ?>';
                if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>' && (is_calculated == '1' || display_calculate_button == '0') && available_weight_for_forward > 0) {
                    var lineitem_forward_btn = '<br><span data-weight="'+ weight +'" data-forwarded_weight="'+ forwarded_weight +'" data-available_weight_for_forward="'+ available_weight_for_forward +'" data-machine_chain_id="'+ machine_chain_id +'" data-machine_chain_detail_id="'+ value.machine_chain_detail_id +'" class="btn btn-xs btn-default machine_chain_forward" style="margin-top: 3px;"><i class="fa fa-mail-forward" style="color : #279B8D;">&nbsp;</i></span> ';

                    var lineitem_forward_chk = '<input type="checkbox" name="forward_rfw[]" value="'+ index +'" style="height: 20px; width: 20px;" class="forward_rfw_chk">';
                    
                }
            <?php } ?>

            var lineitem_add_rfw_from_iwf_btn = '';

            var type_value = '';
            if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?>'){
                type_value = 'IFW';
                if($("#use_selected_tunch").val() == "1") {
                    lineitem_add_rfw_from_iwf_btn = '<a class="btn btn-xs btn-primary btn-edit-item add_rfw_from_iwf_btn" href="javascript:void(0);" onclick="add_rfw_from_iwf(' + index + ')"><i class="fa fa-mail-reply"></i></a> ';
                } else {
                    lineitem_add_rfw_from_iwf_btn = '<a class="btn btn-xs btn-primary btn-edit-item add_rfw_from_iwf_btn hidden" href="javascript:void(0);" onclick="add_rfw_from_iwf(' + index + ')"><i class="fa fa-mail-reply"></i></a> ';
                }
                

            } else if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>'){
                type_value = 'IS';
            } else if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>'){
                type_value = 'RFW';
            } else if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID; ?>'){
                type_value = 'RS';
            }

            if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>' && forwarded_weight > 0) {
                lineitem_edit_btn = '';
                lineitem_delete_btn = '';
            }
            
            var actual_fine = parseFloat(net_wt) * parseFloat(value.actual_tunch) / 100;
            var real_actual_fine = parseFloat(net_wt) * parseFloat(value.real_actual_tunch) / 100;
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    lineitem_order_item_btn +
                    lineitem_forward_btn +
                    lineitem_forward_chk +
                    lineitem_add_rfw_from_iwf_btn +
                    '</td>' +
                    '<td>' + type_value + '</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right">' + weight.toFixed(3) + '</td>' +
                    '<td class="text-right">' + less.toFixed(3) + '</td>' +
                    '<td class="text-right">' + net_wt.toFixed(3) + '</td>' +
                    '<td class="text-right">' + value.purity + '</td>' +
                    '<td class="text-right">' + value.actual_tunch + '</td>' +
                    '<td class="text-right">' + fine.toFixed(3) + '</td>'+
                    '<td class="text-right">' + actual_fine.toFixed(3) + '</td>'+
                    '<td class="text-nowrap">' + value.machine_chain_detail_date + '</td>'+
                    '<td>' + value.machine_chain_detail_remark + '</td>';
            if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID; ?>'){
                total_receive_weight = parseFloat(total_receive_weight) + parseFloat(weight);
                total_receive_less = parseFloat(total_receive_less) + parseFloat(less);
                total_receive_net_wt = parseFloat(total_receive_net_wt) + parseFloat(net_wt);
                total_receive_fine = parseFloat(total_receive_fine) + parseFloat(fine);
                total_receive_pcs = parseFloat(total_receive_pcs) + parseFloat(pcs);
                new_receive_lineitem_html += row_html;
                total_receive_tunch = (parseFloat(total_receive_fine) / parseFloat(total_receive_net_wt)) * 100;
                total_receive_tunch = total_receive_tunch || 0;
                total_receive_actual_fine = parseFloat(total_receive_actual_fine) + parseFloat(actual_fine);
                total_receive_real_actual_fine = parseFloat(total_receive_real_actual_fine) + parseFloat(real_actual_fine);
                
                if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>'){
                    total_receive_fw_weight = parseFloat(total_receive_fw_weight) + parseFloat(weight);
                    total_receive_fw_less = parseFloat(total_receive_fw_less) + parseFloat(less);
                    total_receive_fw_net_wt = parseFloat(total_receive_fw_net_wt) + parseFloat(net_wt);
                    total_receive_fw_fine = parseFloat(total_receive_fw_fine) + parseFloat(fine);
                    total_receive_fw_pcs = parseFloat(total_receive_fw_pcs) + parseFloat(pcs);
                    total_receive_fw_tunch = (parseFloat(total_receive_fw_fine) / parseFloat(total_receive_fw_net_wt)) * 100;
                    total_receive_fw_tunch = total_receive_fw_tunch || 0;
                    total_receive_fw_actual_fine = parseFloat(total_receive_fw_actual_fine) + parseFloat(actual_fine);
                    total_receive_fw_real_actual_fine = parseFloat(total_receive_fw_real_actual_fine) + parseFloat(real_actual_fine);
                }
                if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID; ?>'){
                    total_receive_s_weight = parseFloat(total_receive_s_weight) + parseFloat(weight);
                    total_receive_s_less = parseFloat(total_receive_s_less) + parseFloat(less);
                    total_receive_s_net_wt = parseFloat(total_receive_s_net_wt) + parseFloat(net_wt);
                    total_receive_s_fine = parseFloat(total_receive_s_fine) + parseFloat(fine);
                    total_receive_s_pcs = parseFloat(total_receive_s_pcs) + parseFloat(pcs);
                    total_receive_s_tunch = (parseFloat(total_receive_s_fine) / parseFloat(total_receive_s_net_wt)) * 100;
                    total_receive_s_tunch = total_receive_s_tunch || 0;
                    total_receive_s_actual_fine = parseFloat(total_receive_s_actual_fine) + parseFloat(actual_fine);
                    total_receive_s_real_actual_fine = parseFloat(total_receive_s_real_actual_fine) + parseFloat(real_actual_fine);
                }
                
            } else if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>'){
                total_issue_weight = parseFloat(total_issue_weight) + parseFloat(weight);
                total_issue_less = parseFloat(total_issue_less) + parseFloat(less);
                total_issue_net_wt = parseFloat(total_issue_net_wt) + parseFloat(net_wt);
                total_issue_fine = parseFloat(total_issue_fine) + parseFloat(fine);
                total_issue_pcs = parseFloat(total_issue_pcs) + parseFloat(pcs);
                new_issue_lineitem_html += row_html;
                total_issue_tunch = (parseFloat(total_issue_fine) / parseFloat(total_issue_net_wt)) * 100;
                total_issue_tunch = total_issue_tunch || 0;
                total_issue_actual_fine = parseFloat(total_issue_actual_fine) + parseFloat(actual_fine);
                total_issue_real_actual_fine = parseFloat(total_issue_real_actual_fine) + parseFloat(real_actual_fine);
                
                if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID; ?>'){
                    total_issue_fw_weight = parseFloat(total_issue_fw_weight) + parseFloat(weight);
                    total_issue_fw_less = parseFloat(total_issue_fw_less) + parseFloat(less);
                    total_issue_fw_net_wt = parseFloat(total_issue_fw_net_wt) + parseFloat(net_wt);
                    total_issue_fw_fine = parseFloat(total_issue_fw_fine) + parseFloat(fine);
                    total_issue_fw_pcs = parseFloat(total_issue_fw_pcs) + parseFloat(pcs);
                    total_issue_fw_tunch = (parseFloat(total_issue_fw_fine) / parseFloat(total_issue_fw_net_wt)) * 100;
                    total_issue_fw_tunch = total_issue_fw_tunch || 0;
                    total_issue_fw_actual_fine = parseFloat(total_issue_fw_actual_fine) + parseFloat(actual_fine);
                    total_issue_fw_real_actual_fine = parseFloat(total_issue_fw_real_actual_fine) + parseFloat(real_actual_fine);
                }
                if(value.type_id == '<?php echo MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID; ?>'){
                    total_issue_s_weight = parseFloat(total_issue_s_weight) + parseFloat(weight);
                    total_issue_s_less = parseFloat(total_issue_s_less) + parseFloat(less);
                    total_issue_s_net_wt = parseFloat(total_issue_s_net_wt) + parseFloat(net_wt);
                    total_issue_s_fine = parseFloat(total_issue_s_fine) + parseFloat(fine);
                    total_issue_s_pcs = parseFloat(total_issue_s_pcs) + parseFloat(pcs);
                    total_issue_s_tunch = (parseFloat(total_issue_s_fine) / parseFloat(total_issue_s_net_wt)) * 100;
                    total_issue_s_tunch = total_issue_s_tunch || 0;
                    total_issue_s_actual_fine = parseFloat(total_issue_s_actual_fine) + parseFloat(actual_fine);
                    total_issue_s_real_actual_fine = parseFloat(total_issue_s_real_actual_fine) + parseFloat(real_actual_fine);
                }
            }

        });
        $('#issue_lineitem_list').html(new_issue_lineitem_html);
        $('#total_issue_weight').html(total_issue_weight.toFixed(3));
        $('#total_issue_less').html(total_issue_less.toFixed(3));
        $('#total_issue_net_wt').html(total_issue_net_wt.toFixed(3));
        $('#total_issue_fine').html(total_issue_fine.toFixed(3));
        $('#total_issue_actual_fine').html(total_issue_actual_fine.toFixed(3));
        $('#total_issue_real_actual_fine').val(total_issue_real_actual_fine.toFixed(3));
        $('#total_issue_tunch').html(total_issue_tunch.toFixed(2));
        var total_issue_actual_tunch = ((parseFloat(total_issue_actual_fine) / parseFloat(total_issue_net_wt)) * 100) || 0;
        $('#total_issue_actual_tunch').html(total_issue_actual_tunch.toFixed(2));
        var total_issue_real_actual_tunch = ((parseFloat(total_issue_real_actual_fine) / parseFloat(total_issue_net_wt)) * 100) || 0;
        $('#total_issue_real_actual_tunch').val(total_issue_real_actual_tunch.toFixed(2));
        
        $('#total_issue_fw_weight').html(total_issue_fw_weight.toFixed(3));
        $('#total_issue_fw_less').html(total_issue_fw_less.toFixed(3));
        $('#total_issue_fw_net_wt').html(total_issue_fw_net_wt.toFixed(3));
        $('#total_issue_fw_fine').html(total_issue_fw_fine.toFixed(3));
        $('#total_issue_fw_actual_fine').html(total_issue_fw_actual_fine.toFixed(3));
        $('#total_issue_fw_real_actual_fine').val(total_issue_fw_real_actual_fine.toFixed(3));
        $('#total_issue_fw_tunch').html(total_issue_fw_tunch.toFixed(2));
        var total_issue_fw_actual_tunch = ((parseFloat(total_issue_fw_actual_fine) / parseFloat(total_issue_fw_net_wt)) * 100) || 0;
        $('#total_issue_fw_actual_tunch').html(total_issue_fw_actual_tunch.toFixed(2));
        var total_issue_fw_real_actual_tunch = ((parseFloat(total_issue_fw_real_actual_fine) / parseFloat(total_issue_fw_net_wt)) * 100) || 0;
        $('#total_issue_fw_real_actual_tunch').val(total_issue_fw_real_actual_tunch.toFixed(2));
        
        $('#total_issue_s_weight').html(total_issue_s_weight.toFixed(3));
        $('#total_issue_s_less').html(total_issue_s_less.toFixed(3));
        $('#total_issue_s_net_wt').html(total_issue_s_net_wt.toFixed(3));
        $('#total_issue_s_fine').html(total_issue_s_fine.toFixed(3));
        $('#total_issue_s_actual_fine').html(total_issue_s_actual_fine.toFixed(3));
        $('#total_issue_s_real_actual_fine').val(total_issue_s_real_actual_fine.toFixed(3));
        $('#total_issue_s_tunch').html(total_issue_s_tunch.toFixed(2));
        var total_issue_s_actual_tunch = ((parseFloat(total_issue_s_actual_fine) / parseFloat(total_issue_s_net_wt)) * 100) || 0;
        $('#total_issue_s_actual_tunch').html(total_issue_s_actual_tunch.toFixed(2));
        var total_issue_s_real_actual_tunch = ((parseFloat(total_issue_s_real_actual_fine) / parseFloat(total_issue_s_net_wt)) * 100) || 0;
        $('#total_issue_s_real_actual_tunch').val(total_issue_s_real_actual_tunch.toFixed(2));
        
        $('#receive_lineitem_list').html(new_receive_lineitem_html);
        $('#total_receive_weight').html(total_receive_weight.toFixed(3));
        $('#total_receive_less').html(total_receive_less.toFixed(3));
        $('#total_receive_net_wt').html(total_receive_net_wt.toFixed(3));
        $('#total_receive_fine').html(total_receive_fine.toFixed(3));
        $('#total_receive_actual_fine').html(total_receive_actual_fine.toFixed(3));
        $('#total_receive_real_actual_fine').val(total_receive_real_actual_fine.toFixed(3));
        $('#total_receive_tunch').html(total_receive_tunch.toFixed(2));
        var total_receive_actual_tunch = ((parseFloat(total_receive_actual_fine) / parseFloat(total_receive_net_wt)) * 100) || 0;
        $('#total_receive_actual_tunch').html(total_receive_actual_tunch.toFixed(2));
        var total_receive_real_actual_tunch = ((parseFloat(total_receive_real_actual_fine) / parseFloat(total_receive_net_wt)) * 100) || 0;
        $('#total_receive_real_actual_tunch').val(total_receive_real_actual_tunch.toFixed(2));
        
        $('#total_receive_fw_weight').html(total_receive_fw_weight.toFixed(3));
        $('#total_receive_fw_less').html(total_receive_fw_less.toFixed(3));
        $('#total_receive_fw_net_wt').html(total_receive_fw_net_wt.toFixed(3));
        $('#total_receive_fw_fine').html(total_receive_fw_fine.toFixed(3));
        $('#total_receive_fw_actual_fine').html(total_receive_fw_actual_fine.toFixed(3));
        $('#total_receive_fw_real_actual_fine').val(total_receive_fw_real_actual_fine.toFixed(3));
        $('#total_receive_fw_tunch').html(total_receive_fw_tunch.toFixed(2));
        var total_receive_fw_actual_tunch = ((parseFloat(total_receive_fw_actual_fine) / parseFloat(total_receive_fw_net_wt)) * 100) || 0;
        $('#total_receive_fw_actual_tunch').html(total_receive_fw_actual_tunch.toFixed(2));
        var total_receive_fw_real_actual_tunch = ((parseFloat(total_receive_fw_real_actual_fine) / parseFloat(total_receive_fw_net_wt)) * 100) || 0;
        $('#total_receive_fw_real_actual_tunch').val(total_receive_fw_real_actual_tunch.toFixed(2));
        
        $('#total_receive_s_weight').html(total_receive_s_weight.toFixed(3));
        $('#total_receive_s_less').html(total_receive_s_less.toFixed(3));
        $('#total_receive_s_net_wt').html(total_receive_s_net_wt.toFixed(3));
        $('#total_receive_s_fine').html(total_receive_s_fine.toFixed(3));
        $('#total_receive_s_actual_fine').html(total_receive_s_actual_fine.toFixed(3));
        $('#total_receive_s_real_actual_fine').val(total_receive_s_real_actual_fine.toFixed(3));
        $('#total_receive_s_tunch').html(total_receive_s_tunch.toFixed(2));
        var total_receive_s_actual_tunch = ((parseFloat(total_receive_s_actual_fine) / parseFloat(total_receive_s_net_wt)) * 100) || 0;
        $('#total_receive_s_actual_tunch').html(total_receive_s_actual_tunch.toFixed(2));
        var total_receive_s_real_actual_tunch = ((parseFloat(total_receive_s_real_actual_fine) / parseFloat(total_receive_s_net_wt)) * 100) || 0;
        $('#total_receive_s_real_actual_tunch').val(total_receive_s_real_actual_tunch.toFixed(2));
        
        $('#save_machine_chain').append('<input type="hidden" name="total_receive_fw_weight" id="total_receive_fw_weight" value="' + total_receive_fw_weight + '" />');
        $('#save_machine_chain').append('<input type="hidden" name="total_issue_weight" id="total_issue_weight" value="' + total_issue_weight + '" />');
        $('#save_machine_chain').append('<input type="hidden" name="total_receive_weight" id="total_receive_weight" value="' + total_receive_weight + '" />');

        $('#save_machine_chain').append('<input type="hidden" name="total_issue_net_wt" id="total_issue_net_wt" value="' + total_issue_net_wt + '" />');
        $('#save_machine_chain').append('<input type="hidden" name="total_receive_net_wt" id="total_receive_net_wt" value="' + total_receive_net_wt + '" />');
        $('#save_machine_chain').append('<input type="hidden" name="total_issue_fine" id="total_issue_fine" value="' + total_issue_fine + '" />');
        $('#save_machine_chain').append('<input type="hidden" name="total_receive_fine" id="total_receive_fine" value="' + total_receive_fine + '" />');
        $('#ajax-loader').hide();
        set_total_weight();
        set_total_net_wt();
        set_total_fine();

        if($(".forward_rfw_chk:visible").length > 0) {
            $("#btn_forward_selected_rfw").removeClass('hidden');
            $("#btn_forward_selected_rfw_to_old").removeClass('hidden');
        } else {
            $("#btn_forward_selected_rfw").addClass('hidden');
            $("#btn_forward_selected_rfw_to_old").addClass('hidden');
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

    function add_rfw_from_iwf(index) {        
        var value = lineitem_objectdata[index];
        var new_index = lineitem_objectdata.length;
        var value = JSON.parse(JSON.stringify(value));
        value['type_id'] = '<?php echo MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID; ?>';
        value['type_name'] = 'Receive Finish Work';
        value['added_from_ifw_mcd_index'] =  index;
        value['added_from_ifw_mcd_id'] =  value['machine_chain_detail_id'];
        value['machine_chain_detail_id'] =  null;
        value['forwarded_from_mcd_id'] =  null;

        //$("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_machine_chain_item").addClass('hide');
        $("#line_items_index").val('');

        if (edit_lineitem_inc == 0) {
            edit_lineitem_inc = 1;
            $(".add_lineitem").removeAttr("disabled");
        }
        
        if(value.tunch_textbox == 1){
            $("#tunch_textbox").prop("checked", true).trigger('change');
        } else {
            $("#tunch_textbox").prop("checked", false).trigger('change');
        }

        $("#machine_chain_item_delete").val(value.machine_chain_item_delete);
        if(typeof(value.machine_chain_detail_id) !== "undefined" && value.machine_chain_detail_id !== null) {
            $("#machine_chain_detail_id").val(value.machine_chain_detail_id);
        }
        if(typeof(value.forwarded_from_mcd_id) !== "undefined" && value.forwarded_from_mcd_id !== null) {
            $("#forwarded_from_mcd_id").val(value.forwarded_from_mcd_id);
            $("#weight_limit").val(value.weight_limit);
        } else {
            $("#forwarded_from_mcd_id").val('');
            $("#weight_limit").val('');
        }
        
        $("#added_from_ifw_mcd_index").val(value.added_from_ifw_mcd_index);
        $("#added_from_ifw_mcd_id").val(value.added_from_ifw_mcd_id);
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
        $("#real_actual_tunch").val(value.real_actual_tunch);
        $("#fine").val(value.fine);
        $("#pcs").val(value.pcs);
        $("#machine_chain_detail_date").val(value.machine_chain_detail_date);
        $("#machine_chain_detail_date").datepicker('setDate', value.machine_chain_detail_date);
        $("#machine_chain_detail_remark").val(value.machine_chain_detail_remark);
        $('#total_grwt_sell').val(value.total_grwt_sell);
        if(value.machine_chain_item_delete == 'not_allow'){
            $('#type_id').attr('disabled','disabled');
            $('#item_id').attr('disabled','disabled');
            $('.touch_id').attr('disabled','disabled');
            $('#tunch_textbox').attr('disabled','disabled');
        }
        is_calculated = '0';
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        //$("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_machine_chain_item").addClass('hide');
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
        $("#machine_chain_item_delete").val(value.machine_chain_item_delete);
        if(typeof(value.machine_chain_detail_id) !== "undefined" && value.machine_chain_detail_id !== null) {
            $("#machine_chain_detail_id").val(value.machine_chain_detail_id);
        }
        if(typeof(value.forwarded_from_mcd_id) !== "undefined" && value.forwarded_from_mcd_id !== null) {
            $("#forwarded_from_mcd_id").val(value.forwarded_from_mcd_id);
            $("#weight_limit").val(value.weight_limit);
        } else {
            $("#forwarded_from_mcd_id").val('');
            $("#weight_limit").val('');
        }
        $("#added_from_ifw_mcd_index").val(value.added_from_ifw_mcd_index);
        $("#added_from_ifw_mcd_id").val(value.added_from_ifw_mcd_id);
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
        $("#real_actual_tunch").val(value.real_actual_tunch);
        $("#fine").val(value.fine);
        $("#pcs").val(value.pcs);
        $("#machine_chain_detail_date").val(value.machine_chain_detail_date);
        $("#machine_chain_detail_date").datepicker('setDate', value.machine_chain_detail_date);
        $("#machine_chain_detail_remark").val(value.machine_chain_detail_remark);
        $('#total_grwt_sell').val(value.total_grwt_sell);
        if(value.machine_chain_item_delete == 'not_allow'){
            $('#type_id').attr('disabled','disabled');
            $('#item_id').attr('disabled','disabled');
            $('.touch_id').attr('disabled','disabled');
            $('#tunch_textbox').attr('disabled','disabled');
        }
        is_calculated = '0';
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        value = lineitem_objectdata[index];
        var deleted_mcd_id = 0;
        var deleted_mcd_index = index;
        if (confirm('Are you sure ?')) {
            if (typeof (value.machine_chain_detail_id) !== "undefined" && value.machine_chain_detail_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.machine_chain_detail_id + '" />');
                deleted_mcd_id = value.machine_chain_detail_id;
            }
            lineitem_objectdata.splice(index, 1);

            $.each(lineitem_objectdata, function (index, value) {
                if(value.added_from_ifw_mcd_id == deleted_mcd_id) {
                    value.added_from_ifw_mcd_id = '';    
                }
                if(value.added_from_ifw_mcd_index == deleted_mcd_index) {
                    value.added_from_ifw_mcd_index = '';    
                }
            });
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
    
    function get_selected_order_items(checked_order_items){
        $.ajax({
            url: "<?php echo base_url('machine_chain/get_selected_order_items'); ?>/",
            type: "POST",
            async: false,
            data: {checked_order_items : checked_order_items},
            success: function (response) {
                var json = $.parseJSON(response);
                if (json['order_items'] != '') {
                    var order_items_objectdata = json['order_items'];
                    var row_html_order = '';
                    $.each(order_items_objectdata, function (li_index, li_value) {
                        var is_checked = '';
                        var value_machine_chain_oi_id = '';
                        var value_machine_chain_id = '';
                        $.each(checked_order_items, function (it_index, it_value) {
                            if(it_value.order_lot_item_id == li_value.order_lot_item_id){
                                is_checked = 'checked';
                                if(typeof(it_value.machine_chain_oi_id) !== "undefined" && it_value.machine_chain_oi_id !== null) {
                                    value_machine_chain_oi_id = it_value.machine_chain_oi_id;
                                }
                                if(typeof(it_value.machine_chain_id) !== "undefined" && it_value.machine_chain_id !== null) {
                                    value_machine_chain_id = it_value.machine_chain_id;
                                }
                                return false; // breaks
                            }
                        });
                        row_html_order += '<tr class="items_row">';
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
                        row_html_order += '<td class="text-right">&nbsp;</td>';
                        row_html_order += '<td class="text-right"><b>' + json['total_weight'] + '</b></td>';
                        row_html_order += '<td class="text-right"><b>' + json['total_pcs'] + '</b></td>';
                        row_html_order += '</tr>';
                    $("#selected_order_items_list").html(row_html_order);

                    var checked_order_lot_item_ids = [];
                    $.each(checked_order_items, function (it_index, it_value) {
                        checked_order_lot_item_ids.push(it_value.order_lot_item_id);
                    });

                    $.each(lineitem_objectdata,function (tmp_index,tmp_value) {
                        var mcd_checked_order_items = tmp_value.mcd_checked_order_items;
                        if(mcd_checked_order_items.length > 0) {
                            console.log('mcd_checked_order_items');
                            console.log(mcd_checked_order_items);
                            $.each(mcd_checked_order_items,function (tmp_index1,tmp_value1) {
                                console.log('tmp_value1');
                                console.log(tmp_value1);
                                if(jQuery.inArray(tmp_value1.order_lot_item_id, checked_order_lot_item_ids) !== -1) {
                                } else {
                                    mcd_checked_order_items.splice(tmp_index1,1);
                                }
                            });
                            lineitem_objectdata[tmp_index].mcd_checked_order_items = mcd_checked_order_items;
                        }
                    });
                } else {
                    $.each(lineitem_objectdata,function (tmp_index,tmp_value) {
                        var mcd_checked_order_items = tmp_value.mcd_checked_order_items;
                        if(mcd_checked_order_items.length > 0) {
                            lineitem_objectdata[tmp_index].mcd_checked_order_items = [];
                        }
                    });
                    $("#selected_order_items_list").html('');
                }
            }
        });
    }
    
    function display_calculate_btn(operation_id){
        $.ajax({
            url: "<?php echo base_url('machine_chain/get_machine_chain_operation_detail'); ?>/" + operation_id,
            type: "GET",
            async: false,
            data: "",
            success: function (response) {
                var json = $.parseJSON(response);
                var calculate_btn_div = ''
                if(json.calculate_button == '1' && is_calculated == '0'){
                    calculate_btn_div += '<span id="calculate_btn" class="btn btn-warning btn-xs pull-left" >Calculate & Save</span>';
                    display_calculate_button = '1';
                }
                $("#issue_change_actual_tunch_allow").val(json.issue_change_actual_tunch_allow);
                $("#receive_change_actual_tunch_allow").val(json.receive_change_actual_tunch_allow);
                $("#use_selected_tunch").val(json.use_selected_tunch);
                $('#calculate_btn_div').html(calculate_btn_div);

                if($("#use_selected_tunch").val() == '1') {
                    $(".add_rfw_from_iwf_btn").removeClass('hidden');
                } else {
                    $(".add_rfw_from_iwf_btn").addClass('hidden');
                }
            }
        });
    }
</script>
