<?php $this->load->view('success_false_notify'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Stock Check</h1>
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
                                                <?php foreach ($category as $value) { ?>
                                                    <option value="<?= $value->category_id; ?>"><?= $value->category_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Item</label>
                                            <select id="item_id" class="form-control select2">
                                                <option value="0"> All </option>
                                                <?php foreach ($items as $value) { ?>
                                                    <option value="<?= $value->item_id; ?>"><?= $value->item_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Tunch</label>
                                            <select id="tunch" class="form-control select2">
                                                <option value="0"> All </option>
                                                <?php foreach ($carat as $value) { ?>
                                                    <option value="<?= $value->purity; ?>"><?= $value->purity; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <br/>
                                            <button name="search" id="search" class="btn btn-primary btn-sm pull-left" autocomplete="off"><span class="fa fa-search-plus"></span> Search</button>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-3">
                                            <label for="rfid_number">Enter RFID</label>
                                            <input type="text" name="rfid_number" id="rfid_number" class="form-control">
                                        </div>
                                        <div class="col-md-9">
                                            <label for="re_check">&nbsp;</label><br>
                                            <input type="button" name="re_check" id="re_check" class="btn btn-primary btn-sm pull-right" value="Re Check">
                                        </div>
                                        <div class="clearfix"></div><br>
                                        <div class="col-md-1">
                                            <label for="">Result : </label>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="result_status"></span>
                                        </div>
                                        <div class="col-md-2">
                                            Gr.Wt. : <span class="result_rfid_grwt"></span>
                                        </div>
                                        <div class="col-md-2">
                                            Total Checked Gr.wt. : <span class="result_total_grwt label label-info" style="font-size: 18px;">0</span>
                                        </div>
                                        <div class="clearfix"></div><br>
                                        <div class="col-md-6">
                                            <h4>Checked</h4>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-right" width="80px"></th>
                                                        <th class="text-right"></th>
                                                        <th class="text-right">Total</th>
                                                        <th class="text-right"><span id="rfid_checked_total_grwt">0</span></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right" width="80px">Sr. No.</th>
                                                        <th class="text-right">RFID ID</th>
                                                        <th class="text-right">RFID</th>
                                                        <th class="text-right">Gr.Wt.</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="rfid_stock_checked_list"></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>Not Checked</h4>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"></th>
                                                        <th class="text-right" width="80px"></th>
                                                        <th class="text-right"></th>
                                                        <th class="text-right">Total</th>
                                                        <th class="text-right"><span id="rfid_not_checked_total_grwt">0</span></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Checked?</th>
                                                        <th class="text-right" width="80px">Sr. No.</th>
                                                        <th class="text-right">RFID ID</th>
                                                        <th class="text-right">RFID</th>
                                                        <th class="text-right">Gr.Wt.</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="rfid_stock_not_checked_list"></tbody>
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
</div>
<script type="text/javascript">
    var result_total_grwt = 0;
    var rfid_checked_total_grwt = 0;
    var rfid_not_checked_total_grwt = 0;
    var rfid_stock_checked_in_stock_objectdata = [];
    var rfid_stock_checked_wrong_place_objectdata = [];
    var rfid_stock_checked_new_rfid_objectdata = [];
    var rfid_stock_not_checked_objectdata = [];
    $(document).ready(function () {
//        $("#ajax-loader").show();
        $('.select2').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
        
        $(document).on('change', '#department_id', function(){
            var department_id = $('#department_id').val();
            if(department_id == '' || department_id === null){
                $('#select2-department_id-container .select2-selection__placeholder').html(' All ');
            }
        });
        
        $(document).on('change', '#item_id, #tunch', function(){
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
        });
        
        $(document).on('click', '#search', function(){
            $('#ajax-loader').show();
            var department_id = $('#department_id').val();
            var category_id = $('#category_id').val();
            var item_id = $('#item_id').val();
            var tunch = $('#tunch').val();
            $.ajax({
                url: "<?php echo base_url('reports/get_rfid_data_list'); ?>/",
                type: 'POST',
                async: false,
                data: {department_id : department_id, category_id : category_id, item_id : item_id, tunch : tunch},
                success: function (response) {
                    var rfid_rows = $.parseJSON(response);
                    if(rfid_rows){
                        rfid_stock_not_checked_objectdata = [];
                        $.each(rfid_rows, function (index, value) {
                            var lineitem = {};
                            lineitem['item_stock_rfid_id'] = value.item_stock_rfid_id;
                            lineitem['rfid_number'] = value.rfid_number;
                            lineitem['rfid_grwt'] = value.rfid_grwt;
                            lineitem['rfid_checked'] = value.rfid_checked;
                            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
                            rfid_stock_not_checked_objectdata.push(new_lineitem);
                        });
                    } else {
                        $('.rfid_grwt').html('');
                    }
                    display_rfid_not_checked_html(rfid_stock_not_checked_objectdata);
                    rfid_stock_checked_in_stock_objectdata = [];
                    rfid_stock_checked_wrong_place_objectdata = [];
                    rfid_stock_checked_new_rfid_objectdata = [];
                    display_rfid_checked_html();
                    result_total_grwt = 0;
                    $('.result_status').html('');
                    $('.result_rfid_grwt').html('');
                    $('.result_total_grwt').html('0');
                    $('#ajax-loader').hide();
                }
            });
        });
        
        $(document).on('keypress', '#rfid_number', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                
                if (rfid_stock_not_checked_objectdata == '' && rfid_stock_checked_in_stock_objectdata == '') {
                    show_notify("Please Click on Search to get RFID Numbers!", false);
                    $("#rfid_number").val('');
                    $('#rfid_number').focus();
                    return false;
                }
                
                var rfid_number = $('#rfid_number').val().toLowerCase();
                for (var line_i = 0; line_i < rfid_stock_checked_in_stock_objectdata.length; ++line_i) {
                    var itemrow = rfid_stock_checked_in_stock_objectdata[line_i];
                    var itemrow_rfid_number = itemrow.rfid_number;
                    itemrow_rfid_number = itemrow_rfid_number.toLowerCase()
                    if(itemrow_rfid_number == rfid_number){
                        show_notify("RFID Entered!", false);
                        $("#rfid_number").val('');
                        $('#rfid_number').focus();
                        return false;
                    }
                }
                for (var line_i = 0; line_i < rfid_stock_checked_wrong_place_objectdata.length; ++line_i) {
                    var itemrow = rfid_stock_checked_wrong_place_objectdata[line_i];
                    var itemrow_rfid_number = itemrow.rfid_number;
                    itemrow_rfid_number = itemrow_rfid_number.toLowerCase()
                    if(itemrow_rfid_number == rfid_number){
                        show_notify("RFID Entered!", false);
                        $("#rfid_number").val('');
                        $('#rfid_number').focus();
                        return false;
                    }
                }
                for (var line_i = 0; line_i < rfid_stock_checked_new_rfid_objectdata.length; ++line_i) {
                    var itemrow = rfid_stock_checked_new_rfid_objectdata[line_i];
                    var itemrow_rfid_number = itemrow.rfid_number;
                    itemrow_rfid_number = itemrow_rfid_number.toLowerCase()
                    if(itemrow_rfid_number == rfid_number){
                        show_notify("RFID Entered!", false);
                        $("#rfid_number").val('');
                        $('#rfid_number').focus();
                        return false;
                    }
                }
                
                for (var line_i = 0; line_i < rfid_stock_not_checked_objectdata.length; ++line_i) {
                    var itemrow = rfid_stock_not_checked_objectdata[line_i];
                    var itemrow_rfid_number = itemrow.rfid_number;
                    itemrow_rfid_number = itemrow_rfid_number.toLowerCase()
                    if(itemrow_rfid_number == rfid_number){
                        rfid_stock_not_checked_objectdata.splice(line_i, 1);
                        display_rfid_not_checked_html(rfid_stock_not_checked_objectdata);
                        itemrow['result_status'] = 'In Stock';
                        rfid_stock_checked_in_stock_objectdata.push(itemrow);
                        display_rfid_checked_html();
                        
                        $('.result_status').html('<span class="label label-success">In Stock</span>');
                        $('.result_rfid_grwt').html(itemrow.rfid_grwt);
                        result_total_grwt = result_total_grwt + parseFloat(itemrow.rfid_grwt);
                        $('.result_total_grwt').html(result_total_grwt.toFixed(3));
                        
                        $("#rfid_number").val('');
                        $('#rfid_number').focus();
                        return false;
                    }
                }
                
                $.ajax({
                    url: "<?php echo base_url('reports/get_rfid_row_for_stock_check'); ?>/",
                    type: 'POST',
                    async: false,
                    data: {rfid_number : rfid_number},
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $('#rfid_number').val('');
                        $('#rfid_number').focus();
                        var lineitem = {};
                        lineitem['item_stock_rfid_id'] = json.item_stock_rfid_id;
                        lineitem['rfid_number'] = json.rfid_number;
                        lineitem['rfid_grwt'] = json.rfid_grwt;
                        lineitem['result_status'] = json.result_status;
                        var new_lineitem = JSON.parse(JSON.stringify(lineitem));
                        if(json.result_status == 'Wrong Place'){
                            $('.result_status').html('<span class="label label-warning">Wrong Place</span>');
                            rfid_stock_checked_wrong_place_objectdata.push(new_lineitem);
                        } else {
                            $('.result_status').html('<span class="label label-danger">New RFID</span>');
                            rfid_stock_checked_new_rfid_objectdata.push(new_lineitem);
                        }
                        $('.result_rfid_grwt').html(json.rfid_grwt);
                        display_rfid_checked_html();
                    }
                });
                e.preventDefault();
                return false;
            }
        });
        
        $(document).on('click', '.rfid_checked', function(){
            var index_val = $(this).attr('data-index_val');
            var itemrow = rfid_stock_not_checked_objectdata[index_val];
            rfid_stock_not_checked_objectdata.splice(index_val, 1);
            display_rfid_not_checked_html(rfid_stock_not_checked_objectdata);
            itemrow['result_status'] = 'In Stock';
            rfid_stock_checked_in_stock_objectdata.push(itemrow);
            display_rfid_checked_html();
            
            $('.result_status').html('<span class="label label-success">In Stock</span>');
            $('.result_rfid_grwt').html(itemrow.rfid_grwt);
            result_total_grwt = result_total_grwt + parseFloat(itemrow.rfid_grwt);
            $('.result_total_grwt').html(result_total_grwt.toFixed(3));
        });
        
        $(document).on('click', '#re_check', function(){
            $('#search').click();
            rfid_stock_checked_in_stock_objectdata = [];
            rfid_stock_checked_wrong_place_objectdata = [];
            rfid_stock_checked_new_rfid_objectdata = [];
            display_rfid_checked_html();
            
            result_total_grwt = 0;
            $('.result_status').html('');
            $('.result_rfid_grwt').html('');
            $('.result_total_grwt').html('0');
        });
    });
    
    function display_rfid_not_checked_html(rfid_stock_not_checked_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        rfid_not_checked_total_grwt = 0;
        $.each(rfid_stock_not_checked_objectdata, function (index, value) {
            var row_html = '<tr class="index_' + index + ' ">' +
                    '<td class="text-center" ><i class="fa fa-reply btn btn-success btn-xs rfid_checked" data-index_val="'+ index + '" ></td>' +
                    '<td class="text-right" >'+ (index + 1) +'</td>' +
                    '<td class="text-right" >' + value.item_stock_rfid_id + '</td>' +
                    '<td class="text-right" >' + value.rfid_number + '</td>' +
                    '<td class="text-right" >' + value.rfid_grwt + '</td>' +
                    '</tr>';
            new_lineitem_html += row_html;
            rfid_not_checked_total_grwt = rfid_not_checked_total_grwt + parseFloat(value.rfid_grwt);
        });
        $('#rfid_not_checked_total_grwt').html(rfid_not_checked_total_grwt.toFixed(3));
        $('tbody#rfid_stock_not_checked_list').html(new_lineitem_html);
        $('#ajax-loader').hide();
    }
    
    function display_rfid_checked_html() {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        rfid_checked_total_grwt = 0;
        var n_length = rfid_stock_checked_new_rfid_objectdata.length;
        for(var n_i=n_length-1; n_i>=0; n_i--){
            var row_html = '<tr class="index_' + n_i + ' ">' +
                    '<td class="text-right" >'+ (n_i + 1) +'</td>' +
                    '<td class="text-right" ></td>' +
                    '<td class="text-right" >' + rfid_stock_checked_new_rfid_objectdata[n_i].rfid_number + '</td>' +
                    '<td class="text-right" >' + rfid_stock_checked_new_rfid_objectdata[n_i].rfid_grwt + '</td>' +
                    '<td class="text-center" ><span class="label label-danger">New RFID</span>' +
                    '</tr>';
            new_lineitem_html += row_html;
            rfid_checked_total_grwt = rfid_checked_total_grwt + parseFloat(rfid_stock_checked_new_rfid_objectdata[n_i].rfid_grwt);
        }
        
        new_lineitem_html += '<tr><td colspan="4"></td></tr>';
        
        var w_length = rfid_stock_checked_wrong_place_objectdata.length;
        for(var w_i=w_length-1; w_i>=0; w_i--){
            var row_html = '<tr class="index_' + w_i + ' ">' +
                    '<td class="text-right" >'+ (w_i + 1) +'</td>' +
                    '<td class="text-right" >' + rfid_stock_checked_wrong_place_objectdata[w_i].item_stock_rfid_id + '</td>' +
                    '<td class="text-right" >' + rfid_stock_checked_wrong_place_objectdata[w_i].rfid_number + '</td>' +
                    '<td class="text-right" >' + rfid_stock_checked_wrong_place_objectdata[w_i].rfid_grwt + '</td>' +
                    '<td class="text-center" ><span class="label label-warning">Wrong Place</span></td>' +
                    '</tr>';
            new_lineitem_html += row_html;
            rfid_checked_total_grwt = rfid_checked_total_grwt + parseFloat(rfid_stock_checked_wrong_place_objectdata[w_i].rfid_grwt);
        }
        
        new_lineitem_html += '<tr><td colspan="4"></td></tr>';
        
        var s_length = rfid_stock_checked_in_stock_objectdata.length;
        for(var s_i=s_length-1; s_i>=0; s_i--){
            var row_html = '<tr class="index_' + s_i + ' ">' +
                    '<td class="text-right" >'+ (s_i + 1) +'</td>' +
                    '<td class="text-right" >' + rfid_stock_checked_in_stock_objectdata[s_i].item_stock_rfid_id + '</td>' +
                    '<td class="text-right" >' + rfid_stock_checked_in_stock_objectdata[s_i].rfid_number + '</td>' +
                    '<td class="text-right" >' + rfid_stock_checked_in_stock_objectdata[s_i].rfid_grwt + '</td>' +
                    '<td class="text-center" ><span class="label label-success">In Stock</span></td>' +
                    '</tr>';
            new_lineitem_html += row_html;
            rfid_checked_total_grwt = rfid_checked_total_grwt + parseFloat(rfid_stock_checked_in_stock_objectdata[s_i].rfid_grwt);
        }
        
        $('#rfid_checked_total_grwt').html(rfid_checked_total_grwt.toFixed(3));
        $('tbody#rfid_stock_checked_list').html(new_lineitem_html);
        $('#ajax-loader').hide();
    }
</script>
