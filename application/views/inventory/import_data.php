<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('inventory/save_import_data') ?>" method="post" id="save_import_data" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($ir_data->ir_id) && !empty($ir_data->ir_id)) { ?>
            <input type="hidden" name="ir_id" class="ir_id" value="<?= $ir_data->ir_id ?>">
        <?php } ?>
            <input type="hidden" id="total_grwt_sell" value=""/>

        <!-- Content Header (Page header) -->
     
        <section class="content-header">
            <h1>
                Import Inventory Data
                <?php $isEdit = $this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "add");
                $allow_change_date = $this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "allow_change_date"); ?>
                <?php if(isset($ir_data->ir_id) && !empty($ir_data->ir_id)) { } else { if(isset($isAdd) && $isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($ir_data->ir_id) ? '' : $btn_disable;?>><?= isset($ir_data->ir_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('inventory/data_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Data List</a>
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
                                        <label for="worker_id">Worker Name<span class="required-sign">&nbsp;*</span></label>
                                        <select name="worker_id" id="worker_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br />
                                        <?php if (isset($ir_data->ir_id) && !empty($ir_data->ir_id)) { ?>
                                            <label for="reference_no">Reference No</label>
                                            <input type="text" name="reference_no" id="reference_no" class="form-control" readonly="" value="<?= (isset($ir_data->reference_no)) ? $ir_data->reference_no : ''; ?>">
                                        <?php } ?>
                                        <span class="text-danger">
                                            Note : <br>
                                            In import file, If item are not exist in master the we will consider their Category 'FINE GOLD'<br>
                                            And if exist in master the we will consider their Category from master
                                        </span>
                                        <div class="clearfix"></div><br />

                                    </div>
                                    <div class="col-md-4">
                                        <label for="department_id">Department<span class="required-sign">&nbsp;*</span></label>
                                        <select name="department_id" id="department_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br />

                                        <label for="ir_remark">Remark</label>
                                        <textarea name="ir_remark" id="ir_remark" class="form-control"><?php echo (isset($ir_data->ir_remark)) ? $ir_data->ir_remark : ''; ?></textarea><br />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="date">Date<span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" name="ir_date" id="datepicker2" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control input-datepicker" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?= (isset($ir_data->ir_date)) ? date('d-m-Y', strtotime($ir_data->ir_date)) : date('d-m-Y'); ?>">
                                        <div class="clearfix"></div><br />

                                        <label for="import_file">Import File</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="<?= base_url('assets/inventory_sample_files/data.xlsx') ?>">Download Sample File</a>
                                        <input type="file" name="import_file" id="import_file" class="" >
                                        
                                        <input type="hidden" name="lott_complete" value="1">

                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if (isset($ir_data->ir_id) && !empty($ir_data->ir_id)) { ?>
                                    <div class="created_updated_info" style="margin-left: 10px;">
                                       Created by : <?php echo isset($ir_data->created_by_name) ? $ir_data->created_by_name :'' ; ?>
                                       @ <?php echo isset($ir_data->created_at) ? date('d-m-Y h:i A',strtotime($ir_data->created_at)) :'' ; ?><br/>
                                       Updated by : <?php echo isset($ir_data->updated_by_name) ? $ir_data->updated_by_name :'' ;?>
                                       @ <?php echo isset($ir_data->updated_at) ? date('d-m-Y h:i A',strtotime($ir_data->updated_at)) : '' ;?>
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
<script type="text/javascript">
    var module_submit_flag = 0;
    var zero_value = 0;
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
    <?php if (isset($issue_receive_detail)) { ?>
        var li_lineitem_objectdata = [<?php echo $issue_receive_detail; ?>];
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    display_lineitem_html(lineitem_objectdata);
    $(document).ready(function () {
        $('.type_id, #touch_id').select2();

        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_select2_source') ?>");
        initAjaxSelect2($("#worker_id"), "<?= base_url('app/worker_select2_source') ?>");
        <?php if (isset($ir_data->worker_id)) { ?>
            setSelect2Value($("#worker_id"), "<?= base_url('app/set_worker_select2_val_by_id/' . $ir_data->worker_id) ?>");
        <?php }?>
//        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        <?php if (isset($ir_data->department_id)) { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $ir_data->department_id) ?>");
        <?php } else { ?>
            
        <?php } ?>
            
        $('#purchase_item_selection_popup').on('hidden.bs.modal', function () {
            $("#item_id").val(null).trigger("change");
            $(".delete_ir_item").removeClass('hide');
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
        
        $(document).on('change', '#worker_id', function(){
            var worker_id = $('#worker_id').val();
            $('#department_id').val(null).trigger('change');
            if(worker_id != '' && worker_id != null){
                initAjaxSelect2($("#department_id"), "<?= base_url('app/workers_department_select2_source/') ?>/" + worker_id);
                $.ajax({
                    url: "<?php echo base_url('manufacture/get_default_department_of_worker'); ?>/" + worker_id,
                    type: "GET",
                    async: false,
                    data: "",
                    success: function (response) {
                        var json = $.parseJSON(response);
                        setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id') ?>/" + json.default_department_id);
                    }
                });
            } else {
                $('#department_id').val(null).empty().select2('destroy');
            }
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
                    $(".edit_ir_item").removeClass('hide');
                    $(".delete_ir_item").removeClass('hide');
                    $('#worker_id').removeAttr('disabled','disabled');
                    $('#after_disabled_worker_id').remove();

                    $('#ir_diffrence').val('');
                    $('#ir_diffrence_value').html('');
                    $('#ir_diffrence_calculation').html('');
                    $('#ir_diffrence_amount').html('');
                    $('#worker_gold_rate').val('');
                    $('.lott_complete_div').addClass('hide');
                } else {
                    $(".add_lineitem").addClass('hide');
                    $(".edit_ir_item").addClass('hide');
                    $(".delete_ir_item").addClass('hide');

                    var worker_id = $('#worker_id').val();
                    $('#worker_id').attr('disabled','disabled');
                    $('#worker_id').closest('div').append('<input type="hidden" name="worker_id" id="after_disabled_worker_id" value="' + worker_id + '" />');

                    var worker_gold_rate = '<?php echo $worker_gold_rate; ?>';
                    $('#worker_gold_rate').val(worker_gold_rate);
                    $('.lott_complete_div').removeClass('hide');
                    var ir_diffrence = 0;
                    var ir_diffrence_calculation = '';
                    ir_diffrence = $('#karigar_fine').html();
                    $("#ir_diffrence").val(ir_diffrence);
                    $("#ir_diffrence_value").html(ir_diffrence);
                    $("#ir_diffrence_calculation").html(ir_diffrence_calculation);
                    var ir_diffrence_amount = parseFloat(ir_diffrence) / 10 * parseFloat(worker_gold_rate);
                    ir_diffrence_amount = round(ir_diffrence_amount, 0).toFixed(2);
                    $("#ir_diffrence_amount").html(ir_diffrence_amount);
                    $('.lott_complete_div').removeClass('hide');
                }
            }
        });
        <?php if((isset($ir_data->lott_complete)) && $ir_data->lott_complete == 1){ ?>
            $(".add_lineitem").addClass('hide');
            $(".edit_ir_item").addClass('hide');
            $(".delete_ir_item").addClass('hide');
            $('#worker_gold_rate').val('<?php echo $worker_gold_rate; ?>');
            $('.lott_complete_div').removeClass('hide');
        <?php } ?>

        $(document).on('keyup change', '#worker_gold_rate', function(){
            var ir_diffrence = $("#ir_diffrence").val() || 0;
            var worker_gold_rate = $("#worker_gold_rate").val() || 0;
            var ir_diffrence_amount = parseFloat(ir_diffrence) * parseFloat(worker_gold_rate) / 10;
            ir_diffrence_amount = round(ir_diffrence_amount, 2).toFixed(3);
            $("#ir_diffrence_amount").html(ir_diffrence_amount);
        });
        
        $(document).on('change', '#type_id', function () {
            if ($.trim($("#worker_id").val()) == '') {
                show_notify('Please Select Worker Name.', false);
                $("#worker_id").select2('open');
                $('#type_id').val(null).trigger('change.select2');
                return false;
            }
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                $('#type_id').val(null).trigger('change.select2');
                return false;
            }
//            var type_id = $('#type_id').val();
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
//            $('#ird_date').val(todays_date).trigger('change');
            
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
                        
                        if (json.stock_method == '2') {
//                            alert();
                            var type_id = $('#type_id').val();
                            
                            if(type_id == <?php echo MANUFACTURE_TYPE_ISSUE_ID; ?>){
                                var department_id = $('#department_id').val();
                                var ir_id = '';
                                <?php if (isset($ir_data->ir_id) && !empty($ir_data->ir_id)) { ?>
                                    ir_id = '<?php echo $ir_data->ir_id; ?>';
                                <?php } ?>
                                $.ajax({
                                    url: "<?php echo base_url('sell/get_purchase_to_sell_pending_item'); ?>/",
                                    type: 'POST',
                                    async: false,
                                    data: {department_id : department_id, item_id : item_id, ir_id : ir_id, do_not_count_wstg : 1},
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
        
        $(document).on('keyup change', '#weight, #net_wt, #wastage', function () {
            var net_wt = parseFloat($('#net_wt').val()) || 0;
            net_wt = round(net_wt, 2).toFixed(3);
            var wastage = parseFloat($('#wastage').val()) || 0;
            wastage = round(wastage, 2).toFixed(3);
            var calculated_wastage = parseFloat(net_wt) * parseFloat(wastage) / 100;
            calculated_wastage = round(calculated_wastage, 2).toFixed(3);
            $('#calculated_wastage').val(calculated_wastage);
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

            $.each($("input.pts_selected_index:checked"), function() {
                pts_item_id = $(this).data('item_id');
                var pts_selected_index = $(this).data('pts_selected_index');
                pts_lineitem_objectdata[pts_selected_index].ir_item_delete = 'allow';
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
                var d = new Date();

                var month = d.getMonth()+1;
                var day = d.getDate();

                var todays_date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
            
                pts_lineitem_objectdata[pts_selected_index].ird_date = todays_date;
                pts_lineitem_objectdata[pts_selected_index].ird_remark = '';
                pts_lineitem_objectdata[pts_selected_index].type_id = <?php echo MANUFACTURE_TYPE_ISSUE_ID; ?>;
                
                /******* You not allow to Issue, The Receive of same Entry! Start *******/
                $.each(lineitem_objectdata, function(index, value) {
                    if(typeof(pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id) !== "undefined" && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id !== null && 
                            typeof(value.sell_item_id) !== "undefined" && value.sell_item_id !== null && 
                            pts_lineitem_objectdata[pts_selected_index].purchase_sell_item_id == value.sell_item_id &&
                            pts_lineitem_objectdata[pts_selected_index].stock_type == '4') {
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
//                lineitem_delete.push(values.ird_id);
//            });
//            pts_delete = [];
//            jQuery.each(pts_selected_index_lineitems, function(obj, values) {
//                pts_delete.push(values.ird_id);
//            });
//            var uncheck_sell_item = $(lineitem_delete).not(pts_delete).get();
//            
//            $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + uncheck_sell_item + '" />');
                
            $('#purchase_item_selection_popup').modal('hide');
            
            var remove_arr = [];
            $.each(lineitem_objectdata, function(index, value) {
                if(typeof(value.item_id) !== "undefined" && value.item_id !== null && value.item_id == pts_item_id && value.type_id == '1') {
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
                    $("#save_import_data").submit();
                    return false;
                }
            }
        });

        $(document).on('submit', '#save_import_data', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#worker_id").val()) == '') {
                show_notify('Please Select Worker Name.', false);
                $("#worker_id").select2('open');
                return false;
            }
            var department_id = $('#department_id').val();
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                return false;
            }
            var datepicker2 = $("#datepicker2").val();
            if(datepicker2 == '' ){
                show_notify('Please Select Manufacture Date.', false);
                $("#datepicker2").focus();
                return false;
            }

            if ($.trim($("#import_file").val()) == '') {
                show_notify('Please Select Import Data file.', false);
                return false;
            }
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('inventory/save_import_data') ?>",
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
                        window.location.href = "<?php echo base_url('inventory/data_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('inventory/data_list') ?>";
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
            
            if($("#ird_date").val() == '') {
                show_notify('Please Select Issue/Receive Lineitem Date.', false);
                $("#ird_date").focus();
                return false;
            }

            $("#add_lineitem").attr('disabled', 'disabled');
            var ird_id = $("#ird_id").val();
            if (typeof (ird_id) !== "undefined" && ird_id !== null) {
                $('.line_item_form #deleted_lineitem_id[value="' + ird_id + '"]').remove();
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
                        if (type_id == '<?php echo MANUFACTURE_TYPE_ISSUE_ID; ?>'){
                            var ird_id = $("#ird_id").val();
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
                                data: {process_id : process_id, category_id : category_id, item_id : item_id, touch_id : touch_id, ird_id : ird_id},
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
                                    $("#ir_item_delete").val('allow');
                                    $('#ird_id').val('');
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
                                    $("#wastage").val('');
                                    $("#calculated_wastage").val('');
//                                    var d = new Date();
//                                    var curr_date = strpad00(d.getDate())+'-'+strpad00(d.getMonth()+1)+'-'+d.getFullYear();
                                    $("#ird_date").val('');
                                    $("#ird_date").datepicker('setDate', null);
                                    $("#ird_remark").val('');
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
                            $("#ir_item_delete").val('allow');
                            $('#ird_id').val('');
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
                            $("#wastage").val('');
                            $("#calculated_wastage").val('');
//                            var d = new Date();
//                            var curr_date = strpad00(d.getDate())+'-'+strpad00(d.getMonth()+1)+'-'+d.getFullYear();
                            $("#ird_date").val('');
                            $("#ird_date").datepicker('setDate', null);
                            $("#ird_remark").val('');
                            edit_lineitem_inc = 0;
                        }
                    }
                });
                $('#total_grwt_sell').val('');
            }
            $("#add_lineitem").removeAttr('disabled', 'disabled');
        }); 
    });
    
    function get_category_from_item(item_id){
        if(item_id != '' && item_id != null){
            var category_id = '';
            $.ajax({
                url: "<?php echo base_url('manufacture/get_category_from_item'); ?>/",
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
                                pts_lineitem_objectdata[index].ird_id = li_value.ird_id;
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
        var total_issue_wastage = 0;
        var total_issue_calculated_wastage = 0;
        var total_receive_weight = 0;
        var total_receive_less = 0;
        var total_receive_net_wt = 0;
        var total_receive_fine = 0;
        var total_receive_tunch = 0;
        var total_receive_wastage = 0;
        var total_receive_calculated_wastage = 0;
        
        if($.isEmptyObject(lineitem_objectdata)){
            $('#department_id').removeAttr('disabled','disabled');
            $('#after_disabled_department_id').remove();
        } else {
            var department_id = $('#department_id').val();
            $('#department_id').attr('disabled','disabled');
            $('#department_id').closest('div').append('<input type="hidden" name="department_id" id="after_disabled_department_id" value="' + department_id + '" />');
        }

        var tunch_arr = [];
        var tunch_grwt_arr = [];
        var tunch_issue_calculated_wastage_arr = [];
        var tunch_receive_calculated_wastage_arr = [];
        $.each(lineitem_objectdata, function (index, value) {

            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';  
            var weight = parseFloat(value.weight) || 0;
            var less = parseFloat(value.less) || 0;
            var net_wt = parseFloat(value.net_wt) || 0;
            var fine = parseFloat(value.fine) || 0;
            var wastage = parseFloat(value.wastage) || 0;
            var calculated_wastage = parseFloat(value.calculated_wastage) || 0;
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_ir_item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(value.ir_item_delete == 'allow'){
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_ir_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            }
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td>' + value.item_name + '</td>' +
                    '<td class="text-right">' + weight.toFixed(3) + '</td>' +
                    '<td class="text-right">' + less.toFixed(3) + '</td>' +
                    '<td class="text-right">' + net_wt.toFixed(3) + '</td>' +
                    '<td class="text-right">' + value.purity + '</td>' +
                    '<td class="text-right">' + value.actual_tunch + '</td>';
            row_html += '<td class="text-right">' + wastage.toFixed(3) + '</td>';
            row_html += '<td class="text-right">' + fine.toFixed(3) + '</td>';
            row_html += '<td class="text-right">' + calculated_wastage.toFixed(3) + '</td>';
            row_html += '<td class="text-nowrap">' + value.ird_date + '</td>'+
                    '<td>' + value.ird_remark + '</td>';
            if(value.type_id == '<?php echo MANUFACTURE_TYPE_RECEIVE_ID; ?>'){
                total_receive_weight = parseFloat(total_receive_weight) + parseFloat(weight);
                total_receive_less = parseFloat(total_receive_less) + parseFloat(less);
                total_receive_net_wt = parseFloat(total_receive_net_wt) + parseFloat(net_wt);
                total_receive_fine = parseFloat(total_receive_fine) + parseFloat(fine);
                new_receive_lineitem_html += row_html;
                total_receive_tunch = (parseFloat(total_receive_fine) / parseFloat(total_receive_net_wt)) * 100;
                total_receive_tunch = total_receive_tunch || 0;
                total_receive_wastage = parseFloat(total_receive_wastage) + parseFloat(wastage);
                total_receive_calculated_wastage = parseFloat(total_receive_calculated_wastage) + parseFloat(calculated_wastage);
            } else if(value.type_id == '<?php echo MANUFACTURE_TYPE_ISSUE_ID; ?>'){
                total_issue_weight = parseFloat(total_issue_weight) + parseFloat(weight);
                total_issue_less = parseFloat(total_issue_less) + parseFloat(less);
                total_issue_net_wt = parseFloat(total_issue_net_wt) + parseFloat(net_wt);
                total_issue_fine = parseFloat(total_issue_fine) + parseFloat(fine);
                new_issue_lineitem_html += row_html;
                total_issue_tunch = (parseFloat(total_issue_fine) / parseFloat(total_issue_net_wt)) * 100;
                total_issue_tunch = total_issue_tunch || 0;
                total_issue_wastage = parseFloat(total_issue_wastage) + parseFloat(wastage);
                total_issue_calculated_wastage = parseFloat(total_issue_calculated_wastage) + parseFloat(calculated_wastage);
            }

            // Start balance_calculation_list
            if($.inArray(parseInt(value.purity), tunch_arr) !== -1) {
                if(tunch_grwt_arr[parseInt(value.purity)] && tunch_grwt_arr[parseInt(value.purity)] != 'undefined'){
                    if(value.type_id == '<?php echo MANUFACTURE_TYPE_RECEIVE_ID; ?>'){
                        tunch_grwt_arr[parseInt(value.purity)] = parseFloat(tunch_grwt_arr[parseInt(value.purity)]) - parseFloat(weight);
                        tunch_receive_calculated_wastage_arr[parseInt(value.purity)] = parseFloat(tunch_receive_calculated_wastage_arr[parseInt(value.purity)]) + parseFloat(calculated_wastage);
                    } else {
                        tunch_grwt_arr[parseInt(value.purity)] = parseFloat(tunch_grwt_arr[parseInt(value.purity)]) + parseFloat(weight);
                        tunch_issue_calculated_wastage_arr[parseInt(value.purity)] = parseFloat(tunch_issue_calculated_wastage_arr[parseInt(value.purity)]) + parseFloat(calculated_wastage);
                    }
                } else {
                    if(value.type_id == '<?php echo MANUFACTURE_TYPE_RECEIVE_ID; ?>'){
                        tunch_grwt_arr[parseInt(value.purity)] = zero_value - parseFloat(weight);
                        tunch_receive_calculated_wastage_arr[parseInt(value.purity)] = parseFloat(calculated_wastage);
                        if(tunch_issue_calculated_wastage_arr[parseInt(value.purity)]){ } else {
                            tunch_issue_calculated_wastage_arr[parseInt(value.purity)] = 0;
                        }
                    } else {
                        tunch_grwt_arr[parseInt(value.purity)] = parseFloat(weight);
                        if(tunch_receive_calculated_wastage_arr[parseInt(value.purity)]){ } else {
                            tunch_receive_calculated_wastage_arr[parseInt(value.purity)] = 0;
                        }
                        tunch_issue_calculated_wastage_arr[parseInt(value.purity)] = parseFloat(calculated_wastage);
                    }
                }
            } else {
                tunch_arr.push(parseInt(value.purity));
                if(value.type_id == '<?php echo MANUFACTURE_TYPE_RECEIVE_ID; ?>'){
                    tunch_grwt_arr[parseInt(value.purity)] = zero_value - parseFloat(weight);
                    tunch_receive_calculated_wastage_arr[parseInt(value.purity)] = parseFloat(calculated_wastage);
                    tunch_issue_calculated_wastage_arr[parseInt(value.purity)] = 0;
                } else {
                    tunch_grwt_arr[parseInt(value.purity)] = parseFloat(weight);
                    tunch_receive_calculated_wastage_arr[parseInt(value.purity)] = 0;
                    tunch_issue_calculated_wastage_arr[parseInt(value.purity)] = parseFloat(calculated_wastage);
                }
            }
            // End balance_calculation_list
        });
        $('#issue_lineitem_list').html(new_issue_lineitem_html);
        $('#total_issue_weight').html(total_issue_weight.toFixed(3));
        $('#total_issue_less').html(total_issue_less.toFixed(3));
        $('#total_issue_net_wt').html(total_issue_net_wt.toFixed(3));
        $('#total_issue_fine').html(total_issue_fine.toFixed(3));
        $('#total_issue_tunch').html(total_issue_tunch.toFixed(2));
        $('#total_issue_wastage').html(total_issue_wastage.toFixed(3));
        $('#total_issue_calculated_wastage').html(total_issue_calculated_wastage.toFixed(3));
        $('#receive_lineitem_list').html(new_receive_lineitem_html);
        $('#total_receive_weight').html(total_receive_weight.toFixed(3));
        $('#total_receive_less').html(total_receive_less.toFixed(3));
        $('#total_receive_net_wt').html(total_receive_net_wt.toFixed(3));
        $('#total_receive_fine').html(total_receive_fine.toFixed(3));
        $('#total_receive_tunch').html(total_receive_tunch.toFixed(2));
        $('#total_receive_wastage').html(total_receive_wastage.toFixed(3));
        $('#total_receive_calculated_wastage').html(total_receive_calculated_wastage.toFixed(3));
        $('#save_import_data').append('<input type="hidden" name="total_issue_net_wt" id="total_issue_net_wt" value="' + total_issue_net_wt + '" />');
        $('#save_import_data').append('<input type="hidden" name="total_receive_net_wt" id="total_receive_net_wt" value="' + total_receive_net_wt + '" />');
        $('#save_import_data').append('<input type="hidden" name="total_issue_fine" id="total_issue_fine" value="' + total_issue_fine + '" />');
        $('#save_import_data').append('<input type="hidden" name="total_receive_fine" id="total_receive_fine" value="' + total_receive_fine + '" />');
        $('#ajax-loader').hide();
        set_total_weight();
        set_total_net_wt();
        set_total_fine();
        
        var balance_calculation_list = '';
        var total_karigar_receive_wastage = 0;
        var total_karigar_issue_wastage = 0;
        var total_karigar_balance_wastage = 0;
        var total_gross_with_karigar = 0;
        var total_karigar_fine = 0;
        $.each(tunch_arr, function (index, value) {
            var balance_gross = parseFloat(tunch_grwt_arr[value]).toFixed(3);
            var karigar_receive_wastage = parseFloat(tunch_receive_calculated_wastage_arr[value]).toFixed(3);
            var karigar_issue_wastage = parseFloat(tunch_issue_calculated_wastage_arr[value]).toFixed(3);
            var karigar_balance_wastage = parseFloat(karigar_receive_wastage) - parseFloat(karigar_issue_wastage);
            karigar_balance_wastage = karigar_balance_wastage.toFixed(3);
            var gross_with_karigar = parseFloat(balance_gross) - parseFloat(karigar_receive_wastage) + parseFloat(karigar_issue_wastage);
            gross_with_karigar = gross_with_karigar.toFixed(3);
            var karigar_fine = parseFloat(gross_with_karigar) * parseFloat(value) / 100;
            karigar_fine = karigar_fine.toFixed(3);
            balance_calculation_list += '<tr>';
            balance_calculation_list += '<th class="text-right bg-aqua">' + value + '</th>';
            balance_calculation_list += '<td class="text-right bg-aqua">' + balance_gross + '</td>';
            balance_calculation_list += '<td class="text-right bg-yellow">' + round(karigar_receive_wastage, 2).toFixed(3) + '</td>';
            balance_calculation_list += '<td class="text-right bg-yellow">' + round(karigar_issue_wastage, 2).toFixed(3) + '</td>';
            balance_calculation_list += '<td class="text-right bg-yellow">' + round(karigar_balance_wastage, 2).toFixed(3) + '</td>';
            balance_calculation_list += '<td class="text-right bg-aqua">' + round(gross_with_karigar, 2).toFixed(3) + '</td>';
            balance_calculation_list += '<td class="text-right bg-aqua">' + round(karigar_fine, 2).toFixed(3) + '</td>';
            balance_calculation_list += '<td class="text-right bg-yellow"></td>';
            balance_calculation_list += '<td class="text-right bg-yellow"></td>';
            balance_calculation_list += '</tr>';
            
            total_karigar_receive_wastage = parseFloat(total_karigar_receive_wastage) + parseFloat(karigar_receive_wastage);
            total_karigar_issue_wastage = parseFloat(total_karigar_issue_wastage) + parseFloat(karigar_issue_wastage);
            total_karigar_balance_wastage = parseFloat(total_karigar_balance_wastage) + parseFloat(karigar_balance_wastage);
            total_gross_with_karigar = parseFloat(total_gross_with_karigar) + parseFloat(gross_with_karigar);
            total_karigar_fine = parseFloat(total_karigar_fine) + parseFloat(karigar_fine);
        });
        $('#karigar_receive_wastage').html(round(total_karigar_receive_wastage, 2).toFixed(3));
        $('#karigar_issue_wastage').html(round(total_karigar_issue_wastage, 2).toFixed(3));
        $('#karigar_balance_wastage').html(round(total_karigar_balance_wastage, 2).toFixed(3));
        $('#gross_with_karigar').html(round(total_gross_with_karigar, 2).toFixed(3));
        $('#karigar_fine').html(round(total_karigar_fine, 2).toFixed(3));
        $('#balance_calculation_list').html(balance_calculation_list);
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
        $(".delete_ir_item").addClass('hide');
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
        $("#ir_item_delete").val(value.ir_item_delete);
        if(typeof(value.ird_id) !== "undefined" && value.ird_id !== null) {
            $("#ird_id").val(value.ird_id);
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
        $("#wastage").val(value.wastage);
        $("#calculated_wastage").val(value.calculated_wastage);
        $("#ird_date").val(value.ird_date);
        $("#ird_date").datepicker('setDate', value.ird_date);
        $("#ird_remark").val(value.ird_remark);
        $('#total_grwt_sell').val(value.total_grwt_sell);
        if(value.ir_item_delete == 'not_allow'){
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
            if (typeof (value.ird_id) !== "undefined" && value.ird_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.ird_id + '" />');
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
