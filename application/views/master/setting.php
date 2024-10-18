<?php $this->load->view('success_false_notify'); ?>
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="<?php echo base_url('/assets/plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>">
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="javascript:;" method="post" id="update_setting" novalidate enctype="multipart/form-data">                                    
        
        <section class="content-header">
            <h1>
                Method
                <?php $isEdit = $this->app_model->have_access_role(SETTING_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(SETTING_MODULE_ID, "view"); ?>
                <span class="pull-right" style="margin-right: 20px;"></span>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <?php if($isEdit || $isView){ ?>
                    <!-- Horizontal Form -->
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <?php 
                                        $fields_section_first_field = 0;
                                        $fields_section_first_time = 0;
                                        if(isset($setting_data) && !empty($setting_data)) { 
                                        foreach ($setting_data as $setting){

                                            if(ALLOW_SELL_PURCHASE_TYPE_2 == 'no' && $setting->settings_key == 'sell_purchase_type_2'){ continue; }
                                            if(ALLOW_SELL_PURCHASE_TYPE_3 == 'no' && $setting->settings_key == 'sell_purchase_type_3'){ continue; }
                                            if(ALLOW_INVENTORY_DATA_MODULES == 'no' && $setting->settings_key == 'inventory_data_modules'){ continue; }

                                            if($setting->fields_section  == $fields_section_first_time){
                                                $fields_section_first_field = 1;
                                                $fields_section_first_time++;
                                            } else {
                                                $fields_section_first_field = 0;
                                            }
                                            
                                            if($fields_section_first_field == 1){
                                                echo '<div class="clearfix"></div><div class="col-md-12">';
                                                if($setting->fields_section  == '0'){
                                                    echo '<h3 class="bg-light-blue-gradient" style="padding: 5px 10px; margin: 10px 0px;"> General : </h3>';
                                                } else if($setting->fields_section  == '1'){
                                                    echo '<h3 class="bg-light-blue-gradient" style="padding: 5px 10px; margin: 10px 0px;"> Rate : </h3>';
                                                } else if($setting->fields_section  == '2'){
                                                    echo '<h3 class="bg-light-blue-gradient" style="padding: 5px 10px; margin: 10px 0px;"> Sell/Purchase : </h3>';
                                                } else if($setting->fields_section  == '3'){
                                                    echo '<h3 class="bg-light-blue-gradient" style="padding: 5px 10px; margin: 10px 0px;"> Login : </h3>';
                                                } else if($setting->fields_section  == '4'){
                                                    echo '<h3 class="bg-light-blue-gradient" style="padding: 5px 10px; margin: 10px 0px;"> Manufacturing : </h3>';
                                                } else if($setting->fields_section  == '5'){
                                                    echo '<h3 class="bg-light-blue-gradient" style="padding: 5px 10px; margin: 10px 0px;"> XRF / HM / Laser : </h3>';
                                                } else if($setting->fields_section  == '6'){
                                                    echo '<h3 class="bg-light-blue-gradient" style="padding: 5px 10px; margin: 10px 0px;"> Company Details : </h3>';
                                                }
                                                echo '</div>';
                                            }

                                            $checkbox_arr = array();
                                            // General Section Checkbox
                                            $checkbox_arr[] = 'without_purchase_sell_allow';
                                            $checkbox_arr[] = 'enter_key_to_next';
                                            $checkbox_arr[] = 'use_rfid';
                                            $checkbox_arr[] = 'use_barcode';
                                            $checkbox_arr[] = 'show_backup_email_menu';
                                            $checkbox_arr[] = 'use_category';
                                            $checkbox_arr[] = 'display_net_amount_in_outstanding';
                                            $checkbox_arr[] = 'account_mobile_no_is_required';
                                            $checkbox_arr[] = 'ledger_print_in_page_a5';
                                            $checkbox_arr[] = 'default_from_financial_start_year';
                                            $checkbox_arr[] = 'inventory_data_modules';
                                            // Sell/Purchase Section Checkbox
                                            $checkbox_arr[] = 'sell_purchase_difference';
                                            $checkbox_arr[] = 'ask_ad_charges_in_sell_purchase';
                                            $checkbox_arr[] = 'ask_less_ad_details_in_sell_purchase';
                                            $checkbox_arr[] = 'department_2';
                                            $checkbox_arr[] = 'remark_2';
                                            $checkbox_arr[] = 'delivered_not_2';
                                            $checkbox_arr[] = 'tunch_textbox_2';
                                            $checkbox_arr[] = 'charges_2';
                                            $checkbox_arr[] = 'less_netwt_2';
                                            $checkbox_arr[] = 'wstg_2';
                                            $checkbox_arr[] = 'lineitem_image_2';
                                            $checkbox_arr[] = 'ask_discount_in_sell_purchase';
                                            $checkbox_arr[] = 'c_r_amount_separate';
                                            $checkbox_arr[] = 'approx_amount';
                                            $checkbox_arr[] = 'sell_purchase_type_2';
                                            $checkbox_arr[] = 'sell_purchase_type_3';
                                            $checkbox_arr[] = 'line_item_remark';
                                            $checkbox_arr[] = 'line_item_gold_silver_rate';
                                            $checkbox_arr[] = 'display_line_item_remark_in_ledger';
                                            $checkbox_arr[] = 'display_line_item_remark_in_print';
                                            $checkbox_arr[] = 'sell_purchase_entry_with_gst';
                                            $checkbox_arr[] = 'sell_purchase_print_display_gold_fine_column';
                                            // XRF / HM / Laser Section Checkbox
                                            $checkbox_arr[] = 'xrf_box_no_mandatory';

                                            $cal_md_8_arr = array();
                                            $cal_md_8_arr[] = 'send_otp_mobile_no';

                                            $cal_md_2_arr = array();
                                            $cal_md_2_arr[] = 'use_rfid';
                                            $cal_md_2_arr[] = 'use_barcode';

                                            $not_num_only_textbox = array();
                                            $not_num_only_textbox[] = 'set_backup_email';
                                            $not_num_only_textbox[] = 'send_otp_mobile_no';
                                            $not_num_only_textbox[] = 'company_name';
                                            $not_num_only_textbox[] = 'company_contact';
                                            $not_num_only_textbox[] = 'company_address';
                                    ?>
                                    
                                    
                                        <div class="<?php if(in_array($setting->settings_key, $cal_md_8_arr)) { echo 'col-md-8'; } else if(in_array($setting->settings_key, $cal_md_2_arr)) { echo 'col-md-2'; } else { echo 'col-md-4'; } ?>" >
                                            <?php if($setting->settings_key == 'login_time_start' || $setting->settings_key == 'login_time_end'){ ?>
                                                <label for="date"><?= $setting->settings_label;?></label>
                                                <div class="input-group bootstrap-timepicker timepicker">
                                                    <input name="settings_value[<?= $setting->settings_key;?>]" type="text" class="form-control input-small timepicker1" value="<?= $setting->settings_value;?>" required="">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                </div>
                                                <br/>
                                            <?php } else if (in_array($setting->settings_key, $checkbox_arr)) { ?>
                                                <br /><label for="<?=$setting->settings_key?>"><?= $setting->settings_label;?>&nbsp;&nbsp;</label>
                                                <input type="checkbox" name="settings_value[<?= $setting->settings_key;?>]" id="<?= $setting->settings_key;?>" class="<?=$setting->settings_key?>" required="" <?= $setting->settings_value == '1' ? 'checked' : '';?> />
                                            <?php } else if ($setting->settings_key == 'theme_color_code') { ?>
                                                <label for="theme_color_code"><?= $setting->settings_label;?>&nbsp;&nbsp; <small>Default code is : #3c8dbc</small></label>
                                                <input type="text" name="settings_value[<?= $setting->settings_key;?>]" id="<?= $setting->settings_key;?>" class="form-control input-small theme_color_code my-colorpicker1 colorpicker-element" autocomplete="off" value="<?php echo $setting->settings_value; ?>" />
                                            <?php } else if ($setting->settings_key == 'rate_on') { ?>
                                                <label for="rate_on"><?= $setting->settings_label;?>&nbsp;&nbsp;</label>
                                                <select name="settings_value[<?= $setting->settings_key;?>]" id="<?= $setting->settings_key;?>" class="form-control input-small">
                                                    <option value="1" <?php if($setting->settings_value == '1'){ echo 'Selected'; } ?> >1 Grm</option>
                                                    <option value="2" <?php if($setting->settings_value == '2'){ echo 'Selected'; } ?> >10 Grm</option>
                                                </select>
                                            <?php } else if ($setting->settings_key == 'manufacture_lott_complete_in') { ?>
                                                <label for="manufacture_lott_complete_in"><?= $setting->settings_label;?>&nbsp;&nbsp;</label>
                                                <select name="settings_value[<?= $setting->settings_key;?>]" id="<?= $setting->settings_key;?>" class="form-control input-small">
                                                    <option value="1" <?php if($setting->settings_value == '1'){ echo 'Selected'; } ?> >Gross Weight</option>
                                                    <option value="2" <?php if($setting->settings_value == '2'){ echo 'Selected'; } ?> >Fine</option>
                                                    <option value="3" <?php if($setting->settings_value == '3'){ echo 'Selected'; } ?> >Amount</option>
                                                </select>
                                            <?php } else if ($setting->settings_key == 'issue_receive_karigar_wastage') { ?>
                                                <label for="issue_receive_karigar_wastage"><?= $setting->settings_label;?>&nbsp;&nbsp;</label>
                                                <select name="settings_value[<?= $setting->settings_key;?>]" id="<?= $setting->settings_key;?>" class="form-control input-small">
                                                    <option value="0" <?php if($setting->settings_value == '0'){ echo 'Selected'; } ?> >No</option>
                                                    <option value="1" <?php if($setting->settings_value == '1'){ echo 'Selected'; } ?> >Yes</option>
                                                </select>
                                            <?php } else { ?>
                                                <label for="date"><?= $setting->settings_label;?> <?php if($setting->settings_key == 'send_otp_mobile_no') { echo '<small class="text-danger">Add Multiple Mobile No. by (,) Comma Separated</small>'; } ?></label>
                                                <?php if(in_array($setting->settings_key, $not_num_only_textbox)){ $num_only = ''; } else { $num_only = 'num_only'; } ?>
                                                <input type="text" name="settings_value[<?= $setting->settings_key;?>]" id="<?= $setting->settings_key;?>" class="form-control <?php echo $num_only; ?>" value="<?= $setting->settings_value;?>" required="" />
                                            <?php }?>
                                        </div>
                                        <?php if ($setting->settings_key == 'without_purchase_sell_allow') { ?>
                                            <!--<div class="clearfix"></div>-->
                                        <?php } ?>
                                    <?php 
                                        }
                                        }
                                    ?>
                                    <div class="clearfix"></div><br /><hr><br /><br />
                                    <div class="line_item_form item_fields_div ">
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <?php if (isset($order_lot_item) && !empty($order_lot_item)) { ?>
                                            <input type="hidden" name="line_items_data[id]" id="id" value="0"/>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <label for="line_items_data[mac_address]">Mac Address</label>
                                            <input type="text" name="line_items_data[mac_address]" id="mac_address" class="form-control"><br />
                                        </div>
                                        <input type="button" id="add_lineitem" class="btn btn-info btn-sm add_lineitem" value="Add" style="margin-top: 21px;"/>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <table style="" class="table custom-table border item-table">
                                            <thead>
                                                <tr>
                                                    <th width="5%">Action</th>
                                                    <th width="5%">Mac Address</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lineitem_list"></tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary btn-sm module_save_btn" <?php if($isEdit){ NULL; } else { echo 'disabled'; } ?>>Update [ Ctrl + S ]</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
    
</div>
<!-- Bootstrap Color Picker -->
<script src="<?php echo base_url('/assets/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
<script type="text/javascript">
    
//    var first_time_edit_mode = 1;
//    var on_save_add_edit_item = 0;
//    var edit_lineitem_inc = 0;
    var lineitem_objectdata = [];
    <?php if (isset($order_lot_item)) { ?>
        var li_lineitem_objectdata = [<?php echo $order_lot_item; ?>];
        first_time_edit_mode = 0;
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    display_lineitem_html(lineitem_objectdata);
    
    $(document).ready(function(){
        $('.timepicker1').timepicker();
        
        //Colorpicker
        $('.my-colorpicker1').colorpicker();

        $(document).on('keyup', '#gold_min', function(){
           var gold_min = $(this).val();
           var gold_max = $.trim($('#gold_max').val());
           if(parseInt(gold_min) > parseInt(gold_max)) {
               $('#gold_min').val(gold_max);
               show_notify('Gold Min value can not be Greater than Gold Max value', false);
               return false;
           }
        });

        $(document).on('keyup', '#silver_min', function(){
           var silver_min = $(this).val();
           var silver_max = $.trim($('#silver_max').val());
           if(parseInt(silver_min) > parseInt(silver_max)) {
               $('#silver_min').val(silver_max);
               show_notify('Silver Min value can not be Greater than Silver Max value', false);
               return false;
           }
        });
        
        $('#add_lineitem').on('click', function () {
            var mac_address = $("#mac_address").val();    
            if (mac_address == '' || mac_address == null) {
                $("#mac_address").focus();
                show_notify("Please Enter Mac Address!", false);
                return false;
            }    
            var key = '';
            var value = '';
            var lineitem = {};
            $('input[name^="line_items_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
            var line_items_index = $("#line_items_index").val();
            if (line_items_index != '') {
                lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
            } else {
                lineitem_objectdata.push(new_lineitem);
            } 
            display_lineitem_html(lineitem_objectdata);
//            console.log(lineitem_objectdata);
            $("#mac_address").val('');
            $("#line_items_index").val('');
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#update_setting").submit();
                return false;
            }
        });

        $(document).on('submit', '#update_setting', function () {
            var gold_min = $.trim($('#gold_min').val());
            var gold_max = $.trim($('#gold_max').val());
            var silver_min = $.trim($('#silver_min').val());
            var silver_max = $.trim($('#silver_max').val());
            if(gold_max < gold_min){
                show_notify('Gold Min value can not be Greater than Gold Max value', false);
                return false;
            }
            if(silver_max < silver_min){
                show_notify('Silver Min value can not be Greater than Silver Max value', false);
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            $("#ajax-loader").show();
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            $.ajax({
                url: "<?= base_url('master/update_setting') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    location.reload();
                    return false;
                },
            });
            return false;
        }); 
    });
    
    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            if(index != 0){
                lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td>' + value.mac_address + '</td>';
            new_lineitem_html += row_html;
        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        var value = lineitem_objectdata[index];
        $("#line_items_index").val(index);
        console.log(value);
        if (typeof (value.id) != "undefined" && value.id !== null) {
            $("#id").val(value.id);
        }
        $("#id").val(value.id);
        $("#mac_address").val(value.mac_address);
    }
    
    function remove_lineitem(index) {
        value = lineitem_objectdata[index];
        if (confirm('Are you sure ?')) {
            if (typeof (value.id) != "undefined" && value.id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_mac_aadress_id[]" id="deleted_mac_aadress_id" value="' + value.id + '" />');
            }
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }
</script>