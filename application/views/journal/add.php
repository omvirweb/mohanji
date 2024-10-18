<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('journal/save_journal') ?>" method="post" id="save_journal" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($journal_data->journal_id) && !empty($journal_data->journal_id)) { ?>
            <input type="hidden" name="journal_id" class="journal_id" value="<?= $journal_data->journal_id ?>">
        <?php } ?>
            <input type="hidden" name="total_naam" class="total_naam" id="total_naam_db" value="0">
            <input type="hidden" name="total_jama" class="total_jama" id="total_jama_db" value="0">
            <input type="hidden" name="jd_id" id="jd_id">
            <input type="hidden" name="deleted_jd_ids" id="deleted_jd_ids">
            <input type="hidden" id="credit_limit" value="0">
        <!-- Content Header (Page header) -->
     
        <section class="content-header">
            <h1>
                Journal Entry
                <?php $isEdit = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "edit");
                $isView = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "view");
                $isAdd = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "add");
                $allow_change_date = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "allow_change_date"); ?>
                <?php if (isset($journal_data->journal_id) && !empty($journal_data->journal_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                    <?php if(isset($journal_data->relation_id) && !empty($journal_data->relation_id)){ } else { ?>
                        <?php if(!isset($journal_data->audit_status) || (isset($journal_data->audit_status) && $journal_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($journal_data->journal_id) ? '' : $btn_disable;?>><?= isset($journal_data->journal_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                        <?php } ?>
                    <?php } ?>
                <?php if($isView){ ?>
                    <a href="<?= base_url('journal/journal_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Journal List</a>
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
                                    <?php if (isset($journal_data->journal_id) && !empty($journal_data->journal_id)) { ?>
                                        <div class="col-md-1">
                                            <label for="journal_no">Journal No.</label>
                                            <input type="text" name="journal_no" id="journal_no" class="form-control" readonly value="<?php echo $journal_data->journal_id; ?>"><br />
                                        </div>
                                    <?php } ?>
                                        <div class="col-md-3">
                                            <label for="department_id">Department<span class="required-sign">&nbsp;*</span></label>
                                            <select name="department_id" id="department_id" class="form-control select2" ></select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date">Date</label>
                                            <input type="text" name="journal_date" id="datepicker2" class="<?= !empty($allow_change_date) ? '' : 'disable_datepicker'; ?> form-control input-datepicker" <?= !empty($allow_change_date) ? '' : 'readonly'; ?> value="<?= (isset($journal_data->journal_date)) ? date('d-m-Y', strtotime($journal_data->journal_date)) : date('d-m-Y'); ?>">
                                        </div>
                                        <div class="clearfix"></div>
                                    <div class="line_item_form item_fields_div">
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <?php if(isset($journal_data)){ ?>
                                            <input type="hidden" name="line_items_data[jd_id]" id="lineitem_id" />
                                        <?php } ?>
                                        <h4 class="col-md-12"></h4>
                                        <div class="col-md-3">
                                            <label for="type">Select Type<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[type]" class="form-control type" id="type">
                                                <option value=""> - Select - </option>
                                                <option value="1"<?= isset($type) && $type == 1 ? 'selected="selected"' : ''; ?>>Naam (Dr)</option>
                                                <option value="2"<?= isset($type) && $type == 2 ? 'selected="selected"' : ''; ?>>Jama (Cr)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="account_id">Select Account<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[account_id]" class="form-control account_id select2" id="account_id">
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="amount">Amount<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[amount]" class="form-control num_only amount" id="amount"  placeholder="" value=""><br />
                                        </div>
                                        <div class="col-md-2"  >
                                            <label for="narration">Narration</label>
                                            <textarea name="line_items_data[narration]" class="form-control" id="narration" placeholder=""></textarea><br />
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <input type="button" id="add_lineitem" class="btn btn-info btn-sm add_lineitem" value="Add" style="margin-top: 21px;"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-10">
                                        <table style="" class="table custom-table item-table">
                                            <thead>
                                                <tr>
                                                    <th width="100px">Action</th>
                                                    <th>Account Name</th>
                                                    <th class="text-right">Naam (Dr)</th>
                                                    <th class="text-right">Jama (Cr)</th>
                                                    <th class="">Narration</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lineitem_list"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total:</th>
                                                    <th></th>
                                                    <th class="text-right" id="total_naam"></th>
                                                    <th class="text-right" id="total_jama"></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <?php if (isset($journal_data->journal_id) && !empty($journal_data->journal_id)) { ?>
                                    <div class="created_updated_info" style="margin-left: 15px;">
                                        Created by : <?= isset($journal_data->created_by_name) ? $journal_data->created_by_name : '' ?>
                                        @ <?= isset($journal_data->created_at) ? date ('d-m-Y h:i A', strtotime($journal_data->created_at)) : '' ?><br/>
                                        Updated by : <?= isset($journal_data->updated_by_name) ? $journal_data->updated_by_name : '' ?>
                                        @ <?= isset($journal_data->updated_at) ? date('d-m-Y h:i A', strtotime($journal_data->updated_at)) :'' ; ?>
                                    </div>
                                    <?php } ?>
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
    var journal_index = '';
    <?php if (isset($journal_detail)) { ?>
        var li_lineitem_objectdata = [<?php echo $journal_detail; ?>];
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
        
        if ($('#datepicker2').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker2').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: "today",
                maxDate: 0,
            })
        }

        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_with_number_without_department_without_case_customer_select2_source/1') ?>");
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        <?php if (isset($journal_data->department_id)) { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $journal_data->department_id) ?>");
        <?php } else { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
        <?php } ?>
        <?php if (isset($journal_detail->account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_select2_val_by_id/' . $journal_detail->account_id) ?>");
        <?php } ?>
        
        $("#type").select2();

        $(document).on('change', '#account_id', function(){
            var account_id = $('#account_id').val();
            if (account_id != '' && account_id != null) {
                get_account_fine(account_id);
            } else {
                $('#credit_limit').val('0');
            }
        });
        
        <?php if(isset($journal_data->relation_id) && !empty($journal_data->relation_id)){ } else { ?>
        <?php if(!isset($journal_data->audit_status) || (isset($journal_data->audit_status) && $journal_data->audit_status != AUDIT_STATUS_AUDITED)){ ?>
            $(document).bind("keydown", function(e){
                if(e.ctrlKey && e.which == 83){
                    e.preventDefault();
                    if(module_submit_flag == 0 ){
                        $("#save_journal").submit();
                        return false;
                    }
                }
            });
        <?php } ?>
        <?php } ?>

        $(document).on('submit', '#save_journal', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#department_id").val()) == '') {
                show_notify('Please Select Department Name.', false);
                $("#department_id").select2('open');
                return false;
            }
            if (lineitem_objectdata == '') {
                show_notify("Please Add Item.", false);
                return false;
            }
            var total_naam = $('#total_naam_db').val();
            var total_jama = $('#total_jama_db').val();

            total_naam = round(total_naam);
            total_jama = round(total_jama);
            
            if(total_naam != total_jama){
                show_notify('Total Naam And Total Jama Should be same.', false);
                return false;
            }

            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            $.ajax({
                url: "<?= base_url('journal/save_journal') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                async: false,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Something went Wrong') {
                        $("#ajax-loader").hide();
                        show_notify('Something went Wrong! Please Refresh page and Go ahead.', false);
                    } else if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('journal/journal_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('journal/journal_list') ?>";
                    }
                    return false;
                },
            });
            module_submit_flag = 1;
            return false;
        });

        $('#add_lineitem').on('click', function () {
            var type = $("#type").val();
            
            if (type == '' || type == null) {
                $("#type").select2('open');
                show_notify("Please select Type!", false);
                return false;
            }
            var account_id = $("#account_id").val();
            if (account_id == '' || account_id == null) {
                $("#account_id").select2('open');
                show_notify("Please select Account!", false);
                return false;
            }
            var amount = $("#amount").val();
            if (amount == '' || amount == null) {
                $("#amount").focus();
                show_notify('Amount is required!', false);
                return false;
            }
            var credit_limit = $('#credit_limit').val();
            var amount = $("#amount").val();
            var type = $('#type').val();
            var is_grater = 0;

            if((parseFloat(amount) > parseFloat(credit_limit)) && type == 1) {
                is_grater = 1;
            }
            if(is_grater == 1){
//                swal({
//                    title: "Amount Exceed Credit Limit. Are you sure you want to save?",
//                    type: "warning",
//                    buttons: true,
//                    className: "danger_alert"
//                }).then((willSave) => {
//                    if (willSave) {
//                    alert('will save');
//                        save_lineitem();
//                    }
//                });
                if (confirm('Amount Exceed Credit Limit. Are you sure you want to save?')) {
                    save_lineitem();
                }
            }
            else {
                save_lineitem();
            }
        });
        
    });
    
    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        var total_naam = 0;
        var total_jama = 0;

        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';   
            if(value.type == '1'){
                total_naam = total_naam + parseFloat(value.amount);
            }
            if(value.type == '2'){
                    total_jama = total_jama + parseFloat(value.amount); 
            }
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_j_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td>' + value.account_name + '</td>';
            
                    if(value.type == '1'){
                        row_html += '<td class="text-right">' + value.amount + '</td>';
                    }
                    else{
                        row_html += '<td></td>';
                    }
                    if(value.type == '2'){
                        row_html += '<td class="text-right">' + value.amount + '</td>';
                    }
                    else{
                        row_html += '<td></td>';
                    }
                    row_html += '<td>' + value.narration + '</td>';
            new_lineitem_html += row_html;
        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#total_naam_db').val(total_naam);
        $('#total_jama_db').val(total_jama);
        $('#total_naam').html(total_naam);
        $('#total_jama').html(total_jama);
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_j_item").addClass('hide');
        journal_index = index;
        if (edit_lineitem_inc == 0) {
            edit_lineitem_inc = 1;
            $(".add_lineitem").removeAttr("disabled");
        }
        var value = lineitem_objectdata[index];

        var jd_id = value.jd_id;
        $('#jd_id').val(jd_id);
        $("#line_items_index").val(index);
        if(typeof(value.id) != "undefined" && value.id !== null) {
                $("#lineitem_id").val(value.id);
        }
        $("#type").val(value.type).trigger("change");
        $("#account_id").val(null).trigger("change");
        setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/') ?>" + value.account_id);
        $("#amount").val(value.amount);
        $("#narration").val(value.narration);
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        if (confirm('Are you sure ?')) {
            value = lineitem_objectdata[index];
            var jd_id = value.jd_id;
            var deleted_jd_ids = $('#deleted_jd_ids').val();
            if(deleted_jd_ids != ''){
                deleted_jd_ids += ', '+jd_id;
                $('#deleted_jd_ids').val(deleted_jd_ids);
            }
            else{
                $('#deleted_jd_ids').val(jd_id);
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

    function get_account_fine(account_id){
        if(account_id != '' && account_id != null){
            $.ajax({
                url: "<?= base_url('sell/get_account_old_balance') ?>/" + account_id,
                type: 'GET',
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    if(json.credit_limit == null){
                        json.credit_limit = 0;
                    }
                    $('#credit_limit').val(json.effective_credit_limit);
                }
            });
        } else {
            $('#credit_limit').val('0');
        }
    }
    
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
                if (value.type == type && value.account_id == account_id && typeof (value.id) != "undefined" && value.id !== null) {
                    $('input[name^="line_items_data"]').each(function (index) {
                        keys = $(this).attr('name');
                        keys = keys.replace("line_items_data[", "");
                        keys = keys.replace("]", "");
                        if (keys == 'id') {
                            if (value.id != $(this).val()) {
                                is_validate = '1';
                                show_notify("You cannot Add this Item. This Item has been used!", false);
                                $("#add_lineitem").removeAttr('disabled', 'disabled');
                                return false;
                            }
                        }
                    });
                } else if (value.type == type && value.account_id == account_id) {
                    if(journal_index !== index){
                        is_validate = '1';
                        show_notify("You cannot Add this Item. This Item has been used!", false);
                        $("#add_lineitem").removeAttr('disabled', 'disabled');
                        return false;
                    }
                }
            });
            if (is_validate == '1') {
                return false;
            }
        });
        if (is_validate != '1') {
            var account_data = $('#account_id option:selected').html();

            lineitem['account_name'] = account_data;
            var jd_id = $('#jd_id').val();
            lineitem['jd_id'] = jd_id;
            $('#jd_id').val('');
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));

            var line_items_index = $("#line_items_index").val();
            if (line_items_index != '') {
                lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
            } else {
                lineitem_objectdata.push(new_lineitem);
            } 
            display_lineitem_html(lineitem_objectdata);
            $('#lineitem_id').val('');
            $("#account_id").val(null).trigger("change");
            $("#type").val(null).trigger("change");
            $("#amount").val('');
            $("#narration").val('');
            $("#line_items_index").val('');
            if (on_save_add_edit_item == 1) {
                on_save_add_edit_item == 0;
                $('#save_journal').submit();
            }
            edit_lineitem_inc = 0;
        }
        $("#add_lineitem").removeAttr('disabled', 'disabled');
    }
    
</script>
