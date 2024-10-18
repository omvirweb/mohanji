<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="body-content">
    <style>
    .ui-autocomplete {
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 1000;
      display: none;
      float: left;
      min-width: 160px;
      padding: 7px 0;
      padding-left: 7px;
      margin: 2px 0 0;
      list-style: none;
      font-size: 14px;
      max-height: 200px;
      overflow-x: hidden;
      text-align: left;
      background-color: #ffffff;
      border: 1px solid #cccccc;
      border: 1px solid rgba(0, 0, 0, 0.15);
      border-radius: 4px;
      -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
      background-clip: padding-box;
    }

    .ui-autocomplete > li > div {
      display: block !important;
      padding: 3px 20px !important;
      clear: both;
      font-weight: normal;
      line-height: 1.42857143 !important;
      color: #333333 !important;
      white-space: nowrap;
    }

    .ui-state-hover,
    .ui-state-active,
    .ui-state-focus {
      text-decoration: none;
      color: #262626;
      background-color: #f5f5f5 !important;
      cursor: pointer;
    }

    .ui-helper-hidden-accessible {
      border: 0;
      clip: rect(0 0 0 0);
      height: 1px;
      margin: -1px;
      overflow: hidden;
      padding: 0;
      position: absolute;
      width: 1px;
    } 
</style>
    <form id="account_form_account" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
        <?php if (isset($account_id) && !empty($account_id)) { ?>
            <input type="hidden" id="account_id" name="account_id" value="<?= $account_id; ?>">
        <?php } ?>
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Account
                <?php $isEdit = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "add"); ?>
                <?php if (isset($account_id) && !empty($account_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?>
                <button type="submit" class="btn btn-primary form_btn pull-right module_save_btn btn-sm" style="margin: 5px;" <?php echo isset($account_id) ? '' : $btn_disable;?>><?= isset($account_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('account/account_list'); ?>" class="btn btn-primary pull-right btn-sm" style="margin: 5px;">Account List</a>
                <?php } ?>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- START ALERTS AND CALLOUTS -->
            <div class="row">
                <div class="col-md-12">
                    <?php if($isAdd || $isEdit) { ?>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                Register
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_name" class="control-label">Name<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="account_name" class="form-control" id="account_name" value="<?= isset($account_name) ? $account_name : '' ?>" placeholder="" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_phone" class="control-label"> Phone</label>
                                            <input type="text" name="account_phone" class="form-control num_only" id="account_phone" value="<?= isset($account_phone) ? $account_phone : '' ?>" placeholder="" >
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_mobile" class="control-label"> Mobile<span class="required-sign mobile_required">&nbsp;*</span></label>
                                            <input type="text" name="account_mobile" class="form-control" id="account_mobile" minlength="10" value="<?= isset($account_mobile) ? $account_mobile : '' ?>" placeholder="" >
                                            <small class="">Add multiple mobile by comma separated.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_email_ids" class="control-label">Account Email</label>
                                            <textarea name="account_email_ids" class="form-control" id="account_email_ids" placeholder=""><?= isset($account_email_ids) ? $account_email_ids : '' ?></textarea>
                                            <small class="">Add multiple email by comma separated.</small>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="account_group_id" class="control-label"> Account Group<span class="required-sign">&nbsp;*</span></label>
                                            <select name="account_group_id" id="account_group_id" class="form-control account_group_id select2" ></select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <br/>
                                            <label for="is_supplier" class="control-label">Is Supplier ? &nbsp; </label>
                                            <input type="checkbox" name="is_supplier" id="is_supplier" style="height: 20px; width: 20px;" class="is_supplier" <?= isset($is_supplier) && !empty($is_supplier) ? "checked" : '' ?> >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_remarks" class="control-label">Remarks</label>
                                            <textarea name="account_remarks" class="form-control" id="account_remarks" placeholder=""><?= isset($account_remarks) ? $account_remarks : '' ?></textarea>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h4 class="box-title form_title">Address</h4>
                                        </div>	
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_address" class="control-label">Street</label>
                                            <textarea name="account_address" class="form-control" id="account_address" placeholder=""><?= isset($account_address) ? $account_address : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_postal_code" class="control-label">Postal Code</label>
                                            <input type="number" name="account_postal_code" class="form-control" id="account_igst_per" value="<?= isset($account_postal_code) ? $account_postal_code : '' ?>" placeholder="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_state" class="control-label">State</label>
                                            <select name="account_state" id="account_state" class="form-control select2" ></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_city" class="control-label">City</label>
                                            <select name="account_city" id="account_city" class="form-control select2" ></select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h4 class="box-title form_title">Bank details</h4>
                                        </div>	
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_name" class="control-label">Bank Name</label>
                                            <input type="text" name="bank_name" class="form-control" id="bank_name" placeholder="" value="<?= isset($bank_name) ? $bank_name : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_account_no" class="control-label">Bank Account No</label>
                                            <input type="text" name="bank_account_no" class="form-control" id="bank_account_no" value="<?= isset($bank_account_no) ? $bank_account_no : '' ?>" placeholder="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ifsc_code" class="control-label">IFSC Code</label>
                                            <input type="text" name="ifsc_code" class="form-control" id="ifsc_code" value="<?= isset($ifsc_code) ? $ifsc_code : '' ?>" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_interest" class="control-label">Bank Interest</label>
                                            <input type="text" name="bank_interest" class="form-control num_only" id="interest" value="<?= isset($bank_interest) ? $bank_interest : '' ?>" placeholder="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="box-header with-border">
                                            <h4 class="box-title form_title">More Imformation</h4>
                                        </div>	
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box-header with-border">
                                            <h4 class="box-title form_title"></h4>
                                        </div>	
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_contect_person_name">Contact Person Name</label>
                                            <input type="text" name="account_contect_person_name" class="form-control" id="account_contect_person_name" value="<?= isset($account_contect_person_name) ? $account_contect_person_name : '' ?>" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_contect_person_name">Aadhar</label>
                                            <input type="text" name="account_aadhaar" class="form-control" id="account_aadhaar" value="<?= isset($account_aadhaar) ? $account_aadhaar : '' ?>" placeholder="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_gst_no" class="control-label">GSTIN</label>
                                            <input type="text" name="account_gst_no" class="form-control" id="account_gst_no" value="<?= isset($account_gst_no) ? $account_gst_no : '' ?>" placeholder="" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_pan" class="control-label">Pan</label>
                                            <input type="text" name="account_pan" class="form-control" id="account_pan" value="<?= isset($account_pan) ? $account_pan : '' ?>" placeholder="" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="interest" class="control-label">Interest</label>
                                            <input type="text" name="interest" class="form-control num_only" id="interest" value="<?= isset($interest) ? $interest : '' ?>" placeholder="" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="credit_limit" class="control-label">Credit Limit</label>
                                            <input type="text" name="credit_limit" class="form-control num_only" id="credit_limit" value="<?= isset($credit_limit) ? $credit_limit : '' ?>" placeholder="" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="account_div">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="opening_balance_in_gold" class="control-label">Opening Balance In Gold</label>
                                                <input type="text" name="opening_balance_in_gold" class="form-control num_only" id="opening_balance_in_gold" value="<?= isset($opening_balance_in_gold) ? $opening_balance_in_gold : '' ?>" placeholder="" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gold_ob_credit_debit" class="control-label">Credit / Debit</label>
                                                <select name="gold_ob_credit_debit" id="gold_ob_credit_debit" class="form-control select2">
                                                    <option value="1" <?=(isset($gold_ob_credit_debit) && $gold_ob_credit_debit == '1') ? 'selected' : '' ?>>Credit</option>
                                                    <option value="2" <?=(isset($gold_ob_credit_debit) && $gold_ob_credit_debit == '2') ? 'selected' : '' ?>>Debit</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="opening_balance_in_silver" class="control-label">Opening Balance In Silver</label>
                                                <input type="text" name="opening_balance_in_silver" class="form-control num_only" id="opening_balance_in_silver" value="<?= isset($opening_balance_in_silver) ? $opening_balance_in_silver : '' ?>" placeholder="" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="silver_ob_credit_debit" class="control-label">Credit / Debit</label>
                                                <select name="silver_ob_credit_debit" id="silver_ob_credit_debit" class="form-control select2">
                                                    <option value="1" <?=(isset($silver_ob_credit_debit) && $silver_ob_credit_debit == '1') ? 'selected' : '' ?>>Credit</option>
                                                    <option value="2" <?=(isset($silver_ob_credit_debit) && $silver_ob_credit_debit == '2') ? 'selected' : '' ?>>Debit</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="opening_balance_in_rupees" class="control-label">Opening Balance In Rupees</label>
                                                <input type="text" name="opening_balance_in_rupees" class="form-control num_only" id="opening_balance_in_rupees" value="<?= isset($opening_balance_in_rupees) ? $opening_balance_in_rupees : '' ?>" placeholder="" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rupees_ob_credit_debit" class="control-label">Credit / Debit</label>
                                                <select name="rupees_ob_credit_debit" id="rupees_ob_credit_debit" class="form-control select2">
                                                    <option value="1" <?=(isset($rupees_ob_credit_debit) && $rupees_ob_credit_debit == '1') ? 'selected' : '' ?>>Credit</option>
                                                    <option value="2" <?=(isset($rupees_ob_credit_debit) && $rupees_ob_credit_debit == '2') ? 'selected' : '' ?>>Debit</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price_per_pcs" class="control-label">Price / Per Pcs</label>
                                            <input type="text" name="price_per_pcs" class="form-control num_only" id="price_per_pcs" value="<?= isset($price_per_pcs) ? $price_per_pcs : '' ?>" placeholder="" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="crone_items">
                                        <div class="line_item_form item_fields_div">
                                            <input type="hidden" name="line_items_index" id="line_items_index" />
                                            <?php if (isset($general_invoice_lineitems)) { ?>
                                                <input type="hidden" name="line_items_data[lineitem_id]" id="lineitem_id" />
                                            <?php } ?>
                                                <div class="box-header with-border">
                                                    <h4 class="col-md-12"><b>Itemwise Wastage</b></h4>
                                                    <!--<label for="">&nbsp;</label>-->
                                                    <button type="button" class="btn copy_wastage_from_other btn-primary btn-sm pull-left">Copy wastage from other Account</button>
                                                    <div class="col-md-3 from_account_id_div">
                                                        <!--<label for="from_account_id">Select Account</label>-->
                                                        <select name="" class="form-control" id="from_account_id"></select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="button" class="btn copy_btn btn-primary btn-sm pull-left" style="display: none;">Copy</button>
                                                    </div>
                                                </div>
                                            <div class="col-md-3">
                                                <label for="item_id">Category</label>
                                                <select name="line_items_data[category_id]" class="form-control category_id" id="category_id"></select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="item_id">Item</label>
                                                <select name="line_items_data[item_id]" class="form-control item_id select2" id="item_id"></select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="wstg">Wstg</label>
                                                <input type="text" name="line_items_data[wstg]" class="form-control wstg num_only" id="wstg" placeholder="" value="">
                                            </div>
                                            <div class="col-md-3">
                                                <label>&nbsp;</label><br />
                                                <input type="button" id="add_lineitem" class="btn btn-info btn-sm add_lineitem" value="Add Item" />
                                            </div>
                                        </div>
                                        <div class="clearfix"></div><br />
                                        <div class="col-sm-12">
                                            <table style="" class="table custom-table item-table">
                                                <thead>
                                                    <tr>
                                                        <th width="100px">Action</th>
                                                        <th>Category Name</th>
                                                        <th>Item Name</th>
                                                        <th>Wstg</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lineitem_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary form_btn btn-sm"><?= isset($account_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                            </div>
                            <?php if (isset($account_id) && !empty($account_id)) { ?>
                            <div class="created_updated_info" style="margin: 10px;">
                                Created by : <?= isset($created_by_name) ? $created_by_name : '' ?>
                                @ <?= isset($created_at) ? date ('d-m-Y h:i A', strtotime($created_at)) : '' ?><br/>
                                Updated by : <?= isset($updated_by_name) ? $updated_by_name : '' ?>
                                @ <?= isset($updated_at) ? date('d-m-Y h:i A', strtotime($updated_at)) :'' ; ?>
                            </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- END ALERTS AND CALLOUTS -->
        </section>
    </form>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
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
    var account_mobile_no_is_required = '<?php echo $account_mobile_no_is_required; ?>';
    var need_account_mobile_no = 0;
    var table;
    var item_index = '';
    var on_save_add_edit_item = 0;
    var edit_lineitem_inc = 0;
    lineitem_objectdata = [];
<?php if (isset($party_item_detail)) { ?>
        var li_lineitem_objectdata = [<?php echo $party_item_detail; ?>];
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
<?php } ?>
    display_lineitem_html(lineitem_objectdata);
    
    $(document).ready(function () {
        $('.mobile_required').hide();
        $('.crone_items').hide();
        $(".select2").select2({
            width: "100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
        initAjaxSelect2($('#from_account_id'), "<?= base_url('app/party_name_with_number_select2_source') ?>/");
        $('.from_account_id_div').hide();
        $(document).on('change','#account_group_id', function(){
            var account_group_id = $('#account_group_id').val();
            if(account_group_id == <?= CUSTOMER_GROUP; ?> || account_group_id == <?= SUNDRY_CREDITORS_ACCOUNT_GROUP; ?> || account_group_id == <?= SUNDRY_DEBTORS_ACCOUNT_GROUP; ?>){
                if(account_mobile_no_is_required == '1'){
                    $('.mobile_required').show();
                    need_account_mobile_no = 1;
                }
                $('.crone_items').show();
            } else {
                $('.mobile_required').hide();
                need_account_mobile_no = 0;
                $('.crone_items').hide();
            }
        });
        
<?php if (!isset($account_id) && empty($account_id)) { ?>

<?php } ?>
        initAjaxSelect2($("#account_group_id"), "<?= base_url('app/account_group_select2_source_for_account/') ?>");
        initAjaxSelect2($("#account_state"), "<?= base_url('app/state_select2_source') ?>");
        $('#account_state').on('change', function () {
            $("#account_city").empty().trigger('change');
            var state_home = this.value;
            initAjaxSelect2($('#account_city'), "<?= base_url('app/city_select2_source') ?>/" + state_home);
        });
        
        initAjaxSelect2($("#category_id"), "<?= base_url('app/category_select2_source') ?>");
        $(document).on('change', '#category_id', function () {
            $('#item_id').val(null).trigger('change');
            var category_id = $('#category_id').val();
            if (category_id != '' && category_id != null) {
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_from_category_with_all_select2_source') ?>/" + category_id);
            } else {
                $('#item_id').val(null).trigger('change');
            }
        });

<?php if (isset($account_state) && !empty($account_state)) { ?>
            setSelect2Value($("#account_state"), "<?= base_url('app/set_state_select2_val_by_id/' . $account_state) ?>");
            initAjaxSelect2($('#account_city'), "<?= base_url('app/city_select2_source/' . $account_state) ?>");
<?php } ?>

<?php if (isset($account_city) && !empty($account_city)) { ?>
            setSelect2Value($("#account_city"), "<?= base_url('app/set_city_select2_val_by_id/' . $account_city) ?>");
<?php } ?>


<?php if (isset($account_sales_person) && !empty($account_sales_person)) { ?>
            setSelect2Value($("#account_sales_person"), "<?= base_url('app/set_account_select2_val_by_id/' . $sales_person) ?>");
<?php } ?>
<?php if (isset($account_group_id) && !empty($account_group_id)) { ?>
            setSelect2Value($("#account_group_id"), "<?= base_url('app/set_account_group_select2_val_by_id/' . $account_group_id) ?>");
<?php } ?>
    
        <?php if($this->app_model->have_access_role(ACCOUNT_MODULE_ID, "allow_add_opening")){ ?>
            $('.account_div').show();
        <?php } else { ?>
            $('.account_div').hide();
        <?php } ?>
    
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                e.preventDefault();
                if(module_submit_flag == 0 ){
                    $("#account_form_account").submit();
                    return false;
                }
            }
        });
        
        account_names = [<?php echo $names; ?>];
        $('#account_name').on('input', function () {
            $( "#account_name" ).autocomplete({
              source: account_names,
              autoFocus:false,
            });
        });

        $(document).on('submit', '#account_form_account', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#account_name").val()) == '') {
                show_notify('Please Enter Name.', false);
                $("#account_name").focus();
                return false;
            }
            
            if ($.trim($("#account_group_id").val()) == '') {
                show_notify('Please Select Account Group.', false);
                $("#account_group_id").select2('open');
                return false;
            }

            <?php /*if($.trim($("#account_group_id").val()) == <?php echo CUSTOMER_GROUP; ?>){
                if ($.trim($("#account_mobile").val().length) != '10') {
                    show_notify('Please Enter Valid Mobile No.', false);
                    $("#account_mobile").focus();
                    return false;
                }
            }*/ ?>

            if(account_mobile_no_is_required == '1' && need_account_mobile_no == 1){
                var mobile_nos = $('#account_mobile').val();
                var mobile_nos = mobile_nos.split(',');
                var check_duplicate_mobile = [];
                var found_duplicate = 0;
                var digit_issue = 0;
                var length_issue = 0;
                $.each(mobile_nos, function (index, mobile) {
                    if ($.inArray(mobile, check_duplicate_mobile) > -1) {
                        found_duplicate = 1;
                    } else {
                        check_duplicate_mobile.push(mobile);
                    }
                    if(!$.trim(mobile).match('[0-9]{10}')){
                        digit_issue = 1;
                    }
                    if($.trim(mobile).length != 10){
                        length_issue = 1;
                    }
                });
                if (found_duplicate == 1) {
                    show_notify('Duplicate Mobile Exist!', false);
                    $("#account_mobile").focus();
                    return false;
                }
                if (digit_issue == 1) {
                    show_notify('Mobile No Is Not Valid!', false);
                    $("#account_mobile").focus();
                    return false;
                }
                if (length_issue == 1) {
                    show_notify('Mobile No should be 10 digit!', false);
                    $("#account_mobile").focus();
                    return false;
                }
            }

            var email_ids = $('#account_email_ids').val();
            var email_ids = email_ids.split(',');
            var check_duplicate = [];
            var found_duplicate = 0;
            $.each(email_ids, function (index, email) {
                if ($.inArray(email, check_duplicate) > -1) {
                    found_duplicate = 1;
                } else {
                    check_duplicate.push(email);
                }
            });
            if (found_duplicate == 1) {
                show_notify('Duplicate Email Exist !', false);
                $("#account_email_ids").focus();
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var account_group_id = $('#account_group_id').val();
            if(account_group_id == <?= CUSTOMER_GROUP; ?> || account_group_id == <?= SUNDRY_CREDITORS_ACCOUNT_GROUP; ?> || account_group_id == <?= SUNDRY_DEBTORS_ACCOUNT_GROUP; ?>){
                var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
                postData.append('line_items_data', lineitem_objectdata_stringify);
            }
            $.ajax({
                url: "<?= base_url('account/save_account') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                fileElementId: 'account_image',
                data: postData,
                async: false,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['error'] == 'mobileExist') {
                        show_notify(json['msg'], false);
                        jQuery("#account_mobile").focus();
                        return false;
                    }
                    if (json['error'] == 'emailExist') {
                        show_notify('Email Already Exist in ' + json['msg'] + ' Account!', false);
                        jQuery("#email_id").focus();
                        return false;
                    }
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('account/account_list') ?>";
                    }
                    if (json['error'] == 'errorAdded') {
                        show_notify('Some error has occurred !', false);
                        return false;
                    }
                    if (json['error'] == 'accountExist') {
                        show_notify('Account Name Already Exist !', false);
                        jQuery("#account_name").focus();
                        return false;
                    }
                    if (json['error'] == 'mobileExist') {
                        show_notify('Mobile Already Exist in ' + json['msg'] + ' Account!', false);
                        return false;
                    }
                    if (json['error'] == 'email_error') {
                        show_notify(json['msg'], false);
                        jQuery("#email_ids").focus();
                        return false;
                    }
                    if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('account/account_list') ?>";
                    }
                    return false;
                },
            });
            module_submit_flag = 1;
            return false;
        });
        
        $('.copy_wastage_from_other').on('click', function () {
            $('.from_account_id_div').show();
            $('.copy_btn').show();
        });
        
        $('.copy_btn').on('click', function () {
            if ($.trim($("#from_account_id").val()) == '') {
                show_notify('Please Select Account.', false);
                $("#from_account_id").focus();
                return false;
            }
            $.ajax({
                url: "<?= base_url('account/get_account_wastages') ?>/" + $("#from_account_id").val(),
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function (response) {
                    var res_data = $.parseJSON(response);
                    if(res_data.item_data){
                        $.each(res_data.item_data, function (index, value) {
                            is_dup_copy = 0;
                            $.each(lineitem_objectdata, function (index, old_value) {
                                if(old_value.category_id == value.category_id && old_value.item_id == value.item_id){
                                    is_dup_copy = 1;
                                }
                            });
                            if(is_dup_copy == 0){
                                var new_item_arr = {};
                                new_item_arr['category_id'] = value.category_id;
                                new_item_arr['category_name'] = value.category_name;
                                new_item_arr['item_id'] = value.item_id;
                                new_item_arr['item_name'] = value.item_name;
                                new_item_arr['wstg'] = value.wstg;
                                lineitem_objectdata.push(new_item_arr);
                            }
                        });
                    }
                    display_lineitem_html(lineitem_objectdata);
                }
            });
        });
        
        $('#add_lineitem').on('click', function () {
//            console.log(lineitem_objectdata);
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

            if ($.trim($("#wstg").val()) == '') {
                show_notify('Please Enter Wastage.', false);
                $("#wstg").focus();
                return false;
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
            if(lineitem.item_id == 'all'){
                var line_items_index = $("#line_items_index").val();
                if (line_items_index != '') {
                    lineitem_objectdata.splice(line_items_index, 1);
                }
                delete_lineitem();
                $.ajax({
                    url: "<?= base_url('app/item_name_from_category_select2_source') ?>/" + $("#category_id").val(),
                    type: "POST",
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    success: function (response) {
                        var res_data = $.parseJSON(response);
                        $.each(res_data.results, function (index, value) {
                            var new_item_arr = {};
                            new_item_arr['category_id'] = $("#category_id").val();
                            var item_data_new = $('#category_id').select2('data');
                            new_item_arr['category_name'] = item_data_new[0].text;
                            new_item_arr['item_id'] = value.id;
                            new_item_arr['item_name'] = value.text;
                            new_item_arr['wstg'] = $("#wstg").val();
                            lineitem_objectdata.push(new_item_arr);
                        });
                    }
                });
            
            } else {
               $('select[name^="line_items_data"]').each(function (e) {
                    key = $(this).attr('name');
                    key = key.replace("line_items_data[", "");
                    key = key.replace("]", "");
                    $.each(lineitem_objectdata, function (index, value) {
                        if (value.category_id == category_id && value.item_id == item_id && typeof (value.id) != "undefined" && value.id !== null) {
                            $('input[name^="line_items_data"]').each(function (index) {
                                keys = $(this).attr('name');
                                keys = keys.replace("line_items_data[", "");
                                keys = keys.replace("]", "");
                                if (keys == 'id') {
                                    if (value.id != $(this).val()) {
                                        is_validate = '1';
                                        show_notify("You cannot Add this Item. This Item has been used!", false);
                                        return false;
                                    }
                                }
                            });
                        } else if (value.category_id == category_id && value.item_id == item_id) {
                            if(item_index !== index){
                                is_validate = '1';
                                show_notify("You cannot Add this Item. This Item has been used!", false);
                                return false;
                            }
                        }
                    });
                    if (is_validate == '1') {
                        return false;
                    }
                });
                if (is_validate != '1') {
                    var item_data = $('#category_id').select2('data');
                    lineitem['category_name'] = item_data[0].text;
                    var item_data = $('#item_id').select2('data');
                    lineitem['item_name'] = item_data[0].text;
                    var new_lineitem = JSON.parse(JSON.stringify(lineitem));
                    var line_items_index = $("#line_items_index").val();
                    if (line_items_index != '') {
                        lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
                    } else {
                        lineitem_objectdata.push(new_lineitem);
                    }
                }
            }
            if (is_validate != '1') {
                $('#lineitem_id').val('');
                $("#category_id").val(null).trigger("change");
                $("#item_id").val(null).trigger("change");
                $("#wstg").val('');
                item_index = '';
                $("#line_items_index").val('');
                display_lineitem_html(lineitem_objectdata);
                if (on_save_add_edit_item == 1) {
                    on_save_add_edit_item == 0;
                    $('#party_form').submit();
                }
                edit_lineitem_inc = 0;
            }
        });

    });
    
    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        var total_fine = 0;
        var sale_total = 0;
        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class=" btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class=" disable_delete_btn btn btn-xs btn-danger btn-delete-item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td>' + value.category_name + '</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td>' + value.wstg + '</td>';
            new_lineitem_html += row_html;
        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        if($("#line_items_index").val() != ''){
            $('.disable_delete_btn').hide();
        }
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
//        $("html, body").animate({scrollTop: 0}, "slow");
        $('.disable_delete_btn').hide();
        item_index = index;
        if (edit_lineitem_inc == 0) {
            edit_lineitem_inc = 1;
            $(".add_lineitem").removeAttr("disabled");
        }
        var value = lineitem_objectdata[index];
        $("#line_items_index").val(index);
//        if (typeof (value.id) != "undefined" && value.id !== null) {
//            $("#lineitem_id").val(value.id);
//        }
        $("#category_id").val(value.category_id).trigger("change");
        setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/') ?>" + value.category_id);
        $("#item_id").val(value.item_id).trigger("change");
        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + value.item_id);
        $("#wstg").val(value.wstg);
        $('#ajax-loader').hide();
//        lineitem_objectdata.splice(index, 1);
    }

    function remove_lineitem(index) {
        if (confirm('Are you sure ?')) {
            value = lineitem_objectdata[index];
            if (typeof (value.lineitem_id) != "undefined" && value.lineitem_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.lineitem_id + '" />');
            }
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }
    
    function delete_lineitem(){
        var new_cat_id = $("#category_id").val();
        if(lineitem_objectdata.length !== 0){
            $.each(lineitem_objectdata, function (index, value_old) {
                if(lineitem_objectdata.length === 0){} else {
                    if(typeof (value_old) != "undefined"){
                        if(value_old.category_id == new_cat_id){
                            lineitem_objectdata.splice(index, 1);
                            delete_lineitem();
                        }
                    }
                }
            });
        }
    }
    
</script>
