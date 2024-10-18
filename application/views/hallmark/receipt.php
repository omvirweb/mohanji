<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('hallmark/save_receipt') ?>" method="post" id="save_receipt" novalidate enctype="multipart/form-data">
     
        <section class="content-header">
            <h1>
                Receipt
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn">Save [ Ctrl +S ]</button>
                <?php
                $isView = $this->app_model->have_access_role(HALLMARK_RECEIPT_MODULE_ID, "view"); ?>
                <?php if($isView){ ?>
                <a href="<?= base_url('hallmark/receipt_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Receipt List</a>
                <?php } ?>
                <a href="javascript:void(0);" class="btn btn-primary btn-sm pull-right" id="btn_receipt_popup"  style="margin: 5px;">Receipt Popup</a>
                
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="receipt_no">Receipt / Lot No</label>
                                        <input type="text" name="" id="receipt_no" class="form-control" readonly="">
                                        <br/>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-3">
                                        <label for="receipt_date">Date<span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" name="receipt_date" id="receipt_date" class="form-control input-datepicker" value="<?= (isset($receipt_data->receipt_date)) ? date('d-m-Y', strtotime($receipt_data->receipt_date)) : date('d-m-Y'); ?>">
                                        <br/>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="receipt_time">Time</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="receipt_time" id="receipt_time" class="form-control out_time input-small"  value="<?= (isset($receipt_data->receipt_time)) ? date('h:i A', strtotime($receipt_data->receipt_time)) : date('h:i A'); ?>" />
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                        </div>
                                        <br/>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_date">Delivery Date</label>
                                        <input type="text" name="delivery_date" id="delivery_date" class="form-control input-datepicker" value="<?= (isset($receipt_data->delivery_date)) ? date('d-m-Y', strtotime($receipt_data->delivery_date)) : ''; ?>">
                                        <br/>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="delivery_receipt_time">Delivery Time</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="delivery_time" id="delivery_time" class="form-control out_time input-small" value="<?= (isset($receipt_data->delivery_time)) ? date('h:i A', strtotime($receipt_data->delivery_date)) : date('h:i A'); ?>" />
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                        </div>
                                        <br/>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-3">
                                        <label for="metal">Metal<span class="required-sign">&nbsp;*</span></label>
                                        <select name="metal_id" id="metal_id" class="form-control">
                                            <option value="1" <?= isset($receipt_data->metal_id) && $receipt_data->metal_id == 1 ? 'selected="selected"' : ''; ?>>Gold</option>
                                            <option value="2" <?= isset($receipt_data->metal_id) && $receipt_data->metal_id == 2 ? 'selected="selected"' : ''; ?>>Silver</option>
                                        </select>
                                        <br/>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="party_id">Party<span class="required-sign">&nbsp;*</span></label>
                                        <select name="party_id" id="party_id" class="form-control select2"></select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="licence_no">Licence No</label>
                                        <input type="text" name="licence_no" id="licence_no" class="form-control">
                                        <br/>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="line_item_form item_fields_div">
                                        <h4 class="col-md-12">
                                            Line Item
                                        </h4>
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <?php if(isset($receipt_data)){ ?>
                                            <input type="hidden" name="line_items_data[rd_id]" id="lineitem_id" />
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <label for="article_id">Article<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[article_id]" id="article_id" class="form-control select2"></select><br/>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="receipt_weight">Receipt Wt.<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[receipt_weight]" class="form-control num_only" id="receipt_weight" placeholder="" value=""><br/>
                                        </div>
                                        <div class="col-md-2">
                                            <?php if ($this->app_model->have_access_role(TUNCH_MODULE_ID, "add")) { ?>
                                                <a href="javascript:void(0);" data-href="<?= base_url('master/tunch'); ?>" class="btn btn-xs btn-primary pull-right open_popup"><i class="fa fa-plus"></i></a>
                                            <?php } ?>
                                            <label for="purity">Purity<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[purity]" id="purity" class="form-control select2"></select><br/>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="box_no">Box No<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[box_no]" class="form-control num_only" id="box_no" placeholder="" value=""><br/>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="pcs">Pcs<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[pcs]" class="form-control num_only" id="pcs" placeholder="" value=""><br/>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp;</label>
                                            <input type="button" id="add_lineitem" class="btn btn-info btn-sm pull-right add_lineitem" value="Add" style="margin-top: 21px;"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                        <div class="col-sm-12">
                                            <table style="" class="table custom-table item-table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="70px">Action</th>
                                                        <th width="300px">Article</th>
                                                        <th class="text-right" width="100px">Receipt Wt.</th>
                                                        <th class="text-right" width="100px">Purity</th>
                                                        <th class="text-right" width="100px">Box No</th>
                                                        <th class="text-right" width="100px">Pcs</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lineitem_list"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>Total:</th>
                                                        <th class="text-right" id="total_receipt_weight"></th>
                                                        <th class="text-right" id="total_purity"></th>
                                                        <th class="text-right" id="total_box_no"></th>
                                                        <th class="text-right" id="total_pcs"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
<div id="receipt_popup" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        
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
    
    var first_time_edit_mode = 1;
        var on_save_add_edit_item = 0;
        var edit_lineitem_inc = 0;
        var lineitem_objectdata = [];
        var receipt_index = '';
        <?php if (isset($receipt_detail)) { ?>
            var li_lineitem_objectdata = [<?php echo $receipt_detail; ?>];
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
        $('#receipt_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            endDate: "today",
            maxDate: 0,
        });
        $('#delivery_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
        });
        
        $('#delivery_time').timepicker();
        $('#receipt_time').timepicker();
        $('#transaction_id').select2({width:'100%'});
        $('#party_code').select2({width:'100%'});
        
        initAjaxSelect2($("#party_id"), "<?= base_url('app/party_name_with_number_select2_source') ?>");
        initAjaxSelect2($("#article_id"), "<?= base_url('app/item_name_select2_source') ?>");
        initAjaxSelect2($("#purity"), "<?= base_url('app/touch_xrf_select2_source') ?>");
        

        $('#add_lineitem').on('click', function () {
            var article_id = $("#article_id").val();
            
            if (article_id == '' || article_id == null) {
                $("#article_id").select2('open');
                show_notify("Please select Article!", false);
                return false;
            }
            if ($.trim($("#receipt_weight").val()) == '') {
                $("#receipt_weight").focus();
                show_notify('Receipt Weight is required!', false);
                return false;
            }
            if ($.trim($("#purity").val()) == '') {
                $("#purity").focus();
                show_notify('Purity is required!', false);
                return false;
            }
            if ($.trim($("#box_no").val()) == '') {
                $("#box_no").focus();
                show_notify('Box No is required!', false);
                return false;
            }
            if ($.trim($("#pcs").val()) == '') {
                $("#pcs").focus();
                show_notify('Pcs is required!', false);
                return false;
            }
            save_lineitem();
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#save_receipt").submit();
                return false;
            }
        });
        
        $(document).on('submit', '#save_receipt', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#receipt_date").val()) == '') {
                show_notify('Please Enter Receipt Date.', false);
                $("#receipt_date").focus();
                return false;
            }
            if ($.trim($("#party_id").val()) == '') {
                show_notify('Please Select Party.', false);
                $("#party_id").select2('open');
                return false;
            }
            if (lineitem_objectdata == '') {
                show_notify("Please Add Item.", false);
                return false;
            }
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            $.ajax({
                url: "<?= base_url('hallmark/save_receipt') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('hallmark/receipt_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('hallmark/receipt_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });
        
        $(document).on('click',"#btn_receipt_popup",function(){
            $('#receipt_popup').modal('show');
        });
    });
    
    function save_lineitem(){
        $("#add_lineitem").attr('disabled', 'disabled');
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
        $('textarea').each(function (e) {
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
//                console.log(lineitem_objectdata);
            $.each(lineitem_objectdata, function (index, value) {
                $('input[name^="line_items_data"]').each(function (index) {
                    keys = $(this).attr('name');
                    keys = keys.replace("line_items_data[", "");
                    keys = keys.replace("]", "");
                });
            });
        });
        var article_name = $('#article_id option:selected').html();
        lineitem['article_name'] = article_name;
        var rd_id = $('#rd_id').val();
        lineitem['rd_id'] = rd_id;
        $('#rd_id').val('');
        lineitem['receipt_weight'] = round(lineitem['receipt_weight'], 2).toFixed(3);
        lineitem['purity'] = $('#purity').val();
        lineitem['purity_name'] = round($('#purity option:selected').html(), 2).toFixed(3);
        var new_lineitem = JSON.parse(JSON.stringify(lineitem));

        var line_items_index = $("#line_items_index").val();
        if (line_items_index != '') {
            lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
        } else {
            lineitem_objectdata.push(new_lineitem);
        } 
        display_lineitem_html(lineitem_objectdata);
        $('#lineitem_id').val('');
        $("#article_id").val(null).trigger("change");
        $("#receipt_weight").val('');
        $("#purity").val(null).trigger("change");
        $("#box_no").val('');
        $("#pcs").val('');
        $("#line_items_index").val('');
        if (on_save_add_edit_item == 1) {
            on_save_add_edit_item == 0;
            $('#save_receipt').submit();
        }
        edit_lineitem_inc = 0;
        $("#add_lineitem").removeAttr('disabled', 'disabled');
    }
    
    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        total_receipt_weight = 0;
        total_purity = 0;
        total_box_no = 0;
        total_pcs = 0;
        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';   
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_j_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' + lineitem_edit_btn + lineitem_delete_btn + '</td>';
                row_html += '<td class="">' + value.article_name + '</td>';
                row_html += '<td class="text-right">' + round(value.receipt_weight, 2).toFixed(3) + '</td>';
                row_html += '<td class="text-right">' + round(value.purity_name, 2).toFixed(3) + '</td>';
                row_html += '<td class="text-right">' + value.box_no + '</td>';
                row_html += '<td class="text-right">' + value.pcs + '</td>';
            new_lineitem_html += row_html;
            total_receipt_weight = parseFloat(total_receipt_weight) + parseFloat(value.receipt_weight);
            total_purity = parseFloat(total_purity) + parseFloat(value.purity_name);
            total_box_no = parseFloat(total_box_no) + parseFloat(value.box_no);
            total_pcs = parseFloat(total_pcs) + parseFloat(value.pcs);
        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#total_receipt_weight').html(round(total_receipt_weight, 2).toFixed(3));
        $('#total_purity').html('');
        $('#total_box_no').html(total_box_no);
        $('#total_pcs').html(total_pcs);
        $('#ajax-loader').hide();
    }
    
    function edit_lineitem(index) {
//        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_j_item").addClass('hide');
        receipt_index = index;
        if (edit_lineitem_inc == 0) {
            edit_lineitem_inc = 1;
            $(".add_lineitem").removeAttr("disabled");
        }
        var value = lineitem_objectdata[index];

        var rd_id = value.rd_id;
        $('#rd_id').val(rd_id);
        $("#line_items_index").val(index);
        if(typeof(value.id) != "undefined" && value.id !== null) {
                $("#lineitem_id").val(value.id);
        }
        $("#article_id").val(null).trigger("change");
        setSelect2Value($("#article_id"), "<?= base_url('app/set_item_name_select2_val_by_id/') ?>" + value.article_id);
        $("#receipt_weight").val(value.receipt_weight);
        setSelect2Value($("#purity"), "<?= base_url('app/set_touch_select2_val_by_id/') ?>" + value.purity);
        $("#box_no").val(value.box_no);
        $("#pcs").val(value.pcs);
        $('#ajax-loader').hide();
    }
    
    function remove_lineitem(index) {
        if (confirm('Are you sure ?')) {
            value = lineitem_objectdata[index];
            var rd_id = value.rd_id;
            var deleted_rd_ids = $('#deleted_rd_ids').val();
            if(deleted_rd_ids != ''){
                deleted_rd_ids += ', '+rd_id;
                $('#deleted_rd_ids').val(deleted_rd_ids);
            } else {
                $('#deleted_rd_ids').val(rd_id);
            }
            if (typeof (value.lineitem_id) != "undefined" && value.lineitem_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.lineitem_id + '" />');
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
