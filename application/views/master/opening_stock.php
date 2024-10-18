<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Opening Stock
            <?php $isEdit = $this->app_model->have_access_role(OPENING_STOCK_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(OPENING_STOCK_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(OPENING_STOCK_MODULE_ID, "add"); ?>
            <?php if (isset($opening_stock_data->opening_stock_id) && !empty($opening_stock_data->opening_stock_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/opening_stock') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Opening Stock</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-7">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <?php if($isView){ ?>
                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <div class="col-md-3">
                                                <label>Department</label>
                                                <select name="" class="form-control select2" id="filter_department_id"></select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Category</label>
                                                <select name="" class="form-control select2" id="filter_category_id">
                                                    <option value="0"> All </option>
                                                    <?php foreach ($filter_category as $value) { ?>
                                                        <option value="<?= $value->category_id; ?>"><?= $value->category_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Item</label>
                                                <select id="filter_item_id" class="form-control select2">
                                                    <option value="0"> All </option>
                                                    <?php foreach ($filter_items as $value) { ?>
                                                        <option value="<?= $value->item_id; ?>"><?= $value->item_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive">
                                                <table id="opening_stock_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 80px;">Action</th>
                                                            <th>Department</th>
                                                            <th>Category</th>
                                                            <th>Item</th>
                                                            <th>Gr.Wt.</th>
                                                            <th>Less</th>
                                                            <th>Net.Wt.</th>
                                                            <th>Tunch</th>
                                                            <th>Fine</th>
                                                            <th>Design No</th>
                                                            <th>RFID</th>
                                                            <th>Pcs</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($isAdd || $isEdit) { ?>
                <!-- Horizontal Form -->
                <div class="col-md-5">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form class="form-horizontal" id="opening_stock_form"  action="<?= base_url('master/save_opening_stock') ?>" method="post" novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($opening_stock_data->opening_stock_id) && !empty($opening_stock_data->opening_stock_id)) { ?>
                                                <input type="hidden" name="opening_stock_id" class="opening_stock_id" value="<?= $opening_stock_data->opening_stock_id ?>">
                                            <?php } ?>
                                            <label for="department_id">Department<span class="required-sign">&nbsp;*</span></label>
                                            <select name="department_id" id="department_id" class="form-control select2"></select>
                                            <div class="clearfix"></div><br />
                                            <label for="category_id">Category<span class="required-sign">&nbsp;*</span></label>
                                            <select name="category_id" class="form-control category_id" id="category_id"></select>
                                            <div class="clearfix"></div><br />
                                            <label for="item_id">Item<span class="required-sign">&nbsp;*</span></label>
                                            <select name="item_id" class="form-control item_id select2" id="item_id"></select>
                                            <div class="clearfix"></div><br />
                                            <label for="grwt">Gr.Wt.<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="grwt" class="form-control grwt num_only" id="grwt" placeholder="" value="<?= isset($opening_stock_data->grwt) ? $opening_stock_data->grwt : ''; ?>"><br />
                                            <label for="less">Less</label>
                                            <input type="text" name="less" class="form-control less num_only" id="less"  placeholder="" value="<?= isset($opening_stock_data->less) ? $opening_stock_data->less : ''; ?>"><br />
                                            <label for="unit">Net.Wt<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="ntwt" class="form-control net_wt num_only" id="net_wt" placeholder="" value="<?= isset($opening_stock_data->ntwt) ? $opening_stock_data->ntwt : ''; ?>" readonly=""><br />
                                            <label for="tunch">Tunch<span class="required-sign">&nbsp;*</span></label>
                                            <select name="tunch"  id="tunch" class="form-control select2 touch_id">
                                                <option value=""> - Select - </option>
                                                <?php if(isset($touch) && !empty($touch)){ foreach ($touch as $value) { ?>
                                                    <option value="<?= $value->purity; ?>"<?= isset($opening_stock_data->tunch) && $value->purity == $opening_stock_data->tunch ? 'selected="selected"' : ''; ?>><?= $value->purity; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <div class="clearfix"></div><br />
                                            <label for="fine">Fine</label></span>
                                            <input type="text" name="fine" class="form-control fine" id="fine" placeholder="" value="<?= isset($opening_stock_data->fine) ? $opening_stock_data->fine : ''; ?>" readonly><br />
                                            <div class="clearfix"></div>
                                            <label for="design_no">Design No</label></span>
                                            <input type="text" name="design_no" class="form-control" id="design_no" placeholder="" value="<?= isset($opening_stock_data->design_no) ? $opening_stock_data->design_no : ''; ?>"><br />
                                            <div class="clearfix"></div>
                                            <label for="rfid_number">RFID</label></span>
                                            <input type="text" name="rfid_number" class="form-control" id="rfid_number" placeholder="" value="<?= isset($opening_stock_data->rfid_number) ? $opening_stock_data->rfid_number : ''; ?>"><br />
                                            <label for="opening_pcs">Pcs</label></span>
                                            <input type="text" name="opening_pcs" class="form-control" id="opening_pcs" placeholder="" value="<?= isset($opening_stock_data->opening_pcs) ? $opening_stock_data->opening_pcs : ''; ?>"><br />
                                            <div class="clrearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($opening_stock_data->category_id) ? '' : $btn_disable;?>><?= isset($opening_stock_data->category_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                                            <?php if (isset($opening_stock_data->item_stock_id) && !empty($opening_stock_data->item_stock_id)) { ?>
                                            <div class="created_updated_info">
                                                Created by : <?php echo (isset($opening_stock_data->created_by_name)) ? $opening_stock_data->created_by_name : ''; ?>
                                                @ <?php echo (isset($opening_stock_data->created_at)) ? date('d-m-Y h:i A', strtotime($opening_stock_data->created_at)) : ''; ?> <br/>
                                                Updated by : <?php echo (isset($opening_stock_data->updated_by_name)) ? $opening_stock_data->updated_by_name : ''; ?>
                                                @ <?php echo (isset($opening_stock_data->updated_at)) ?date('d-m-Y h:i A', strtotime($opening_stock_data->updated_at)) : '' ?>
                                            </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    var table;
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('#filter_category_id, #filter_item_id').select2();
        initAjaxSelect2($("#filter_department_id"), "<?= base_url('app/department_select2_source') ?>");
        setSelect2Value($("#filter_department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
        $('#tunch').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        <?php if (isset($opening_stock_data->department_id)) { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $opening_stock_data->department_id) ?>");
            $('#department_id').attr('disabled','disabled');
        <?php } ?>
        initAjaxSelect2($("#category_id"), "<?= base_url('app/category_for_gold_and_silver_select2_source') ?>");
    <?php if (isset($opening_stock_data->category_id)) { ?>
            setSelect2Value($("#category_id"), "<?= base_url('app/set_category_select2_val_by_id/' . $opening_stock_data->category_id) ?>");
            $('#category_id').attr('disabled','disabled');
    <?php } ?>
    <?php if (isset($opening_stock_data->item_id)) { ?>
        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/' . $opening_stock_data->item_id) ?>");
        $('#item_id').attr('disabled','disabled');
    <?php } ?>
        <?php if (isset($opening_stock_data->tunch)) { ?>
//            $('#tunch').attr('disabled','disabled');
        <?php } ?>
        
        $(document).on('change', '#filter_category_id', function () {
            var filter_category_id = $('#filter_category_id').val();
            if (filter_category_id != '0' && filter_category_id != null) {
                initAjaxSelect2($("#filter_item_id"), "<?= base_url('app/item_name_from_category_select2_source') ?>/" + filter_category_id);
            } else {
                initAjaxSelect2($("#filter_item_id"), "<?= base_url('app/item_name_select2_source') ?>");
                $('#filter_item_id').val(null).trigger('change');
            }
        });
        $(document).on('change', '#filter_department_id, #filter_category_id, #filter_item_id', function () {
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('change', '#category_id', function (e) {
            $('#item_id').val(null);
            $('#less').removeAttr('readonly');
            var category_id = $('#category_id').val();
            if (category_id != '' && category_id != null) {
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_defult_from_category_select2_source') ?>/" + category_id);
            } else {
                $('#item_id').val(null).trigger('change');
            }
        });

        $(document).on('change', '#item_id', function (e) {
            $('#less').removeAttr('readonly');
            var item_id = $('#item_id').val();
            if (item_id != '' && item_id != null) {
                $.ajax({
                    url: "<?= base_url('master/get_item_less_info') ?>",
                    type: "POST",
                    data: {item_id : item_id},
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['less'] == 0) {
                            $('#less').attr('readonly', true);
                        }
                    },
                });
            }
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

        $(document).bind('keyup change', '#net_wt, #tunch', function () {
            var net_wt = parseFloat($('#net_wt').val()) || 0;
            net_wt = round(net_wt, 2).toFixed(3);
            var tunch = parseFloat($('#tunch').val()) || 0;
            var fine = 0;
            fine = parseFloat(net_wt) * parseFloat(tunch) / 100;
            fine = round(fine, 2).toFixed(3);
            $('#fine').val(fine);
        });
    
        table = $('#opening_stock_table').DataTable({
            "serverSide": true,
            "ordering": true,
            "searching": true,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('master/opening_stock_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.filter_department_id = $('#filter_department_id').val();
                    d.filter_category_id = $('#filter_category_id').val();
                    d.filter_item_id = $('#filter_item_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [4,5,6,7,8,9,10,11],
            }],
            "scrollY": 400,
            "scrollX": '100%',
            "scroller": {
                "loadingIndicator": true
            },
            "sScrollX": "100%",
            "sScrollXInner": "100%"
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#opening_stock_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#opening_stock_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department.', false);
                $("#department_id").select2('open');
                return false;
            }
            if ($.trim($("#category_id").val()) == '') {
                show_notify('Please Select Category.', false);
                $("#category_id").select2('open');
                return false;
            }
            if ($.trim($("#item_id").val()) == '') {
                show_notify('Please Select Item.', false);
                $("#item_id").select2('open');
                return false;
            }
            if ($.trim($("#grwt").val()) == '') {
                show_notify('Please Enter Gr.WT.', false);
                $("#grwt").focus();
                return false;
            }
            if ($.trim($("#tunch").val()) == '') {
                show_notify('Please Select Tunch.', false);
                $("#tunch").select2('open');
                return false;
            }
            
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_opening_stock') ?>",
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
                        $('input').val('');
                        $('#department_id').find('option').remove().end().val('whatever');
                        $('#category_id').find('option').remove().end().val('whatever');
                        $('#item_id').find('option').remove().end().val('whatever');
                        $("#tunch").val(null).trigger("change");
                        //$('#tunch').find('option').remove().end().val('whatever');
                        table.draw();
                        show_notify('Opening Stock Added Successfully!', true);
                    }else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/opening_stock') ?>";
                    }
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    return false;
                },
            });
            return false;
        });
        
        $(document).on("click", ".delete_button", function () {
        if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Opening Stock Deleted Successfully!', true);
                        }
                    }
                });
            }
        });

    });
</script>
