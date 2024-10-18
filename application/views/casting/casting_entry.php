<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('casting/save_casting_entry') ?>" method="post" id="save_manu_hand_made" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($casting_data->ce_id) && !empty($casting_data->ce_id)) { ?>
            <input type="hidden" name="ce_id" class="ce_id" value="<?= $casting_data->ce_id ?>">
        <?php } ?>
            <input type="hidden" id="total_grwt_sell" value=""/>

        <!-- Content Header (Page header) -->
     
        <section class="content-header">
            <h1>
                Add Casting
                <?php 
                $isEdit = $this->app_model->have_access_role(CASTING_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(CASTING_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(CASTING_MODULE_ID, "add"); 
                $allow_change_date = $this->app_model->have_access_role(CASTING_MODULE_ID, "allow_change_date");
                ?>
                <?php if(isset($casting_data->ce_id) && !empty($casting_data->ce_id)) { } else { if(isset($isAdd) && $isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($casting_data->ce_id) ? '' : $btn_disable;?>><?= isset($casting_data->ce_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('casting/casting_entry_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Casting List</a>
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
                                    <input type="hidden" name="department_id" id="department_id" value="<?php echo CASTING_DEPARTMENT_ACCOUNT_ID; ?>">
                                    <div class="col-md-4">
                                        <label for="worker_id">Worker<span class="required-sign">&nbsp;*</span></label>
                                        <select name="worker_id" id="worker_id" class="form-control select2" ></select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date">Date<span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" name="ce_date" id="datepicker2" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control input-datepicker" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?= (isset($casting_data->ce_date)) ? date('d-m-Y', strtotime($casting_data->ce_date)) : date('d-m-Y'); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="from_casting_status_id">From Casting Status<span class="required-sign">&nbsp;*</span></label>
                                        <select name="from_casting_status_id" id="from_casting_status_id" class="form-control select2" >
                                        </select>
                                    </div>
                                    <?php if(isset($casting_data->from_casting_status_id) && !empty($casting_data->from_casting_status_id)){ ?>
                                        <input type="hidden" name="from_casting_status_id" value="<?= $casting_data->from_casting_status_id; ?>" >
                                    <?php } ?>
                                    <div class="col-md-3">
                                        <label for="to_casting_status_id">To Casting Status<span class="required-sign">&nbsp;*</span></label>
                                        <select name="to_casting_status_id" id="to_casting_status_id" class="form-control select2" >
                                        </select>
                                    </div>
                                    <div class="clearfix"></div><br/>
                                    <div class="col-md-4">
                                        <label for="ce_remark">Remark</label>
                                        <textarea name="ce_remark" id="ce_remark" class="form-control"><?php echo (isset($casting_data->ce_remark)) ? $casting_data->ce_remark : ''; ?></textarea><br />
                                    </div>
                                    <div class="col-md-2">
                                        <div class="clearfix"></div>
                                        <label for="lott_complete">Lott Complete ?</label><br>
                                        <?php if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"allow to lott complete")) { ?>
                                            <label><input type="radio" name="lott_complete" class="iradio_minimal-blue" value="1" <?= (isset($casting_data->lott_complete)) && $casting_data->lott_complete == 1 ? 'checked' : ''; ?>> Yes</label> &nbsp;&nbsp;&nbsp;
                                            <label><input type="radio" name="lott_complete" class="iradio_minimal-blue" value="0" <?= (isset($casting_data->lott_complete)) ? $casting_data->lott_complete == 0 ? 'checked' : '' : 'checked'; ?>> No</label>
                                        <?php } else { ?>
                                            <?= (isset($casting_data->lott_complete)) && $casting_data->lott_complete == 1 ? 'Yes' : 'No'; ?>
                                            <input type="hidden" name="lott_complete" value="<?= (isset($casting_data->lott_complete)) && $casting_data->lott_complete == 1 ? '1' : '0'; ?>">
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-3 pull-right" id="worker_id_div" <?= (isset($casting_data->to_casting_status_id)) && $casting_data->to_casting_status_id == MANUFACTURE_STATUS_IN_CAD ? '' : 'style="display: none;"'; ?> >
                                        <label for="">CAD Designer</label>
                                        <select name="cad_worker_id" id="cad_worker_id" class="form-control"></select>
                                    </div>
                                    <div class="col-md-3 pull-right design_files_div" <?= (isset($casting_data->to_casting_status_id)) && $casting_data->to_casting_status_id == MANUFACTURE_STATUS_DESIGN_READY ? '' : 'style="display: none;"'; ?>>
                                        <label for="design_files">Design Files</label>
                                        <input type="file" name="design_files[]" id="design_files" multiple="" class="form-control">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4 pull-right design_files_div" <?= (isset($casting_data->to_casting_status_id)) && $casting_data->to_casting_status_id == MANUFACTURE_STATUS_DESIGN_READY ? '' : 'style="display: none;"'; ?>>
                                        <h4 style="text-align: center">Design Files</h4>
                                        <table style="" border="1" class="table custom-table item-table">
                                            <thead>
                                                <tr>
                                                    <th width="80px">Action</th>
                                                    <th>File</th>
                                                </tr>
                                            </thead>
                                            <tbody id="">
                                                <?php if(isset($casting_files_details) && !empty($casting_files_details)){ ?>
                                                    <?php foreach($casting_files_details as $casting_file){ ?>
                                                        <tr id="design_file_id_<?= $casting_file->design_file_id; ?>">
                                                            <td style="text-align: center; vertical-align: middle;"><a href="javascript:void(0);" class="" onclick="delete_design_file(<?= $casting_file->design_file_id; ?>);"><span class="glyphicon glyphicon-trash" style="color : red"></span></a></td>
                                                            <td><img class="" src="<?= base_url() ?>uploads/casting_images/<?= $casting_file->design_file_name; ?>" style="width: 90px; height: 70px;" alt=""></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix"></div><br/>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm" id="order_items_btn" ><b> Order Items</b></button>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="line_item_form item_fields_div">
                                        <h4 class="col-md-12">
                                            Line Item &nbsp;&nbsp;&nbsp;
                                            <span><label style="margin-bottom: 0px;"><input type="checkbox" name="line_items_data[tunch_textbox]" id="tunch_textbox" > <small>Tunch Textbox</small></label></span>
                                        </h4>
                                        <div class="clearfix"></div><br />
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <input type="hidden" name="line_items_data[ce_item_delete]" id="ce_item_delete" value="allow" />
                                        <?php if(isset($ce_detail) && !empty($ce_detail)){ ?>
                                            <input type="hidden" name="line_items_data[ce_detail_id]" id="ce_detail_id" />
                                            <input type="hidden" name="line_items_data[purchase_sell_item_id]" id="purchase_sell_item_id"/>
                                            <input type="hidden" name="line_items_data[stock_type]" id="stock_type" />
                                        <?php } ?>
                                        <div class="col-md-2">
                                            <label for="type">Type<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[type_id]" class="form-control type_id" id="type_id">
                                                <option value=""> - Select - </option>
                                                <option value="<?php echo CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID; ?>">Issue Finish Work</option>
                                                <option value="<?php echo CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID; ?>">Issue Scrap</option>
                                                <option value="<?php echo CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID; ?>">Receive Finish Work</option>
                                                <option value="<?php echo CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID; ?>">Receive Scrap</option>
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
                                            <label for="ce_detail_date">Date<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[ce_detail_date]" class="form-control datepicker" id="ce_detail_date" placeholder="" ><br />
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-1">
                                            <label for="ad_weight">Ad Weight</label>
                                            <input type="text" name="line_items_data[ad_weight]" class="form-control num_only ad_weight" id="ad_weight"  placeholder="" value=""><br />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="ad_pcs">Ad Pcs</label>
                                            <input type="text" name="line_items_data[ad_pcs]" class="form-control num_only ad_pcs" id="ad_pcs"  placeholder="" value=""><br />
                                        </div>
                                        <div class="col-md-3">
                                            <label for="ce_detail_remark">Remark</label>
                                            <textarea name="line_items_data[ce_detail_remark]" class="form-control" id="ce_detail_remark" placeholder=""></textarea><br />
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
                                                    <th class="text-nowrap">Date</th>
                                                    <th class="text-right">Ad Weight</th>
                                                    <th class="text-right">Ad Pcs</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="receive_lineitem_list"></tbody>
                                            <tfoot>
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
                                                    <th></th>
                                                    <th class="text-right" id="total_receive_ad_weight"></th>
                                                    <th class="text-right" id="total_receive_ad_pcs"></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>Total with AD:</th>
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
                                                    <th class="text-right" id="total_receive_with_ad_ad_weight"></th>
                                                    <th class="text-right" id="total_receive_with_ad_ad_pcs"></th>
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
                                                    <th class="text-nowrap">Date</th>
                                                    <th class="text-right">Ad Weight</th>
                                                    <th class="text-right">Ad Pcs</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="issue_lineitem_list"></tbody>
                                            <tfoot>
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
                                                    <th></th>
                                                    <th class="text-right" id="total_issue_ad_weight"></th>
                                                    <th class="text-right" id="total_issue_ad_pcs"></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>Total with AD:</th>
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
                                                    <th class="text-right" id="total_issue_with_ad_ad_weight"></th>
                                                    <th class="text-right" id="total_issue_with_ad_ad_pcs"></th>
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
                                    <?php if (isset($casting_data->ce_id) && !empty($casting_data->ce_id)) { ?>
                                    <div class="created_updated_info" style="margin-left: 10px;">
                                       Created by : <?php echo isset($casting_data->created_by_name) ? $casting_data->created_by_name :'' ; ?>
                                       @ <?php echo isset($casting_data->created_at) ? date('d-m-Y h:i A',strtotime($casting_data->created_at)) :'' ; ?><br/>
                                       Updated by : <?php echo isset($casting_data->updated_by_name) ? $casting_data->updated_by_name :'' ;?>
                                       @ <?php echo isset($casting_data->updated_at) ? date('d-m-Y h:i A',strtotime($casting_data->updated_at)) : '' ;?>
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
                    <div class="popup_div" style="height: 450px; overflow: scroll;">
                        <table  class="table table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center"><label><input type="checkbox" id="select_all_items" style="height:20px; width:20px" /> Select All</label></th>
                                    <th class="">Item Name</th>
                                    <th class="">Category Name</th>
                                    <th>Order No</th>
                                    <th class="">Order Date</th>
                                    <th class="">Delivery Date</th>
                                    <th class="text-right">Tunch</th>
                                    <th class="text-right">Weight</th>
                                    <th class="text-right">Pcs</th>
                                    <th class="text-center">Image</th>
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
<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Order Item Image</h4>
            </div>
            <div class="modal-body edit-content">
                <img id="doc_img_src" src="" class="img-responsive" height='500px' width='300px'>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var checked_order_items = [];
    var delete_design_file_ids = [];
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
    <?php if (isset($ce_detail)) { ?>
        var li_lineitem_objectdata = [<?php echo $ce_detail; ?>];
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    display_lineitem_html(lineitem_objectdata);
    console.log(lineitem_objectdata);
    $(document).ready(function () {
        $('.type_id, #touch_id').select2();
        $('#to_casting_status_id').select2();
        $('#from_casting_status_id').select2();
        
        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_select2_source') ?>");
        initAjaxSelect2($("#worker_id"), "<?= base_url('app/worker_select2_source') ?>");
        <?php if (isset($casting_data->worker_id)) { ?>
            setSelect2Value($("#worker_id"), "<?= base_url('app/set_worker_select2_val_by_id/' . $casting_data->worker_id) ?>");
        <?php }?>
        <?php if (isset($casting_data->cad_worker_id) && !empty($casting_data->cad_worker_id)) { ?>
            setSelect2Value($("#cad_worker_id"), "<?= base_url('app/set_worker_select2_val_by_id/' . $casting_data->cad_worker_id) ?>");
        <?php }?>
        initAjaxSelect2($("#from_casting_status_id"), "<?= base_url('app/manufacture_status_select2_source') ?>");
        <?php if (isset($casting_data->from_casting_status_id)) { ?>
            setSelect2Value($("#from_casting_status_id"), "<?= base_url('app/set_manufacture_status_select2_val_by_id/' . $casting_data->from_casting_status_id) ?>");
            $("#from_casting_status_id").prop("disabled", true);
        <?php }?>
        initAjaxSelect2($("#to_casting_status_id"), "<?= base_url('app/manufacture_status_select2_source') ?>");
        <?php if (isset($casting_data->to_casting_status_id)) { ?>
            setSelect2Value($("#to_casting_status_id"), "<?= base_url('app/set_manufacture_status_select2_val_by_id/' . $casting_data->to_casting_status_id) ?>");
        <?php }?>
        
        $(document).on('click', '#order_items_btn', function(){
            var from_casting_status_id = $('#from_casting_status_id').val();
            var ce_id = '';
            <?php if (isset($casting_data->ce_id) && !empty($casting_data->ce_id)) { ?>
                ce_id = '<?php echo $casting_data->ce_id; ?>';
            <?php } ?>
            if(from_casting_status_id == '' || from_casting_status_id == null){
                show_notify('Please Select From Casting Status.', false);
//                $("#department_id").select2('open');
                return false;
            } else {
                $.ajax({
                    url: "<?php echo base_url('casting/new_order_casting_item_datatable'); ?>/" + from_casting_status_id + '/' + ce_id,
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
                                var is_ahead_disable = '';
                                var value_ce_oi_id = '';
                                var value_ce_id = '';
                                $.each(checked_order_items, function (it_index, it_value) {
                                    if(it_value.order_lot_item_id == li_value.order_lot_item_id){
                                        is_checked = 'checked';
                                        if(it_value.is_ahead == '1'){
                                            is_ahead_disable = 'disabled';
                                        }
                                        if(typeof(it_value.ce_oi_id) !== "undefined" && it_value.ce_oi_id !== null) {
                                            value_ce_oi_id = it_value.ce_oi_id;
                                        }
                                        if(typeof(it_value.ce_id) !== "undefined" && it_value.ce_id !== null) {
                                            value_ce_id = it_value.ce_id;
                                        }
                                        return false; // breaks
                                    }
                                });
                                row_html_order += '<tr class="items_row">';
                                row_html_order += '<td class="text-center">' +
                                '<input type="checkbox" data-ce_oi_id="' + value_ce_oi_id + '" data-ce_id="' + value_ce_id + '" data-order_id="' + li_value.order_id + '" data-order_lot_item_id="' + li_value.order_lot_item_id + '"  class="checkbox_ch" '+is_checked+' '+is_ahead_disable+' style="height:20px; width:20px"></td>';
                                row_html_order += '<td class="">' + li_value.item_name + '</td>';
                                row_html_order += '<td class="">' + li_value.category_name + '</td>';
                                row_html_order += '<td class="">' + li_value.order_no + '</td>';
                                row_html_order += '<td class="">' + li_value.order_date + '</td>';
                                row_html_order += '<td class="">' + li_value.delivery_date + '</td>';
                                row_html_order += '<td class="text-right">' + li_value.purity + '</td>';
                                row_html_order += '<td class="text-right">' + li_value.weight + '</td>';
                                row_html_order += '<td class="text-right">' + li_value.pcs + '</td>';
                                row_html_order += '<td class="text-center"><a href="javascript:void(0)" class="btn btn-xs btn-primary image_model" data-img_src="' + li_value.item_img + '" ><i class="fa fa-image"></i></a></td>';
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
                                row_html_order += '<td class="text-right"><b>&nbsp;</b></td>';
                                row_html_order += '</tr>';
                            $("#order_items_selection_list").html(row_html_order);
                        } else {
                            $("#order_items_selection_list").html('');
                        }
                    }
                });
            }
        });
        
        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");

            setTimeout(function () {
                $("#doc_img_src").attr('src', src);
            }, 0);
            $('#edit-modal').modal('show');
        });
        
        $(document).on('click', '#select_all_items', function () {
            if($(this).prop('checked') == true){
                $(".checkbox_ch").each(function(){
                    if($(this).prop('disabled') == true){
                    } else {
                        $(this).prop("checked", true);
                    }
                });
            } else {
                $(".checkbox_ch").each(function(){
                    if($(this).prop('disabled') == true){
                    } else {
                        $(this).prop("checked", false);
                    }

                });
            }
        });
        
        $(document).on('click', '#items_save_btn', function () {
            checked_order_items = [];
            $(".checkbox_ch").each(function(){
                if($(this).prop('checked') == true){
                    var ch_arr = {};
                    ch_arr['ce_oi_id'] = $(this).attr('data-ce_oi_id');
                    ch_arr['ce_id'] = $(this).attr('data-ce_id');
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
            $(".delete_ce_item").removeClass('hide');
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
                if($(this).val() == 0){
                    $(".add_lineitem").removeClass('hide');
                    $(".edit_ce_item").removeClass('hide');
                    $(".delete_ce_item").removeClass('hide');
                } else {
                    $(".add_lineitem").addClass('hide');
                    $(".edit_ce_item").addClass('hide');
                    $(".delete_ce_item").addClass('hide');
                }
            }
        });
        <?php if((isset($casting_data->lott_complete)) && $casting_data->lott_complete == 1){ ?>
            $(".add_lineitem").addClass('hide');
            $(".edit_ce_item").addClass('hide');
            $(".delete_ce_item").addClass('hide');
        <?php } ?>
        
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
            if(type_id == <?php echo CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID; ?>){
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
            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();
            var todays_date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
//            $('#ce_detail_date').val(todays_date).trigger('change');
            
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
                            
                            if(type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID; ?>' || type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID; ?>'){
                                var department_id = $('#department_id').val();
                                var ce_id = '';
                                <?php if (isset($casting_data->ce_id) && !empty($casting_data->ce_id)) { ?>
                                    ce_id = '<?php echo $casting_data->ce_id; ?>';
                                <?php } ?>
                                $.ajax({
                                    url: "<?php echo base_url('sell/get_purchase_to_sell_pending_item'); ?>/",
                                    type: 'POST',
                                    async: false,
                                    data: {department_id : department_id, item_id : item_id, ce_id : ce_id, do_not_count_wstg : 1},
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
                pts_lineitem_objectdata[pts_selected_index].ce_item_delete = 'allow';
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
                var d = new Date();

                var month = d.getMonth()+1;
                var day = d.getDate();

                var todays_date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
            
                pts_lineitem_objectdata[pts_selected_index].ce_detail_date = todays_date;
                pts_lineitem_objectdata[pts_selected_index].ce_detail_remark = '';
                pts_lineitem_objectdata[pts_selected_index].ad_weight = '0';
                pts_lineitem_objectdata[pts_selected_index].ad_pcs = '0';
                pts_lineitem_objectdata[pts_selected_index].type_id = type_id;
                
                /******* You not allow to Issue, The Receive of same Entry! Start *******/
                $.each(lineitem_objectdata, function(index, value) {
                    if(typeof(pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id) !== "undefined" && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id !== null && 
                            typeof(value.sell_item_id) !== "undefined" && value.sell_item_id !== null && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id == value.sell_item_id &&
                            (pts_lineitem_objectdata[pts_selected_index].stock_type == <?php echo STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID; ?> || pts_lineitem_objectdata[pts_selected_index].stock_type == <?php echo STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID; ?>)) {
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
//                lineitem_delete.push(values.ce_detail_id);
//            });
//            pts_delete = [];
//            jQuery.each(pts_selected_index_lineitems, function(obj, values) {
//                pts_delete.push(values.ce_detail_id);
//            });
//            var uncheck_sell_item = $(lineitem_delete).not(pts_delete).get();
//            
//            $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + uncheck_sell_item + '" />');
                
            $('#purchase_item_selection_popup').modal('hide');
            
            var remove_arr = [];
            $.each(lineitem_objectdata, function(index, value) {
                if(typeof(value.item_id) !== "undefined" && value.item_id !== null && value.item_id == pts_item_id && (value.type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID; ?>')) {
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
                $("#save_manu_hand_made").submit();
                return false;
            }
        });

        $(document).on('submit', '#save_manu_hand_made', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#worker_id").val()) == '') {
                show_notify('Please Select Worker Name.', false);
                $("#worker_id").select2('open');
                return false;
            }
            var datepicker2 = $("#datepicker2").val();
            if(datepicker2 == '' ){
                show_notify('Please Select Entry Date.', false);
                $("#datepicker2").focus();
                return false;
            }
            if ($.trim($("#from_casting_status_id").val()) == '') {
                show_notify('Please Select From Casting Status.', false);
                $("#worker_id").select2('open');
                return false;
            }
            if ($.trim($("#to_casting_status_id").val()) == '') {
                show_notify('Please Select To Casting Status.', false);
                $("#worker_id").select2('open');
                return false;
            }
            if (lineitem_objectdata == '') {
                show_notify("Please Add Item.", false);
                return false;
            }
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var delete_design_file_objectdata_stringify = JSON.stringify(delete_design_file_ids);
            postData.append('delete_design_file_ids', delete_design_file_objectdata_stringify);
            var checked_order_items_objectdata_stringify = JSON.stringify(checked_order_items);
            postData.append('checked_order_items', checked_order_items_objectdata_stringify);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            $.ajax({
                url: "<?= base_url('casting/save_casting_entry') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    $('.changed-input').removeClass('changed-input');
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Something went Wrong') {
                        $("#ajax-loader").hide();
                        show_notify('Something went Wrong! Please Refresh page and Go ahead.', false);
                    } else if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('casting/casting_entry_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('casting/casting_entry_list') ?>";
                    }
                    return false;
                },
            });
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
            
            if($("#ce_detail_date").val() == '') {
                show_notify('Please Select Manufacture Hand Made Lineitem Date.', false);
                $("#ce_detail_date").focus();
                return false;
            }

            $("#add_lineitem").attr('disabled', 'disabled');
            var ce_detail_id = $("#ce_detail_id").val();
            if (typeof (ce_detail_id) !== "undefined" && ce_detail_id !== null) {
                $('.line_item_form #deleted_lineitem_id[value="' + ce_detail_id + '"]').remove();
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
                        if (type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID; ?>' || type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID; ?>'){
                            var ce_detail_id = $("#ce_detail_id").val();
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
                                data: {process_id : process_id, category_id : category_id, item_id : item_id, touch_id : touch_id, ce_detail_id : ce_detail_id},
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
                                    $("#ce_item_delete").val('allow');
                                    $('#ce_detail_id').val('');
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
//                                    var d = new Date();
//                                    var curr_date = strpad00(d.getDate())+'-'+strpad00(d.getMonth()+1)+'-'+d.getFullYear();
                                    $("#ce_detail_date").val('');
                                    $("#ce_detail_date").datepicker('setDate', null);
                                    $("#ce_detail_remark").val('');
                                    $("#ad_pcs").val('');
                                    $("#ad_weight").val('');
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
                            $("#ce_item_delete").val('allow');
                            $('#ce_detail_id').val('');
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
//                            var d = new Date();
//                            var curr_date = strpad00(d.getDate())+'-'+strpad00(d.getMonth()+1)+'-'+d.getFullYear();
                            $("#ce_detail_date").val('');
                            $("#ce_detail_date").datepicker('setDate', null);
                            $("#ce_detail_remark").val('');
                            $("#ad_pcs").val('');
                            $("#ad_weight").val('');
                            edit_lineitem_inc = 0;
                        }
                    }
                });
                $('#total_grwt_sell').val('');
            }
            $("#add_lineitem").removeAttr('disabled', 'disabled');
        }); 
        
        <?php if(isset($casting_data->lott_complete) && $casting_data->lott_complete == 1){ ?>
            $("input[type=radio][name=lott_complete][value=1]").click();
        <?php } else { ?>
            $("input[type=radio][name=lott_complete][value=0]").click();
        <?php }  ?>
            
        initAjaxSelect2($("#cad_worker_id"), "<?= base_url('app/cad_worker_select2_source') ?>");
        $(document).on("change", "#to_casting_status_id", function () {
            if($(this).val() !== '' || $(this).val() !== null){
                if($(this).val() == '<?php echo MANUFACTURE_STATUS_IN_CAD ?>'){
                    $("#cad_worker_id").val(null).trigger("change");
                    $('#worker_id_div').show();
                } else {
                    $("#cad_worker_id").val(null).trigger("change");
                    $('#worker_id_div').hide();
                }
            } else {
                $("#cad_worker_id").val(null).trigger("change");
                $('#worker_id_div').hide();
            }
            if($(this).val() !== '' || $(this).val() !== null){
                if($(this).val() == '<?php echo MANUFACTURE_STATUS_DESIGN_READY ?>'){
                    $("#design_files").val(null).trigger("change");
                    $('.design_files_div').show();
                } else {
                    $("#design_files").val(null).trigger("change");
                    $('.design_files_div').hide();
                }
            } else {
                $("#design_files").val(null).trigger("change");
                $('.design_files_div').hide();
            }
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
                                pts_lineitem_objectdata[index].ce_detail_id = li_value.ce_detail_id;
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
        var total_issue_pcs = 0;
        var total_issue_ad_pcs = 0;
        var total_issue_ad_weight = 0;
        var total_receive_weight = 0;
        var total_receive_less = 0;
        var total_receive_net_wt = 0;
        var total_receive_fine = 0;
        var total_receive_tunch = 0;
        var total_receive_actual_fine = 0;
        var total_receive_pcs = 0;
        var total_receive_ad_pcs = 0;
        var total_receive_ad_weight = 0;
        
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
            var ad_pcs = parseFloat(value.ad_pcs) || 0;
            var ad_weight = parseFloat(value.ad_weight) || 0;
            
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_ce_item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.ce_item_delete == 'allow'){
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_ce_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var type_value = '';
            if(value.type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID; ?>'){
                type_value = 'IFW';
            } else if(value.type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID; ?>'){
                type_value = 'IS';
            } else if(value.type_id == '<?php echo CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID; ?>'){
                type_value = 'RFW';
            } else if(value.type_id == '<?php echo CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID; ?>'){
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
                    '<td class="text-nowrap">' + value.ce_detail_date + '</td>'+
                    '<td class="text-right">' + ad_weight.toFixed(3) + '</td>'+
                    '<td class="text-right">' + ad_pcs + '</td>'+
                    '<td>' + value.ce_detail_remark + '</td>';
            if(value.type_id == '<?php echo CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID; ?>'){
                total_receive_weight = parseFloat(total_receive_weight) + parseFloat(weight);
                total_receive_less = parseFloat(total_receive_less) + parseFloat(less);
                total_receive_net_wt = parseFloat(total_receive_net_wt) + parseFloat(net_wt);
                total_receive_fine = parseFloat(total_receive_fine) + parseFloat(fine);
                total_receive_pcs = parseFloat(total_receive_pcs) + parseFloat(pcs);
                total_receive_ad_pcs = parseFloat(total_receive_ad_pcs) + parseFloat(ad_pcs);
                total_receive_ad_weight = parseFloat(total_receive_ad_weight) + parseFloat(ad_weight);
                new_receive_lineitem_html += row_html;
                total_receive_tunch = (parseFloat(total_receive_fine) / parseFloat(total_receive_net_wt)) * 100;
                total_receive_tunch = total_receive_tunch || 0;
                
                var receive_actual_fine = parseFloat(net_wt) * parseFloat(value.actual_tunch) / 100;
                total_receive_actual_fine = parseFloat(total_receive_actual_fine) + parseFloat(receive_actual_fine);
                
            } else if(value.type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID; ?>' || value.type_id == '<?php echo CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID; ?>'){
                total_issue_weight = parseFloat(total_issue_weight) + parseFloat(weight);
                total_issue_less = parseFloat(total_issue_less) + parseFloat(less);
                total_issue_net_wt = parseFloat(total_issue_net_wt) + parseFloat(net_wt);
                total_issue_fine = parseFloat(total_issue_fine) + parseFloat(fine);
                total_issue_pcs = parseFloat(total_issue_pcs) + parseFloat(pcs);
                total_issue_ad_pcs = parseFloat(total_issue_ad_pcs) + parseFloat(ad_pcs);
                total_issue_ad_weight = parseFloat(total_issue_ad_weight) + parseFloat(ad_weight);
                new_issue_lineitem_html += row_html;
                total_issue_tunch = (parseFloat(total_issue_fine) / parseFloat(total_issue_net_wt)) * 100;
                total_issue_tunch = total_issue_tunch || 0;
                
                var issue_actual_fine = parseFloat(net_wt) * parseFloat(value.actual_tunch) / 100;
                total_issue_actual_fine = parseFloat(total_issue_actual_fine) + parseFloat(issue_actual_fine);
            }

        });
        $('#issue_lineitem_list').html(new_issue_lineitem_html);
        $('#total_issue_weight').html(total_issue_weight.toFixed(3));
        $('#total_issue_less').html(total_issue_less.toFixed(3));
        $('#total_issue_net_wt').html(total_issue_net_wt.toFixed(3));
        $('#total_issue_fine').html(total_issue_fine.toFixed(3));
        $('#total_issue_pcs').html(total_issue_pcs.toFixed(2));
        $('#total_issue_ad_pcs').html(total_issue_ad_pcs.toFixed(2));
        $('#total_issue_ad_weight').html(total_issue_ad_weight.toFixed(3));
        $('#total_issue_tunch').html(total_issue_tunch.toFixed(2));
        
        var total_issue_with_ad_weight = total_issue_weight + total_issue_ad_weight;
        $('#total_issue_with_ad_weight').html(total_issue_with_ad_weight.toFixed(3));
        $('#total_issue_with_ad_less').html(total_issue_less.toFixed(3));
        var total_issue_with_ad_net_wt = total_issue_with_ad_weight - total_issue_less;
        $('#total_issue_with_ad_net_wt').html(total_issue_with_ad_net_wt.toFixed(3));
        $('#total_issue_with_ad_tunch').html('0.00');
        $('#total_issue_with_ad_actual_tunch').html('0.00');
        $('#total_issue_with_ad_fine').html('0.00');
        $('#total_issue_with_ad_pcs').html(total_issue_pcs.toFixed(2));
        $('#total_issue_with_ad_ad_pcs').html(total_issue_ad_pcs.toFixed(2));
        $('#total_issue_with_ad_ad_weight').html(total_issue_ad_weight.toFixed(3));
        
        var total_issue_actual_tunch = ((parseFloat(total_issue_actual_fine) / parseFloat(total_issue_net_wt)) * 100) || 0;
        $('#total_issue_actual_tunch').html(total_issue_actual_tunch.toFixed(2));
        
        $('#receive_lineitem_list').html(new_receive_lineitem_html);
        $('#total_receive_weight').html(total_receive_weight.toFixed(3));
        $('#total_receive_less').html(total_receive_less.toFixed(3));
        $('#total_receive_net_wt').html(total_receive_net_wt.toFixed(3));
        $('#total_receive_fine').html(total_receive_fine.toFixed(3));
        $('#total_receive_pcs').html(total_receive_pcs.toFixed(2));
        $('#total_receive_ad_pcs').html(total_receive_ad_pcs.toFixed(2));
        $('#total_receive_ad_weight').html(total_receive_ad_weight.toFixed(3));
        $('#total_receive_tunch').html(total_receive_tunch.toFixed(2));
        
        var total_receive_with_ad_weight = total_receive_weight + total_receive_ad_weight;
        $('#total_receive_with_ad_weight').html(total_receive_with_ad_weight.toFixed(3));
        $('#total_receive_with_ad_less').html(total_receive_less.toFixed(3));
        var total_receive_with_ad_net_wt = total_receive_with_ad_weight - total_receive_less;
        $('#total_receive_with_ad_net_wt').html(total_receive_with_ad_net_wt.toFixed(3));
        $('#total_receive_with_ad_tunch').html('0.00');
        $('#total_receive_with_ad_actual_tunch').html('0.00');
        $('#total_receive_with_ad_fine').html('0.00');
        $('#total_receive_with_ad_pcs').html(total_receive_pcs.toFixed(2));
        $('#total_receive_with_ad_ad_pcs').html(total_receive_ad_pcs.toFixed(2));
        $('#total_receive_with_ad_ad_weight').html(total_receive_ad_weight.toFixed(3));
        
        var total_receive_actual_tunch = ((parseFloat(total_receive_actual_fine) / parseFloat(total_receive_net_wt)) * 100) || 0;
        $('#total_receive_actual_tunch').html(total_receive_actual_tunch.toFixed(2));
        
        $('#save_manu_hand_made').append('<input type="hidden" name="total_issue_net_wt" id="total_issue_net_wt" value="' + total_issue_net_wt + '" />');
        $('#save_manu_hand_made').append('<input type="hidden" name="total_receive_net_wt" id="total_receive_net_wt" value="' + total_receive_net_wt + '" />');
        $('#save_manu_hand_made').append('<input type="hidden" name="total_issue_fine" id="total_issue_fine" value="' + total_issue_fine + '" />');
        $('#save_manu_hand_made').append('<input type="hidden" name="total_receive_fine" id="total_receive_fine" value="' + total_receive_fine + '" />');
        $('#ajax-loader').hide();
        set_total_weight();
        set_total_net_wt();
        set_total_fine();
        if($('input[name="lott_complete"]:checked').val() == 1){
            $(".delete_ce_item").addClass('hide');
        } else {
            $(".delete_ce_item").removeClass('hide');
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

    function edit_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_ce_item").addClass('hide');
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
        $("#ce_item_delete").val(value.ce_item_delete);
        if(typeof(value.ce_detail_id) !== "undefined" && value.ce_detail_id !== null) {
            $("#ce_detail_id").val(value.ce_detail_id);
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
        $("#ce_detail_date").val(value.ce_detail_date);
        $("#ce_detail_date").datepicker('setDate', value.ce_detail_date);
        $("#ce_detail_remark").val(value.ce_detail_remark);
        $("#ad_pcs").val(value.ad_pcs);
        $("#ad_weight").val(value.ad_weight);
        $('#total_grwt_sell').val(value.total_grwt_sell);
        if(value.ce_item_delete == 'not_allow'){
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
            if (typeof (value.ce_detail_id) !== "undefined" && value.ce_detail_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.ce_detail_id + '" />');
            }
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }
    
    function delete_design_file(design_file_id) {
        if (confirm('Are you sure ?')) {
            if (design_file_id !== null) {
                delete_design_file_ids.push(design_file_id);
                $('#design_file_id_'+design_file_id).hide();
            }
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
</script>
