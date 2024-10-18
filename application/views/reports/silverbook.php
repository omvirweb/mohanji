<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Silver Bullion <a href="<?= base_url('reports/silverbook') ?>" class="btn btn-primary btn-xs" style="margin: 5px;" ><i class="fa fa-refresh"></i></a>
        </h1>
    </section>

    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12 form-group">
                                    <div class="col-md-2">
                                        <label>Form Date<span style="color: red">*</span></label>
                                        <input type="text" name="from_date" id="datepicker2" class="form-control from_date" value="<?php echo date("d-m-Y");?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>To Date<span style="color: red">*</span></label>
                                        <input type="text" name="to_date" id="datepicker3" class="form-control to_date" value="<?= date('d-m-Y'); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Account</label>
                                        <select name="account_id" id="account_id" class="form-control select2"></select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Department</label>
                                        <select name="department_id" id="department_id" class="form-control select2"></select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label><br>
                                        <a class="btn btn-primary btn-sm" id="search_button">Search</a>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <div class="col-md-2">
                                        <label>Opening Silver Weight</label>
                                        <b><input type="text" style="text-align:right" name="opening_silver_weight" id="opening_silver_weight" class="form-control" readonly="" val=""></b>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Opening Silver Rate</label>
                                        <b><input type="text" style="text-align:right" name="opening_silver_rate" id="opening_silver_rate" class="form-control" readonly="" val=""></b>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Opening Silver Amount</label>
                                        <b><input type="text" style="text-align:right" name="opening_silver_value" id="opening_silver_value" class="form-control" readonly="" val=""></b>
                                    </div>
                                    <div class="col-md-3">
                                        <label><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></label><br />
                                        <label>Silver Rate : <?php echo $silver_rate; ?></label><br />
                                        <label>Min Silver Rate : <?php echo $silver_min; ?></label><br />
                                        <label>Max Silver Rate : <?php echo $silver_max; ?></label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <meta name="viewport" content="width=device-width, initial-scale=1">
                                        <h4 align="center">Purchase Table</h4>
                                        <table id="purchase_table" align="center" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th class="text-nowrap" width="60px;">Date</th>
                                                    <th>Ref. No.</th>
                                                    <th>Account</th>
                                                    <th>Fine Wt</th>
                                                    <th>P.Rate</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th style="text-align: right;">Total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <meta name="viewport" content="width=device-width, initial-scale=1">
                                        <h4 align="center">Sell Table</h4>
                                        <table id="sell_table" align="center" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th class="text-nowrap" width="60px;">Date</th>
                                                    <th>Ref. No.</th>
                                                    <th>Account</th>
                                                    <th>Fine Wt</th>
                                                    <th>S.Rate</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th style="text-align: right;">Total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table><br>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <div class="col-md-2">
                                        <label>Closing Silver Weight</label>
                                        <b><input type="text" style="text-align:right" readonly="" name="closeing_silver_weight" id="closeing_silver_weight" class="form-control" value=""></b><br>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Closing Silver Rate</label>
                                        <b><input type="text" style="text-align:right" readonly="" name="closeing_silver_rate" id="closeing_silver_rate" class="form-control" value=""></b><br>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Closing Silver Amount</label>
                                        <b><input type="text" style="text-align:right" readonly="" name="closeing_silver_value" id="closeing_silver_value" class="form-control" value=""></b><br>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Date Range Silver Weight</label>
                                        <b><input type="text" style="text-align:right" readonly="" name="date_range_silver_weight" id="date_range_silver_weight" class="form-control" value=""></b><br>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Date Range Silver Rate</label>
                                        <b><input type="text" style="text-align:right" readonly="" name="date_range_silver_rate" id="date_range_silver_rate" class="form-control" value=""></b><br>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Date Range Silver Amount</label>
                                        <b><input type="text" style="text-align:right" readonly="" name="date_range_silver_value" id="date_range_silver_value" class="form-control" value=""></b><br>
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
<script>
    $(document).ready(function () {
        $("#ajax-loader").show();
        $('.select2').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        <?php /* setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>"); */ ?>
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_without_cash_customer_select2_source') ?>");

        var from_date = $('#datepicker2').val();
        var to_date = $('#datepicker3').val();
        get_silverbook_opening_closing_balance(from_date, to_date);

        sell_table = $('#sell_table').DataTable({
            "serverSide": true,
            "scrollY": true,
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports/silverbook_purchase_sell_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.silver_sale_purchase = '1';
                    d.from_date = $('#datepicker2').val();
                    d.to_date = $('#datepicker3').val();
                    d.department_id = $('#department_id').val();
                    d.account_id = $('#account_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {
                    "className": "dt-right",
                    "targets": [4, 5, 6],
                },
                {
                    "targets": [ 1 ],
                    "class": 'text-nowrap'
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                fine_wt_total = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                $(api.column(4).footer()).html(fine_wt_total.toFixed(3));

                amount_total = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                $(api.column(6).footer()).html(amount_total.toFixed(0));
                //Sell rate & Buy Rate Avg
                if(fine_wt_total !== 0 && amount_total !== 0){
                    var avg = (amount_total / fine_wt_total) * 10;
                    $(api.column(5).footer()).html(avg.toFixed(0));
                } else {
                    $(api.column(5).footer()).html('0');
                }
            },
        });

        purchase_table = $('#purchase_table').DataTable({
            "serverSide": true,
            "scrollY": true,
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reports/silverbook_purchase_sell_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.silver_sale_purchase = '2';
                    d.from_date = $('#datepicker2').val();
                    d.to_date = $('#datepicker3').val();
                    d.department_id = $('#department_id').val();
                    d.account_id = $('#account_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {
                    "className": "dt-right",
                    "targets": [4, 5, 6],
                },
                {
                    "targets": [ 1 ],
                    "class": 'text-nowrap'
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                fine_wt_total = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                $(api.column(4).footer()).html(fine_wt_total.toFixed(3));

                amount_total = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                $(api.column(6).footer()).html(amount_total.toFixed(0));
                //Sell rate & Buy Rate Avg
                if(fine_wt_total !== 0 && amount_total !== 0){
                    var avg = (amount_total / fine_wt_total) * 10;
                    $(api.column(5).footer()).html(avg.toFixed(0));
                } else {
                    $(api.column(5).footer()).html('0');
                }
            },
        });

        $(document).on('click', '#search_button', function () {
            if ($.trim($("#datepicker2").val()) == '') {
                show_notify('Please Select From Date!', false);
                $("#datepicker2").focus();
                return false;
            }
            if ($.trim($("#datepicker3").val()) == '') {
                show_notify('Please Select To Date!', false);
                $("#datepicker3").focus();
                return false;
            }
            var from_date = $('#datepicker2').val();
            var to_date = $('#datepicker3').val();
            get_silverbook_opening_closing_balance(from_date, to_date);
            $("#ajax-loader").show();
            purchase_table.draw();
            sell_table.draw();
        });
        
        $(document).on('change', '#department_id', function(){
            var department_id = $('#department_id').val();
            if(department_id == '' || department_id === null){
                $('#select2-department_id-container .select2-selection__placeholder').html(' All ');
            }
        });

    });

    function get_silverbook_opening_closing_balance(from_date, to_date) {
        var department_id = $('#department_id').val();
        var account_id = $('#account_id').val();
        $.ajax({
            url: "<?php echo base_url('reports/get_silverbook_opening_closing_balance'); ?>",
            type: "POST",
            data: {from_date: from_date, to_date: to_date, department_id: department_id, account_id: account_id},
            success: function (response) {
                var json = $.parseJSON(response);
                $('#opening_silver_weight').val(json.opening_silver_weight.toFixed(3));
                $('#opening_silver_rate').val(json.opening_silver_rate.toFixed(0));
                $('#opening_silver_value').val(json.opening_silver_value);
                $('#closeing_silver_weight').val(json.closeing_silver_weight.toFixed(3));
                $('#closeing_silver_rate').val(json.closeing_silver_rate.toFixed(0));
                $('#closeing_silver_value').val(json.closeing_silver_value);
                $('#date_range_silver_weight').val(json.date_range_silver_weight.toFixed(3));
                $('#date_range_silver_rate').val(json.date_range_silver_rate.toFixed(0));
                $('#date_range_silver_value').val(json.date_range_silver_value);
            }
        });
    }

</script>