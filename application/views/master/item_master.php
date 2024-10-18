<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <form class="form-horizontal" action="<?= base_url('master/save_item_master') ?>" method="post" id="item_master_form" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($item_master_data->item_id) && !empty($item_master_data->item_id)) { ?>
            <input type="hidden" name="item_id" class="item_id" value="<?= $item_master_data->item_id ?>">
        <?php } ?>
        <section class="content-header">
            <h1>
                Item Master
                <?php $isEdit = $this->app_model->have_access_role(ITEM_MASTER_MODULE_ID, "edit");
                $isAdd = $this->app_model->have_access_role(ITEM_MASTER_MODULE_ID, "add"); 
                $isView = $this->app_model->have_access_role(ITEM_MASTER_MODULE_ID, "view"); ?>
                <?php if (isset($item_master_data->item_id) && !empty($item_master_data->item_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($item_master_data->item_id) ? '' : $btn_disable;?>><?= isset($item_master_data->item_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('master/item_master_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Item Master List</a>
                <?php } ?>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <!-- Horizontal Form -->
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <?php if($isAdd || $isEdit) { ?>
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <label for="category_id">Category Name<span class="required-sign">&nbsp;*</span></label>
                                                <select name="category_id" id="category_id" class="form-control "></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="item_name">Item Name<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="item_name" id="item_name" class="form-control" autofocus value="<?= (isset($item_master_data->item_name)) ? $item_master_data->item_name : ''; ?>"><br />
                                            </div>
                                            <div class="clrearfix"></div>
                                            <div class="col-md-6">
                                                <label for="short_item">Short Item Name</label>
                                                <input type="text" name="short_item" id="short_item" class="form-control"  value="<?= (isset($item_master_data->short_item)) ? $item_master_data->short_item : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="die_no">Die No</label>
                                                <input type="text" name="die_no" id="die_no" class="form-control"  value="<?= (isset($item_master_data->die_no)) ? $item_master_data->die_no : ''; ?>"><br />
                                            </div>
                                            <div class="clrearfix"></div>
                                            <div class="col-md-6">
                                                <label for="design_no">Design No</label>
                                                <input type="text" name="design_no" id="design_no" class="form-control"  value="<?= (isset($item_master_data->design_no)) ? $item_master_data->design_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="min_order_qty">Min Order Qty<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="min_order_qty" id="min_order_qty" class="form-control num_only"  value="<?= (isset($item_master_data->min_order_qty)) ? $item_master_data->min_order_qty : '1'; ?>"><br />
                                            </div>
                                            <div class="clrearfix"></div>
                                            <div class="col-md-6">
                                                <label for="default_wastage">Default Wastage <span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="default_wastage" id="default_wastage" class="form-control num_only"  value="<?= (isset($item_master_data->default_wastage)) ? $item_master_data->default_wastage : '0'; ?>"><br />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="less">Less</label><br />
                                                <span> Yes  &nbsp;</span> <input type="radio" name="less" class="" id="less1" value="1"<?= (isset($item_master_data->less) && $item_master_data->less == '1') ? 'checked' : ''; ?>>
                                                &nbsp;<span> No &nbsp; </span><input type="radio" name="less" class="" id="less2" value="0"<?= (isset($item_master_data->less) && $item_master_data->less == '0') ? 'checked' : ''; ?>><br />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="display_item_in">Display Item In</label><br />
                                                 <!--// if (isset($display_items->display_item_in) && !empty($item_master_data->display_item_in)) {-->
                                                <span> Purchase &nbsp; </span> <input type="checkbox" class="icheckbox_minimal-green" name="display_item_in[]" id="display_item_in" value="Purchase" <?php if(isset($display_items)) { if(in_array('Purchase', $display_items)) { echo 'checked'; } } else { echo 'checked'; } ?> >&nbsp;
                                                &nbsp;<span> Sell &nbsp; </span><input type="checkbox" name="display_item_in[]" class="icheckbox_minimal-green" id="display_item_in" value="Sell" <?php if(isset($display_items)) { if(in_array('Sell', $display_items)) { echo 'checked'; } } else { echo 'checked'; } ?> >&nbsp;
                                                &nbsp;<span> Exchange &nbsp; </span><input type="checkbox" name="display_item_in[]" class="icheckbox_minimal-green" id="display_item_in" value="Exchange" <?php if(isset($display_items)) { if(in_array('Exchange', $display_items)) { echo 'checked'; } } else { echo 'checked'; } ?> ><br />
                                                <?php //  } ?>
                                            </div><br />
                                            <div class="clearfix"></div>
                                            <div class="col-md-6">
                                                <label for="st_default_wastage">Stock  Transfer Default Wastage <span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="st_default_wastage" id="st_default_wastage" class="form-control num_only"  value="<?= (isset($item_master_data->st_default_wastage)) ? $item_master_data->st_default_wastage : '0'; ?>"><br />
                                            </div>
                                            <div class="col-md-3">
                                                <label>Image</label>
                                                <input type="file" name="image" id="image" class="from-control" accept="image/*"><br />
                                                <div class="col-md-6">
                                                    <?php if(!empty($item_master_data->image)) { ?>
                                                        <img src="<?php echo base_url(); ?><?= (isset($item_master_data->image)) ? $item_master_data->image : ''; ?>" alt="Image" style="width:50%; height:50%;"/><br />
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <span> Metal Issue Receive&nbsp; </span> <input type="checkbox" class="" name="metal_payment_receipt" id="metal_payment_receipt" value="1" <?= (isset($item_master_data->metal_payment_receipt)) ? $item_master_data->metal_payment_receipt == '1' ? 'checked' : ''  : '' ?>>&nbsp;
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-6">
                                                <label for="stock_method">Stock Method</label>
                                                <select name="stock_method" id="stock_method" class="form-control " <?php echo isset($item_master_data->item_exist) && $item_master_data->item_exist == '1' ? 'disabled' : ''; ?>>
                                                    <option value="1" <?php echo isset($item_master_data->stock_method) && $item_master_data->stock_method == '1' ? 'Selected' : ''; ?>>Default</option>
                                                    <option value="2" <?php echo isset($item_master_data->stock_method) && $item_master_data->stock_method == '2' ? 'Selected' : ''; ?>>Item Wise</option>
                                                    <option value="3" <?php echo isset($item_master_data->stock_method) && $item_master_data->stock_method == '3' ? 'Selected' : ''; ?>>Combine</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="sequence_no">Sequence No</label>
                                                <input type="text" name="sequence_no" id="sequence_no" class="form-control num_only"  value="<?= (isset($item_master_data->sequence_no)) ? $item_master_data->sequence_no : ''; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rate_on">Rate On</label>
                                                <input type="text" name="rate_on" id="rate_on" class="form-control num_only"  value="<?= (isset($item_master_data->rate_on)) ? $item_master_data->rate_on : '1'; ?>"><br />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rate_of">Rate Of</label>
                                                <select name="rate_of" id="rate_of" class="form-control">
                                                    <option value="1" <?php echo isset($item_master_data->rate_of) && $item_master_data->rate_of == '1' ? 'Selected' : ''; ?>>Labour</option>
                                                    <option value="2" <?php echo isset($item_master_data->rate_of) && $item_master_data->rate_of == '2' ? 'Selected' : ''; ?>>Item</option>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div><br/>
                                            <?php if (isset($item_master_data->item_id) && !empty($item_master_data->item_id)) { ?>
                                            <div class="created_updated_info" style="margin-left: 15px;">
                                                Created by : <?php echo (isset($item_master_data->created_by_name)) ? $item_master_data->created_by_name :'';?>
                                                @ <?php echo (isset($item_master_data->created_at)) ? date('d-m-Y h:i A', strtotime($item_master_data->created_at)) :'';?><br/>
                                                Updated by : <?php echo (isset($item_master_data->updated_by_name)) ? $item_master_data->updated_by_name :'';?>
                                                @ <?php echo (isset($item_master_data->updated_at)) ? date('d-m-Y h:i A', strtotime($item_master_data->updated_at)) :'';?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    $(document).ready(function () {
        
        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
        });
//        
        initAjaxSelect2($("#category_id"), "<?= base_url('app/category_select2_source') ?>");
    <?php if (isset($item_master_data->category_id)) { ?>
            setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/' . $item_master_data->category_id) ?>");
    <?php } ?>
        
        <?php if(isset($item_master_data->less) && !empty($item_master_data->less)){ } else { ?>
            $('#less2').attr('checked','checked');
        <?php } ?>
            
        $('#stock_method').select2();
        $('#rate_of').select2();
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#item_master_form").submit();
                return false;
            }
        });
        
        $(document).on('change', '#category_id', function(){
            var category_id = $('#category_id').val();
            <?php if (isset($item_master_data->category_id)) { ?>
            <?php } else { ?>
                $("#stock_method option[value='2']").removeAttr('disabled', 'disabled');
                $('#stock_method').select2();
                $("#stock_method").val('1').trigger("change");
            <?php } ?>
            if(category_id != '' && category_id != null){
                $.ajax({
                    url : "<?= base_url('master/get_category_group') ?>/" + category_id,
                    type: "GET",
                    data: "",
                    success: function(response){
                        var json = $.parseJSON(response);
                        if(json.category_group_id == '3'){
                            <?php if (isset($item_master_data->category_id)) { ?>
                                if($("#stock_method").val() == '2'){
                                    $("#stock_method").val('1').trigger("change");
                                }
                            <?php } ?>
                            $("#stock_method option[value='2']").attr('disabled', 'disabled');
                            $('#stock_method').select2();
                        } else {
                            $("#stock_method option[value='2']").removeAttr('disabled', 'disabled');
                            $('#stock_method').select2();
                        }
                    }
                });
            }
        });
        
        <?php if (isset($item_master_data->category_id)) { ?>
            $('#category_id').change();
        <?php } ?>
        
        $(document).on('submit', '#item_master_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#category_id").val()) == '') {
                show_notify('Please Select Category Name.', false);
                $("#category_id").select2('open');
                return false;
            }
            if ($.trim($("#item_name").val()) == '') {
                show_notify('Please Enter Item Name.', false);
                $("#item_name").focus();
                return false;
            }
            if ($.trim($("#min_order_qty").val()) == '') {
                show_notify('Please Enter Min Order Qty.', false);
                $("#min_order_qty").focus();
                return false;
            }
            if ($.trim($("#default_wastage").val()) == '') {
                show_notify('Please Enter Default Wastage.', false);
                $("#default_wastage").focus();
                return false;
            }
            if ($.trim($("#st_default_wastage").val()) == '') {
                show_notify('Please Enter Stock Transfer Default Wastage.', false);
                $("#st_default_wastage").focus();
                return false;
            }
            if ($.trim($("#rate_on").val()) <= 0) {
                show_notify("Please Enter Rate Greater than 0!", false);
                $("#rate_on").focus();
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_item_master') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);

                    if (json['error'] == 'Exist') {
                        show_notify(json['error_exist'], false);
                    } else if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('master/item_master_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/item_master_list') ?>";
                    }
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    return false;
                },

            });
            return false;
        });
        
        $('input[type="radio"]').iCheck({
            radioClass: 'iradio_flat-blue'
        });

    });
</script>
