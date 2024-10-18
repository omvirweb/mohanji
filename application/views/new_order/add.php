<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('new_order/save_new_order') ?>" method="post" id="save_order" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
            <input type="hidden" name="order_id" class="order_id" value="<?= $new_order_data->order_id ?>">
        <?php } ?>
            <input type="hidden" name="total_weight" class="total_weight" id="total_weight_db" value="">
            <input type="hidden" name="total_pcs" class="total_pcs" id="total_pcs_db" value="">
            <input type="hidden" name="oli_id" id="oli_id">
            <input type="hidden" name="deleted_oli_ids" id="deleted_oli_ids">
        <!-- Content Header (Page header) -->
     
        <section class="content-header">
            <h1>
                Add Order
                <?php $isEdit = $this->app_model->have_access_role(ORDER_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(ORDER_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(ORDER_MODULE_ID, "add"); ?>
                <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($new_order_data->order_id) ? '' : $btn_disable;?>><?= isset($new_order_data->order_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('new_order/new_order_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Order List</a>
                <?php } ?>
                <span class="pull-right" style="margin-right: 20px;">
                    <div class="form-group">
                        <label for="send_whatsapp_sms" class="col-sm-12 input-sm text-green" style="font-size: 18px; line-height: 25px;">
                            <input type="checkbox" name="send_whatsapp_sms" id="send_whatsapp_sms" class="send_whatsapp_sms" checked="">  &nbsp; Send<img src="<?php echo base_url(); ?>assets/dist/img/whatsapp_icon.png" style="width:25px;" >
                        </label>
                    </div>
                </span>
                <span class="pull-right" style="margin-right: 20px;">
                    <div class="form-group">
                        <label for="send_sms" class="col-sm-12 input-sm" style="font-size: 18px; line-height: 25px;">
                            <input type="checkbox" name="send_sms" id="send_sms" class="send_sms" checked="">  &nbsp; Send SMS
                        </label>
                    </div>
                </span>
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

                                        <label for="date">Date</label>
                                        <input type="text" name="order_date" id="datepicker2" class="form-control input-datepicker" value="<?= (isset($new_order_data->order_date)) ? date('d-m-Y', strtotime($new_order_data->order_date)) : date('d-m-Y'); ?>">
                                        <div class="clearfix"></div><br />

                                        <label for="party_id">Party Name & No.<span class="required-sign">&nbsp;*</span></label>
                                        <select name="party_id" id="party_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br />
                                        
                                        <label for="remark">Remark</label>
                                        <input type="text" name="remark" id="remark" class="form-control" value="<?= (isset($new_order_data->remark)) ? $new_order_data->remark : ''; ?>"><br />
                                    </div>
                                    <div class="col-md-4">
                                        <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
                                            <label for="order_no">Order No.</label>
                                            <input type="text" name="order_no" id="order_no" class="form-control" readonly value="<?= ((isset($new_order_data->order_no)) ? $new_order_data->order_no : $order_no); ?>"><br />
                                        <?php } else { ?>
                                            <input type="hidden" ><br /><br /><br /><br />
                                        <?php } ?>
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="delivery_date">Delivery Date<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="delivery_date" id="delivery_date" class="form-control datepicker" value="<?= (isset($new_order_data->delivery_date)) ? date('d-m-Y', strtotime($new_order_data->delivery_date)) : date('d-m-Y'); ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="real_delivery_date">Real Delivery Date<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="real_delivery_date" id="real_delivery_date" class="form-control datepicker" value="<?= (isset($new_order_data->real_delivery_date)) ? date('d-m-Y', strtotime($new_order_data->real_delivery_date)) : ''; ?>"><br />
                                            </div>
                                        </div>
                                        <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
                                            <label for="remark">Status</label>
                                            <select name="order_status_id" id="order_status_id" class="form-control order_status_id"></select><br />
                                            <input type="hidden" name="pre_order_status_id" value="<?= (isset($new_order_data->order_status_id)) ? $new_order_data->order_status_id : ''; ?>"><br />
                                        <?php } ?>
                                        <label for="supplier_id">To Supplier</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br />
                                        <label for="supplier_delivery_date">Supplier Delivery Date</label>
                                        <input type="text" name="supplier_delivery_date" id="supplier_delivery_date" class="form-control datepicker" value="<?= (isset($new_order_data->supplier_delivery_date) && strtotime($new_order_data->supplier_delivery_date) > 0) ? date('d-m-Y', strtotime($new_order_data->supplier_delivery_date)) : ''; ?>">
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="order_type">Type</label>
                                        <select name="order_type" id="order_type" class="form-control select2">
                                            <option value="1" <?= (isset($new_order_data->order_type) && $new_order_data->order_type == '1') ? 'Selected' : ''; ?>>Order</option>
                                            <option value="2" <?= (isset($new_order_data->order_type) && $new_order_data->order_type == '2') ? 'Selected' : ''; ?>>Inquiry</option>
                                        </select>
                                        <div class="clearfix"></div><br />
                                        
                                        <label for="gold_price">Gold Price for this Order</label>
                                        <input type="text" name="gold_price" id="gold_price" class="form-control num_only" value="<?= (isset($new_order_data->gold_price)) ? $new_order_data->gold_price : ''; ?>">
                                        <div class="clearfix"></div><br />
                                        
                                        <label for="sliver_price">Silver Price for this Order</label>
                                        <input type="text" name="silver_price" id="silver_price" class="form-control num_only" value="<?= (isset($new_order_data->silver_price)) ? $new_order_data->silver_price : ''; ?>">
                                        <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
                                        <div class="reason" style="display: none;"><br />
                                            <label>Reason</label>
                                            <textarea name="reason" id="reason" class="form-control reason"><?= (isset($new_order_data->reason)) ? $new_order_data->reason : ''; ?></textarea>
                                        </div>
                                        <?php } ?>
                                        <br />
                                        <div class="party_balance" id="party_balance" ></div>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <div class="line_item_form item_fields_div">
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <?php if(isset($new_order_data)){ ?>
                                            <input type="hidden" name="line_items_data[order_lot_item_id]" id="lineitem_id" />
                                        <?php } ?>
                                        <h4 class="col-md-12"> Lot Items</h4>
                                        <div class="col-md-3">
                                            <label for="item_id">Select Category<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[category_id]" class="form-control category_id select2" id="category_id"></select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="item_id">Select Item<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[item_id]" class="form-control item_id select2" id="item_id">
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="touch_id">Tunch<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[touch_id]" class="form-control touch_id" id="touch_id">
                                                <option value=""> - Select - </option>
                                                <?php foreach ($touch as $value) { ?>
                                                    <option value="<?= $value->carat_id; ?>"<?= isset($touch_id) && $value->carat_id == $touch_id ? 'selected="selected"' : ''; ?>><?= $value->purity; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="weight">Weight<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[weight]" class="form-control num_only weight" id="weight"  placeholder="" value="<?= (isset($order_lot_item_data->weight)) ? $order_lot_item_data->weight : ''; ?>"><br />
                                        </div>
                                        <div class="col-md-1"  >
                                            <label for="pcs">PCS<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[pcs]" class="form-control num_only pcs" id="pcs" placeholder="" value="<?= (isset($order_lot_item_data->pcs)) ? $order_lot_item_data->pcs : ''; ?>"><br />
                                        </div>
                                        <div class="col-md-1">
                                            <label for="size">Size</label>
                                            <input type="text" name="line_items_data[size]" class="form-control num_only size" id="size" placeholder="" value="<?= (isset($order_lot_item_data->size)) ? $order_lot_item_data->size : ''; ?>"><br />
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-2">
                                            <label for="length">Length</label>
                                            <input type="text" name="line_items_data[length]" class="form-control num_only length" id="length" placeholder="" value="<?= (isset($order_lot_item_data->length)) ? $order_lot_item_data->length : ''; ?>"><br />
                                        </div>
                                        <div class="col-md-2">
                                            <label for="hook_style">Hook Style</label>
                                            <input type="text" name="line_items_data[hook_style]" class="form-control hook_style" id="hook_style" placeholder="" value="<?= (isset($order_lot_item_data->hook_style)) ? $order_lot_item_data->hook_style : ''; ?>"><br />
                                        </div>
                                        <div class="col-md-2">
                                            <label for="lot_remark">Remark</label>
                                            <input type="text" name="line_items_data[lot_remark]" class="form-control lot_remark" id="lot_remark" placeholder="" value="<?= (isset($order_lot_item_data->lot_remark)) ? $order_lot_item_data->lot_remark : ''; ?>"><br />
                                        </div>
                                        <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
                                        <div class="col-md-2">
                                            <label for="remark">Status<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[item_status_id]" id="item_status_id" class="form-control item_status_id"></select><br />
                                        </div>
                                        <?php } ?>
                                        <div class="col-md-2">
                                            <label for="file_upload">Image</label>
                                            <input type="file" name="line_items_data[file_upload]" id="file_upload" class="from-control" onchange="readURL(this);" accept="image/*" value="<?= (isset($order_lot_item_data->image)) ? $order_lot_item_data->image : ''; ?>"><br />
                                            <input type="hidden" name="line_items_data[image]" id="image" class="from-control" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <br /><span id="image_name"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <input type="button" id="add_lineitem" class="btn btn-info btn-sm pull-right add_lineitem" value="Add Lot Item" style="margin-top: 21px;"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-12">
                                        <table style="" class="table custom-table item-table">
                                            <thead>
                                                <tr>
                                                    <th width="100px">Action</th>
                                                    <th>Category Name</th>
                                                    <th>Item Name</th>
                                                    <th>Tunch</th>
                                                    <th class="text-right">Weight</th>
                                                    <th class="text-right">PCS</th>
                                                    <th class="text-right">Size</th>
                                                    <th class="text-right">Length</th>
                                                    <th>Hook Style</th>
                                                    <th>Remark</th>
                                                    <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
                                                    <th>Status</th>
                                                    <?php } ?>
                                                    <th>Image</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lineitem_list"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-right" id="total_weight"></th>
                                                    <th class="text-right" id="total_pcs"></th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
                                    <div class="created_updated_info" style="margin-left: 10px;">
                                       Created by : <?php echo isset($new_order_data ->created_by_name) ? $new_order_data ->created_by_name :'' ; ?>
                                       @ <?php echo isset($new_order_data ->created_at) ? date('d-m-Y h:i A',strtotime($new_order_data ->created_at)) :'' ; ?><br/>
                                       Updated by : <?php echo isset($new_order_data ->updated_by_name) ? $new_order_data ->updated_by_name :'' ;?>
                                       @ <?php echo isset($new_order_data ->updated_at) ? date('d-m-Y h:i A',strtotime($new_order_data ->updated_at)) : '' ;?>
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
    <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Document Image</h4>
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
    
    var first_time_edit_mode = 1;
    var on_save_add_edit_item = 0;
    var edit_lineitem_inc = 0;
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
    $(document).ready(function () {
        <?php if(!isset($new_order_data->supplier_delivery_date)) { ?>
            var supplier_delivery_date = moment($('#delivery_date').val(), 'DD-MM-YYYY').subtract(2,'days').format("DD-MM-YYYY");
            $('#supplier_delivery_date').val(supplier_delivery_date);
        <?php } ?>
            
        $('#order_type').select2();
        $('input[type="checkbox"].send_sms').iCheck({
			checkboxClass: 'icheckbox_flat-blue',
		});
        $('input[type="checkbox"].send_whatsapp_sms').iCheck({
			checkboxClass: 'icheckbox_flat-green',
		});
        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");

            setTimeout(function () {
                $("#doc_img_src").attr('src', src);
            }, 0);
            $('#edit-modal').modal('show');
        });
        $('#datepicker2').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            endDate: "today",
            maxDate: 0,
        })

        $('#delivery_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: '-0m',
        }).on('changeDate', function(ev){
            /*var supplier_delivery_date = moment($('#delivery_date').val(), 'DD-MM-YYYY').subtract(2,'days').format("DD-MM-YYYY");
            $('#supplier_delivery_date').val(supplier_delivery_date);*/

            /*if($("#delivery_date").val() != $("#datepicker2").val()) {


                $("#supplier_delivery_date").val($("#delivery_date").val());
            } else {
                $("#supplier_delivery_date").val('');
            }*/
        });
        
        initAjaxSelect2($("#party_id"), "<?= base_url('app/party_name_with_number_for_order_select2_source') ?>");
        <?php if (isset($new_order_data->party_id)) { ?>
            get_account_fine(<?php echo $new_order_data->party_id; ?>);
            setSelect2Value($("#party_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . $new_order_data->party_id) ?>");
        <?php } else { ?>
            $("#party_id").select2('open');
        <?php } ?>
        
        initAjaxSelect2($("#supplier_id"), "<?= base_url('app/supplier_worker_with_number_select2_source') ?>");
        <?php if (isset($new_order_data->supplier_id)) { ?>
            setSelect2Value($("#supplier_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . $new_order_data->supplier_id) ?>");
        <?php } ?>

        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_for_order_select2_source') ?>");
        initAjaxSelect2($("#department_id"), "<?= base_url('app/order_department_select2_source') ?>");

        <?php if (isset($new_order_data->process_id)) { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $new_order_data->process_id) ?>");
        <?php } else { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
        <?php } ?>

            initAjaxSelect2($("#category_id"), "<?= base_url('app/category_for_gold_and_silver_select2_source') ?>");
        <?php if (isset($new_order_data->category_id)) { ?>
            setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/' . $new_order_data->category_id) ?>");
        <?php } ?>
        <?php if (isset($order_lot_item->item_id)) { ?>
            setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/' . $order_lot_item->item_id) ?>");
        <?php } ?>
        <?php if (isset($new_order_data->touch_id)) { ?>
            setSelect2Value($("#touch_id"), "<?= base_url('app/set_touch_select2_val_by_id/' . $new_order_data->touch_id) ?>");
        <?php } ?>
            initAjaxSelect2($("#order_status_id"), "<?= base_url('app/order_status_select2_source') ?>");
        <?php if (isset($new_order_data->order_status_id)) { ?>
            setSelect2Value($("#order_status_id"), "<?= base_url('app/set_order_status_select2_val_by_id/' . $new_order_data->order_status_id) ?>");
        <?php } ?>
            initAjaxSelect2($("#item_status_id"), "<?= base_url('app/order_status_select2_source') ?>");
        
//        $("#category_id").select2();
        $("#touch_id").select2();
        
        $(document).on('change', '#order_status_id', function () {
            var order_status = $('#order_status_id').val();
            if(order_status == '2'){ 
                $('.reason').show();
            } else {
                $('.reason').hide();
            }
        });
        
        <?php if (isset($new_order_data->order_status_id) && $new_order_data->order_status_id == '2') { ?>
            $('.reason').show();
        <?php } else { ?>
            $('.reason').hide();
        <?php } ?>
        
        $(document).on('change', '#category_id', function (e) {
            $('#item_id').val(null).trigger('change');
            var category_id = $('#category_id').val();
            if (category_id != '' && category_id != null) {
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_for_order_select2_source') ?>/" + category_id);
            } else {
                $('#item_id').val(null).trigger('change');
            }
        });
         
        $(document).on('change', '#party_id', function(){
            var party_id = $('#party_id').val();
            get_account_fine(party_id);
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                e.preventDefault();
                if(module_submit_flag == 0 ){
                    $("#save_order").submit();
                    return false;
                }
            }
        });
        
        $(document).on('submit', '#save_order', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department Name.', false);
                $("#department_id").select2('open');
                return false;
            }
            var order_type = $('#order_type').val();
            if(order_type == 1) {
                var item_rate = $("#delivery_date").val();
                if (item_rate == '' || item_rate == null) {
                    show_notify("Delivery Date is required!", false);
                    $("#delivery_date").focus();
                    return false;
                }
            }

            if($.trim($("#datepicker2").val()) == $.trim($("#delivery_date").val())){
                if(!confirm('Delivery Date is same as Order Date.')){
                    return false;
                }
            }
            if ($.trim($("#party_id").val()) == '') {
                show_notify('Please Select Party Name.', false);
                $("#party_id").select2('open');
                return false;
            }
            if (lineitem_objectdata == '') {
                show_notify("Please Add Order Lot Item.", false);
                return false;
            }
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            $.ajax({
                url: "<?= base_url('new_order/save_new_order') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                async: false,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['send_whatsapp_sms_url']) {
                        window.open(json['send_whatsapp_sms_url'],"_blank");
                    }
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('new_order/new_order_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('new_order/new_order_list') ?>";
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
            var touch_id = $("#touch_id").val();
            if (touch_id == '' || touch_id == null) {
                $("#touch_id").select2('open');
                show_notify('Please Select Touch.', false);
                return false;
            }
            var order_type = $('#order_type').val();
            if(order_type == 1) {
                var item_rate = $("#weight").val();
                if (item_rate == '' || item_rate == null) {
                    show_notify("Weight is required!", false);
                    $("#weight").focus();
                    return false;
                }
                var item_rate = $("#pcs").val();
                if (item_rate == '' || item_rate == null) {
                    show_notify("Pcs is required!", false);
                    $("#pcs").focus();
                    return false;
                }
            }
            <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
                    var item_status = $("#item_status_id").val();
                    if (item_status== '' || item_status == null) {
                        show_notify("Please Select Status!", false);
                        $("#item_status_id").select2('open');
                        return false;
                    }
            <?php } ?>
            $('#add_lineitem').attr('disabled', 'disabled');
            var key = '';
            var value = '';
            var lineitem = {};
            $('select[name^="line_items_data"]').each(function (e) {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
//            console.log(lineitem);
            $('input[name^="line_items_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });
            var category_data = $('#category_id option:selected').html();
            var item_data = $('#item_id option:selected').html();
            var item_status = $('#item_status_id option:selected').html();
            
            lineitem['category_name'] = category_data;
            lineitem['item_name'] = item_data;
            lineitem['item_status'] = item_status;
            lineitem['is_sell'] = 0;
            var touch_data = $('#touch_id').val();
            lineitem['purity'] = $('#touch_id option:selected').html();
            $.ajax({
                url: "<?php echo base_url('sell/get_category_group'); ?>/" + item_id,
                type: "GET",
                contentType: "application/json",
                data: "",
                success: function(response){
                    var json = $.parseJSON(response);
//                    console.log(json);
               
                    lineitem['group_name'] = json;
                    var oli_id = $('#oli_id').val();
                    lineitem['order_lot_item_id'] = oli_id;
                    $('#oli_id').val('');
                    var new_lineitem = JSON.parse(JSON.stringify(lineitem));
                    var line_items_index = $("#line_items_index").val();
                    if (line_items_index != '') {
                        lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
                    } else {
                        lineitem_objectdata.push(new_lineitem);
                    } 
        //            console.log(lineitem_objectdata);
                    display_lineitem_html(lineitem_objectdata);
                    $('#lineitem_id').val('');
                    $("#category_id").val(null).trigger("change");
                    $("#item_id").val(null).trigger("change");
                    $("#touch_id").val(null).trigger("change");
                    $("#item_status_id").val(null).trigger("change");
                    $("#weight").val('');
                    $("#pcs").val('');
                    $("#size").val('');
                    $("#length").val('');
                    $("#hook_style").val('');
                    $("#lot_remark").val('');
                    $("#image").val('');
                    $("#image_name").hide();
                    $("#file_upload").val('');
                    $("#line_items_index").val('');
                    if (on_save_add_edit_item == 1) {
                        on_save_add_edit_item == 0;
                        $('#save_order').submit();
                    }
                    edit_lineitem_inc = 0;
                }
            });
            $("#add_lineitem").removeAttr('disabled', 'disabled');
        });
        
    });
    
    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        var total_pcs = 0;
        var total_weight = 0;
        var total_final_weight = 0;
//        console.log(lineitem_objectdata);
        $.each(lineitem_objectdata, function (index, value) {
//            console.log("value");
//            console.log(value); return false;
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';  
            var pcs = value.pcs || 0;
            total_pcs = total_pcs + parseFloat(pcs); 
            var weight = parseFloat(value.weight) || 0;
            if(value.group_name == 1){
                var weight = round(weight, 2).toFixed(3);
            } else {
                var weight = round(weight, 1).toFixed(3);
            }
            
            total_final_weight = parseFloat(pcs) * weight;
            total_weight = total_weight + total_final_weight;
            
            var purity = parseFloat(value.purity);
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_o_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td>' + value.category_name + '</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right">' + purity + '</td>' +
                    '<td class="text-right">' + weight + '</td>' +
                    '<td class="text-right">' + pcs + '</td>'+
                    '<td class="text-right">' + value.size + '</td>'+
                    '<td class="text-right">' + value.length + '</td>'+
                    '<td>' + value.hook_style + '</td>'+
                    <?php if (isset($new_order_data->order_id) && !empty($new_order_data->order_id)) { ?>
                    '<td>' + value.lot_remark + '</td>'+
                    '<td>' + value.item_status + '</td>';
                    <?php } else { ?>
                        '<td>' + value.lot_remark + '</td>';
                    <?php } ?>
                    if(value.image !== null && value.image !== ''){
                        var img_url = '<?php echo base_url(); ?>'+value.image;
                        row_html += '<td><a href="javascript:void(0)" class="btn btn-xs btn-primary image_model" data-img_src="'+img_url+'" ><i class="fa fa-image"></i></a></td>';
                    }
            new_lineitem_html += row_html;
        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#total_pcs').html(total_pcs);
        $('#total_pcs_db').val(total_pcs);
        $('#total_weight').html(total_weight.toFixed(3));
        $('#total_weight_db').val(total_weight.toFixed(3));
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_o_item").addClass('hide');
        if (edit_lineitem_inc == 0) {
            edit_lineitem_inc = 1;
            $(".add_lineitem").removeAttr("disabled");
        }
        var value = lineitem_objectdata[index];
        var oli_id = value.order_lot_item_id;
        $('#oli_id').val(oli_id);
        $("#line_items_index").val(index);
		if(typeof(value.id) != "undefined" && value.id !== null) {
			$("#lineitem_id").val(value.id);
		}
        $("#category_id").val(value.category_id).trigger("change");
        setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/') ?>" + value.category_id);
        $("#item_id").val(null).trigger("change");
        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + value.item_id);
        $("#item_status_id").val(null).trigger("change");
        setSelect2Value($("#item_status_id"), "<?= base_url('app/set_order_status_select2_val_by_id/') ?>" + value.item_status_id);
        $("#touch_id").val(value.touch_id).trigger("change");
        $("#weight").val(value.weight);
        $("#pcs").val(value.pcs);
        $("#size").val(value.size);
        $("#length").val(value.length);
        $("#hook_style").val(value.hook_style);
        $("#lot_remark").val(value.lot_remark);
        $("#image").val(value.image);
        $("#image_name").show();
        $("#image_name").text(value.image);
//        $("#file_upload").val(value.file_upload);
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        value = lineitem_objectdata[index];
        if(value.is_sell == 1){
            show_notify("You can not delete this item. This item has been used!", false);
        }
        else {
            if (confirm('Are you sure ?')) {
                var oli_id = value.order_lot_item_id;
                var deleted_oli_ids = $('#deleted_oli_ids').val();
                if(deleted_oli_ids != ''){
                    deleted_oli_ids += ', '+oli_id;
                    $('#deleted_oli_ids').val(deleted_oli_ids);
                }
                else{
                    $('#deleted_oli_ids').val(oli_id);
                }
                if (typeof (value.lineitem_id) != "undefined" && value.lineitem_id !== null) {
                    $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.lineitem_id + '" />');
                }
                lineitem_objectdata.splice(index, 1);
                display_lineitem_html(lineitem_objectdata);
            }
        }
    }
    
    function get_account_fine(party_id){
        <?php if (!$this->applib->have_access_role(BALANCE_ID, 'view')) { ?>
            return false;
        <?php } ?>
        if(party_id != '' && party_id != null){
            $.ajax({
                url: "<?= base_url('sell/get_account_old_balance') ?>/" + party_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    var gold_fine = round(json.gold_fine, 2).toFixed(3);
                    var silver_fine = round(json.silver_fine, 1).toFixed(3);
                    if(gold_fine > 0){
                        gold_fine = '<span style="color:red;">'+ gold_fine + '</span>';
                    } else {
                        gold_fine = '<span style="color:blue;">'+ gold_fine + '</span>';
                    }
                    if(silver_fine > 0){
                        silver_fine = '<span style="color:red;">'+ silver_fine + '</span>';
                    } else {
                        silver_fine = '<span style="color:blue;">'+ silver_fine + '</span>';
                    }
                    if(json.amount > 0){
                        var amount = '<span style="color:red;">'+ json.amount + '</span>';
                    } else {
                        var amount = '<span style="color:blue;">'+ json.amount + '</span>';
                    }
                    $('#party_balance').html('<label>Old Balance</label><br /><span><b>Gold Fine : ' + gold_fine + '</b><br /></span><span><b>Silver Fine : ' + silver_fine + '</b></span><br /><span><b>Amount : ' + amount + '</b></span>');
                }
            });
        }
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
                url: "<?= base_url('new_order/get_temp_path_image') ?>",
                success: function(html){
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
    
</script>
