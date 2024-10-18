<?php $this->load->view('success_false_notify'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Stock Status</h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <div class="col-md-2">
                                            <label>Department</label>
                                            <select name="" class="form-control select2" id="department_id"></select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Category</label>
                                            <select name="" class="form-control select2" id="category_id">
                                                <option value="0"> All </option>
                                                <?php if(isset($category) && !empty($category)){ foreach ($category as $value) { ?>
                                                    <option value="<?= $value->category_id; ?>"><?= $value->category_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Item</label>
                                            <select id="item_id" class="form-control select2">
                                                <option value="0"> All </option>
                                                <?php if(isset($items) && !empty($items)){ foreach ($items as $value) { ?>
                                                    <option value="<?= $value->item_id; ?>"><?= $value->item_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Tunch</label>
                                            <select id="tunch" class="form-control select2">
                                                <option value="0"> All </option>
                                                <?php if(isset($carat) && !empty($carat)){ foreach ($carat as $value) { ?>
                                                    <option value="<?= $value->purity; ?>"><?= $value->purity; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
<!--                                        <div class="col-md-2">
                                            <label>From Date</label>
                                            <input type="text" name="stock_status_upto" id="datepicker1" class="form-control" value="<?php echo date("d-m-Y");?>">
                                        </div>-->
                                        <div class="col-md-2">
                                            <label><input type="checkbox" name="in_stock" id="in_stock" checked > In stock</label><br />
                                            <label><input type="checkbox" name="item_wise" id="item_wise" > Item Wise</label><br />
                                            <label><input type="checkbox" name="include_wstg" id="include_wstg" > Include Wastage</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></label><br />
                                            <label>Gold Rate : <?php echo $gold_rate; ?></label><br />
                                            <label>Silver Rate : <?php echo $silver_rate; ?></label><br />
                                        </div><div class="clearfix"></div>
                                        <div class="col-md-2">
                                            <label>RFID</label>
                                            <select name="rfid_filter" class="form-control select2" id="rfid_filter">
                                                <option value="0"> All </option>
                                                <option value="1"> Only RFID Stock </option>
                                                <option value="2"> Without RFID Stock </option>
                                            </select>
                                        </div>
                                        <table class="table row-border table-bordered table-striped" style="width:100%" id="stock_status_table">
                                            <thead>
                                                <tr>
                                                    <!--<th>Department</th>-->
                                                    <th>Category</th>
                                                    <th>Item Name</th>
                                                    <th>Gr.Wt.</th>
                                                    <th>RFID Wt</th>
                                                    <th>Loose Stock</th>
                                                    <th width="120"></th>
                                                    <th>Less</th>
                                                    <th>Net.Wt.</th>
                                                    <th>Tunch</th>
                                                    <th>Gold</th>
                                                    <th>Silver</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="stock_adjust_model" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-6">
                    <h4 class="modal-title" id="myModalLabel">Stock Adjust</h4>
                </div>
                <div class="col-md-6">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </div>
            <div class="modal-body edit-content">
                <div class="row stock_adjust_div">
                    <div class="col-md-12"><span id="prev_stock"></span></div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <label for="after_adjust_grwt"><span id="category_group"></span> Grwt<span class="required-sign">&nbsp;*</span></label>
                        <input type="text" name="after_adjust_grwt" id="after_adjust_grwt" class="form-control num_only" required="">
                        <input type="hidden" name="adjust_grwt" id="adjust_grwt" value="0">
                    </div>
                    <div class="col-md-3">
                        <label for="after_adjust_less">Less<span class="required-sign">&nbsp;*</span></label>
                        <input type="text" name="after_adjust_less" id="after_adjust_less" class="form-control num_only" required="">
                        <input type="hidden" name="adjust_less" id="adjust_less" value="0">
                    </div>
                    <div class="col-md-6">
                        <label><input type="checkbox" name="adjust_forcefully" id="adjust_forcefully" > Force Adjust Stock Even If Other User is Entering Data For This Item</label>
                    </div>
                    <div class="clearfix"></div><br />
                    <div class="col-md-12"><span id="adjust_stock"></span></div>
                    <div class="clearfix"></div><br />
                    <div class="col-md-12"><span id="new_stock"></span></div>
                </div>
            </div>
            <div class="clearfix"></div><br />
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="stock_adjust_button" >Adjust</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div id="rfid_model" class="modal fade rfid_model" tabindex="-1" role="dialog" aria-labelledby="rfid_modelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <div class="col-md-6">
                    <h4 class="modal-title" id="rfid_modelLabel">RFID / Barcode</h4>
                </div>
                <div class="col-md-6">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </div>
            <div class="modal-body edit-content">
                <form class="form-horizontal" method="post" id="create_rfid_form" novalidate enctype="multipart/form-data">
                    <input type="hidden" name="item_stock_rfid_id" id="item_stock_rfid_id">
                    <input type="hidden" name="rfid_item_stock_id" id="rfid_item_stock_id">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="rfid_category">Category</label>
                            <input type="text" name="rfid_category" id="rfid_category" class="form-control" readonly="">
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_item_name">Item Name</label>
                            <input type="text" name="rfid_item_name" id="rfid_item_name" class="form-control" readonly="">
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_item_cur_stock">Current Stock</label>
                            <input type="text" name="rfid_item_cur_stock" id="rfid_item_cur_stock" class="form-control" readonly="">
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_tunch">Tunch</label>
                            <input type="text" name="rfid_tunch" id="rfid_tunch" class="form-control" readonly="">
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <label for="rfid_stock">RFID Stock</label>
                            <input type="text" name="rfid_stock" id="rfid_stock" class="form-control" value="25" readonly="">
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_pcs">RFID Pcs</label>
                            <input type="text" name="rfid_pcs" id="rfid_pcs" class="form-control" value="3" readonly="">
                        </div>
                        <div class="col-md-12">
                            <h4>Add RFID</h4>
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_grwt">Gr. Wt. <span class="required-sign">&nbsp;*</span></label>
                            <input type="text" name="rfid_grwt" id="rfid_grwt" class="form-control num_only">
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_less">Less -</label>
                            <input type="text" name="rfid_less" id="rfid_less" class="form-control num_only">
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_add">Add +</label>
                            <input type="text" name="rfid_add" id="rfid_add" class="form-control num_only">
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_size">Size</label>
                            <input type="text" name="rfid_size" id="rfid_size" class="form-control">
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <label for="rfid_charges">Charges</label>
                            <input type="text" name="rfid_charges" id="rfid_charges" class="form-control num_only">
                        </div>
                        <div class="col-md-3">
                            <label for="rfid_ad_id">Charges For</label>
                            <select name="rfid_ad_id" class="form-control rfid_ad_id" id="rfid_ad_id"></select>
                        </div>
                        <div class="col-md-3">
                            <label for="real_rfid">Real RFID <span class="required-sign">&nbsp;*</span></label>
                            <input type="text" name="real_rfid" id="real_rfid" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <br/>
                            <?php if ($this->applib->have_access_role(STOCK_STATUS_MODULE_ID, "rfid_add")) { ?>
                                <button type="submit" class="btn btn-primary" id="create_rfid_btn" class="create_rfid_btn">Create RFID / Barcode</button>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div><br />
                        <div class="col-md-12">
                            <table class="table row-border table-bordered table-striped" style="width:100%" id="created_rfid_table">
                                <thead>
                                    <tr>
                                        <th width="100px">Action</th>
                                        <th width="40px">Sr. no</th>
                                        <th>RFID ID</th>
                                        <th>RFID</th>
                                        <th>Gr.Wt.</th>
                                        <th>Less</th>
                                        <th>Add</th>
                                        <th>NtWt</th>
                                        <th>Fine</th>
                                        <th>Charges</th>
                                        <th>Charges For</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var table;
    var created_rfid_table;
    var selected_rows = [];
    var adjust_type = '';
    var category_group_id = '';
    var department_id = '';
    var category_id = '';
    var item_id = '';
    var adjust_grwt = 0;
    var adjust_less = 0;
    var adjust_ntwt = 0;
    var adjust_tunch = 0;
    var adjust_wstg = 0;
    var adjust_fine = 0;
    var new_grwt = 0;
    var new_less = 0;
    var new_ntwt = 0;
    var new_tunch = 0;
    var new_wstg = 0;
    var new_fine = 0;
    var old_gold_fine_val = 0;
    var old_silver_fine_val = 0;
    var old_amount_val = 0;
    var zero_value = 0;
    var item_stock_id = 0;
    var edit_lineitem_inc = 0;
    
    $(document).ready(function () {
        $("#ajax-loader").show();
        $('.select2').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
        
        initAjaxSelect2($("#rfid_ad_id"), "<?= base_url('app/ad_name_select2_source/') ?>");
        
        $(document).on('click', '.item_stock_row', function () {
            department_id = $(this).attr('data-department_id');
            category_id = $(this).attr('data-category_id');
            item_id = $(this).attr('data-item_id');
            category_group_id = $(this).attr('data-category_group_id');
            var grwt = $(this).attr('data-grwt');
            var less = $(this).attr('data-less');
            var less_allow = $(this).attr('data-less_allow');
            var ntwt = $(this).attr('data-ntwt');
            var tunch = $(this).attr('data-tunch');
            var wstg = $(this).attr('data-wstg');
            var fine = $(this).attr('data-fine');
            item_stock_id = $(this).attr('data-item_stock_id');
            var category_group = '';
            if(category_group_id == 1){
                category_group = 'Gold';
            } else if (category_group_id == 2){
                category_group = 'Silver';
            } else {
                category_group = 'Other';
            }
            $('#prev_stock').html("<b>Current Stock :</b> <table class='table'><tr><th>Grwt</th><th>Less</th><th>NtWt</th><th>Tunch</th><th>Fine</th></tr><tr><td>" + grwt + "</td><td>" + less + "</td><td>" + ntwt + "</td><td>" + tunch + "</td><td>" + fine + "</td></tr></table>");
            $('#category_group').html(category_group);
            $('#after_adjust_grwt').val(grwt);
            $('#after_adjust_grwt').attr('data-old_grwt', grwt);
            $('#after_adjust_grwt').attr('data-category_group_id', category_group_id);
            $('#after_adjust_grwt').attr('data-tunch', tunch);
            $('#after_adjust_grwt').attr('data-wstg', wstg);
            $('#after_adjust_less').val(less);
            if(less_allow != 1){
                $('#after_adjust_less').attr('disabled', 'disabled');
            }
            $('#after_adjust_less').attr('data-old_less', less);
            $('#stock_adjust_model').modal('show');
            get_bill_balance(<?php echo ADJUST_EXPENSE_ACCOUNT_ID; ?>);
        });
        $(document).on('click', '.item_rfid_detail', function () {
            var category_name = $(this).attr('data-category_name');
            var item_id = $(this).attr('data-item_id');
            var item_name = $(this).attr('data-item_name');
            var grwt = $(this).attr('data-grwt');
            var tunch = $(this).attr('data-tunch');
            var item_stock_id = $(this).attr('data-item_stock_id');
            $("#rfid_category").val(category_name);
            $("#rfid_item_name").val(item_name);
            $("#rfid_item_cur_stock").val(grwt);
            $("#rfid_tunch").val(tunch);
            $("#rfid_item_stock_id").val(item_stock_id);
            $('#rfid_model').modal('show');
            
            $.ajax({
                url: "<?php echo base_url('sell/get_item_data'); ?>/" + item_id,
                type: "GET",
                async: false,
                data: "",
                success: function(response){
                    var json = $.parseJSON(response);
                    if (json.less == 0) {
                        $('#rfid_less').attr('readonly', 'readonly');
                        $('#rfid_add').attr('readonly', 'readonly');
                    }
                }
            });
            
            created_rfid_table.draw();
        });
        $('#rfid_model').on('shown.bs.modal',function(){
            $("#rfid_grwt").focus();
			$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
        $('#rfid_model').on('hidden.bs.modal',function(){
			$("#rfid_grwt").val('');
            $("#rfid_less").val('');
            $("#rfid_add").val('');
            $("#real_rfid").val('');
            $("#rfid_size").val('');
            $("#rfid_charges").val('');
            $("#rfid_ad_id").val(null).trigger("change");
            $('#rfid_less').removeAttr('readonly', 'readonly');
            $('#rfid_add').removeAttr('readonly', 'readonly');
        });
        
        $(document).on('keyup change', '#after_adjust_grwt, #after_adjust_less', function () {
            var after_adjust_grwt = $('#after_adjust_grwt').val() || 0;
            after_adjust_grwt = parseFloat(after_adjust_grwt);
            after_adjust_grwt = after_adjust_grwt.toFixed(3);
            var old_grwt = $('#after_adjust_grwt').attr('data-old_grwt') || 0;
            adjust_grwt = parseFloat(after_adjust_grwt) - parseFloat(old_grwt);
            if(category_group_id == 1){
                adjust_grwt = round(adjust_grwt, 2).toFixed(3);
            } else if (category_group_id == 2){
                adjust_grwt = round(adjust_grwt, 1).toFixed(3);
            } else {
                adjust_grwt = round(adjust_grwt, 2).toFixed(3);
            }
            
            var after_adjust_less = $('#after_adjust_less').val() || 0;
            var old_less = $('#after_adjust_less').attr('data-old_less') || 0;
            adjust_less = parseFloat(after_adjust_less) - parseFloat(old_less);
            adjust_less = adjust_less.toFixed(3);
            
            if(adjust_grwt < 0){
                adjust_type = 1;
                adjust_less = 0 - adjust_less;
            } else {
                adjust_type = 2;
            }
            adjust_tunch = $('#after_adjust_grwt').attr('data-tunch') || 0;
            adjust_wstg = $('#after_adjust_grwt').attr('data-wstg') || 0;
            adjust_ntwt = parseFloat(adjust_grwt) - parseFloat(adjust_less);
            adjust_ntwt = adjust_ntwt.toFixed(3);
            adjust_fine = parseFloat(adjust_ntwt) * (parseFloat(adjust_tunch) + parseFloat(adjust_wstg)) / 100 || 0;
            adjust_fine = round(adjust_fine, 2).toFixed(3);
            if(category_group_id == 3){
                adjust_ntwt = '0.000';
                adjust_less = '0.000';
                adjust_fine = '0.000';
                adjust_tunch = '0';
            }
            $('#adjust_stock').html("<b>Adjust Stock :</b> <table class='table'><tr><th>Grwt</th><th>Less</th><th>NtWt</th><th>Tunch</th><th>Fine</th></tr><tr><td>" + adjust_grwt + "</td><td>" + adjust_less + "</td><td>" + adjust_ntwt + "</td><td>" + adjust_tunch + "</td><td>" + adjust_fine + "</td></tr></table>");
            
            new_grwt = after_adjust_grwt;
            new_less = after_adjust_less;
            new_tunch = $('#after_adjust_grwt').attr('data-tunch') || 0;
            new_wstg = $('#after_adjust_grwt').attr('data-wstg') || 0;
            new_ntwt = parseFloat(after_adjust_grwt) - parseFloat(after_adjust_less);
            new_ntwt = new_ntwt.toFixed(3);
            new_fine = parseFloat(new_ntwt) * (parseFloat(new_tunch) + parseFloat(new_wstg)) / 100 || 0;
            new_fine = round(new_fine, 2).toFixed(3);
            if(category_group_id == 3){
                new_ntwt = '0.000';
                after_adjust_less = '0.000';
                new_fine = '0.000';
                new_tunch = '0';
            }
            $('#new_stock').html("<b>After Adjust New Stock :</b> <table class='table'><tr><th>Grwt</th><th>Less</th><th>NtWt</th><th>Tunch</th><th>Fine</th></tr><tr><td>" + after_adjust_grwt + "</td><td>" + after_adjust_less + "</td><td>" + new_ntwt + "</td><td>" + new_tunch + "</td><td>" + new_fine + "</td></tr></table>");
        });
        
        $('#stock_adjust_model').on('hidden.bs.modal', function () {
            $('#adjust_grwt').val(0);
            $('#adjust_less').val(0);
            $('#adjust_forcefully').prop('checked', false);
            $('#adjust_stock').html('');
            $('#new_stock').html('');
        });
        
        $(document).on('click', '#stock_adjust_button', function () {
            if ($.trim($("#after_adjust_grwt").val()) == '') {
                show_notify('Please Adjust GrWt.', false);
                $("#after_adjust_grwt").focus();
                return false;
            }
            if(adjust_grwt == 0){
                show_notify("Please Adjust GrWt.", false);
                return false;
            } else if(adjust_grwt < 0){
                adjust_type = 1;
            } else {
                adjust_type = 2;
            }
            
            var old_grwt = $('#after_adjust_grwt').attr('data-old_grwt') || 0;
            var old_less = $('#after_adjust_less').attr('data-old_less') || 0;
            $.ajax({
                url: "<?= base_url('reports/get_item_current_grwt') ?>/" + item_stock_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    var current_grwt = parseFloat(json.grwt).toFixed(3);
                    var current_less = parseFloat(json.less).toFixed(3);
                    if ($('#adjust_forcefully').is(":checked")){
                        current_grwt = old_grwt;
                        current_less = old_less;
                    }
                    if(current_grwt != old_grwt || current_less != old_less){
                        show_notify('Previous Balance changed After you have opened this screen. So, please Click Here to Refresh <a href="<?= base_url('reports/stock_status/') ?>" class="btn btn-primary btn-xs" style="margin: 5px;" ><i class="fa fa-refresh"></i></a>', false);
                        return false;
                    } else {
                        if (confirm('Are you sure, You want to Stock Adjust?')) {
                            if(category_group_id == 3){
                                var line_items_data = [{
                                    "type": adjust_type,
                                    "category_id": category_id,
                                    "item_id": item_id,
                                    "grwt": Math.abs(adjust_grwt),
                                    "rate": '0',
                                    "rate_on": '1',
                                    "amount": '0',
                                }];
                            } else {
                                var line_items_data = [{
                                    "type": adjust_type,
                                    "category_id": category_id,
                                    "item_id": item_id,
                                    "grwt": Math.abs(adjust_grwt),
                                    "less": adjust_less,
                                    "net_wt": Math.abs(adjust_ntwt),
                                    "touch_id": Math.abs(adjust_tunch),
                                    "wstg": Math.abs(adjust_wstg),
                                    "fine": Math.abs(adjust_fine),
                                    "image":"",
                                }];
                            }

                            var lineitem_objectdata_stringify = JSON.stringify(line_items_data);
                            $("#ajax-loader").show();

                            var sell_gold_fine = 0;
                            var sell_silver_fine = 0;
                            if(category_group_id == 1){
                                sell_gold_fine = adjust_fine;
                            } else if (category_group_id == 2){
                                sell_silver_fine = adjust_fine;
                            }
                            if(adjust_type == 1){
                                var gold_fine_total = parseFloat(old_gold_fine_val) - parseFloat(sell_gold_fine);
                                var silver_fine_total = parseFloat(old_silver_fine_val) - parseFloat(sell_silver_fine);
                                sell_gold_fine = Math.abs(sell_gold_fine);
                                sell_silver_fine = Math.abs(sell_silver_fine);
                            } else {
                                var gold_fine_total = parseFloat(old_gold_fine_val) - parseFloat(sell_gold_fine);
                                var silver_fine_total = parseFloat(old_silver_fine_val) - parseFloat(sell_silver_fine);
                                sell_gold_fine = zero_value - parseFloat(sell_gold_fine);
                                sell_silver_fine = zero_value - parseFloat(sell_silver_fine);
                            }
                            var amount_total = old_amount_val;
                            var stock_url = '';

                            if(category_group_id == 3){
                                adjust_grwt = parseFloat(adjust_grwt).toFixed(3);
                                var postData = {
                                    amount_total : amount_total,
                                    account_id : '<?php echo ADJUST_EXPENSE_ACCOUNT_ID; ?>',
                                    department_id : department_id,
                                    other_date : '<?php echo date('d-m-Y'); ?>',
                                    other_remark : 'Stock Adjust',
                                    line_items_data : lineitem_objectdata_stringify,
                                    other_grwt : adjust_grwt,
                                    other_amount : '0',
                                };
                                stock_url = "<?= base_url('other/save_other') ?>";
                            } else {
                                var postData = {
                                    gold_fine_total : gold_fine_total.toFixed(3),
                                    silver_fine_total : silver_fine_total.toFixed(3),
                                    amount_total : amount_total,
                                    account_id : '<?php echo ADJUST_EXPENSE_ACCOUNT_ID; ?>',
                                    process_id : department_id,
                                    sell_date : '<?php echo date('d-m-Y'); ?>',
                                    sell_remark : 'Stock Adjust',
                                    delivery_type : '1',
                                    sell_gold_fine : sell_gold_fine,
                                    sell_silver_fine : sell_silver_fine,
                                    sell_amount : 0,
                                    bill_cr_c_amount : 0,
                                    bill_cr_r_amount : 0,
                                    line_items_data : lineitem_objectdata_stringify,
                                    depart_gold_fine : sell_gold_fine.toFixed(3),
                                    depart_silver_fine : sell_silver_fine.toFixed(3),
                                    pay_rec_amount : amount_total,
                                };
                                stock_url = "<?= base_url('sell/save_sell') ?>";
                            }
                            $.ajax({
                                url: stock_url,
                                type: "POST",
                                data: postData,
                                datatype: 'json',
                                success: function (response) {
                                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                                    var json = $.parseJSON(response);
                                    if (json['error'] == 'Exist') {
                                        show_notify(json['error_exist'], false);
                                    } else if (json['success'] == 'Added') {
                                        show_notify('Stock Adjust Successfully!', true);
                                        table.draw();
                                        $('#stock_adjust_model').modal('hide');
                                    }
                                    $("#ajax-loader").hide();
                                    return false;
                                },

                            });
                        }
                        return false;
                    }
                }
            });
            
        });
        
        table = $('#stock_status_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports/stock_status_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.department_id = $('#department_id').val();
                    d.category_id = $('#category_id').val();
                    d.item_id = $('#item_id').val();
                    d.tunch = $('#tunch').val();
                    d.in_stock = $('input[name="in_stock"]').prop('checked');
                    d.item_wise = $('input[name="item_wise"]').prop('checked');
                    d.include_wstg = $('input[name="include_wstg"]').prop('checked');
                    d.rfid_filter = $('#rfid_filter').val();
//                    d.stock_status_upto = $('#datepicker1').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {
                    "className": "dt-right",
                    "targets": [2,3,4,5,6,7,8,9,10,11],
                },
            ],
            "fnRowCallback": function (nRow, aData) {
                var api = this.api(), data;
                var $nRow = $(nRow);
                var category_text = '';
                if(aData[0] != ''){
                    category_text = aData[0].replace(/(<([^>]+)>)/ig,"");
                }
                var item_text = '';
                if(aData[1] != ''){
                    item_text = aData[1].replace(/(<([^>]+)>)/ig,"");
                }
                var gr_wt_text = '';
                if(aData[2] != ''){
                    gr_wt_text = aData[2].replace(/(<([^>]+)>)/ig,"");
                }
                var tunch_text = '';
                if(aData[5] != ''){
                    tunch_text = aData[5].replace(/(<([^>]+)>)/ig,"");
                }
                var row_unique_text = category_text + item_text + gr_wt_text + tunch_text;
                $nRow.attr("data-row_particular",row_unique_text);
                if(jQuery.inArray(row_unique_text,selected_rows) !== -1) {
                    $nRow.addClass('selected');
                }
                return nRow;
            },
        });
        
        $('#stock_status_table tbody').on( 'click', 'tr', function () {
            if($(this).hasClass('selected') == false) {
                console.log($(this).attr('data-row_particular'));
                selected_rows.push($(this).attr('data-row_particular'));
            } else {
                remove_selected_rows(selected_rows,$(this).attr('data-row_particular'));
            }
            $(this).toggleClass('selected');
        } );
        
        $(document).on('change', '#department_id', function(){
            var department_id = $('#department_id').val();
            if(department_id == '' || department_id === null){
                $('#select2-department_id-container .select2-selection__placeholder').html(' All ');
            }
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('change', '#item_id, #tunch, #in_stock, #item_wise, #datepicker1, #include_wstg', function(){
//            table.columns( [0] ).visible( false, false );
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('change', '#category_id', function(){
            var category_id = $('#category_id').val();
            if(category_id != ' ' && category_id != null){
                $.ajax({
                    url:"<?php echo base_url('new_order/get_item_name'); ?>/" + category_id,
                    type:'GET',
                    data:'',
                    success: function(response){
                        var json = $.parseJSON(response);
                        console.log(json);
                        var row_inc = 1;
                        var option = '';
                        option = '<option value=""> All </option>'
                        $.each(json.items ,function(index, value){
                            option += '<option value="' + value.item_id + '">' + value.item_name + '</option>';
                        });
                        $('#item_id').html(option);
                        row_inc++;
                    }
                });
            }
            table.draw();
        });
        
        $(document).on('change', '#rfid_filter', function(){
            table.draw();
        });
        
        $(document).on('submit', '#create_rfid_form', function () {
            var rfid_grwt = $.trim($("#rfid_grwt").val());
            if (rfid_grwt == '') {
                show_notify('Please Enter Gr. Wt.!', false);
                $("#rfid_grwt").focus();
                $("#real_rfid").val('');
                return false;
            }
            var rfid_item_cur_stock = $.trim($("#rfid_item_cur_stock").val());
            var rfid_stock = $.trim($("#rfid_stock").val());
            var pending_rfid_stock = parseFloat(rfid_item_cur_stock) - parseFloat(rfid_stock);
            if (pending_rfid_stock < rfid_grwt) {
                show_notify('Stock Not Available!', false);
                $("#rfid_grwt").focus();
                return false;
            }
            if ($.trim($("#real_rfid").val()) == '') {
                show_notify('Please Enter Real RFID!', false);
                $("#real_rfid").focus();
                return false;
            }
            var postData = new FormData(this);
            $("#ajax-loader").show();
            $('.create_rfid_btn').attr('disabled', 'disabled');
            $.ajax({
                url: "<?= base_url('reports/create_rfid') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                datatype: 'json',
                async: false,
                success: function (response) {
                    $('.create_rfid_btn').removeAttr('disabled', 'disabled');
                    $("#ajax-loader").hide();
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Exist') {
                        show_notify('This RFID is Already in Used!', false);
                        $("#real_rfid").val('');
                    } else if (json['success'] == 'Added') {
                        show_notify('RFID Created Successfully!', true);
                        var win = window.open('<?php echo base_url('reports/print_item_rfid/'); ?>'+ json['item_stock_rfid_id'], '_blank');
                        if (win) { //Browser has allowed it to be opened
                            win.focus();
                        } else { //Browser has blocked it
                            alert('Please allow popups for this website');
                        }
                        $("#item_stock_rfid_id").val('');
                        $("#rfid_grwt").val('');
                        $("#rfid_less").val('');
                        $("#rfid_add").val('');
                        $("#real_rfid").val('');
                        $("#rfid_size").val('');
                        $("#rfid_charges").val('');
                        $("#rfid_ad_id").val(null).trigger("change");
                        $("#rfid_grwt").focus();
                        created_rfid_table.draw();
                        table.draw();
                    }
                    return false;
                },
            });
            return false;
        });
        
        created_rfid_table = $('#created_rfid_table').DataTable({
            "serverSide": true,
            "scrollY": "300px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "asc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports/get_created_rfid_list') ?>",
                "type": "POST",
                "data": function (d) {
                    d.rfid_item_stock_id = $('#rfid_item_stock_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
                "dataSrc": function ( jsondata ) {
                    if(jsondata.rfid_stock){
                        $('#rfid_stock').val(jsondata.rfid_stock);
                    } else {
                        $('#rfid_stock').val('0');
                    }
                    if(jsondata.rfid_pcs){
                        $('#rfid_pcs').val(jsondata.rfid_pcs);
                    } else {
                        $('#rfid_pcs').val('0');
                    }
                    return jsondata.data;
                }
            },
            "columnDefs": [
                {
                    "className": "dt-right",
                    "targets": [1,2,3,4,5,6,7,8,9],
                },
            ],
        });
    
        $(document).on("click", ".edit_rfid", function () {
            var item_stock_rfid_id = $(this).attr('data-item_stock_rfid_id');
            $.ajax({
                url: "<?php echo site_url('reports/get_created_rfid_data/') ?>" + item_stock_rfid_id,
                type: "POST",
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    if(json.item_stock_rfid.item_stock_rfid_id){
                        $('#item_stock_rfid_id').val(json.item_stock_rfid.item_stock_rfid_id);
                    }
                    if(json.item_stock_rfid.rfid_grwt){
                        $('#rfid_grwt').val(json.item_stock_rfid.rfid_grwt);
                        edit_lineitem_inc = 1;
                        var rfid_stock = $('#rfid_stock').val();
                        var rfid_stock = parseFloat(rfid_stock) - parseFloat(json.item_stock_rfid.rfid_grwt);
                        $('#rfid_stock').val(rfid_stock.toFixed(3));
                    }
                    $('#rfid_less').val('');
                    if(json.item_stock_rfid.rfid_less){
                        $('#rfid_less').val(json.item_stock_rfid.rfid_less);
                    }
                    $('#rfid_add').val('');
                    if(json.item_stock_rfid.rfid_add){
                        $('#rfid_add').val(json.item_stock_rfid.rfid_add);
                    }
                    $('#real_rfid').val('');
                    if(json.item_stock_rfid.real_rfid){
                        $('#real_rfid').val(json.item_stock_rfid.real_rfid);
                    }
                    $('#rfid_size').val('');
                    if(json.item_stock_rfid.rfid_size){
                        $('#rfid_size').val(json.item_stock_rfid.rfid_size);
                    }
                    $('#rfid_charges').val('');
                    if(json.item_stock_rfid.rfid_charges){
                        $('#rfid_charges').val(json.item_stock_rfid.rfid_charges);
                    }
                    if(json.item_stock_rfid.rfid_ad_id){
                        setSelect2Value($("#rfid_ad_id"), "<?= base_url('app/set_ad_name_select2_val_by_id/') ?>" + json.item_stock_rfid.rfid_ad_id);
                    }
                }
            });
        });
        
        $(document).on("click", ".delete_rfid", function () {
            if (confirm('Are you sure delete this RFID?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        created_rfid_table.draw();
                        table.draw();
                        show_notify('RFID Deleted Successfully!', true);
                    }
                });
            }
        });
        
    });
    
    function get_bill_balance(account_id){
        if(account_id != '' && account_id != null){
            $.ajax({
                url: "<?= base_url('sell/get_account_old_balance') ?>/" + account_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    var gold_fine = json.gold_fine;
                    var silver_fine = json.silver_fine;
                    var amount = parseInt(json.amount);
                    old_gold_fine_val = round(gold_fine, 2).toFixed(3);
                    old_silver_fine_val = round(silver_fine, 1).toFixed(3);
                    old_amount_val = amount.toFixed(0);
                }
            });
        }
    }
    
    function remove_selected_rows(array, value) {
        var i = 0;
        while (i < array.length) {
            if(array[i] === value) {
                array.splice(i, 1);
            } else {
                ++i;
            }
        }
        return array;
    }
</script>
