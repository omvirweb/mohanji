<?php $this->load->view('success_false_notify'); ?>
<style>
    .padding_sm{
        padding-left: 7px;
        padding-right: 7px;
    }
</style>
<div class="content-wrapper" id="body-content">
    <section class="content-header">
        <h1>Import Data</h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <!-- Horizontal Form -->
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <div class="box-title">Import Bank Statement</div>
                            <small><a  href="<?= base_url('assets/sample_csv/DetailedStatement.psv') ?>" download="" target="_blank"> Sample File</a></small>
                            <div class="text-danger blink_div">Currently it's only for ICICI Bank Statement</div>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="get_import_bank_statement_data" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="ibs_bank_id">Select Bank <span class="required-sign">&nbsp;*</span></label>
                                        <select name="ibs_bank_id" id="ibs_bank_id" class="form-control select2"></select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="ibs_department_id">Department <span class="required-sign">&nbsp;*</span></label>
                                        <select name="ibs_department_id" id="ibs_department_id" class="form-control select2" ></select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="ibs_file">Upload File <span class="required-sign">&nbsp;*</span></label>
                                        <input type="file" name="ibs_file" id="ibs_file" class="" >
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary module_save_btn btn-sm" ><b>Import Data</b></button>
                                    </div>
                                </div>
                            </form>
                            <form class="form-horizontal" method="post" id="save_import_bank_statement_data" enctype="multipart/form-data">
                                <div class="row hide" id="import_data_display_div">
                                    <div class="col-md-12">
                                        <h4 id="statement_title" class="text-info"></h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="save_which_data">Save which Data</label>
                                        <select name="save_which_data" id="save_which_data" class="form-control select2">
                                            <option value="1"> All </option>
                                            <option value="2"> Pending </option>
                                            <option value="3"> Allocated </option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Account Name</th>
                                                    <th class="text-right">Dr Tran Amt</th>
                                                    <th class="text-right">Cr Tran Amt</th>
                                                    <th class="">Tran Date</th>
                                                    <th class="">Tran Particular</th>
                                                    <!--<th class="">Inst Num</th>-->
                                                    <!--<th class="">Deposit Branch</th>-->
                                                </tr>
                                            </thead>
                                            <tbody id="ibs_data_list"></tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary module_save_btn btn-sm" ><b>Save Data</b></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-body">
                            <form class="form-horizontal" action="<?= base_url('import/import_data') ?>" method="post" id="import_data" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Select Module</label><br />
                                        <input type="radio" name="type" class="" id="type1" value="1">
                                        <label for="type1"> Opening Import &nbsp;</label><br>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="less">Sample Files</label><br />
                                        <a  href="<?= base_url('assets/sample_csv/opening_import.csv') ?>" target="_blank"> Opening Import &nbsp;</a><br>
                                        <br />
                                    </div>
                                </div>

                                <div class="row"><br>
                                    <div class="col-md-3">
                                        <label for="date">Upload File <span class="required-sign">&nbsp;*</span></label>
                                        <input type="file" name="userfile" id="userfile" class="" ><br />
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary module_save_btn btn-sm" ><b>Save[Ctrl+S]</b></button>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row_duplicate_data"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ibs_inc = '0';
    $(document).ready(function () {
        initAjaxSelect2($("#ibs_bank_id"), "<?= base_url('app/account_bank_select2_source') ?>");
        initAjaxSelect2($("#ibs_department_id"), "<?= base_url('app/department_select2_source') ?>");
        setSelect2Value($("#ibs_department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");

        $('input[type="radio"]').iCheck({
            radioClass: 'iradio_flat-blue'
        });

        $(document).on('submit', '#get_import_bank_statement_data', function () {
            if ($.trim($("#ibs_bank_id").val()) == '') {
                show_notify('Please Select Bank.', false);
                $("#ibs_bank_id").select2('open');
                return false;
            }
            if ($.trim($("#ibs_file").val()) == '') {
                show_notify('Please Select Upload File.', false);
                $("#ibs_file").focus();
                return false;
            }
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('import/get_import_bank_statement_data') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                async: false,
                success: function (response) {
                    var json = $.parseJSON(response);
                    $('#statement_title').html(json.statement_title);
                    var ibs_data_list_html = '';
                    $.each(json.ibs_data, function (index, value) {
                        var bg_color = '';
                        if(value.account_id == '0'){
                            bg_color = 'bg-danger';
                        } else {
                            if(value.journal_id){
                                bg_color = 'bg-red';
                            } else {
                                bg_color = 'bg-info';
                            }
                        }

                        var row_html = '<tr class="lineitem_index_' + index + ' ' + bg_color + '">';
                        row_html += '<td>';
                        row_html += '<select name="ibs_account_id[]" id="ibs_account_id_' + index + '" class="form-control ibs_account_id select2"></select>';
                        if(value.jd_id){
                            row_html += '<input type="hidden" name="jd_id[]" value="' + value.jd_id + '">';
                        }
                        row_html += '<input type="hidden" name="ibs_dr_amount[]" value="' + value.ibs_dr_amount + '">';
                        row_html += '<input type="hidden" name="ibs_cr_amount[]" value="' + value.ibs_cr_amount + '">';
                        row_html += '<input type="hidden" name="journal_date[]" value="' + value.journal_date + '">';
                        row_html += '<input type="hidden" name="ibs_tran_particular[]" value="' + value.ibs_tran_particular + '">';
                        row_html += '<input type="hidden" name="ibs_inst_num[]" value="' + value.ibs_inst_num + '">';
                        row_html += '<input type="hidden" name="ibs_deposit_branch[]" value="' + value.ibs_deposit_branch + '">';
                        row_html += '</td>';
                        row_html += '<td class="text-right">' + value.ibs_dr_amount + '</td>';
                        row_html += '<td class="text-right">' + value.ibs_cr_amount + '</td>';
                        row_html += '<td>' + value.journal_date + '</td>';
                        row_html += '<td>' + value.ibs_tran_particular + '</td>';
//                        row_html += '<td>' + value.ibs_deposit_branch + '</td>';
                        row_html += '</tr>';
                        ibs_data_list_html += row_html;
                    });

                    $('#ibs_data_list').html(ibs_data_list_html);
                    initAjaxSelect2($(".ibs_account_id"), "<?= base_url('app/account_name_with_number_without_department_without_case_customer_select2_source/1') ?>");
                    setTimeout(function () {
                        $.each(json.ibs_data, function (index, value) {
                            $('#ibs_account_id_' + index).val(value.account_id).trigger("change");
                            setSelect2Value($('#ibs_account_id_' + index), "<?= base_url('app/set_account_name_with_number_val_by_id/') ?>" + value.account_id);
                        });
                    }, 2000);
                    $('#save_which_data').select2();
                    $('#import_data_display_div').removeClass('hide');
                    setTimeout(function () {
                        $("#ajax-loader").hide();
                        $('.module_save_btn').removeAttr('disabled', 'disabled');
                    }, 5000);
                    return false;
                },
            });
            return false;
        });

//        var json = $.parseJSON('{"ibs_data_list_html":[{"journal_date":"07-Oct-2019","ibs_tran_particular":"CAM\/68312SRY\/CASH DEP\/07-10-19","ibs_inst_num":"","ibs_dr_amount":"0.00","ibs_cr_amount":"29500.00","ibs_deposit_branch":"JODHPUR SOJATI GATE"},{"journal_date":"07-Oct-2019","ibs_tran_particular":"CAM\/68312SRY\/CASH DEP\/07-10-19","ibs_inst_num":"","ibs_dr_amount":"0.00","ibs_cr_amount":"500.00","ibs_deposit_branch":"JODHPUR SOJATI GATE"},{"journal_date":"09-Oct-2019","ibs_tran_particular":"CLG\/CLG\/783011\/YES\/04.10.2019","ibs_inst_num":"","ibs_dr_amount":"0.00","ibs_cr_amount":"5774.00","ibs_deposit_branch":"DELHI RPC"},{"journal_date":"11-Oct-2019","ibs_tran_particular":"CLG\/SHREE MANOHAR JEWELLERS\/913767\/YES\/07.10.2019","ibs_inst_num":"","ibs_dr_amount":"0.00","ibs_cr_amount":"3552.00","ibs_deposit_branch":"DELHI RPC"},{"journal_date":"05-Nov-2019","ibs_tran_particular":"INF\/NEFT\/021669812411\/LABINDIA       \/UTIB0000061\/ PAYMENT FOR EL","ibs_inst_num":"","ibs_dr_amount":"10625.90","ibs_cr_amount":"0.00","ibs_deposit_branch":"JODHPUR SOJATI GATE"}],"statement_title":"ICICI Bank account Statement from 07-10-2019 to 06-11-2019. <br>683105500387 |    07-OCT-2019 |   B\/F                                                                                                  |                   |                  |                  |         22718.00 |                             <br><br> Closing Balance as on 07-11-2019 03:42:42 is INR.37379.10 includes Uncleared Funds of INR.0.00<br>"}');

        $(document).on('submit', '#save_import_bank_statement_data', function () {
            var ibs_bank_id = $("#ibs_bank_id").val();
            if (ibs_bank_id == '' || ibs_bank_id == null) {
                show_notify('Please Select Bank.', false);
                $("#ibs_bank_id").select2('open');
                return false;
            }
            var ibs_department_id = $("#ibs_department_id").val();
            if (ibs_department_id == '' || ibs_department_id == null) {
                show_notify('Please Select Department.', false);
                $("#ibs_department_id").select2('open');
                return false;
            }
//            $('select[name="ibs_account_id[]"]').each(function() {
//                if ($(this).val() == '' || $(this).val() == null) {
//                  show_notify('Please Select all Accounts.', false);
//                  return false;
//                }
//            });
            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            if(ibs_inc === '0'){
                ibs_inc = '1';
                var postData = new FormData(this);
                postData.append('ibs_bank_id', ibs_bank_id);
                postData.append('ibs_department_id', ibs_department_id);
                $.ajax({
                    url: "<?= base_url('import/save_import_bank_statement_data') ?>",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: postData,
                    async: false,
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('Please Select all Accounts!', false);
                        } else if (json['success'] == 'Added') {
                            window.location.href = "<?php echo base_url('import/import') ?>";
                        } else {
                            show_notify('Someing is wrong!', false);
                        }
                        $("#ajax-loader").hide();
                        $('.module_save_btn').removeAttr('disabled', 'disabled');
                        return false;
                    },
                });
            }
            return false;
        });

        $(document).on('submit', '#import_data', function () {
            if (!$('input[name=type]:checked').val()) {
                show_notify('Please Select Module.', false);
                $("#type1").focus();
                return false;
            }
            if ($.trim($("#userfile").val()) == '') {
                show_notify('Please Select Upload File.', false);
                $("#userfile").focus();
                return false;
            }

//            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('import/import_data') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Exist') {
                        show_notify(json['error_exist'], false);
                        $('.row_duplicate_data').html(json['error_exist'] + '<br><span class="text-danger text-bold">So, Please below row numbers data from Excel or Database (from Opening Stock Master) ( And Before Import again 1st refresh page )</span><br>File Row Numbers : <span class="text-info text-bold">' + json['row_duplicate_data'] + '</span>');
                        $('.module_save_btn').removeAttr('disabled', 'disabled');
                    } else if (json['error'] == 'File is Improper') {
                        show_notify(json['error'] + ', Please check all field data and then import <br>( And Before Import again 1st refresh page )', false);
                        $('.row_duplicate_data').html(json['error'] + ',<span class="text-danger text-bold"> Please check all field data and then import <br>( And Before Import again 1st refresh page )</span>');
                        $('.module_save_btn').removeAttr('disabled', 'disabled');
                    } else if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('import/import') ?>";
                    } else {
                        window.location.href = "<?php echo base_url('import/import') ?>";
                    }
                    return false;
                },
            });
            return false;
        });
    });
</script>
